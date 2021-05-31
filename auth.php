<?php

class Auth {
    private static $instance;
	private $database;

	public function __construct() {
		$this->cookieLogin();
	}

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new Auth;
        }

        return self::$instance;
    }

	public function login($user, $password, $remember) {
		// Get the user from the database
		$res = Database::query('SELECT * FROM users WHERE username = ?', $user)->get_result();

		if ($res->num_rows > 0) {
			$user = $res->fetch_assoc();
			// Check the password
			if (password_verify($password, $user['password'])) {
				// Set the session
                unset($user['password']);
                // TODO break into methods, hide password hash from user array
				$_SESSION['user'] = $user;

				// If the user wants a persistent session, generate a cookie for later authentication
				if ($remember) {
					$this->generateSessionCookie($user['id']);
				}

				return "Login succesful";
			}
		}

		return "Invalid login";
	}

	// Check if a username is available for registration 
	private function isNameAvailable($user) {
		$stmt = Database::query('SELECT username FROM users WHERE username = ?', $user);

		return $stmt->get_result()->num_rows == 0;
	}

	public function register($user, $password, $passwordConfirm) {
		// Make sure the name is available
		if (!$this->isNameAvailable($user)) {
			return "Name is taken!";
		}

		// Make sure password and password confirmation match
		if ($password !== $passwordConfirm) {
			return "Password confirmation does not match!";
		}

		// Minimum password length
		if (strlen($password) < 5) {
			return "Password must be at least 5 characters in length!";
		}

		// Hash the password
		$passwordHash = password_hash($password, PASSWORD_DEFAULT);

		// Insert the new user, and log them in
		Database::query('INSERT INTO users (username, password) VALUES (?, ?)', $user, $passwordHash);
		$this->login($user, $password, false);

		return "Registration sucessful";
	}

	private function cookieLogin() {
		// If the user is not logged in, and an auth cookie is set
		if (empty($_SESSION['user']) && !empty($_COOKIE['authsess'])) {
			// Get the cookie
			list($selector, $authenticator) = explode(':', $_COOKIE['authsess']);

			// Get the session token for this cookie
			$res = Database::query('SELECT * FROM auth_tokens WHERE selector = ?', $selector)->get_result();

			if ($res->num_rows == 1) {
				$row = $res->fetch_assoc();

				// Check if the token is valid				
				if (hash_equals($row['token'], hash('sha256', base64_decode($authenticator)))) {
					$userId = $row['user_id'];

					// Get the user from the cookies user_id
					$res = Database::query('SELECT * FROM users WHERE id = ?', $userId)->get_result();
					
					if ($res->num_rows == 1) {
						$user = $res->fetch_assoc();
						// Log the user in
						$_SESSION['user'] = $user;
						// Invalidate the old token and generate a new, for next use
						$this->generateSessionCookie($userId, $selector);
					}
				}
			}
		}
	}

	private function generateSessionCookie($userId, $oldSelector = null) {
		if ($oldSelector != null) {
			Database::query('DELETE FROM auth_tokens WHERE selector = ?', $oldSelector)->close();
		}

		// 2 week expire time for the session / cookie
		$time = time() + 30 * 24 * 60 * 60;
		$authenticator = random_bytes(33);
		$expires = date('Y-m-d\TH:i:s', $time);
		$selector = base64_encode(random_bytes(9));
		$authenticatorHash = hash('sha256', $authenticator);

		$cookie = setcookie(
			'authsess',
			$selector . ':' . base64_encode($authenticator),
			$time,
			'/',
			'',
			false,
			true
		);

		Database::query('INSERT INTO auth_tokens (selector, token, user_id, expires) VALUES (?, ?, ?, ?)', $selector, $authenticatorHash, $userId, $expires);
	}
}
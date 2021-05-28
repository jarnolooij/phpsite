<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Register</title>
	</head>
	<body>
		<h3>{{ echo flash('REG_MESSAGE'); }}</h3>
		<form action="/auth/register" method="POST">
			<label for="name">Username</label>
			<input type="text" name="username" id="name">

			<label for="password">Password</label>
			<input type="password" name="password" id="password">

			<label for="password_confirm">Confirm password</label>
			<input type="password" name="password_confirm" id="password_confirm">

			<input type="submit" value="Register">
		</form>
		<a href="/login">Already have an account?</a>
	</body>	
</html>
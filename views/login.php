<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Login</title>
	</head>
	<body>
		<h3>{{ echo flash('LOGIN_MESSAGE'); }}</h3>
		<form action="/auth/login" method="POST">
			<label for="name">Username</label>
			<input type="text" name="username" id="name">

			<label for="password">Password</label>
			<input type="password" name="password" id="password">
			<label for="remember">Remember login</label> 

			<input type="checkbox" name="remember" id="remember">
			<input type="submit" value="Login">
		</form>
		<a href="/register">Sign up for an account</a>
	</body>
</html>
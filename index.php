<?php
	session_start();
?>
<!doctype html>
<html lang="en">
<head>
	<title>The Coding Dojo Crimewatch</title>
	<meta charset="utf-8">
	<style type="text/css">
		* {font-family: sans-serif;}
		.center {text-align: center;}
		.signup {display: inline-block; vertical-align: top; width: 480px; height: 500px; border: solid 1px black;}
		.signin {display: inline-block; vertical-align: top; width: 480px; height: 500px; border: solid 1px black;}
		.block {display: block;}
		.index_messages {width: 460px; margin: 0px auto; height: 200px; overflow: scroll;}
		.green {color: green;}
		.red {color: red;}
	</style>
</head>
<body>
	<h1 class="center">Welcome to the Codingdojo Crime Watch!</h1>
	<h3 class="center">Hide your bikes, hide your flipflops, and eat some bacon</h3>
	<div class="signup">
		<h2 class="center">Sign Up</h2>
		<form action="process.php" method="post">
			<label class="block">First Name: <input type="text" name="first_name"></label>
			<label class="block">Last Name: <input type="text" name="last_name"></label>
			<label class="block">Email: <input type="text" name="email"></label>
			<label class="block">Password <input type="password" name="password"></label>
			<label class="block">Confirm <input type="password" name="confirm_password"></label>
			<input type="submit" value="Register">
			<input type="hidden" name="action" value="register">
		</form>
		<div class="index_messages">
			<?php
				if(isset($_SESSION['errors'])){
					echo $_SESSION['errors'];
					unset($_SESSION['errors']);
				}
				if(isset($_SESSION['success_message'])){
					echo $_SESSION['success_message'];
					unset($_SESSION['success_message']);
				}
			?>
		</div><!-- end of index_messages DIV inside signup DIV -->
	</div><!-- end of signup DIV -->
	<div class="signin">
		<h2 class="center">Sign In</h2>
		<form action="process.php" method="post">
			<label class="block">Email: <input type="text" name="email"></label>
			<label class="block">Password <input type="password" name="password"></label>
			<input type="submit" value="Login">
			<input type="hidden" name="action" value="login">
		</form>
		<div class="index_messages">
			<?php
				if(isset($_SESSION['errors_login'])){
					echo $_SESSION['errors_login'];
					unset($_SESSION['errors_login']);
				}
			?>
		</div><!-- end of index_messages DIV inside signin DIV -->
	</div><!-- end of signin DIV -->
</body>
</html>
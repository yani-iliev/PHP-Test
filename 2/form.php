<?php
/**
 * A very simple login form
 * @version 0.1
 * @author Yani Iliev <yani.iliev@cspath.com>
 */

// start the session
require_once("session.php");

// check if the user is already logged in
if( isset( $_SESSION["logged_in"] ) && $_SESSION["logged_in"] === true ) {
	// user is already logged in, redirect the user to the member area
	header('Location: member.php');
}
// else display the login form
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"> 
		<title>Login</title>
		<link href="css/style.1.css" type="text/css" rel="stylesheet"> 
	</head>
	<body>
		<div id="holder">
			<div id="login_error">
			</div>
			<form>
				<div class="row">
					<label for="username">Username:</label>
					<input type="text" name="username" id="username" />
				</div>
				<div class="row">
					<label for="password">Password:</label>
					<input type="password" name="password" id="password" />
				</div>
				<div class="lastrow">
					<input type="checkbox" name="remember" id="remember" /><label for="remember">Remember Me</label>
					<input type="button" name="login"  value="Log In" id="login" style="float: right" />
				</div>
				<div style="clear:both;"></div>
			</form>
		</div>
		<!-- LOAD JQUERY -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
		<!-- LOAD blockUI plugin-->
		<script src="js/jquery.blockUI.js"></script>
		<!-- LOAD LOGIN SCRIPT -->
		<script src="js/login.js"></script>
	</body>
</html>
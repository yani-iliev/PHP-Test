<?php
/**
 * A very simple page that is accessible only to the logged in user
 * @version 0.1
 * @author Yani Iliev <yani.iliev@cspath.com>
 */

// start the session
require_once("session.php");

// check to see if the user is logged in
if( ! isset( $_SESSION["logged_in"] ) || $_SESSION["logged_in"] !== true ) {
	// user is not logged in, redirect to the login page
	header('Location: form.php');
}
// user is logged in display the holy grail
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"> 
		<title></title>
		<style>
		BODY {
			margin: 0;
			padding: 0;
		}
		.centered {
			margin: 100px auto;
			text-align: center;
		}
		</style>
	</head>
	<body>
		<div class="centered">
			<img src="images/pbox.jpg" width="300" height="224" alt="Pandora box" />
		</div>
	</body>
</html>
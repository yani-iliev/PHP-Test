<?php
/**
 * A very simple form handler
 * @version 0.1
 * @author Yani Iliev <yani.iliev@cspath.com>
 */
// include configuration and start session
require_once("config.php");
require_once("session.php");

// The api accepts both POST and GET requests
$data = $_REQUEST;

// check to see if the user wants to login
if( isset( $data["login"] ) ) {
	// this array will hold all errors that are
	// encountered during the login process
	// and once the error checking ends
	// the errors are shown to the user
	$errors = array();
	
	// check t see if the username is passed and it is not empty
	if( ! isset( $data["username"] ) || empty( $data["username"] ) ) {
		// username hasn't been passed or is empty. assign an error
		$errors[] = "Username cannot be empty!";
	}
	// check to see if the password is passed and is not an empty string
	if( ! isset( $data["password"] ) || empty( $data["password"] ) ) {
		// password hasn't been passed or is empty, let the user know
		$errors[] = "Password cannot be empty!";
	}
	
	// if there are errors, notify the user and abort execution
	if( count( $errors ) > 0 ) {
		outputErrors($errors);
	}
	
	// create a new mysql connection
	$link = mysql_connect( DB_SERVER, DB_USER, DB_PASSWORD ) or outputErrors( "Unfortunately the server is down. 
																																						Try again later." );
																																						
  // Select the database
	mysql_select_db( DB_NAME, $link );
	
	$username = $data["username"];
	// encrypt the password in md5
	$password = md5( $data["password"] );
	
	// escape the user data and create a query
	$query = sprintf( "SELECT password FROM ".TABLE_USERS." WHERE username='%s'",
	            			mysql_real_escape_string($username) );
	
	// run the query
	$res = mysql_query( $query	, $link );
	
	// check to see if there is a match
	if( ! $res || ( mysql_numrows( $res ) < 1 ) ) {
		// no match, notify the user
		$errors[] = "Invalid username.";
	}
	// there is a match - time to fetch the user data
	$row = mysql_fetch_array($res);
	
	// the passwords match ?
	if( $row["password"] == $password ) {
		// set logged_in to true and set the user id
		$_SESSION["uid"] = $row[0];
		$_SESSION["logged_in"] = true;
		// notify the script that the login was successful
		echo json_encode( array( "error_flag" => 0 ) );
		exit( 0 );
	} else {
		// oops your password doesn't match
		// notify the user
		$errors[] = "Password is incorrect.";
		outputErrors($errors);
	}
}

// outputs data in json format
// sets the error_flag
function outputErrors( $errors ){
	echo json_encode( array( "error_flag" => 1, "errors" => $errors ) );
	exit( 0 );
}

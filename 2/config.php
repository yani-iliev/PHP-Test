<?php
/**
 * Simple configuration file
 * @version 0.1
 * @author Yani Iliev <yani.iliev@cspath.com>
 */
// set this to your DB server
$db_server = "localhost";
if(!defined("DB_SERVER")) define("DB_SERVER", $db_server);

// set this to your DB user
$db_user = "root";
if(!defined("DB_USER")) define("DB_USER", $db_user);

// set this to your DB password
$db_pass = "";
if(!defined("DB_PASSWORD")) define("DB_PASSWORD", $db_pass);

// set this to your DB name
$db_name = "test";
if(!defined("DB_NAME")) define("DB_NAME", $db_name);

// set this to the name of your sessions table
$table_users = "members";
if(!defined("TABLE_USERS")) define("TABLE_USERS", $table_users);

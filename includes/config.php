<?php
$dbuser = "root";
$dbpass = "mysqlpassword@123";
$host = "localhost";
$db = "hostel";

// Create connection
$mysqli = new mysqli($host, $dbuser, $dbpass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set the character set to UTF-8
$mysqli->set_charset("utf8");
?>

<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_portal1";

$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
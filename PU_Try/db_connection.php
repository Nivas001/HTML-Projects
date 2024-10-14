<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hall_booking";

$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>

<?php
$servername = "localhost";
$username = "root"; // Change as needed
$password = ""; // Change as needed
$dbname = "contact_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

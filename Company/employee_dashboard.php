<?php
session_start();
if ($_SESSION['role'] != 'employee') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
</head>
<body>
    <h2>Welcome, Employee</h2>
    <h3>Admin Details:</h3>


    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "company_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT name FROM users WHERE role='admin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Admin: " . $row["name"] . "<br>";
        }
    } else {
        echo "No admins found.";
    }

    $conn->close();
    ?>
</body>
</html>

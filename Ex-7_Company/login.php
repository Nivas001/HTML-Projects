<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['role'] = $row['role'];
            $_SESSION['username'] = $row['username'];

            if ($row['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: employee_dashboard.php");
            }
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found!";
    }
}
$conn->close();
?>

<?php
require 'config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database with name
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "Registration successful! You can now log in.";
        header("Location: login.html"); // Redirect to login page
        exit();
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>

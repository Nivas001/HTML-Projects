<?php
session_start();
require 'config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input (use email instead of username)
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the database by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Bind email to query
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If user found, verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php"); // Admin view all employees
        } else {
            header("Location: employee_dashboard.php"); // Employee view admin
        }
        exit();
    } else {
        echo "Invalid email or password!";
    }
}
?>

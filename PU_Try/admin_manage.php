<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_portal";

$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Handle form submissions for adding, updating, and deleting records
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $role = $_POST['role'];

        $sql = "INSERT INTO Users (username, password, email, role) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssss", $username, $password, $email, $role);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>User added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } elseif (isset($_POST['update_user'])) {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $sql = "UPDATE Users SET username = ?, email = ?, role = ? WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $role, $user_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>User updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } elseif (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];

        $sql = "DELETE FROM Users WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>User deleted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    // Similarly handle Rooms and Bookings tables
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manage - Pondicherry University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Admin Management</h2>

        <!-- User Management -->
        <h3>User Management</h3>
        <form method="POST">
            <h4>Add User</h4>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="Professor">Professor</option>
                    <option value="Assistant Professor">Assistant Professor</option>
                    <option value="HOD">HOD</option>
                    <option value="Dean">Dean</option>
                    <option value="Registrar">Registrar</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
        </form>

        <!-- User Update and Delete Section -->
        <h4 class="mt-5">Update/Delete User</h4>
        <form method="POST">
            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <input type="location" class="form-control" id="user_id" name="user_id" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">New Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">New Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">New Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="">Select Role</option>
                    <option value="Professor">Professor</option>
                    <option value="Assistant Professor">Assistant Professor</option>
                    <option value="HOD">HOD</option>
                    <option value="Dean">Dean</option>
                    <option value="Registrar">Registrar</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <button type="submit" name="update_user" class="btn btn-secondary">Update User</button>
            <button type="submit" name="delete_user" class="btn btn-danger">Delete User</button>
        </form>

        <!-- Similarly add forms for Rooms and Bookings management -->

    </div>
</body>
</html>

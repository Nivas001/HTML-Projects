<?php
session_start();

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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input fields
    if (empty($username) || empty($password)) {
        echo "Username and password are required!";
        exit();
    }

    // Prepare SQL to fetch user details
    $sql = "SELECT user_id, username, password, role FROM Users WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $db_password)) {
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $role;

            // Redirect to the page they were trying to access or the dashboard
            if (isset($_SESSION['redirect_to'])) {
                $redirect_page = $_SESSION['redirect_to'];
                unset($_SESSION['redirect_to']);  // Clear the redirect session
                header("Location: $redirect_page");
            } else {
                header("Location: index.php");  // Default to dashboard
            }
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No user found with that username!";
    }

    // Close the statement and connection
    $stmt->close();
    $db->close();
}
?>

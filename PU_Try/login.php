<?php
session_start();

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "university_portal";

$db = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input fields
    if (empty($username) || empty($password)) {
        $error_msg = "Username and password are required!";
    } else {
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
                    unset($_SESSION['redirect_to']);
                    header("Location: $redirect_page");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error_msg = "Incorrect username or password!";
            }
        } else {
            $error_msg = "Incorrect username or password!";
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Pondicherry University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: 80px auto;
        }

        .login-container h3 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .login-container .form-control {
            height: 45px;
            font-size: 14px;
        }


        .login-container p {
            margin-top: 15px;
            text-align: center;
        }

        .login-container p a {
            color: #007bff;
            text-decoration: none;
        }

        .login-container p a:hover {
            text-decoration: underline;
        }

        .alert {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3>Login</h3>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-1">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?><br>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>
</body>
</html>

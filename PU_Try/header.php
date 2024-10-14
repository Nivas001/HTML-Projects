<?php
$loggedIn = isset($_SESSION['role']);
$user_role = $loggedIn ? $_SESSION['role'] : null;
// Check if the user is logged in by checking if a session variable is set
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondicherry University Hall Booking System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        header {
            color: white;
            background-color: white;
        }
        .header-container {
            background-color: #4C5594; 
            display: flex;
            justify-content: space-between;
            align-items: start;
            padding: 10px 20px;
        }
        .logo {
            height: 50px;
        }
        .heading {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .user-options {
            background-color:#ffffff; 
            font-size: 16px;
            text-align: right;
            padding: 2px 20px;
            color: #000000;       
        }
        .user-options a {
            color: white;
            text-decoration: none;
            margin-left: 10px;
            color: #000000;
        }
        .nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-top:30px;
            border-bottom: 5px solid #7680c9;
        }
        .nav a {
            background-color: #ffffff;
            color: black;
            text-decoration: none;
            margin-left: 15px;
            font-size: 16px;
            padding: 8px 12px;
            border:2px solid #7680c9;
            border-radius: 10px 10px 0px 0px;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .nav a:hover {
            color: #7680c9;
            background-color: #f0f0f0;
        }
        .nav a.active {
            background-color: #7680c9;
            color: #ffffff;
        }
    </style>
</head>
<body>

<header>
    <div class="header-container">
        <div class="logo">
            <img src="img/pu_logo.png" alt="Pondicherry University Logo" class="logo">
        </div>
        <div class="heading">
            <center>Hall Booking System<center>
        </div>
    </div>
    <div class="user-options">
        <a href="#">Account</a> &nbsp&nbsp&nbsp| 
        <?php if ($loggedIn): ?>
            <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                    <?php endif; ?>
    </div>
    <div class="nav">
<?php
    $current_page = basename($_SERVER['PHP_SELF']);
?>

            <a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">Hall Booking</a>
            <a href="view_bookings.php" class="<?= ($current_page == 'view_bookings.php') ? 'active' : '' ?>">View / Modify / Cancel Bookings</a>
                    <?php if ($user_role == 'Admin'): ?>
                        <a href="admin.php" class="<?= ($current_page == 'admin.php') ? 'active' : '' ?>">Admin Panel</a>
                    <?php endif; ?>
                    <?php if ($user_role == 'Dean'): ?>
                        <a href="status_update.php" class="<?= ($current_page == 'status_update.php') ? 'active' : '' ?>">Approve/Reject Booking</a>
                        <a href="update_hall.php" class="<?= ($current_page == 'update_hall.php') ? 'active' : '' ?>">Seminar Hall Details</a>                    
                        <?php endif; ?>
                    
        </div>
</header>

</body>
</html>

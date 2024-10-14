<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    exit("Unauthorized access");
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

// Handle room deletion
if (isset($_GET['delete'])) {
    $hall_id = $_GET['delete'];
    $delete_sql = "DELETE FROM Rooms WHERE hall_id = ?";
    $delete_stmt = $db->prepare($delete_sql);
    
    if ($delete_stmt) {
        $delete_stmt->bind_param("i", $hall_id);
        
        if ($delete_stmt->execute()) {
            echo "<script>alert('Room deleted successfully!'); window.location.href = 'manage_rooms.php';</script>";
        } else {
            echo "<script>alert('Failed to delete room.');</script>";
        }
        
        $delete_stmt->close();
    } else {
        echo "<script>alert('Failed to prepare delete statement.');</script>";
    }
}

// Fetch rooms and department names
$sql = "SELECT Rooms.hall_id, Rooms.room_name, Rooms.capacity, Rooms.location, Departments.department_name 
        FROM Rooms 
        LEFT JOIN Departments ON Rooms.department_id = Departments.department_id";
$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand">Pondicherry University</a>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Admin Panel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_users.php">Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_bookings.php">Manage Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_room.php">Add Room</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Manage Rooms</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Room Name</th>
                <th>Capacity</th>
                <th>Location</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['room_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                        <td>
                            <a href="edit_room.php?hall_id=<?php echo urlencode($row['hall_id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="manage_rooms.php?delete=<?php echo urlencode($row['hall_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No rooms found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$db->close();
?>

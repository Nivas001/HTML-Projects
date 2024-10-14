<?php
session_start();
// Ensure session is started and user is logged in as Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    exit("Unauthorized access");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_portal";

$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $hall_id = $_POST['hall_id'];
    $room_name = $_POST['room_name'];
    $capacity = $_POST['capacity'];
    $location = $_POST['location'];
    $department_id = $_POST['department_id'];

    // Prepare and execute the SQL query
    $sql = "UPDATE Rooms SET room_name = ?, capacity = ?, location = ?, department_id = ? WHERE hall_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("siiii", $room_name, $capacity, $location, $department_id, $hall_id);

    if ($stmt->execute()) {
        echo "<script>alert('Room updated successfully!'); window.location.href = 'manage_rooms.php';</script>";
    } else {
        echo "<script>alert('Failed to update room.');</script>";
    }

    // Close connections
    $stmt->close();
    $db->close();
    exit();
}

// Fetch room details for the given hall_id
if (isset($_GET['hall_id'])) {
    $hall_id = $_GET['hall_id'];
    $sql = "SELECT hall_id, room_name, capacity, location, department_id FROM Rooms WHERE hall_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $hall_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        exit("Room not found");
    }

    $stmt->bind_result($hall_id, $room_name, $capacity, $location, $department_id);
    $stmt->fetch();
    $stmt->close();
} else {
    exit("Invalid room ID");
}

// Fetch departments for the department dropdown
$departments_sql = "SELECT department_id, department_name FROM Departments";
$departments_result = $db->query($departments_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
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
                    <a class="nav-link" href="manage_rooms.php">Manage Rooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Edit Room</h2>
    <form method="POST" action="edit_room.php">
        <input type="hidden" name="hall_id" value="<?php echo htmlspecialchars($hall_id); ?>">
        <div class="form-group">
            <label for="room_name">Room Name:</label>
            <input type="text" class="form-control" id="room_name" name="room_name" value="<?php echo htmlspecialchars($room_name); ?>" required>
        </div>
        <div class="form-group">
            <label for="capacity">Capacity:</label>
            <input type="location" class="form-control" id="capacity" name="capacity" value="<?php echo htmlspecialchars($capacity); ?>" required>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="location" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
        </div>
        <div class="form-group">
            <label for="department_id">Department:</label>
            <select class="form-control" id="department_id" name="department_id" required>
                <?php while ($dept_row = $departments_result->fetch_assoc()): ?>
                    <option value="<?php echo $dept_row['department_id']; ?>" <?php echo $department_id == $dept_row['department_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($dept_row['department_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Room</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$db->close();
?>

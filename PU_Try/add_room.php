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

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch departments from the database
$departments = [];
$sql = "SELECT department_id, department_name FROM Departments";
$result = $db->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $room_name = $_POST['room_name'];
    $capacity = $_POST['capacity'];
    $location = $_POST['location'];
    $department_id = $_POST['department_id'];

    // Prepare and execute the SQL query
    $sql = "INSERT INTO Rooms (room_name, capacity, location, department_id) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("siii", $room_name, $capacity, $location, $department_id);

    if ($stmt->execute()) {
        echo "<script>alert('Room added successfully!'); window.location.href = 'manage_rooms.php';</script>";
    } else {
        echo "<script>alert('Failed to add room.');</script>";
    }

    // Close connections
    $stmt->close();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
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
    <h2>Add Room</h2>
    <form method="POST" action="add_room.php">
        <div class="form-group">
            <label for="room_name">Room Name:</label>
            <input type="text" class="form-control" id="room_name" name="room_name" required>
        </div>
        <div class="form-group">
            <label for="capacity">Capacity:</label>
            <input type="location" class="form-control" id="capacity" name="capacity" required>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="location" class="form-control" id="location" name="location" required>
        </div>
        <div class="form-group">
            <label for="department_id">Department:</label>
            <select class="form-control" id="department_id" name="department_id" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?php echo $department['department_id']; ?>">
                        <?php echo $department['department_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Room</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

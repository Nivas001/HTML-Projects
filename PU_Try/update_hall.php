<?php
session_start();
include 'db_connection.php'; // Use the existing connection file
// Ensure user is logged in and has the role of Dean
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Dean') {
    header("Location: login.php");
    exit();
}

// Fetch Dean's department_id
$dean_id = $_SESSION['user_id'];
$sql_dean_department = "SELECT department_id FROM Users WHERE user_id = ?";
$stmt = $db->prepare($sql_dean_department);
$stmt->bind_param("i", $dean_id);
$stmt->execute();
$stmt->bind_result($dean_department_id);
$stmt->fetch();
$stmt->close();

// Initialize message variable
$message = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['hall_id']) && is_array($_POST['hall_id'])) {
        foreach ($_POST['hall_id'] as $index => $hall_id) {
            $room_name = trim($_POST['room_name'][$index]);
            $capacity = (int)$_POST['capacity'][$index];
            $location = (int)$_POST['location'][$index];

            // Basic validation
            if (empty($room_name) || $capacity <= 0 || $location <= 0) {
                $message[] = '<div class="alert alert-warning" role="alert">Invalid input for room ID ' . htmlspecialchars($hall_id) . '.</div>';
                continue; // Skip this iteration
            }

            // Update room details
            $sql_update = "UPDATE venue 
                           SET room_name = ?, capacity = ?, location = ? 
                           WHERE hall_id = ? 
                           AND department_id = ?";
            $stmt = $db->prepare($sql_update);
            $stmt->bind_param("siiii", $room_name, $capacity, $location, $hall_id, $dean_department_id);

            if ($stmt->execute()) {
                $message[] = '<div class="alert alert-success" role="alert">Room ID ' . htmlspecialchars($hall_id) . ' details updated successfully.</div>';
            } else {
                $message[] = '<div class="alert alert-danger" role="alert">Error updating room ID ' . htmlspecialchars($hall_id) . ': ' . htmlspecialchars($stmt->error) . '</div>';
            }

            $stmt->close();
        }
    } else {
        $message[] = '<div class="alert alert-warning" role="alert">No room data found to update.</div>';
    }
}

// Fetch room details for the Dean's department
$sql_rooms = "SELECT * FROM venue WHERE department_id = ?";
$stmt = $db->prepare($sql_rooms);
$stmt->bind_param("ii", $dean_department_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Seminar Hall Details - Pondicherry University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class='navbar navbar-expand-lg navbar-light bg-light'>
    <div class='container'>
        <a class='navbar-brand' href='index.php'>Pondicherry University</a>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
        </button>
        <div class='collapse navbar-collapse' id='navbarNav'>
            <ul class='navbar-nav'>
                <li class='nav-item'><a class='nav-link' href='index.php'>Home</a></li>
                <li class='nav-item'><a class='nav-link' href='my_bookings.php'>My Bookings</a></li>
                <?php if ($_SESSION['role'] == 'Dean'): ?>
                    <li class='nav-item'><a class='nav-link' href='admin.php'>Admin Panel</a></li>
                    <li class='nav-item'><a class='nav-link' href='update_hall.php'>Seminar Hall Details</a></li>
                    <li class='nav-item'><a class='nav-link' href='status_update.php'>Approve/Reject Booking</a></li>
                <?php endif; ?>
                <li class='nav-item'><a class='nav-link' href='logout.php'>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1>Update Seminar Hall Details</h1>

    <!-- Display messages below the form -->
    <?php foreach ($message as $msg): ?>
        <?php echo $msg; ?>
    <?php endforeach; ?>

    <!-- Room Details Form -->
    <form action="update_hall.php" method="post">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Capacity</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="text" name="room_name[]" value="<?php echo htmlspecialchars($row['room_name']); ?>" required></td>
                            <td><input type="number" name="capacity[]" value="<?php echo htmlspecialchars($row['capacity']); ?>" required></td>
                            <td><input type="text" name="location[]" value="<?php echo htmlspecialchars($row['location']); ?>" required></td>
                            <input type="hidden" name="hall_id[]" value="<?php echo htmlspecialchars($row['hall_id']); ?>">
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3">No rooms available to update.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Update All Rooms</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$db->close();
?>

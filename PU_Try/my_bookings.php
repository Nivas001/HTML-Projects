<?php
// Ensure session is started and user is logged in
session_start();
include 'db_connection.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch user's bookings
$user_id = $_SESSION['user_id'];
$sql = "SELECT r.room_name, b.booking_id, b.booking_date, b.start_time, b.end_time, b.status
        FROM bookings b
        JOIN seminar_halls r ON b.hall_id = r.hall_id
        WHERE b.user_id = ?";
$stmt = $db->prepare($sql);

if ($stmt === false) {
    die("Failed to prepare SQL statement: " . $db->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>My Bookings</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>
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

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                    <li class='nav-item'><a class='nav-link' href='admin.php'>Admin Panel</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Dean'): ?>
                    <li class='nav-item'><a class='nav-link' href='update_hall.php'>Seminar Hall Details</a></li>
                    <li class='nav-item'><a class='nav-link' href='status_update.php'>Approve/Reject Booking</a></li>
                <?php endif; ?>

                <li class='nav-item'><a class='nav-link' href='logout.php'>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class='container mt-5'>
    <h2>My Bookings</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Booking Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['room_name']) ?></td>
                        <td><?= date('Y-m-d', strtotime($row['booking_date'])) ?></td>
                        <td><?= date('h:i A', strtotime($row['start_time'])) ?></td>
                        <td><?= date('h:i A', strtotime($row['end_time'])) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'Pending'): ?>
                                <form action="cancel_booking.php" method="post">
                                    <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                </form>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no bookings.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$db->close();
?>

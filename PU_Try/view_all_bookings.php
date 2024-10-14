<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
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

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Handle booking status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['new_status'];

    $update_sql = "UPDATE Bookings SET status = ? WHERE booking_id = ?";
    $stmt = $db->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $booking_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Booking status updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Prepare SQL based on role and filters
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

if ($role == 'Dean') {
    $sql = "SELECT b.booking_id, r.room_name, b.booking_date, b.time_slot, b.status
            FROM Bookings b
            JOIN Rooms r ON b.hall_id = r.hall_id
            WHERE r.room_name LIKE '%Seminar%'";

    if ($filter_date) {
        $sql .= " AND b.booking_date = ?";
    }
    if ($filter_status) {
        $sql .= " AND b.status = ?";
    }
    $sql .= " ORDER BY b.booking_date DESC, b.time_slot";
} else if ($role == 'Admin') {
    $sql = "SELECT b.booking_id, r.room_name, b.booking_date, b.time_slot, b.status
            FROM Bookings b
            JOIN Rooms r ON b.hall_id = r.hall_id";

    if ($filter_date) {
        $sql .= " WHERE b.booking_date = ?";
    }
    if ($filter_status) {
        $sql .= ($filter_date ? " AND" : " WHERE") . " b.status = ?";
    }
    $sql .= " ORDER BY b.booking_date DESC, b.time_slot";
}

$stmt = $db->prepare($sql);

if ($filter_date && $filter_status) {
    $stmt->bind_param("ss", $filter_date, $filter_status);
} else if ($filter_date) {
    $stmt->bind_param("s", $filter_date);
} else if ($filter_status) {
    $stmt->bind_param("s", $filter_status);
}

$stmt->execute();
$result = $stmt->get_result();

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Bookings - Pondicherry University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">View All Bookings</h2>
        <form class="row g-3 mb-4" method="GET">
            <div class="col-md-4">
                <label for="date" class="form-label">Filter by Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($filter_date) ?>">
            </div>
            <div class="col-md-4">
                <label for="status" class="form-label">Filter by Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" <?= $filter_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $filter_status == 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= $filter_status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary w-100">Apply Filters</button>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Booking Date</th>
                    <th>Time Slot</th>
                    <th>Status</th>
                    <?php if ($role == 'Admin' || $role == 'Dean'): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['room_name']) ?></td>
                            <td><?= htmlspecialchars($row['booking_date']) ?></td>
                            <td><?= htmlspecialchars($row['time_slot']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                            <?php if ($role == 'Admin' || $role == 'Dean'): ?>
                                <td>
                                    <form action="view_all_bookings.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($row['booking_id']) ?>">
                                        <select name="new_status" class="form-select form-select-sm" required>
                                            <option value="">Change Status</option>
                                            <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="approved" <?= $row['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="rejected" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= $role == 'Admin' || $role == 'Dean' ? '7' : '6' ?>" class="text-center">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hall_id = $_POST['hall_id'];
    $user_id = $_SESSION['user_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Check if room is available
    $sql = "SELECT * FROM Bookings WHERE hall_id = ? AND ((start_time <= ? AND end_time >= ?) OR (start_time <= ? AND end_time >= ?))";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("issss", $hall_id, $end_time, $start_time, $start_time, $end_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Room is already booked for the selected time slot.']);
    } else {
        $status = 'Pending';
        $sql = "INSERT INTO Bookings (hall_id, user_id, start_time, end_time, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iisss", $hall_id, $user_id, $start_time, $end_time, $status);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Booking successful!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }
        $stmt->close();
    }
}

$db->close();
?>

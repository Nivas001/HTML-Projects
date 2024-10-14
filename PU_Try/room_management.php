<?php
session_start();

// Ensure user is authenticated
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    exit(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_portal";

$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    exit(json_encode(['success' => false, 'message' => 'Connection failed: ' . $db->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $hall_id = filter_input(INPUT_POST, 'hall_id', FILTER_VALIDATE_INT);
    $room_name = filter_input(INPUT_POST, 'room_name', FILTER_SANITIZE_STRING);
    $capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
    $location = filter_input(INPUT_POST, 'location', FILTER_VALIDATE_INT);

    if ($hall_id === false || $capacity === false || $location === false || empty($room_name)) {
        exit(json_encode(['success' => false, 'message' => 'Invalid input.']));
    }

    $sql = "UPDATE Rooms SET room_name = ?, capacity = ?, location = ? WHERE hall_id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        exit(json_encode(['success' => false, 'message' => 'SQL statement preparation failed: ' . $db->error]));
    }

    $stmt->bind_param("siii", $room_name, $capacity, $location, $hall_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Room details updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
}

$db->close();
?>

<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $hall_id = $_POST['hall_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $user_id = $_POST['user_id'];

    // Validate user ID
    $stmt = $db->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 0) {
        echo json_encode(["error" => "Invalid User ID."]);
        exit;
    }
    $stmt->close();

    // Determine room type based on hall_id
    $room_type = '';
    $room_types = ['auditoriums', 'seminar_halls', 'lecture_hall_rooms'];
    
    foreach ($room_types as $type) {
        $stmt = $db->prepare("SELECT hall_id FROM $type WHERE hall_id = ?");
        $stmt->bind_param("i", $hall_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $room_type = $type;
            break;
        }
        $stmt->close();
    }

    if ($room_type === '') {
        echo json_encode(["error" => "Room ID not found."]);
        exit;
    }

    // Check for overlapping bookings
    $stmt = $db->prepare("SELECT * FROM bookings WHERE hall_id = ? AND booking_date = ? AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))");
    $stmt->bind_param("isssss", $hall_id, $booking_date, $end_time, $start_time, $end_time, $start_time);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["error" => "Room is already booked during this time."]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Insert booking
    $stmt = $db->prepare("INSERT INTO bookings (hall_id, room_type, user_id, booking_date, start_time, end_time, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("isssss", $hall_id, $room_type, $user_id, $booking_date, $start_time, $end_time);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Booking successful!"]);
    } else {
        echo json_encode(["error" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $db->close();
}
?>
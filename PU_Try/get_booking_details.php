<?php
include 'db_connection.php';

$bookingId = $_GET['id'];
$query = "SELECT b.booking_id, r.name, b.start_date, b.end_date, b.status
          FROM bookings b
          JOIN venue r ON b.hall_id = r.hall_id
          WHERE b.booking_id = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

echo json_encode($booking);
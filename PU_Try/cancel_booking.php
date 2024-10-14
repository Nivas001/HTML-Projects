<?php
include 'db_connection.php';

$bookingId = $_POST['booking_id'];
$reason = $_POST['reason'];
$otherReason = $_POST['other_reason'];

if ($reason === 'Other') {
    $reason = $otherReason;
}

$query = "UPDATE bookings_pu SET status = 'cancelled', cancellation_reason = ? WHERE booking_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("si", $reason, $bookingId);

if ($stmt->execute()) {
    echo "Booking cancelled successfully";
} else {
    echo "Error cancelling booking";
}
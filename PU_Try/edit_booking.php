<?php
include 'db_connection.php';

// Ensure you are retrieving the necessary POST values
$bookingId = $_POST['booking_id'];
$startDate = $_POST['start_date'];
$endDate = $_POST['end_date'];
$purposeType = $_POST['purpose']; // Purpose type from the dropdown
$purposeName = $_POST['purpose_name']; // Purpose name from the text input
$organiserName = $_POST['organiser_name'];
$organiserMobile = $_POST['organiser_mobile'];
$organiserEmail = $_POST['organiser_email'];
$organiserDepartment = $_POST['organiser_department'];

// Update query to include new fields
$query = "UPDATE bookings_pu SET start_date = ?, end_date = ?, purpose = ?, purpose_name = ?, organiser_name = ?, organiser_mobile = ?, organiser_email = ?, organiser_department = ? WHERE booking_id = ?";
$stmt = $db->prepare($query);

if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($db->error));
}

$stmt->bind_param("ssssssssi", $startDate, $endDate, $purposeType, $purposeName, $organiserName, $organiserMobile, $organiserEmail, $organiserDepartment, $bookingId);

if ($stmt->execute()) {
    echo "Booking updated successfully";
} else {
    echo "Error updating booking: " . htmlspecialchars($stmt->error);
}

$stmt->close(); // Close the statement
$db->close(); // Close the connection
?>
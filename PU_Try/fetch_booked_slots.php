<?php
// Start the session
session_start();
// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized access");
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

// Fetch booked slots
if (isset($_POST['hall_id']) && isset($_POST['booking_date'])) {
    $hall_id = $_POST['hall_id'];
    $booking_date = $_POST['booking_date'];

    // Prepare the SQL query
    $sql = "SELECT start_time, end_time, status FROM Bookings WHERE hall_id = ? AND booking_date = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die("Failed to prepare SQL statement: " . $db->error);
    }

    $stmt->bind_param("is", $hall_id, $booking_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>Start Time</th><th>End Time</th><th>Status</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . date('h:i A', strtotime($row['start_time'])) . "</td>";
            echo "<td>" . date('h:i A', strtotime($row['end_time'])) . "</td>";
            echo "<td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No booked slots for this room and date.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Please provide room ID and booking date.</p>";
}

$db->close();
?>

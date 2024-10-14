<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university_portal";

$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'getRoomDetails') {
    $hall_id = $_POST['roomId'];
    
    $sql = "SELECT * FROM Rooms WHERE hall_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $hall_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($room = $result->fetch_assoc()) {
        $response = [
            'success' => true,
            'details' => "<div><strong>Room Name:</strong> " . htmlspecialchars($room['room_name']) . "</div>" .
                         "<div><strong>Capacity:</strong> " . htmlspecialchars($room['capacity']) . "</div>" .
                         "<div><strong>Seats Available:</strong> " . htmlspecialchars($room['location']) . "</div>"
        ];
    } else {
        $response = ['success' => false, 'message' => 'Room not found.'];
    }

    echo json_encode($response);
    $stmt->close();
}
?>


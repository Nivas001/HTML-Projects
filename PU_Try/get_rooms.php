<?php
include 'db_connection.php';

$type = $_GET['type'] ?? 'auditorium';
$complex_id = $_GET['complex_id'] ?? null;

$query = "SELECT * FROM venue WHERE hall_type = ?";
$params = [$type];

if ($type === 'lecture hall room' && $complex_id) {
    $query .= " AND complex_id = ?";
    $params[] = $complex_id;
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$result = $stmt->get_result();
$rooms = $result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($rooms);
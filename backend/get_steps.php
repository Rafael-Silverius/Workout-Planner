<?php
session_start();
include './config.php';

$userId = $_SESSION['user_id'];

$sql = "SELECT steps, date FROM steps WHERE user_id = ? ORDER BY date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$steps = [];
$dates = [];

while ($row = $result->fetch_assoc()) {
    $steps[] = $row['steps'];
    $dates[] = date("M j", strtotime($row['date']));
}

echo json_encode([
    'labels' => $dates,
    'data' => $steps,
]);

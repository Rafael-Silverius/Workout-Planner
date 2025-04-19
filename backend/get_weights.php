<?php
session_start();
include './config.php';

$userId = $_SESSION['user_id'];

$sql = "SELECT weight, date FROM weights WHERE user_id = ? ORDER BY date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$weights = [];
$dates = [];

while ($row = $result->fetch_assoc()) {
    $weights[] = $row['weight'];
    $dates[] = date("M j", strtotime($row['date'])); // e.g., Apr 10
}

echo json_encode([
    'labels' => $dates,
    'data' => $weights,
]);

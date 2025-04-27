<?php
session_start();
include './config.php';

header('Content-Type: application/json');

$userId = $_SESSION['user_id'];

$sql = "SELECT step_goal FROM goals WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['step_goal' => $row['step_goal']]);
} else {
    echo json_encode(['error' => 'Step goal not found']);
}

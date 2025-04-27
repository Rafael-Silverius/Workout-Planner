<?php
session_start();
include './config.php';

$userId = $_SESSION['user_id'];

$sql = "SELECT steps, date FROM steps WHERE user_id = ? ORDER BY date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$stepsByDate = [];
while ($row = $result->fetch_assoc()) {
    $formattedDate = date("M j", strtotime($row['date']));
    $stepsByDate[$formattedDate] = (int)$row['steps'];
}

// Add today's date if it's not already in the data
$todayFormatted = date("M j");
if (!array_key_exists($todayFormatted, $stepsByDate)) {
    $stepsByDate[$todayFormatted] = 0;
}


$dates = array_keys($stepsByDate);
$steps = array_values($stepsByDate);

// 2. Fetch the latest step goal
$goalSql = "SELECT step_goal FROM goals WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$goalStmt = $conn->prepare($goalSql);
$goalStmt->bind_param("i", $userId);
$goalStmt->execute();
$goalResult = $goalStmt->get_result();
$goal = $goalResult->fetch_assoc();

echo json_encode([
    'labels' => $dates,
    'data' => $steps,
    'stepGoal' => isset($goal['step_goal']) ? (int)$goal['step_goal'] : 10000,
]);

<?php
// Fetch user's height from the database

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT height FROM biometrics WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$biometric = $result->fetch_assoc();

$showHeightPrompt = false;
if (!$biometric || empty($biometric['height']) || $biometric['height'] == 0) {
    $showHeightPrompt = true;
}
$stmt->close();
$conn->close();

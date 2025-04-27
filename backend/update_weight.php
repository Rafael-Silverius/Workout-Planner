<?php
session_start();
include './config.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $weight = $_POST['weight'] ?? null;

    if ($weight !== null) {
        $stmt = $conn->prepare("INSERT INTO weights (user_id, weight, date) VALUES (?, ?, NOW())");
        $stmt->bind_param("id", $userId, $weight);

        if ($stmt->execute()) {
            $_SESSION["toast_message"] = "Your weight was successfully updated 🎉";
            header("Location: ../public/profile.php");
            exit();
        } else {
            echo "Error logging weight.";
        }
    } else {
        echo "Invalid weight input.";
    }
}

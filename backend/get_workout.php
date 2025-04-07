<?php
session_start();
include './config.php';


if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    // Prepare the query to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM workouts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $workouts = [];

    while ($row = $result->fetch_assoc()) {
        $workouts[] = $row;
    }

    echo json_encode($workouts);

    $stmt->close();
} else {
    echo json_encode(["error" => "User is not logged in"]);
}

$conn->close();

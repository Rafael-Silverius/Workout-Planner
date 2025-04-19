<?php

function estimateStepLength($heightInCm)
{
    return $heightInCm * 0.415 / 100; // convert to meters
}

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $height = $_SESSION['height'];
    $users_step_length = estimateStepLength($height);

    // Get total distance of today's workouts
    $stmt = $conn->prepare("SELECT SUM(distance) FROM workouts WHERE user_id = ? AND type='running' AND DATE(created_at) = CURDATE()");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($totalDistance);
    $stmt->fetch();
    $stmt->close();

    if ($totalDistance === null) $totalDistance = 0;

    $steps = round($totalDistance * 1000 / $users_step_length);
}

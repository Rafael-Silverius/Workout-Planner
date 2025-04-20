<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$steps_updated_message = "";
$height = $_SESSION['height'];

if (!isset($conn)) {
    include('config.php');
}

if (!isset($user_id) || !isset($type) || !isset($distance) || !isset($duration) || !isset($height)) {
    $steps_updated_message = "Missing workout data";
    return;
}

if ($type !== 'running') {
    $steps_updated_message = "Not a running workout";
    return;
}

$today = date("Y-m-d");

// Function to estimate steps based on pace and height
function estimateSteps($distance_km, $duration_min, $height_cm)
{
    if ($distance_km == 0) return 0;

    // Estimate stride length based on height
    $stride = $height_cm * 0.413; // stride length in cm

    // Calculate pace to determine the activity type (running, jogging, walking)
    $pace = $duration_min / $distance_km;

    // Adjust stride based on pace
    if ($pace <= 6) {
        // Fast run
        $stride *= 1.2; // slightly longer stride for faster pace
    } elseif ($pace <= 8) {
        // Jog
        $stride *= 1.1; // moderately longer stride for jogging
    } else {
        // Walk
        $stride *= 0.9; // shorter stride for walking
    }

    // Convert distance from kilometers to centimeters for the step calculation
    return round(($distance_km * 100000) / $stride);
}

// Estimate steps for this workout
$steps_for_workout = estimateSteps($distance, $duration, $height);

// Check for existing steps entry for today
$check = $conn->prepare("SELECT steps FROM steps WHERE user_id = ? AND date = ?");
$check->bind_param("is", $user_id, $today);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    $row = $check_result->fetch_assoc();
    $updated_steps = $row['steps'] + $steps_for_workout;

    $update = $conn->prepare("UPDATE steps SET steps = ? WHERE user_id = ? AND date = ?");
    $update->bind_param("iis", $updated_steps, $user_id, $today);
    if ($update->execute()) {
        $steps_updated_message = "Steps updated successfully (+$steps_for_workout)";
    } else {
        $steps_updated_message = "Failed to update steps";
    }
    $update->close();
} else {
    $insert = $conn->prepare("INSERT INTO steps (user_id, steps, date) VALUES (?, ?, ?)");
    $insert->bind_param("iis", $user_id, $steps_for_workout, $today);
    if ($insert->execute()) {
        $steps_updated_message = "Steps inserted successfully ($steps_for_workout)";
    } else {
        $steps_updated_message = "Failed to insert steps";
    }
    $insert->close();
}

$check->close();

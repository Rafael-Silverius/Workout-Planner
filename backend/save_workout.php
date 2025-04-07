<?php
session_start();
header("Content-Type: application/json");

include('config.php');

// Read and decode JSON data from request
$data = json_decode(file_get_contents("php://input"), true);


// Validate and sanitize inputs
$user_id = $_SESSION["user_id"];
$type = $data['type'] ?? null;
$distance = isset($data['distance']) ? filter_var($data['distance'], FILTER_VALIDATE_FLOAT) : null;
$duration = isset($data['duration']) ? filter_var($data['duration'], FILTER_VALIDATE_FLOAT) : null;
$cadence = isset($data['cadence']) ? filter_var($data['cadence'], FILTER_VALIDATE_INT) : null;
$elevationGain = isset($data['elevationGain']) ? filter_var($data['elevationGain'], FILTER_VALIDATE_FLOAT) : null;
$latitude = isset($data['coords'][0]) ? filter_var($data['coords'][0], FILTER_VALIDATE_FLOAT) : null;
$longitude = isset($data['coords'][1]) ? filter_var($data['coords'][1], FILTER_VALIDATE_FLOAT) : null;
$description = isset($data['description']) ? htmlspecialchars(strip_tags($data['description'])) : null;
$created_at = isset($data['date']) ? date("Y-m-d", strtotime($data['date'])) : date("Y-m-d");

// Validate required fields
if (!$type || !$distance || !$duration || !$latitude || !$longitude || !$description) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

// ✅ Use Prepared Statement
$stmt = $conn->prepare("INSERT INTO workouts (user_id, type, distance, duration, cadence, elevationGain, latitude, longitude, description, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt) {
    // Bind parameters (s = string, d = double, i = integer)
    $stmt->bind_param("isddiiddss", $user_id, $type, $distance, $duration, $cadence, $elevationGain, $latitude, $longitude, $description, $created_at);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "Workout saved successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Execution failed: " . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Statement preparation failed"]);
}

$conn->close();
exit;

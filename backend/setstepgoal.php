<?php
// Start session to get the user ID (Assuming the user is logged in)
session_start();
include './config.php';


// Check if user is logged in and has a valid session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the step goal from the form
    $step_goal = $_POST['step_goal'];


    // Prepare SQL query to update the user's step goal
    $sql = "UPDATE users SET step_goal = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $step_goal, $user_id);

    // Execute the query and check if the update was successful
    if ($stmt->execute()) {
        echo "Step goal updated successfully!";
    } else {
        echo "Error updating step goal: " . $conn->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

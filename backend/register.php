<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim and sanitize inputs
    $username = trim(htmlspecialchars($_POST["username"]));
    $email = trim(htmlspecialchars($_POST["email"]));
    $password = $_POST["password"];
    $created_at = date("Y-m-d");

    // Check if fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION["error_message"] = "All fields are required.";
        header("Location: ../public/register_page.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error_message"] = "Invalid email format.";
        header("Location: ../public/register_page.php");
        exit();
    }

    // Check if username OR email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["error_message"] = "Username or email already exists.";
        header("Location: ../public/register_page.php");
        exit();
    }
    $check_stmt->close();

    // Use Argon2id for secure password hashing
    $options = [
        'memory_cost' => 1 << 17, // 128MB
        'time_cost' => 4,
        'threads' => 2
    ];
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID, $options);

    // Prepare the SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, ?)");

    if ($stmt) {
        // Bind parameters (s = string)
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $created_at);

        if ($stmt->execute()) {


            // Get the new user's ID
            $user_id = $stmt->insert_id;

            $default_steps = 10000;
            $insert_goal_stmt = $conn->prepare("INSERT INTO goals (user_id, step_goal) VALUES (?, ?)");
            if ($insert_goal_stmt) {
                $insert_goal_stmt->bind_param("ii", $user_id, $default_steps);
                $insert_goal_stmt->execute();
                $insert_goal_stmt->close();
            }

            // Set session variables for logged-in user
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["created_at"] = $created_at;
            $_SESSION["toast_message"] = "Welcome, " . ucfirst($username) . " 🎉";

            // Redirect to the dashboard or homepage
            header("Location: ../public/index.php");
            exit();
        } else {
            $_SESSION["error_message"] = "Error: " . $stmt->error;
            header("Location: ../public/register_page.php");
            exit();
        }
        $stmt->close();
    } else {
        $_SESSION["error_message"] = "Database error: Could not prepare statement.";
        header("Location: ../public/register_page.php");
        exit();
    }

    $conn->close();
} else {
    $_SESSION["error_message"] = "Invalid request method.";
    header("Location: ../public/register_page.php");
    exit();
}

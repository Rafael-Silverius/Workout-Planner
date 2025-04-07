<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    //Prepared Statement
    $stmt = $conn->prepare("SELECT id, username, password, email, bio, birth, step_goal, weight, height, created_at  FROM users WHERE username = ?");


    if ($stmt) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify the hashed password from the database
                if (password_verify($password, $row['password'])) {
                    // Set the session variables
                    $_SESSION["user_id"] = $row["id"];
                    $_SESSION["username"] = $username; // Store username in session
                    $_SESSION["email"] = $row["email"];
                    $_SESSION["bio"] = $row["bio"];
                    $_SESSION["birth"] = $row["birth"];
                    $_SESSION["step_goal"] = $row["step_goal"];
                    $_SESSION["weight"] = $row["weight"];
                    $_SESSION["height"] = $row["height"];
                    $_SESSION["created_at"] = $row["created_at"];

                    $_SESSION["toast_message"] = "Welcome back " . ucfirst($_SESSION['username']) . "👋";

                    // Redirect to the homepage or dashboard (Ensure the correct path)
                    header("Location: ../public/index.php");
                    exit();
                } else {
                    // If the password is incorrect, show an error message
                    $_SESSION["error_message"] = "Invalid username or password.";
                    header("Location: ../public/login_page.php");
                    exit();
                }
            } else {
                // If the username is not found, show an error message
                $_SESSION["error_message"] = "Invalid username or password.";
                header("Location: ../public/login_page.php");
                exit();
            }
        } else {
            // If the statement execution fails
            $_SESSION["error_message"] = "Database query execution failed: " . $stmt->error;
            header("Location: ../public/login_page.php");
            exit();
        }
        $stmt->close();
    } else {
        // If the statement preparation fails
        $_SESSION["error_message"] = "Database error: Could not prepare statement.";
        header("Location: ../public/login_page.php");
        exit();
    }
    // Close the database connection
    $conn->close();
} else {
    $_SESSION["error_message"] = "Invalid request method.";
    header("Location: ../public/login_page.php");
    exit();
}

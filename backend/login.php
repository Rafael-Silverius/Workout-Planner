<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    //Prepared Statement to fetch user by username
    $stmt = $conn->prepare("SELECT id, username, password, email, bio, birth, profile_img, created_at FROM users WHERE username = ?");

    if ($stmt) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row['password'])) {
                    // Set basic session data
                    $_SESSION["user_id"] = $row["id"];
                    $_SESSION["username"] = $username;
                    $_SESSION["email"] = $row["email"];
                    $_SESSION["bio"] = $row["bio"];
                    $_SESSION["birth"] = $row["birth"];
                    $_SESSION["profile_img"] = $row["profile_img"];
                    $_SESSION["created_at"] = $row["created_at"];

                    $user_id = $row["id"];

                    // Fetch step goal from goals table
                    $goal_stmt = $conn->prepare("SELECT step_goal FROM goals WHERE user_id = ?");
                    if ($goal_stmt) {
                        $goal_stmt->bind_param("i", $user_id);
                        $goal_stmt->execute();
                        $goal_result = $goal_stmt->get_result();
                        if ($goal_result->num_rows > 0) {
                            $goal_row = $goal_result->fetch_assoc();
                            $_SESSION["step_goal"] = $goal_row["step_goal"];
                        }
                        $goal_stmt->close();
                    }

                    // Fetch weight and height from biometrics table
                    $bio_stmt = $conn->prepare("SELECT weight, height FROM biometrics WHERE user_id = ?");
                    if ($bio_stmt) {
                        $bio_stmt->bind_param("i", $user_id);
                        $bio_stmt->execute();
                        $bio_result = $bio_stmt->get_result();
                        if ($bio_result->num_rows > 0) {
                            $bio_row = $bio_result->fetch_assoc();
                            $_SESSION["weight"] = $bio_row["weight"];
                            $_SESSION["height"] = $bio_row["height"];
                        }
                        $bio_stmt->close();
                    }

                    $_SESSION["toast_message"] = "Welcome back " . ucfirst($_SESSION['username']) . " 👋";
                    header("Location: ../public/index.php");
                    exit();
                } else {
                    $_SESSION["error_message"] = "Invalid username or password.";
                    header("Location: ../public/login_page.php");
                    exit();
                }
            } else {
                $_SESSION["error_message"] = "Invalid username or password.";
                header("Location: ../public/login_page.php");
                exit();
            }
        } else {
            $_SESSION["error_message"] = "Database query execution failed: " . $stmt->error;
            header("Location: ../public/login_page.php");
            exit();
        }
        $stmt->close();
    } else {
        $_SESSION["error_message"] = "Database error: Could not prepare statement.";
        header("Location: ../public/login_page.php");
        exit();
    }
    $conn->close();
} else {
    $_SESSION["error_message"] = "Invalid request method.";
    header("Location: ../public/login_page.php");
    exit();
}

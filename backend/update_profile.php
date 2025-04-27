<?php
session_start();
include './config.php';

$userId = $_SESSION['user_id'];


$oldImagePath = null;
$checkSql = "SELECT profile_img FROM users WHERE id = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("i", $userId);
$checkStmt->execute();
$checkStmt->bind_result($oldImagePath);
$checkStmt->fetch();
$checkStmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $location = $_POST['location'];
    $bio = $_POST['bio'];
    $birth = $_POST['birth'];
    $step_goal = $_POST['step_Goal'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];

    $imagePath = null;

    // Handle cropped image from base64
    if (!empty($_POST['cropped_image'])) {
        $base64 = $_POST['cropped_image'];
        $base64 = str_replace('data:image/png;base64,', '', $base64);
        $base64 = str_replace(' ', '+', $base64);
        $imageData = base64_decode($base64);

        $uploadDir = '../assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = 'profile_' . $userId . '_' . time() . '.png';
        $imagePath = $uploadDir . $fileName;

        if (!file_put_contents($imagePath, $imageData)) {
            die("Error saving cropped image.");
        }
    }

    // Handle regular file upload if there's no cropped image
    elseif (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_img']['tmp_name'];
        $fileName = basename($_FILES['profile_img']['name']);
        $uploadDir = '../assets/uploads/';
        $imagePath = $uploadDir . time() . '_' . $fileName;

        if (!move_uploaded_file($fileTmpPath, $imagePath)) {
            die("Error uploading the image.");
        }
    }

    if ($imagePath && $oldImagePath && file_exists($oldImagePath)) {
        if (strpos($oldImagePath, 'user.png') === false) {
            unlink($oldImagePath);
        }
    }

    // Update user info
    try {
        // 1. Update users table
        $sqlUser = "UPDATE users SET username = ?, location = ?, bio = ?, birth = ?";
        if ($imagePath) $sqlUser .= ", profile_img = ?";
        $sqlUser .= " WHERE id = ?";
        $stmtUser = $conn->prepare($sqlUser);
        if ($imagePath) {
            $stmtUser->bind_param("sssssi", $username, $location, $bio, $birth, $imagePath, $userId);
        } else {
            $stmtUser->bind_param("ssssi", $username, $location, $bio, $birth, $userId);
        }
        $stmtUser->execute();
        $stmtUser->close();

        // 2. Update or Insert into goals table
        $stmtGoalCheck = $conn->prepare("SELECT id FROM goals WHERE user_id = ?");
        $stmtGoalCheck->bind_param("i", $userId);
        $stmtGoalCheck->execute();
        $resultGoal = $stmtGoalCheck->get_result();

        if ($resultGoal->num_rows > 0) {
            // Entry exists – update
            $stmtGoal = $conn->prepare("UPDATE goals SET step_goal = ? WHERE user_id = ?");
            $stmtGoal->bind_param("ii", $step_goal, $userId);
            $stmtGoal->execute();
            $stmtGoal->close();
        } else {
            // Entry doesn't exist – insert
            $stmtGoal = $conn->prepare("INSERT INTO goals (user_id, step_goal) VALUES (?, ?)");
            $stmtGoal->bind_param("ii", $userId, $step_goal);
            $stmtGoal->execute();
            $stmtGoal->close();
        }
        $stmtGoalCheck->close();

        // 3. Update or Insert into biometrics table
        $stmtBioCheck = $conn->prepare("SELECT id FROM biometrics WHERE user_id = ?");
        $stmtBioCheck->bind_param("i", $userId);
        $stmtBioCheck->execute();
        $resultBio = $stmtBioCheck->get_result();

        if ($resultBio->num_rows > 0) {
            // Entry exists – update
            $stmtBio = $conn->prepare("UPDATE biometrics SET height = ? WHERE user_id = ?");
            $stmtBio->bind_param("di", $height, $userId);
            $stmtBio->execute();
            $stmtBio->close();
        } else {
            // Entry doesn't exist – insert
            $stmtBio = $conn->prepare("INSERT INTO biometrics (user_id, height) VALUES (?, ?)");
            $stmtBio->bind_param("di", $userId, $height);
            $stmtBio->execute();
            $stmtBio->close();
        }
        $stmtBioCheck->close();

        // Commit transaction
        $conn->commit();

        // Update session variables
        $_SESSION['username'] = $username;
        $_SESSION['location'] = $location;
        $_SESSION['bio'] = $bio;
        $_SESSION['birth'] = $birth;
        $_SESSION['step_goal'] = $step_goal;
        $_SESSION['height'] = $height;
        if ($imagePath) $_SESSION['profile_img'] = $imagePath;

        $_SESSION["toast_message"] = "Profile successfully updated 🎉";
        header("Location: ../public/profile.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION["error_message"] = "Error updating profile: " . $e->getMessage();
        header("Location: ../public/profile.php");
        exit();
    }

    $conn->close();
}

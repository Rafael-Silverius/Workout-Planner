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
    $sql = "UPDATE users SET username = ?, bio = ?, birth = ?, step_goal = ?, height = ?, weight = ?";
    if ($imagePath) {
        $sql .= ", profile_img = ?";
    }
    $sql .= " WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($imagePath) {
        $stmt->bind_param("ssssddsi", $username, $bio, $birth, $step_goal, $height, $weight, $imagePath, $userId);
    } else {
        $stmt->bind_param("ssssddi", $username, $bio, $birth, $step_goal, $height, $weight, $userId);
    }

    if ($stmt->execute()) {
        // Update session values
        $_SESSION['username'] = $username;
        $_SESSION['bio'] = $bio;
        $_SESSION['birth'] = $birth;
        $_SESSION['step_goal'] = $step_goal;
        $_SESSION['height'] = $height;
        $_SESSION['weight'] = $weight;
        if ($imagePath) {
            $_SESSION['profile_img'] = $imagePath;
        }

        header("Location: ../public/profile.php");
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

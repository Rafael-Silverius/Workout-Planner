<?php
session_start();
include('../backend/config.php');

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT username, email, created_at, birth, bio, weight, height, step_goal FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            // Update session variables with fresh data
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['created_at'] = $row['created_at'];
            $_SESSION['birth'] = $row['birth'];
            $_SESSION['bio'] = $row['bio'];
            $_SESSION['weight'] = $row['weight'];
            $_SESSION['height'] = $row['height'];
            $_SESSION['step_goal'] = $row['step_goal'];
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>
    <title>Profile - Workout-Planner</title>
    <link rel="icon" type="image/x-icon" href="../assets/icons/fitness.png">
    <link rel="stylesheet" href="style.css" />
    <script defer src="script.js"></script>
    <!-- Include Toastify.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="profile">

    <!-- Header Section -->
    <header>
        <a href="index.php"><img class="logo" src="../assets/images/exercise1.png" alt=""></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="log_workout.php">Log Workout</a>
            <a href="profile.php">Profile</a>
            <a href="../backend/logout.php">Logout</a>
        </nav>
    </header>

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="profile-container">
            <div class="profile-header">
                <div class="left">
                    <img
                        src="<?php echo htmlspecialchars(isset($_SESSION['profile_img']) ? $_SESSION['profile_img'] : '../assets/icons/user.png'); ?>"
                        alt="Profile Picture"
                        class="profile-img"
                        onerror="this.src='../assets/icons/user.png';" />
                    <div class="">
                        <h2> <?php echo ucfirst($_SESSION['username']); ?></h2>
                        <h3>location</h3>
                        <h3 class="bio"><?php echo isset($_SESSION['bio']) ? $_SESSION['bio'] : ''; ?></h3>
                    </div>
                </div>
                <button class="editProfilebtn" id="editProfile">
                    <img src="../assets/icons/edit.png" alt="editIcon">
                    Edit
                </button>
            </div>
            <div class="profile-boxes">
                <div class="profile-box steps">
                    <div class="profile-box-header">
                        <h2>Todays Progress</h2>
                    </div>
                    <div class="profile-box-content">
                        <div class="profile-box-content-tile steps">
                            <p>23 / <?php echo $_SESSION['step_goal'] . ' Steps'; ?> </p>
                        </div>
                    </div>
                </div>

                <div class="profile-box">
                    <div class="profile-box-header">
                        <h2>Profile Information</h2>
                    </div>
                    <div class="profile-box-content">
                        <div class="profile-box-content-tile">
                            <h3>Username</h3>
                            <p><?php echo ucfirst($_SESSION['username']); ?></p>
                        </div>
                        <div class="profile-box-content-tile">
                            <h3>Email Address</h3>
                            <p> <?php echo $_SESSION['email']; ?></p>
                        </div>
                        <div class="profile-box-content-tile">
                            <h3>Date of Birth</h3>
                            <p><?php echo isset($_SESSION['birth']) ? $_SESSION['birth'] : '-'; ?></p>
                        </div>
                        <div class="profile-box-content-tile">
                            <h3>Joined</h3>
                            <p><?php echo $_SESSION['created_at']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="profile-box">
                    <div class="profile-box-header">
                        <h2>Statistics</h2>
                    </div>
                    <div class="profile-box-content">
                        <div class="profile-box-content-tile">
                            <h3>Daily Step Goal</h3>
                            <p><?php echo isset($_SESSION['step_goal']) ? $_SESSION['step_goal'] : '-'; ?></p>
                        </div>
                        <div class="profile-box-content-tile">
                            <h3>Weight</h3>
                            <p><?php echo isset($_SESSION['weight']) ? $_SESSION['weight'] : '-'; ?></p>
                        </div>
                        <div class="profile-box-content-tile">
                            <h3>Height</h3>
                            <p><?php echo isset($_SESSION['height']) ? $_SESSION['height'] : '-'; ?></p>
                        </div>
                        <div class="profile-box-content-tile">
                            <h3>BMI</h3>
                            <p><?php
                                if (isset($_SESSION['weight']) && isset($_SESSION['height']) && $_SESSION['height'] != 0) {
                                    $heightMeters = $_SESSION['height'] / 100;
                                    $bmi = $_SESSION['weight'] / ($heightMeters * $heightMeters);
                                    echo number_format($bmi, 2);
                                } else {
                                    echo '-';
                                }
                                ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="editProfileModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 class="form__title">Edit your profile</h2>
                <form class="form editprofileform" action="../backend/update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="form__row">
                        <label for="profile_img" class="form__label">Profile Image</label>
                        <!-- Current Profile Image Display (if exists, otherwise default image) -->
                        <div id="currentProfileImageContainer" style="margin-top: 10px; display: flex; align-items: center; cursor: pointer;">
                            <img
                                id="currentProfileImage"
                                src="<?php echo isset($_SESSION['profile_img']) ? $_SESSION['profile_img'] : '../assets/icons/user.png'; ?>"
                                alt="Profile Image"
                                class="profile-img" />
                        </div>
                        <label class="form__label">Click on the image to select a new one! </label>

                        <!-- File Selector -->
                        <input class="form__input hidden" type="file" name="profile_img" id="uploadImage" accept="image/*">

                        <!-- Image Preview Area -->
                        <div id="imagePreviewContainer" style="margin-top: 10px; display: none;  width:200px; height: 100px;">
                            <img id="imagePreview" />
                        </div>

                        <!-- Crop Button -->
                        <button type="button" id="cropButton" style="margin-top: 10px; color:black">Use the croped image</button>

                        <!-- Hidden input to store base64 image -->
                        <input type="hidden" name="cropped_image" id="croppedImageInput">
                    </div>
                    <div class="form__row">
                        <label for="username" class="form__label">Username</label>
                        <input class="form__input" type="text" name="username" value=<?php echo $_SESSION['username'] ?>>
                    </div>
                    <div class="form__row">
                        <label for="bio" class="form__label">Bio</label>
                        <textarea class="form__input" name="bio" id="bio" rows="2" value="Add something about you.."><?php echo isset($_SESSION['bio']) ? $_SESSION['bio'] : ''; ?></textarea>
                    </div>
                    <div class="form__row">
                        <label for="birth" class="form__label">Date of Birht</label>
                        <input class="form__input" type="date" name="birth"
                            value="<?php echo isset($_SESSION['birth']) ? $_SESSION['birth'] : ''; ?>"
                            max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form__row">
                        <label for="step_goal" class="form__label">Daily Step Goal</label>
                        <input class="form__input" type="number" name="step_Goal" value="<?php echo isset($_SESSION['step_goal']) ? $_SESSION['step_goal'] : ''; ?>"
                            min=0>
                    </div>
                    <div class="form__row">
                        <label for="height" class="form__label">Height (cm)</label>
                        <input class="form__input" type="number" step="1" name="height" value="<?php echo isset($_SESSION['height']) ? $_SESSION['height'] : ''; ?>"
                            min=0>
                    </div>
                    <div class="form__row">
                        <label for="weight" class="form__label">Weight (kg)</label>
                        <input class="form__input" type="number" step="0.1" name="weight" value="<?php echo isset($_SESSION['weight']) ? $_SESSION['weight'] : ''; ?>"
                            min=0>
                    </div>
                    <div class="form__row">
                        <button type="submit" class="modal-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>© 2025 Workout Logger. All rights reserved.</p>
    </footer>

    <script>
        <?php
        if (isset($_SESSION["toast_message"])) {
            echo 'Toastify({
             text: "' . $_SESSION["toast_message"] . '",
            duration: 3000,
            gravity: "top", // top or bottom
            position: "center", // left, center, or right,
            className: "large-toast",
            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
        }).showToast();';

            // Unset the session variable after displaying the toast
            unset($_SESSION["toast_message"]);
        }
        ?>
    </script>

</body>

</html>
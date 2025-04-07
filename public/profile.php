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
    <title>Profile - Workout Logger</title>
    <link rel="stylesheet" href="style.css" />
    <script defer src="script.js"></script>


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
                    <img src="../assets/icons/user.png" alt="profilePic">
                    <div class="">
                        <h2> <?php echo ucfirst($_SESSION['username']); ?></h2>
                        <h3>location</h3>
                        <h3 class="bio"><?php echo isset($_SESSION['bio']) ? $_SESSION['bio'] : ''; ?></h3>
                    </div>
                </div>
                <button class="editProfilebtn">
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


            <!-- <div id="editProfileModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 class="form__title">Edit your profile</h2>
                    <form class="form" action="edit_profile.php" method="POST">

                        <div class="form__row">
                            <input class="form__input" type="text" name="username" placeholder="username" required>
                        </div>
                        <div class="form__row">
                            <input class="form__input" type="password" name="password" placeholder="password" required>
                        </div>
                        <div class="form__row">
                            <button type="submit" class="modal-btn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="deleteProfileModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3 class="form__title">Are you sure you want to delete your profile?</h3>
                    <button>Yes</button>
                    <button>No</button>
                </div>-->
        </div>

    </section>

    <!-- Footer Section -->
    <footer>
        <p>© 2025 Workout Logger. All rights reserved.</p>
    </footer>

</body>

</html>
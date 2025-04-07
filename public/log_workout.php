<?php
session_start();
include('../backend/config.php');

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Workout Logger</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script defer src="script.js"></script>
</head>

<header>
        <a href="index.php"><img class="logo" src="../assets/images/exercise1.png" alt=""></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="log_workout.php">Log Workout</a>
            <a href="profile.php">Profile</a>
            <a href="../backend/logout.php">Logout</a>
        </nav>
</header>

<body class="log-workout">
    <!-- Header Section -->
    

    <div class="main">
        <div class="sidebar">
            <ul class="workouts">
                <div class="spinner-container">
                    <div class="spinner"></div>
                </div>
                <form class="form workouts-form hidden" method="POST" action="submit_workout.php">
                    <div class="form__row">
                        <label class="form__label">Type</label>
                        <select class="form__input form__input--type" name="type">
                            <option value="running">Running</option>
                            <option value="cycling">Cycling</option>
                        </select>
                    </div>
                    <div class="form__row">
                        <label class="form__label">Distance</label>
                        <input class="form__input form__input--distance" name="distance" placeholder="km" />
                    </div>
                    <div class="form__row">
                        <label class="form__label">Duration</label>
                        <input class="form__input form__input--duration" name="duration" placeholder="min" />
                    </div>
                    <div class="form__row">
                        <label class="form__label">Cadence</label>
                        <input class="form__input form__input--cadence" name="cadence" placeholder="step/min" />
                    </div>
                    <div class="form__row form__row--hidden">
                        <label class="form__label">Elev Gain</label>
                        <input class="form__input form__input--elevation" name="elevationGain" placeholder="meters" />
                    </div>
                    <div>
                        <button type="submit" class="hidden">Submit</button>
                    </div>
                </form>
            </ul>
        </div>

        <!-- Hero Section -->
        <div id="map"></div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>© 2025 Workout Logger. All rights reserved.</p>
    </footer>
</body>

</html>
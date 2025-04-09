<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Workout-Planner</title>
  <link rel="icon" type="image/x-icon" href="../assets/icons/fitness.png">
  <link rel="stylesheet" href="style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
  <script defer src="script.js"></script>
  <!-- Include Toastify.js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="index">
  <!-- Header Section -->

  <header>
    <a href="index.php"><img class="logo" src="../assets/images/exercise1.png" alt=""></a>
    <nav>
      <a href="index.php">Home</a>

      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="log_workout.php">Log Workout</a>
        <a href="profile.php">Profile</a>
        <a href="../backend/logout.php">Logout</a>
      <?php else: ?>
        <a href="login_page.php">Sign In</a>
        <a href="register_page.php" id="sign-up">Sign Up</a>
      <?php endif; ?>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h2>Track Your Workouts
        <h2>Stay <span class="auto-type"></span></h2>
      </h2>
      <p>
        Log your workouts, visualize your progress, and improve your fitness
        journey.
      </p>
      <?php if (isset($_SESSION['user_id'])): ?>
        <button onclick=" window.location.href='log_workout.php'">Start by logging a workout
        </button>
      <?php else: ?>
        <button onclick=" window.location.href='login_page.php'"> Start your fitness journey
        </button>
      <?php endif; ?>

    </div>

    <!-- Features Section -->

    <section class=" features">
      <div class="carousel">
        <div class="carousel-container">
          <div class="carousel-track">
            <div class="carousel-item">
              <div class="feature">
                <img src="../assets/icons/running.svg" alt="Log Workouts" />
                <h3>Log Workouts</h3>
                <p>Track your workouts with ease and store them securely.</p>
              </div>
            </div>
            <div class="carousel-item">
              <div class="feature">
                <img src="../assets/icons/progress.svg" alt="View Progress" />
                <h3>View Progress</h3>
                <p>Analyze your workout history and personal records.</p>
              </div>
            </div>
            <div class="carousel-item">
              <div class="feature">
                <img src="../assets/icons/map.svg" alt="Map Your Route" />
                <h3>Map Your Route</h3>
                <p>
                  Visualize where you've exercised using interactive maps.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel-dots"></div>
      </div>
    </section>
  </section>

  <!-- Footer Section -->
  <footer>
    <p>© 2025 Workout Logger. All rights reserved.</p>
  </footer>

  <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
  <script>
    let typed = new Typed(".auto-type", {
      strings: ['Motivated', 'Active', 'Fit'],
      typeSpeed: 150,
      backSpeed: 150,
      loop: true
    })

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
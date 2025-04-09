<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Store error message in a variable if it exists
$errorMessage = isset($_SESSION["error_message"]) ? $_SESSION["error_message"] : null;

// Unset session error after storing it in a JS-friendly variable
unset($_SESSION["error_message"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Workout-Planner</title>
    <link rel="icon" type="image/x-icon" href="../assets/icons/fitness.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <script defer src="script.js"></script>
    <!-- Include Toastify.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<!-- Header Section -->

<body class="loggin-Page">
    <header>
        <a href="index.php"><img class="logo" src="../assets/images/exercise1.png" alt=""></a>

        <nav>
            <a href="index.php">Home</a>
            <a href="login_page.php">Sign In</a>
            <a href="register_page.php">Sign Up</a>
        </nav>
    </header>

    <div class="login-container">
        <h2 class="form__title">Sign In</h2>
        <p class="form__title">Enter your credentials to start using the app</p>
        <form method="POST" class="form" action="../backend/login.php">
            <div class="form__row">
                <input class="form__input usernameIn" type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form__row">
                <input class="form__input" type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form__row">
                <button type="submit" class="form__btn">Login</button>
            </div>
        </form>
    </div>

    <footer>
        <p>© 2025 Workout Logger. All rights reserved.</p>
    </footer>

    <script>
        // Handle error message and toast notifications
        <?php if ($errorMessage): ?>
            Toastify({
                text: "<?php echo $errorMessage; ?>",
                duration: 3000,
                gravity: "top",
                position: "center",
                className: "large-toast",
                backgroundColor: "linear-gradient(to right, #ff0000, #ff7300)"
            }).showToast();
        <?php endif; ?>
    </script>
</body>

</html>
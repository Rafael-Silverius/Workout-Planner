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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <title>Register - Workout-Planner</title>
    <link rel="icon" type="image/x-icon" href="../assets/icons/fitness.png">
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/zxcvbn@4.4.2/dist/zxcvbn.js"></script>
    <script defer src="script.js"></script>
    <!-- Include Toastify.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="register-Page">
    <!-- Header Section -->
    <header>
        <a href="index.php"><img class="logo" src="../assets/images/exercise1.png" alt="Logo"></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="login_page.php">Sign In</a>
            <a href="register_page.php">Sign Up</a>
        </nav>
    </header>

    <div class="login-container">
        <h2 class="form__title">Register</h2>
        <p class="form__title">Enter your credentials to create an account</p>
        <form method="POST" class="form" id="register-form" action="../backend/register.php">
            <div class="form__row">
                <input class="form__input usernameIn" type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form__row">
                <input class="form__input" type="email" name="email" placeholder="Email" required>
            </div>
            <div class="password-wrapper form__row">

                <input class="form__input" type="password" name="password" placeholder="Password" required minlength="6" id="password">
                <div class="toggle-password" onclick="togglePassword()">
                    <i class="fa fa-fw fa-eye field-icon "></i>
                </div>
            </div>
            <div class="form__row">
                <div id="password-strength-text" style="font-size: 14px; color: #fff;"></div>
            </div>
            <div class="password-tips">
                <p><strong>Password must include:</strong></p>
                <ul id="password-rules">
                    <li id="length" class="invalid">At least 8 characters</li>
                    <li id="lower-upper" class="invalid">Lowercase & Uppercase letters</li>
                    <li id="number" class="invalid">At least one number</li>
                    <li id="symbol" class="invalid">At least one symbol (!@#$...)</li>
                    <li id="common" class="invalid">Avoid common or personal info</li>
                </ul>
            </div>
            <div class="form__row">
                <button class="form__btn" type="submit">Sign Up</button>
            </div>
        </form>
    </div>

    <footer>
        <p>© 2025 Workout Logger. All rights reserved.</p>
    </footer>

    <script>
        // Handle error messages using JavaScript
        const errorMessage = <?php echo json_encode($errorMessage); ?>;
        if (errorMessage) {
            Toastify({
                text: errorMessage,
                duration: 3000,
                gravity: "top",
                position: "center",
                className: "large-toast",
                backgroundColor: "linear-gradient(to right, #ff0000, #ff7300)"
            }).showToast();
        }

        // Toggle Password Visibility
        function togglePassword() {
            const passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>

</html>
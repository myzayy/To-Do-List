<?php
session_start();
require_once 'config/config.php';

// If the user is already logged in, redirect to the main page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require 'config/connect.php'; // connect.php

$registration_error = '';
$registration_success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password_plain = $_POST['password'];

    if (empty($username) || empty($password_plain)) {
        $registration_error = "The username and password cannot be empty.";
    } elseif (strlen($password_plain) < 6) {
        $registration_error = "The password must contain at least 6 characters.";
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        if ($stmt_check) {
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $registration_error = "A user with this name already exists.";
            } else {
                $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
                $stmt_insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
                if ($stmt_insert) {
                    $stmt_insert->bind_param("ss", $username, $password_hashed);
                    if ($stmt_insert->execute()) {
                        // Посилання на login.php (в корені)
                        $registration_success = "Registration is successful! Now you can <a href='login.php'>login</a>.";
                    } else {
                        $registration_error = "Registration error: " . $stmt_insert->error;
                    }
                    $stmt_insert->close();
                } else {
                    $registration_error = "Error preparing an insert request: " . $conn->error;
                }
            }
            $stmt_check->close();
        } else {
            $registration_error = "Error in preparing an audit request: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<?php include 'parts/header.php'; // header ?>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="cd-hero">
        <?php include 'parts/navigation.php'; // navigation ?>

        <div class="container-fluid tm-page-pad">
            <div class="row">
                <div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
                     <div class="text-xs-left tm-textbox tm-textbox-padding tm-bg-white-translucent">
                        <h2 class="tm-text-title text-xs-center">Register</h2>
                        <?php if (!empty($registration_error)): ?>
                            <div class="alert alert-danger" role="alert" style="margin-bottom: 15px;"><?php echo htmlspecialchars($registration_error); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($registration_success)): ?>
                            <div class="alert alert-success" role="alert" style="margin-bottom: 15px;"><?php echo $registration_success; /* Дозволяємо HTML для посилання */ ?></div>
                        <?php endif; ?>

                        <?php if (empty($registration_success)): // Hide the form after successful registration ?>
                        <form method="POST" action="register.php">
                            <div class="form-group">
                                <label for="register_username">Username:</label>
                                <input type="text" id="register_username" name="username" class="form-control" placeholder="Username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="register_password">Password:</label>
                                <input type="password" id="register_password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block tm-submit-btn">Register</button>
                        </form>
                        <?php endif; ?>
                        <p class="text-xs-center" style="margin-top: 15px;">Already have an account? <a href="login.php">Login here</a>.</p>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'parts/footer.php'; // footer ?>
    </div>

    <script src="<?php echo BASE_PATH; ?>js/jquery-1.11.3.min.js"></script>
    <script src="https://www.atlasestateagents.co.uk/javascript/tether.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>js/jquery.magnific-popup.min.js"></script>
    <script>
        $(window).on('load', function(){
            $('body').addClass('loaded');
            // Mobile menu collapse
            $('#tmNavbar .nav-link').on('click', function(){
                if ($('.navbar-toggler').is(':visible') && $('#tmNavbar').hasClass('show')) {
                    $('#tmNavbar').collapse('hide');
                }
            });
        });
    </script>
</body>
</html>
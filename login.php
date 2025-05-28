<?php
session_start();
require_once 'config/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // If you are already logged in, go to the main page
    exit();
}

require 'config/connect.php';
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $login_error = "The username and password cannot be empty.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($user = $result->fetch_assoc()) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: index.php"); // Успішний вхід
                    exit();
                } else {
                    $login_error = "Incorrect username or password.";
                }
            } else {
                $login_error = "Incorrect username or password.";
            }
            $stmt->close();
        } else {
            $login_error = "Error in preparing a database query: " . $conn->error;
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
                        <h2 class="tm-text-title text-xs-center">Login</h2>
                        <?php if (!empty($login_error)): ?>
                            <div class="alert alert-danger" role="alert" style="margin-bottom: 15px;"><?php echo htmlspecialchars($login_error); ?></div>
                        <?php endif; ?>
                        <form method="POST" action="login.php">
                            <div class="form-group">
                                <label for="login_username">Username:</label>
                                <input type="text" id="login_username" name="username" class="form-control" placeholder="Username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="login_password">Password:</label>
                                <input type="password" id="login_password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block tm-submit-btn">Login</button>
                        </form>
                        <p class="text-xs-center" style="margin-top: 15px;">Don't have an account? <a href="register.php">Register here</a>.</p>
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
            $('#tmNavbar .nav-link').on('click', function(){
                if ($('.navbar-toggler').is(':visible') && $('#tmNavbar').hasClass('show')) {
                    $('#tmNavbar').collapse('hide');
                }
            });
        });
    </script>
</body>
</html>
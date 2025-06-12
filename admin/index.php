<?php

session_start(); // Start the session at the very beginning

require_once '../config/config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page (which is in the root directory)
    header("Location: ../login.php");
    exit();
} elseif (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    // If logged in but not an admin, redirect to the homepage (or show an access denied message)
    header("Location: ../index.php");
    exit();
}

// If all checks pass, the user is an admin and can see the page content.
// You can include connect.php if you need database access on this page
// require '../config/connect.php'; // Adjusted path
?>
<!DOCTYPE html>
<html lang="uk">
<?php include '../parts/header.php'; // Path to header.php from admin/ directory ?>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="cd-hero">
        <?php include '../parts/navigation.php'; // Path to navigation.php from admin/ directory ?>

        <div class="container-fluid tm-page-pad">
            <div class="row">
                <div class="col-xs-12">
                    <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding">
                        <h2 class="tm-text-title">Admin Panel</h2>
                        <p class="tm-text">Welcome, Administrator <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                        <p class="tm-text">This is the main dashboard for the admin panel. From here, you will be able to manage users, tasks, and other site settings.</p>
                        
                        <h3 class="tm-text-title" style="margin-top: 30px;">Future Admin Links:</h3>
                        <ul>
                            <li><a href="manage_users.php">Manage Users</a></li>
                            <li><a href="#">Manage All Tasks</a> (Not implemented yet)</li>
                            <li><a href="#">Site Settings</a> (Not implemented yet)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../parts/footer.php'; // Path to footer.php from admin/ directory ?>
    </div>

    <script src="../js/jquery-1.11.3.min.js"></script>
    <script src="https://www.atlasestateagents.co.uk/javascript/tether.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.magnific-popup.min.js"></script>
    <script>
        $(window).on('load', function(){
            $('body').addClass('loaded'); // Preloader

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
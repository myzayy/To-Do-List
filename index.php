<?php
session_start();
require_once 'config/config.php'
// Можливо, require 'config/connect.php'; якщо ця сторінка використовує БД
?>
<!DOCTYPE html>
<html lang="uk">
<?php include 'parts/header.php'; ?>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="cd-hero">
        <?php include 'parts/navigation.php'; ?>

        <div class="container-fluid tm-page-pad"> 
            <div class="row">
                <div class="tm-3-col-container">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 tm-3-col-textbox">
                        <div class="text-xs-left tm-textbox tm-textbox-padding tm-bg-white-translucent tm-3-col-textbox-inner">
                            <i class="fa fa-4x fa-pagelines tm-home-fa"></i>
                            <h2 class="tm-text-title">Hello Guest</h2>
                            <p class="tm-text">Upper HTML Template contains different background images for pages. Please check <a href="http://www.templatemo.com/tm-494-motion" target="_parent">Motion Template</a> if you want to see video backgrounds. Feel free to download and use web templates from templatemo.</p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 tm-3-col-textbox">
                        <div class="text-xs-left tm-textbox tm-textbox-padding tm-bg-white-translucent tm-3-col-textbox-inner">
                            <i class="fa fa-4x fa-bicycle tm-home-fa"></i>
                            <h2 class="tm-text-title">Welcome!</h2>
                            <p class="tm-text">There are 3 different gallaries in this template. You can customize them in any suitable way you prefer. You can also modify the content columns as you wish. Images are from Unsplash website. Good Luck!</p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 tm-3-col-textbox">
                        <div class="text-xs-left tm-textbox tm-textbox-padding tm-bg-white-translucent tm-3-col-textbox-inner">
                            <i class="fa fa-4x fa-plane tm-home-fa"></i>
                            <h2 class="tm-text-title">Stay relaxed</h2>
                            <p class="tm-text">You can easily change icons in HTML codes by <a href="http://fontawesome.io/icons/" target="_parent">Font Awesome</a>. Praesent tempus dapibus. Curabitur sodales, est auctor congue vulputate, nisl tellus finibus nunc, vitae consectetur enim.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'parts/footer.php'; ?>
    </div>

    <script src="<?php echo BASE_PATH; ?>js/jquery-1.11.3.min.js"></script>
    <script src="https://www.atlasestateagents.co.uk/javascript/tether.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>js/jquery.magnific-popup.min.js"></script>
    <script>
        $(window).on('load', function(){
            $('body').addClass('loaded'); // Preloader
            
            // mobile menu collapse
            $('#tmNavbar .nav-link').on('click', function(){
                if ($('.navbar-toggler').is(':visible') && $('#tmNavbar').hasClass('show')) {
                    $('#tmNavbar').collapse('hide');
                }
            });
        });
    </script>
</body>
</html>
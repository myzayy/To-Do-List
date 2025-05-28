<?php
session_start();
require_once 'config/config.php';
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

        <div class="container-fluid tm-page-width tm-page-pad"> 
            <div class="row">
                <div class="col-xs-12">
                    <div class="tm-flex">
                        <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-textbox-padding tm-white-box-margin-b">
                            <h2 class="tm-text-title">Lorem ipsum dolor</h2>
                            <p class="tm-text">Nulla efficitur, ligula et imperdiet volutpat, lacus tortor tempus massa, eget tempus quam nibh vel nulla. Vivamus non molestie leo, non tincidunt diam. Mauris sagittis elit in velit ultricies aliquet sed in magna. Pellentesque semper, est nec consequat viverra, sem augue tincidunt nisi, a posuere nisi sapien sed sapien. Nulla facilisi.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="tm-flex">
                        <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-2-col-textbox-2 tm-textbox-padding">
                            <h2 class="tm-text-title">Nulla facilisi</h2>
                            <p class="tm-text">Donec vitae bibendum est, et ultrices urna. Curabitur ac bibendum augue, a convallis mi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed ultrices placerat arcu.</p>
                        </div>
                        <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-2-col-textbox-2 tm-textbox-padding">
                            <h2 class="tm-text-title">Aliquam sem sem</h2>
                            <p class="tm-text">Proin sagittis mauris dolor, vel efficitur lectus dictum nec. Sed ultrices placerat arcu, id malesuada metus cursus suscipit. Donex quis consectetur ligula. Proin accumsan eros id nisi porttitor, a facilisis quam cursus.</p>
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

            $('#tmNavbar .nav-link').on('click', function(){
                if ($('.navbar-toggler').is(':visible') && $('#tmNavbar').hasClass('show')) {
                    $('#tmNavbar').collapse('hide');
                }
            });
        });
    </script>
</body>
</html>
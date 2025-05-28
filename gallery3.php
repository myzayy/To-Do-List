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

        <div class="container-fluid tm-page-pad">
            <div class="tm-img-gallery-container tm-img-gallery-container-3">
                <div class="tm-img-gallery gallery-three">
                    <div class="tm-img-gallery-info-container">
                        <h2 class="tm-text-title tm-gallery-title"><span class="tm-white">Third Image Grid</span></h2>
                        <p class="tm-text"><span class="tm-white">Nulla efficitur, ligula et imperdiet volutpat, lacus tortor tempus massa, eget tempus quam nibh vel nulla. Maecenas purus sem, lobortis id odio in, ultrices scelerisque sapien.</span></p>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-11-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>One</span></h2>
                                <p class="tm-figure-description">Suspendisse id placerat risus. Mauris quis luctus risus.</p>
                                <a href="img/tm-img-11.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-12-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Two</span></h2>
                                <p class="tm-figure-description">Maecenas purus sem, lobortis id odio in sapien.</p>
                                <a href="img/tm-img-12.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-13-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Three</span></h2>
                                <p class="tm-figure-description">Suspendisse id placerat risus. Mauris quis luctus risus.</p>
                                <a href="img/tm-img-13.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-14-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Four</span></h2>
                                <p class="tm-figure-description">Maecenas purus sem, lobortis id odio in sapien.</p>
                                <a href="img/tm-img-14.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-15-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Five</span></h2>
                                <p class="tm-figure-description">Suspendisse id placerat risus. Mauris quis luctus risus.</p>
                                <a href="img/tm-img-15.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-16-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Six</span></h2>
                                <p class="tm-figure-description">Maecenas purus sem, lobortis id odio in sapien.</p>
                                <a href="img/tm-img-16.jpg">View more</a>
                            </figcaption>
                        </figure>
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
            $('body').addClass('loaded');

            if (typeof $.fn.magnificPopup === 'function') {
                $('.gallery-three').magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    gallery:{enabled:true}
                });
            }

            $('#tmNavbar .nav-link').on('click', function(){
                if ($('.navbar-toggler').is(':visible') && $('#tmNavbar').hasClass('show')) {
                    $('#tmNavbar').collapse('hide');
                }
            });
        });
    </script>
</body>
</html>
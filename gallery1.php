<?php
session_start();
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
            <div class="tm-img-gallery-container">
                <div class="tm-img-gallery gallery-one">
                    <div class="tm-img-gallery-info-container">
                        <h2 class="tm-text-title tm-gallery-title tm-white"><span class="tm-white">First Image Gallery</span></h2>
                        <p class="tm-text">Curabitur quis tellus sed orci rhoncus fermentum. Praesent sollicitudin scelerisque nunc et vehicula. Sed ex magna, elementum ut volutpat ut, vehicula quis metus.</p>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-01-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Image <span>One</span></h2>
                                <p class="tm-figure-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <a href="img/tm-img-01.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-02-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Image <span>Two</span></h2>
                                <p class="tm-figure-description">Maecenas purus sem, lobortis id odio in sapien.</p>
                                <a href="img/tm-img-02.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-03-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Image <span>Three</span></h2>
                                <p class="tm-figure-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <a href="img/tm-img-03.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-04-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Image <span>Four</span></h2>
                                <p class="tm-figure-description">Maecenas purus sem, lobortis id odio in sapien.</p>
                                <a href="img/tm-img-04.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'parts/footer.php'; ?>
    </div>

    <script src="js/jquery-1.11.3.min.js"></script>
    <script src="https://www.atlasestateagents.co.uk/javascript/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script>
        $(window).on('load', function(){
            $('body').addClass('loaded');

            if (typeof $.fn.magnificPopup === 'function') {
                $('.gallery-one').magnificPopup({
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
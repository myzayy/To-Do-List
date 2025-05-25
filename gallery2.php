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
            <div class="tm-img-gallery-container tm-img-gallery-container-2">
                <div class="tm-img-gallery gallery-two">
                    <div class="tm-img-gallery-info-container">
                        <h2 class="tm-text-title tm-gallery-title"><span class="tm-white">Second Gallery</span></h2>
                        <p class="tm-text"><span class="tm-white">Aenean nulla lorem, laoreet eu nibh et, lacinia ullamcorper dui. Nullam ut tincidunt odio. Morbi accumsan diam vel enim cursus, in dapibus eros tristique.</span></p>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-05-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>One</span></h2>
                                <p class="tm-figure-description">Suspendisse id placerat risus. Mauris quis luctus risus.</p>
                                <a href="img/tm-img-05.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-06-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Two</span></h2>
                                <p class="tm-figure-description">Maecenas purus sem, lobortis id odio in sapien.</p>
                                <a href="img/tm-img-06.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-07-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Three</span></h2>
                                <p class="tm-figure-description">Suspendisse id placerat risus. Mauris quis luctus risus.</p>
                                <a href="img/tm-img-07.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-08-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Four</span></h2>
                                <p class="tm-figure-description">Maecenas purus sem, lobortis id odio in sapien.</p>
                                <a href="img/tm-img-08.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-09-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Five</span></h2>
                                <p class="tm-figure-description">Suspendisse id placerat risus. Mauris quis luctus risus.</p>
                                <a href="img/tm-img-09.jpg">View more</a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="grid-item">
                        <figure class="effect-ruby">
                            <img src="img/tm-img-10-tn.jpg" alt="Image" class="img-fluid tm-img">
                            <figcaption>
                                <h2 class="tm-figure-title">Picture <span>Six</span></h2>
                                <p class="tm-figure-description">Maecenas purus sem, lobortis id odio in sapien.</p>
                                <a href="img/tm-img-10.jpg">View more</a>
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
                $('.gallery-two').magnificPopup({
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
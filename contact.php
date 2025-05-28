<?php
session_start();
require_once 'config/config.php';
$contact_message_status = ''; // Для повідомлень про відправку форми

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contact_name'])) {
    // Тут має бути логіка відправки email або збереження в БД
    // Наприклад:
    // $name = htmlspecialchars(trim($_POST['contact_name']));
    // $email = htmlspecialchars(trim($_POST['contact_email']));
    // $message = htmlspecialchars(trim($_POST['contact_message']));
    // if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
    //     // mail("your_email@example.com", "New Contact Form Submission from $name", $message, "From: $email");
    //     $contact_message_status = '<div class="alert alert-success">Ваше повідомлення успішно відправлено!</div>';
    // } else {
    //     $contact_message_status = '<div class="alert alert-danger">Будь ласка, заповніть всі поля коректно.</div>';
    // }
    $contact_message_status = '<div class="alert alert-info">Обробка контактної форми ще не реалізована.</div>'; // Заглушка
}
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
            <div class="tm-contact-page">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="tm-flex tm-contact-container">
                            <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-2-col-textbox-2 tm-textbox-padding tm-textbox-padding-contact">
                                <h2 class="tm-contact-info">Contact Us</h2>
                                <p class="tm-text">Praesent tempus dapibus odio nec elementum. Sed elementum est quis tortor faucibus, et molestie nibh finibus. Mauris condimentum ex vestibulum fringilla consectetur.</p>
                                
                                <?php echo $contact_message_status; // Вивід статусу відправки ?>

                                <form action="contact.php" method="post" class="tm-contact-form">
                                    <div class="form-group">
                                        <input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Name" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="email" id="contact_email" name="contact_email" class="form-control" placeholder="Email" required />
                                    </div>
                                    <div class="form-group">
                                        <textarea id="contact_message" name="contact_message" class="form-control" rows="5" placeholder="Your message" required></textarea>
                                    </div>
                                    <button type="submit" class="pull-xs-right tm-submit-btn">Send</button>
                                </form>
                            </div>
                            <div class="tm-bg-white-translucent text-xs-left tm-textbox tm-2-col-textbox-2 tm-textbox-padding tm-textbox-padding-contact">
                                <h2 class="tm-contact-info">123 New Street 11000, San Francisco, CA</h2>
                                <div id="google-map"></div>
                            </div>
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
        var map = '';
        var center;

        function initialize() {
            var mapOptions = {
                zoom: 15,
                center: new google.maps.LatLng(37.769725, -122.462154),
                scrollwheel: false
            };
            map = new google.maps.Map(document.getElementById('google-map'), mapOptions);
            google.maps.event.addDomListener(map, 'idle', function() {
                calculateCenter();
            });
            google.maps.event.addDomListener(window, 'resize', function() {
                map.setCenter(center);
            });
        }

        function calculateCenter() {
            center = map.getCenter();
        }

        function loadGoogleMap() {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            
            script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=initialize';
            document.body.appendChild(script);
        }

        $(window).on('load', function(){
            $('body').addClass('loaded');
            if (document.getElementById('google-map')) {
                loadGoogleMap(); // Google Map
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
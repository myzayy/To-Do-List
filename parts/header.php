<?php
// parts/header.php
// This file assumes that config/config.php (with BASE_PATH)
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Upper HTML Website Template</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
    
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>font-awesome-4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>css/templatemo-style.css">


    <style>
        /* === Початок ВБУДОВАНИХ стилів для виправлення відступів === */

        /* Забезпечуємо, що .cd-hero є позиціонованим контекстом для футера та навігації */
        .cd-hero {
            position: relative; 
            padding-bottom: 120px; /* Відступ для абсолютно позиціонованого футера. Налаштуйте за потребою (приблизно висота футера + запас). */
        }

        /* Головний контейнер контенту на сторінці, що йде після навігації */
        /* Має бути <div class="container-fluid tm-page-pad"> */
        .cd-hero > .container-fluid.tm-page-pad {
            padding-top: 100px;    /* Початковий відступ для найменших екранів */
            padding-left: 10px;    /* Базовий горизонтальний відступ */
            padding-right: 10px;   /* Базовий горизонтальний відступ */
            padding-bottom: 70px;  /* Нижній відступ для контенту (можна взяти з оригінального .tm-page-pad або налаштувати) */
            min-height: calc(100vh - 220px); /* Орієнтовна мін. висота: 100% висоти екрану мінус ~ (висота навігації + висота футера + відступи) */
        }

        /* Адаптивні верхні відступи для різних розмірів екрана */
        @media only screen and (min-width: 768px) {
            .cd-hero > .container-fluid.tm-page-pad {
                padding-top: 120px; /* Для планшетів */
                padding-left: 30px;
                padding-right: 30px;
            }
        }

        @media only screen and (min-width: 992px) {
            .cd-hero > .container-fluid.tm-page-pad {
                padding-top: 130px; /* Для середніх десктопів */
                padding-left: 20px;
                padding-right: 20px;
            }
        }

        @media only screen and (min-width: 1063px) {
            /* На цьому брейкпойнті .cd-slider-nav отримує top: 30px */
            .cd-hero > .container-fluid.tm-page-pad {
                padding-top: 140px; /* Висота посилань навігації ~80px + top 30px + запас ~30px */
            }
        }

        @media only screen and (min-width: 1333px) {
            /* На цьому брейкпойнті посилання навігації стають ~110px заввишки */
            .cd-hero > .container-fluid.tm-page-pad {
                padding-top: 170px; /* Висота посилань навігації ~110px + top 30px + запас ~30px */
            }
        }
        /* === Кінець ВБУДОВАНИХ стилів для виправлення відступів === */
    </style>
</head>
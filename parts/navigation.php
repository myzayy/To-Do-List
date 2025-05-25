<?php
// session_start(); // Якщо ще не запущено на сторінці, що включає цей файл
?>
<div class="cd-slider-nav">
    <nav class="navbar">
        <div class="tm-navbar-bg">
            <a class="navbar-brand text-uppercase" href="#"><i class="fa fa-flash tm-brand-icon"></i>Upper</a>
            <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#tmNavbar">
                &#9776;
            </button>
            <div class="collapse navbar-toggleable-md text-xs-center text-uppercase tm-navbar" id="tmNavbar">
                <ul class="nav navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item active selected">
                            <a class="nav-link" href="#0" data-no="1">Home <span class="sr-only">(current)</span></a>
                        </li>                                
                        <li class="nav-item">
                            <a class="nav-link" href="#0" data-no="2">First Gallery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#0" data-no="3">Second</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#0" data-no="4">Third Grid</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#0" data-no="5">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#0" data-no="6">Contact</a>
                        </li>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="admin_panel.php">Admin Panel</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Вихід (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Вхід</a></li>
                        <li class="nav-item"><a class="nav-link" href="config/register.php">Реєстрація</a></li>
                    <?php endif; ?>
                </ul>
            </div>                        
        </div>
    </nav>
</div>
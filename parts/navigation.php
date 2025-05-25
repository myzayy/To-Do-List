<?php
// parts/navigation.php
// session_start(); // ession_start() must be on the page that connects this file, if not already called
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="cd-slider-nav">
    <nav class="navbar">
        <div class="tm-navbar-bg">

            <a class="navbar-brand text-uppercase" href="index.php"><i class="fa fa-flash tm-brand-icon"></i>Upper</a>

            <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#tmNavbar">
                &#9776;
            </button>
            <div class="collapse navbar-toggleable-md text-xs-center text-uppercase tm-navbar" id="tmNavbar">
                <ul class="nav navbar-nav">
                    <li class="nav-item <?php echo ($current_page == 'index.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page == 'gallery1.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="gallery1.php">First Gallery</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page == 'gallery2.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="gallery2.php">Second</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page == 'gallery3.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="gallery3.php">Third Grid</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page == 'about.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="about.php">About Us</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page == 'contact.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                            <li class="nav-item <?php echo ($current_page == 'admin_panel.php' ? 'active selected' : ''); ?>">
                                <a class="nav-link" href="admin_panel.php">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item <?php echo ($current_page == 'login.php' ? 'active selected' : ''); ?>">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item <?php echo ($current_page == 'register.php' ? 'active selected' : ''); ?>">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</div>
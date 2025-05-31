<?php
// parts/navigation.php


$current_page_filename = basename($_SERVER['PHP_SELF']); // Get only the filename
$current_script_path = $_SERVER['PHP_SELF']; // Get the full script path from the web root

$relative_script_path = str_replace(rtrim(BASE_PATH, '/'), '', $current_script_path);
if (strpos($relative_script_path, '/') !== 0) {
    $relative_script_path = '/' . $relative_script_path;
}

?>
<div class="cd-slider-nav">
    <nav class="navbar">
        <div class="tm-navbar-bg">

            <a class="navbar-brand text-uppercase" href="<?php echo BASE_PATH; ?>index.php"><i class="fa fa-flash tm-brand-icon"></i>Upper</a>

            <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#tmNavbar">
                &#9776;
            </button>
            <div class="collapse navbar-toggleable-md text-xs-center text-uppercase tm-navbar" id="tmNavbar">
                <ul class="nav navbar-nav">
                    
                    <li class="nav-item <?php echo (rtrim($relative_script_path, '/') === '/index.php' || $relative_script_path === '/' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="<?php echo BASE_PATH; ?>index.php">Home</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page_filename == 'gallery1.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="<?php echo BASE_PATH; ?>gallery1.php">First Gallery</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page_filename == 'gallery2.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="<?php echo BASE_PATH; ?>gallery2.php">Second</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page_filename == 'gallery3.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="<?php echo BASE_PATH; ?>gallery3.php">Third Grid</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page_filename == 'about.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="<?php echo BASE_PATH; ?>about.php">About Us</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page_filename == 'contact.php' ? 'active selected' : ''); ?>">
                        <a class="nav-link" href="<?php echo BASE_PATH; ?>contact.php">Contact</a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): // User is logged in ?>
                        <li class="nav-item <?php echo ($current_page_filename == 'tasks.php' ? 'active selected' : ''); ?>">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>tasks.php">My Tasks</a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): // User is an admin ?>
                            <?php
                                // Check if the current page is the admin panel's index.php
                                $isAdminPanelPage = (strpos($current_script_path, rtrim(BASE_PATH, '/') . '/admin/index.php') !== false);
                            ?>
                            <li class="nav-item <?php echo ($isAdminPanelPage ? 'active selected' : ''); ?>">
                                <a class="nav-link" href="<?php echo BASE_PATH; ?>admin/index.php">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                        </li>
                    <?php else: // User is not logged in ?>
                        <li class="nav-item <?php echo ($current_page_filename == 'login.php' ? 'active selected' : ''); ?>">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>login.php">Login</a>
                        </li>
                        <li class="nav-item <?php echo ($current_page_filename == 'register.php' ? 'active selected' : ''); ?>">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</div>
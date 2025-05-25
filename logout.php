<?php
session_start();

// Deleting all session variables
$_SESSION = array();

// Deleting cookies
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// kill session
session_destroy();

// Redirect to the login page or home
header("Location: login.php");
exit();
?>
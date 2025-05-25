<?php
session_start(); // Starting a session for possible messages or redirects

// If the user is already logged in, redirect to the main page
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // ../index.php, cause register.php in config/
    exit();
}

require 'connect.php'; // connect to db

$registration_error = '';
$registration_success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password_plain = $_POST['password']; // Password to check length, etc. before hashing

    // Validation
    if (empty($username) || empty($password_plain)) {
        $registration_error = "The username and password cannot be empty.";
    } elseif (strlen($password_plain) < 6) { // minimum password length
        $registration_error = "The password must contain at least 6 characters.";
    } else {
        // Check if a user with this name exists
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $registration_error = "A user with this name already exists.";
        } else {
            // Password hashing
            $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT); //

            // Adding a new user with the ‘user’ role
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')"); //
            $stmt_insert->bind_param("ss", $username, $password_hashed); //

            if ($stmt_insert->execute()) { //
                $registration_success = "Registration is successful! Now you can <a href='../login.php'>login</a>."; // link to login.php
            } else {
                $registration_error = "Registration error: " . $stmt_insert->error; //
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
// $conn->close(); 
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f0f0; margin: 0; }
        .container { background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 3px; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration</h2>
        <?php if ($registration_error): ?>
            <p class="error"><?php echo $registration_error; ?></p>
        <?php endif; ?>
        <?php if ($registration_success): ?>
            <p class="success"><?php echo $registration_success; ?></p>
        <?php else: // Show the form only if there is no successful message ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required> 
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="../login.php">Login here.</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
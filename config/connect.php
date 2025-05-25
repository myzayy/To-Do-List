<?php
// data for connect
$host = 'localhost';      // server
$dbname = 'todo_list';  // name
$username = 'root';       // username
$password = '';           // password

// connect by mysqli
$conn = new mysqli($host, $username, $password, $dbname);

// 3. connection check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connection succes!";
}

// connection close
// $conn->close();
// ?>

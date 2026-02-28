<?php
$host = "db_host";
$user = "all_db_user";   // replace with your MySQL username
$pass = "password";      // replace with your MySQL password
$db   = "simple_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

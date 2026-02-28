<?php
$XVWA_WEBROOT = "";
$host = "db_host";
$dbname = 'xvwa';
$user = "all_db_user";
$pass = "password";
$conn = new mysqli($host,$user,$pass,$dbname);
$conn1 = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

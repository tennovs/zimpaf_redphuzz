<?php

$username = "all_db_user";
$pass = "password";
$database = "wackopicko";

require_once("database.php");
$db = new DB("db_host", $username, $pass, $database);

define("OURDB", $db);

?>

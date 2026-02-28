<?php
//http://localhost/orders/index_cashiers.html
//Employee name: alice
//File Name: schedule
define("DB_ROOT", "mysql_db/");
include (DB_ROOT . 'db.php');

$name = base64_decode($_GET['name']); //' UNION SELECT '.'  #
$file = base64_decode($_GET['file']); //schedule/../../../../../../../../../etc/passwd 
echo $name . "<br>";
echo $file . "<br>";


if(!is_numeric($name) && !is_numeric($file)){
    echo "Inputs are valid<br>";
    if(fnmatch("schedule*", $file)){
        $query  = "SELECT role from users where username = '$name'"; //vuln
        $result = mysqli_query($conn, $query);
        $row    = mysqli_fetch_assoc($result);
        $role   = $row['role'];
 //       $path   = "schedule/" . $role . ".txt";
	    $path = $role . "/" . $file;        //vuln
        echo $path . "<br>";
        include("$path");
    }else{
        echo "ERROR: No file name started with $file";
        exit;
    }
    $query  = "SELECT username FROM users WHERE role = 'branch_manager'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $manager = $row['username'];
    echo $manager . "<br>";

    echo "Schedule of $role is verified by $manager.";
}
?>

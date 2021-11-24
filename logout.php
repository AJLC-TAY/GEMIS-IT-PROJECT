<?php   
session_start(); 
require_once('class/config.php');
$dbConfig = new dbConfig();
$con = $dbConfig->connect();
$date_time = date('Y-m-d H:i:s');
                // $id = $_SESSION['user_id'] ?? 0;
                $query = "INSERT INTO historylogs (id_no, user_type, action, datetime, sy_id) VALUES('{$_SESSION['user_id']}', '{$_SESSION['user_type']}', 'Logout', '$date_time', '{$_SESSION['sy_id']}' );";
                $result = mysqli_query($con,$query);

session_destroy(); 
header("location: login.php"); 
exit();
?>
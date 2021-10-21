<?php
session_start();
if(!isset($_SESSION['id']) || $_SESSION['user_type'] != 'ST') {
    header("location: ../login.php");
}
?>
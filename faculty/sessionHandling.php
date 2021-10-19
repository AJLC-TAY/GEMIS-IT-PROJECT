<?php
session_start();
if(!isset($_SESSION['id']) || $_SESSION['user_type'] != 'FA') {
    header("location: ../login.php");
}
?>
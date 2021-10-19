<?php
session_start();
if(!isset($_SESSION['id']) || $_SESSION['user_type'] != 'AD') {
    header("location: ../login.php");
}
?>
<?php 
require_once("../class/Student.php");
$student = new StudentModule();

if (isset($_POST['action']) && $_POST['action'] === 'enroll') {
    $student->enrollOldStudent();
    $student ->enterLog("Enroll");
}
<?php
require_once ("../class/Student.php");
$student = new StudentModule();

if (isset($_GET['data']) && $_GET['data'] == 'schedule') {
    $student->getSubjectSchedule();
}

?>
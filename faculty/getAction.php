<?php

require_once ("../class/Faculty.php");
$faculty = new FacultyModule();
if (isset($_GET['data']) && $_GET['data'] == 'student') {
    $faculty->getClass();
}

if (isset($_GET['data']) && $_GET['data'] == 'classGrades') {
    $faculty->getClassGrades();
}
?>
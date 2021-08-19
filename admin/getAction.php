<?php
include('../class/Administration.php');
$admin = new Administration();

if (isset($_GET['data']) && $_GET['data'] == 'program') {
    $admin->listProgramsUnderCurrJSON('program');
}

if (isset($_GET['data']) && $_GET['data'] == 'subjects') {
    $admin->listSubjectsJSON();
}

if (isset($_GET['data']) && $_GET['data'] == 'faculty') {
    $admin->listFacultyJSON();
}

if (isset($_GET['data']) && $_GET['data'] == 'student') {
    $admin->listStudentJSON();
}
?>
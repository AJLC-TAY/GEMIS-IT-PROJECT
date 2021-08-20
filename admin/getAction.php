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
<<<<<<< HEAD

if (isset($_GET['data']) && $_GET['data'] == 'student') {
    $admin->listStudentJSON();
}
?>
=======
?>
>>>>>>> f85611a257b5f349311e4c38cad14640fddadf20

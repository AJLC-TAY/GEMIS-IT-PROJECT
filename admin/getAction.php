<?php
include('../class/Administration.php');
$admin = new Administration();

if (isset($_GET['data']) && $_GET['data'] == 'administrators') {
    $admin->listAdministratorsJSON();
}

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
if (isset($_GET['data']) && $_GET['data'] == 'school_year') {
    $admin->listSYJSON();
}
if (isset($_GET['data']) && $_GET['data'] == 'section') {
    $admin->listSectionJSON();
}
if (isset($_GET['data']) && $_GET['data'] == 'enrollees') {
//    $admin->listEnrolleesJSON();
    $admin->getEnrollees();
}

if (isset($_GET['data']) && $_GET['data'] == 'faculty-privilege') {
    $admin->listFacultyPrivilegeJSON();
}
// if (isset($_GET['data']) && $_GET['data'] == 'sectionOption') {
//     $admin->listSectionOptionJSON($_GET['teacher_id']);
// }

if (isset($_GET['data']) && $_GET['data'] == 'fullSection') {
    $admin->listFullSectionJSON();
}

if (isset($_GET['data']) && $_GET['data'] == 'enrollFilters') {
   $test =  $admin->getEnrollFilters();
}
?>


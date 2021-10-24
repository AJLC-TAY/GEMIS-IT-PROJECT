<?php
require_once ("../class/Faculty.php");
$faculty = new FacultyModule();
/******** FACULTY ********/
if ((isset($_POST['profile']) && $_POST['profile'] == 'faculty')
    && $_POST['action'] === 'edit') {
    $faculty->processFaculty();
}
if ((isset($_POST['action']) && $_POST['action'] == 'changeAttendance')) {
    $faculty->changeAttendance();
}
if (isset($_POST['export'])) {
    $faculty->exportSubjectGradesToCSV();
    // $faculty->tryExport();
}
if ((isset($_POST['action']) && $_POST['action'] == 'gradeClass')) {
    $faculty->editGrades();
    // $faculty->tryExport();
}

/** ENROLLMENT */
if (isset($_POST['action']) && $_POST['action'] === 'validateEnrollment') {
    $faculty->validateEnrollment();
}

// if ((isset($_POST['action']) && $_POST['action'] == 'export')) {
//     $faculty->exportSubjectGradesToCSV();
// }
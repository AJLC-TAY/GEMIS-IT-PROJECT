<?php
require_once ("../class/Faculty.php");
$faculty = new FacultyModule();
/******** FACULTY ********/
if (isset($_POST['action']) && $_POST['action'] === 'enroll') {
    $faculty->enroll();
    $faculty->enterLog("Enroll Student");

}

if ((isset($_POST['profile']) && $_POST['profile'] == 'faculty')
    && $_POST['action'] === 'edit') {
    $faculty->processFaculty();
    $faculty->enterLog("Edit Details");
}
if ((isset($_POST['action']) && $_POST['action'] == 'changeAttendance')) {
    $faculty->changeAttendance();
    // $faculty->enterLog("Change Attendance");

}
if (isset($_POST['export'])) {
    $faculty->exportSubjectGradesToCSV();
    $faculty->enterLog("Export Subject Grades");

    // $faculty->tryExport();
}
if ((isset($_POST['action']) && $_POST['action'] == 'gradeClass')) {
    $faculty->gradeClass();
    $faculty->enterLog("Grade Subject Class");
}
if ((isset($_POST['action']) && $_POST['action'] == 'gradeAdvisory')) {
    $faculty->gradeAdvisory();
    $faculty->enterLog("Grade Advisory Class");
}

if ((isset($_POST['action']) && $_POST['action'] == 'gradeValues')) {
    $faculty->updateValueGrades();
    $faculty->enterLog("Update Values Grade");
}
if ((isset($_POST['action']) && $_POST['action'] == 'calculateGeneralAverage')) {
    $faculty->calculateGenAveBySection();
}

/** ENROLLMENT */
if (isset($_POST['action']) && $_POST['action'] === 'validateEnrollment') {
    $faculty->validateEnrollment();
    $faculty->enterLog("Validate Enrollment");

}

if (isset($_POST['action']) && $_POST['action'] === 'promote') {
    $faculty->promoteStudent();
    $faculty->enterLog("promote");
}

if (isset($_POST['action']) && $_POST['action'] === 'assessTransferee') {
    $faculty->assessTransferee();
    $faculty->enterLog("assess Transferee");

}
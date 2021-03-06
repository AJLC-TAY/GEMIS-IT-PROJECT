<?php
require_once ("../class/Faculty.php");
$faculty = new FacultyModule();
if (isset($_GET['data']) && $_GET['data'] == 'student') {
    $faculty->getClass();
}
if (isset($_GET['data']) && $_GET['data'] == 'enrollees') {
    $faculty->getEnrollees();
}
if (isset($_GET['data']) && $_GET['data'] == 'classGrades') {
    $faculty->getClassGrades();
}
if (isset($_GET['data']) && $_GET['data'] == 'class_attendance') {
    $faculty->listAttendance(TRUE);
}
if (isset($_GET['data']) && $_GET['data'] == 'student-award-selection') {
    $faculty->listStudentAwardSelection(TRUE);
}
if (isset($_GET['data']) && $_GET['data'] == 'for-promotion') {
    $faculty->listStudentsForPromotion();
}
if (isset($_GET['data']) && $_GET['data'] == 'schedule') {
    $faculty->getSubjectSchedule();
}
if (isset($_GET['data']) && $_GET['data'] == 'enroll-data') {
    $faculty->listEnrollmentData(TRUE);
}
if (isset($_GET['data']) && $_GET['data'] == 'advisoryClasses') {
    $faculty->listAdvisoryClasses(TRUE);
}
?>
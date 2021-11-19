<?php
require_once('../class/Administration.php');
$admin = new Administration();
if (isset($_GET['data']) && $_GET['data'] == 'administrators') {
    $admin->listAdministratorsJSON();
}
if (isset($_GET['data']) && $_GET['data'] == 'grdAdvisor') {
    $admin->getClass();
}
if (isset($_GET['data']) && $_GET['data'] == 'classGrades') {
    $admin->getClassGrades();
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
if (isset($_GET['data']) && $_GET['data'] == 'students') { # section options for transfering or adding
    $admin->listStudents(TRUE);
}
if (isset($_GET['data']) && $_GET['data'] == 'school_year') {
    $admin->listSYJSON();
}
if (isset($_GET['data']) && $_GET['data'] == 'section') {
    $admin->listSectionJSON();
}
if (isset($_GET['data']) && $_GET['data'] == 'sectionInfo') {
    $admin->getSectionSubClassInfo();
}
if (isset($_GET['data']) && $_GET['data'] == 'sections') {
    $admin->listSectionJSON($_GET['sy_id'], $_GET['grade'], $_GET['section']);
}
if (isset($_GET['data']) && $_GET['data'] == 'enrollees') {
    $admin->getEnrollees();
}
if (isset($_GET['data']) && $_GET['data'] == 'enrolled') {
    $admin->getEnrolled();
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
   $admin->getEnrollFilters();
}
if (isset($_GET['data']) && $_GET['data'] == 'signatory') {
   $admin->listSignatory(TRUE);
}
if (isset($_GET['data']) && $_GET['data'] == 'advisoryClasses') {
   $admin->listAdvisoryClasses(NULL, TRUE);
}
if (isset($_GET['data']) && $_GET['data'] == 'all-sub-classes') {
   $admin->listAllSubjectClasses(TRUE);
}
if (isset($_GET['data']) && $_GET['data'] == 'enroll-data') {
    $admin->listEnrollmentData(TRUE);
}
if (isset($_GET['data']) && $_GET['data'] == 'adminCount') {
    $admin->checkAdministratorCount();
}
if (isset($_GET['data']) && $_GET['data'] == 'academicExcellence') {
    $admin->getExcellenceAwardData();
}
if (isset($_GET['data']) && $_GET['data'] == 'perfectAttendance') {
    $admin->getPerfectAttendance();
}
if (isset($_GET['data']) && $_GET['data'] == 'schedule') {
    $admin->getSubjectSchedule();
}

if (isset($_GET['data']) && $_GET['data'] == 'attendance') {
    $admin->getStudentAttendanceJSON();
}

?>


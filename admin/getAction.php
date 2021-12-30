<?php
require_once('../class/Administration.php');
$admin = new Administration();
/** PASSWORD */
if (isset($_GET['data']) && $_GET['data'] == 'validatePassword') {
    $admin->validatePassword();
}
if (isset($_GET['data']) && $_GET['data'] == 'systemLogs') {
    $admin->getSystemLogs();
}
if (isset($_GET['data']) && $_GET['data'] == 'checkCodeUnique') {
    $admin->checkCodeUnique();
}
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
if (isset($_GET['data']) && $_GET['data'] == 'students') {
    $admin->listStudents(TRUE);
}
/** SCHOOL YEAR */
if (isset($_GET['data']) && $_GET['data'] == 'action-maintenance' && ($_GET['action'] == 'restore' || $_GET['action'] == 'delete')) {
    $admin->performMaintenance();
}

if (isset($_GET['data']) && $_GET['data'] == 'action-maintenance' && $_GET['action'] == 'backupData') {
    $admin->exportTables();
    $admin->enterLog("Exported backup ({$_SESSION['school_year']}).");
} 
if (isset($_GET['data']) && $_GET['data'] == 'action' && in_array($_GET['action'], ['archivesy', 'unarchivesy'])) {
    $admin->performMaintenance();
}
if (isset($_GET['data']) && $_GET['data'] == 'backupFiles') {
    $admin->listBackupFiles();
}
if (isset($_GET['data']) && $_GET['data'] == 'archivedSY') {
    $admin->listArchivedSY();
}
if (isset($_GET['data']) && $_GET['data'] == 'end_school_year') {
    $admin->endSchoolYear();
}
if (isset($_GET['data']) && $_GET['data'] == 'school_year') {
    $admin->listSYJSON();
}
if (isset($_GET['data']) && $_GET['data'] == 'school_years') {
    $admin->checkIfSYUnique();
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

if (isset($_GET['data']) && $_GET['data'] == 'fullSection') {
    $admin->listFullSectionJSON();
}
if (isset($_GET['data']) && $_GET['data'] == 'sectionAdviserHistory') {
    $admin->listSectionAdvisersHistory();
}

if (isset($_GET['data']) && $_GET['data'] == 'enrollFilters') {
   $admin->getEnrollFilters();
}
if (isset($_GET['data']) && $_GET['data'] == 'signatory') {
   $admin->listSignatory(TRUE);
}
if (isset($_GET['data']) && $_GET['data'] == 'advisoryClasses') {
   $admin->listAdvisoryClasses(TRUE);
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
/** AWARDS */
if (isset($_GET['data']) && $_GET['data'] == 'academicExcellence') {
    $admin->getExcellenceAwardData();
}
if (isset($_GET['data']) && $_GET['data'] == 'perfectAttendance') {
    $admin->getPerfectAttendance();
}
if (isset($_GET['data']) && $_GET['data'] == 'otherAwards') {
    $admin->getAwardDataFromSubject();
}
if (isset($_GET['data']) && $_GET['data'] == 'schedule') {
    $admin->getSubjectSchedule();
}
/** AWARDS END */

if (isset($_GET['data']) && $_GET['data'] == 'attendance') {
    $admin->getStudentAttendanceJSON();
}

/** TRANSFEREE  */
if (isset($_GET['data']) && $_GET['data'] == 'transfereesubject') {
    $admin->getTransfereeSubject();
}
?>


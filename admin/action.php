<?php
include('../class/Administration.php');
$admin = new Administration();

/******** TEST ********/
if (isset($_POST['action']) && $_POST['action'] === 'validateImage') {
    echo "image";
//    print_r($admin->validateImage('image', 5000000));
    Administration::saveImage("2194014", 'image', "../student_assets", 'psa');
}


/******** ADMINISTRATOR ********/
if (isset($_POST['action']) && $_POST['action'] === 'addAdministrator') {
    $admin->addAdministrator();
}

if (isset($_POST['action']) && $_POST['action'] === 'editAdministrator') {
    $admin->editAdministrator();
}

/******** SCHOOL YEAR ********/
if (isset($_POST['action']) && $_POST['action'] === 'initializeSY') {
    $admin->initializeSY();
}
if (isset($_POST['action']) && $_POST['action'] === 'editSY') {
    $admin->editSY();
}
if (isset($_POST['action']) && $_POST['action'] === 'editEnrollStatus') {
    $admin->editEnrollStatus();
}

if (isset($_POST['action']) && $_POST['action'] === 'enroll') {
    $admin->enroll();
}

/******** USER ********/
if (isset($_POST['action']) && $_POST['action'] === 'deactivate') {
    // $admin->deactivate();
}

/******** CURRICULUM ********/
if (isset($_POST['action']) && $_POST['action'] === 'addCurriculum') {
    $admin->addCurriculum();
}

if (isset($_POST['action']) && $_POST['action'] === 'deleteCurriculum') {
    $admin->deleteCurriculum();
}

if (isset($_POST['action']) && $_POST['action'] === 'getCurriculumJSON') {
    $admin->listCurriculumJSON('curriculum');
}

if (isset($_POST['action']) && $_POST['action'] === 'updateCurriculum') {
    $admin->updateCurriculum();
}

if (isset($_POST['action']) && $_POST['action'] === 'archiveCurriculum') {
    // $admin->moveCurriculum('archived_curriculum','curriculum','archived_program','program','archived_sharedsubject','sharedsubject');
    $admin->moveCurriculum("", "archived_");
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveCurriculum') {
    // $admin->moveCurriculum('curriculum','archived_curriculum','program','archived_program','sharedsubject','archived_sharedsubject');
    $admin->moveCurriculum("archived_", "");
}

if (isset($_POST['action']) && $_POST['action'] === 'getArchivedCurriculumJSON') {
    echo ("from getArchivedCurriculumJSON");
    $admin->listCurriculumJSON('archived_curriculum');
}

/******** PROGRAM ********/
if (isset($_POST['action']) && $_POST['action'] === 'addProgram') {
    $admin->addProgram();
}

if (isset($_POST['action']) && $_POST['action'] === 'deleteProgram') {
    $admin->deleteProgram();
}

if (isset($_POST['action']) && $_POST['action'] === 'getProgramJSON') {
    $admin->listProgramsJSON('program');
}

if (isset($_POST['action']) && $_POST['action'] === 'updateProgram') {
    $admin->updateProgram();
}

if (isset($_POST['action']) && $_POST['action'] === 'archiveProgram') {
    // echo("from action.php: archiveProgram");
    // $admin->moveProgram('archived_program','program','archived_sharedsubject','sharedsubject');
    $admin->moveProgram('', 'archived_');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveProgram') {
    // $admin->moveProgram('program','archived_program','sharedsubject','archived_sharedsubject');
    $admin->moveProgram('archived_', '');

}

if (isset($_POST['action']) && $_POST['action'] === 'getArchivedProgramJSON') {
    $admin->listProgramsJSON('archived_program');
}

/******** SUBJECT ********/
if (isset($_POST['action']) && $_POST['action'] === 'addSubject') {
    $admin->addSubject();
}

if (isset($_POST['action']) && $_POST['action'] === 'deleteSubject') {
    $admin->deleteSubject();
}



if (isset($_POST['action']) && $_POST['action'] === 'getSubjectJSON') {
    $admin->listSubjectsJSON();
}

if (isset($_POST['action']) && $_POST['action'] === 'getAllSubjectJSON') {
    $admin->listAllSub('subject');
}

if (isset($_POST['action']) && $_POST['action'] === 'getArchiveSubjectJSON') {
    $admin->listAllSub('archived_subject');
}

if (isset($_POST['action']) && $_POST['action'] === 'updateSubject') {
    $admin->updateSubject();
}

if (isset($_POST['action']) && $_POST['action'] === 'archiveSubject') {
    $admin->moveSubject("", "archived_");
    $admin->listArchSubjectsJSON();
    // $admin->moveSubject('archived_subject','subject','archived_sharedsubject','sharedsubject','archived_requisite','requisite');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveSubject') {
    $admin->moveSubject("archived_", "");
    $admin->listArchSubjectsJSON();
    // $admin->moveSubject('subject','archived_subject','sharedsubject','archived_sharedsubject', 'requisite','archived_requisite');
}

/******** FACULTY ********/
if ((isset($_POST['profile']) && $_POST['profile'] == 'faculty') 
     && 
     ($_POST['action'] === 'add' || $_POST['action'] === 'edit')) {
    $admin->processFaculty();
}
if (isset($_POST['action']) && $_POST['action'] === 'updateFacultyRoles') {
    $admin->updateFacultyRoles();
}
if (isset($_POST['action']) && $_POST['action'] === 'editDepartment') {
    $admin->updateFacultyDepartment();
}

if (isset($_POST['action']) && $_POST['action'] === 'editSubject') {
    $admin->updateFacultySubjects($_POST['teacher_id']);
}

if (isset($_POST['action']) && $_POST['action'] === 'advisoryChange') {
    $admin->changeAdvisory();
}
if (isset($_POST['action']) && $_POST['action'] === 'changeEnrollPriv') {
    $admin->changeEnrollPriv();
}

/******** SECTION ********/
if (isset($_POST['action']) && $_POST['action'] === 'getSectionJSON') {
    $admin->listSectionJSON();
}

if (isset($_POST['action']) && $_POST['action'] === 'addSection') {
    $admin->addSection();
}

if (isset($_POST['action']) && $_POST['action'] === 'editSection') {
    $admin->editSection();
}
if (isset($_POST['action']) && $_POST['action'] === 'assignSubClasses') {
    $admin->assignSubClasses($_POST['teacher_id']);
}
if (isset($_POST['action']) && $_POST['action'] === 'unassignSubClasses') {
    $admin->unassignSubClasses();
}

/******** STUDENT ********/
if (isset($_POST['action']) && $_POST['action'] === 'transferStudent') {
    echo('from action:transferStud');
    // $admin->transferStudent();
}
if (isset($_POST['action']) && $_POST['action'] === 'updateStudent') {
    // echo('from action:update');
    $admin->editStudent();
}
if (isset($_POST['action']) && $_POST['action'] === 'archiveStudent') {
    $admin->moveSubject("", "archived_");
    $admin->listArchStudentsJSON();
    // $admin->moveSubject('archived_subject','subject','archived_sharedsubject','sharedsubject','archived_requisite','requisite');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveStudent') {
    $admin->moveSubject("archived_", "");
    $admin->listArchStudentsJSON();
    // $admin->moveSubject('subject','archived_subject','sharedsubject','archived_sharedsubject', 'requisite','archived_requisite');
}

if (isset($_POST['action']) && $_POST['action'] === 'forgotPassword'){
    $admin->forgotPassword();
}
?>
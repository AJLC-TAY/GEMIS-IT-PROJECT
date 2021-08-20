<?php
include('../class/Administration.php');
$admin = new Administration();

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
    $admin = $admin->updateCurriculum();
}

if (isset($_POST['action']) && $_POST['action'] === 'archiveCurriculum') {
    $admin->moveCurriculum('archived_curriculum','curriculum','archived_program','program','archived_sharedsubject','sharedsubject');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveCurriculum') {
    echo("from unarchiveCurriculum");
    $admin->moveCurriculum('curriculum','archived_curriculum','program','archived_program','sharedsubject','archived_sharedsubject');
    
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
    echo("from action.php: archiveProgram");
    $admin->moveProgram('archived_program','program','archived_sharedsubject','sharedsubject');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveProgram') {
    $admin->moveProgram('program','archived_program','sharedsubject','archived_sharedsubject');
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

if (isset($_POST['action']) && $_POST['action'] === 'updateSubject') {
    $admin->updateSubject();
}

if (isset($_POST['action']) && $_POST['action'] === 'archiveSubject') {
    $admin->moveSubject('archived_subject','subject','archived_sharedsubject','sharedsubject','archived_requisite','requisite');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveSubject') {
    $admin->moveSubject('subject','archived_subject','sharedsubject','archived_sharedsubject', 'requisite','archived_requisite');
}

/******** FACULTY ********/
if (isset($_POST['action']) && $_POST['action'] === 'addFaculty') {
    $admin->addFaculty();
}
if (isset($_POST['action']) && $_POST['action'] === 'updateFacultyRoles') {
    $admin->updateFacultyRoles();
}

?>
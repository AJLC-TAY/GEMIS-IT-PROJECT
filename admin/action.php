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
    $admin = $admin->moveCurriculum('archived_curriculum','curriculum','archived_program','program','archived_sharedsubject','sharedsubject');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveCurriculum') {
    echo("from unarchiveCurriculum");
    $admin = $admin->moveCurriculum('curriculum','archived_curriculum','program','archived_program','sharedsubject','archived_sharedsubject');
    
}

if (isset($_POST['action']) && $_POST['action'] === 'getArchivedCurrJSON') {
    $admin = $admin->listCurriculumJSON('archived_curriculum');
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
    $admin = $admin->moveProgram('archived_program','program','archived_sharedsubject','sharedsubject');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveProgram') {
    $admin = $admin->moveProgram('program','archived_program','sharedsubject','archived_sharedsubject');
}

if (isset($_POST['action']) && $_POST['action'] === 'getArchiveProgJSON') {
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
?>
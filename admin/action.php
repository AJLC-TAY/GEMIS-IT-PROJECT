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
    $admin->listCurriculumJSON();
}

if (isset($_POST['action']) && $_POST['action'] === 'updateCurriculum') {
    $admin = $admin->updateCurriculum();
}

if (isset($_POST['action']) && $_POST['action'] === 'archiveCurriculum') {
    $admin = $admin->transferCurriculum('archived_curriculum','curriculum');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveCurriculum') {
    echo("from unarchiveCurriculum");
    $admin = $admin->transferCurriculum('curriculum','archived_curriculum');
    
}

if (isset($_POST['action']) && $_POST['action'] === 'getArchivedCurrJSON') {
    $admin = $admin->listArchivedCurrJSON();
}

/******** PROGRAM ********/
if (isset($_POST['action']) && $_POST['action'] === 'addProgram') {
    $admin->addProgram();
}

if (isset($_POST['action']) && $_POST['action'] === 'deleteProgram') {
    $admin->deleteProgram();
}

if (isset($_POST['action']) && $_POST['action'] === 'getProgramJSON') {
    $admin->listProgramsJSON();
}

if (isset($_POST['action']) && $_POST['action'] === 'updateProgram') {
    $admin->updateProgram();
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
    $admin->transferSubject('archived_subject','subject');
}
?>
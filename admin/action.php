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
    $code = $admin->updateCurriculum();
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

// /******** SUBJECT ********/
// if (isset($_POST['action']) && $_POST['action'] === 'addSubject') {
//     $admin->addSubject();
// }

// if (isset($_POST['action']) && $_POST['action'] === 'deleteSubject') {
//     $admin->deleteSubject();
// }

// if (isset($_POST['action']) && $_POST['action'] === 'getSubjectJSON') {
//     $admin->listSubjectJSON();
// }

// if (isset($_POST['action']) && $_POST['action'] === 'updateSubject') {
//     $admin->updateSubject();
// }
?>
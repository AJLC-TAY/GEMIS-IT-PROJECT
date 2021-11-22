<?php
include('../class/Administration.php');
$admin = new Administration();

/******** TEST ********/
if (isset($_POST['action']) && $_POST['action'] === 'validateImage') {
    echo "image";
//    print_r($admin->validateImage('image', 5000000));
    // Administration::saveImage("2194014", 'image', "../student_assets", 'psa');
}

/******** ADMINISTRATOR ********/
if (isset($_POST['action']) && $_POST['action'] === 'addAdministrator') {
    $admin->addAdministrator();
    $admin->enterLog("Add Administrator");
}
if (isset($_POST['action']) && $_POST['action'] === 'editAdministrator') {
    $admin->editAdministrator();
    $admin->enterLog("Edit Administrator");
}
if (isset($_POST['action']) && $_POST['action'] === 'deleteAdmin') {
    $admin->deleteAdmin();
    $admin->enterLog("Delete Administrator");
}

/******** SCHOOL YEAR ********/
if (isset($_POST['action']) && $_POST['action'] === 'initializeSY') {
    $admin->initializeSY(FALSE, TRUE);
    $admin->enterLog("Inialize School Year");
}
if (isset($_POST['action']) && $_POST['action'] === 'initAndSchedule') {
    $admin->initializeSY(TRUE, FALSE, 'schedule');
    $admin->enterLog("Inialize School Year and Schedule");
}
if (isset($_POST['action']) && $_POST['action'] === 'initAndSwitch') {
    $admin->initializeSY(TRUE, FALSE, 'view');
    $admin->enterLog("Inialize and Switch School Year");
}
if (isset($_POST['action']) && $_POST['action'] === 'editSY') {
    $admin->editSY();
    $admin->enterLog("Edit School Year");
}
if (isset($_POST['action']) && $_POST['action'] === 'editEnrollStatus') {
    $admin->editEnrollStatus();
    $admin->enterLog("Edit Enrollment Status");
}
if (isset($_POST['action']) && $_POST['action'] === 'editAcademicDays') {
    $admin->editAcademicDays();
    $admin->enterLog("Edit Academic Days");
}
if (isset($_GET['action']) && $_GET['action'] === 'switchSY') {
    $admin->switchSY(NULL, TRUE, 'view');
    $admin->enterLog("Switch School Year");
}

/******** ENROLLMENT ********/
if (isset($_POST['action']) && $_POST['action'] === 'enroll') {
    $admin->enroll();
    $admin->enterLog("Enroll Students");
}
if (isset($_POST['action']) && $_POST['action'] === 'validateEnrollment') {
    $admin->validateEnrollment();
    $admin->enterLog("Validate Enrollment");
}
if (isset($_POST['action']) && $_POST['action'] === 'deleteStudent') {
    $admin->deleteStudent();
    $admin->enterLog("Delete Student");
}
if (isset($_POST['action']) && $_POST['action'] === 'assessTransferee') {
    $admin->assessTransferee();
}

/******** USER ********/
if (isset($_POST['action']) && $_POST['action'] === 'deactivate') {
    $admin->toggleAccountStatus(FALSE);
    $admin->enterLog("Deactivate User");
}
if (isset($_POST['action']) && $_POST['action'] === 'activate') {
    $admin->toggleAccountStatus(TRUE);
    $admin->enterLog("Activate User");
}
if (isset($_POST['action']) && $_POST['action'] === 'reset') {
    $admin->resetMultiplePassword();
    $admin->enterLog("Reset User");
}
if (isset($_POST['action']) && $_POST['action'] === 'changePassword') {
    $admin->changePassword();
    $admin->enterLog("Change User Password");
}

/******** CURRICULUM ********/
if (isset($_POST['action']) && $_POST['action'] === 'addCurriculum') {
    $admin->addCurriculum();
    $admin->enterLog("Add Curriculum");
}

if (isset($_POST['action']) && $_POST['action'] === 'deleteCurriculum') {
    $admin->deleteCurriculum();
    $admin->enterLog("Delete Curriculum");
}

if (isset($_POST['action']) && $_POST['action'] === 'getCurriculumJSON') {
    $admin->listCurriculumJSON('curriculum');
}

if (isset($_POST['action']) && $_POST['action'] === 'updateCurriculum') {
    $admin->updateCurriculum();
    $admin->enterLog("Update Curriculum");
}

if (isset($_POST['action']) && $_POST['action'] === 'archiveCurriculum') {
    // $admin->moveCurriculum('archived_curriculum','curriculum','archived_program','program','archived_sharedsubject','sharedsubject');
    $admin->moveCurriculum("", "archived_");
    $admin->enterLog("Archive Curriculum");
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveCurriculum') {
    // $admin->moveCurriculum('curriculum','archived_curriculum','program','archived_program','sharedsubject','archived_sharedsubject');
    $admin->moveCurriculum("archived_", "");
    $admin->enterLog("Unarchive Curriculum");
}

if (isset($_POST['action']) && $_POST['action'] === 'getArchivedCurriculumJSON') {
    echo ("from getArchivedCurriculumJSON");
    $admin->listCurriculumJSON('archived_curriculum');
}

/******** PROGRAM ********/
if (isset($_POST['action']) && $_POST['action'] === 'addProgram') {
    $admin->addProgram();
    $admin->enterLog("Add Program");
}

if (isset($_POST['action']) && $_POST['action'] === 'deleteProgram') {
    $admin->deleteProgram();
    $admin->enterLog("Delete Program");
}

if (isset($_POST['action']) && $_POST['action'] === 'getProgramJSON') {
    $admin->listProgramsJSON('program');
}

if (isset($_POST['action']) && $_POST['action'] === 'updateProgram') {
    $admin->updateProgram();
    $admin->enterLog("Update Program");
}

if (isset($_POST['action']) && $_POST['action'] === 'archiveProgram') {
    // echo("from action.php: archiveProgram");
    // $admin->moveProgram('archived_program','program','archived_sharedsubject','sharedsubject');
    $admin->moveProgram('', 'archived_');
    $admin->enterLog("Archive Program");
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveProgram') {
    // $admin->moveProgram('program','archived_program','sharedsubject','archived_sharedsubject');
    $admin->moveProgram('archived_', '');
    $admin->enterLog("Unarchive Program");
}

if (isset($_POST['action']) && $_POST['action'] === 'getArchivedProgramJSON') {
    $admin->listProgramsJSON('archived_program');
}

/******** SUBJECT ********/
if (isset($_POST['action']) && $_POST['action'] === 'addSubject') {
    $admin->addSubject();
    $admin->enterLog("Add subject");
}

if (isset($_POST['action']) && $_POST['action'] === 'deleteSubject') {
    $admin->deleteSubject();
    $admin->enterLog("Delete subject");
}


if (isset($_POST['action']) && $_POST['action'] === 'getSubjectJSON') {
    $admin->listSubjectsJSON();
}

if (isset($_POST['action']) && $_POST['action'] === 'listFacultySubjects') {
    $admin->listFacultySubjects();
}

if (isset($_POST['action']) && $_POST['action'] === 'getAllSubjectJSON') {
    $admin->listAllSub('subject');
}

if (isset($_POST['action']) && $_POST['action'] === 'getArchiveSubjectJSON') {
    $admin->listAllSub('archived_subject');
}

if (isset($_POST['action']) && $_POST['action'] === 'editSubject') {
    $admin->updateSubject();
    $admin->enterLog("Edit subject");
}
if (isset($_POST['action']) && $_POST['action'] === 'saveSchedule') {
    $admin->saveSchedule();
}

if (isset($_POST['action']) && $_POST['action'] === 'archiveSubject') {
    echo("from action: archivesubject");
    $admin->moveSubject("", "archived_");
    $admin->listArchSubjectsJSON();
    $admin->enterLog("Archive subject");
    // $admin->moveSubject('archived_subject','subject','archived_sharedsubject','sharedsubject','archived_requisite','requisite');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveSubject') {
    $admin->moveSubject("archived_", "");
    $admin->listArchSubjectsJSON();
    $admin->enterLog("Unarchive subject");
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
    $admin->enterLog("Update Faculty Roles");
}
if (isset($_POST['action']) && $_POST['action'] === 'editDepartment') {
    $admin->updateFacultyDepartment();
    $admin->enterLog("Update Faculty Department");
}

if (isset($_POST['action']) && $_POST['action'] === 'editSubjectFaculty') {
    $admin->updateFacultySubjects($_POST['teacher_id']);
    $admin->enterLog("Edit Subject Faculty");
}

if (isset($_POST['action']) && $_POST['action'] === 'advisoryChange') {
    $admin->changeAdvisory();
    $admin->enterLog("Change Advisory");
}
if (isset($_POST['action']) && $_POST['action'] === 'changeEnrollPriv') {
    $admin->changeEnrollPriv();
    $admin->enterLog("Change Enrollment Privilege");
}

/******** SECTION ********/
if (isset($_POST['action']) && $_POST['action'] === 'getSectionJSON') {
    $admin->listSectionJSON();
}

if (isset($_POST['action']) && $_POST['action'] === 'addSection') {
    $admin->addSection();
    $admin->enterLog("Add Section");
}

if (isset($_POST['action']) && $_POST['action'] === 'editSection') {
    $admin->editSection();
    $admin->enterLog("Edit Section");

}
if (isset($_POST['action']) && $_POST['action'] === 'assignSubClasses' &&
    $_POST['target'] === 'SCFaculty'
) {
    $admin->assignSubClasses($_POST['teacher_id'], TRUE);
    return;
}

if (isset($_POST['action']) && $_POST['action'] === 'unassignSubClasses' &&
    $_POST['target'] === 'SCFaculty'
) {
    $admin->unassignSubClasses(TRUE);
    return;
}
if (isset($_POST['action']) && $_POST['action'] === 'assignSubClasses') {
    $admin->assignSubClasses($_POST['teacher_id']);
    $admin->enterLog("Assign Subject Class");
}
if (isset($_POST['action']) && $_POST['action'] === 'unassignSubClasses') {
    $admin->unassignSubClasses();
    $admin->enterLog("Unassign Subject Class");
}
if (isset($_POST['action']) && ($_POST['action'] === 'addStudentInSection' || $_POST['action'] === 'transferStudentInSection')) {
    $admin->addStudentInSection();
    $admin->enterLog("Add Student In Section");

}
if (isset($_POST['action']) &&  $_POST['action'] === 'editSubjectSection') {
    $admin->editSubjectSection();
    $admin->enterLog("Edit Subject Section");

}


/******** STUDENT ********/
if (isset($_POST['action']) && $_POST['action'] === 'transferStudent') {
    $admin->transferStudent();
    $admin->enterLog("Tranfer Student");
}

if (isset($_POST['action']) && $_POST['action'] === 'transferStudentFull') {
    $admin->transferStudentFull();
    $admin->enterLog("Tranfer Student To Full Section");
}
if (isset($_POST['action']) && $_POST['action'] === 'updateStudent') {
     $admin->editStudent();
     $admin->enterLog("Edit Student");
}
if (isset($_POST['action']) && $_POST['action'] === 'archiveStudent') {
    $admin->moveSubject("", "archived_");
    $admin->enterLog("Archive Student");
    // $admin->listArchStudentsJSON();
    // $admin->moveSubject('archived_subject','subject','archived_sharedsubject','sharedsubject','archived_requisite','requisite');
}

if (isset($_POST['action']) && $_POST['action'] === 'unarchiveStudent') {
    $admin->moveSubject("archived_", "");
    $admin->enterLog("Unarchive Student");
    // $admin->listArchStudentsJSON();
    // $admin->moveSubject('subject','archived_subject','sharedsubject','archived_sharedsubject', 'requisite','archived_requisite');
}

if (isset($_POST['action']) && $_POST['action'] === 'forgotPassword'){
    $admin->forgotPassword();
}
if (isset($_POST['action']) && $_POST['action'] === 'newPassword'){
    $admin->newPassword();
    $admin->enterLog("Change Password");
}
if (isset($_POST['action']) && $_POST['action'] === 'editSubjectGrade'){
    $admin->editSubjectGrade();
    // $admin->enterLog("Edit Subject Grade");
}



/******** SIGNATORY ********/
if (isset($_POST['action']) && $_POST['action'] === 'addSignatory'){
    $admin->addSignatory();
    $admin->enterLog("Add Signatory");
}

if (isset($_POST['action']) && $_POST['action'] === 'editSignatory'){
    $admin->editSignatory();
    $admin->enterLog("Edit Signatory");
}

if (isset($_POST['action']) && $_POST['action'] === 'deleteSignatory'){ 
    $admin->deleteSignatory();
    $admin->enterLog("Delete Signatory");
}

if (isset($_POST['action']) && $_POST['action'] === 'changeAttendance'){ 
    $admin->changeAttendance();
}


?>
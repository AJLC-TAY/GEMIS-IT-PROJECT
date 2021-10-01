<?php
require_once ("../class/Faculty.php");
$faculty = new FacultyModule();
/******** FACULTY ********/
if ((isset($_POST['profile']) && $_POST['profile'] == 'faculty')
    && $_POST['action'] === 'edit') {
    $faculty->processFaculty();
}
<?php
include('../class/Administration.php');
$admin = new Administration();

if (isset($_GET['code']) && $_GET['data'] == 'program') {
    $admin->getPrograms();
}

// if (isset($_GET['code']) && $_GET['data'] == 'subject') {
//     $admin->getSubjects();
// }
?>
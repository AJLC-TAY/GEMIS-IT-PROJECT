<?php
session_start();
if(!isset($_SESSION['id']) || $_SESSION['user_type'] != 'AD') {
    header("location: ../login.php");
} else {
    require_once('../class/config.php');
    $dbConfig = new dbConfig();
    $con = $dbConfig->connect();
    # update session enrollment, and quarter status
    $qry_sy = "SELECT sy_id, CONCAT(start_year,' - ', end_year) AS sy , current_quarter, current_semester, can_enroll FROM schoolyear;";
    $sy_res = mysqli_query($con, $qry_sy);

    if (mysqli_num_rows($sy_res) == 0) {
        $_SESSION['school_year'] = NULL;
        $_SESSION['sy_id'] = NULL;
        $_SESSION['enroll_status'] = NULL;
        $_SESSION['current_semester'] = NULL;
        $_SESSION['current_quarter'] = NULL;
    } else {
        $qry_sy = "SELECT sy_id, CONCAT(start_year,' - ', end_year) AS sy , current_quarter, current_semester, can_enroll FROM schoolyear WHERE status = '1';";
        $sy_res = mysqli_query($con, $qry_sy);
        if (mysqli_num_rows($sy_res) == 0) {
            $sy_res = mysqli_query($con, "SELECT sy_id, CONCAT(start_year,' - ', end_year) AS sy , current_quarter, current_semester, can_enroll FROM schoolyear ORDER BY sy_id DESC LIMIT 1;");
            $sy_row = mysqli_fetch_assoc($sy_res);
            $_SESSION['sy_id'] = $sy_row['sy_id'];
            $_SESSION['school_year'] = $sy_row['sy'];
            $_SESSION['current_quarter'] = 5;
        } else {
            $sy_row = mysqli_fetch_assoc($sy_res);
            $_SESSION['school_year'] = $sy_row['sy'];
            $_SESSION['sy_id'] = $sy_row['sy_id'];
            $_SESSION['enroll_status'] = $sy_row['can_enroll']; ;
            $_SESSION['current_semester'] = $sy_row['current_semester'];
            $_SESSION['current_quarter'] = $sy_row['current_quarter'];
        }
    }
}
?>
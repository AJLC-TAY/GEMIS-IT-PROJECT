<?php
session_start();
if(!isset($_SESSION['id']) || $_SESSION['user_type'] != 'ST') {
    header("location: ../login.php");
}else {
    require_once('../class/config.php');
    $dbConfig = new dbConfig();
    $con = $dbConfig->connect();
    # update session enrollment, and quarter status
    $qry_sy = "SELECT sy_id, CONCAT(start_year,' - ', end_year) AS sy , current_quarter, current_semester, can_enroll FROM schoolyear WHERE status = '1';";
    $sy_res = mysqli_query($con, $qry_sy);
    if (mysqli_num_rows($sy_res) != 0) {
        $sy_row = mysqli_fetch_assoc($sy_res);
        $_SESSION['school_year'] = $sy_row['sy'];
        $_SESSION['sy_id'] = $sy_row['sy_id'];
        $_SESSION['enroll_status'] = $sy_row['can_enroll']; ;
        $_SESSION['current_semester'] = $sy_row['current_semester'];
        $_SESSION['current_quarter'] = $sy_row['current_quarter'];
    }
    $query2 = "SELECT promote, prog_code, description, enrolled_in FROM enrollment e JOIN student USING (stud_id) JOIN program USING(prog_code) WHERE e.stud_id = {$_SESSION['id']} ORDER BY date_of_enroll DESC;"; //plus promotion
    $data = mysqli_fetch_row(mysqli_query($con, $query2));
    $_SESSION['promote'] = $data[0];
    $_SESSION['strand'] = $data[2];
    $_SESSION['grd_lvl'] = $data[3];
}
?>
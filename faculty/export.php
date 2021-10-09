<?php
require('../class/config.php'); 
require('../class/Dataclasses.php');
require('../class/Traits.php');


if ((isset($_POST['export']))) { 
    $connect = mysqli_connect("localhost","root","","gemis",3306);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="data.csv"');
    $output = fopen('php://output', 'w');
    $fields = array('LRN', 'NAME', 'FIRST GRADING', 'SECOND GRADING', 'FINAL GRADE'); 
    fputcsv($output, $fields);
    $query = mysqli_query($connect, "SELECT LRN, CONCAT(last_name, ', ', first_name, ' ', LEFT(middle_name, 1), '.', COALESCE(ext_name, '')) as stud_name, first_grading, second_grading, final_grade FROM student JOIN classgrade USING(stud_id) JOIN subjectclass USING(sub_class_code) JOIN sysub USING (sub_sy_id) JOIN subject USING (sub_code) WHERE teacher_id=26 AND sub_class_code = 9101 AND sy_id=9;"); 
    while($row = mysqli_fetch_assoc($query)){
        $data = array($row['LRN'], $row['stud_name'], $row['first_grading'], $row['second_grading'], $row['final_grade']); 
        fputcsv($output, $data);
    }
    fclose($output);
    }

    ?> 
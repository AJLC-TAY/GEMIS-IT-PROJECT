<?php
require_once('config.php');
require_once('Dataclasses.php');
require_once('Traits.php');

// sending email
use PHPMailer\PHPMailer\PHPMailer;

class FacultyModule extends Dbconfig
{
    const MAX_SECTION_COUNT = 50;
    protected $hostName;
    protected $userName;
    protected $password;
    protected $dbName;

    private $db = false;
    const ALLOWED_IMG_TYPES = array('jpg', 'png', 'jpeg');
    const SEMESTERS = [1, 2];
    const QUARTER = [1, 2, 3, 4];
    const GRADE_LEVEL = [11, 12];
    const SECTION_SIZE = 50;
    use QueryMethods, School, UserSharedMethods, FacultySharedMethods, Enrollment, Grade;

    public function __construct()
    {
        if (!$this->db) {
            $database = new dbConfig();
            $conn = $database->connect();
            if ($conn->connect_error) {
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            } else {
                $this->db = $conn;
                mysqli_set_charset($this->db, 'utf8');
            }
        }
    }



    public function listSubjectClass($is_JSON = false)
    {
        $students = [];
        $result = $this->query(
            "SELECT id_no, LRN, sex, CONCAT(last_name, ', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) AS name, first_grading, second_grading, final_grade FROM student
                                    JOIN enrollment USING (stud_id)
                                    JOIN classgrade USING (stud_id)
                                    WHERE sub_code='{$_GET['sub_code']}' ;"
        );
        while ($row = mysqli_fetch_assoc($result)) {
            $stud_id = $row['id_no'];
            $students[] = [
                'id'     =>  $stud_id,
                'lrn'    =>  $row['LRN'],
                'name'   =>  $row['name'],
                'sex'    =>  $row['sex'] == 'm' ? "Male" : "Female",
                'grd_1'  =>  $row['first_grading'],
                'grd_2'  =>  $row['second_grading'],
                'grd_f'  =>  $row['final_grade'],
                'action' =>  "<div class='d-flex justify-content-center'>"
                    . "<a href='grade.php?id=$stud_id' class='btn btn-sm btn-primary' target='_blank'>Grade</a>"
                    . "</div>"
            ];
        }

        if ($is_JSON) {
            echo json_encode($students);
            return;
        }
        return $students;
    }

    public function getClasses()
    {
        $advisory = $this->getAdvisoryClass($_SESSION['sy_id']);
        $subjects = $this->get_handled_sub_classes($_SESSION['id']);

        return ['advisory' => $advisory, 'sub_class' => $subjects];
    }



    public function importSubjectGradesToCSV()
    {


        // Allowed mime types
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

        // Validate whether selected file is a CSV file
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {

            // If the file is uploaded
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {

                // Open uploaded CSV file with read-only mode
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                // Skip the first line
                fgetcsv($csvFile);

                // Parse data from CSV file line by line
                while (($line = fgetcsv($csvFile)) !== FALSE) {
                    // Get row data
                    $LRN  = $line[0];
                    $stud_name = $line[1];
                    $first_grading  = $line[2];
                    $second_grading = $line[3];
                    $final_grading = $line[4];

                    // Check whether member already exists in the database with the same email
                    $prevQuery = "SELECT stud_id FROM student WHERE LRN = '" . $line[0] . "'";
                    $prevResult = $this->query($prevQuery);

                    if ($prevResult->num_rows > 0) {
                        // Update member data in the database
                        $this->query("UPDATE `classgrade` SET `first_grading` = '" . $first_grading . "', `second_grading` = '" . $second_grading . "', `final_grade` = '" . $final_grading . "' WHERE stud_id IN (SELECT stud_id FROM student WHERE LRN='" . $LRN . "';"); //madami siyang pinalitan na rowsss need din ata ng subclasscode
                    }
                }

                // Close opened CSV file
                fclose($csvFile);

                $qstring = '?status=succ';
            } else {
                $qstring = '?status=err';
            }
        } else {
            $qstring = '?status=invalid_file';
        }
    }



    function getSchoolYearInfo($sy_id)
    {
        //implement session for sy_id then remove param
        $row_temp = $this->query("SELECT current_quarter AS qtr, current_semester AS sem, can_enroll FROM schoolyear WHERE sy_id='$sy_id';");
        $sy = mysqli_fetch_row($row_temp);

        return [
            'grading' => $sy[0],
            'sem'     => $sy[1],
            'enroll'  => $sy[2]
        ];
    }

    public function listStudentsForPromotion()
    {
        session_start();
        $section_code = $_GET['section'];

        $students =[];
        $ave = $_SESSION['current_semester']=='1'?'first_gen_ave':'second_gen_ave';
        $result = $this->query("SELECT stud_id, $ave, CONCAT(last_name, ', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) AS name FROM student JOIN enrollment USING (stud_id) JOIN gradereport USING(stud_id) WHERE section_code=$section_code AND $ave > 74 AND promote = 0 AND stud_id NOT IN (SELECT stud_id FROM classgrade WHERE final_grade < 74)");
    //    echo("SELECT stud_id, $ave, CONCAT(last_name, ', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) AS name FROM student JOIN enrollment USING (stud_id) JOIN gradereport USING(stud_id) WHERE section_code=$section_code AND $ave > 74 AND promote = 0 AND stud_id NOT IN (SELECT stud_id FROM classgrade WHERE final_grade < 74)");
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = [
                'stud_id' => $row['stud_id'],
                'name' => "{$row['name']} <input type ='hidden' id='{$row['stud_id']}' value='enable'>",
                'gen_ave' => $row[$ave],
                'action' => "<div class='d-flex justify-content-center'>
                <button data-id='{$row['stud_id']}' data-type='undo' class='btn btn-sm btn-primary action' title='Undo' style='display: none;'><i class='bi bi-arrow-return-left'></i></button>
                <button data-id='{$row['stud_id']}' data-type='remove' class='btn btn-sm btn-danger action' title='Remove student'><i class='bi bi-trash'></i></button>
            </div>"
            ];
        }
        echo json_encode($students);
    }

    public function listValuesReport($stud_id)
    {

        $marka = ['AO', 'SO', 'RO', 'NO'];
        $qtr = $_SESSION['current_quarter']; //session

        $values = [];
        $values_desc = [];
        $marking = [];
        $sy_id = $_SESSION['sy_id'];
        $result = $this->query("SELECT value_name, bhvr_statement FROM `values`"); // query for behavior_stament tapos ung value name  //note: need nung ticks kasi baka iba mainterpret ng sql na values, hindi jay table


        while ($val = mysqli_fetch_assoc($result)) {


            for ($x = 1; $x <= $qtr; $x++) {
                $markings = $this->query("SELECT value_name, status, value_id, report_id, bhvr_statement, marking FROM `observedvalues` JOIN `values` USING (value_id) WHERE stud_id = $stud_id AND quarter = $x");
                while ($marks = mysqli_fetch_assoc($markings)) {
                    // echo("entered");
                    if ($marks['bhvr_statement'] == $val['bhvr_statement'] and $marks['value_name'] == $val['value_name']) {
                        if ($x < $qtr || $marks['status'] == 1) {
                            $list = $marks['marking'];
                        } else {
                            $list = "<select class='markings' name='markings' class='select2 px-0 form-select form-select-sm' required>";
                            foreach ($marka as $markas) {
                                if ($markas == $marks['marking']) {
                                    $list .= "<option value='{$stud_id}/{$marks['value_id']}/{$x}/{$markas}/{$marks['report_id']}' selected>$markas</option>";
                                } else {
                                    $list .= "<option value='{$stud_id}/{$marks['value_id']}/{$x}/{$markas}/{$marks['report_id']}'>$markas</option>";
                                }
                            }
                        }
                        $marking[$val['bhvr_statement']][] =  $list;
                        $list = '';
                    }
                }
                
                // foreach ($marka as $markas) {
                //     if ($markas == 'SO') {
                //         $list .= "<option value='{$stud_id}//{$x}/{$markas}/' selected>$markas</option>";
                //     } else {
                //         $list .= "<option value='{$stud_id}//{$x}/{$markas}/'>$markas</option>";
                //     }
                // }
            }

            if (sizeof($marking) != 4) {
                for ($x = 1; $x <= 3; $x++) {
                    $marking[$val['bhvr_statement']][] =  '';
                }
            }

            $values[$val['value_name']][] = $marking;
            $marking = [];
        }
        return $values;
    }

    public function updateValueGrades()
    {
        $mark = $_POST['mark'];
        $status = (int) $_POST['stat'];
        $stud_id = $_POST['id'];
        $value_id = $_POST['val_id'];
        $report_id = $_POST['rep_id'];
        $qtr = $_POST['qtr'];
        echo ("mark: $mark stat: $status stud id: $stud_id value_id: $value_id report_id: $report_id qtr: $qtr");
        $this->prepared_query(
            "UPDATE `observedvalues` SET `marking`=?, `status`=? WHERE `value_id`=? AND `stud_id`=? AND `report_id`=? AND `quarter`=? ;",
            [$mark, $status, $value_id, $stud_id, $report_id, $qtr],
            "sisiii"
        );
    }

    public function gradeClass()
    {
        $stud_id = $_POST['id'];
        $grading = $_POST['grading'];
        $grade = $_POST['grade'];
        $code = $_POST['code'];
        $stat = (int) $_POST['stat'];

        $grade = $grade != "" ? $grade : NULL;
        echo ("stud id: $stud_id, grading: $grading, grade: $grade, code: $code, stat: $stat ");

        $this->prepared_query(
            "UPDATE `classgrade` SET `$grading` =? , status = ? WHERE`classgrade`.`stud_id` = ?  AND `classgrade`.`sub_code` = ?;",
            [$grade, $stat, $stud_id, $code],
            "diis"
        );
    }

    public function gradeAdvisory()
    {
        session_start();
        $stud_id = $_POST['id'];
        $report_id = $_POST['rep_id'];
        $gen_ave = $_POST['gen_ave'];
        $sem = $_POST['sem'];
        $stat = (int) $_POST['stat'];

        $gen_ave = $gen_ave != "" ? $gen_ave : NULL;

        echo ("stud id:$stud_id rep_id: $report_id gen_ave: $gen_ave stat: $stat");
        
        $this->prepared_query(
            "UPDATE `gradereport` SET `$sem` =?, status = ? WHERE`gradereport`.`stud_id` = ?  AND `gradereport`.`report_id` = ?;",
            [$gen_ave, $stat, $stud_id, $report_id],
            "iiii"
        );
    }


    function exportSubjectGradesToCSV()
    {
        $teacher_id = 26; //SESSION
        $sy_id = 9; //SESSION
        $sub_class_code = $_POST['code'];

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename= "' . $sub_class_code . ' - grades.csv"'); // classcode sana + grade
        $output = fopen('php://output', 'w');
        $fields = array('LRN', 'NAME', 'FIRST GRADING', 'SECOND GRADING', 'FINAL GRADE');
        fputcsv($output, $fields);
        $query = $this->query("SELECT DISTINCT LRN, CONCAT(last_name, ', ', first_name, ' ', LEFT(middle_name, 1), '.', COALESCE(ext_name, '')) as stud_name, first_grading, second_grading, final_grade 
        FROM student 
        JOIN classgrade USING(stud_id) 
        JOIN subject USING(sub_code) 
        JOIN sysub USING(sub_code) 
        JOIN subjectclass USING(sub_sy_id)
        WHERE classgrade.sub_code = 'OCC1' 
        AND sy_id=$sy_id;");
        while ($row = mysqli_fetch_assoc($query)) {
            $data = array($row['LRN'], $row['stud_name'], $row['first_grading'], $row['second_grading'], $row['final_grade']);
            fputcsv($output, $data);
        }
        fclose($output);
    }

    public function calculateGenAveBySection()
    {
        session_start();
        $sy_id = $_SESSION['sy_id'];
        $semester = in_array($_SESSION['current_quarter'], [1, 2]) ? "1" : "2";
        $section_code = $_POST['section_code'];
        $result = $this->query("SELECT prog_code, enrolled_in, report_id FROM enrollment e JOIN gradereport g on e.stud_id = g.stud_id WHERE section_code = '$section_code';");
        $general_average_data = [];
        # section code
        if (mysqli_num_rows($result)) {
            while ($row = mysqli_fetch_assoc($result)) {
                # calculate
                $result_ga = $this->query("SELECT cg.report_id, ROUND(AVG(final_grade)) AS general_average FROM classgrade cg
                                             JOIN gradereport g on cg.report_id = g.report_id
                                             JOIN enrollment e on cg.stud_id = e.stud_id
                                             WHERE e.section_code = '$section_code' AND g.report_id = '{$row['report_id']}'
                                             AND sub_code IN (SELECT DISTINCT sub_code FROM sharedsubject
                                                              WHERE for_grd_level = '{$row['enrolled_in']}' AND prog_code = '{$row['prog_code']}'
                                             AND sub_semester = '$semester' AND sy_id = '$sy_id') GROUP BY cg.report_id;");
                $result_ga_row = mysqli_fetch_row($result_ga);
                $general_average_data[] = [
                    'report_id' => $result_ga_row[0],
                    'general_ave' => $result_ga_row[1],
                    'semester'  => in_array($_SESSION['current_quarter'], [1,2]) ? "first" : "second"
                ];
            }
        }
        echo json_encode($general_average_data);
    }
}

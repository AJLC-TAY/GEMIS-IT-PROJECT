<?php
require('config.php');
require('Dataclasses.php');
require('Traits.php');

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

    public function getClasses()
    {
        $advisory = $this->getAdvisoryClass($_SESSION['sy_id']);
        $subjects = $this->getHandled_sub_classes($_SESSION['id']);

        return ['advisory' => $advisory, 'sub_class' => $subjects];
    }

    public function listAdvisoryStudents($is_JSON = false)
    {
        session_start();
        $students = [];
        $section_code = $_GET['section'];
        $result = $this->query("SELECT stud_id, LRN, sex, CONCAT(last_name, ', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) AS name FROM student 
                                JOIN enrollment USING (stud_id) WHERE section_code='$section_code'");
        while ($row = mysqli_fetch_assoc($result)) {
            $stud_id = $row['stud_id'];
            # get report id
            $row_temp = $this->query("SELECT report_id, general_average FROM gradereport WHERE stud_id='$stud_id' AND sy_id='{$_SESSION['sy_id']}';");

            $temp = mysqli_fetch_row($row_temp);
            if ($temp != NULL) {
                $report_id = $temp[0];
                $gen_ave = $temp[1];
            }

            $students[] = [
                'id'     =>  $stud_id,
                'lrn'    =>  $row['LRN'],
                'name'   =>  $row['name'],
                'grd_f'  =>  "<input name='{$stud_id}/{$report_id}/general_average' class='form-control form-control-sm text-center mb-0 number gen-ave' value='{$gen_ave}'>",
                'sex'    =>  $row['sex'] == 'm' ? "Male" : "Female",
                'action' =>  "<div class='d-flex justify-content-center'>"
                    . "<button class='btn btn-sm btn-secondary me-1'>View</button>"
                    . "<button data-report-id='$report_id' data-stud-id='$stud_id' class='btn btn-sm btn-secondary me-1 export-grade'>Export Grades</button>"
                    . "<a href='grade.php?id=$report_id' role='button' target='_blank' class='btn btn-sm btn-primary'>View Grades</a>"
                    . "<a href='advisory.php?values_grade=$report_id&id=$stud_id' role='button' target='_blank' class='btn btn-sm btn-primary'>Grade Values</a>"
                    . "</div>",
            ];
        }

        if ($is_JSON) {
            echo json_encode($students);
            return;
        }
        return $students;
    }

    public function listSubjectClass($is_JSON = false)
    {
        $students = [];
        $result = $this->query(
            "SELECT id_no, LRN, sex, CONCAT(last_name, ', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) AS name, first_grading, second_grading, final_grade FROM student
                                    JOIN enrollment USING (stud_id)
                                    JOIN classgrade USING (stud_id)
                                    WHERE sub_code='{$_GET['sub_code']}';"
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

    public function getClass()
    {
        if (isset($_GET['section'])) {
            $this->listAdvisoryStudents(true);
        }
        if (isset($_GET['sub_class_code'])) {
            $this->listSubjectClass(true);
        }
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
    public function listValuesReport()
    {

        $marka = ['AO', 'SO', 'RO', 'NO'];
        $qtr = '4'; //session
        $values = [];
        $values_desc = [];
        $marking = [];
        $stud_id = 110001; //$_GET['id'];
        $sy_id = 4;
        $result = $this->query("SELECT value_name, bhvr_statement FROM `values`"); // query for behavior_stament tapos ung value name  //note: need nung ticks kasi baka iba mainterpret ng sql na values, hindi jay table


        while ($val = mysqli_fetch_assoc($result)) {


            for ($x = 1; $x <= $qtr; $x++) {
                $markings = $this->query("SELECT value_name, status, value_id, report_id, bhvr_statement, marking FROM `observedvalues` JOIN `values` USING (value_id) WHERE stud_id = $stud_id AND quarter = $x");
                while ($marks = mysqli_fetch_assoc($markings)) {
                    if ($marks['bhvr_statement'] == $val['bhvr_statement'] and $marks['value_name'] == $val['value_name']) {
                        if ($x < $qtr || $marks['status'] == 1){
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
        echo("mark: $mark stat: $status stud id: $stud_id value_id: $value_id report_id: $report_id qtr: $qtr");
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
        $stud_id = $_POST['id'];
        $report_id = $_POST['rep_id'];
        $gen_ave = $_POST['gen_ave'];
        $stat = (int) $_POST['stat'];

        $gen_ave = $gen_ave != "" ? $gen_ave : NULL;

        $this->prepared_query(
            "UPDATE `gradereport` SET `general_average` =?, status = ? WHERE`gradereport`.`stud_id` = ?  AND `gradereport`.`report_id` = ?;",
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
        $query = $this->query("SELECT LRN, CONCAT(last_name, ', ', first_name, ' ', LEFT(middle_name, 1), '.', COALESCE(ext_name, '')) as stud_name, first_grading, second_grading, final_grade FROM student JOIN classgrade USING(stud_id) JOIN subjectclass USING(sub_class_code) JOIN sysub USING (sub_sy_id) JOIN subject USING (sub_code) WHERE teacher_id=26 AND sub_class_code = '$sub_class_code' AND sy_id=9;");
        while ($row = mysqli_fetch_assoc($query)) {
            $data = array($row['LRN'], $row['stud_name'], $row['first_grading'], $row['second_grading'], $row['final_grade']);
            fputcsv($output, $data);
        }
        fclose($output);
    }
}

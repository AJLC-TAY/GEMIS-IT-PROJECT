<?php
require_once('config.php');
require_once('Dataclasses.php');
require_once('Traits.php');

// sending email
use PHPMailer\PHPMailer\PHPMailer;

class Administration extends Dbconfig
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
    const MONTHS = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July ', 'August', 'September', 'October', 'November', 'December'
    ];
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

    /*** Administrator Methods */
    /**
     * Creates an administrator user.
     */
    public function addAdministrator()
    {
        $user_id = $this->createUser("AD");
        $this->prepared_query(
            "INSERT INTO administrator (last_name, first_name, middle_name, ext_name, cp_no, sex, age, email, admin_user_no) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);",
            [
                $_POST['lastname'],
                $_POST['firstname'],
                $_POST['middlename'],
                $_POST['extensionname'],
                $_POST['cpnumber'],
                $_POST['sex'],
                $_POST['age'],
                $_POST['email'],
                $user_id
            ],
            "ssssssisi"
        );
        $id = mysqli_insert_id($this->db);
        header("Location: admin.php?id=$id");
    }

    public function listGradeStud()
    {
        $id = $_SESSION['id'];
        $sy = $_SESSION['sy_id'];
        $curr_sem = $_SESSION['current_semester'];
        $grado = [];
        $result = $this->query(
            "SELECT s.sub_name, first_grading, second_grading, final_grade, s.sub_type
                FROM classgrade c JOIN gradereport gr USING (report_id)
               JOIN subject s USING(sub_code) 
                JOIN schoolyear USING (sy_id)
                WHERE gr.stud_id = $id AND sy_id = $sy;"
        );
        # old query
        //        "SELECT sub_name, first_grading, second_grading, final_grade, sub_type
        //            FROM schoolyear JOIN sysub USING (sy_id) JOIN subject USING(sub_code)
        //            JOIN subjectclass USING (sub_sy_id) JOIN classgrade USING(sub_class_code)
        //            WHERE stud_id = $id AND sy_id = $sy AND current_semester = $curr_sem AND status = 1;"

        while ($grd =  mysqli_fetch_assoc($result)) {
            $grado[] = [
                'sub_name'  => $grd['sub_name'],
                'grade_1'   => $grd['first_grading'],
                'grade_2'   => $grd['second_grading'],
                'grade_f'   => $grd['final_grade']
            ];
        }

        return $grado;
    }


    public function editAdministrator()
    {
        session_start();
        $id = $_SESSION['id'];
        $this->prepared_query(
            "UPDATE administrator SET last_name=?, first_name=?, middle_name=?, ext_name=?, age=?, cp_no=?, sex=?, email=? "
                . "WHERE admin_id=?;",
            [
                $_POST['lastname'],
                $_POST['firstname'],
                $_POST['middlename'],
                $_POST['extensionname'],
                $_POST['age'],
                $_POST['cpnumber'],
                $_POST['sex'],
                $_POST['email'],
                $id
            ],
            "ssssisssi"
        );

        header("Location: admin.php?id=$id");
    }
    public function getAdministrator($id = NULL)
    {
        $id = $id ?? $_SESSION['id'];
        $result = $this->query(
            "SELECT admin_id, admin_user_no, "
                . "last_name, first_name, middle_name, ext_name, "
                . "CASE WHEN sex = 'm' THEN 'Male' ELSE 'Female' END AS sex, "
                . "age, cp_no, email, admin_user_no "
                . "FROM administrator WHERE admin_id='$id';"
        );
        $row = mysqli_fetch_assoc($result);
        return new Administrator(
            // $row['admin_id'],
            $row['admin_user_no'],
            $row['last_name'],
            $row['first_name'],
            $row['middle_name'],
            $row['ext_name'],
            $row['age'],
            $row['sex'],
            $row['cp_no'],
            $row['email'],
            $row['admin_user_no']
        );
    }

    public function listAdministrators()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $result = $this->query("SELECT admin_id, admin_user_no, last_name, first_name, middle_name, ext_name, "
            . "CASE WHEN sex = 'm' THEN 'Male' ELSE 'Female' END AS sex, age, cp_no, email FROM administrator WHERE admin_id!='{$_SESSION['id']}';");
        $administrators = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $administrators[] = new Administrator(
                // $row['admin_id'],
                $row['admin_user_no'],
                $row['last_name'],
                $row['first_name'],
                $row['middle_name'],
                $row['ext_name'],
                $row['age'],
                $row['sex'],
                $row['cp_no'],
                $row['email']
            );
        }
        return $administrators;
    }

    public function listAdministratorsJSON()
    {
        echo json_encode($this->listAdministrators());
    }
    /*** School Year Methods */
    public function getSystemLogs()
    {
        session_start();
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        //      $query = "SELECT * FROM historylogs WHERE sy_id = {$_SESSION['sy_id']} ";
        $query = "SELECT * FROM historylogs ";


        $search_query = "";
        if (strlen(trim($_GET['search'])) > 0) {
            $text = $_GET['search'];
            $search_query .= " WHERE (log_Id LIKE \"%$text%\"";
            $search_query .= " OR id_no LIKE \"%$text%\"";
            $search_query .= " OR user_type LIKE \"%$text%\"";
            $search_query .= " OR action LIKE \"%$text%\"";
            $search_query .= " OR datetime LIKE \"%$text%\")";
        }

        $query .= $search_query;
        if (isset($_GET['sort'])) {
            $query .= " ORDER BY {$_GET['sort']} {$_GET['order']} ";
        } else {
            $query .= " ORDER BY datetime DESC";
        }
        $result = $this->query($query);
        $num_rows_not_filtered = $result->num_rows;

        $query .= " LIMIT $limit";
        $query .= " OFFSET $offset";
        $result = $this->query($query);
        $records = array();

        while ($row = mysqli_fetch_assoc($result)) { // MYSQLI_ASSOC allows to retrieve the data through the column name
            if ($row['user_type'] == 'ST') {
                $tbl = 'student';
                $hdr = 'id_no';
            } elseif ($row['user_type'] == 'AD') {
                $tbl = 'administrator';
                $hdr = 'admin_user_no';
            } else {
                $tbl = 'faculty';
                $hdr = 'teacher_user_no';
            }

            // echo ("SELECT CONCAT(last_name,', ',first_name,' ',COALESCE(middle_name,''),' ', COALESCE(ext_name, '')) as name FROM `$tbl` WHERE $hdr = {$row['id_no']};");
            $name = mysqli_fetch_row($this->query("SELECT CONCAT(last_name,', ',first_name,' ',COALESCE(middle_name,''),' ', COALESCE(ext_name, '')) as name FROM `$tbl` WHERE $hdr = {$row['id_no']};"))[0];
            // echo $name;

            $records[] = [
                'log_id' => $row['log_id'],
                'id_no' => $row['id_no'],
                'name' => $name,
                'user_type' => $row['user_type'],
                'action' => $row['action'],
                'datetime' => $row['datetime']
            ];
        }
        $output = new stdClass();
        $output->total = $num_rows_not_filtered;
        $output->totalNotFiltered = $num_rows_not_filtered;
        $output->rows = $records;
        echo json_encode($output);
    }
    public function getInitSYData()
    {
        # curriculum
        $result = $this->query("SELECT c.curr_code, c.curr_name, prog_code, description FROM curriculum as c JOIN program USING (curr_code);");
        $track_program = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $track_code = $row['curr_code'];
            if (!in_array($track_code, array_keys($track_program))) {
                $track_program[$track_code] = [
                    "track_name" => $row['curr_name'],
                    "programs" => []
                ];
            }
            $track_program[$track_code]['programs'][$row['prog_code']] = $row['description'];
        }

        # subject
        $subject_result = $this->query("SELECT sub_code, sub_name, sub_type FROM subject;");
        $subjects = [];
        while ($sub_row = mysqli_fetch_assoc($subject_result)) {
            $sub_type = $sub_row['sub_type'];
            if (!in_array($sub_type, array_keys($subjects))) {
                $subjects[$sub_type] = [$sub_row['sub_code'] => []];
            }
            $subjects[$sub_type][$sub_row['sub_code']] = $sub_row['sub_name'];
        }
        return ["track_program" => $track_program, "subjects" => $subjects];
    }

    /**
     * Initializes school year.
     * 1.   Create school year record.
     * 2.   Initialize curriculum.
     * 3.   Initialize academic monthly days.
     * 4.   Create directory for image file system.
     */
    public function initializeSY($switch = FALSE, $is_JSON = FALSE, $destination = NULL)
    {
        session_start();
        $start_yr = $_POST['start-year'];
        $end_yr = $_POST['end-year'];
        $enrollment = 0;
        $current_quarter = 1;
        $current_semester = 1;

        # Step 1
        $query = "INSERT INTO schoolyear (start_year, end_year, current_quarter, current_semester, can_enroll) "
            . "VALUES (?, ?, ?, ?, ?);";
        $this->prepared_query($query, [$start_yr, $end_yr, $current_quarter, $current_semester, $enrollment], "iiiii");

        $sy_id = mysqli_insert_id($this->db);

        // echo "Added school year. ID: ". $sy_id ."<br>";
        # Step 2
        $tracks = $_POST['track']['name'];
        foreach ($tracks as $track) {
            // insert record to sycurriculum table
            $this->prepared_query("INSERT INTO sycurriculum (sy_id, curr_code) VALUES (?, ?);", [$sy_id, $track], 'is');
            // echo "Added school year - curriculum: ". $sy_id ." - ". $track ."<br>";

            // store syc_id
            $school_year_curr_id = mysqli_insert_id($this->db);

            // insert strands offered in the sycurrstrand 
            $programs = array_keys($_POST['track'][$track]);
            foreach ($programs as $prog) {
                $this->prepared_query("INSERT INTO sycurrstrand (syc_id, prog_code) VALUES (?, ?);", [$school_year_curr_id, $prog], 'is');
            }
            // echo "----------"."<br>";
        }

        # Step 3
        $start_month = $_POST['start-month'];
        $end_month = $_POST['end-month'];
        $default_days = $_POST['default-days'];
        $months_records = [];
        foreach (Administration::MONTHS as $ind => $month) {
            if ($ind >= $start_month) {
                $months_records[] = $month;
            }
        }

        foreach (Administration::MONTHS as $ind => $month) {
            if ($ind <= $end_month) {
                $months_records[] = $month;
            }
        }

        foreach ($months_records as $mr) {
            $this->query("INSERT academicdays (month, no_of_days, sy_id) VALUES ('$mr', '$default_days', '$sy_id');");
        }

        # Step 4
        $dir_path = "../uploads/student/$sy_id";
        $cred_dir_path = "../uploads/credential/$sy_id";
        if (!file_exists($dir_path)) {
            mkdir($dir_path);
        }
        if (!file_exists($cred_dir_path)) {
            mkdir($cred_dir_path);
        }

        if ($switch) {
            $this->switchSY($sy_id, FALSE, $destination);
            return;
        }

        // echo "School year successfully initialized.";
        if ($is_JSON) {
            echo json_encode("schoolYear.php?id=$sy_id");
            exit;
        }
        return $sy_id;
    }

    public function endSchoolYear()
    {
        session_start();
        $this->query("UPDATE schoolyear SET status='0', can_enroll='0' WHERE sy_id = '{$_SESSION['sy_id']}';");
        header("Location: index.php");
    }
    public function updateSYStatus($sy_id = NULL)
    {
        if (empty($_SESSION)) {
            session_start();
        }
        $sy_id = $_GET['id'] ?? $sy_id;
        $current_sy = $_SESSION['sy_id'] ?? NULL;
        if (!is_null($current_sy)) {
            $this->query("UPDATE `schoolyear` SET `status` = '0', can_enroll='0' WHERE `schoolyear`.`sy_id` = '$current_sy';");
        }
        $this->query("UPDATE `schoolyear` SET `status` = '1' WHERE `schoolyear`.`sy_id` = '$sy_id';");
        $qry_sy = "SELECT sy_id, CONCAT(start_year,' - ', end_year) AS sy , current_quarter, can_enroll FROM schoolyear WHERE status = '1';";
        $sy_res = $this->query($qry_sy);
        $sy_row = mysqli_fetch_assoc($sy_res);
        $_SESSION['school_year'] = $sy_row['sy'];
        $_SESSION['sy_id'] = $sy_row['sy_id'];
        $_SESSION['enroll_status'] = $sy_row['can_enroll'];
        $_SESSION['current_quarter'] = $sy_row['can_enroll'];
        return $sy_id;
    }

    public function switchSY($sy_id = NULL, $redirect = FALSE, $destination = NULL)
    {
        $sy_id = $this->updateSYStatus($sy_id);
        if ($redirect) {
            header("Location: schoolYear.php?id=$sy_id");
        } else {
            switch ($destination) {
                case "schedule":
                    echo json_encode("subject.php?page=schedule");
                    break;
                case "view":
                    echo json_encode("schoolYear.php?id=$sy_id");
                    break;
            }
        }
    }

    public function checkIfSYUnique()
    {
        $type = $_GET['type'];
        $value = $_POST[$type . "-year"];
        $result = $this->query("SELECT distinct 1 " . $type . "_year FROM schoolyear WHERE " . $type . "_year = '$value';");
        if (mysqli_num_rows($result) > 0) {
            echo "false";
        } else {
            echo "true";
        }
    }

    public function listGrade()
    {

        $grades = [];
        if ($_SESSION['user_type'] == 'ST') {
            $stud_id = $_SESSION['id'];
        } else {
            $stud_id = 110001;
        }
        $grado = [];
        $result = $this->query("SELECT current_semester FROM schoolyear WHERE sy_id = {$_SESSION['sy_id']}");
        $sy_id = $_SESSION['sy_id'];
        $subject_type = $this->query("SELECT DISTINCT sub_type FROM classgrade JOIN subjectclass USING(sub_code) JOIN subject WHERE stud_id = $stud_id"); //insert ung query nung pagretrieve ng subtypes nung subjects na meron si stud  // subtypes lang ba etey?
        while ($sem = mysqli_fetch_assoc($result)) {
            while ($sub_type = mysqli_fetch_assoc($subject_type)) { // e.g. $row = sem 
                for ($x = 1; $x <= $sem['current_semester']; $x++) {

                    $stud_grade = $this->query("SELECT cg.status, sub.sub_name, cg.first_grading, cg.second_grading, cg.final_grade, sub.sub_type
                    FROM schoolyear as sy
                    JOIN classgrade as cg
                    JOIN subject as sub USING(sub_code) WHERE cg.stud_id = $stud_id AND sy.sy_id = $sy_id AND sy.current_semester = $x;");
                    // foreach($sub_type as $type){
                    while ($grd = mysqli_fetch_assoc($stud_grade)) {
                        // echo json_encode($sub_type['sub_type']);
                        // echo ("-------------");

                        if ($sub_type['sub_type'] == $grd['sub_type']) {
                            // if ($_SESSION['user_type'] == 'ST') {
                            if ($grd['status'] == 1) {
                                $grado[] = [
                                    'sub_name'  => $grd['sub_name'],
                                    'grade_1'   => $grd['first_grading'],
                                    'grade_2'   => $grd['second_grading'],
                                    'grade_f'   => $grd['final_grade']
                                ];
                            } else {
                                $grado[] = [
                                    'sub_name'  => $grd['sub_name'],
                                    'grade_1'   => '',
                                    'grade_2'   => '',
                                    'grade_f'   => ''
                                ];
                            }
                            // } else {
                            //     $grado[] = [
                            //         'sub_name'  => $grd['sub_name'],
                            //         'grade_1'   => $grd['first_grading'],
                            //         'grade_2'   => $grd['second_grading'],
                            //         'grade_f'   => $grd['final_grade']
                            //     ];
                            // }
                        }
                    }



                    // }
                    $grades[$x][$sub_type['sub_type']] = $grado;
                    $grado = [];
                }

                //ung kukunin lang is ung sub_name and sub_type ni stud
                if (sizeof($grades) != 2) {
                    $stud_grd = $this->query("SELECT DISTINCT classgrade.status, sub_name, first_grading, 
                second_grading, final_grade, sub_type FROM schoolyear 
                    JOIN sysub USING (sy_id) JOIN subject USING(sub_code) 
                    JOIN subjectclass USING (sub_sy_id) 
                    JOIN classgrade 
                    WHERE stud_id = $stud_id AND sy_id = $sy_id AND current_semester = 1 AND classgrade.status = 1;"); //sub_name | first_grading | second_grading | final_grading | sub_type

                    // foreach($sub_type as $type){
                    while ($grds = mysqli_fetch_assoc($stud_grd)) {

                        $grades['2'][$grds['sub_type']][] = [
                            'sub_name'  => $grds['sub_name'],
                            'grade_1'   => '',
                            'grade_2'   => '',
                            'grade_f'   => ''
                        ];
                    }
                }
            }
        }
        // // add for empty data kunmabaga kapag first quarter lang meron padin ung 2nd, 3rd, 4th quarter sa array pero no values
        return ($grades);
    }


    public function listValuesReport()
    {
        $values = [];
        $values_desc = [];
        $marking = [];
        $stud_id = 110003;
        $sy_id = $_SESSION['sy_id'];
        $result = $this->query("SELECT value_name, bhvr_statement FROM `values`"); // query for behavior_stament tapos ung value name  //note: need nung ticks kasi baka iba mainterpret ng sql na values, hindi jay table


        while ($val = mysqli_fetch_assoc($result)) {


            $qtr = $this->query("SELECT current_quarter FROM schoolyear WHERE sy_id = $sy_id");
            while ($qtrs = mysqli_fetch_assoc($qtr)) {
                for ($x = 1; $x <= $qtrs['current_quarter']; $x++) {
                    $markings = $this->query("SELECT value_name, bhvr_statement, marking FROM `observedvalues` JOIN `values` USING (value_id) WHERE stud_id = $stud_id AND quarter = $x AND status = 1");
                    while ($marks = mysqli_fetch_assoc($markings)) {
                        if ($marks['bhvr_statement'] == $val['bhvr_statement'] and $marks['value_name'] == $val['value_name']) {
                            $marking[$val['bhvr_statement']][] =  $marks['marking'];
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
        }
        return $values;
    }

    public function listAttendanceReport()
    {
        $attendance = [];
        return $attendance; // "jan" [=> category => days
        //           => category2 => grade] 
        //feb [=> no_of_absent => 18
    }

    //RETRIEVAL FOR ADVISER AND STUDENT --->pacheck haha 
    public function listStudentGrades()
    {
        $stud_id = $_POST['stud_id'];
        $sy_id = $_POST['sy_id'];
        $this->query("SELECT sub_code, sub_name, first_grading, second_grading, final_grade 
            FROM classgrade 
            JOIN subjectclass USING (sub_class_code) 
            JOIN subject USING (sub_code) 
            WHERE report_id IN (SELECT report_id FROM gradereport WHERE stud_id=$stud_id) 
            AND sy_id=$sy_id;");
    }

    public function editGrades()
    {
        $stud_id = $_POST['stud_id'];
        $first_grading = $_POST['first_grading '];
        $second_grading = $_POST['second_grading'];
        $final_grade = $_POST['final_grade'];
        $grade_id = $_POST['grade_id'];
        $report_id = $_POST['report_id'];
        $sub_class_code = $_POST['sub_class_code'];
        //$grade=;

        //1 => first_grading
        //2 => seon
        //first grading //ganito muna di ko sure coniditional kung first,second,final ineedit ng user       
        // $this->prepared_query("UPDATE `classgrade` SET `$grade` =? WHERE `classgrade`.`grade_id` =? AND `classgrade`.`stud_id` = ? AND `classgrade`.`report_id` = ? AND `classgrade`.`sub_class_code` = ?;",
        //                      [$grade, $grade_id, $stud_id, $report_id, $sub_class_code],
        //                     "iiii");  
        //second grading
        $this->prepared_query(
            "UPDATE `classgrade` SET `second_grading` =? WHERE `classgrade`.`grade_id` =? AND `classgrade`.`stud_id` = ? AND `classgrade`.`report_id` = ? AND `classgrade`.`sub_class_code` = ?;",
            [$second_grading, $grade_id, $stud_id, $report_id, $sub_class_code],
            "iiii"
        );


        //final grade
        $this->prepared_query(
            "UPDATE `classgrade` SET `second_grading` =? WHERE `classgrade`.`grade_id` =? AND `classgrade`.`stud_id` = ? AND `classgrade`.`report_id` = ? AND `classgrade`.`sub_class_code` = ?;",
            [$final_grade, $grade_id, $stud_id, $report_id, $sub_class_code],
            "iiii"
        );
    }

    //pacheeeeck - ben
    public function editValues()
    {
        $marking = $_POST['marking'];
        $stud_id = $_POST['stud_id'];
        $value_id = $_POST['value_id'];
        $quarter = $_POST['quarter'];
        $report_id = $_POST['report_id'];

        $this->prepared_query(
            "UPDATE `observedvalues` SET `marking`=? WHERE `stud_id`=? AND `value_id`=? AND `quarter`=? AND `report_id`=?;",
            [$marking, $stud_id, $value_id, $quarter, $report_id],
            "iiii"
        );
    }

    public function editAttendance()
    {
        //UPDATE `attendance` SET `no_of_present` = '29', `no_of_absent` = '1', `no_of_tardy` = '0', `no_of_days` = '30' WHERE `attendance`.`attendance_id` = 1
    }

    public function listSYJSON()
    {
        session_start();
        $result = $this->query("SELECT * FROM schoolyear ORDER BY status DESC;");
        $sy_list = [];
        $quarter_list = array('1' => 'First', '2' => 'Second', '3' => 'Third', '4' => 'Fourth');
        while ($row = mysqli_fetch_assoc($result)) {
            $sy_id = $row['sy_id'];
            $quarter = $row['current_quarter'];
            $enrollment = $row['can_enroll'];

            // quarter options
            $quarter_opt = "<input class='form-control m-0 border-0 bg-transparent' data-id='$sy_id' data-name='quarter' type='text'  data-key='$quarter' value='{$quarter_list[$quarter]}' readonly><select data-id='$sy_id' name='quarter' class='form-select d-none'>";
            foreach ($quarter_list as $id => $value) {
                $quarter_opt .= "<option value='$id'>$value</option>";
            }
            $quarter_opt .= "</select>";
            $sy_year = $row['start_year'] . " - " . $row['end_year'];

            # Action buttons
            $actions_btn = "";
            $archive_btn = "<button onclick='showNameToModal(`$sy_id`, `$sy_year`)' data-bs-toggle='modal' data-bs-target='#archive-confirmation-modal' class='btn btn-sm btn-danger archive-sy-btn' title='Archive'><i class='bi bi-archive'></i></button>";
            if ($sy_id == $_SESSION['sy_id']) {
                $actions_btn =  "<button data-id='$sy_id' title='Edit current quarter' class='btn btn-secondary edit-btn btn-sm'><i class='bi bi-pencil-square'></i></button>"
                    . "<div class='edit-options' style='display: none;'>"
                    . "<button data-id='$sy_id' class='save-btn d-inline w-auto  btn btn-success btn-sm'>Save</button>"
                    . "<button data-id='$sy_id' class='cancel-btn btn btn-outline-dark d-inline btn-sm m-1'>Cancel</button>"
                    . "</div>";
                $archive_btn = "";
            }

            $enroll_opt = ($enrollment ? "On-going" : "Ended");
            $enroll_opt =
                "<div class='form-check form-switch ms-3 my-auto'>"
                . "<input " . ($enrollment ? "checked" : "") . " name='enrollment' data-id='$sy_id' class='form-check-input' type='checkbox' title='Turn " . ($enrollment ? "off" : "on") . " enrollment' " . ($_SESSION['sy_id'] == $sy_id ? "" : "disabled") . ">"
                . "<span class='status'>$enroll_opt</span>"
                . "</div>";

            $switch = ($_SESSION['sy_id'] == $sy_id) ? "" : "<a role='button' title='Switch to this school year' href='action.php?action=switchSY&id=$sy_id' class='btn btn-dark btn-sm'>Switch</a>";
            $sy_list[] = [
                'id'              => $sy_id,
                's_year'          => $row['start_year'],
                'e_year'          => $row['end_year'],
                'sy_year'         => $sy_year,
                'current_qtr_val' => $quarter,
                'current_qtr'     => $quarter_opt,
                'enrollment_val'  => $enrollment,
                'enrollment'      => $enroll_opt,
                'action' => $actions_btn
                    . $switch
                    . "<a role='button' href='schoolYear.php?id=$sy_id' title='View school year' class='btn btn-primary btn-sm m-1' target='_blank'><i class='bi bi-eye'></i></a>"
                    . $archive_btn
            ];
        }
        echo json_encode($sy_list);
    }

    public function get_sy()
    {
        $result = $this->prepared_select("SELECT * FROM schoolyear WHERE sy_id=?", [$_GET['sy_id']], "i");
        $row = mysqli_fetch_assoc($result);
        return [
            'id' => $row['sy_id'],
            's_year' => $row['start_year'],
            'e_year' => $row['end_year'],
            'current_qtr' => $row['current_quarter'],
            'enrollment' => $row['can_enroll']
        ];
    }


    public function editSY()
    {
        session_start();
        $sy_id = $_POST['sy_id'] ?? $_SESSION['sy_id'];
        $quarter = $_POST['quarter'];
        $first = [1, 2];
        $sem = in_array($quarter, $first) ? 1 : 2;
        $this->prepared_query("UPDATE schoolyear SET current_quarter=?, current_semester=? WHERE sy_id=?", [$quarter, $sem, $sy_id], "iii");
        echo json_encode("School is currently at " . ($quarter == 1 ? "First" : ($quarter == 2 ? "Second" : ($quarter == 3 ? "Third" : "Fourth"))) . " Quarter");
    }

    public function editAcademicDays()
    {
        //        $sy_id = $_POST['sy-id'];
        session_start();
        $sy_id = $_SESSION['sy_id'];
        $new_month_values = $_POST['month'];
        # delete current months if new months value array is zero
        if (count($new_month_values) === 0) {
            echo ("Delete all");
            $this->query("DELETE FROM academicdays WHERE sy_id = '$sy_id';");
        } else {
            # store current academic days
            $cur_acad = [];
            $cur_acad_res = $this->query("SELECT acad_days_id FROM academicdays WHERE sy_id = '$sy_id';");
            while ($cur_row = mysqli_fetch_row($cur_acad_res)) {
                $cur_acad[] = $cur_row[0];
            }
            echo ("Current months <br>");
            print_r($cur_acad);

            # store new set of academic key ids
            $new_months_keys = array_keys($new_month_values);
            echo ("<br>To delete <br>");
            print_r($new_months_keys);


            # academic months to delete
            $acad_months_to_delete = array_diff($cur_acad, $new_months_keys);
            echo ("<br>To delete <br>");
            print_r($acad_months_to_delete);
            foreach ($acad_months_to_delete as $e) {
                $this->query("DELETE FROM academicdays WHERE acad_days_id='$e';");
            }

            foreach ($new_month_values as $m => $days) {
                $this->prepared_query("UPDATE academicdays SET no_of_days=? WHERE acad_days_id = ?;", [$days, $m], "is");
            }
        }

        if (isset($_POST['newmonth'])) {
            foreach ($_POST['newmonth'] as $month => $days) {
                $this->prepared_query("INSERT INTO academicdays (month, sy_id, no_of_days) VALUES (?, ?, ?);", [$month, $sy_id, $days], "sii");
            }
        }

        // $this->listSYJSON();
    }

    public function getSYInfo()
    {
        $sy_id = $_GET['id'];
        $sy_info = [
            "curriculum" => [], "month" => [],
            "subject" => [
                'core' => [],
                'spap' => []
            ]
        ];
        # school year description
        $sy_res = $this->query("SELECT CONCAT(start_year,' - ', end_year) AS sy_desc FROM schoolyear WHERE sy_id='$sy_id';");
        $sy_info["desc"] = mysqli_fetch_row($sy_res)[0];

        # curriculum
        $result = $this->query("SELECT syc_id, curr_code, curr_name FROM schoolyear sy 
                                        JOIN sycurriculum syc USING (sy_id)
                                        JOIN curriculum USING (curr_code)
                                        WHERE sy_id = '$sy_id';");
        while ($row = mysqli_fetch_assoc($result)) {
            $curr_code = $row['curr_code'];
            $syc_id = $row['syc_id'];
            $sy_info["curriculum"][$curr_code]['desc'] = $row['curr_name'];

            # program
            $prog_res = $this->query("SELECT prog_code, description FROM program JOIN sycurrstrand 
                                            USING (prog_code) WHERE curr_code='$curr_code'
                                                            AND syc_id = '$syc_id';");
            while ($prog_row = mysqli_fetch_assoc($prog_res)) {
                $sy_info["curriculum"][$curr_code]["program"][$prog_row['prog_code']] =  $prog_row['description'];
            }
        }
        # month
        $mon_res = $this->query("SELECT * FROM academicdays WHERE sy_id='$sy_id';");
        $days = 0;
        while ($mon_row = mysqli_fetch_assoc($mon_res)) {
            $days += $mon_row['no_of_days'];
            $sy_info['month'][$mon_row['acad_days_id']] = ['month' => $mon_row['month'], 'number' => $mon_row['no_of_days']];
        }
        $sy_info['total_days'] = $days;
        return $sy_info;
    }
    /** School Year Methods End */
    /** Section Methods */
    public function listSection($sy_id = NULL, $grade_level = NULL, $section = NULL)
    {
        if (empty($_SESSION)) {
            session_start();
        }

        $query = '';
        $sectionList = array();
        if (is_null($sy_id) && is_null($grade_level)) { # no specific school year id, grade level, and section
            $query = "SELECT section_code, section_name, sy_id, grd_level, stud_no_max, stud_no, CONCAT('T. ',' ',last_name,', ',first_name,' ',COALESCE(middle_name,''),' ', COALESCE(ext_name, '')) as name FROM section LEFT JOIN faculty USING (teacher_id) ";
            $query .= "WHERE sy_id={$_SESSION['sy_id']};";
            $result = $this->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $sectionList[] = new Section($row['section_code'], $row['sy_id'], $row['section_name'], $row['grd_level'], $row['stud_no_max'], $row['stud_no'], $row['name']);
            }
        } else {
            $grd_condition = isset($grade_level) ? "AND grd_level = '$grade_level' " : "";
            $section_condition = isset($section) ? "AND section_code != '$section';" : ";";
            $query = "SELECT section_code, section_name, grd_level, stud_no, CONCAT('T. ',' ',last_name,', ',first_name,' ',COALESCE(middle_name,''),' ', COALESCE(ext_name, '')) as name 
                      FROM section LEFT JOIN faculty USING (teacher_id) WHERE sy_id = '$sy_id' $grd_condition $section_condition";
            $result = $this->query($query);
            while ($row = mysqli_fetch_assoc($result)) {
                $sectionList[] = [
                    "section_code" => $row['section_code'],
                    "name"         => $row['section_name'],
                    "grade_level"  => $row['grd_level'],
                    "student_no"   => $row['stud_no'],
                    "adviser"      => $row['name']
                ];
            }
        }

        return $sectionList;
    }


    public function listSectionJSON($sy_id = NULL, $grade_level = NULL, $section = NULL)
    {
        echo json_encode($this->listSection($sy_id, $grade_level, $section));
    }

    public function enterAdviserLog($adviser, $section)
    {
        $result = $this->query("SELECT teacher_id FROM section WHERE section_code='$section';");
        if (!is_null($adviser)) {
            if (mysqli_fetch_row($result)[0] != $adviser) {
                $this->enterLog((mysqli_num_rows($result) > 0 ? "Updated" : "Assigned") . " adviser of section ID: $section.");
                $semester = in_array($_SESSION['current_quarter'], [1, 2]) ? "1" : "2";
                $this->query("INSERT INTO historysection VALUES ('$adviser', '$section', NOW(), '$semester');");
            }
        } else {
            $this->enterLog("Unassigned adviser of section ID: $section.");
        }
    }

    public function editSection()
    {
        $name = trim($_POST['sect-name']) ?: NULL;
        $max_no = $_POST['max-no'];
        $adviser = $_POST['adviser'] ?: NULL;
        $section = $_POST['section'];

        $this->enterAdviserLog($adviser, $section);
        $this->prepared_query(
            "UPDATE section SET section_name=?, stud_no_max=?, teacher_id=? WHERE section_code=?;",
            [$name, $max_no, $adviser, $section],
            "siis"
        );
        echo json_encode(["section" => $section]);
    }

    public function getSection()
    {
        $result = $this->prepared_select("SELECT * FROM section JOIN schoolyear USING(sy_id) WHERE section_code=?", [$_GET["sec_code"]], "s");
        $row = mysqli_fetch_assoc($result);
        $adv_result = $this->query("SELECT teacher_id, last_name, first_name, middle_name, ext_name FROM faculty where teacher_id='{$row['teacher_id']}'");
        $adviser = mysqli_fetch_assoc($adv_result);
        $school_year = $row['start_year'] . " - " . $row['end_year'];
        if ($adviser) {
            $name = "{$adviser['last_name']}, {$adviser['first_name']} {$adviser['middle_name']} {$adviser['ext_name']}";
            $adviser = [
                "teacher_id" => $adviser['teacher_id'],
                "name" => $name
            ];
        }
        return new Section(
            $row['section_code'],
            $row['sy_id'],
            $row['section_name'],
            $row['grd_level'],
            $row['stud_no_max'],
            $row['stud_no'],
            $adviser,
            $school_year
        );
    }

    public function getSectionSubClassInfo()
    {
        session_start();
        $code = $_GET['code'];
        $programs = [];
        $recommended = [];
        $sy_id = $_SESSION['sy_id'];

        $grd_level = mysqli_fetch_row($this->query("SELECT grd_level FROM section WHERE section_code = '$code';"))[0];
        $result = $this->query("SELECT DISTINCT(prog_code) FROM enrollment WHERE sy_id = '$sy_id' AND section_code = '$code'; ");
        while ($row = mysqli_fetch_row($result)) {
            $program = $row[0];
            $programs[] = ["code" => $program, "link" => "<a href='program.php?prog_code=$program' target='_blank' role='button' class='btn btn-sm btn-primary rounded-pill m-1'>$program</a>"];
        }

        # retrieve current subjects assigned to the section
        $current_sub_code = $this->getCurrentSubjectsOfSection($code, $sy_id);

        # retrieve faculty options
        $none_display = "style='display: none;'";
        $sub_class_faculty = [];
        $current_subjects = [];
        foreach ($programs as $prog) {
            $prog_code = $prog['code'];
            $result = $this->query("SELECT sub_code, sub_semester, sub_name FROM sharedsubject JOIN subject USING (sub_code) WHERE prog_code = '$prog_code' AND for_grd_level = '$grd_level';");
            while ($row = mysqli_fetch_assoc($result)) {
                $sub_code = $row['sub_code'];
                $sub_semester = $row['sub_semester'];

                if (!in_array($sub_code, $current_subjects)) {
                    $current_subjects[] = $sub_code;
                    $checked_state = (in_array($sub_code, $current_sub_code) ? "checked" : "");

                    # get current subject teacher
                    $teach_row = NULL;
                    $sub_class_code = NULL;
                    $teacher_id = NULL;

                    $teach_result = $this->query("SELECT sc.sub_class_code, sc.teacher_id FROM subjectclass sc
                                            JOIN section s ON sc.section_code = s.section_code
                                            LEFT JOIN faculty f ON sc.teacher_id = f.teacher_id WHERE sc.section_code='$code' AND sc.sub_code='$sub_code';");

                    if (mysqli_num_rows($teach_result) != 0) {
                        $teach_row = mysqli_fetch_row($teach_result);
                        $sub_class_code = $teach_row[0];
                        $teacher_id = $teach_row[1] ?? NULL;
                        $sub_class_faculty[] = [
                            'sect_code' => $code,
                            'sub_code'  => $sub_code,
                            'sub_teacher' => $teacher_id
                        ];
                    }

                    $add_btn_display = is_null($teacher_id) ? "" : $none_display;
                    $unassign_display = is_null($teacher_id) ? $none_display : "";
                    $action = "<div class='d-flex align-content-center'>"
                        . "<button class='btn-success btn btn-sm me-1 action' data-type='add' type='button' $add_btn_display><i class='bi bi-plus-lg'></i></button>"
                        . "<button class='btn-danger btn btn-sm me-1 action' data-type='unassign' data-section='$code' data-sub-code='$sub_code' title='Unassign Faculty' type='button' $unassign_display><i class='bi bi-person-dash-fill'></i></button>"
                        . "<span class='w-100'><select name='subjectClass[$code][$sub_code]' class='teacher-select select2--small'>"
                        . "<option value=''>-- Select Faculty --</option></select></span>"
                        . "</div>";

                    $recommended[$prog_code][$sub_semester][] = [
                        "checkbox" => "<input class='form-check-input' type='checkbox' name='subjects[$prog_code][]' value='$sub_code' id='$sub_code' $checked_state>",
                        "subName" => "<label class='form-check-label' for='$sub_code'>{$row['sub_name']}</label>",
                        "action" => $action
                    ];
                }
            }
        }
        echo json_encode([
            "programs"      => $programs,
            "recommended"   => $recommended,
            "currentSubTeacher" => $sub_class_faculty
        ]);
    }

    public function listSectionAdvisersHistory()
    {
        # history advisers
        $result = $this->query("SELECT CONCAT ('T. ',last_name,', ',first_name,' ',COALESCE(LEFT(middle_name, 1), ''),' ',COALESCE(ext_name,'')) AS teacher, date_assignment, 
            CASE WHEN semester = 1 THEN 'First' ELSE 'Second' END as semester  FROM `historysection` JOIN faculty USING (teacher_id) WHERE section_code = '{$_GET['section_code']}' ORDER BY date_assignment DESC");
        $list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = [
                'faculty' => $row['teacher'],
                'date_assignment' => date_format(date_create($row['date_assignment']), "d F Y, h:i:s A"),
                'semester' => $row['semester']
            ];
        }
        echo json_encode($list);
    }

    public function listSectionStudentJSON()
    {
    }

    /** Enrollment Methods */

    public function editEnrollStatus()
    {
        session_start();
        $can_enroll = isset($_POST['enrollment']) ? 1 : 0;
        if (isset($_POST['sy_id'])) {
            echo 'here';
            # enrollment status of other previous school year
            $sy_id = $_POST['sy_id'];
        } else {
            echo 'applied';
            # enrollment status of current school year; hence, update session value
            $sy_id = $_SESSION['sy_id'];
            $_SESSION['enroll_status'] = $can_enroll;
            echo $can_enroll;
        }
        $this->prepared_query("UPDATE schoolyear SET can_enroll=? WHERE sy_id=?;", [$can_enroll, $sy_id], "ii");
        // header("Location: enrollment.php");
    }
    /*** Curriculum Methods */
    public function checkCodeUnique()
    {
        $type = $_GET['type'];
        $table_condition = "$type";
        $code = '';
        switch ($type) {
            case "curriculum":
                $code = $_POST['code'];
                $table_condition .= " WHERE curr_code = '$code';";
                break;
            case "program":
                $code = $_POST['prog-code'];
                $table_condition .= " WHERE prog_code = '$code';";
                break;
            case "subject":
                $table_condition .= " WHERE sub_code = '$code';";
                break;
        }
        $row = mysqli_fetch_row($this->query("SELECT CASE WHEN COUNT(*) > 0 THEN 'false' ELSE 'true' END AS is_unique FROM $table_condition; "))[0];
        echo $row;
    }

    public function listCurriculumJSON()
    {
        echo json_encode($this->listCurriculum('curriculum'));
    }

    /** Get curriculum object from a specified curriculum code */
    public function getCurriculum()
    {
        $result = $this->prepared_select("SELECT * FROM curriculum WHERE curr_code=?", [$_GET['code']]);
        $row = mysqli_fetch_assoc($result);
        return new Curriculum($row['curr_code'], $row['curr_name'], $row['curr_desc']);
    }

    /** Adds a new curriculum */
    public function addCurriculum()
    {
        $code = $_POST['code'];
        $name = $_POST['name'];
        $desc = $_POST['curriculum-desc'];
        $this->prepared_query("INSERT INTO curriculum (curr_code, curr_name, curr_desc) VALUES (?, ?, ?)", [$code, $name, $desc]);
        $this->listCurriculumJSON();
    }

    public function deleteCurriculum()
    {
        $this->prepared_query("DELETE FROM curriculum WHERE curr_code=?;", [$_POST['code']]);
        $this->listCurriculumJSON();
    }

    public function updateCurriculum()
    {
        $code = $_POST['code'];
        $old_code = $_POST['current_code'];
        // parameter order: new code, current code, name, description, current code
        $param = [$code, $_POST['name'], $_POST['curriculum-desc'], $old_code];
        $this->prepared_query("UPDATE curriculum SET curr_code=?, curr_name=?, curr_desc=? WHERE curr_code=?;", $param);
        //                header("Location: curriculum.php?code=$code");
        echo json_encode($code);
    }

    public function moveCurriculum($pref_og, $pref_dest)
    {
        $code = $_POST['code'];

        $dest = "{$pref_dest}curriculum";
        $origin = "{$pref_og}curriculum";
        $prog_dest = "{$pref_dest}program";
        $prog_origin = "{$pref_og}program";
        $shared_dest = "{$pref_dest}sharedsubject";
        $shared_origin = "{$pref_og}sharedsubject";

        mysqli_query($this->db, "INSERT INTO {$dest} SELECT * FROM {$origin} WHERE curr_code = '{$code}';");
        mysqli_query($this->db, "INSERT INTO {$prog_dest} SELECT * FROM $prog_origin WHERE curr_code = '{$code}';");
        mysqli_query($this->db, "INSERT INTO {$shared_dest} SELECT * FROM $shared_origin WHERE prog_code IN (SELECT prog_code FROM $prog_origin WHERE curr_code = '{$code}');");
        mysqli_query($this->db, "DELETE FROM {$origin} WHERE curr_code = '{$code}';");
        // mysqli_multi_query($this->db, $query);
        $this->listCurriculumJSON();
    }

    /*** Program Methods */
    public function listProgramsJSON()
    {
        echo json_encode($this->listPrograms('program'));
    }

    public function listProgramsUnderCurrJSON($tbl)
    {
        echo json_encode($this->listPrograms($tbl));
    }

    public function getProgram()
    {
        $result = $this->prepared_select("SELECT * FROM program WHERE prog_code=?;", [$_GET['prog_code']]);
        $row = mysqli_fetch_assoc($result);
        return new Program($row['prog_code'], $row['curr_code'], $row['description']);
    }

    /** Adds a new program */
    public function addProgram()
    {
        session_start();
        $code = $_POST['prog-code'];
        $currCode = $_POST['curr-code'];
        $description = $_POST['desc'];

        $this->prepared_query("INSERT INTO program (prog_code, description, curr_code) VALUES (?, ?, ?);", [$code, $description, $currCode]);

        # initialize sharedsubject records for core subjects
        $result = $this->query("SELECT sub_code FROM subject WHERE sub_type = 'core';");
        while ($row = mysqli_fetch_row($result)) {
            $this->addSharedSubject([$row[0], $code]);
        }

        $this->listProgramsJSON();
    }

    public function deleteProgram()
    {
        $this->prepared_query("DELETE FROM program WHERE prog_code=?;", [$_POST['code']]);
        $this->listProgramsJSON();
    }

    public function updateProgram()
    {
        $code = $_POST['prog-code'];
        $prog_description = $_POST['name'];
        $old_code = $_POST['current_code'];

        $this->prepared_query("UPDATE program SET prog_code=?, description=? WHERE prog_code=?;", [$code, $prog_description, $old_code]);
    }

    public function moveProgram($pref_og, $pref_dest)
    {
        $code = $_POST['code'];

        $dest = "{$pref_dest}program";
        $origin = "{$pref_og}program";
        $shared_dest = "{$pref_dest}sharedsubject";
        $shared_origin = "{$pref_og}sharedsubject";

        $this->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->query("INSERT INTO $dest SELECT * FROM $origin where prog_code = '$code';");
        $this->query("INSERT INTO $shared_dest SELECT * FROM $shared_origin WHERE sub_code = '$code';");
        $this->query("DELETE FROM $origin WHERE prog_code = '$code';");
        $this->query("DELETE FROM $shared_origin WHERE prog_code = '$code';");
        $this->listProgramsJSON();
    }


    /*** Subject Methods */
    public function listAllSub($tbl)
    {
        $result = $this->query("SELECT * FROM {$tbl};");
        $subjectList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $subject =  new Subject($row['sub_code'], $row['sub_name'], $row['sub_type']);
            $subjectList[] = $subject;
        }
        echo json_encode($subjectList);
    }

    public function getSubjectScheduleData($prog_code = NULL, $is_for_tranferee = FALSE, $transferee_id = NULL)
    {
        if (empty($_SESSION)) {
            session_start();
        }

        $prog_condition = is_null($prog_code) ? "" : "AND prog_code = '$prog_code'";
        $result = $this->query("SELECT DISTINCT sub_code, sub_name, sub_type, prog_code FROM subject JOIN sharedsubject USING (sub_code);");

        $subjectList = array();
        while ($row = mysqli_fetch_row($result)) {
            $type = $row[2];
            $subjectList['options'][$type][]  = [
                'code' => $row[0],
                'name' => $row[1],
                'program' => $row[3]
            ];
        }

        $subjectList['schedule'] = [];
        if ($is_for_tranferee) {
            $result = $this->query("SELECT * FROM transfereeschedule JOIN subject USING (sub_code) 
                                             WHERE transferee_id='$transferee_id';");
        } else {
            $result = $this->query("SELECT * FROM subject JOIN sharedsubject USING (sub_code) WHERE for_grd_level != '0'  $prog_condition;");
        }
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $subjectList['schedule'][$row['prog_code']]["data[" . $row['for_grd_level'] . "][" . $row['sub_semester'] . "][" . $row['sub_type'] . "][]"][] =  $row['sub_code'];
            }
        }

        //        if ($is_JSON) {
        //            echo json_encode($subjectList);
        //            return;
        //        }
        return $subjectList;
    }

    private function setParentPrograms($code, $sub_type, $subject)
    {
        $result = $this->query("SELECT prog_code FROM sharedsubject WHERE sub_code='$code';");
        $programs = [];
        while ($row = mysqli_fetch_row($result)) {
            $programs[] = $row[0];
        }

        if ($sub_type == 'applied') {
            $subject->set_Programs($programs);
        } else if ($sub_type == 'specialized') {
            $subject->set_Program($programs[0]);
        }

        return $subject;
    }

    public function listSubUnderProgJSON()
    {
        $subjectList = [];
        $query = "SELECT sub_code FROM sharedsubject WHERE prog_code='{$_GET['prog_code']}';";
        $result = mysqli_query($this->db, $query);
        $subjects = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row['sub_code'];
        }

        foreach ($subjects as $sub_code) {
            $result = mysqli_query($this->db, "SELECT * FROM subject WHERE sub_code='$sub_code';");
            while ($row = mysqli_fetch_assoc($result)) {
                $code = $row['sub_code'];
                $sub_type = $row['sub_type'];
                $subject =  new Subject($code, $row['sub_name'], $sub_type);

                if ($sub_type !== 'core') {
                    session_start();
                    $sy_id = $_SESSION['sy_id'];
                    $sy_id_condition = (is_null($sy_id) ? "AND sy_id IS NULL;" : "AND sy_id = '$sy_id';");
                    $subject = $this->setParentPrograms($code, $sub_type, $subject, $sy_id_condition);
                }

                $subjectList[] = $subject;
            }
        }
        echo json_encode($subjectList);
    }

    public function getSubject()
    {
        $sy_id = $_SESSION['sy_id'] ?? NULL;
        $code = $_GET['sub_code'];
        $result = $this->prepared_select("SELECT * FROM subject WHERE sub_code=?", [$code]);
        $row = mysqli_fetch_assoc($result);
        $sub_type = $row['sub_type'];
        //        $sy_id_condition = (is_null($sy_id) ? "AND sy_id IS NULL;" : "AND sy_id = '$sy_id';");

        # schedule
        $res = $this->query("SELECT prog_code, sub_semester, for_grd_level FROM sharedsubject WHERE sub_code = '$code';");
        $schedule = [];
        while ($sched_row = mysqli_fetch_row($res)) {
            $grd = $sched_row[2];
            if ($grd != 0) {
                $schedule[] = ["program" => $sched_row[0], "semester" => $sched_row[1], "grade" => $grd];
            }
        }
        # end schedule

        $subject = new Subject($row['sub_code'], $row['sub_name'], $sub_type, $schedule);
        if ($sub_type != 'core') {
            $subject = $this->setParentPrograms($code, $sub_type, $subject);
        }

        $resultTwo = $this->query("SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='PRE';");
        $prereq = [];
        if ($resultTwo) {
            while ($rowTwo = mysqli_fetch_assoc($resultTwo)) {
                $prereq[] = $rowTwo['req_sub_code'];
            }
        }

        $resultThree = $this->query("SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='CO';");
        $coreq = [];
        if ($resultThree) {
            while ($rowThree = mysqli_fetch_assoc($resultThree)) {
                $coreq[] = $rowThree['req_sub_code'];
            }
        }

        if ($prereq) {
            $subject->set_prerequisite($prereq);
        }

        if ($coreq) {
            $subject->set_corequisite($coreq);
        }
        return $subject;
    }

    /** Adds a sharedsubject record */
    public function addSharedSubject($params)
    {
        $this->prepared_query("INSERT INTO sharedsubject (sub_code, prog_code, for_grd_level, sub_semester) VALUES (?, ?, 0, 0);", $params, "ss");
    }

    /**
     * Adds a new subject
     * 1. Insert subject record
     *
     */
    public function addSubject()
    {
        session_start();
        $sy_id = $_SESSION['sy_id'] ?? NULL;
        $code = $_POST['code'];
        $subName = $_POST['name'];
        $type = $_POST["sub-type"];

        # Step 1
        $this->prepared_query(
            "INSERT INTO subject (sub_code, sub_name, sub_type) VALUES (?, ?, ?);",
            [$code, $subName, $type],
            "sss"
        );

        # insert program and subject info in sharedsubject table
        if ($type == 'core') {
            $all_programs = $this->listPrograms('program');
            foreach ($all_programs as $ap) {
                $prog_code = $ap->get_prog_code();
                $this->addSharedSubject([$code, $prog_code]);
            }
        }

        if ($type == 'specialized') {
            $prog_code = $_POST['prog_code'][0];
            $this->addSharedSubject([$code, $prog_code],);
        }

        if ($type == 'applied') {
            $prog_code_list = $_POST['prog_code'];
            foreach ($prog_code_list as $prog_code) {
                $this->addSharedSubject([$code, $prog_code]);
            }
        }

        # insert pre | co requisite
        if (isset($_POST['PRE'])) {
            foreach ($_POST['PRE'] as $req_code) {
                $this->prepared_query("INSERT INTO requisite (sub_code, type, req_sub_code) VALUES (?, 'PRE', ?);", [$code, $req_code]);
            }
        }

        if (isset($_POST['CO'])) {
            foreach ($_POST['CO'] as $req_code) {
                $this->prepared_query("INSERT INTO requisite (sub_code, type, req_sub_code) VALUES (?, 'CO', ?);", [$code, $req_code]);
            }
        }

        $redirect = (($type === 'specialized') ? "prog_code=$prog_code&" : "") . "sub_code=$code&state=view";
        echo json_encode((object) ["redirect" => $redirect, "status" => 'Subject successfully added!']);
    }

    public function getUpdateRequisiteQry($code, $requisite)
    {
        //        $query = '';
        if (isset($_POST["$requisite"])) {
            $req_code_list = $_POST["$requisite"];

            // get current requisite subjects
            $queryThree = "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='$requisite';";
            $result = $this->query($queryThree);
            $current_subjects = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $current_subjects[] = $row['req_sub_code'];
            }

            // delete req subject not found in the submitted requisite code list
            $sub_codes_to_delete = array_diff($current_subjects, $req_code_list);
            if (count($sub_codes_to_delete) > 0) {
                foreach ($sub_codes_to_delete as $code_to_delete) {
                    $this->query("DELETE FROM requisite WHERE sub_code='$code' AND req_sub_code='$code_to_delete' AND  type='$requisite';");
                }
            }

            // add new req subject found in the submitted requisite code list
            $new_sub_codes = array_diff($req_code_list, $current_subjects);       // codes not found in the current req sub will be added as new row in the db
            if (count($new_sub_codes) > 0) {
                foreach ($new_sub_codes as $new_code) {
                    $this->query("INSERT INTO requisite VALUES ('$code', '$requisite', '$new_code');");
                }
            }
        } else {
            $this->query("DELETE FROM requisite WHERE sub_code='$code' AND  type='$requisite';");
        }

        //        return $query;
    }

    public function updateSubject()
    {
        session_start();
        $current_sub_code = $_POST['current_sub_code'];
        $code = $_POST['code'];
        $subName = $_POST['name'];
        $type = $_POST["sub-type"];
        # validate here
        $this->query("UPDATE subject SET sub_code='$code', sub_name='$subName', sub_type='$type' WHERE sub_code='$current_sub_code';");
        $this->getUpdateProgramQuery($code, $type);
        $this->getUpdateRequisiteQry($code, 'PRE');
        $this->getUpdateRequisiteQry($code, 'CO');
        $redirect = (($type === 'specialized') ? "prog_code={$_POST['prog_code'][0]}&" : "") . "sub_code=$code&state=view&success=updated";
        echo json_encode(["redirect" => $redirect, "status" => 'Subject successfully updated!']);
    }

    private function getUpdateProgramQuery($code, $type)
    {
        $current_programs = $_POST['old_program'] ?? NULL;

        // insert program and subject info in shared subject table
        if ($type === 'applied') {
            $prog_code_list = $_POST['prog_code'];
            $prog_codes_to_delete = array_diff($current_programs, $prog_code_list);
            if (count($prog_codes_to_delete) > 0) {
                foreach ($prog_codes_to_delete as $code_to_delete) {
                    $this->query("DELETE FROM sharedsubject WHERE prog_code='$code_to_delete' AND sub_code='$code';");
                }
            }

            // add new row with new program codes found in the submitted program code list
            $new_prog_codes = array_diff($prog_code_list, $current_programs);
            if (count($new_prog_codes) > 0) {
                foreach ($new_prog_codes as $new_code) {
                    $this->query("INSERT INTO sharedsubject (sub_code, prog_code, for_grd_level, sub_semester) VALUES ('$code', '$new_code', 0, 0);");
                }
            }
            return;
        }

        if ($type === 'specialized') {
            $new_prog_code = $_POST['prog_code'][0];
            // get current program/s from db
            $result = $this->query("SELECT prog_code FROM sharedsubject WHERE sub_code='$code';");

            if (mysqli_num_rows($result) > 1) {
                $this->query("DELETE FROM sharedsubject WHERE sub_code='$code';");
                $this->query("INSERT INTO sharedsubject (prog_code, sub_code, for_grd_level, sub_semester) VALUES ('$new_prog_code', '$code', 0, 0);");
                return;
            }

            $current_program = mysqli_fetch_row($result)[0];
            $this->query("UPDATE sharedsubject SET prog_code='$new_prog_code' WHERE prog_code='{$current_program}' AND sub_code='$code';");
            return;
        }

        # subject type is core at this point
        # get all programs offered
        $result = $this->query("SELECT DISTINCT prog_code FROM sharedsubject;");
        $all_programs = [];
        while ($row = mysqli_fetch_row($result)) {
            $all_programs[] = $row[0];
        }
        $to_add = array_diff($all_programs, $current_programs);
        foreach ($to_add as $to_add_sub_prog) {
            $this->query("INSERT INTO sharedsubject (sub_code, prog_code, for_grd_level, sub_semester) VALUES ('$code', '$to_add_sub_prog', 0, 0);");
        }
    }



    public function deleteSubject()
    {
        $code = $_POST['code'];
        $query = "DELETE FROM subject WHERE sub_code='$code'";
        $this->query($query);
    }

    public function searchSubjects()
    {
        if (strlen($_GET['keyword']) > 0) {
            $text = $_GET['keyword'];
            $query = "SELECT * FROM subject WHERE sub_name LIKE \"%$text%\"";
            $result = mysqli_query($this->db, $query);
            $subjects = array();

            while ($row = mysqli_fetch_assoc($result)) {
                $sub = new stdClass();
                $sub->code = $row['code'];
                $sub->name = $row['name'];
                $subjects[] = $sub;
            }
            echo json_encode($subjects);
        }
    }


    /** Returns the list of subjects by specified grade level. */
    public function listSubjectsByLevel($grd)
    {
        $result = $this->query("SELECT * FROM subject JOIN sharedsubject USING (sub_code) WHERE for_grd_level = '$grd' GROUP BY sub_code;");
        $subjects = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = new Subject($row['sub_code'], $row['sub_name'], $row['sub_type']);
        }
        return $subjects;
    }

    public function listSubjectbyLevelJSON($grd)
    {
        echo json_encode($this->listSubjectsByLevel($grd));
    }

    function listArchSubjectsJSON()
    {
        // echo json_encode($this->listSubjects('archived_subject'));
    }

    /** Returns the list of subjects by specified grade level. */
    public function listFacultySubjects()
    {
        $code = 'ABM11'; //$_POST['section_code'];
        $result = $this->query("SELECT sub_class_code, s.sub_code, s.sub_name FROM subjectclass JOIN subject as s USING (sub_code) WHERE section_code= '$code';");
        $subjects = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = new Subject($row['sub_class_code'], $row['sub_code'], $row['sub_name']);
        }
        return $subjects;
    }

    public function moveSubject($pref_og, $pref_dest)
    {

        $sub_origin = "{$pref_og}subject";
        $sub_dest = "{$pref_dest}subject";

        $shared_origin = "{$pref_og}sharedsubject";
        $shared_dest = "{$pref_dest}sharedsubject";

        $req_dest = "{$pref_dest}requisite";
        $req_origin = "{$pref_og}requisite";

        $code = $_POST['code'];

        $this->query("INSERT INTO $sub_dest SELECT * FROM $sub_origin WHERE sub_code = '$code';");
        $this->query("INSERT INTO $shared_dest SELECT * FROM $shared_origin WHERE sub_code = '$code';");
        $this->query("INSERT INTO $req_dest SELECT * FROM $req_origin where sub_code = '$code';");
        $this->query("DELETE FROM $sub_origin WHERE sub_code = '$code';");
        $this->query("DELETE FROM $req_origin WHERE sub_code = '$code';");
    }

    public function saveSchedule()
    {
        $prog_code = $_POST['program'];
        $grd_levels = [11, 12];
        $semesters = [1, 2];
        $types = ['core', 'specialized', 'applied'];
        foreach ($grd_levels as $grd) {
            foreach ($semesters as $sem) {
                foreach ($types as $type) {
                    $codes = $_POST['data'][$grd][$sem][$type] ?? [];

                    $result = $this->query("SELECT sub_code FROM sharedsubject JOIN subject USING (sub_code) WHERE sub_type='$type' 
                        AND prog_code = '$prog_code' AND ((for_grd_level = '$grd' AND sub_semester = '$sem') OR for_grd_level = '0');");
                    $current = [];
                    while ($row = mysqli_fetch_row($result)) {
                        $current[]  = $row[0];
                    }
                    echo "current<br>";
                    print_r($current);
                    echo "new<br>";
                    print_r($codes);

                    # Delete
                    $delete = array_diff($current, $codes);
                    foreach ($delete as $sub_del) {
                        $this->query("DELETE FROM sharedsubject WHERE sub_code = '$sub_del' AND prog_code = '$prog_code' AND for_grd_level = '$grd' AND sub_semester = '$sem';");
                    }

                    # Add
                    $add = array_diff($codes, $current);
                    echo "add<br>";
                    print_r($add);
                    echo "end";
                    foreach ($add as $sub_add) {
                        $this->query("INSERT INTO sharedsubject (sub_code, prog_code, for_grd_level, sub_semester, sy_id) VALUES ('$sub_add', '$prog_code', '$grd', '$sem');");
                    }

                    # update
                    $update = array_intersect($codes, $current);
                    print_r("update");
                    print_r($update);
                    foreach ($update as $sub_upd) {
                        echo "UPDATE sharedsubject SET for_grd_level='$grd', sub_semester='$sem' WHERE sub_code='$sub_upd' AND prog_code='$prog_code';";
                        $this->query("UPDATE sharedsubject SET for_grd_level='$grd', sub_semester='$sem' WHERE sub_code='$sub_upd' AND prog_code='$prog_code';");
                    }
                }
            }
        }
        echo json_encode(["program" => $prog_code, "new" => $this->getSubjectScheduleData($prog_code)['schedule']]);
    }

    public function saveTransfereeSchedule($stud_id, $transferee_id, $prog_code)
    {
        session_start();
        $sy_id = $_SESSION['sy_id'] ?? NULL;
        $grd_levels = [11, 12];
        $semesters = [1, 2];
        $types = ['core', 'specialized', 'applied'];
        $sy_condition = (is_null($sy_id) ? "IS NULL" : "= '$sy_id'");

        $report_id = mysqli_fetch_row($this->query("SELECT report_id FROM `gradereport` WHERE stud_id = '$stud_id' AND sy_id = '$sy_id';"))[0];
        foreach ($grd_levels as $grd) {
            foreach ($semesters as $sem) {
                foreach ($types as $type) {
                    $codes = $_POST['data'][$grd][$sem][$type] ?? [];

                    $result = $this->query("SELECT sub_code FROM transfereeschedule JOIN subject USING (sub_code) WHERE sub_type='$type' 
                        AND prog_code = '$prog_code'  AND sy_id " . $sy_condition . " AND ((for_grd_level = '$grd' AND sub_semester = '$sem') OR for_grd_level = '0')
                        AND transferee_id = '$transferee_id';");

                    $current = [];
                    while ($row = mysqli_fetch_row($result)) {
                        $current[] = $row[0];
                    }

                    # Delete
                    $delete = array_diff($current, $codes);
                    foreach ($delete as $sub_del) {
                        $this->query("DELETE FROM transfereeschedule WHERE transferee_id = '$transferee_id' AND sub_code = '$sub_del' AND prog_code = '$prog_code' AND for_grd_level = '$grd' AND sub_semester = '$sem' AND sy_id $sy_condition;");
                        $this->query("DELETE FROM classgrade WHERE report_id = '$report_id' AND sub_code = '$sub_del' AND prog_code = '$prog_code' AND for_grd_level = '$grd' AND sub_semester = '$sem' AND sy_id $sy_condition;");
                    }

                    # Add
                    $add = array_diff($codes, $current);
                    foreach ($add as $sub_add) {
                        $this->query("INSERT INTO transfereeschedule (transferee_id, sub_code, prog_code, for_grd_level, sub_semester, sy_id) VALUES ('$transferee_id','$sub_add', '$prog_code', '$grd', '$sem', " . (is_null($sy_id) ? "NULL" : "'$sy_id'") . ");");
                        $current_subgrade = $this->query("SELECT * FROM classgrade WHERE report_id = '$report_id' AND sub_code = '$sub_add';");
                        if (mysqli_num_rows($current_subgrade) == 0) {
                            $this->query("INSERT INTO classgrade (report_id, stud_id, sub_code) VALUES('$report_id', '$stud_id', '$sub_add') ON DUPLICATE KEY UPDATE sub_code='$sub_add';");
                        }
                    }

                    # update
                    $update = array_intersect($codes, $current);
                    foreach ($update as $sub_upd) {
                        $this->query("UPDATE sharedsubject SET for_grd_level='$grd', sub_semester='$sem' WHERE sub_code='$sub_upd' AND prog_code='$prog_code' AND sy_id $sy_condition;");
                    }
                }
            }
        }
        header("Location: student.php?id=$stud_id");
    }

    public function getTransfereeSubject()
    {
        $data = [];
        $result = $this->query("SELECT sub_code FROM transfereesubject WHERE transferee_id = '{$_GET['transferee_id']}';");
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_row($result)) {
                $data[] = $row[0];
            }
        }
        echo json_encode($data);
    }
    /** Faculty Methods */

    public function listFacultyPrivilegeJSON()
    {
        $result = $this->query("SELECT teacher_id, CONCAT(last_name,', ',first_name,' ',COALESCE(middle_name,''),' ',COALESCE(ext_name, '')) AS name, "
            . "enable_enroll FROM faculty ORDER BY name;");
        $faculty_list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $can_enroll_display = "";
            $cant_enroll_disp = "d-none";
            $enable_enroll = $row['enable_enroll'];
            if ($enable_enroll == 0) {
                $can_enroll_display = "d-none";
                $cant_enroll_disp = "";
            }
            $teacher_id = $row['teacher_id'];

            $action = "<div>"
                . "<a href='javascript:;' onClick='togglePrivilege(`{$teacher_id}`, `0`)' class='can-enroll-btn $can_enroll_display btn-sm btn btn-primary' title='Unallow teacher to enroll'>Yes</a>"
                . "<a href='javascript:;' onClick='togglePrivilege(`{$teacher_id}`, `1`)' class='cant-enroll-btn $cant_enroll_disp btn-sm btn btn-secondary' title='Allow teacher to enroll'>No</a>"
                . "</div>";

            $faculty_list[] = [
                'teacher_id' => $teacher_id,
                'name'       => $row['name'],
                'status'     => $enable_enroll,
                'can-enroll' => $action
            ];
        }
        echo json_encode($faculty_list);
    }

    public function listFaculty()
    {
        $result = $this->query("SELECT * FROM faculty f JOIN user u ON f.teacher_user_no = u.id_no;");
        $facultyList = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $facultyList[] = new Faculty(
                $row['teacher_user_no'],
                $row['teacher_id'],
                $row['last_name'],
                $row['middle_name'] ?: "",
                $row['first_name'],
                $row['ext_name'] ?: "",
                $row['birthdate'],
                $row['age'],
                $row['sex'],
                $row['department'],
                $row['cp_no'],
                $row['email'],
                $row['award_coor'],
                $row['enable_enroll'],
                $row['is_active']
            );
        }
        return $facultyList;
    }

    public function listNotAdvisers($teacher_id = NULL)
    {
        $result = $this->query("SELECT CONCAT(last_name, ', ', first_name,' ',COALESCE(middle_name,'')) as name, teacher_id FROM faculty WHERE teacher_id NOT IN (SELECT DISTINCT (teacher_id)
                    FROM section WHERE teacher_id IS NOT NULL AND section.sy_id = {$_SESSION['sy_id']})" . (!is_null($teacher_id) ? " OR teacher_id = '{$teacher_id}';" : ";"));
        $not_advisers = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $not_advisers[] = [
                "teacher_id" => $row["teacher_id"],
                "name"       => $row["name"]
            ];
        }
        return $not_advisers;
    }

    public function listFacultyJSON()
    {
        echo json_encode($this->listFaculty());
    }

    /** User Profile */
    /**
     * Returns the count of current administrators, faculty, and students.
     * @return array
     */
    public function getUserCounts()
    {
        $query = "SELECT (
            SELECT COALESCE(COUNT(admin_id), 0) FROM administrator
        ) AS administrators, 
        (    
            SELECT COALESCE(COUNT(teacher_id), 0) FROM faculty
        ) as teachers,
        (
            SELECT COALESCE(COUNT(stud_id), 0) FROM enrollment e JOIN student  USING (stud_id) WHERE sy_id = '{$_SESSION['sy_id']}' AND e.valid_stud_data = '1'
        ) as students, 
        (
           SELECT COALESCE(COUNT(sign_id), 0) FROM signatory
        ) as sign;";
        $result = $this->query($query);
        $row = mysqli_fetch_row($result);
        return [$row[0], $row[1], $row[2], $row[3]];
    }

    /**
     * Resets the password of the user with the given User ID.
     * @param int $user_id ID of the user.
     */
    public function resetPassword(int $user_id)
    {
        $query = '';
        $user_type = $_POST['user_type'];
        switch ($user_type) {
            case "AD":
                $query = "SELECT admin_user_no FROM administrator WHERE admin_id='$user_id';";
                break;
            case "FA":
                $query = "SELECT teacher_user_no FROM faculty WHERE teacher_id='$user_id';";
                break;
            case "ST":
                $query = "SELECT id_no FROM student WHERE stud_id ='$user_id'";
                break;
        }
        $row = mysqli_fetch_row($this->query($query))[0];
        echo $row;
        $default_password = password_hash($user_type . $row, PASSWORD_DEFAULT);
        $this->query("UPDATE user SET password='$default_password' WHERE id_no='$row';");
    }

    /**
     * Resets the password of users stored in the given array.
     */
    public function resetMultiplePassword()
    {
        foreach ($_POST['id'] as $user_id) {
            $this->resetPassword($user_id);
        }
    }

    public function deleteStudentData($stud_id)
    {
        # delete student images
        $result = $this->query("SELECT form_137, id_picture, psa_birth_cert FROM student WHERE stud_id = '$stud_id';");
        $row = mysqli_fetch_row($result);
        foreach ([$row[0], $row[1], $row[2]] as $path) {
            if (!is_null($path)) {
                $path = "../" . $path;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        # reduce section student no if student have section
        $this->query("UPDATE section SET stud_no = (stud_no - 1) WHERE section_code = ANY(SELECT section_code FROM enrollment WHERE stud_id = '$stud_id');");

        # delete student record
        $id_no = mysqli_fetch_row($this->query("SELECT s.id_no FROM student s WHERE stud_id = '$stud_id';"))[0];
        $this->query("DELETE FROM student WHERE stud_id = '$stud_id';");

        # delete user record
        $this->query("DELETE FROM user WHERE id_no = '$id_no';");
    }

    public function deleteStudent()
    {
        if (isset($_GET['id'])) {
            $this->deleteStudentData($_GET['id']);
            return;
        }
        $students = $_POST['students'];
        foreach ($students as $stud) {
            $this->deleteStudentData($stud);
        }
    }

    public function checkAdministratorCount()
    {
        $count = mysqli_fetch_row($this->query("SELECT COUNT(admin_id) FROM administrator; "))[0];
        // $is_allowed_to_delete = TRUE;
        if ($count == 1) {
            http_response_code(403);
            die();
        }
        // echo json_encode(["allowed" => $is_allowed_to_delete]);
    }

    public function deleteAdmin()
    {
        session_start();
        $id = $_SESSION['id'];
        $this->query("DELETE FROM user WHERE id_no = ANY(SELECT admin_user_no FROM administrator WHERE admin_id = '$id') AND user_type = 'AD';");
        $result = $this->query("SELECT * FROM administrator;");
        if (mysqli_num_rows($result) == 0) {
            $this->createDefaultAdmin();
        }
        echo json_encode("../logout.php");
    }


    public function createDefaultAdmin()
    {
        if (empty($_SESSION)) {
            session_start();
        }
        $id = $this->createUser('AD', TRUE);
        $this->query("INSERT INTO administrator (admin_id, last_name, first_name, admin_user_no) VALUES (1, 'PCNHS', 'ADMIN', '$id');");
        header("Location: ../login.php");
    }

    /** Faculty Methods */
    /**
     * Implementation of inserting Faculty
     * 1.   Create user default password by concatenating the prefix and the next 
     *      auto increment value (that is maximum of column id_no + 1). ex. FA1234567890
     * 2.   Create faculty record
     * 3.   Insert every subject handled if exist
     * 4.   Insert every subject class handled
     *  */
    private function addFaculty($params, $types)
    {

        // Step 1
        $user_id = $this->createUser("FA");
        $params[] = $user_id;
        $types .= "i";

        // Step 2
        $query = "INSERT INTO faculty (last_name, first_name, middle_name, ext_name, birthdate, age, sex,  email, award_coor, enable_enroll, department, cp_no, teacher_user_no) "
            . "VALUES(?, ?, ?, ?, ?,  ?, ?, ?, ?, ?,  ?, ?, ?);";

        $this->prepared_query($query, $params, $types);
        $id = mysqli_insert_id($this->db);  // store the last inserted primary key (that is the teacher_id)

        // Step 3
        if (isset($_POST['subjects'])) {
            $subjects = $_POST['subjects'];
            foreach ($subjects as $subject) {
                $this->prepared_query("INSERT INTO subjectfaculty (sub_code, teacher_id) VALUES (?, ?);", [$subject, $id], "sd");
            }
        }

        // Step 4
        $this->updateAssignedSubClass($id);

        if ($id) {
            echo json_encode(["teacher_id" => $id]);
            # header("Location: faculty.php?id=$id");
        } else {
            return "Faculty unsuccessfully added.";
        }
    }

    /**
     * @param int|string $id
     */
    private function updateAssignedSubClass(string $id): void
    {
        if (isset($_POST['asgn-sub-class'])) {
            $asgn_sub_classes = $_POST['asgn-sub-class'];

            $result = $this->query("SELECT sub_class_code FROM subjectclass WHERE teacher_id='$id';");
            $current_asgn_sub_classes = [];
            while ($row = mysqli_fetch_row($result)) {
                $current_as_class = $current_asgn_sub_classes[] = $row[0];
                if (!in_array($current_as_class, $asgn_sub_classes)) {
                    $this->query("UPDATE subjectclass SET teacher_id=NULL WHERE sub_class_code='$current_as_class';");
                }
            }

            foreach (array_diff($asgn_sub_classes, $current_asgn_sub_classes) as $new_asgn_sub_class) {
                $this->query("UPDATE subjectclass SET teacher_id='$id' WHERE sub_class_code='$new_asgn_sub_class';");
            }
        } else {
            $this->query("UPDATE subjectclass SET teacher_id=NULL WHERE teacher_id = '$id';");
        }
    }

    /**
     * Updates the faculty roles in the database.
     */
    public function updateFacultyRoles()
    {
        $param = $this->prepareFacultyRolesValue();
        $param[] = $_POST['teacher_id'];
        $this->prepared_query(
            "UPDATE faculty SET enable_enroll=?, award_coor=? WHERE teacher_id=?;",
            $param,
            "iii"
        );
    }

    public function changeEnrollPriv()
    {
        $teacher_id_list = $_POST['teacher-id'];
        foreach ($teacher_id_list as $teacher_id) {
            $this->query("UPDATE faculty SET enable_enroll='{$_POST['can-enroll']}' WHERE teacher_id='$teacher_id';");
        }
    }

    /**
     * Evaluates the current faculty roles basing from the POST value 'access'
     * 
     * @return array An array of binary values indicating the bool values of the faculty roles.
     *               Array order: 
     *                  [0] Edit grades
     *                  [1] Can Enroll
     *                  [2] Award Coordinator
     */
    private function prepareFacultyRolesValue()
    {
        $canEnroll = 0;
        $awardRep = 0;
        if (isset($_POST['access'])) {
            foreach ($_POST['access'] as $accessRole) {
                if ($accessRole == 'canEnroll') {
                    $canEnroll = 1;
                }
                if ($accessRole == 'awardReport') {
                    $awardRep = 1;
                }
            }
        }
        return [$canEnroll, $awardRep];
    }

    /**
     * Updates the subjects handled by the faculty if exists with the given Faculty ID.
     * If no subjects are submitted, all subject rows associated to the faculty is deleted.
     * 
     * 1.   Store the new set of subjects
     * 2.   Delete rows with subject codes not found in the submitted list of subjects
     * 3.   Add new row with subject codes found in the submitted list of subjects
     */
    public function updateFacultySubjects($id)
    {
        if (isset($_POST['subjects'])) {
            // Step 1
            $subjects = $_POST['subjects'];
            $result = $this->query("SELECT sub_code FROM subjectfaculty WHERE teacher_id='$id'");
            $current_subjects = [];

            if (mysqli_num_rows($result) == 0) {
                foreach ($subjects as $sub) {
                    $this->query("INSERT INTO subjectfaculty (sub_code, teacher_id) VALUES ('$sub', '$id');");
                }
                return;
            }

            while ($row =  mysqli_fetch_row($result)) {
                $current_subjects[] = $row[0];
            }

            // Step 2
            $sub_codes_to_delete = array_diff($current_subjects, $subjects); // compares the two arrays, and returns an array of elements not found in array 2
            foreach ($sub_codes_to_delete as $code_to_delete) {
                $this->query("DELETE FROM subjectfaculty WHERE sub_code='$code_to_delete' AND teacher_id='$id';");
            }

            // Step 3
            $new_sub_codes = array_diff($subjects, $current_subjects);       // codes not found in the current subjects will be added as new row in the db
            foreach ($new_sub_codes as $new_code) {
                $this->query("INSERT INTO subjectfaculty (sub_code, teacher_id) VALUES ('$new_code', '$id');");
            }
        } else {
            // Delete all subject rows handled by the faculty
            $result = $this->query("DELETE FROM subjectfaculty 
                                                      WHERE teacher_id='$id' 
                                                      AND (SELECT COUNT(sub_code) WHERE teacher_id='$id');") > 0;
        }
    }

    /**
     * Updates the current department of the faculty.
     */
    public function updateFacultyDepartment()
    {
        $this->prepared_query(
            "UPDATE faculty SET department=? WHERE teacher_id=?;",
            [$_POST['department'], $_POST['teacher_id']],
            "si"
        );
    }

    public function listSubClassFacultyOptions()
    {
        //        $condition = '';
        //        if (isset($_POST['exclude'])) {
        //            $condition = "WHERE teacher-id != '{$_POST['exclude']}'";
        //        }
        $result = $this->query("SELECT teacher_id, CONCAT('T. ',last_name, ', ', first_name, ' ', 
                                    COALESCE(middle_name, ''), ' ',COALESCE(ext_name, '')) AS name
                                    FROM faculty f JOIN user u ON f.teacher_user_no = u.id_no WHERE u.is_active = 1;");
        $faculty_list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $faculty_list[] = [
                'teacher_id' => $row['teacher_id'],
                'name'       => $row['name']
            ];
        }
        return $faculty_list;
    }

    /** Faculty End */
    public function deleteUser($type)
    {
        $id = $_POST['id'];
        $this->query("DELETE FROM user WHERE id_no='$id' AND user_type='$type';");
    }

    public function listStudent()
    {
        session_start();

        $query = "SELECT * from student s "
            . "JOIN enrollment e ON e.stud_id = s.stud_id "
            . "JOIN user u ON u.id_no = s.id_no WHERE e.valid_stud_data = '1' AND e.sy_id = {$_SESSION['sy_id']} "
            . (isset($_GET['section']) ? "AND e.section_code='{$_GET['section']}';" : ""); # WHERE s.id_no IN (SELECT id_no FROM user WHERE is_active=1);
        $result = $this->query($query);
        $studentList = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $studentList[] = new Student(
                $row['stud_id'],
                $row['id_no'],
                $row['LRN'],
                $row['first_name'],
                $row['middle_name'],
                $row['last_name'],
                $row['ext_name'],
                $row['sex'],
                $row['age'],
                $row['birthdate'],
                $row['birth_place'],
                $row['indigenous_group'],
                $row['mother_tongue'],
                $row['religion'],
                NULL,
                $row['cp_no'],
                $row['psa_birth_cert'],
                $row['belong_to_IPCC'],
                $row['id_picture'],
                $row['section_code'],
                $row['section_code'],
                NULL,
                NULL,
                NULL,
                NULL,
                $row['is_active'],
                $row['prog_code']
            );
        }
        return $studentList;
    }

    public function listStudents($is_JSON = FALSE)
    {
        $sy_id = $_GET['sy_id'];
        $section = $_GET['section'];
        $student_list = [];
        $result = $this->query("SELECT LRN, stud_id, CONCAT(last_name,', ', first_name,' ',COALESCE(middle_name, ''),' ', COALESCE(ext_name, '')) AS name,
                                 section_code, section_name, enrolled_in AS grade, prog_code FROM student JOIN enrollment e USING (stud_id) 
                                    JOIN user USING (id_no)
                                     LEFT JOIN section USING (section_code) WHERE e.sy_id='$sy_id' AND (section_code != '$section' OR section_code IS NULL) AND valid_stud_data = '1' AND is_active = '1';");
        while ($row = mysqli_fetch_assoc($result)) {
            $student_list[] = [
                'lrn'           => $row['LRN'],
                'stud_id'       => $row['stud_id'],
                'name'          => $row['name'],
                'section_code'  => $row['section_code'],
                'section_name'  => $row['section_name'],
                'program'       => $row['prog_code'],
                'grade'         => $row['grade']
            ];
        }

        if ($is_JSON) {
            echo json_encode($student_list);
            return;
        }
        return $student_list;
    }



    public function listStudentJSON()
    {
        echo json_encode($this->listStudent());
    }

    /** Enroll Methods */

    public function listEnrolleesJSON()
    {
        echo json_encode($this->getEnrollees());
    }

    /** Section Methods */
    public function listAllSubjectClasses($is_JSON = FALSE)
    {
        if (empty($_SESSION)) {
            session_start();
        }
        $result = $this->query("SELECT sub_class_code, sub_name, section_code, section_name, sc.teacher_id, 
                                    CONCAT('T. ',last_name, ', ', first_name, ' ', COALESCE (middle_name, ''), ' ',COALESCE(ext_name, '')) AS name, 
                                    grd_level  FROM subjectclass sc
                                        JOIN section s USING (section_code)
                                        JOIN subject USING (sub_code)
                                        LEFT JOIN faculty f ON f.teacher_id = sc.teacher_id
                                        WHERE s.sy_id ='{$_SESSION['sy_id']}';");

        $sub_classes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $teacher_id = $row['teacher_id'];
            $sub_class_code = $row['sub_class_code'];
            $name = $row['name'];
            $action = is_null($teacher_id) ? "<div class='d-flex justify-content-center'><button data-type='assign' data-sub-class-code='$sub_class_code' class='btn btn-sm btn-primary action m-auto'>Assign</button></div>"
                : "<div class='d-flex justify-content-center'>"
                . "<button class='btn-danger btn btn-sm me-1 action' data-type='unassign'  data-sub-class-code='$sub_class_code' title='Unassign Faculty'><i class='bi bi-person-dash-fill'></i></button>"
                . "<button class='btn-dark btn btn-sm action' data-type='change' data-current-id='$teacher_id' data-current='$name' data-sub-class-code='$sub_class_code' title='Change assigned faculty'>Change</button>"
                . "</p></div>";
            $sub_classes[] = [
                'sub_class_code' => $sub_class_code,
                'section_code'   => $row['section_code'],
                'section_name'   => $row['section_name'],
                'sub_name'       => $row['sub_name'],
                'teacher_id'     => $teacher_id,
                'name'           => $name,
                'grd_level'      => $row['grd_level'],
                'action'         => $action
            ];
        }

        if ($is_JSON) {
            echo json_encode($sub_classes);
            return;
        }
        return $sub_classes;
    }

    private function getSectionName($section_id)
    {
        $result = mysqli_query($this->db, "SELECT section_name FROM section WHERE section_code='$section_id'");
        return mysqli_fetch_row($result)[0];
    }

    private function prepareSectionResult($section_dest, $teacher_id)
    {
        echo json_encode([
            "section_code" => $section_dest,
            "section_name" => $this->getSectionName($section_dest),
            "data"         => $this->listSectionOption($teacher_id)
        ]);
    }

    public function changeAdvisory()
    {
        $teacher_id = $_POST['teacher-id'];
        if (isset($_POST["unassign"])) {
            mysqli_query($this->db, "UPDATE section SET teacher_id=NULL WHERE section_code='{$_POST['current-section']}';");

            echo json_encode([
                "section_code" => NULL,
                "section_name" => "No advisory class set",
                "data" => $this->listSectionOption($teacher_id)
            ]);
            return;
        }

        $section_dest = $_POST['section'];

        if (!$section_dest) {
            // faculty will be unassigned to any section
            $this->prepared_query("UPDATE section SET teacher_id=NULL WHERE teacher_id=?;", [$teacher_id], "i");
            return;
        }

        # faculty will be assigned to new section
        if (!isset($_POST['current-adviser'])) {
            // no adviser to the section where the faculty will be transferred
            $this->query("UPDATE section SET teacher_id=NULL WHERE teacher_id='$teacher_id';");
            $this->query("UPDATE section SET teacher_id='$teacher_id' WHERE section_code='$section_dest';");
            $this->prepareSectionResult($section_dest, $teacher_id);
            return;
        }

        # there is an adviser to the section destination
        if (isset($_POST['current-section'])) {
            $current_section = $_POST['current-section'];
            # switch of advisory classes
            $section_adviser = $_POST['current-adviser'];
            $this->query("UPDATE section SET teacher_id='$teacher_id' WHERE section_code='$section_dest';");
            $this->query("UPDATE section SET teacher_id='$section_adviser' WHERE section_code='$current_section';");
            $this->prepareSectionResult($section_dest, $teacher_id);
            return;
        }

        # the current adviser does not have an advisory class
        # this adviser will be replaced as the new adviser of the section destination
        $this->query("UPDATE section SET teacher_id='$teacher_id' WHERE section_code='$section_dest';");
        $this->prepareSectionResult($section_dest, $teacher_id);
    }

    public function assignSubClasses($teacher_id, $return_all = FALSE)
    {
        $sub_class_code_list = $_POST['sub_class_code'];
        foreach ($sub_class_code_list as $sub_class_code) {
            $this->prepared_query("UPDATE subjectclass SET teacher_id=? WHERE sub_class_code=?;", [$teacher_id, $sub_class_code], "ii");
        }

        if ($return_all) {
            $this->listAllSubjectClasses(TRUE);
        } else {
            echo json_encode($this->listSubjectClasses($teacher_id));
        }
    }


    /**
     * @param $code
     * @param $sy_id
     * @param array $current_sub_code
     * @return array
     */
    public function getCurrentSubjectsOfSection($code, $sy_id): array
    {
        $current_sub_code = [];
        $result = $this->query("SELECT DISTINCT(sc.sub_code) FROM `subjectclass` sc JOIN section USING (section_code)  WHERE sc.section_code='$code' AND sy_id = '$sy_id'");
        while ($row = mysqli_fetch_row($result)) {
            $current_sub_code[] = $row[0];
        }
        return $current_sub_code;
    }

    public function unassignSubClasses($return_all = FALSE)
    {
        $this->assignSubClasses(NULL, TRUE);
    }

    public function addStudentInSection()
    {
        $new_section_code = $_POST['section_code'];
        $new_count = [];
        $count_to_be_added = count($_POST['students']);
        foreach ($_POST['students'] as $stud_id => $section) {
            if ($section != '') {
                if (!isset($new_count[$section])) {     # initialize section count if not included in the array
                    $new_count[$section] = 1;
                } else {                                # increment section count by 1
                    $new_count[$section] = $new_count[$section] + 1;
                }
            }
            # update section of student
            $this->query("UPDATE enrollment SET section_code='$new_section_code' WHERE stud_id = '$stud_id';");
        }
        # update current section count 
        $this->query("UPDATE section SET stud_no=(stud_no + $count_to_be_added) WHERE section_code = '$new_section_code';");
        # update counts of sections where students transferred from
        foreach ($new_count as $sect_id => $count) {
            $this->query("UPDATE section SET stud_no=(stud_no - $count) WHERE section_code = '$sect_id';");
        }
    }


    public function editSubjectSection()
    {
        session_start();
        $code = $_POST['section'];
        $sy_id = $_SESSION['sy_id'];
        # retrieve current subject codes offered in the section
        $current_sub_codes = $this->getCurrentSubjectsOfSection($code, $sy_id);
        print_r($current_sub_codes);
        if (isset($_POST['subjects'])) {
            foreach ($_POST['subjects'] as $prog_code => $sub_codes) {
                # delete
                $to_delete = array_diff($current_sub_codes, $sub_codes);
                foreach ($to_delete as $sub_code_delete) {
                    $this->query("DELETE FROM subjectclass WHERE section_code = '$code' AND sub_code = '$sub_code_delete';");
                }
                # add
                $to_add = array_diff($sub_codes, $current_sub_codes);
                foreach ($to_add as $sub_code_add) {
                    $this->query("INSERT INTO subjectclass (section_code, sub_code) VALUES ('$code', '$sub_code_add');");
                }
            }
        } else {
            # delete all subject class
            $this->query("DELETE FROM subjectclass WHERE section_code = '$code';");
        }

        # update subject faculty
        foreach ($_POST['subjectClass'] as $section_code => $subjectData) {
            foreach ($subjectData as $subject_code => $teacher_id) {
                $teacher_id = empty($teacher_id) ? "NULL" : "'$teacher_id'";

                $result = $this->query("SELECT sub_class_code FROM subjectclass WHERE section_code = '$section_code' AND sub_code = '$subject_code';");
                if (mysqli_num_rows($result) != 0) {
                    $sub_class_code = mysqli_fetch_row($result)[0];
                    $this->query("UPDATE subjectclass SET teacher_id = $teacher_id WHERE sub_class_code = '$sub_class_code'");
                }
            }
        }
    }

    public function editStudent()
    {
        session_start();
        $sy_id = $_SESSION['sy_id'];
        $statusMsg = array();
        $allowTypes = array('jpg', 'png', 'jpeg');

        //general info
        $stud_id = trim($_POST['student_id']);
        $lrn = trim($_POST['lrn']) ?: NULL;
        $first_name = trim($_POST['first-name']);
        $middle_name = trim($_POST['middle-name']) ?: NULL;
        $last_name = trim($_POST['last-name']);
        $ext_name = trim($_POST['ext-name']) ?: NULL;
        $sex = trim($_POST['sex']);
        $age = trim($_POST['age']);
        $birthdate = trim($_POST['birthdate']);
        $birth_place = trim($_POST['birth-place']) ?: NULL;
        $indigenous_group = trim($_POST['group']) ?: NULL;
        $mother_tongue = trim($_POST['mother-tongue']);
        $religion = trim($_POST['religion']) ?: NULL;
        $cp_no = trim($_POST['cp-no']) ?: NULL;
        $belong_to_ipcc = trim($_POST['group']) == 'No' ? '0' : '1';

        //address
        $house_no = trim($_POST['house-no']);
        $street = trim($_POST['street']);
        $barangay = trim($_POST['barangay']);
        $city = trim($_POST['city-muni']);
        $province = trim($_POST['province']);
        $zip = trim($_POST['zip-code']);

        //parent
        $f_firstname = trim($_POST['f_firstname']) ?: NULL;
        $m_firstname = trim($_POST['m_firstname']) ?: NULL;
        $parent = array();

        if ($f_firstname != NULL) {
            $parent[trim($_POST['f_sex'])] = array(
                'fname' => trim($_POST['f_firstname']),
                'mname' => trim($_POST['f_middlename']) ?: NULL,
                'lname' => trim($_POST['f_lastname']),
                'extname' => trim($_POST['f_extensionname']) ?: NULL,
                'sex' => trim($_POST['f_sex']),
                'cp_no' => trim($_POST['f_contactnumber']),
                'occupation' => trim($_POST['f_occupation']) ?: NULL
            );
        }

        if ($m_firstname != NULL) {
            $parent[trim($_POST['m_sex'])] = array(
                'fname' => trim($_POST['m_firstname']),
                'mname' => trim($_POST['m_middlename']) ?: NULL,
                'lname' => trim($_POST['m_lastname']),
                'extname' => NULL,
                'sex' => trim($_POST['m_sex']),
                'cp_no' => trim($_POST['m_contactnumber']),
                'occupation' => trim($_POST['m_occupation']) ?: NULL
            );
        }

        $g_firstname = trim($_POST['g_firstname']) ?: NULL;
        $g_lastname = trim($_POST['g_lastname']) ?: NULL;
        $g_middlename = trim($_POST['g_middlename']) ?: NULL;
        $g_cp_no = trim($_POST['g_contactnumber']) ?: NULL;
        $relationship = trim($_POST['relationship']) ?: NULL;

        # images
        if ($_FILES['psaImage']['size'] > 0) {
            $result = $this->query("SELECT psa_birth_cert FROM student WHERE stud_id = '$stud_id';");
            if (mysqli_num_rows($result) > 0) {
                $path =  mysqli_fetch_row($result)[0];
                if (file_exists($path)) {
                    unlink("../$path");
                }
            }
            $psa_img = $_FILES['psaImage'];
            $filename = basename($psa_img['name']);
            $file_type = pathinfo($filename, PATHINFO_EXTENSION);
            $img_name = time() . "_" . uniqid("", true) . ".$file_type";
            $img_content = $psa_img['tmp_name'];
            $fileDestination = "uploads/credential/$sy_id/$img_name";
            move_uploaded_file($img_content, "../" . $fileDestination);
            $this->prepared_query("UPDATE student SET psa_birth_cert = ?  WHERE stud_id = ?;", [$fileDestination, $stud_id], "si");
        }

        if ($_FILES['form137Image']['size'] > 0) {
            $result = $this->query("SELECT form_137 FROM student WHERE stud_id = '$stud_id';");
            if (mysqli_num_rows($result) > 0) {
                $path =  mysqli_fetch_row($result)[0];
                if (file_exists($path)) {
                    unlink("../$path");
                }
            }
            $form_img = $_FILES['form137Image'];
            $filename = basename($form_img['name']);
            $file_type = pathinfo($filename, PATHINFO_EXTENSION);
            $img_name = time() . "_" . uniqid("", true) . ".$file_type";
            $img_content = $form_img['tmp_name'];
            $fileDestination = "uploads/credential/$sy_id/$img_name";
            move_uploaded_file($img_content, "../" . $fileDestination);
            $this->prepared_query("UPDATE student SET form_137 = ?  WHERE stud_id = ?;", [$fileDestination, $stud_id], "si");
        }
        if ($_FILES['image']['size'] > 0) {
            $result = $this->query("SELECT id_picture FROM student WHERE stud_id = '$stud_id';");
            if (mysqli_num_rows($result) > 0) {
                $path =  mysqli_fetch_row($result)[0];
                if (file_exists($path)) {
                    unlink("../$path");
                }
            }
            $profile_img = $_FILES['image'];
            $filename = basename($profile_img['name']);
            $file_type = pathinfo($filename, PATHINFO_EXTENSION);
            $img_name = time() . "_" . uniqid("", true) . ".$file_type";
            $img_content = $profile_img['tmp_name'];
            $fileDestination = "uploads/student/$sy_id/$img_name";
            move_uploaded_file($img_content, "../" . $fileDestination);
            $this->prepared_query("UPDATE student SET id_picture = ?  WHERE stud_id = ?;", [$fileDestination, $stud_id], "si");
        }

        //defining update student 
        $stud_params = [
            $lrn, $first_name, $middle_name, $last_name, $ext_name,
            $sex, $age, $birthdate, $birth_place, $indigenous_group,
            $mother_tongue, $religion, $cp_no, $belong_to_ipcc, $stud_id
        ];
        $stud_types = "issss" . "sdsss" . "ssiii";

        $stud_query = "UPDATE student SET LRN=?, first_name=?, middle_name=?, last_name=?, ext_name=?, sex=?, age=?, birthdate=?, birth_place=?,
        indigenous_group=?,mother_tongue=?,religion=?,cp_no=?,belong_to_IPCC=? WHERE stud_id= ?;";
        $this->prepared_query($stud_query, $stud_params, $stud_types);

        $address_params = [
            $house_no, $street, $barangay, $city, $province, $zip, $stud_id
        ];
        $address_types = "sssssii";

        $address_query = "UPDATE `address` SET home_no=?, street=?, barangay=?, mun_city=?,province=?,zip_code=? WHERE stud_id=?;";
        $this->prepared_query($address_query, $address_params, $address_types);

        foreach ($parent as $parents) {
            $parents_params = [
                $parents['lname'], $parents['mname'], $parents['fname'], $parents['extname'], $parents['sex'], $parents['cp_no'], $parents['occupation'], $stud_id
            ];
            $parents_types = "sssssssi";
            $parents_query = "CALL editStudentParent(?, ?, ?, ?, ?, ?, ?, ?);";
            $this->prepared_query($parents_query, $parents_params, $parents_types);
        }

        $guardian_params = [
            $g_firstname, $g_middlename, $g_lastname, $relationship, $g_cp_no, $stud_id
        ];

        $guardian_types = "sssssi";
        $guardian_query = "CALL editStudentGuardian(?, ?, ?, ?, ?, ?);";
        $this->prepared_query($guardian_query, $guardian_params, $guardian_types);

        //        header("Location: student.php?id=$stud_id");
        echo json_encode($stud_id);
    }

    public function editSubjectGrade()
    {
        $new_data = $_POST['grade'];
        foreach ($new_data as $grade_id => $new_grade) {
            # update grade
            $params = [
                $new_grade['first'],
                $new_grade['second'],
                $new_grade['final'],
                $grade_id
            ];
            $this->prepared_query("UPDATE classgrade SET first_grading = ?, second_grading = ?, final_grade = ?, first_status = 1, second_status = 1 WHERE grade_id = ?;", $params, "iiii");
            # write log
            [$stud_id, $sub_code, $stud_name] = mysqli_fetch_row($this->query("SELECT lrn, sub_code, CONCAT(first_name,' ',COALESCE(middle_name,''),' ', last_name) FROM classgrade JOIN student USING (stud_id) WHERE grade_id = '$grade_id';"));
            $action = "Edited subject grade of student, $stud_name (LRN: $stud_id and Subject code: $sub_code).";
            $this->enterLog($action);

            echo json_encode($grade_id);
        }
    }

    public function editGeneralAverage()
    {
        $new_data = $_POST['gaGrade'];
        foreach ($new_data as $grade_id => $new_data) {
            # update grade
            foreach ($new_data as $semester => $new_grade) {
                $attribute = ($semester == 'first' ? "first_gen_ave" : "second_gen_ave");
                $params = [$new_grade, $grade_id];
                $this->prepared_query("UPDATE gradereport SET $attribute = ? WHERE report_id = ?;", $params, "ii");
                # write log
                [$stud_id, $stud_name] = mysqli_fetch_row($this->query("SELECT lrn, CONCAT(first_name,' ',COALESCE(middle_name,''),' ', last_name) FROM gradereport JOIN student USING (stud_id) WHERE report_id = '$grade_id';"));
                $action = "Edited $semester general average of student, $stud_name (LRN: $stud_id).";
                $this->enterLog($action);
            }

            echo json_encode($grade_id);
        }
    }

    public function listAvailableSection()
    {
        $stud_id = $_GET['id'];
        $stud_data = mysqli_fetch_row($this->prepared_select("SELECT section_code , enrolled_in FROM enrollment WHERE stud_id=?", [$stud_id], "i"));
        $section_code = '';
        $grade_level = '';
        if ($stud_data) {
            $section_code = $stud_data[0];
            $grade_level = $stud_data[1];
        }

        $res = $this->prepared_select("SELECT t.last_name, t.first_name, t.middle_name, s.section_name, s.stud_no, s.section_code 
        from section s left join faculty t ON s.teacher_id = t.teacher_id 
        where stud_no <> stud_no_max AND section_code <> ? AND grd_level = ?", [$section_code, $grade_level], "si");

        $available_sections =  array();
        // while ($list = mysqli_fetch_row($this->prepared_select($retrieve_sec_query, [$data["section_code"], $data["grdlvl"]], "si"))){
        //     $available_sections[$list[0]] = ["section_name" => $list[1], "slot" => 40 - $list[2]];
        // }

        while ($section = mysqli_fetch_assoc($res)) {
            $available_sections[$section['section_code']] = ["code" => $section['section_code'], "name" => $section['section_name'], "slot" => 40 - $section['stud_no'], "adviser" => $section['first_name'] . " " . $section['middle_name'] . " " . $section['last_name']];
        }

        return $available_sections;
    }

    public function getNames($section)
    {

        $list = "<select name='studentNames' class='select2 px-0 form-select form-select-sm' required>
        <option>Select student</option>";

        $studList =  $this->prepared_select("SELECT stud_id, last_name, middle_name, first_name from student where stud_id in (select stud_id from enrollment where section_code = ?)", [$section], 's');
        while ($stud = mysqli_fetch_assoc($studList)) {
            $code = $stud['stud_id'];
            $name = $stud['first_name'] . " " . $stud['middle_name'] . " " . $stud['last_name'];

            $list .= "<option value='$code'>{$name}</option>";
        }

        $list .= "</select>";
        return $list;
    }


    public function listFullSectionJSON()
    {
        $stud_id = $_GET['id'];

        $stud_data = mysqli_fetch_row($this->prepared_select("SELECT section_code , enrolled_in FROM enrollment WHERE stud_id=?", [$stud_id], "i"));
        if ($stud_data) {
            $data = ["section_code" => $stud_data[0], "grdlvl" => $stud_data[1]];
        }

        $res = $this->prepared_select("SELECT t.last_name, t.first_name, t.middle_name, s.section_name, s.stud_no, s.section_code 
        from section s left join faculty t ON s.teacher_id = t.teacher_id 
        where stud_no = stud_no_max AND section_code <> ?  AND grd_level = ?", [$data['section_code'], $data['grdlvl']], "si");

        $sectionList =  array();

        while ($section = mysqli_fetch_assoc($res)) {
            $adviser = $section['first_name'] . " " . $section['middle_name'] . " " . $section['last_name'];
            $code = $section['section_code'];
            $sectionList[] = [
                "current_code" => $stud_data[0],
                "section_code" => $code,
                "section_name" => $section['section_name'],
                "adviser_name" => $adviser,
                "student" =>  $this->getNames($code),
                "action" => "<button id='' class='swapStudent d-inline w-auto  btn btn-success btn-sm'>Transfer</button>"
            ];
        }
        echo json_encode($sectionList);
    }

    public function transferStudent()
    {
        $stud_id = $_POST['stud_id'];
        $section = $_POST['section_id'];
        $oldSection = $_POST['current_section'];

        mysqli_query($this->db, "UPDATE enrollment SET section_code = '{$section}' WHERE stud_id = {$stud_id};");
        mysqli_query($this->db, "UPDATE section SET stud_no = stud_no - 1 WHERE section_code = '{$oldSection}';");
        mysqli_query($this->db, "UPDATE section SET stud_no = stud_no + 1 WHERE section_code = '{$section}';");
    }

    public function transferStudentFull()
    {
        $stud_id = $_POST['id'];
        $stud_to_swap = $_POST['stud_to_swap'];
        $curr_section = $_POST['current_code'];
        $section = $_POST['section'];

        echo ($stud_id . ' ' . $stud_to_swap . " " . $curr_section . " " . $section);
        $this->prepared_select("UPDATE enrollment SET section_code = (CASE WHEN stud_id = ? then ? WHEN stud_id = ? then ? END) WHERE stud_id in (?,?);", [$stud_id, $section, $stud_to_swap, $curr_section, $stud_id, $stud_to_swap], "isisii");
        header("Refresh:5");
    }

    public function forgotPassword()
    {
        echo ("from administration: forgotpassowrd");
        $email = $_POST['email'];

        $res = $this->query("SELECT email FROM user");
        while ($userEmails = mysqli_fetch_assoc($res)) {
            var_dump($userEmails);
            if (in_array($email, $userEmails)) {
                require_once "../PHPMailer/PHPMailer.php";
                require_once "../PHPMailer/SMTP.php";
                require_once "../PHPMailer/Exception.php";

                $token = bin2hex(random_bytes(50)); //generate random token 
                $query = "INSERT INTO resetpassword (email, token) VALUES (?, ?);";
                $this->prepared_query($query, [$email, $token], "ss");

                $mail = new PHPMailer();

                //server settings
                $mail->isSMTP();
                $mail->Mailer = 'smtp';                                    //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //gmail smtp
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'gemispcnhs@gmail.com';                     //SMTP username
                $mail->Password   = 'GridleyCG21';                               //SMTP password
                $mail->SMTPSecure = 'ssl';
                $mail->Port       = 465;

                //Recipient
                $mail->setFrom('gemispcnhs@gmail.com', 'GEMIS PCNHS - Incognito');
                $mail->addAddress($email);      //Add a recipient

                //Content
                $mail->isHTML(true);
                $mail->Header = 'From: GEMIS PCNHS - Incognito Team';
                $mail->Subject = 'Reset Your Password';
                // $mail->Body    = 'Hello, please click on this <a href="newPassword.php?token=' . $token . '>link</a> to reset your password.';
                $mail->Body    = "Hello, please click on this <a href='http://localhost:3000/passwordReset/newPassword.php?token=$token'>link</a> to reset your password.";

                if ($mail->send()) {
                    $status = "success";
                    $response = "Email sent successfully.";
                } else {
                    $status = "failed";
                    $response = "Email not sent. Mailer Error: {$mail->ErrorInfo}";
                }

                header('location: ../passwordReset/forgotPassword.php');
            } else {
            }
        }
    }

    public function newPassword()
    {
        $newPass = $_POST['newPass'];
        $newPassConf = $_POST['newPassConf'];

        $token = $_POST['token'];

        if ($newPass == $newPassConf) {
            echo ("tamasdfasd");
            $email = mysqli_fetch_assoc($this->prepared_select("SELECT email FROM resetpassword WHERE token=?", [$token], "s"));
            var_dump($email['email']);
            echo ("----------");
            $mail = $email['email'];

            if ($email['email']) {
                $newPass = $newPass;
                echo ($newPass);
                $this->prepared_query("UPDATE user SET password=? WHERE email=?;", [$newPass, $mail], "ss");
                header('location: ../login.php');
            }
        }
    }

    /** SIGNATORY METHODS */
    public function addSignatory()
    {
        $this->prepared_query(
            "INSERT INTO signatory (first_name, middle_name, last_name, acad_degree, year_started, year_ended, position) VALUES (?, ?, ?, ?, ?, ?, ?);",
            [$_POST['first-name'], $_POST['middle-name'], $_POST['last-name'], trim($_POST['academic-degree']) ?: NULL, $_POST['start-year'], $_POST['end-year'], $_POST['position']],
            "ssssiis"
        );
    }
    public function listSignatory($is_JSON = false)
    {
        //        $result = $this->query("SELECT * FROM signatory;");
        $result = $this->query("SELECT  sign_id, first_name, middle_name, last_name, acad_degree, "
            . "CONCAT(year_started, ' - ', year_ended) AS years, year_started, year_ended, position FROM signatory;");
        $signatory = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $signatory[] = new Signatory(
                $row['sign_id'],
                $row['first_name'],
                $row['middle_name'],
                $row['last_name'],
                $row['acad_degree'],
                $row['years'],
                $row['year_started'],
                $row['year_ended'],
                $row['position']
            );
        }
        if (!$is_JSON) {
            return $signatory;
        }
        echo json_encode($signatory);
    }

    public function editSignatory()
    {
        $firstname = $_POST['first-name'];
        $middlename = $_POST['middle-name'] ?: NULL;
        $lastname = $_POST['last-name'];
        $acaddegree = $_POST['academic-degree'] ?: NULL;
        $position = $_POST['position'];
        $started = $_POST['start-year'];
        $ended = $_POST['end-year'];
        $id = $_POST['sig-id'];

        $this->prepared_query("UPDATE signatory SET first_name=?, middle_name=?, last_name=?, acad_degree=?, year_started=?, year_ended=?,position=? WHERE sign_id=?;", [$firstname, $middlename, $lastname, $acaddegree, $started, $ended, $position, $id], "ssssiisi");
    }

    public function deleteSignatory()
    {
        $this->prepared_query("DELETE FROM signatory WHERE sign_id=?;", [$_POST['id']], "i");
    }

    public function toggleAccountStatus($activate)
    {
        $id = $_POST['id'];
        $user_type = $_POST['user_type'];
        $value = ($activate == TRUE ? 1 : 0);
        switch ($user_type) {
            case 'FA':
                $sub_query = "SELECT teacher_user_no from faculty where teacher_id = ?";
                break;
            case 'ST':
                $sub_query = "SELECT id_no FROM student WHERE stud_id = ?";
                break;
        }

        foreach ($id as $id_no) {
            $this->prepared_query("UPDATE user SET is_active = $value WHERE id_no = ANY($sub_query);", [$id_no], "i");
        }
    }

    public function getStudentAttendanceJSON()
    {
        session_start();
        $id = $_GET['id'];
        $sy = $_SESSION['sy_id'];

        $result = $this->query("SELECT attendance_id, month, no_of_absent, no_of_tardy, no_of_present FROM attendance JOIN academicdays using (acad_days_id) 
        WHERE report_id = (SELECT report_id FROM gradereport WHERE stud_id = '$id' AND sy_id = '$sy')");

        while ($row = mysqli_fetch_assoc($result)) {
            $attend_id = $row['attendance_id'];
            $attendance[] = [
                'month' => $row['month'],
                'present' => "<input name='data[{$attend_id}][present]' class='form-control form-control-sm text-center mb-0 number' readonly value='{$row['no_of_present']}'>",
                'absent'  => "<input name='data[{$attend_id}][absent]' class='form-control form-control-sm text-center mb-0 number' readonly value='{$row['no_of_absent']}'>",
                'tardy'   => "<input name='data[{$attend_id}][tardy]' class='form-control form-control-sm text-center mb-0 number' readonly value='{$row['no_of_tardy']}'>",
                'action'    => "<div class='d-flex justify-content-center'>
                                   <button class='btn btn-sm btn-secondary edit-spec-btn action' data-type='edit'><i class='bi bi-pencil-square'></i></button>
                                   <div class='edit-spec-options' style='display: none;'>
                                       <button data-type='cancel' class='action btn btn-sm btn-dark me-1 mb-1'>Cancel</a>
                                       <button data-type='save' class='action btn btn-sm btn-success'>Save</button>                                
                                    </div>
                                </div>"
            ];
        }

        echo json_encode($attendance);
    }

    public function importSubjectGradesToCSV()
    {
        // // Load the database configuration file
        // //if(isset($_POST['importSubmit'])){

        //     // Allowed mime types
        //     $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

        //     // Validate whether selected file is a CSV file
        //     if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){

        //         // If the file is uploaded
        //         if(is_uploaded_file($_FILES['file']['tmp_name'])){

        //             // Open uploaded CSV file with read-only mode
        //             $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

        //             // Skip the first line
        //             fgetcsv($csvFile);

        //             // Parse data from CSV file line by line
        //             while(($line = fgetcsv($csvFile)) !== FALSE){
        //                 // Get row data
        //                 $LRN  = $line[0];
        //                 $stud_name = $line[1]; 
        //                 $first_grading  = $line[2];
        //                 $second_grading = $line[3];
        //                 $final_grading = $line[3];

        //                 // Check whether member already exists in the database with the same email
        //                 $prevQuery = "SELECT id FROM members WHERE email = '".$line[1]."'";
        //                 $prevResult = $this->query($prevQuery);

        //                 if($prevResult->num_rows > 0){
        //                     // Update member data in the database
        //                     $this->query("UPDATE members SET name = '".$name."', phone = '".$phone."', status = '".$status."', modified = NOW() WHERE email = '".$email."'");
        //                 }else{
        //                     // Insert member data in the database
        //                     $this->query("INSERT INTO members (name, email, phone, created, modified, status) VALUES ('".$name."', '".$email."', '".$phone."', NOW(), NOW(), '".$status."')");
        //                 }
        //             }

        //             // Close opened CSV file
        //             fclose($csvFile);

        //             $qstring = '?status=succ';
        //         }else{
        //             $qstring = '?status=err';
        //         }
        //     }else{
        //         $qstring = '?status=invalid_file';
        //     }
    }

    public function getStudentAttendance($report_id)
    {
        $status = ['no_of_days', 'no_of_present', 'no_of_absent', 'no_of_tardy'];
        foreach ($status as $stat) {
            $result = $this->query("SELECT no_of_days, no_of_present, no_of_absent, no_of_tardy, month FROM attendance 
                                    JOIN academicdays USING (acad_days_id) WHERE report_id='$report_id';");
            while ($row = mysqli_fetch_assoc($result)) {
                $attendance[$stat][$row['month']] = $row[$stat];
            }
        }

        return $attendance;
    }

    public function getTrackStrand($stud_id)
    {
        $trackStrand = mysqli_fetch_row($this->prepared_select("SELECT p.prog_code, CONCAT(c.curr_name,': ', p.description) FROM enrollment e JOIN curriculum c USING(curr_code) JOIN program p on c.curr_code = p.curr_code where stud_id=?;", [$stud_id], "i"));
        return $trackStrand;
    }

    /** MAINTENANCE METHODS */
    public function listArchivedSY()
    {
        $sy_list = [];
        $result = $this->query("SELECT sy_id, CONCAT(start_year, ' - ', end_year) FROM archived_schoolyear;");
        while ($row = mysqli_fetch_row($result)) {
            $sy_id = $row[0];
            $sy = $row[1];
            $sy_list[] = [
                "id" => $sy_id,
                "sy_year" => $sy,
                "action" => "<button data-sy-id='$sy_id' data-action='unarchivesy' data-sy='$sy' class='btn btn-sm btn-success action' title='Unarchive SY $sy'>Unarchive</button>"
            ];
        }
        echo json_encode($sy_list);
    }
    public function listBackupFiles()
    {
        $backup = "./maintenance/backup";
        $list = scandir($backup);
        $backup_list = array_diff($list, array('..', '.'));
        $data = [];
        foreach ($backup_list as $item) {
            $index = strpos($item, '.');
            $name = substr($item, 0, $index);
            $date =  date("F d, Y H:i:s", filemtime($backup . "/" . $name . ".sql"));
            $data[] = [
                "name" =>  $name,
                "date" => $date,
                "action" => "<button onclick='showBackUpName(`$name`)' data-bs-toggle='modal' data-bs-target='#delete-confirmation-modal' class='btn btn-danger btn-sm m-1'>Delete</button>"
                    . "<button  onclick='showBackUpName(`$name`)' data-bs-toggle='modal' data-bs-target='#restore-confirmation-modal' class='btn btn-success btn-sm m-1'>Restore</button>"
            ];
        }

        echo json_encode($data);
    }

    public function resetSystem()
    {
        echo("entered resetSystem");
        $this->truncateTables();
        # create default admin
        $this->createDefaultAdmin();
    }

    public function truncateTables()
    {
        $this->query("SET FOREIGN_KEY_CHECKS=0;");
        # Truncate All Tables
        // $db = 'gemis';
        // $toTruncate = $this->query("SELECT CONCAT('TRUNCATE TABLE ',table_schema,'.',TABLE_NAME,';') FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = {$db};");
        $tables = array();
        $result = $this->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
        foreach ($tables as $table) {
            $result = $this->query("TRUNCATE TABLE $table");
        }
        $this->query("SET FOREIGN_KEY_CHECKS=1;");
        // $this->query("TRUNCATE TABLE academicdays;");
        // $this->query("TRUNCATE TABLE academicexcellence;");
        // $this->query("TRUNCATE TABLE address;");
        // $this->query("TRUNCATE TABLE administrator;");
        // $this->query("TRUNCATE TABLE archived_academicdays;");
        // $this->query("TRUNCATE TABLE archived_attendance;");
        // $this->query("TRUNCATE TABLE archived_classgrade;");
        // $this->query("TRUNCATE TABLE archived_enrollment;");
        // $this->query("TRUNCATE TABLE archived_gradereport;");
        // $this->query("TRUNCATE TABLE archived_schoolyear;");
        // $this->query("TRUNCATE TABLE archived_section;");
        // $this->query("TRUNCATE TABLE archived_subjectclass;");
        // $this->query("TRUNCATE TABLE archived_sycurriculum;");
        // $this->query("TRUNCATE TABLE archived_sycurrstrand;");
        // $this->query("TRUNCATE TABLE archived_transferee;");
        // $this->query("TRUNCATE TABLE archived_transfereeschedule;");
        // $this->query("TRUNCATE TABLE archived_transfereesubject;");
        // $this->query("TRUNCATE TABLE attendance;");
        // $this->query("TRUNCATE TABLE award;");
        // $this->query("TRUNCATE TABLE classgrade;");
        // $this->query("TRUNCATE TABLE curriculum;");
        // $this->query("TRUNCATE TABLE enrollment;");
        // $this->query("TRUNCATE TABLE faculty;");
        // $this->query("TRUNCATE TABLE gradereport;");
        // $this->query("TRUNCATE TABLE guardian;");
        // $this->query("TRUNCATE TABLE historylogs;");
        // $this->query("TRUNCATE TABLE historysection;");
        // $this->query("TRUNCATE TABLE historysubjectclass;");
        // $this->query("TRUNCATE TABLE observedvalues;");
        // $this->query("TRUNCATE TABLE parent;");
        // $this->query("TRUNCATE TABLE program;");
        // $this->query("TRUNCATE TABLE requisite;");
        // $this->query("TRUNCATE TABLE resetpassword;");
        // $this->query("TRUNCATE TABLE schoolyear;");
        // $this->query("TRUNCATE TABLE section;");
        // $this->query("TRUNCATE TABLE sharedsubject;");
        // $this->query("TRUNCATE TABLE signatory;");
        // $this->query("TRUNCATE TABLE signedreport;");
        // $this->query("TRUNCATE TABLE student;");
        // $this->query("TRUNCATE TABLE studentaward;");
        // $this->query("TRUNCATE TABLE subject;");
        // $this->query("TRUNCATE TABLE subjectclass;");
        // $this->query("TRUNCATE TABLE subjectfaculty;");
        // $this->query("TRUNCATE TABLE sycurriculum;");
        // $this->query("TRUNCATE TABLE sycurrstrand;");
        // $this->query("TRUNCATE TABLE transferee;");
        // $this->query("TRUNCATE TABLE transfereeschedule;");
        // $this->query("TRUNCATE TABLE transfereesubject;");
        // $this->query("TRUNCATE TABLE user;");
        // $this->query("TRUNCATE TABLE values;");
        // $this->query("SET FOREIGN_KEY_CHECKS=1;");
        echo json_encode("All tables truncated.");
    }

    public function moveData($id, $is_archive = TRUE)
    {
        $archive = "archived_";
        $a_other = "";
        if (!$is_archive) {
            $a_other = $archive;
            $archive = "";
        }
        # School year
        $this->query("INSERT INTO {$archive}schoolyear SELECT * FROM {$a_other}schoolyear WHERE sy_id = $id;");
        # School year curriculum and strand
        $this->query("INSERT INTO {$archive}sycurriculum SELECT * FROM {$a_other}sycurriculum WHERE sy_id = $id;");
        $this->query("INSERT INTO {$archive}sycurrstrand SELECT * FROM {$a_other}sycurrstrand WHERE syc_id IN (SELECT syc_id FROM {$a_other}sycurriculum WHERE sy_id = $id);");
        # Enrollment
        $this->query("INSERT INTO {$archive}enrollment SELECT * FROM {$a_other}enrollment WHERE sy_id = $id;");
        # Grade Report
        $this->query("INSERT INTO {$archive}gradereport SELECT * FROM {$a_other}gradereport WHERE sy_id = $id; ");
        #Class Grade
        $this->query("INSERT INTO {$archive}class_grade SELECT * FROM {$a_other}classgrade WHERE report_id IN (SELECT report_id FROM {$a_other}gradereport WHERE sy_id = $id);");
        # Academic days
        $this->query("INSERT INTO {$archive}academicdays SELECT * FROM {$a_other}academicdays WHERE sy_id = $id;");
        $this->query("INSERT INTO {$archive}attendance SELECT * FROM {$a_other}attendance WHERE acad_days_id IN (SELECT acad_days_id FROM {$a_other}academicdays WHERE sy_id = $id);");
        # Historylogs
        $this->query("INSERT INTO {$archive}historylogs SELECT * FROM {$a_other}historylogs WHERE sy_id = $id;");
        # Section and subjectclass
        $this->query("INSERT INTO {$archive}section SELECT * FROM {$a_other}section WHERE sy_id = $id;");
        $this->query("INSERT INTO {$archive}subjectclass SELECT * FROM {$a_other}subjectclass WHERE section_code IN (SELECT section_code FROM {$a_other}section WHERE sy_id = $id);");
    }

    public function deleteDataFromTables($id, $is_archive = TRUE)
    {
        $archive = ($is_archive ? "" : "archived_");
        # School year
        $this->query("DELETE FROM {$archive}schoolyear WHERE sy_id = $id;");
        # School year curriculum and strand
        $this->query("DELETE FROM {$archive}sycurriculum WHERE sy_id = $id;");
        $this->query("DELETE FROM {$archive}sycurrstrand WHERE syc_id IN (SELECT syc_id FROM {$archive}sycurriculum WHERE sy_id = $id);");
        # Enrollment
        $this->query("DELETE FROM {$archive}enrollment WHERE sy_id = $id;");
        # Grade report
        $this->query("DELETE FROM {$archive}gradereport WHERE sy_id = $id;");
        #Class Grade
        $this->query("DELETE FROM {$archive}classgrade WHERE report_id IN (SELECT report_id FROM {$archive}gradereport WHERE sy_id = $id);");
        #Academicdays
        $this->query("DELETE FROM {$archive}academicdays WHERE sy_id = $id;");
        $this->query("DELETE FROM {$archive}attendance WHERE acad_days_id IN (SELECT acad_days_id FROM {$archive}academicdays WHERE sy_id = $id;");
        # Historylogs
        $this->query("DELETE FROM {$archive}historylogs WHERE sy_id = $id;");
        # Section
        $this->query("DELETE FROM {$archive}section WHERE sy_id = $id;");
        $this->query("DELETE FROM {$archive}subjectclass WHERE section_code IN (SELECT section_code FROM {$archive}section WHERE sy_id = $id);");
    }

    public function archiveSY($is_archive = TRUE)
    {
        $id = $_GET['id'];
        $this->moveData($id, $is_archive);
        $this->deleteDataFromTables($id, $is_archive);
    }

    public function exportTables($tables = '*')
    {
        session_start();
        if ($tables == '*') {
            $tables = array();
            $result = $this->query("SHOW TABLES");
            while ($row = $result->fetch_row()) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        $return = '';

        foreach ($tables as $table) {
            $result = $this->query("SELECT * FROM `$table`");
            $numColumns = $result->field_count;

            $result2 = $this->query("SHOW CREATE TABLE `$table`");
            $row2 = $result2->fetch_row();

            $return .= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $numColumns; $i++) {
                while ($row = $result->fetch_row()) {
                    $return .= "INSERT INTO `$table` VALUES(";
                    for ($j = 0; $j < $numColumns; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = $row[$j];
                        if (isset($row[$j])) {
                            $return .= '"' . $row[$j] . '"';
                        } else {
                            $return .= '""';
                        }
                        if ($j < ($numColumns - 1)) {
                            $return .= ',';
                        }
                    }
                    $return .= ");\n";
                }
            }

            $return .= "\n\n\n";
        }
        $sy = $_SESSION['school_year'] . uniqid("_backup");
        $handle = fopen("../admin/maintenance/backup/{$sy}.sql", 'w+');
        fwrite($handle, $return);
        fclose($handle);
        echo json_encode("Database Export Successfully!");
    }

    public function performMaintenance()
    {
        $action = $_GET['action'];
        $path = "../admin/maintenance/backup/" . $_GET['name'] . ".sql";

        switch ($action) {
            case "unarchivesy":
                $this->archiveSY(FALSE);
                $action = "unarchive";
                break;
            case "archivesy":
                $this->archiveSY();
                $action = "archive";
                break;
            case "restore":
                $sql = file_get_contents($path);
                mysqli_multi_query($this->db, $sql);
                break;
            case "delete":
                unlink($path);
                break;
        }

        echo json_encode([
            "status" => "success",
            "message" => "Item successfully $action" . "d"
        ]);
    }
}

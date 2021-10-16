<?php
require('config.php');
require('Dataclasses.php');
require('Traits.php');

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
            "SELECT admin_id, "
                . "last_name, first_name, middle_name, ext_name, "
                . "CASE WHEN sex = 'm' THEN 'Male' ELSE 'Female' END AS sex, "
                . "age, cp_no, email, admin_user_no "
                . "FROM administrator WHERE admin_id='$id';"
        );
        $row = mysqli_fetch_assoc($result);
        return new Administrator(
            $row['admin_id'],
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
        //        session_start();
        $result = $this->query("SELECT admin_id, last_name, first_name, middle_name, ext_name, "
            . "CASE WHEN sex = 'm' THEN 'Male' ELSE 'Female' END AS sex, age, cp_no, email FROM administrator WHERE admin_id!='{$_SESSION['id']}';");
        $administrators = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $administrators[] = new Administrator(
                $row['admin_id'],
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
    public function getInitSYData()
    {
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

    function addSubjectClass($sy_id, $sub_code, $sub_type)
    {
        $this->prepared_query("INSERT INTO sysub (sy_id, sub_code) VALUES (?, ?);", [$sy_id, $sub_code], 'is');
        $sub_sy_id = mysqli_insert_id($this->db); // 1
        // echo "Added school year - strand: ". $sub_code ."<br>";


        $query_core = "SELECT sub_sy_id, section_code FROM sysub JOIN section USING (sy_id) 
                        JOIN subject USING (sub_code) 
                        WHERE sub_sy_id='$sub_sy_id' AND for_grd_level = grd_level;";
        $query_spap = "SELECT sysub.sub_sy_id, temp.section_code FROM subject sub
                        JOIN sysub USING (sub_code) 	
                        JOIN sharedsubject sh USING (sub_code)     
                        JOIN sycurriculum syc USING (sy_id)    
                        JOIN sycurrstrand sycs USING (syc_id)    
                        JOIN sectionprog sp USING (sycs_id)    
                        JOIN (SELECT * FROM section WHERE sy_id = '$sy_id') AS temp ON sp.section_code = temp.section_code
                        WHERE syc.sy_id = '$sy_id' AND sysub.sub_sy_id = '$sub_sy_id' 
                        AND sub.for_grd_level = temp.grd_level GROUP BY temp.section_code;";
        # Step 4 - Create subject class
        $result = $this->query($sub_type == "core" ? $query_core : $query_spap);

        while ($row = mysqli_fetch_row($result)) {
            // insert query sa subjectclass // sub_sy_id [0], section [1]
            $this->query("INSERT INTO subjectclass (sub_sy_id, section_code) VALUES ('$row[0]', '$row[1]');");
        }
    }

    /**
     * Initializes school year.
     * 1.   Create school year record.
     * 2.   Initialize curriculum.
     * 3.   Initialize sections.
     * 4.   Initialize subject class.
     * 5.   Initialize academic monthly days.
     * 6.   Create directory for images file system.
     */
    public function initializeSY()
    {
        $start_yr = $_POST['start-year'];
        $end_yr = $_POST['end-year'];
        // $grd_level = $_POST['grade-level'];
        $grd_level = NULL;
        $enrollment = 0;
        $current_quarter = 1;
        $current_semester = 1;

        // $enrollment = isset($_POST['enrollment']) ? 1 : 0; // short hand for isset; here, return null if isset returns false

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
                $new_sy_curr_strand_id = mysqli_insert_id($this->db);
                // echo "Added school year - strand: ". $school_year_curr_id ." - ". $prog ."<br>";

                # Prepare section
                $alphabet = range('A', 'Z');

                # Step 3
                foreach (Administration::GRADE_LEVEL as $grade) {
                    $section_code = $this->addSection($grade, $prog, 50, $alphabet[0], $sy_id, $new_sy_curr_strand_id);
                }
            }
            // echo "----------"."<br>";
        }


        # Step 4
        // insert subjects offered in the sysub
        ## Core subjects
        $subjects = $_POST['subjects']['core'];
        foreach ($subjects as $sub_code) {
            $this->addSubjectClass($sy_id, $sub_code, 'core');
        }

        ## Specialized and Applied subjects
        $subjects = $_POST['subjects']['spap']; // spap (specialized + applied)
        foreach ($subjects as $sub_code) {
            $this->addSubjectClass($sy_id, $sub_code, 'applied');
        }

        # Step 5
        $start_month = $_POST['start-month']; // 9
        $end_month = $_POST['end-month'];
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
            $this->query("INSERT academicdays (month, no_of_days, sy_id) VALUES ('$mr', 20, '$sy_id');");
        }

        # Step 6
        $dir_path = "../uploads/student/$sy_id";
        $cred_dir_path = "../uploads/credential/$sy_id";
        if (!file_exists($dir_path)) {
            mkdir($dir_path);
        }
        if (!file_exists($cred_dir_path)) {
            mkdir($cred_dir_path);
        }


        // echo "School year successfully initialized.";
        echo json_encode($sy_id);
        // header("Location: schoolYear.php");
    }




    public function listGrade()
    {

        $grades = [];
        $stud_id = 110001;
        $grado = [];
        $result = $this->query("SELECT current_semester FROM schoolyear WHERE sy_id = 9"); //insert ung query nung pagretrieve ng sem  // kastoy ba HHSHAHSHA
        $sy_id = 9; //$this->query("SELECT * FROM schoolyear WHERE status = 'current'"); '
        $subject_type = $this->query("SELECT sub_type FROM classgrade JOIN subjectclass USING(sub_class_code) JOIN sysub USING(sub_sy_id) JOIN subject USING(sub_code) WHERE stud_id = $stud_id GROUP BY sub_type"); //insert ung query nung pagretrieve ng subtypes nung subjects na meron si stud  // subtypes lang ba etey?
        while ($sem = mysqli_fetch_assoc($result)) {
            while ($sub_type = mysqli_fetch_assoc($subject_type)) { // e.g. $row = sem 
                for ($x = 1; $x <= $sem['current_semester']; $x++) {
                    $stud_grade = $this->query("SELECT sub_name, first_grading, second_grading, final_grade, sub_type FROM schoolyear JOIN sysub USING (sy_id) JOIN subject USING(sub_code) JOIN subjectclass USING (sub_sy_id) JOIN classgrade USING(sub_class_code) WHERE stud_id = $stud_id AND sy_id = $sy_id AND current_semester = $x"); //sub_name | first_grading | second_grading | final_grading | sub_type

                    // foreach($sub_type as $type){
                    while ($grd = mysqli_fetch_assoc($stud_grade)) {
                        // echo json_encode($sub_type['sub_type']);
                        // echo ("-------------");
                        if ($sub_type['sub_type'] == $grd['sub_type']) {

                            // echo ($grd['sub_type']);
                            $grado[] = [
                                'sub_name'  => $grd['sub_name'],
                                'grade_1'   => $grd['first_grading'],
                                'grade_2'   => $grd['second_grading'],
                                'grade_f'   => $grd['final_grade']
                            ];
                        }
                    }


                    // }
                    $grades[$x][$sub_type['sub_type']] = $grado;
                    $grado = [];
                }

                //ung kukunin lang is ung sub_name and sub_type ni stud
                if (sizeof($grades) != 2) {

                    $stud_grd = $this->query("SELECT sub_name, first_grading, second_grading, final_grade, sub_type FROM schoolyear JOIN sysub USING (sy_id) JOIN subject USING(sub_code) JOIN subjectclass USING (sub_sy_id) JOIN classgrade USING(sub_class_code) WHERE stud_id = $stud_id AND sy_id = $sy_id AND current_semester = 1"); //sub_name | first_grading | second_grading | final_grading | sub_type

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
        $sy_id = 4;
        $result = $this->query("SELECT value_name, bhvr_statement FROM `values`"); // query for behavior_stament tapos ung value name  //note: need nung ticks kasi baka iba mainterpret ng sql na values, hindi jay table


        while ($val = mysqli_fetch_assoc($result)) {


            $qtr = $this->query("SELECT current_quarter FROM schoolyear WHERE sy_id = $sy_id");
            while ($qtrs = mysqli_fetch_assoc($qtr)) {
                for ($x = 1; $x <= $qtrs['current_quarter']; $x++) {
                    $markings = $this->query("SELECT value_name, bhvr_statement, marking FROM `observedvalues` JOIN `values` USING (value_id) WHERE stud_id = $stud_id AND quarter = $x");
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
        $this->query($this->db,  "SELECT sub_code, sub_name, first_grading, second_grading, final_grade 
        FROM classgrade 
        JOIN subjectclass USING (sub_class_code) 
        JOIN sysub USING (sub_sy_id) JOIN subject USING (sub_code) 
        WHERE report_id IN (SELECT report_id FROM gradereport WHERE stud_id=$stud_id) 
        AND sy_id=$sy_id;");




        //
    }



    // UPDATE ---> pacheeeeck - ben
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
        $result = mysqli_query($this->db, "SELECT * FROM schoolyear ORDER BY end_year DESC;");
        $sy_list = [];
        $grd_list = array('11' => '11', '12' => '12');
        $quarter_list = array('1' => 'First', '2' => 'Second', '3' => 'Third', '4' => 'Fourth');
        $semester_list = array('1' => 'First', '2' => 'Second');
        while ($row = mysqli_fetch_assoc($result)) {
            $sy_id = $row['sy_id'];
            $quarter = $row['current_quarter'];
            $semester = $row['current_semester'];
            $enrollment = $row['can_enroll'];

            // grade options
            $grd_opt = "<input class='form-control m-0 border-0 bg-transparent' data-id='$sy_id' data-name='grade-level' type='text' data-key='$grd_level' value='$grd_level' readonly>"
                . "<select data-id='$sy_id' name='grade-level' class='form-select d-none'>";
            foreach ($grd_list as $id => $value) {
                // $grd_opt .= "<option value='$id' ". (($id == $grd_level) ? "selected" : "") .">$value</option>";
                $grd_opt .= "<option value='$id'>$value</option>";
            }
            $grd_opt .= "</select>";

            // quarter options
            $quarter_opt = "<input class='form-control m-0 border-0 bg-transparent' data-id='$sy_id' data-name='quarter' type='text'  data-key='$quarter' value='{$quarter_list[$quarter]}' readonly><select data-id='$sy_id' name='quarter' class='form-select d-none'>";
            foreach ($quarter_list as $id => $value) {
                // $quarter_opt .= "<option value='$id' ". (($id == $quarter) ? "selected" : "") .">$value</option>";
                $quarter_opt .= "<option value='$id'>$value</option>";
            }
            $quarter_opt .= "</select>";

            // semester options
            $sem_opt = "<input class='form-control m-0 border-0 bg-transparent' data-id='$sy_id' data-name='semester' type='text' data-key='$semester' value='{$semester_list[$semester]}' readonly><select data-id='$sy_id' name='semester' class='form-select d-none'>";
            foreach ($semester_list as $id => $value) {
                // $sem_opt .= "<option value='$id' ". (($id == $semester) ? "selected" : "") .">$value</option>";
                $sem_opt .= "<option value='$id'>$value</option>";
            }
            $sem_opt .= "</select>";


            $enroll_opt = ($enrollment ? "On-going" : "Ended");
            $enroll_opt =
                "<div class='form-check form-switch ms-3 my-auto'>"
                . "<input " . ($enrollment ? "checked" : "") . " name='enrollment' data-id='$sy_id' class='form-check-input' type='checkbox' title='Turn " . ($enrollment ? "off" : "on") . " enrollment'>"
                . "<span class='status'>$enroll_opt</span>"
                . "</div>";

            # get academic days
            $acad_months_res = $this->query("SELECT * FROM academicdays WHERE sy_id='$sy_id' ORDER BY acad_days_id; ");
            $acad_months = [];
            while ($acad_month = mysqli_fetch_assoc($acad_months_res)) {
                $acad_months[$acad_month['acad_days_id']] = ['month' => $acad_month['month'], 'days' => $acad_month['no_of_days']];
            }

            $sy_list[] = [
                'id'              => $sy_id,
                's_year'          => $row['start_year'],
                'e_year'          => $row['end_year'],
                'sy_year'         => $row['start_year'] . " - " . $row['end_year'],
                'current_grd_val' => $grd_level,
                'grd_level'       => $grd_opt,
                'current_qtr_val' => $quarter,
                'current_qtr'     => $quarter_opt,
                'current_sem_val' => $semester,
                'current_sem'     => $sem_opt,
                'enrollment_val'  => $enrollment,
                'enrollment'      => $enroll_opt,
                'acad_months'     => $acad_months,
                'action' => "<button data-id='$sy_id' class='btn btn-secondary edit-btn btn-sm m-1'>Edit</button>"
                    . "<div class='edit-options' style='display: none;'>"
                    . "<button data-id='$sy_id' class='cancel-btn btn btn-dark d-inline btn-sm m-1'>Cancel</button>"
                    . "<button data-id='$sy_id' class='save-btn d-inline w-auto  btn btn-success btn-sm'>Save</button>"
                    . "</div>"
                    . "<a role='button' href='schoolYear.php?id=$sy_id' class='btn btn-primary btn-sm m-1' target='_blank'>View</a>"
                    . "<button data-id='$sy_id' class='btn btn-secondary btn-sm edit-month-btn'>Edit Acad Days</button>"
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
            // 'grd_level' => $row['grd_level'],
            'current_qtr' => $row['current_quarter'],
            'current_sem' => $row['current_semester'],
            'enrollment' => $row['can_enroll']
        ];
    }

    public function editSY()
    {
        $sy_id = $_POST['sy_id'];
        // $grd_level = $_POST['grade-level'];
        $quarter = $_POST['quarter'];
        $semester = $_POST['semester'];
        // $this->prepared_query("UPDATE schoolyear SET grd_level=?, current_quarter=?, current_semester=? WHERE sy_id=?", [$grd_level, $quarter, $semester, $sy_id], "iiii");
        $this->prepared_query("UPDATE schoolyear SET current_quarter=?, current_semester=? WHERE sy_id=?", [$quarter, $semester, $sy_id], "iii");
    }

    public function editAcademicDays()
    {
        $sy_id = $_POST['sy-id'];
        $new_month_values = $_POST['month'];
        # delete current months if new months value array is zero
        if (count($new_month_values) === 0) {
            echo ("Delete all");
            // $this->prepared_query("DELETE FROM academicdays WHERE sy_id = '$sy_id';");
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
                $this->prepared_query("UPDATE academicdays SET no_of_days=? WHERE sy_id = ? AND month = ?;", [$days, $sy_id, $m], "iis");
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
        # subject
        $sub_res = $this->query("SELECT sub_code, sub_name, sub_type FROM subject JOIN sysub USING (sub_code) WHERE sy_id = '$sy_id' GROUP BY sub_code;");
        while ($sub_row = mysqli_fetch_assoc($sub_res)) {
            $sub_type = $sub_row['sub_type'];
            $key = ($sub_type === "core") ? $sub_type : 'spap';
            $sub_code = $sub_row['sub_code'];
            $sy_info['subject'][$key][$sub_code]['name'] = $sub_row['sub_name'];
            if ($key == 'spap') {
                $sub_prog = $this->query("SELECT prog_code FROM sharedsubject WHERE sub_code='$sub_code';");
                while ($row_sub_prog = mysqli_fetch_row($sub_prog)) {
                    $sy_info['subject'][$key][$sub_code]['prog'][] = $row_sub_prog[0];
                }
            }
        }
        # month
        $mon_res = $this->query("SELECT * FROM academicdays WHERE sy_id='$sy_id';");
        $days = [];
        while ($mon_row = mysqli_fetch_assoc($mon_res)) {
            $sy_info['month'][$mon_row['month']] = $days[] = $mon_row['no_of_days'];
        }
        $sy_info['total_days'] = array_sum($days);
        return $sy_info;
    }
    /** School Year Methods End */
    /** Section Methods */
    public function listSection()
    {
        session_start();
        $query = "SELECT * FROM section" . ((isset($_GET['current']) && $_GET['current'] === 'true')
            ? "WHERE sy_id='{$_SESSION['sy_id']}'"
            : "") . ";";
        $result = mysqli_query($this->db, $query);
        $sectionList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $sectionList[] = new Section($row['section_code'], $row['sy_id'], $row['section_name'], $row['grd_level'], $row['stud_no_max'], $row['stud_no'], $row['teacher_id']);
        }
        return $sectionList;
    }


    public function listSectionJSON()
    {
        echo json_encode($this->listSection());
    }

    //     public function addSection() 
    //     {
    //         session_start();
    //         $code = $_POST['code'];
    // //        $program = $_POST['program'];
    //         $grade_level = $_POST['grade-level'];
    //         $max_no = $_POST['max-no'] ?: Administration::MAX_SECTION_COUNT;
    //         $section_name = $_POST['section-name'];
    //         $adviser = $_POST['adviser'] ?: NULL;
    //         $sy_id = $_SESSION['sy_id'];

    //         $this->prepared_query(
    //             "INSERT INTO section (section_code, section_name, grd_level, stud_no_max, teacher_id, sy_id) VALUES (?, ?, ?, ?, ?, ?) ;",
    //             [$code, $section_name, $grade_level, $max_no, $adviser, $sy_id],
    //             "ssiiii"
    //         );
    //     }

    public function editSection()
    {
        $max_no = $_POST['max-no'];
        $adviser = $_POST['adviser'] ?: NULL;
        $section = $_POST['section'];

        $this->prepared_query(
            "UPDATE section SET stud_no_max=?, teacher_id=? WHERE section_code=?;",
            [$max_no, $adviser, $section],
            "iis"
        );
        echo json_encode(["section" => $section]);
    }

    public function getSection()
    {
        $result = $this->prepared_select("SELECT * FROM section JOIN schoolyear USING(sy_id) WHERE section_code=?", [$_GET["sec_code"]], "s");
        $row = mysqli_fetch_assoc($result);
        $adv_result = mysqli_query($this->db, "SELECT teacher_id, last_name, first_name, middle_name, ext_name FROM faculty where teacher_id='{$row['teacher_id']}'");
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
            // unset($_SESSION['enroll_status']);
            $_SESSION['enroll_status'] = $can_enroll;
            echo $can_enroll;
        }
        $this->prepared_query("UPDATE schoolyear SET can_enroll=? WHERE sy_id=?;", [$can_enroll, $sy_id], "ii");
        // header("Location: enrollment.php");
    }
    /*** Curriculum Methods */

    /** Returns the list of curriculum. */
    public function listCurriculum($tbl)
    {
        $result = mysqli_query($this->db, "SELECT * FROM $tbl;");
        $curriculumList = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $curriculumList[] = new Curriculum($row['curr_code'], $row['curr_name'], $row['curr_desc']);
        }
        return $curriculumList;
    }

    public function listCurriculumJSON()
    {
        echo json_encode([
            'data'        => $this->listCurriculum('curriculum'),
            'archived'    => $this->listCurriculum('archived_curriculum')
        ]);
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
        // start of validation
        $result = $this->query("SELECT * FROM curriculum WHERE curr_code = '$code';");
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                die('Curriculum already exists');
            } else {
                // curriculum is valid
                $this->prepared_query("INSERT INTO curriculum (curr_code, curr_name, curr_desc) VALUES (?, ?, ?)", [$code, $name, $desc]);
                $this->listCurriculumJSON();
            }
        } else {
            die('Error: ' . mysqli_error($this->db));
        }
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
        //        header("Location: curriculum.php?code=$code");
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

    // public function listArchivedCurr()
    // {
    //     $query = "SELECT * FROM archived_curriculum";
    //     $result = mysqli_query($this->db, $query);
    //     $archivedCurrList = array();

    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $curriculum = new Curriculum($row['curr_code'], $row['curr_name']);
    //         $curriculum->add_cur_desc($row['curr_desc']);
    //         $archivedCurrList[] = $curriculum;
    //     }
    //     return $archivedCurrList;
    // }

    // public function listArchivedCurrJSON()
    // {
    //     echo json_encode($this->listArchivedCurr());
    // }

    /*** Program Methods */
    // public function listPrograms()
    public function listPrograms($tbl)
    {
        $query = isset($_GET['code']) ? "SELECT * FROM {$tbl} WHERE curr_code='{$_GET['code']}';" : "SELECT * FROM {$tbl};";
        $result = mysqli_query($this->db, $query);
        $programList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $programList[] = new Program($row['prog_code'], $row['curr_code'], $row['description']);
        }
        return $programList;
    }

    public function listProgramsJSON()
    {
        echo json_encode([
            'data' => $this->listPrograms('program'),
            'archived' => $this->listPrograms('archived_program')
        ]);
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
        $code = $_POST['code'];
        $currCode = $_POST['curr-code'];
        $description = $_POST['desc'];
        // start of validation

        // end of validation
        $this->prepared_query("INSERT INTO program (prog_code, description, curr_code) VALUES (?, ?, ?);", [$code, $description, $currCode]);
        $this->listProgramsJSON();
    }

    public function deleteProgram()
    {
        $this->prepared_query("DELETE FROM program WHERE prog_code=?;", [$_POST['code']]);
        $this->listProgramsJSON();
    }

    public function updateProgram()
    {
        $code = $_POST['code'];
        $prog_description = $_POST['name'];
        $old_code = $_POST['current_code'];

        $this->prepared_query("UPDATE program SET prog_code=?, description=? WHERE prog_code=?;", [$code, $prog_description, $old_code]);
        //        $this->listProgramsJSON();

        //        header("Location: program.php?prog_code=$code");
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
        $query = "SELECT * FROM {$tbl}";
        $result = mysqli_query($this->db, $query);
        $subjectList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $subject =  new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $row['sub_type']);
            $subjectList[] = $subject;
        }
        echo json_encode($subjectList);
    }

    private function setParentPrograms($code, $sub_type, $subject)
    {
        $result = mysqli_query($this->db, "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';");
        $programs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $programs[] = $row['prog_code'];
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
                $subject =  new Subject($code, $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);

                if ($sub_type !== 'core') {
                    $subject = $this->setParentPrograms($code, $sub_type, $subject);
                }

                $subjectList[] = $subject;
            }
        }
        echo json_encode($subjectList);
    }

    public function getSubject()
    {
        $code = $_GET['sub_code'];
        $result = $this->prepared_select("SELECT * FROM subject WHERE sub_code=?", [$code]);
        // $queryOne = "SELECT * FROM subject WHERE sub_code='$code'";
        // $result = mysqli_query($this->db, $queryOne);
        $row = mysqli_fetch_assoc($result);
        $sub_type = $row['sub_type'];
        $subject = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);

        if ($sub_type != 'core') {
            $subject = $this->setParentPrograms($code, $sub_type, $subject);
        }

        $resultTwo = mysqli_query($this->db, "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='PRE';");
        $prereq = [];
        if ($resultTwo) {
            while ($rowTwo = mysqli_fetch_assoc($resultTwo)) {
                $prereq[] = $rowTwo['req_sub_code'];
            }
        }

        $resultThree = mysqli_query($this->db, "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='CO';");
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

    /** Adds a new subject */
    public function addSubject()
    {
        $code = $_POST['code'];
        $subName = $_POST['name'];
        $type = $_POST["sub-type"];
        $grdLvl = $_POST['grade-level'];
        $sem = $_POST['semester'];

        // start of validation

        // end of validation

        $this->prepared_query(
            "INSERT INTO subject (sub_code, sub_name, for_grd_level, sub_semester, sub_type) VALUES (?, ?, ?, ?, ?);",
            [$code, $subName, $grdLvl, $sem, $type],
            "ssiis"
        );

        // insert program and subject info in sharedsubject table
        if ($type == 'specialized') {
            $program_code = $_POST['prog_code'];
            $this->prepared_query("INSERT INTO sharedsubject (sub_code, prog_code) VALUES (?, ?);", [$code, $program_code]);
        }

        if ($type == 'applied') {
            $prog_code_list = $_POST['prog_code'];
            foreach ($prog_code_list as $prog_code) {
                $this->prepared_query("INSERT INTO sharedsubject (sub_code, prog_code) VALUES (?, ?);", [$code, $prog_code]);
            }
        }

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

        $redirect = (($type === 'specialized') ? "prog_code=$program_code&" : "") . "sub_code=$code&state=view";
        echo json_encode((object) ["redirect" => $redirect, "status" => 'Subject successfully added!']);
    }

    public function getUpdateRequisiteQry($code, $requisite)
    {
        $query = '';
        if (isset($_POST["$requisite"])) {
            $req_code_list = $_POST["$requisite"];

            // get current requisite subjects
            $queryThree = "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='$requisite';";
            $result = mysqli_query($this->db, $queryThree);
            $current_subjects = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $current_subjects[] = $row['req_sub_code'];
            }

            // delete req subject not found in the submitted requisite code list
            $sub_codes_to_delete = array_diff($current_subjects, $req_code_list);
            if (count($sub_codes_to_delete) > 0) {
                foreach ($sub_codes_to_delete as $code_to_delete) {
                    $query .= "DELETE FROM requisite WHERE sub_code='$code' AND req_sub_code='$code_to_delete' AND  type='$requisite';";
                }
            }

            // add new req subject found in the submitted requisite code list
            $new_sub_codes = array_diff($req_code_list, $current_subjects);       // codes not found in the current req sub will be added as new row in the db
            if (count($new_sub_codes) > 0) {
                foreach ($new_sub_codes as $new_code) {
                    $query .= "INSERT INTO requisite VALUES ('$code', '$requisite', '$new_code');";
                }
            }
        } else {
            $query .= "DELETE FROM requisite WHERE sub_code='$code' AND  type='$requisite';";
        }

        return $query;
    }

    public function updateSubject()
    {
        $code = $_POST['code'];
        $subName = $_POST['name'];
        $type = $_POST["sub-type"];
        $grdLvl = $_POST['grade-level'];
        $sem = $_POST['semester'];

        // start of validation

        // end of validation

        $query = "UPDATE subject SET sub_code='$code', sub_name='$subName', for_grd_level='$grdLvl', sub_semester='$sem', sub_type='$type' WHERE sub_code='$code';";

        $program_info = $this->getUpdateProgramQuery($code, $type);
        $query .= ($type === 'specialized') ? $program_info[1] : $program_info;
        $query .= $this->getUpdateRequisiteQry($code, 'PRE');
        $query .= $this->getUpdateRequisiteQry($code, 'CO');
        mysqli_multi_query($this->db, $query);
        $redirect = (($type === 'specialized') ? "prog_code={$program_info[0]}&" : "") . "sub_code=$code&state=view&success=updated";
        echo json_encode((object) ["redirect" => $redirect, "status" => 'Subject successfully updated!']);
    }

    private function getUpdateProgramQuery($code, $type)
    {
        $query = '';
        // insert program and subject info in sharedsubject table
        if ($type === 'applied') {
            $prog_code_list = $_POST['prog_code'];

            // get current program/s from db
            $queryTwo = "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';";
            $result = mysqli_query($this->db, $queryTwo);
            $current_programs = [];
            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                $current_programs[] = $row[0];
            }

            // delete rows with program codes not found in the submitted program code list
            $prog_codes_to_delete = array_diff($current_programs, $prog_code_list); // compares the two arrays, and returns an array of elements not found in array 2
            if (count($prog_codes_to_delete) > 0) {
                foreach ($prog_codes_to_delete as $code_to_delete) {
                    $query .= "DELETE FROM sharedsubject WHERE prog_code='$code_to_delete' AND sub_code='$code';";
                }
            }

            // add new row with new program codes found in the submitted program code list
            $new_prog_codes = array_diff($prog_code_list, $current_programs);       // codes not found in the current programs will be added as new row in the db
            if (count($new_prog_codes) > 0) {
                foreach ($new_prog_codes as $new_code) {
                    $query .= "INSERT INTO sharedsubject VALUES ('$code', '$new_code');";
                }
            }
            return $query;
        }

        if ($type === 'specialized') {
            $prog_code = $_POST['code'][0];

            // get current program/s from db
            $queryTwo = "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';";
            $result = mysqli_query($this->db, $queryTwo);
            $current_program = [];

            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                $current_program[] = $row[0];
            }

            if (count($current_program) > 1) {
                $query .= "DELETE FROM sharedsubject WHERE sub_code='$code';";
                $query .= "INSERT INTO sharedsubject VALUES('$code', '$prog_code');";
                // print_r($query);
                return $query;
            }

            $current_program = $current_program[0];
            $query .= "UPDATE sharedsubject SET prog_code='$prog_code' WHERE prog_code='{$current_program}' AND sub_code='$code';";
            return [$prog_code, $query];
        }

        // subject type is core at this point
        return "DELETE FROM sharedsubject WHERE sub_code='$code';";
    }



    public function deleteSubject()
    {
        $code = $_POST['code'];
        $query = "DELETE FROM subject WHERE sub_code='$code'";
        mysqli_query($this->db, $query);
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
        $query = "SELECT * FROM subject WHERE for_grd_level = $grd;";
        $result = mysqli_query($this->db, $query);
        $subjects = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $row['sub_type']);
        }
        return $subjects;
    }

    public function listSubjectbyLevelJSON($grd)
    {
        echo json_encode($this->listSubjectsByLevel($grd));
    }

    function listArchSubjectsJSON()
    {
        echo json_encode($this->listSubjects('archived_subject'));
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
        // $query = "";

        mysqli_query($this->db, "INSERT INTO $sub_dest SELECT * FROM $sub_origin WHERE sub_code = '$code';");
        mysqli_query($this->db, "INSERT INTO $shared_dest SELECT * FROM $shared_origin WHERE sub_code = '$code';");
        mysqli_query($this->db, "INSERT INTO $req_dest SELECT * FROM $req_origin where sub_code = '$code';");
        mysqli_query($this->db, "DELETE FROM $sub_origin WHERE sub_code = '$code';");
        mysqli_query($this->db, "DELETE FROM $req_origin WHERE sub_code = '$code';");
        // mysqli_query($this->db, "DELETE FROM $shared_origin WHERE sub_code = '$code';");

        // mysqli_multi_query($this->db, $query);
    }

    public function listFacultyPrivilegeJSON()
    {
        $result = $this->query("SELECT teacher_id, CONCAT(last_name,', ',first_name,' ',middle_name,' ',COALESCE(ext_name, '')) AS name, "
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
        $query = "SELECT * FROM faculty WHERE teacher_user_no IN (SELECT id_no from user WHERE is_active=1);";
        $result = mysqli_query($this->db, $query);
        $facultyList = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $facultyList[] = new Faculty(
                $row['teacher_id'],
                $row['last_name'],
                $row['middle_name'],
                $row['first_name'],
                $row['ext_name'],
                $row['birthdate'],
                $row['age'],
                $row['sex'],
                $row['department'],
                $row['cp_no'],
                $row['email'],
                $row['award_coor'],
                $row['enable_enroll'],
                $row['enable_edit_grd'],
                $row['id_picture']
            );
        }
        return $facultyList;
    }

    public function listNotAdvisers($teacher_id = NULL)
    {
        $result = $this->query("SELECT CONCAT(last_name, ', ', first_name, ' ', middle_name ) as name, teacher_id FROM faculty WHERE teacher_id NOT IN (SELECT DISTINCT (teacher_id)
                    FROM section WHERE teacher_id IS NOT NULL)" . (!is_null($teacher_id) ? " OR teacher_id = '{$teacher_id}';" : ";"));
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
            SELECT COUNT(admin_id) FROM administrator
        ) AS administrators, 
        (    
            SELECT COUNT(teacher_id) FROM faculty
        ) as teachers,
        (
            SELECT COUNT(stud_id) FROM student JOIN enrollment USING (stud_id) WHERE sy_id = {$_SESSION['sy_id']}
        ) as students";
        $result = mysqli_query($this->db, $query);
        $row = mysqli_fetch_row($result);
        return [$row[0], $row[1], $row[2], 0];
    }

    /**
     * Resets the password of the user with the given User ID.
     * @param int $user_id ID of the user.
     */
    public function resetPassword(int $user_id)
    {
        mysqli_query($this->db, "UPDATE user SET user.password=CONCAT(user_type, id_no) WHERE id='$user_id';");
    }

    /**
     * Resets the password of users stored in the given array.
     * @param array $list An array containing multiple User ID.
     */
    public function resetMultiplePassword(array $list)
    {
        foreach ($list as $user_id) {
            $this->resetPassword($user_id);
        }
    }

    /** Faculty Methods */
    /**
     * Implementation of inserting Faculty
     * 1.   Create user default password by concatenating the prefix and the next 
     *      auto increment value (that is maximum of column id_no + 1). ex. FA1234567890
     *      1.1 Move image to directory
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
        $query = "INSERT INTO faculty (last_name, first_name, middle_name, ext_name, birthdate, age, sex,  email, award_coor, enable_enroll, enable_edit_grd, department, cp_no, id_picture, teacher_user_no) "
            . "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";


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
            "UPDATE faculty SET enable_edit_grd=?, enable_enroll=?, award_coor=? WHERE teacher_id=?;",
            $param,
            "iiii"
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
        $editGrades = 0; // Roles are set to 0 by default
        $canEnroll = 0;
        $awardRep = 0;
        if (isset($_POST['access'])) {
            foreach ($_POST['access'] as $accessRole) {
                if ($accessRole == 'editGrades') {
                    $editGrades = 1;
                }
                if ($accessRole == 'canEnroll') {
                    $canEnroll = 1;
                }
                if ($accessRole == 'awardReport') {
                    $awardRep = 1;
                }
            }
        }
        return [$editGrades, $canEnroll, $awardRep];
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
            $result = mysqli_query($this->db, "SELECT sub_code FROM subjectfaculty WHERE teacher_id='$id'");
            $current_subjects = [];
            while ($row =  mysqli_fetch_row($result)) {
                $current_subjects[] = $row[0];
            }

            // Step 2
            $sub_codes_to_delete = array_diff($current_subjects, $subjects); // compares the two arrays, and returns an array of elements not found in array 2
            foreach ($sub_codes_to_delete as $code_to_delete) {
                mysqli_query($this->db, "DELETE FROM subjectfaculty WHERE sub_code='$code_to_delete' AND teacher_id='$id';");
            }

            // Step 3
            $new_sub_codes = array_diff($subjects, $current_subjects);       // codes not found in the current subjects will be added as new row in the db
            foreach ($new_sub_codes as $new_code) {
                mysqli_query($this->db, "INSERT INTO subjectfaculty (sub_code, teacher_id) VALUES ('$new_code', '$id');");
            }
        } else {
            // Delete all subject rows handled by the faculty
            $result = mysqli_query($this->db, "DELETE FROM subjectfaculty 
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

    public function listAdvisoryClasses($is_JSON = FALSE)
    {
        // session_start();
        $id = $_GET['id'];
        $advisorCondition = isset($_GET['currentAdvisory']) ? "AND section_code!={$_GET['currentAdvisory']}" : "";
        $result = $this->query("SELECT se.section_code, se.section_name, se.grd_level, se.stud_no, "
            . "CONCAT(sy.start_year,' - ',sy.end_year) AS school_year, sy.start_year, sy.end_year  "
            . "FROM section AS se JOIN schoolyear AS sy USING (sy_id) WHERE teacher_id={$id} $advisorCondition;");
        $advisory_classes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $advisory_classes[] = [
                "sy"           => $row['school_year'],
                "start_y"      => $row['start_year'],
                "end_y"        => $row['end_year'],
                "section_code" => $row['section_code'],
                "section_name" => $row['section_name'],
                "section_grd"  => $row['grd_level'],
                "stud_no"      => $row['stud_no']
            ];
        }

        if ($is_JSON) {
            echo json_encode($advisory_classes);
            return;
        }
        return $advisory_classes;
    }

    public function listSubClassFacultyOptions()
    {
        if (isset($_POST['exclude'])) {
            $condition = "WHERE teacher-id != '{$_POST['exclude']}'";
        }
        $result = $this->query("SELECT teacher_id, CONCAT('T. ',last_name, ', ', first_name, ' ', 
                                    middle_name, ' ',COALESCE(ext_name, '')) AS name
                                    FROM faculty " . $condition ?? '' . ";");
        $faculty_list = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $faculty_list[] = [
                'teacher_id' => $row['teacher_id'],
                'name'       => $row['name'],
            ];
        }
        return $faculty_list;
    }

    /** Faculty End */

    //    public static function saveImage($id, $file_name, $target_dir, $file_type)
    //    {
    //        session_start();
    //        $school_year = $_SESSION['sy'] = "2021-2020";
    //        $target_dir = $target_dir."/".$file_type."/".$school_year;
    //        if (!file_exists($target_dir)) {
    //            mkdir($target_dir, 0777, true);
    //        }
    //
    //        if (($_FILES[$file_name]['name']!="")){
    //            // Where the file is going to be stored
    //            $file = $_FILES[$file_name]['name'];
    //            $path = pathinfo($file);
    ////            $filename = $path['filename'];
    //            $ext = $path['extension'];
    //            $temp_name = $_FILES[$file_name]['tmp_name'];
    //            $path_filename_ext = $target_dir."/$id-$file_type.$ext"; // ../student_assets/psa/2194014-*.jpg
    //            // Check if file already exists
    //            if (file_exists($path_filename_ext)) {
    //                echo "Sorry, file already exists.";
    //            }else{
    //                move_uploaded_file($temp_name,$path_filename_ext);
    //                echo "Congratulations! File Uploaded Successfully.";
    //            }
    //        }
    //    }


    //    public function editStudent()
    //    {
    //        $id = $_POST['id'];
    //        $params = [
    //            $_POST['lrn'], $_POST[''], $_POST[''], $_POST[''], $_POST[''],
    //            $_POST['birthdate'], $_POST['sex'], $_POST['age'], $_POST[''], $_POST[''],
    //            $_POST[''], $_POST['religion'], $_POST[''], $_POST[''], $_POST['']
    //        ];
    //        $this->prepared_query(
    //            "UPDATE student SET LRN=?, last_name=?, first_name=?, middle_name=?, ext_name=?, "
    //                 ."birthdate=?, sex=?, age=?, birth_place=?, indigenous_group=?, "
    //                 ."mother_tongue=?, religion=?, cp_no=?, psa_birth_cert=?, id_picture=?;",
    //            $params,
    //            "issss" . "siiss" . "sssss"
    //        );
    //        header("Location: student.php?id=$id");
    //    }

    public function deleteUser($type)
    {
        $id = $_POST['id'];

        //        $user_table =  "";
        //        $id_attribute = "";
        //        switch ($type) {
        //            case 'AD':
        //                $user_table = 'administrator';
        //                $id_attribute = 'admin_id';
        //                break;
        //            case 'FA':
        //                $user_table = 'faculty';
        //                $id_attribute = 'teacher_id';
        //                break;
        //            case 'ST':
        //                $user_table = 'student';
        //                $id_attribute = 'stud_id';
        //                break;
        //        }

        $this->query("DELETE FROM user WHERE id_no='$id' AND user_type='$type';");
    }

    public function listStudent()
    {

        $query = "SELECT * from student AS s "
            . "JOIN enrollment AS e ON e.stud_id = s.stud_id "
            . (isset($_GET['section']) ? "WHERE e.section_code='{$_GET['section']}';" : "AND s.id_no IN (SELECT id_no FROM user WHERE is_active=1);");
        $result = mysqli_query($this->db, $query);
        $studentList = array();

        $address = array();
        $parent = array();
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
                $address,
                $row['cp_no'],
                $row['psa_birth_cert'],
                $row['belong_to_IPCC'],
                $row['id_picture'],
                NULL,
                $row['section_code'],
                $parent,
                $parent
            );
        }
        return $studentList;
    }

    public function listStudentJSON()
    {
        echo json_encode($this->listStudent());
    }

    /**
     * Returns Student with the specified student ID.
     * 1.   Get Student Personal Information
     * 2.   Get Student Parent Information
     * 3.   Get Student Guardian Information 
     * 3.   Initialize Student object
     * 
     * @return Student Student object.
     */
    public function getStudent($id)
    {
        // Step 1
        $result = $this->prepared_select("SELECT * FROM student as s
                                        JOIN `address` as a ON a.stud_id = s.stud_id 
                                        WHERE s.stud_id=?;", [$id], "i");
        $personalInfo = mysqli_fetch_assoc($result);



        // Step 2
        $result = $this->prepared_select("SELECT * FROM parent WHERE stud_id=?;", [$id], "i");
        $parent = array();
        while ($parentInfo = mysqli_fetch_assoc($result)) {
            $extname = is_null($parentInfo['ext_name']) ? NULL : $parentInfo['ext_name'];
            $name = $parentInfo['last_name'] . ", " . $parentInfo['first_name'] . " " . $parentInfo['middle_name'] . " " . $extname;
            $parent[$parentInfo['sex']] = array(
                'name' => $name,
                'fname' => $parentInfo['first_name'],
                'mname' => $parentInfo['middle_name'],
                'lname' => $parentInfo['last_name'],
                'extname' => $extname,
                'sex' => $parentInfo['sex'],
                'cp_no' => $parentInfo['cp_no'],
                'occupation' => is_null($parentInfo['occupation']) ? NULL : $parentInfo['occupation']
            );
        };

        // Step 3
        $result = $this->prepared_select("SELECT * FROM guardian WHERE stud_id=?;", [$id], "i");
        $guardian = array();

        while ($guardianInfo = mysqli_fetch_assoc($result)) {
            $name = $guardianInfo['guardian_last_name'] . ", " . $guardianInfo['guardian_first_name'] . " " . $guardianInfo['guardian_middle_name']; // to be added: $parentInfo['ext_name']
            $guardian = [
                'name' => $name,
                'fname' => $guardianInfo['guardian_first_name'],
                'mname' => $guardianInfo['guardian_last_name'],
                'lname' => $guardianInfo['guardian_middle_name'],
                'relationship' => $guardianInfo['relationship'],
                'cp_no' => $guardianInfo['cp_no']
            ]; //is_null($parentInfo['occcupation']) ? NULL : $parentInfo['occcupation']);
        };

        sizeof($parent) != 0 ?: $parent = NULL;
        sizeof($guardian) != 0 ?: $guardian = NULL;

        $result = $this->prepared_select("SELECT s.section_name, e.section_code FROM enrollment e JOIN section s ON s.section_code=e.section_code WHERE stud_id=?;", [$id], "i");
        while ($res = mysqli_fetch_row($result)) {
            $section = $res[0];
            $section_code = $res[1];
        }

        // echo(($personalInfo['home_no'] = ""));
        $home_no = is_null($personalInfo['home_no']) ? "" : $personalInfo['home_no'];
        $street = is_null($personalInfo['street']) ? "" : $personalInfo['street'];
        $barangay = is_null($personalInfo['barangay']) ? "" : $personalInfo['barangay'];
        $mun_city = is_null($personalInfo['mun_city']) ? "" : $personalInfo['mun_city'];
        $province = is_null($personalInfo['mun_city']) ? "" : $personalInfo['mun_city'];
        $zipcode  = is_null($personalInfo['zip_code']) ? "" : $personalInfo['zip_code'];


        $complete_add = "$home_no $street $barangay, $mun_city, $province $zipcode";
        $add = [
            'address' => $complete_add,
            'home_no' => $home_no,
            'street' => $street,
            'barangay' => $barangay,
            'mun_city' => $mun_city,
            'province' => $province,
            'zipcode' => $zipcode
        ];
        return new Student(
            $personalInfo['stud_id'],
            $personalInfo['id_no'],
            is_null($personalInfo['LRN']) ? NULL : $personalInfo['LRN'],
            $personalInfo['first_name'],
            is_null($personalInfo['middle_name']) ? NULL : $personalInfo['middle_name'],
            $personalInfo['last_name'],
            is_null($personalInfo['ext_name']) ? NULL : $personalInfo['ext_name'],
            $personalInfo['sex'],
            $personalInfo['age'],
            $personalInfo['birthdate'],
            is_null($personalInfo['birth_place']) ? NULL : $personalInfo['birth_place'],
            is_null($personalInfo['indigenous_group']) ? NULL : $personalInfo['indigenous_group'],
            is_null($personalInfo['mother_tongue']) ? NULL : $personalInfo['mother_tongue'],
            is_null($personalInfo['religion']) ? NULL : $personalInfo['religion'],
            $add,
            is_null($personalInfo['cp_no']) ? NULL : $personalInfo['cp_no'],
            is_null($personalInfo['psa_birth_cert']) ? NULL :  $personalInfo['psa_birth_cert'],
            is_null($personalInfo['belong_to_IPCC']) ? NULL : $personalInfo['belong_to_IPCC'],
            is_null($personalInfo['id_picture']) ? NULL : $personalInfo['id_picture'],
            $section_code,
            $section,
            $parent,
            $guardian
        );
    }

    /** Enroll Methods */

    public function listEnrolleesJSON()
    {
        //        $result = $this->getEnrollees();
        //        $enrollees = [];
        //        while ($row = mysqli_fetch_assoc($result)) {
        //            $enrollees[] = new Enrollee(
        //                $row['SY'], $row['LRN'], $row['name'],
        //                $row['date_of_enroll'], $row['enrolled_in'],
        //                $row['curr_code'], $row['status'], $row['stud_id']
        //            );
        //        }
        //
        //        echo json_encode($enrollees);
        echo json_encode($this->getEnrollees());
    }

    /** Section Methods */
    public function listAllSubjectClasses($is_JSON = FALSE)
    {
        session_start();
        //        $_SESSION['sy_id'] = 9;
        $result = $this->query("SELECT sub_class_code, sub_name, section_code, section_name, sc.teacher_id, CONCAT('T. ',last_name, ', ', first_name, ' ', middle_name, ' ',COALESCE(ext_name, ''))
                                        AS name, grd_level FROM subjectclass sc
                                        JOIN sysub s ON sc.sub_sy_id = s.sub_sy_id
                                        JOIN subject su ON s.sub_code = su.sub_code
                                        JOIN section USING (section_code)
                                        LEFT JOIN faculty f ON sc.teacher_id = f.teacher_id
                                        WHERE s.sy_id ='{$_SESSION['sy_id']}';");

        $sub_classes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $teacher_id = $row['teacher_id'];
            $sub_class_code = $row['sub_class_code'];
            $name = $row['name'];
            $action = is_null($teacher_id) ? "<div class='d-flex justify-content-center'><button data-type='assign' data-sub-class-code='$sub_class_code' class='btn btn-sm btn-primary action m-auto'>Assign</button></div>"
                : "<div class='d-flex justify-content-center'>"
                . "<button class='btn-danger btn btn-sm me-1 action' data-type='unassign'  data-sub-class-code='$sub_class_code'>Unassign</button>"
                . "<button class='btn-dark btn btn-sm action' data-type='change' data-current-id='$teacher_id' data-current='$name' data-sub-class-code='$sub_class_code'>Change</button>"
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

        // if (!$section_dest) {
        //     // faculty will be unassigned to any section
        //     $this->prepared_query("UPDATE section SET teacher_id=NULL WHERE teacher_id=?;", [$teacher_id], "i");
        //     return;
        // }

        // faculty will be assigned to new section
        if (!isset($_POST['current-adviser'])) {
            // no adviser to the section where the faculty will be transfered
            $query_1 = "UPDATE section SET teacher_id=NULL WHERE teacher_id='$teacher_id';";
            $query_2 = "UPDATE section SET teacher_id='$teacher_id' WHERE section_code='$section_dest';";

            mysqli_query($this->db, $query_1);
            mysqli_query($this->db, $query_2);
            $this->prepareSectionResult($section_dest, $teacher_id);
            return;
        }

        // there is an adviser to the section destination
        $current_section = $_POST['current-section'];
        if (!$current_section) {
            // the current adviser will be replaced as the new adviser of the section destination
            mysqli_query($this->db, "UPDATE section SET teacher_id='$teacher_id' WHERE section_code='$section_dest';");
            $this->prepareSectionResult($section_dest, $teacher_id);
            return;
        }

        // switch of advisory classes
        $section_adviser = $_POST['current-adviser'];
        mysqli_query($this->db, "UPDATE section SET teacher_id='$teacher_id' WHERE section_code='$section_dest';");
        mysqli_query($this->db, "UPDATE section SET teacher_id='$section_adviser' WHERE section_code='$current_section';");
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

    public function unassignSubClasses($return_all = FALSE)
    {
        $this->assignSubClasses(NULL, TRUE);
    }

    public function editStudent()
    {
        $statusMsg = array();
        $allowTypes = array('jpg', 'png', 'jpeg');

        //general info
        $stud_id = trim($_POST['student_id']);
        $lrn = trim($_POST['lrn']) ?: NULL;
        $first_name = trim($_POST['first_name']);
        $middle_name = trim($_POST['middl_name']) ?: NULL;
        $last_name = trim($_POST['last_name']);
        $ext_name = trim($_POST['suffix']) ?: NULL;
        $sex = trim($_POST['sex']);
        $age = trim($_POST['age']);
        $birthdate = trim($_POST['birthdate']);
        $birth_place = trim($_POST['birthplace']) ?: NULL;
        $indigenous_group = trim($_POST['group']) ?: NULL;
        $mother_tongue = trim($_POST['mother_tongue']);
        $religion = trim($_POST['religion']) ?: NULL;
        $cp_no = trim($_POST['contact_no']) ?: NULL;
        $belong_to_ipcc = trim($_POST['belong_group']) == 'No' ? '0' : '1';

        //address
        $house_no = trim($_POST['house_no']);
        $street = trim($_POST['street']);
        $barangay = trim($_POST['barangay']);
        $city = trim($_POST['city']);
        $province = trim($_POST['province']);
        $zip = trim($_POST['zip']);

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
                'sex' => trim($_POST['m_sex']),
                'cp_no' => trim($_POST['m_contactnumber']),
                'occupation' => trim($_POST['m_occupation']) ?: NULL
            );
        }

        $g_firstname = trim($_POST['g_firstname']) ?: NULL;
        if ($g_firstname != NULL) {
            $g_lastname = trim($_POST['g_lastname']);
            $g_middlename = trim($_POST['g_middlename']) ?: NULL;
            $g_cp_no = trim($_POST['g_contactnumber']);
            $relationship = trim($_POST['relationship']);
        }

        //profile image
        $profile_img = NULL;
        $fileSize = $_FILES['image']['size'];
        // print_r($_FILES);
        if ($fileSize > 0) {
            if ($fileSize > 5242880) { //  file is greater than 5MB
                $statusMsg["imageSize"] = "Sorry, image size should not be greater than 3 MB";
            }
            $filename = basename($_FILES['image']['name']);
            $fileType = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                $profile_img = file_get_contents($_FILES['image']['tmp_name']);
            } else {
                $statusMsg["imageExt"] = "Sorry, only JPG, JPEG, & PNG files are allowed to upload.";
                http_response_code(400);
                die(json_encode($statusMsg));
            }
        }

        //psa
        $psa_img = NULL;
        $fileSize = $_FILES['psaImage']['size'];
        if ($fileSize > 0) {
            if ($fileSize > 5242880) { //  file is greater than 5MB
                $statusMsg["imageSize"] = "Sorry, image size should not be greater than 3 MB";
            }
            $filename = basename($_FILES['psaImage']['name']);
            $fileType = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                $psa_img = file_get_contents($_FILES['psaImage']['tmp_name']);
            } else {
                $statusMsg["imageExt"] = "Sorry, only JPG, JPEG, & PNG files are allowed to upload.";
                http_response_code(400);
                die(json_encode($statusMsg));
            }
        }

        //defining update student 
        $stud_params = [
            $lrn, $first_name, $middle_name, $last_name, $ext_name, $sex, $age, $birthdate, $birth_place,
            $indigenous_group, $mother_tongue, $religion, $cp_no, $belong_to_ipcc
        ];
        $stud_types = "isssssdsssssii";

        #Add picture parameter if images are not null
        $imgQuery = $psaQuery = "";
        if ($profile_img !== NULL) {
            $imgQuery = ", id_picture=?";
            $stud_params[] = $profile_img;
            $stud_types .= "s";
        }

        if ($psa_img !== NULL) {
            $psaQuery = ", psa_birth_cert=?";
            $stud_params[] = $psa_img;
            $stud_types .= "s";
        }

        $stud_params[] = $id = $_POST['student_id'];
        $stud_types .= "i";

        $stud_query = "UPDATE student SET LRN=?, first_name=?, middle_name=?, last_name=?, ext_name=?, sex=?, age=?, birthdate=?, birth_place=?,
        indigenous_group=?,mother_tongue=?,religion=?,cp_no=?,belong_to_IPCC=? $imgQuery $psaQuery WHERE stud_id= ?";
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
        header("Location: student.php?id=$stud_id");
    }

    public function listAvailableSection()
    {
        $stud_id = $_GET['id'];
        $stud_data = mysqli_fetch_row($this->prepared_select("SELECT section_code , enrolled_in FROM enrollment WHERE stud_id=?", [$stud_id], "i"));
        if ($stud_data) {
            $data = ["section_code" => $stud_data[0], "grdlvl" => $stud_data[1]];
        }

        $res = $this->prepared_select("SELECT t.last_name, t.first_name, t.middle_name, s.section_name, s.stud_no, s.section_code 
        from section s left join faculty t ON s.teacher_id = t.teacher_id 
        where stud_no <> stud_no_max AND section_code <> ? AND grd_level = ?", [$data['section_code'], $data['grdlvl']], "si");

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

        //validation ng passwords kung nagmatch
        // $token = $_SESSION['token'];
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
        $id = $_POST['id'];
        $this->prepared_query("DELETE FROM signatory WHERE sign_id=?;", [$_POST['id']], "i");
    }

    public function deactivate()
    {
        $id = $_POST['user_id'];
        $user_type = $_POST['user_type'];
        switch ($user_type) {
            // case 'AD':
            //     $this->prepared_query("UPDATE user SET is_active = 0 WHERE id_no IN (SELECT admin_user_no from administrator where admin_id = ?);", [$id], "i");
            //     header('location: admin.php');
            //     break;
            case 'FA':
                $this->prepared_query("UPDATE user SET is_active = 0 WHERE id_no IN (SELECT teacher_user_no from faculty where teacher_id = ?);", [$id], "i");
                header('location: faculty.php');
                break;
            case 'ST':
                $this->prepared_query("UPDATE user SET is_active = 0 WHERE id_no IN (SELECT id_no from student where stud_id = ?);", [$id], "i");
                header('location: student.php');
                break;
        }
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

    // Redirect to the listing page
    // header("Location: index.php".$qstring);

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

    public function getTrackStrand()
    {
        $stud_id = 110001;
        $trackStrand = mysqli_fetch_row($this->prepared_select("SELECT CONCAT(c.curr_name,' ', e.prog_code) FROM enrollment e JOIN curriculum c USING(curr_code) where stud_id=?;", [$stud_id], "i"));
        return $trackStrand;
    }

    // public function addMonth{
    //     $this->prepared_query("INSERT INTO sysub (sy_id, sub_code) VALUES (?, ?);", [$sy_id, $sub_code], 'is');
    //     $sub_sy_id = mysqli_insert_id($this->db);
    //     INSERT tablename (sy_id, month, days) VALUES ($sy_id, $month, 20);
    // }
}

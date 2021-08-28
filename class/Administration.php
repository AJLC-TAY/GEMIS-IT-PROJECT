<?php
// session_start();
require('config.php');
require('Dataclasses.php');

class Administration extends Dbconfig
{
    protected $hostName;
    protected $userName;
    protected $password;
    protected $dbName;

    private $dbConnect = false;
    public function __construct()
    {
        if (!$this->dbConnect) {
            $database = new dbConfig();
            $conn = $database->connect();
            if ($conn->connect_error) {
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            } else {
                $this->dbConnect = $conn;
                mysqli_set_charset($this->dbConnect, 'utf8');
            }
        }
    }

    public function prepared_query($sql, $params, $types = "")
    {
        $types = $types ?: str_repeat("s", count($params));
        $stmt = mysqli_prepare($this->dbConnect, $sql);
        if (!$stmt) {
            die('mysqli error: ' . mysqli_error($this->dbConnect));
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        if (!mysqli_stmt_execute($stmt)) {
            die('stmt error: ' . mysqli_stmt_error($stmt));
        };

        return $stmt;
    }

    public function prepared_select($sql, $params, $types = "")
    {
        return mysqli_stmt_get_result($this->prepared_query($sql, $params, $types));
    }


    /*** School Year Methods */

    public function initializeSY()
    {
        $curr_code = $_POST['curr-code'];
        $start_yr= $_POST['start-year'];
        $end_yr = $_POST['end-year'];
        $grd_level = $_POST['grade-level'];
        $enrollment = isset($_POST['enrollment']) ? 1 : 0; // short hand for isset; here, return null if isset returns false

        $query = "INSERT INTO schoolyear (start_year, end_year, grd_level, current_quarter, current_semester, can_enroll) "
                ."VALUES (?, ?, ?, ?, ?, ?);";
        $this->prepared_query($query, [$start_yr, $end_yr, $grd_level, 1, 1, $enrollment], "iiiiii");
        echo "School year successfully initialized.";
        header("Location: schoolyear.php");
    }

    public function listSYJSON()
    {
        $result = mysqli_query($this->dbConnect, "SELECT * FROM schoolyear ORDER BY end_year DESC;");
        $sy_list = [];
        $grd_list = array('11' => '11', '12' => '12');
        $quarter_list = array('1' => 'First', '2' => 'Second', '3' => 'Third', '4' => 'Fourth');
        $semester_list = array('1' => 'First', '2' => 'Second');
        while ($row = mysqli_fetch_assoc($result)) {
            $sy_id = $row['sy_id'];
            $quarter = $row['current_quarter'];
            $semester = $row['current_semester'];
            $enrollment = $row['can_enroll'];
            $grd_level = $row['grd_level'];

            // grade options
            $grd_opt = "<input class='form-control m-0 border-0 bg-transparent' data-id='$sy_id' data_name='grade-level' type='text' data-key='$grd_level' value='$grd_level' readonly>"
                        ."<select data-id='$sy_id' name='grade-level' class='form-select d-none'>";
            foreach($grd_list as $id => $value) {
                // $grd_opt .= "<option value='$id' ". (($id == $grd_level) ? "selected" : "") .">$value</option>";
                $grd_opt .= "<option value='$id'>$value</option>";
            }
            $grd_opt .= "</select>";

            // quarter options
            $quarter_opt = "<input class='form-control m-0 border-0 bg-transparent' data-id='$sy_id' data_name='quarter' type='text'  data-key='$quarter' value='{$quarter_list[$quarter]}' readonly><select data-id='$sy_id' name='quarter' class='form-select d-none'>";
            foreach($quarter_list as $id => $value) {
                // $quarter_opt .= "<option value='$id' ". (($id == $quarter) ? "selected" : "") .">$value</option>";
                $quarter_opt .= "<option value='$id'>$value</option>";
            }
            $quarter_opt .= "</select>";

            // semester options
            $sem_opt = "<input class='form-control m-0 border-0 bg-transparent' data-id='$sy_id' data_name='semester' type='text' data-key='$semester' value='{$semester_list[$semester]}' readonly><select data-id='$sy_id' name='semester' class='form-select d-none'>";
            foreach($semester_list as $id => $value) {
                // $sem_opt .= "<option value='$id' ". (($id == $semester) ? "selected" : "") .">$value</option>";
                $sem_opt .= "<option value='$id'>$value</option>";
            }
            $sem_opt .= "</select>";


            $enroll_opt = ($enrollment ? "On-going" : "Ended");
            $enroll_opt = 
                        "<div class='form-check form-switch ms-3 my-auto'>"
                            ."<input ". ($enrollment ? "checked" : "") ." name='enrollment' data-id='$sy_id' class='form-check-input' type='checkbox' title='Turn ". ($enrollment ? "off" : "on")." enrollment'>"
                            ."<span class='status'>$enroll_opt</span>"
                        ."</div>";
             
            $sy_list[] = [  'id' => $sy_id, 
                            's_year' => $row['start_year'], 
                            'e_year' => $row['end_year'], 
                            'sy_year' => $row['start_year']." - ".$row['end_year'], 
                            'current_grd_val' => $grd_level, 
                            'grd_level' => $grd_opt, 
                            'current_qtr_val' => $quarter, 
                            'current_qtr' => $quarter_opt, 
                            'current_sem_val' =>  $semester,
                            'current_sem' => $sem_opt,
                            'enrollment_val' => $enrollment, 
                            'enrollment' => $enroll_opt, 
                            'action' => "<button data-id='$sy_id' class='btn btn-secondary edit-btn btn-sm'>Edit</button>"
                                        ."<div class='edit-options d-none'>"
                                            ."<button data-id='$sy_id' class='cancel-btn btn btn-dark d-inline btn-sm me-1'>Cancel</button>"
                                            ."<button data-id='$sy_id' class='save-btn d-inline w-auto  btn btn-success btn-sm'>Save</button>"
                                        ."</div>"];
        }
        echo json_encode($sy_list);
    }

    public function editEnrollStatus()
    {
        $can_enroll = isset($_POST['enrollment']) ? 1 : 0;
        $this->prepared_query("UPDATE schoolyear SET can_enroll=? WHERE sy_id=?;", [$can_enroll, $_POST['sy_id']], "ii");
        echo "test";
    }
    public function get_sy()
    {
        $result = $this->prepared_select("SELECT * FROM schoolyear WHERE sy_id=?", [$_GET['sy_id']], "i");
        $row = mysqli_fetch_assoc($result);
        return [
            'id' => $row['sy_id'],
            's_year' => $row['start_year'],
            'e_year' => $row['end_year'],
            'grd_level' => $row['grd_level'],
            'current_qtr' => $row['current_quarter'],
            'current_sem' => $row['current_semester'],
            'enrollment' => $row['can_enroll']
        ];
    }

    public function editSY()
    {
        $sy_id = $_POST['sy_id'];
        $grd_level = $_POST['grade-level'];
        $quarter = $_POST['quarter'];
        $semester = $_POST['semester'];
        $this->prepared_query("UPDATE schoolyear SET grd_level=?, current_quarter=?, current_semester=? WHERE sy_id=?", [$grd_level, $quarter, $semester, $sy_id], "iiii");
    }

    /** Section Methods */
    public function listSectionJSON()
    {
        $query = 'SELECT * from section';
        $result = mysqli_query($this->dbConnect, $query);
        $sectionList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $sectionList[] = new Section($row['section_code'], $row['section_name'],$row['grd_level'],$row['stud_no_max'],$row['stud_no'],$row['teacher_id']);
        }
        echo json_encode($sectionList);
    }

    public function addSection() 
    {

        $code = $_POST['code'];
        $program = $_POST['program'];
        $grade_level = $_POST['grade-level'];
        $max_no = $_POST['max-no'];
        $section_name = $_POST['section-name'];
        $adviser = $_POST['adviser'] ?: NULL;

        $this->prepared_query("INSERT INTO section (section_code, section_name, grd_level, stud_no_max, teacher_id) VALUES (?, ?, ?, ?, ?, ?); ",
                             [$code, $section_name, $grade_level, $max_no, $adviser],
                            "sssiii");
    }

    public function editSection() 
    {
        $max_no = $_POST['max-no'];
        $adviser = $_POST['adviser'] ?: NULL;
        $section = $_POST['section'];
        
        $this->prepared_query("UPDATE section SET stud_no_max=?, teacher_id=? WHERE section_code=?;",
                             [$max_no, $adviser, $section],
                            "iis");
    }

    public function getSection() 
    {
        $result = $this->prepared_select("SELECT * FROM section WHERE section_code=?", [$_GET["code"]], "s");
        $row = mysqli_fetch_assoc($result);
        $adv_result = mysqli_query($this->dbConnect, "SELECT teacher_id, last_name, first_name, middle_name, ext_name FROM faculty where teacher_id='{$row['teacher_id']}'");
        $adviser = mysqli_fetch_assoc($adv_result);
        if ($adviser) {
            $name = "{$adviser['last_name']}, {$adviser['first_name']} {$adviser['middle_name']} {$adviser['ext_name']}";
            $adviser = ["teacher_id" => $adviser['teacher_id'],
                        "name" => $name];
        }
        return new Section($row['section_code'], $row['section_name'], $row['grd_level'],
                            $row['stud_no_max'], $row['stud_no'], $adviser);
    }
    public function listSectionStudentJSON() 
    {
    }
    /*** Curriculum Methods */

    /** Returns the list of curriculum. */
    public function listCurriculum($tbl)
    {
        $result = mysqli_query($this->dbConnect, "SELECT * FROM $tbl;");
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

        // end of validation
        $this->prepared_query("INSERT INTO curriculum (curr_code, curr_name, curr_desc) VALUES (?, ?, ?)", [$code, $name, $desc]); 
    }

    public function deleteCurriculum()
    {
        $this->prepared_query("DELETE FROM curriculum WHERE curr_code=?;", [$_POST['code']]);
    }

    public function updateCurriculum()
    {
        $code = $_POST['code'];
        $old_code = $_POST['current_code'];
        // parameter order: new code, current code, name, description, current code
        $param = [$code, $old_code, $_POST['name'], $_POST['curriculum-desc'], $old_code];
        $this->prepared_query("UPDATE curriculum SET curr_code=?, curr_name=?, curr_desc=? WHERE curr_code=?;", $param);
        header("Location: curriculum.php?code=$code");
    }

    public function moveCurriculum($dest, $origin, $prog_dest, $prog_origin, $shared_dest, $shared_origin)
    {
        echo ("from moveCurriculum");
        $code = $_POST['code'];
        $query = "INSERT INTO {$dest} SELECT * FROM {$origin} WHERE curr_code = '{$code}';";
        $query .= "INSERT INTO {$prog_dest} SELECT * FROM $prog_origin WHERE curriculum_curr_code = '{$code}';";
        $query .= "INSERT INTO {$shared_dest} SELECT * FROM $shared_origin WHERE prog_code IN (SELECT prog_code FROM $prog_origin WHERE curriculum_curr_code = '{$code}');";
        $query .= "DELETE FROM {$origin} WHERE curr_code = '{$code}'";
        echo ($query);
        #mysqli_query($this->dbConnect, $query);
        mysqli_multi_query($this->dbConnect, $query);
    }

    // public function listArchivedCurr()
    // {
    //     $query = "SELECT * FROM archived_curriculum";
    //     $result = mysqli_query($this->dbConnect, $query);
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
        $result = mysqli_query($this->dbConnect, $query);
        $programList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $programList[] = new Program($row['prog_code'], $row['curr_code'], $row['description']);
        }
        return $programList;
    }

    public function listProgramsJSON()
    {
        echo json_encode(['data' => $this->listPrograms('program'),
                          'archived' => $this->listPrograms('archived_program')]);
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
    }

    public function deleteProgram()
    {
        $this->prepared_query("DELETE FROM program WHERE prog_code=?;", [$_POST['code']]);
    }

    public function updateProgram()
    {
        $code = $_POST['code'];
        $currCode = $_GET['curr-code']; //uneditable yung curr_code here noh?
        $prog_description = $_POST['name'];
        $old_code = $_POST['current_code'];

        //$updateQuery = "UPDATE program SET prog_code = '$code', description = '$description' WHERE prog_code = '$code'";

        // $updateQuery = "UPDATE ".$this->program." 
        // SET prog_code = '".$_POST["code"]."', description = '".$_POST["description"]."'
        // WHERE prog_code ='".$_POST["code"]."'";
        // $isUpdated = mysqli_query($this->dbConnect, $updateQuery);

        //if($_POST['code']) {	}	 
        // $this->prepared_query("UPDATE program SET prog_code=?, description=? WHERE prog_code=?", [$code, $currCode, $prog_description, $old_code]);
        $updateQuery = "UPDATE program SET prog_code='" . $code . "', description='" . $prog_description . "' WHERE prog_code = '" . $old_code . "'";
        mysqli_query($this->dbConnect, $updateQuery);
        header("Location: program.php?prog_code=$code");
    }

    public function moveProgram($dest, $origin, $shared_dest, $shared_origin)
    {
        $code = $_POST['code'];
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        $query .= "INSERT INTO $dest SELECT * FROM $origin where prog_code = '$code';";
        $query .= "INSERT INTO $shared_dest SELECT * FROM $shared_origin WHERE sub_code = '$code';";
        $query .= "DELETE FROM $shared_origin WHERE prog_code = '$code';";
        $query .= "DELETE FROM $origin WHERE prog_code = '$code';";

        echo ($query);
        mysqli_multi_query($this->dbConnect, $query);
    }


    /*** Subject Methods */
    public function listSubjects()
    {
        $subjectList = [];


        $queryOne = (!isset($_GET['prog_code']))
            ? "SELECT * FROM subject;"
            : "SELECT * FROM subject WHERE sub_code 
               IN (SELECT sub_code FROM sharedsubject 
               WHERE prog_code='{$_GET['prog_code']}')
               UNION SELECT * FROM `subject` WHERE sub_type='CORE';";

        $resultOne = mysqli_query($this->dbConnect, $queryOne);

        while ($row = mysqli_fetch_assoc($resultOne)) {
            $code = $row['sub_code'];
            $sub_type = $row['sub_type'];
            $subject =  new Subject($code, $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);
            if ($sub_type == 'specialized') {
                $resultTwo = $this->prepared_select("SELECT prog_code FROM sharedsubject WHERE sub_code=?;", [$code]);
                // $queryTwo = "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';";
                // $resultTwo = mysqli_query($this->dbConnect, $queryTwo);
                $rowTwo = mysqli_fetch_row($resultTwo);
                $subject->set_program($rowTwo[0]);
            }
            $subjectList[] = $subject;
        }
        return $subjectList;
    }

    public function listSubjectsJSON()
    {
        echo json_encode($this->listSubjects());
    }

    public function listAllSub($tbl)
    {
        $query = "SELECT * FROM {$tbl}";
        $result = mysqli_query($this->dbConnect, $query);
        $subjectList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $subject =  new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $row['sub_type']);
            $subjectList[] = $subject;
        }
        echo json_encode($subjectList);
    }

    private function setParentPrograms($code, $sub_type, $subject)
    {
        $result = mysqli_query($this->dbConnect, "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';");
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
        $result = mysqli_query($this->dbConnect, $query);
        $subjects = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row['sub_code'];
        }

        foreach ($subjects as $sub_code) {
            $result = mysqli_query($this->dbConnect, "SELECT * FROM subject WHERE sub_code='$sub_code';");
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
        // $result = mysqli_query($this->dbConnect, $queryOne);
        $row = mysqli_fetch_assoc($result);
        $sub_type = $row['sub_type'];
        $subject = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);

        if ($sub_type != 'core') {
            $subject = $this->setParentPrograms($code, $sub_type, $subject);
        }

        $resultTwo = mysqli_query($this->dbConnect, "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='PRE';");
        $prereq = [];
        if ($resultTwo) {
            while ($rowTwo = mysqli_fetch_assoc($resultTwo)) {
                $prereq[] = $rowTwo['req_sub_code'];
            }
        }

        $resultThree = mysqli_query($this->dbConnect, "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='CO';");
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
            foreach($prog_code_list as $prog_code) {
                $this->prepared_query("INSERT INTO sharedsubject (sub_code, prog_code) VALUES (?, ?);", [$code, $prog_code]);
            }
        }

        if (isset($_POST['PRE'])) {
            foreach($_POST['PRE'] as $req_code) {
                $this->prepared_query("INSERT INTO requisite (sub_code, type, req_sub_code) VALUES (?, 'PRE', ?);", [$code, $req_code]);
            }
        }

        if (isset($_POST['CO'])) {
            foreach($_POST['CO'] as $req_code) {
                $this->prepared_query("INSERT INTO requisite (sub_code, type, req_sub_code) VALUES (?, 'CO', ?);", [$code, $req_code]);
            }
        }

        $redirect = (($type === 'specialized') ? "prog_code=$program_code&" : "") ."sub_code=$code&state=view" ;
        echo json_encode((object) ["redirect" => $redirect, "status" => 'Subject successfully added!']);
    }

    public function getUpdateRequisiteQry($code, $requisite)
    {
        $query = '';
        if (isset($_POST["$requisite"])) {
            $req_code_list = $_POST["$requisite"];

            // get current requisite subjects
            $queryThree = "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='$requisite';";
            $result = mysqli_query($this->dbConnect, $queryThree);
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
        mysqli_multi_query($this->dbConnect, $query);
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
            $result = mysqli_query($this->dbConnect, $queryTwo);
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
            $prog_code = $_POST['prog_code'][0];

            // get current program/s from db
            $queryTwo = "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';";
            $result = mysqli_query($this->dbConnect, $queryTwo);
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
        mysqli_query($this->dbConnect, $query);
    }

    public function searchSubjects()
    {
        if (strlen($_GET['keyword']) > 0) {
            $text = $_GET['keyword'];
            $query = "SELECT * FROM subject WHERE sub_name LIKE \"%$text%\"";
            $result = mysqli_query($this->dbConnect, $query);
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
        $result = mysqli_query($this->dbConnect, $query);
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

    public function moveSubject($sub_dest, $sub_origin, $shared_dest, $shared_origin, $req_dest, $req_origin)
    {
        $code = $_POST['code'];
        $query = "INSERT INTO $sub_dest SELECT * FROM $sub_origin WHERE sub_code = '$code';";
        $query .= "INSERT INTO $shared_dest SELECT * FROM $shared_origin WHERE sub_code = '$code';";
        $query .= "INSERT INTO $req_dest SELECT * FROM $req_origin where sub_code = '$code';";
        $query .= "DELETE FROM $shared_origin WHERE sub_code = '$code';";
        $query .= "DELETE FROM $req_origin WHERE sub_code = '$code';";
        $query .= "DELETE FROM $sub_origin WHERE sub_code = '$code';";
        mysqli_multi_query($this->dbConnect, $query);
    }

    public function listFaculty()
    {
        $query = 'SELECT * FROM faculty;';
        $result = mysqli_query($this->dbConnect, $query);
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

    public function listFacultyJSON()
    {
        echo json_encode($this->listFaculty());
    }

    /** User Profile */
    public function getProfile($type)
    {
        $id = $_GET['id'];

        if ($type === 'FA') {
            return $this->getFaculty($id);
        }

        if ($type === 'S') {
            return $this->getStudent($id);
        }
    }
    public function getUserCounts() 
    {
        $query = "SELECT (
            SELECT COUNT(teacher_id) FROM faculty
        ) as teachers,
        (
            SELECT COUNT(stud_id) FROM student
        ) as students";
        $result = mysqli_query($this->dbConnect, $query);
        $row = mysqli_fetch_row($result);
        return [0, $row[0], $row[1], 0];
    }

    /**
     * Resets the password of the user with the given User ID.
     * @param int $user_id ID of the user.
     */
    public function resetPassword(int $user_id)
    {
        mysqli_query($this->dbConnect, "UPDATE user SET user.password=CONCAT(user_type, id_no) WHERE id='$user_id';");
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

    /**
     * Creates a User record basing from the specified user type.
     * @param   String $type Can either be AD, FA, or ST, short for Admin, Faculty, and Student, respectively.
     * @return  String User ID number.
     */
    public function createUser(String $type)
    {
        $qry = mysqli_query($this->dbConnect, "SELECT CONCAT('FA', (COALESCE(MAX(id_no), 0) + 1)) FROM user;");
        $PASSWORD = mysqli_fetch_row($qry)[0];
        mysqli_query($this->dbConnect, "INSERT INTO user (date_last_modified, user_type, password) VALUES (NOW(), '$type', '$PASSWORD');");
        return mysqli_insert_id($this->dbConnect);  // Return User ID ex. 123456789
    }

    /** Faculty Methods */
    /**
     * Implementation of adding new, or editing existing faculty record.
     */
    public function processFaculty()
    {
        $statusMsg = array();
        $action = $_POST['action'];                 // value = "add" or "edit"
        $allowTypes = array('jpg', 'png', 'jpeg');  // allowed image extensions


        // General information
        $lastname = trim($_POST['lastname']);
        $firstname = trim($_POST['firstname']);
        $middlename = trim($_POST['middlename']);
        $extname = trim($_POST['extensionname']) ?: NULL; // return null if first value is '', otherwise return first value
        $lastname = trim($_POST['lastname']);
        $age = trim($_POST['age']);
        $birthdate = trim($_POST['birthdate']);
        $sex = $_POST['sex'];

        // Contact information
        $cp_no = trim($_POST['cpnumber']) ?: NULL;
        $email = trim($_POST['email']);


        // School information
        $department = trim($_POST['department']) ?: NULL;
        [$editGrades, $canEnroll, $awardRep] = $this->prepareFacultyRolesValue();

        // Profile image
        $imgContent = NULL;
        $fileSize = $_FILES['image']['size'];
        print_r($_FILES);
        if ($fileSize > 0) {
            if ($fileSize > 5242880) { //  file is greater than 5MB
                $statusMsg["imageSize"] = "Sorry, image size should not be greater than 3 MB";
            }
            $filename = basename($_FILES['image']['name']);
            $fileType = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                $imgContent = file_get_contents($_FILES['image']['tmp_name']);
            } else {
                $statusMsg["imageExt"] = "Sorry, only JPG, JPEG, & PNG files are allowed to upload."; 
                http_response_code(400);
                die(json_encode($statusMsg));
                return;
            }
        }

        $params = [
            $lastname, $firstname, $middlename, $extname, $birthdate, $age, $sex, $email, $awardRep,
            $canEnroll, $editGrades, $department, $cp_no, $imgContent
        ];
        $types = "sssssdssiiisss"; // datatypes of the current parameters

        if ($action == 'add') {
            $statusMsg = $this->addFaculty($params, $types);
        }
        if ($action == 'edit') {
            $statusMsg = $this->editFaculty($params, $types);
        }

        echo $statusMsg;
    }

    /**
     * Implementation of inserting Faculty
     * 1.   Create user default password by concatenating the prefix and the next 
     *      auto increment value (that is maximum of column id_no + 1). ex. FA1234567890
     * 2.   Create faculty record
     * 3.   Insert every subject handled if exist
     *  */
    private function addFaculty($params, $types)
    {
        // Step 1
        $user_id = $this->createUser("FA");
        $params[] = $user_id;
        $types .= "i";

        // Step 2
        $query = "INSERT INTO faculty (last_name, first_name, middle_name, ext_name, birthdate, age, sex,  email, award_coor, enable_enroll, enable_edit_grd, department, cp_no, id_picture, teacher_user_no) "
                ."VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $this->prepared_query($query, $params, $types);
        $id = mysqli_insert_id($this->dbConnect);  // store the last inserted primary key (that is the teacher_id)

        // Step 3
        if (isset($_POST['subjects'])) {
            $subjects = $_POST['subjects'];
            foreach ($subjects as $subject) {
                $this->prepared_query("INSERT INTO subjectfaculty (sub_code, teacher_id) VALUES (?, ?);", [$subject, $id], "sd");
            }
        }

        if ($id) {
            header("Location: faculty.php?id=$id");
        } else {
            return "Faculty unsuccessfully added.";
        }
    }

    /**
     * Implementation of updating Faculty
     * 1.   Remove image content from parameters and types if null
     * 2.   Add teacher id to the parameter and types before executing the query
     * 3.   Update every subject handled if exist
     *  */
    private function editFaculty(array $params, String $types)
    {
        // Step 1
        $imgContent = end($params);                      // Image content is the last element of the parameter array
        $imgQuery = ", id_picture=?";
        if (is_null($imgContent)) {                     // If image content is null
            $imgQuery = "";
            array_pop($params);                         // remove image in params
            $types = substr_replace($types, "", -1);    // remove last type
        } 
        // Step 2
        $params[] = $id = $_POST['teacher_id'];
        $types .= "i";
        $query = "UPDATE faculty SET last_name=?, first_name=?, middle_name=?, ext_name=?, birthdate=?, age=?, sex=?, "
            . "email=?, award_coor=?, enable_enroll=?, enable_edit_grd=?, department=?, cp_no=?$imgQuery WHERE teacher_id=?;";
        $this->prepared_query($query, $params, $types);

        // Step 3
        $this->updateFacultySubjects($id);

        header("Location: faculty.php?id=$id");
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
            $result = mysqli_query($this->dbConnect, "SELECT sub_code FROM subjectclass WHERE teacher_id='$id'");
            $current_subjects = [];
            while ($row =  mysqli_fetch_row($result)) {
                $current_subjects[] = $row[0];
            }

            // Step 2
            $sub_codes_to_delete = array_diff($current_subjects, $subjects); // compares the two arrays, and returns an array of elements not found in array 2
            if (count($sub_codes_to_delete) > 0) {
                foreach ($sub_codes_to_delete as $code_to_delete) {
                    mysqli_query($this->dbConnect, "DELETE FROM subjectclass WHERE sub_code='$code_to_delete' AND teacher_id='$id';");
                }
            }

            // Step 3
            $new_sub_codes = array_diff($subjects, $current_subjects);       // codes not found in the current subjects will be added as new row in the db
            if (count($new_sub_codes) > 0) {
                foreach ($new_sub_codes as $new_code) {
                    mysqli_query($this->dbConnect, "INSERT INTO subjectclass (sub_code, teacher_id) VALUES ('$new_code', '$id');");
                }
            }
        } else {
            // Delete all subject rows handled by the faculty
            $result = mysqli_query($this->dbConnect, "DELETE FROM subjectclass 
                                                      WHERE teacher_id='$id' 
                                                      AND (SELECT COUNT(sub_code) WHERE teacher_id='$id');") > 0;
        }
    }

    /**
     * Returns faculty with the specified teacher ID.
     * 1.   Get faculty record
     * 2.   Get records of handled subjects
     * 3.   Initialize faculty object
     * 
     * @return Faculty Faculty object.
     */
    public function getFaculty($id)
    {
        // Step 1
        $result = $this->prepared_select("SELECT * FROM faculty WHERE teacher_id=?;", [$id], "i");
        $row = mysqli_fetch_assoc($result);

        // Step 2
        $result = $this->prepared_select("SELECT * FROM subject WHERE sub_code IN (SELECT sub_code FROM subjectclass WHERE teacher_id=?);", [$id], "i");
        $subjects = array();
        while ($s_row = mysqli_fetch_assoc($result)) {
            $subjects[] = new Subject($s_row['sub_code'], $s_row['sub_name'], $s_row['for_grd_level'], $s_row['sub_semester'], $s_row['sub_type']);
        };

        // Step 3
        return new Faculty(
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
            $row['id_picture'],
            $subjects
        );
    }

    /**
     * Updates the current department of the faculty.
     */
    public function updateFacultyDepartment()
    {
        $param = [$_POST['department'], $_POST['teacher_id']];
        $this->prepared_query(
            "UPDATE faculty SET department=? WHERE teacher_id=?;",
            $param,
            "si"
        );
    }

    /** Faculty End */

    public function listStudent()
    {

        $query = "SELECT * from student AS s
                  JOIN enrollment AS e ON e.stud_id = s.stud_id "
                . (isset($_GET['section']) ? "WHERE e.section_code='{$_GET['section']}';" : ";");
        $result = mysqli_query($this->dbConnect, $query);
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
                                        JOIN `address` as a ON a.student_stud_id = s.stud_id 
                                        WHERE s.stud_id=?;", [$id], "i");
        $personalInfo = mysqli_fetch_assoc($result);

        // Step 2
        $result = $this->prepared_select("SELECT * FROM parent WHERE stud_id=?;", [$id], "i");
        $parent = array();
        while ($parentInfo = mysqli_fetch_assoc($result)) {
            $name = $parentInfo['last_name'] . ", " . $parentInfo['first_name'] . " " . $parentInfo['middle_name'] . " ". $personalInfo['ext_name']; 
            $parent[$parentInfo['sex']] = array(
                'name' => $name,
                'fname' => $parentInfo['first_name'],
                'mname' => $parentInfo['middle_name'],
                'lname' => $parentInfo['last_name'],
                'extname' => $personalInfo['ext_name'],
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

        $section = "to follow";
        $complete_add = $personalInfo['home_no'] . " " . $personalInfo['street'] . ", " . $personalInfo['barangay'] . ", " . $personalInfo['mun_city'] . ", " . $personalInfo['zip_code'] . " " . $personalInfo['province'];
        $add = [
            'address' => $complete_add,
            'home_no' => $personalInfo['home_no'],
            'street' => $personalInfo['street'],
            'barangay' => $personalInfo['barangay'],
            'mun_city' => $personalInfo['mun_city'],
            'province' => $personalInfo['mun_city'],
            'zipcode' => $personalInfo['zip_code']
        ];
        return new Student(
            $personalInfo['stud_id'],
            $personalInfo['id_no'],
            is_null($personalInfo['LRN']) ? NULL : $personalInfo['LRN'],
            $personalInfo['first_name'],
            $personalInfo['middle_name'],
            $personalInfo['last_name'],
            $personalInfo['ext_name'],
            $personalInfo['sex'],
            $personalInfo['age'],
            $personalInfo['birthdate'],
            $personalInfo['birth_place'],
            $personalInfo['indigenous_group'],
            $personalInfo['mother_tongue'],
            $personalInfo['religion'],
            $add,
            $personalInfo['cp_no'],
            $personalInfo['psa_birth_cert'],
            $personalInfo['belong_to_IPCC'],
            $personalInfo['id_picture'],
            $section,
            $parent,
            $guardian
        );
    }

    public function listDepartments()
    {
        $result = mysqli_query($this->dbConnect, "SELECT DISTINCT(department) FROM faculty WHERE department!=NULL;");
        $departments = [];
        while ($row = mysqli_fetch_row($result)) {
            $departments[] = $row[0];
        }

        return $departments;
    }


  

    public function transferStudent(){
        $sec_code = $_POST['code'];
        $id = $_POST['stud_id'];
        echo("from tranferStudent: admin");
        echo ($sec_code);
        echo ($id);
        // $query = "UPDATE enrollment SET section_code='TVLE11' WHERE stud_id = $id";
        // $param = [$code, $old_code, $_POST['name'], $_POST['curriculum-desc'], $old_code];
        // $this->prepared_query("UPDATE curriculum SET curr_code=?, curr_name=?, curr_desc=? WHERE curr_code=?;", $param);
    }
}

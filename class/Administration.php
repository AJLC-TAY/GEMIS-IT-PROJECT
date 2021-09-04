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

    private $db = false;
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

    public function prepared_query($query, $params, $types = "")
    {
        $types = $types ?: str_repeat("s", count($params));
        $stmt = mysqli_prepare($this->db, $query);
        if (!$stmt) {
            die('mysqli error: ' . mysqli_error($this->db));
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        if (!mysqli_stmt_execute($stmt)) {
            die('stmt error: ' . mysqli_stmt_error($stmt));
        };

        return $stmt;
    }

    public function prepared_select($query, $params, $types = "")
    {
        return mysqli_stmt_get_result($this->prepared_query($query, $params, $types));
    }

    public function query($query)
    {
        return mysqli_query($this->db, $query);
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

        // create sections
        $sy_id = mysqli_insert_id($this->db);
        $grade_level = [11, 12];
        $alphabet = range('A', 'Z');

        $result = mysqli_query($this->db, "SELECT COUNT(*) FROM program;");
        $program_count = mysqli_fetch_row($result)[0];

        foreach($grade_level as $grade) {
            for ($i = 0; $i < $program_count; $i++) {
                $section_name =  "$grade-{$alphabet[$i]}-1-Class";
                $section_code = rand(10, 1000000);
                $this->query("INSERT INTO section (section_code, sy_id, section_name, grd_level, stud_no_max) VALUES ($section_code, $sy_id, '$section_name', $grade, 50 );");
            }
        }

        // create subject class

        


        echo "School year successfully initialized.";
        // header("Location: schoolyear.php");
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
    public function listSection() 
    {
        $query = "SELECT * FROM section;";
        $result = mysqli_query($this->db, $query);
        $sectionList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $sectionList[] = new Section($row['section_code'], $row['sy_id'], $row['section_name'],$row['grd_level'],$row['stud_no_max'],$row['stud_no'],$row['teacher_id']);
        }
        return $sectionList;
    }

    public function listSectionOption($teacher_id)
    {
        $query = "SELECT s.section_code, s.section_name, s.grd_level, s.teacher_id, f.last_name, f.first_name, f.middle_name, f.ext_name FROM section AS s "
                ."LEFT JOIN faculty AS f USING (teacher_id) "
                ."WHERE teacher_id != '$teacher_id' "
                ."OR teacher_id IS NULL ORDER BY teacher_id;";
        $result = mysqli_query($this->db, $query);
        $sectionList = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $teacher_id = $row['teacher_id'];
            $name = $teacher_id ? "T. {$row['last_name']}, {$row['first_name']} {$row['middle_name']} {$row['ext_name']}" : "";
            $sectionList[] = ["section_code" => $row['section_code'], 
                              "section_name" => $row['section_name'],
                              "section_grd"  => $row['grd_level'],
                              "adviser_id"   => $teacher_id,
                              "adviser_name" => $name
                            ];
        }
        return $sectionList;
    }

    public function listSectionJSON()
    {
        echo json_encode($this->listSection());
    }

    public function listSectionOptionJSON($teacher_id)
    {
        echo json_encode($this->listSectionOption($teacher_id));
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
        echo json_encode(["section" => $section]);
    }

    public function getSection() 
    {
        $result = $this->prepared_select("SELECT * FROM section WHERE section_code=?", [$_GET["sec_code"]], "s");
        $row = mysqli_fetch_assoc($result);
        $adv_result = mysqli_query($this->db, "SELECT teacher_id, last_name, first_name, middle_name, ext_name FROM faculty where teacher_id='{$row['teacher_id']}'");
        $adviser = mysqli_fetch_assoc($adv_result);
        if ($adviser) {
            $name = "{$adviser['last_name']}, {$adviser['first_name']} {$adviser['middle_name']} {$adviser['ext_name']}";
            $adviser = ["teacher_id" => $adviser['teacher_id'],
                        "name" => $name];
        }
        return new Section($row['section_code'], $row['school_yr'], $row['section_name'], $row['grd_level'],
                            $row['stud_no_max'], $row['stud_no'], $adviser);
    }
    public function listSectionStudentJSON() 
    {
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

        // end of validation
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

        $this->query( "SET FOREIGN_KEY_CHECKS = 0;");
        $this->query( "INSERT INTO $dest SELECT * FROM $origin where prog_code = '$code';");
        $this->query( "INSERT INTO $shared_dest SELECT * FROM $shared_origin WHERE sub_code = '$code';");
        $this->query( "DELETE FROM $origin WHERE prog_code = '$code';");
        $this->query( "DELETE FROM $shared_origin WHERE prog_code = '$code';");
        $this->listProgramsJSON();
    }


    /*** Subject Methods */
    public function listSubjects($tbl)
    {
        $subjectList = [];

        $shared_sub = ($tbl == "archived_subject") ? "archived_sharedsubject" : "sharedsubject";

        $queryOne = (!isset($_GET['prog_code']))
            ? "SELECT * FROM $tbl;"
            : "SELECT * FROM $tbl WHERE sub_code 
               IN (SELECT sub_code FROM $shared_sub 
               WHERE prog_code='{$_GET['prog_code']}')
               UNION SELECT * FROM $shared_sub WHERE sub_type='CORE';";

        $resultOne = mysqli_query($this->db, $queryOne);

        while ($row = mysqli_fetch_assoc($resultOne)) {
            $code = $row['sub_code'];
            $sub_type = $row['sub_type'];
            $subject =  new Subject($code, $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);
            
            if ($sub_type == 'specialized') {
                $resultTwo = mysqli_query($this->db,  "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';");
                $rowTwo = mysqli_fetch_row($resultTwo);
                $subject->set_program($rowTwo[0]);
            }
            $subjectList[] = $subject;
        }
        return $subjectList;
    }

    public function listSubjectsJSON()
    {
        echo json_encode($this->listSubjects('subject'));
    }

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

    function listArchSubjectsJSON () {
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

        // $query = "";
        foreach($_POST['code'] as $code) {
            mysqli_query($this->db, "INSERT INTO $sub_dest SELECT * FROM $sub_origin WHERE sub_code = '$code';");
            mysqli_query($this->db, "INSERT INTO $shared_dest SELECT * FROM $shared_origin WHERE sub_code = '$code';");
            mysqli_query($this->db, "INSERT INTO $req_dest SELECT * FROM $req_origin where sub_code = '$code';");
            mysqli_query($this->db, "DELETE FROM $sub_origin WHERE sub_code = '$code';");
            mysqli_query($this->db, "DELETE FROM $req_origin WHERE sub_code = '$code';");
            // mysqli_query($this->db, "DELETE FROM $shared_origin WHERE sub_code = '$code';");
        }
        // mysqli_multi_query($this->db, $query);
    }

    public function listFaculty()
    {
        $query = 'SELECT * FROM faculty;';
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
        $result = mysqli_query($this->db, $query);
        $row = mysqli_fetch_row($result);
        return [0, $row[0], $row[1], 0];
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

    /**
     * Creates a User record basing from the specified user type.
     * @param   String $type Can either be AD, FA, or ST, short for Admin, Faculty, and Student, respectively.
     * @return  String User ID number.
     */
    public function createUser(String $type)
    {
        $qry = mysqli_query($this->db, "SELECT CONCAT('FA', (COALESCE(MAX(id_no), 0) + 1)) FROM user;");
        $PASSWORD = mysqli_fetch_row($qry)[0];
        mysqli_query($this->db, "INSERT INTO user (date_last_modified, user_type, password) VALUES (NOW(), '$type', '$PASSWORD');");
        return mysqli_insert_id($this->db);  // Return User ID ex. 123456789
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
        $id = mysqli_insert_id($this->db);  // store the last inserted primary key (that is the teacher_id)

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

        echo "test";
        // header("Location: faculty.php?id=$id");
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
            $result = mysqli_query($this->db, "SELECT sub_code FROM subjectfaculty WHERE teacher_id='$id'");
            $current_subjects = [];
            while ($row =  mysqli_fetch_row($result)) {
                $current_subjects[] = $row[0];
            }

            // Step 2
            $sub_codes_to_delete = array_diff($current_subjects, $subjects); // compares the two arrays, and returns an array of elements not found in array 2
            foreach($sub_codes_to_delete as $code_to_delete) {
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
     * Returns faculty with the specified teacher ID.
     * 1.   Get faculty record
     * 2.   Get records of handled subjects
     * 3.   Get records of handled subject class
     * 4.   Initialize faculty object
     * 
     * @return Faculty Faculty object.
     */
    public function getFaculty($id)
    {
        // Step 1
        $result = $this->prepared_select("SELECT * FROM faculty WHERE teacher_id=?;", [$id], "i");
        $row = mysqli_fetch_assoc($result);

        // Step 2
        $result = $this->prepared_select("SELECT * FROM subject WHERE sub_code IN (SELECT sub_code FROM subjectfaculty WHERE teacher_id=?);", [$id], "i");
        $subjects = array();
        while ($s_row = mysqli_fetch_assoc($result)) {
            $subjects[] = new Subject($s_row['sub_code'], $s_row['sub_name'], $s_row['for_grd_level'], $s_row['sub_semester'], $s_row['sub_type']);
        };

        // Step 3
        $teacher_id = $row['teacher_id'];
        $query = "SELECT sc.sub_class_code, sc.section_code, sc.sub_code, sc.teacher_id, s.sub_name, s.sub_type, se.grd_level, s.sub_semester, se.school_yr, se.section_name 
                  FROM subjectclass AS sc JOIN subject AS s USING (sub_code) 
                  JOIN section AS se USING (section_code) WHERE sc.teacher_id='$teacher_id'";
        $result = $this->query($query);
        $handled_sub_classes = array();

        while ($sc_row = mysqli_fetch_assoc($result)) {
            $handled_sub_classes[] = new SubjectClass(
                $sc_row['sub_code'],
                $sc_row['sub_name'],
                $sc_row['grd_level'],
                $sc_row['sub_semester'],
                $sc_row['sub_type'],
                $sc_row['sub_class_code'],
                $sc_row['section_code'],
                $sc_row['section_name'],
                $sc_row['school_yr'],
                $teacher_id
            );
        }
        // Step 4
        $faculty = new Faculty(
            $teacher_id,
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

        $faculty->set_handled_sub_classes($handled_sub_classes);
        return $faculty;
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

    /** Faculty End */

    public function listStudent()
    {

        $query = "SELECT * from student AS s
                  JOIN enrollment AS e ON e.stud_id = s.stud_id "
                . (isset($_GET['section']) ? "WHERE e.section_code='{$_GET['section']}';" : ";");
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
        $result = mysqli_query($this->db, "SELECT DISTINCT(department) FROM faculty WHERE department!=NULL;");
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

    /** Section Methods */
    public function listSubjectClasses($teacher_id = "")
    {
        $condition = $teacher_id ? "WHERE sc.teacher_id !='$teacher_id' OR sc.teacher_id IS NULL" : "";
        $query = "SELECT sc.sub_class_code, sc.section_code, sc.sub_code, sc.teacher_id, s.sub_name, s.sub_type, se.grd_level, s.sub_semester, se.school_yr, se.section_name 
                  FROM subjectclass AS sc JOIN subject AS s USING (sub_code) 
                  JOIN section AS se USING (section_code) $condition ORDER BY sc.teacher_id ";
        $result = $this->query($query);
        $sub_classes = array();

        while ($sc_row = mysqli_fetch_assoc($result)) {
            $sub_classes[] = new SubjectClass(
                $sc_row['sub_code'],
                $sc_row['sub_name'],
                $sc_row['grd_level'],
                $sc_row['sub_semester'],
                $sc_row['sub_type'],
                $sc_row['sub_class_code'],
                $sc_row['section_code'],
                $sc_row['section_name'],
                $sc_row['school_yr'],
                $sc_row['teacher_id']
            );
        }
        return $sub_classes;
    }
    private function getSectionName($section_id) {
        $result = mysqli_query($this->db, "SELECT section_name FROM section WHERE section_code='$section_id'");
        return mysqli_fetch_row($result)[0];
    }

    private function prepareSectionResult($section_dest, $teacher_id)
    {
        echo json_encode(["section_code" => $section_dest,
            "section_name" => $this->getSectionName($section_dest),
            "data"         => $this->listSectionOption($teacher_id)]);
    }

    public function changeAdvisory()
    {
        $teacher_id = $_POST['teacher-id'];
        if (isset($_POST["unassign"])) {
            mysqli_query($this->db, "UPDATE section SET teacher_id=NULL WHERE section_code='{$_POST['current-section']}';");

            echo json_encode(["section_code" => NULL,
                "section_name" => "No advisory class set",
                "data" => $this->listSectionOption($teacher_id)]);
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

    public function getAdvisoryClass() {
        $data = mysqli_fetch_row($this->prepared_select("SELECT section_code, section_name FROM section WHERE teacher_id=?", [$_GET['id']], "i"));
        if ($data) {
            return ["section_code" => $data[0], "section_name" => $data[1]];
        }
        return false;
    }

    public function assignSubClasses($teacher_id) {
        $sub_class_code_list = $_POST['sub_class_code'];
        foreach($sub_class_code_list as $sub_class_code) {
            $this->prepared_query( "UPDATE subjectclass SET teacher_id=? WHERE sub_class_code=?;", [$teacher_id, $sub_class_code], "ii");
        }
        echo json_encode($this->listSubjectClasses($teacher_id));
    }

    public function unassignSubClasses() {
        $this->assignSubClasses(NULL);
    }

    public function editStudent(){


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
        $belong_to_ipcc = trim($_POST['belong_group']) == 'No' ? '0': '1';

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

        if ($f_firstname != NULL){
            $parent[trim($_POST['f_sex'])] = array(
                'fname' => trim($_POST['f_lastname']),
                'mname' => trim($_POST['f_middlename']) ?: NULL,
                'lname' => trim($_POST['f_lastname']),
                'extname' => trim($_POST['f_extensionname']) ?: NULL,
                'sex' => trim($_POST['f_sex']),
                'cp_no' => trim($_POST['f_contactnumber']),
                'occupation' => trim($_POST['f_occupation']) ?: NULL
            );
        }

        if ($m_firstname != NULL){
            $parent[trim($_POST['m_sex'])] = array(
                'fname' => trim($_POST['m_lastname']),
                'mname' => trim($_POST['m_middlename']) ?: NULL,
                'lname' => trim($_POST['m_lastname']),
                'sex' => trim($_POST['m_sex']),
                'cp_no' => trim($_POST['m_contactnumber']),
                'occupation' => trim($_POST['m_occupation']) ?: NULL
            );
        }

        $g_firstname = trim($_POST['g_firstname']) ?: NULL;
        if ($g_firstname != NULL){
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
                return;
            }
        }

        //psa
        $psa_img = NULL;
        $fileSize = $_FILES['image']['size'];

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
                return;
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
        
        $address_params= [
            $house_no,$street,$barangay,$city,$province,$zip,$stud_id
        ];
        $address_types = "sssssii";

        $query = "UPDATE `address` SET home_no=?, street=?, barangay=?, mun_city=?,province=?,zip_code=? WHERE student_stud_id=?;";
        $this->prepared_query($query, $address_params, $address_types);

        // foreach($parent as $parents){
        //     $parents_params= [
        //             $parents['fname'],$parents['mname'],$parents['lname'],$parents['extname'],$parents['sex'],$parents['cp_no'],$parents['fname'], $stud_id
        //         ];
        //     $parents_types = "sssssii";
            
        // }
        

        

        // $parents_params[] = $id = $_POST['student_id'];
        // $parents_types .= "i";

        // $guardian_params[] = $id = $_POST['student_id'];
        // $guardian_types .= "i";

        

         header("Location: student.php?id=$stud_id");
    }

}



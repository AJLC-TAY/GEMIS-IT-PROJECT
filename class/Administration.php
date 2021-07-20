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
            }
        }
    }

    private function getData($sqlQuery)
    {
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        $data = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    /*** Curriculum Methods */

    /** Returns the list of curriculum. */
    public function listCurriculum()
    {
        $query = "SELECT * FROM curriculum";
        $result = mysqli_query($this->dbConnect, $query);
        $curriculumList = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $curriculum = new Curriculum($row['curr_code'], $row['curr_name']);
            $curriculum->add_cur_desc($row['curr_desc']);
            $curriculumList[] = $curriculum;
        }
        return $curriculumList;
    }

    public function listCurriculumJSON()
    {
        echo json_encode($this->listCurriculum());
    }

    /** Get curriculum object from a specified curriculum code */
    public function getCurriculum()
    {
        $code = $_GET['code'];
        $query = "SELECT * FROM curriculum WHERE curr_code='$code'";
        $result = mysqli_query($this->dbConnect, $query);
        $row = mysqli_fetch_assoc($result);
        $curriculum = new Curriculum($row['curr_code'], $row['curr_name']);
        $curriculum->add_cur_desc($row['curr_desc']);
        return $curriculum;
    }

    /** Adds a new curriculum */   
    public function addCurriculum()
    {
        $code = $_POST['code'];
        $name = $_POST['name'];
        $description = $_POST['curriculum-desc'];
        // start of validation

        // end of validation

        $query = "INSERT INTO curriculum VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->dbConnect, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $code, $name, $description);
        mysqli_stmt_execute($stmt);
    }

    public function deleteCurriculum()
    {
        $code = $_POST['code'];
        $query = "DELETE FROM curriculum WHERE curr_code='$code'";
        mysqli_query($this->dbConnect, $query);
    }

    public function updateCurriculum()
    {
        $code = $_POST['code'];
        $old_code = $_POST['current_code'];
        $name = $_POST['name'];
        $description = $_POST['curriculum-desc']; 

        // $updateQuery = "UPDATE curriculum SET curr_code=?, curr_name=?, curr_desc=? WHERE=?";//kasama ata ung where statement para alam kong anong curr and iaupdate?
        // $stmt = mysqli_prepare($this->dbConnect, $updateQuery);
        // mysqli_stmt_bind_param($stmt, 'ssss', $code, $name, $description, $old_code);
        // mysqli_stmt_execute($stmt);
        $updateQuery = "UPDATE curriculum SET curr_code='" . $code . "', curr_name='" . $name . "', curr_desc='" . $description . "' WHERE curr_code = '" . $old_code . "'";
        mysqli_query($this->dbConnect, $updateQuery);
        header("Location: curriculum.php?code=$code");
    }

    /*** Program Methods */
    public function listPrograms()
    {
        $query = "SELECT * FROM program";
        $result = mysqli_query($this->dbConnect, $query);
        $programList = array();

        
        while ($row = mysqli_fetch_assoc($result)) {
            $programList[] = new Program($row['prog_code'], $row['curriculum_curr_code'], $row['description']);
        }
        return $programList;
    }

    public function listProgramsJSON()
    {
        echo json_encode($this->listPrograms());
    }

    public function getProgram()
    {
        $code = $_GET['prog_code'];
        $query = "SELECT * FROM program WHERE prog_code='$code'";
        $result = mysqli_query($this->dbConnect, $query);
        $row = mysqli_fetch_assoc($result);
        $program = new Program($row['prog_code'], $row['curriculum_curr_code'], $row['description']);
        return $program;
    }

    /** Adds a new program */
    public function addProgram()
    {
        $code = $_POST['code'];
        $currCode = $_POST['curr-code'];
        $description = $_POST['desc'];
        // start of validation

        // end of validation

        $query = "INSERT INTO program VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->dbConnect, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $code, $description, $currCode);
        mysqli_stmt_execute($stmt);
    }

    public function deleteProgram()
    {
        $code = $_POST['code'];
        $query = "DELETE FROM program WHERE prog_code='$code'";
        mysqli_query($this->dbConnect, $query);
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
        $updateQuery = "UPDATE program SET prog_code='" . $code . "', description='" . $prog_description . "' WHERE prog_code = '" . $old_code . "'";
        mysqli_query($this->dbConnect, $updateQuery);
        header("Location: program.php?code=$code");
    }

    /*** Subject Methods */
    public function listSubjects()
    {
        $subjectList = [];
     
        $queryOne = "SELECT * FROM subject";
        $resultOne = mysqli_query($this->dbConnect, $queryOne);
        
        while ($row = mysqli_fetch_assoc($resultOne)) {
            $code = $row['sub_code'];
            $sub_type = $row['sub_type'];
            $subject =  new Subject($code, $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);
            if ($sub_type == 'specialized') {
                $queryTwo = "SELECT program_prog_code FROM sharedsubject WHERE subject_sub_code='$code';";
                $resultTwo = mysqli_query($this->dbConnect, $queryTwo);
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

    public function listSubUnderProgJSON() {
        $subjectList = [];
        $query = "SELECT subject_sub_code FROM sharedsubject WHERE program_prog_code='". $_GET['prog_code'] ."';";
        $result = mysqli_query($this->dbConnect, $query);

        $subjects = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row['subject_sub_code'];
        }

        foreach($subjects as $sub) {
            $query = "SELECT * FROM subject WHERE sub_code='". $sub ."';";
            $result = mysqli_query($this->dbConnect, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $code = $row['sub_code'];
                $sub_type = $row['sub_type'];
                $subject =  new Subject($code, $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);
                if ($sub_type == 'specialized' || $sub_type == 'applied') {
                    $queryTwo = "SELECT program_prog_code FROM sharedsubject WHERE subject_sub_code='$code';";
                    $resultFour = mysqli_query($this->dbConnect, $queryTwo);
                    $programs = [];
                    while ($rowFour = mysqli_fetch_assoc($resultFour)) {
                        $programs[] = $rowFour['program_prog_code'];
                    }
                    $subject->set_programs($programs);
                }
                $subjectList[] = $subject;
            }
        }
        echo json_encode($subjectList);
    }

    public function getSubject()
    {
        $code = $_GET['code'];
        $queryOne = "SELECT * FROM subject WHERE sub_code='$code'";
        $result = mysqli_query($this->dbConnect, $queryOne);
        $row = mysqli_fetch_assoc($result);
        $sub_type = $row['sub_type'];
        $subject = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);

        $queryTwo = "SELECT * FROM requisite WHERE sub_code='$code' AND type='pre'";
        $resultTwo = mysqli_query($this->dbConnect, $queryTwo);
        $prereq = [];
        while ($rowTwo = mysqli_fetch_assoc($resultTwo)) {
            $prereq[] = $rowTwo['req_sub_code'];
        }

        $queryThree = "SELECT * FROM requisite WHERE sub_code='$code' AND type='co'";
        $resultThree = mysqli_query($this->dbConnect, $queryThree);
        $coreq = [];
        while ($rowThree = mysqli_fetch_assoc($resultThree)) {
            $coreq[] = $rowThree['req_sub_code'];
        }

        if ($sub_type != 'core') {
            $queryFour = "SELECT * FROM sharedsubject WHERE subject_sub_code='$code'";
            $resultFour = mysqli_query($this->dbConnect, $queryFour);
            $programs = [];
            while ($rowFour = mysqli_fetch_assoc($resultFour)) {
                $programs[] = $rowFour['program_prog_code'];
            }
            $subject->set_programs($programs);
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

        $query = 'INSERT INTO subject (sub_code, sub_name, for_grd_level, sub_semester, sub_type)';
        $query .= 'VALUES ("'. $code .'", "'. $subName . '", "'. $grdLvl .'", "'. $sem. '", "'. $type . '");';

        // insert program and subject info in sharedsubject table
        if ($type == 'applied' || $type == 'specialized') {
            $prog_code_list = $_POST['prog_code'];
            foreach($prog_code_list as $prog_code) {
                $query .= 'INSERT INTO sharedsubject VALUES ("'. $code .'", "'. $prog_code .'");';
            }
        }

        if (isset($_POST['pre'])) {
            foreach($_POST['pre'] as $req_code) {
                $query .= 'INSERT INTO requisite VALUES ("'. $code .'", "pre", "'. $req_code .'");';
            }
        }

        if (isset($_POST['co'])) {
            foreach($_POST['co'] as $req_code) {
                $query .= 'INSERT INTO requisite VALUES ("'. $code .'", "co", "'. $req_code .'");';
            }
        }

        mysqli_multi_query($this->dbConnect, $query);
    }

    public function updateSubject()
    {
        $code = $_POST['code'];// to be changed based sa form nung edit HAHAHHAHA
        $subName = $_GET['name']; 
        $grdLvl = $_POST['gradeLevel'];
        $sem = $_POST['semester'];
        $type = $_POST["sub-type"];
        $prereq = $_POST["Radios"];//?
        $coreq = $_POST["Radios1"]; //?
        $old_code = $_POST['sub_code'];

        //$updateQuery = "UPDATE program SET prog_code = '$code', description = '$description' WHERE prog_code = '$code'";

        // $updateQuery = "UPDATE ".$this->program." 
        // SET prog_code = '".$_POST["code"]."', description = '".$_POST["description"]."'
        // WHERE prog_code ='".$_POST["code"]."'";
        // $isUpdated = mysqli_query($this->dbConnect, $updateQuery);

        //if($_POST['code']) {	}	 
        $updateQuery = "UPDATE subject SET sub_code='" . $code . "',sub_name='" . $subName . "', for_grd_level='" . $grdLvl . "',sub_semester='" . $sem . "',sub_type='" . $type . "',prerequisite='" . $prereq . "', corerequisite='" . $coreq . "' WHERE sub_code = '" . $old_code . "'";
        mysqli_query($this->dbConnect, $updateQuery);
        header("Location: subject.php?code=$code");
    }
    public function deleteSubject()
    {
        $code = $_POST['code'];
        $query = "DELETE FROM subject WHERE sub_code='$code'";
        mysqli_query($this->dbConnect, $query);
    }

    public function searchSubjects() 
    {
        if(strlen($_GET['keyword']) > 0) {
            $text = $_GET['keyword'];
            $query = "SELECT * FROM subject WHERE sub_name LIKE \"%$text%\"";
            $result = mysqli_query($this->dbConnect, $query);
            $subjects = array();

            while($row = mysqli_fetch_assoc($result)) {
                $sub = new stdClass();
                $sub->code = $row['code'];
                $sub->name = $row['name'];
                $subjects[] = $sub;
            }
            echo json_encode($subjects);
        }
    }

        
    /** Returns the list of Grade 11 subjects. */
    public function listSubjectsGrade11()
    {
        $query = "SELECT * FROM subject WHERE for_grd_level = 11";
        $result = mysqli_query($this->dbConnect, $query);
        $subjGrade11List = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $subjectGrade11 = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'],$row['sub_type'],$row['prerequisite'],$row['corequisite']);
            $subjGrade11List[] = $subjectGrade11;
        }
        return $subjGrade11List;
    }

    public function listSubjectsGrade11JSON()
    {
        echo json_encode($this->listSubjectsGrade11());
    }

    /** Returns the list of Grade 12 subjects. */
    public function listSubjectsGrade12()
    {
        $query = "SELECT * FROM subject WHERE for_grd_level = 12";
        $result = mysqli_query($this->dbConnect, $query);
        $subjGrade12List = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $subjectGrade12 = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'],$row['sub_type'],$row['prerequisite'],$row['corequisite']);
            $subjGrade12List[] = $subjectGrade12;
        }
        return $subjGrade12List;
    }

    public function listSubjectsGrade12JSON()
    {
        echo json_encode($this->listSubjectsGrade12());
    }

}

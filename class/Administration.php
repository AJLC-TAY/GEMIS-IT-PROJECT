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
            $this->hostName = $database->serverName;
            $this->userName = $database->userName;
            $this->password = $database->password;
            $this->dbName = $database->dbName;

            $conn = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
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
        $code = $_GET['code'];
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
        $query = "SELECT * FROM subject";
        $result = mysqli_query($this->dbConnect, $query);
        $subjectList = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $subjectList[] = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'],$row['sub_type'],$row['prerequisite'],$row['corequisite']);
        }
        return $subjectList;
    }

    public function listSubjectsJSON()
    {
        echo json_encode($this->listSubjects());
    }

    public function getSubject()
    {
        $code = $_GET['code'];
        $query = "SELECT * FROM subject WHERE sub_code='$code'";
        $result = mysqli_query($this->dbConnect, $query);
        $row = mysqli_fetch_assoc($result);
        $subject = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'],$row['sub_type'],$row['prerequisite'],$row['corequisite']);
        return $subject;
    }

    /** Adds a new program */
    public function addSubject()
    {
        $code = $_POST['code'];
        $subName = $_GET['name']; 
        $grdLvl = $_POST['gradeLevel'];
        $sem = $_POST['semester'];
        $type = $_POST["sub-type"];
        $prereq = $_POST["Radios"];//?
        $coreq = $_POST["Radios1"]; //?

        // start of validation

        // end of validation

        $query = "INSERT INTO program VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->dbConnect, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $code, $subName, $grdLvl, $sem, $type, $prereq, $coreq);
        mysqli_stmt_execute($stmt);
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
        $updateQuery = "UPDATE subject SET sub_code='" . $code . "',sub_name='" . $subName . "', for_grd_lvl='" . $grdLvl . "',sub_semester='" . $sem . "',sub_type='" . $type . "',prerequisite='" . $prereq . "', corerequisite='" . $coreq . "' WHERE sub_code = '" . $old_code . "'";
        mysqli_query($this->dbConnect, $updateQuery);
        header("Location: subject.php?code=$code");
    }
    public function deleteSubject()
    {
        $code = $_POST['code'];
        $query = "DELETE FROM subject WHERE sub_code='$code'";
        mysqli_query($this->dbConnect, $query);
    }
}

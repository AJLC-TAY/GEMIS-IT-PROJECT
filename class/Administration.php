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
    public function listCurriculum($tbl)
    {
        $query = "SELECT * FROM {$tbl}";
        $result = mysqli_query($this->dbConnect, $query);
        $curriculumList = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $curriculum = new Curriculum($row['curr_code'], $row['curr_name']);
            $curriculum->add_cur_desc($row['curr_desc']);
            $curriculumList[] = $curriculum;
        }
        return $curriculumList;
    }

    public function listCurriculumJSON($tbl)
    {
        echo json_encode($this->listCurriculum($tbl));
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
        $updateQuery = "UPDATE curriculum SET curr_code='{$code}', curr_name='{$name}', curr_desc='{$description}' WHERE curr_code = '{$old_code}';";
        mysqli_query($this->dbConnect, $updateQuery);
        header("Location: curriculum.php?code=$code");
    }

    public function transferCurriculum($dest, $origin)
    {
        echo("from transferCurriculum");
        $code = $_POST['code'];
        $query = "INSERT INTO {$dest} SELECT * FROM {$origin} WHERE curr_code = '{$code}';"; //{dest} {origin} lang ung sa tables kong saan macocopy kasi kapag meron siyang qoutation iproprocess siya as string hindi as a table
        //insert other queries 
        $query .= "DELETE FROM {$origin} WHERE curr_code = '{$code}'";
        echo($query);
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
    public function listPrograms($tbl)
    {
        $query = "SELECT * FROM {$tbl}";
        $result = mysqli_query($this->dbConnect, $query);
        $programList = array();

    
        while ($row = mysqli_fetch_assoc($result)) {
            $programList[] = new Program($row['prog_code'], $row['curriculum_curr_code'], $row['description']);
        }
        return $programList;
    }

    public function listProgramsJSON($tbl)
    {
        echo json_encode($this->listPrograms($tbl));
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
        header("Location: program.php?prog_code=$code");
    }

    public function transferProgram($dest, $origin, $sub_dest, $sub_origin, $req_dest, $req_org, $shared_dest, $shared_org )
    {
        $code = $_POST['code'];
        $query = "INSERT INTO $dest SELECT * FROM $origin where prog_code = '$code';";
        $query .= "INSERT INTO $sub_dest SELECT * FROM $sub_origin WHERE sub_code IN (SELECT sub_code FROM $shared_org WHERE prog_code = '$code');";
        $query .= "INSERT INTO $req_dest SELECT * FROM $req_org where sub_code IN (SELECT sub_code FROM $shared_org WHERE prog_code = '$code');";
        $query .= "INSERT INTO $shared_dest SELECT * FROM $shared_org WHERE sub_code = '$code';";
        $query .= "DELETE FROM $shared_org WHERE prog_code = '$code'";
        $query .= "DELETE FROM $origin WHERE prog_code = '$code';";
        mysqli_multi_query($this->dbConnect, $query);
    }

    public function listArchivedProgJSON()
    {

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
                $queryTwo = "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';";
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

    private function setParentPrograms($code, $sub_type, $subject) 
    {
        $queryTwo = "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';";
        $resultFour = mysqli_query($this->dbConnect, $queryTwo);
        $programs = [];
        while ($rowFour = mysqli_fetch_assoc($resultFour)) {
            $programs[] = $rowFour['prog_code'];
        }

        if ($sub_type == 'applied') {
            $subject->set_Programs($programs);
        } else if ($sub_type == 'specialized') {
            $subject->set_Program($programs[0]);
        }

        return $subject;
    }

    public function listSubUnderProgJSON() {
        $subjectList = [];
        $query = "SELECT sub_code FROM sharedsubject WHERE prog_code='". $_GET['prog_code'] ."';";
        $result = mysqli_query($this->dbConnect, $query);

        $subjects = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row['sub_code'];
        }

        foreach($subjects as $sub) {
            $query = "SELECT * FROM subject WHERE sub_code='$sub';";
            $result = mysqli_query($this->dbConnect, $query);

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
        $code = $_GET['code'];
        $queryOne = "SELECT * FROM subject WHERE sub_code='$code'";
        $result = mysqli_query($this->dbConnect, $queryOne);
        $row = mysqli_fetch_assoc($result);
        $sub_type = $row['sub_type'];
        $subject = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);

        if ($sub_type != 'core') {
            $subject = $this->setParentPrograms($code, $sub_type, $subject);
        }

        $queryTwo = "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='PRE';";
        $resultTwo = mysqli_query($this->dbConnect, $queryTwo);
        $prereq = [];
        if ($resultTwo) {
            while ($rowTwo = mysqli_fetch_assoc($resultTwo)) {
                // $prereq[] = $rowTwo['req_sub_code'];
                $prereq[] = $rowTwo['req_sub_code'];
            }
        }

        $queryThree = "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='CO'";
        $resultThree = mysqli_query($this->dbConnect, $queryThree);
        $coreq = [];
        if ($resultThree) {
            while ($rowThree = mysqli_fetch_assoc($resultThree)) {
                // $coreq[] = $rowThree['req_sub_code'];
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

        $query = "INSERT INTO subject (sub_code, sub_name, for_grd_level, sub_semester, sub_type) VALUES ('$code', '$subName', '$grdLvl', '$sem', '$type');";


        // insert program and subject info in sharedsubject table
        if ($type == 'specialized') {
            $program_code = $_POST['prog_code'];
            $query .= "INSERT INTO sharedsubject VALUES ('$code', '$program_code');";
        }

        if ($type == 'applied') {
            $prog_code_list = $_POST['prog_code'];
            foreach($prog_code_list as $prog_code) {
                $query .= "INSERT INTO sharedsubject VALUES ('$code', '$prog_code');";
            }
        }

        if (isset($_POST['PRE'])) {
            foreach($_POST['PRE'] as $req_code) {
                $query .= "INSERT INTO requisite VALUES ('$code', 'PRE', '$req_code');";
            }
        }

        if (isset($_POST['CO'])) {
            foreach($_POST['CO'] as $req_code) {
                $query .= "INSERT INTO requisite VALUES ('$code', 'CO', '$req_code');";
            }
        }

        mysqli_multi_query($this->dbConnect, $query);
        $redirect = (($type === 'specialized') ? "prog_code={$program_code}&" : "") ."code=$code&state=view&success=added" ;
        echo json_encode((object) ["redirect" =>$redirect, "status" => 'Subject successfully added!']);
    }
    
    public function getUpdateRequisiteQry($code, $requisite) {
        $query = '';
        if (isset($_POST["$requisite"])) {
            $req_code_list = $_POST["$requisite"];

            // get current requisite subjects
            $queryThree = "SELECT req_sub_code FROM requisite WHERE sub_code='$code' AND type='$requisite';";
            $result = mysqli_query($this->dbConnect, $queryThree);
            $current_subjects = [];
            while($row = mysqli_fetch_assoc($result)) {
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
            if (count($new_sub_codes) > 0 ){
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
        $redirect = (($type === 'specialized') ? "prog_code={$program_info[0]}&" : "") ."code=$code&state=view&success=updated" ;
        echo json_encode((object) ["redirect" =>$redirect, "status" => 'Subject successfully updated!']);
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
            if (count($new_prog_codes) > 0 ){
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

            while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
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
            $subjectGrade11 = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'],$row['sub_type']);
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
            $subjectGrade12 = new Subject($row['sub_code'], $row['sub_name'], $row['for_grd_level'], $row['sub_semester'],$row['sub_type']);
            $subjGrade12List[] = $subjectGrade12;
        }
        return $subjGrade12List;
    }

    public function listSubjectsGrade12JSON()
    {
        echo json_encode($this->listSubjectsGrade12());
    }

    public function transferSubject($sub_dest, $sub_origin, $shared_dest , $shared_org, $req_dest,$req_org)
    {
        $code = $_POST['code'];
        $query = "INSERT INTO $sub_dest SELECT * FROM $sub_origin WHERE sub_code = '$code';";
        $query .= "INSERT INTO $shared_dest SELECT * FROM $shared_org WHERE sub_code = '$code';";
        $query .= "INSERT INTO $req_dest SELECT * FROM $req_org where sub_code = '$code';";
        $query .= "DELETE FROM $shared_org WHERE sub_code = '$code'";
        mysqli_multi_query($this->dbConnect, $query);
    }

}

<?php
    // session_start();
    require('config.php');
    require('Dataclasses.php');

    class Administration extends Dbconfig {
        protected $hostName;
        protected $userName;
        protected $password;
        protected $dbName;

        private $dbConnect = false;
        public function __construct() {
            if(!$this->dbConnect) {
                $database = new dbConfig();
                $this -> hostName = $database -> serverName;
                $this -> userName = $database -> userName;
                $this -> password = $database -> password;
                $this -> dbName = $database -> dbName;

                $conn = new mysqli($this-> hostName, $this-> userName, $this-> password, $this->dbName);
                if($conn->connect_error) {
                    die("Error failed to connect to MySQL: " . $conn->connect_error);
                } else {
                    $this->dbConnect = $conn;
                }
            }
        }

        private function getData($sqlQuery) {
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            if(!$result){
                die('Error in query: ' . mysqli_error());
            }
            $data = array();
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $data[]=$row;            
            }
            return $data;
        }

        /*** Curriculum Methods */

        /** Returns the list of curriculum. */
        public function listCurriculum() {
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

        public function listCurriculumJSON() {
            echo json_encode($this->listCurriculum());
        }

        /** Get curriculum object from a specified curriculum code */
        public function getCurriculum() {
            $code = $_GET['code'];
            $query = "SELECT * FROM curriculum WHERE curr_code='$code'";
            $result = mysqli_query($this->dbConnect, $query);
            $row = mysqli_fetch_assoc($result);
            $curriculum = new Curriculum($row['curr_code'], $row['curr_name']);
            $curriculum->add_cur_desc($row['curr_desc']);
            return $curriculum;
        }

        /** Adds a new curriculum */
        public function addCurriculum() {
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

        public function deleteCurriculum() {
            $code = $_POST['code'];
            $query = "DELETE FROM curriculum WHERE curr_code='$code'";
            mysqli_query($this->dbConnect, $query);
        }

        public function updateCurriculum() {
            $code = $_POST['code'];
            $old_code = $_POST['current_code'];
            $name = $_POST['name'];
            $description = $_POST['curriculum-desc'];
            
            // $updateQuery = "UPDATE curriculum SET curr_code=?, curr_name=?, curr_desc=? WHERE=?";//kasama ata ung where statement para alam kong anong curr and iaupdate?
            // $stmt = mysqli_prepare($this->dbConnect, $updateQuery);
            // mysqli_stmt_bind_param($stmt, 'ssss', $code, $name, $description, $old_code);
            // mysqli_stmt_execute($stmt);
            $updateQuery = "UPDATE curriculum SET curr_code='".$code."', curr_name='".$name."', curr_desc='".$description."' WHERE curr_code = '".$old_code."'";
			mysqli_query($this->dbConnect, $updateQuery);
            header("Location: curriculum.php?code=$code");
        }

        /*** Program Methods */
        public function getPrograms() {
            $code = $_GET['code'];
            $query = "SELECT * FROM program WHERE curriculum_curr_code='$code'";
            $result = mysqli_query($this->dbConnect, $query);
            $strands = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $strands[] = new Program($row['prog_code'], $row['curriculum_curr_code'], $row['description']);
            }
            echo json_encode($strands);
        }

        public function listProgram(){
            $query = "SELECT * FROM program";
            $result = mysqli_query($this->dbConnect, $query);
            $programList = array();

            while ($row = mysqli_fetch_assoc($result)) {
                $programList[] = new Program($row['prog_code'], $row['curriculum_curr_code'], $row['description']);
            }
            return $programList;
        }

        public function getProgramsJSON() {
            echo json_encode($this->getPrograms());
        }

        /** Adds a new program */
        public function addProgram() {
            $code = $_POST['code'];
            $currCode = $_POST['curr-code'];
            $description = $_POST['program-desc'];
            // start of validation

            // end of validation

            $query = "INSERT INTO program VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($this->dbConnect, $query);
            mysqli_stmt_bind_param($stmt, 'sss', $code, $currCode, $description);
            mysqli_stmt_execute($stmt);
        }

        public function deleteProgram() {
            $code = $_POST['code'];
            $query = "DELETE FROM program WHERE prog_code='$code'";
            mysqli_query($this->dbConnect, $query);
        }

        public function updateProgram() {
            $code = $_POST['code'];
            $currCode = $_GET['curr-code']; //uneditable yung curr_code here noh?
            $prog_description = $_POST['program-desc']; 

            //$updateQuery = "UPDATE program SET prog_code = '$code', description = '$description' WHERE prog_code = '$code'";

            // $updateQuery = "UPDATE ".$this->program." 
            // SET prog_code = '".$_POST["code"]."', description = '".$_POST["description"]."'
            // WHERE prog_code ='".$_POST["code"]."'";
            // $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
            
            //if($_POST['code']) {	}	 
        }

                /*** Subject Methods */
                // public function getPrograms() {
                //     $code = $_GET['code'];
                //     $query = "SELECT * FROM program WHERE curriculum_curr_code='$code'";
                //     $result = mysqli_query($this->dbConnect, $query);
                //     $strands = array();
                //     while ($row = mysqli_fetch_assoc($result)) {
                //         $strands[] = new Program($row['prog_code'], $row['curriculum_curr_code'], $row['prog_name']);
                //     }
                //     echo json_encode($strands);
                // }
        
                // public function getProgramsJSON() {
                //     echo json_encode($this->getPrograms());
                // }
        
                // /** Adds a new program */
                // public function addProgram() {
                //     $code = $_POST['code'];
                //     $currCode = $_POST['curr-code'];
                //     $description = $_POST['program-desc'];
                //     // start of validation
        
                //     // end of validation
        
                //     $query = "INSERT INTO program VALUES (?, ?, ?)";
                //     $stmt = mysqli_prepare($this->dbConnect, $query);
                //     mysqli_stmt_bind_param($stmt, 'sss', $code, $currCode, $description);
                //     mysqli_stmt_execute($stmt);
                // }
        
                // public function deleteProgram() {
                //     $code = $_POST['code'];
                //     $query = "DELETE FROM program WHERE prog_code='$code'";
                //     mysqli_query($this->dbConnect, $query);
                // }
        
                // public function updateProgram() {
                //     //if($_POST['subjectid']) {	
                //         $updateQuery = "UPDATE ".$this->subjectsTable." 
                //         SET subject = '".$_POST["subject"]."', type = '".$_POST["s_type"]."', code = '".$_POST["code"]."'
                //         WHERE subject_id ='".$_POST["subjectid"]."'";
                //         $isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
                //     //}	
               // }
                   
    }
?>
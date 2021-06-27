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
                if($conn->connect_error){
                    die("Error failed to connect to MySQL: " . $conn->connect_error);
                } else {
                    $this->dbConnect = $conn;
                }
            }
        }

        private function getData($sqlQuery) {
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            if(!$result){
                die('Error in query: '. mysqli_error());
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
                $curriculum = new Curriculum($row['curr_code'], $row['curr_name'], $row['curr_desc']);
                $curriculum->add_cur_desc($row['curr_desc']);
                $curriculumList[] = $curriculum;
            }
            return $curriculumList;
        }

        public function listCurriculumJSON() {
            echo json_encode($this->listCurriculum());
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

        public function getPrograms() {
            $code = $_GET['code'];
            $query = "SELECT * FROM program WHERE curriculum_curr_code='$code'";
            $result = mysqli_query($this->dbConnect, $query);
            $strands = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $strands[] = new Program($row['prog_code'], $row['curriculum_curr_code'], $row['prog_name']);
            }
            echo json_encode($strands);
        }

        
        public function getSubjects() {
            // $code = $_GET['code'];
            // $query = "SELECT * FROM program WHERE curriculum_curr_code='$code'";
            // $result = mysqli_query($this->dbConnect, $query);
            // $strands = array();
            // while ($row = mysqli_fetch_assoc($result)) {
            //     $strands[] = new Program($row['prog_code'], $row['curriculum_curr_code'], $row['prog_name']);
            // }
            // echo json_encode($strands);
        }
    }
?>
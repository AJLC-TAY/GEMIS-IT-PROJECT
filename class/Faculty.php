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

    public function getClasses() {
        $advisory = $this->getAdvisoryClass($_SESSION['sy_id']);
        $subjects = $this->getHandled_sub_classes($_SESSION['id']);

        return ['advisory' => $advisory, 'sub_class' => $subjects];
    }

    public function listAdvisoryStudents($is_JSON = false) {
        $students = [];
        $section_code = $_GET['section'];
        $result = $this->query("SELECT id_no, lrn, sex, CONCAT(last_name, ', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) AS name FROM student JOIN enrollment USING (stud_id) WHERE section_code='$section_code'");
        while($row = mysqli_fetch_assoc($result)) {
            $students [] = [
                'id'     =>  $row['id_no'],
                'lrn'    =>  $row['LRN'],
                'name'   =>  $row['name'],
                'sex'    =>  $row['sex'] == 'm' ? "Male" : "Female",
                'action' =>  "<div class='d-flex justify-content-center'>"
                        ."<button class='btn btn-sm btn-secondary me-1'>View</button>"
                        ."<button class='btn btn-sm btn-primary'>View Grades</button>"
                    ."</div>"
            ];
        }

        if ($is_JSON) {
            echo json_encode($students);
            return;
        }
        return $students;

    }

    public function listSubjectClass($is_JSON = false) {
        $students = [];
        $result = $this->query("SELECT id_no, lrn, sex, CONCAT(last_name, ', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) AS name, first_grading, second_grading, final_grade FROM student
                                    JOIN enrollment USING (stud_id)
                                    JOIN classgrade USING (stud_id)
                                    WHERE sub_class_code='{$_GET['sub_class_code']}';"
        );
        while($row = mysqli_fetch_assoc($result)) {
            $stud_id = $row['id_no'];
            $students [] = [
                'id'     =>  $stud_id,
                'lrn'    =>  $row['LRN'],
                'name'   =>  $row['name'],
                'sex'    =>  $row['sex'] == 'm' ? "Male" : "Female",
                'grd_1'  =>  $row['first_grading'],
                'grd_2'  =>  $row['second_grading'],
                'grd_f'  =>  $row['final_grading'],
                'action' =>  "<div class='d-flex justify-content-center'>"
                    ."<a href='grade.php?id=$stud_id' class='btn btn-sm btn-primary' target='_blank'>Grade</a>"
                    ."</div>"
            ];
        }

        if ($is_JSON) {
            echo json_encode($students);
            return;
        }
        return $students;
    }

    public function getClass() {
        if (isset($_GET['section'])) {
            $this->listAdvisoryStudents(true);
        }
        if (isset($_GET['sub_class_code'])) {
            $this->listSubjectClass(true);
        }
    }
}
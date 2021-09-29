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
    use QueryMethods, UserSharedMethods, FacultySharedMethods, Enrollment, Grade;

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
}
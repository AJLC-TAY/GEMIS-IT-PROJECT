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
        session_start();
        $students = [];
        $section_code = $_GET['section'];
        $result = $this->query("SELECT stud_id, lrn, sex, CONCAT(last_name, ', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) AS name FROM student 
                                JOIN enrollment USING (stud_id) WHERE section_code='$section_code'");
        while($row = mysqli_fetch_assoc($result)) {
            $stud_id = $row['stud_id'];
            # get report id
            $row_temp = $this->query("SELECT report_id FROM gradereport WHERE stud_id='$stud_id' AND sy_id='{$_SESSION['sy_id']}';");
            $report_id = mysqli_fetch_row($row_temp)[0];
            $students [] = [
                'id'     =>  $stud_id,
                'lrn'    =>  $row['LRN'],
                'name'   =>  $row['name'],
                'sex'    =>  $row['sex'] == 'm' ? "Male" : "Female",
                'action' =>  "<div class='d-flex justify-content-center'>"
                        ."<button class='btn btn-sm btn-secondary me-1'>View</button>"
                        ."<button data-report-id='$report_id' data-stud-id='$stud_id' class='btn btn-sm btn-secondary me-1 export-grade'>Export Grades</button>"
                        ."<a href='grade.php?id=$report_id' role='button' target='_blank' class='btn btn-sm btn-primary'>View Grades</a>"
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

    public function exportSubjectGradesToCSV () { 

        // Fetch records from database 
        $query = $this->query("SELECT LRN, CONCAT(last_name, ', ', first_name, ' ', LEFT(middle_name, 1), '.', COALESCE(ext_name, '')) as stud_name, first_grading, second_grading, final_grade FROM student JOIN classgrade USING(stud_id) JOIN subjectclass USING(sub_class_code) JOIN sysub USING (sub_sy_id) JOIN subject USING (sub_code) WHERE teacher_id=26 AND sub_class_code = 9101 AND sy_id=9;"); 

        if($query->num_rows > 0){ 
            $delimiter = ","; 
            $filename = "student-grades" . date('Y-m-d') . ".csv"; // + code ng subject class
     
            // Create a file pointer 
            $f = fopen('php://memory', 'w'); 
            
            // Set column headers 
            $fields = array('LRN', 'NAME', 'FIRST GRADING', 'SECOND GRADING', 'FINAL GRADE'); 
            fputcsv($f, $fields, $delimiter); 
     
            // Output each row of the data, format line as csv and write to file pointer 
            while($row = mysqli_fetch_assoc($query)){ 
                //$status = ($row['status'] == 1)?'Active':'Inactive'; 
                $lineData = array($row['LRN'], $row['stud_name'], $row['first_grading'], $row['second_grading'], $row['final_grade']); 
                fputcsv($f, $lineData, $delimiter); 
            } 
           
            
            // Move back to beginning of file 
            fseek($f, 0); 
     
 
     
            //output all remaining data on a file pointer 
            fpassthru($f); 
        } 
        fclose($f);
        exit; 
        

    }
    public function importSubjectGradesToCSV () {
        // Load the database configuration file
        //if(isset($_POST['importSubmit'])){
            
            // Allowed mime types
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
            
            // Validate whether selected file is a CSV file
            if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
                
                // If the file is uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    
                    // Open uploaded CSV file with read-only mode
                    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                    
                    // Skip the first line
                    fgetcsv($csvFile);
                    
                    // Parse data from CSV file line by line
                    while(($line = fgetcsv($csvFile)) !== FALSE){
                        // Get row data
                        $LRN  = $line[0];
                        $stud_name = $line[1]; 
                        $first_grading  = $line[2];
                        $second_grading = $line[3];
                        $final_grading = $line[4];
                        
                        // Check whether member already exists in the database with the same email
                        $prevQuery = "SELECT stud_id FROM student WHERE LRN = '".$line[0]."'";
                        $prevResult = $this->query($prevQuery);
                        
                        if($prevResult->num_rows > 0){
                            // Update member data in the database
                            $this->query("UPDATE `classgrade` SET `first_grading` = '".$first_grading."', `second_grading` = '".$second_grading."', `final_grade` = '".$final_grading."' WHERE stud_id IN (SELECT stud_id FROM student WHERE LRN='".$LRN."';"); //madami siyang pinalitan na rowsss need din ata ng subclasscode
                        }
                    }
                    
                    // Close opened CSV file
                    fclose($csvFile);
                    
                    $qstring = '?status=succ';
                }else{
                    $qstring = '?status=err';
                }
            }else{
                $qstring = '?status=invalid_file';
            }
        }
        
        // Redirect to the listing page
        // header("Location: index.php".$qstring);

    

    // function array_to_csv_download($filename = "export.csv", $delimiter=";") {
    //     $array = Array
    //     (
            
    //             (
    //                 ['fs_id'] => '4c524d8abfc6ef3b201f489c',
    //                 ['name'] => 'restaurant',
    //                 ['lat'] => 40.702692,
    //                 ['lng'] => -74.012869,
    //                 ['address'] => 'new york',
    //                 ['postalCode'] => 'sadsada'
    //             )
        
    //             );
    //     // open raw memory as file so no temp files needed, you might run out of memory though
    //     $f = fopen('php://memory', 'w'); 
    //     // loop over the input array
    //     foreach ($array as $line) { 
    //         // generate csv lines from the inner arrays
    //         fputcsv($f, $line, $delimiter); 
    //     }
    //     // reset the file pointer to the start of the file
    //     fseek($f, 0);
    //     // tell the browser it's going to be a csv file
    //     header('Content-Type: text/csv');
    //     // tell the browser we want to save it instead of displaying it
    //     header('Content-Disposition: attachment; filename="'.$filename.'";');
    //     // make php send the generated csv lines to the browser
    //     fpassthru($f);
    // }

    //RETRIEVAL FOR STUDENT GRADE PER CLASS studentname | First_grading | second_grading | Final
    //Store siya sa dataClass kaya dapat may class sa dataclass - classgrade
    //Tapos JSON ung return niya 
    public function getClassGrades(){
        $class_code = $_GET['sub_code'];
        $teacher_id = $_GET['id'];
        $sy_id = $_GET['sy_id']; 

        $res = $this->query("SELECT LRN, CONCAT(last_name, ', ', first_name, ' ', LEFT(middle_name, 1), '.', COALESCE(ext_name, '')) as stud_name, 
        first_grading, second_grading, final_grade FROM student 
        JOIN classgrade USING(stud_id) 
        JOIN subjectclass USING(sub_class_code) 
        JOIN sysub USING (sub_sy_id) 
        JOIN subject USING (sub_code) 
        WHERE teacher_id=$teacher_id
        AND sub_class_code =$class_code
        AND sy_id=$sy_id");
        
        $class_grades = [];
        while($grd = mysqli_fetch_assoc($res)) {
            $class_grades[] = [
                'lrn' => $grd['LRN'],
                'name' => $grd['stud_name'],
                'grd_1' => $grd['first_grading'],
                'grd_2' => $grd['second_grading'],
                'grd_f' => $grd['final_grade']
            ];
            
        }

        echo json_encode($class_grades);
    }
}
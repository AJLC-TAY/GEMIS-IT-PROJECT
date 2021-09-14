<?php
trait QueryMethods
{
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

}

trait UserShareMethods
{
    /**
     * Creates a User record basing from the specified user type.
     * @param   String $type Can either be AD, FA, or ST, short for Admin, Faculty, and Student, respectively.
     * @return  String User ID number.
     */
    public function createUser(String $type)
    {
        $qry = mysqli_query($this->db, "SELECT CONCAT('$type', (COALESCE(MAX(id_no), 0) + 1)) FROM user;");
        $PASSWORD = mysqli_fetch_row($qry)[0];
        mysqli_query($this->db, "INSERT INTO user (date_last_modified, user_type, password) VALUES (NOW(), '$type', '$PASSWORD');");
        return mysqli_insert_id($this->db);  // Return User ID ex. 123456789
    }
}

trait FacultySharedMethods
{
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
        $query = "SELECT sc.sub_class_code, sc.section_code, sc.sub_code, sc.teacher_id, s.sub_name, s.sub_type, se.grd_level, s.sub_semester, se.sy_id, se.section_name 
                  FROM subjectclass AS sc JOIN subject AS s USING (sub_code) 
                  JOIN section AS se USING (section_code) WHERE sc.teacher_id='$teacher_id';";
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
                $sc_row['sy_id'],
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
}

trait Enrollment
{
    /** Enrollment Methods */

    public function enroll()
    {
        session_start();
        echo "Add student starting...<br>";
        $student_id = $this->addStudent();
        echo "Add student finished.<br>";

        // School information
        echo "Add School info starting...<br>";

        $school_info = [$_POST['track'],  $_POST['program']];
        [$track, $program] = $this->preprocessData($school_info);
        $this->prepared_query(
            "INSERT INTO enrollment (date_of_enroll, valid_stud_data, enrolled_in, stud_id, sy_id, curr_code) "
            ."VALUES (NOW(), 0, ?, ?, ?, ?);",  // null for date_first_attended, and section code
            [
                $_POST['grade-level'],
                $student_id,
                $_SESSION['sy_id'],  // should be replaced by the current school year
                $track
            ],
            "iiss"
        );
        echo "Add School info ended...<br>";

        echo 'Adding promotion record...<br>';
        $this->prepared_query(
            "INSERT INTO promotion (school_id, school_name, school_type, school_add, last_grd_lvl_comp, last_school_yr_comp, "
            ."balik_aral, grd_to_enroll, last_gen_ave, semester, stud_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
//                $_POST['school-id'],
                NULL, # school_id
                $_POST['school-name'],
                NULL, # school_type
                $_POST['school-address'],
                $_POST['last-grade-level'],

                $_POST['last-sy'],
                $_POST['balik'],
                $_POST['grade-level'],
                $_POST['general-average'],
                $_POST['semester'] ?? 1, # default is 1

                $student_id
            ],
            // 11 params
            "isssi". "siiii" . "i"
        );
        echo 'Added promotion record...<br>';
    }

    public function getEnrollees()
    {
        $result = $this->query(
            "SELECT CONCAT(sy.start_year, ' - ', sy.end_year) AS SY, e.stud_id, LRN, CONCAT(s.last_name,', ', s.first_name,' ',s.middle_name,' ',COALESCE(s.ext_name, '')) AS name, "
            ."e.date_of_enroll, e.enrolled_in, e.curr_code, CASE WHEN e.valid_stud_data = 1 THEN 'Enrolled' WHEN e.valid_stud_data = 0 THEN 'Pending' ELSE 'Cancelled' END AS status FROM enrollment AS e "
            ."JOIN student AS s USING (stud_id) "
            ."JOIN schoolyear AS sy ON e.sy_id=sy.sy_id;"
        );
        $enrollees = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $enrollees[] = new Enrollee(
                $row['SY'], $row['LRN'], $row['name'],
                $row['date_of_enroll'], $row['enrolled_in'],
                $row['curr_code'], $row['status'], $row['stud_id']
            );

        }
        return $enrollees;
    }

    public function editEnrollStatus()
    {
        session_start();
        $can_enroll = isset($_POST['enrollment']) ? 1 : 0;
        if (isset($_POST['sy_id'])) {
            echo 'here';
            # enrollment status of other previous school year
            $sy_id = $_POST['sy_id'];
        } else {
            echo 'applied';
            # enrollment status of current school year; hence, update session value
            $sy_id = $_SESSION['sy_id'];
            $_SESSION['enrollment'] = $can_enroll;
        }
        $this->prepared_query("UPDATE schoolyear SET can_enroll=? WHERE sy_id=?;", [$can_enroll, $sy_id], "ii");
    }


    private function in_multi_array($string, $array) {
        $bool = false;
        foreach($array as $id => $value) {
            $bool = $string == $id;
        }
        return $bool;
    }

    public function getEnrollmentReportData($is_json = false)
    {
        $school_year = 5;
        $result = $this->query("SELECT curr_code, prog_code, valid_stud_data, COUNT(stud_id) AS 'count' FROM enrollment WHERE sy_id='$school_year' GROUP BY prog_code, valid_stud_data;");
        $data = [];

        $track = "";
        while($row = mysqli_fetch_assoc($result)) {
            $track = $row['curr_code'];
            $program = $row['prog_code'];
            $count =  $row['count'];
            if (count($data) === 0) {
                $program_array = [$count];
                $data[$track] = [$program => $program_array];
            } else {
                if ($this->in_multi_array($track, $data)) {
                    $data[$track][$program][] = $count;
                } else {
                    $program_array = [$count];
                    $data[$track] = [$program => $program_array];
                }
            }
        }

        if ($is_json) {
            echo json_encode($data);
            return;
        }
        return $data;

    }
    public function validateImage($file, $file_size)
    {
        echo "<br>Start validating image ... <br>";
        // default values
        $status = 'invalid';
        $statusInfo = [];
        $this_file_size = $file['size'];
        $profile_img = NULL;
        if ($this_file_size > 0) {
            echo "<br>Image exists ... <br>";

            if ($this_file_size > $file_size) {
                echo "<br>Image is greater than size ... <br>";
                $statusInfo['status'] = $status;
                $statusInfo["imageSize"] = "Sorry, image size should not be greater than 5 MB";
            }

            $filename = basename($file['name']);
            $fileType = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array($fileType, Administration::ALLOWED_IMG_TYPES)) {
                echo "<br>Image is valid ... <br>";
                $profile_img = file_get_contents($file['tmp_name']);
            } else {
                $statusInfo['status'] = $status;
                $statusInfo["imageExt"] = "Sorry, only JPG, JPEG, & PNG files are allowed to upload.";
                return $statusInfo;
            }

            $statusInfo['status'] = 'valid';
            $statusInfo['image'] = $profile_img;
            return $statusInfo;
        }
    }

    /**
     * Trims each element of the array and make each null if empty.
     * Returns a new array.
     * @param   array   $params
     * @return  array
     */
    public static function preprocessData($params)
    {
        return array_map(function($e) {
            $e = trim($e);
            return  $e ?? NULL;
        }, $params);
    }

    public function addStudent()
    {
        // Preprocess data gathered
        // Student personal info
        $params = [
            $_POST['lrn'], $_POST['last-name'], $_POST['first-name'], $_POST['middle-name'], $_POST['ext-name'],
            $_POST['birthdate'], $_POST['sex'], $_POST['age'], $_POST['birth-place'], $_POST['group-name'],
            $_POST['mother-tongue'], $_POST['religion'], $_POST['cp-no'], $_POST['psa'], $_POST['group']
        ];

        $address = [
            $_POST['house-no'], $_POST['street'], $_POST['barangay'],
            $_POST['city-muni'], $_POST['province'], $_POST['zip-code']
        ];

        function prepareParentData ($type)
        {
            $parent =  [$_POST["{$type}-lastname"], $_POST["{$type}-firstname"], $_POST["{$type}-middlename"]];
            if ($type === 'f') {
                $parent[] = $_POST["{$type}-extensionname"];
            }
            $parent[] =  $_POST["{$type}-contactnumber"];

            if ($type === 'g') { // if guardian, add relationship else occupation of parent
                $parent[] = $_POST["{$type}-relationship"];
            } else {
                $parent[] =  $type;
                $parent[] =  $_POST["{$type}-occupation"];
            }

            return Administration::preprocessData($parent);
        }
        $father = prepareParentData('f');
        $mother = prepareParentData('m');
        $guardian = prepareParentData('g');

        $params = $this->preprocessData($params);
        $address = $this->preprocessData($address);

        // Image validation
        $psa_img = $this->validateImage($_FILES['image-psa'], 8000000);
        $form_img = $this->validateImage($_FILES['image-form'], 8000000);
        $profile_img = $this->validateImage($_FILES['image-studentid'], 5242880);

        foreach([$psa_img, $form_img, $profile_img] as $image) {
            // add image to the parameters if valid
            if ($image['status'] == 'valid') {
                $params[] = $image['image'];
            }
        }

        // Values are valid; hence, create a user and add the created user id to the parameter
        $user_id = $this->createUser("ST");
        $params[] = $user_id;

        print_r(count($params));
        $this->prepared_query(
            "INSERT INTO student (LRN, last_name, first_name, middle_name, ext_name, "
            ."birthdate, sex, age, birth_place, indigenous_group, "
            ."mother_tongue, religion, cp_no, psa_num, belong_to_IPCC, "
            ."psa_birth_cert, form_137, id_picture, id_no) "
            ."VALUES (?, ?, ?, ?, ?,  ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?,  ?, ?, ?, ?);",
            $params,
            "sssss" . "ssiss" . "ssssi" . "sssi"
        );

        echo "Student info added. <br>";

        $student_id = mysqli_insert_id($this->db);
        $father[] = $student_id;
        $mother[] = $student_id;
        $guardian[] = $student_id;

        // insert parent info
        $general_q = "INSERT INTO parent (last_name, first_name, middle_name,";
        $f_query = "$general_q ext_name, cp_no, sex, occupation, stud_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
        $m_query = "$general_q cp_no, sex, occupation, stud_id) VALUES (?, ?, ?, ?, ?, ?, ?);";
        $g_query = "INSERT INTO guardian (guardian_last_name, guardian_first_name, guardian_middle_name, cp_no, relationship, stud_id) VALUES (?, ?, ?, ?, ?, ?);";
        $this->prepared_query($f_query, $father, "sssssssi");
        $this->prepared_query($m_query, $mother, "ssssssi");
        $this->prepared_query($g_query, $guardian, "sssssi");
        echo "Parent info added. <br>";


        // insert address info
        $address[] = $student_id;
        $a_query = "INSERT INTO address (home_no, street, barangay, mun_city, province, zip_code, stud_id) VALUES (?, ?, ?, ?, ?, ?, ?);";
        $this->prepared_query($a_query, $address, "sssssii");

        echo "Address info added. <br>";
        echo "$student_id <br>";

        return $student_id;
    }

}
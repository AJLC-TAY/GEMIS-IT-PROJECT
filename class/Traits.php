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
        }

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

trait School
{
    public function listDepartments()
    {
        $result = mysqli_query($this->db, "SELECT DISTINCT(department) FROM faculty WHERE department IS NOT NULL;");
        $departments = [];
        while ($row = mysqli_fetch_row($result)) {
            $departments[] = $row[0];
        }

        return $departments;
    }

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

    public function listSubjects($tbl, $tbl2)
    {
        session_start();
        $sy_id = $_SESSION['sy_id'];
        $subjectList = [];

        $shared_sub = ($tbl == "archived_subject") ? 'archived_sharedsubject' : 'sharedsubject';

        $queryOne = (isset($_GET['prog_code']))
            ? "SELECT * FROM subject JOIN sharedsubject USING (sub_code) WHERE prog_code = '{$_GET['prog_code']}' AND sy_id = '$sy_id';"
            // ? "SELECT * FROM $tbl WHERE sub_code 
            //    IN (SELECT sub_code FROM $shared_sub
            //    WHERE prog_code='{$_GET['prog_code']}' AND sy_id='$sy_id')
            //    UNION SELECT * FROM $tbl WHERE sub_type='core' AND sy_id='$sy_id' GROUP BY sub_code;"
            : "SELECT * FROM $tbl JOIN $tbl2 USING (sub_code) WHERE sy_id = '$sy_id' GROUP BY sub_code;";

        $resultOne = $this->query($queryOne);

        while ($row = mysqli_fetch_assoc($resultOne)) {
            $code = $row['sub_code'];
            $sub_type = $row['sub_type'];
            $subject =  new Subject($code, $row['sub_name'], $sub_type);

            if ($sub_type == 'specialized') {
                $resultTwo = $this->query("SELECT prog_code FROM sharedsubject WHERE sub_code='$code';");
                $rowTwo = mysqli_fetch_row($resultTwo);
                $subject->set_program($rowTwo[0] ?? "");
            }
            $subjectList[] = $subject;
        }

        return $subjectList;
    }

    public function listSubjectsJSON()
    {
        echo json_encode($this->listSubjects('subject', 'sharedsubject'));
    }

    public function listClass()
    {
        if ($_GET['class'] === 'advisory') {
        } else {
        }
    }

    public function listEnrollmentData($is_JSON = false)
    {
        session_start();
        $en_data = ['pending' => 0, 'enrolled' => 0, 'rejected' => 0];
        $sy_id = 9;
        //        $sy_id = $_SESSION['sy_id'];
        $result = $this->query("SELECT valid_stud_data AS status, COUNT(*) AS count FROM enrollment WHERE sy_id = '$sy_id' GROUP BY valid_stud_data;");
        while ($row = mysqli_fetch_assoc($result)) {
            switch ($row['status']) {
                case 0:
                    $status = 'pending';
                    break;
                case 1:
                    $status = 'enrolled';
                    break;
                case 2:
                    $status = 'rejected';
                    break;
            }
            $en_data[$status] = $row['count'];
        }
        if ($is_JSON) {
            echo json_encode($en_data);
            return;
        }
        return $en_data;
    }
}
trait UserSharedMethods
{
    /**
     * Creates a User record basing from the specified user type.
     * @param   String $type Can either be AD, FA, or ST, short for Admin, Faculty, and Student, respectively.
     * @return  int User ID number.
     */
    public function createUser(String $type): int
    {
        $qry = mysqli_query($this->db, "SELECT CONCAT('$type', (COALESCE(MAX(id_no), 0) + 1)) FROM user;");
        $PASSWORD = mysqli_fetch_row($qry)[0];
        mysqli_query($this->db, "INSERT INTO user (date_last_modified, user_type, password) VALUES (NOW(), '$type', '$PASSWORD');");
        return mysqli_insert_id($this->db);  // Return User ID ex. 123456789
    }

    /**
     * Returns the user Object of the specified user type.
     * @param String $type  Values could either be AD, FA, and ST for administrators, faculty, and student, respectively.
     * @return Administrator|Faculty|Student
     */

    public function getProfile($type)
    {
        $id = $_GET['id'] ?? $_SESSION['id'];

        if ($type === 'AD' && $_SESSION['user_type'] == 'AD') {
            return $this->getAdministrator($id ?? NULL);
        }

        if ($type === 'FA') {
            return $this->getFaculty($id);
        }

        if ($type === 'ST') {
            return $this->getStudent($id);
        }
    }

    /**
     * Returns Student with the specified student ID.
     * 1.   Get Student Personal Information
     * 2.   Get Student Parent Information
     * 3.   Get Student Guardian Information 
     * 4.   Get Student enrollment Status
     * 5.   Initialize Student object
     * 
     * @return Student Student object.
     */
    public function getStudent($id)
    {
        // Step 1
        $result = $this->prepared_select("SELECT * FROM student as s
                                        JOIN `address` as a ON a.stud_id = s.stud_id 
                                        WHERE s.stud_id=?;", [$id], "i");
        $personalInfo = mysqli_fetch_assoc($result);

        // Step 2
        $result = $this->prepared_select("SELECT * FROM parent WHERE stud_id=?;", [$id], "i");
        $parent = array();
        while ($parentInfo = mysqli_fetch_assoc($result)) {
            $extname = is_null($parentInfo['ext_name']) ? NULL : $parentInfo['ext_name'];
            $name = $parentInfo['last_name'] . ", " . $parentInfo['first_name'] . " " . $parentInfo['middle_name'] . " " . $extname;
            $parent[$parentInfo['sex']] = array(
                'name' => $name,
                'fname' => $parentInfo['first_name'],
                'mname' => $parentInfo['middle_name'],
                'lname' => $parentInfo['last_name'],
                'extname' => $extname,
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

        // Step 4

        $status = $this->prepared_select("SELECT CASE WHEN e.valid_stud_data = 1 THEN 'Enrolled' WHEN e.valid_stud_data = 0 THEN 'Pending' ELSE 'Rejected' END AS status FROM enrollment AS e WHERE stud_id = ?;", [$id], "i");
        while ($res = mysqli_fetch_row($status)) {
            $stat = $res[0];
        }


        sizeof($parent) != 0 ?: $parent = NULL;
        sizeof($guardian) != 0 ?: $guardian = NULL;

        $section = '';
        $section_code = '';
        $result = $this->prepared_select("SELECT s.section_name, e.section_code FROM enrollment e JOIN section s ON s.section_code=e.section_code WHERE stud_id=?;", [$id], "i");
        while ($res = mysqli_fetch_row($result)) {
            $section = $res[0];
            $section_code = $res[1];
        }

        $home_no = is_null($personalInfo['home_no']) ? "" : $personalInfo['home_no'];
        $street = is_null($personalInfo['street']) ? "" : $personalInfo['street'];
        $barangay = is_null($personalInfo['barangay']) ? "" : $personalInfo['barangay'];
        $mun_city = is_null($personalInfo['mun_city']) ? "" : $personalInfo['mun_city'];
        $province = is_null($personalInfo['mun_city']) ? "" : $personalInfo['mun_city'];
        $zipcode  = is_null($personalInfo['zip_code']) ? "" : $personalInfo['zip_code'];


        $complete_add = "$home_no $street $barangay, $mun_city, $province $zipcode";
        $add = [
            'address' => $complete_add,
            'home_no' => $home_no,
            'street' => $street,
            'barangay' => $barangay,
            'mun_city' => $mun_city,
            'province' => $province,
            'zipcode' => $zipcode
        ];

        //Step 5
        return new Student(
            $personalInfo['stud_id'],
            $personalInfo['id_no'],
            is_null($personalInfo['LRN']) ? NULL : $personalInfo['LRN'],
            $personalInfo['first_name'],
            is_null($personalInfo['middle_name']) ? NULL : $personalInfo['middle_name'],
            $personalInfo['last_name'],
            is_null($personalInfo['ext_name']) ? NULL : $personalInfo['ext_name'],
            $personalInfo['sex'],
            $personalInfo['age'],
            $personalInfo['birthdate'],
            is_null($personalInfo['birth_place']) ? NULL : $personalInfo['birth_place'],
            is_null($personalInfo['indigenous_group']) ? NULL : $personalInfo['indigenous_group'],
            is_null($personalInfo['mother_tongue']) ? NULL : $personalInfo['mother_tongue'],
            is_null($personalInfo['religion']) ? NULL : $personalInfo['religion'],
            $add,
            is_null($personalInfo['cp_no']) ? NULL : $personalInfo['cp_no'],
            is_null($personalInfo['psa_birth_cert']) ? NULL :  $personalInfo['psa_birth_cert'],
            is_null($personalInfo['belong_to_IPCC']) ? NULL : $personalInfo['belong_to_IPCC'],
            is_null($personalInfo['id_picture']) ? NULL : $personalInfo['id_picture'],
            $section_code,
            $section,
            $parent,
            $guardian,
            is_null($personalInfo['form_137']) ? NULL : $personalInfo['form_137'],
            $stat
        );
    }
}

trait FacultySharedMethods
{
    /**
     * Implementation of adding new, or editing existing faculty record.
     */
    public function processFaculty()
    {
        session_start();
        $user_type = $_SESSION['user_type'];
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

        $params = [
            $lastname, $firstname, $middlename, $extname, $birthdate, $age, $sex, $email
        ];
        $types = "sssssdss";
        // Profile image
        $fileDestination = NULL;
        $fileSize = $_FILES['image']['size'];
        if ($fileSize > 0) {
            if ($fileSize > 5242880) { //  file is greater than 5MB
                $statusMsg["imageSize"] = "Sorry, image size should not be greater than 5 MB";
            }
            $filename = basename($_FILES['image']['name']);
            $fileType = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                //                $imgContent = file_get_contents($_FILES['image']['tmp_name']);
                # Upload image
                $imgContent = $_FILES['image']['tmp_name'];
                $filename = time() . "_" . uniqid("", true) . ".$fileType";  // 234236513_12323.png
                $fileDestination = "uploads/faculty/$filename"; // ex. uploads/faculty/234236513_12323.png
                if (isset($_POST["current_image_path"])) { // if it exists, page is from edit form
                    $current_img_path = $_POST["current_image_path"];
                    if (strlen($current_img_path) != 0) { // if more than 0, there exists an image
                        unlink("../" . $current_img_path);                                 // delete current image
                    }
                }
                move_uploaded_file($imgContent, "../" . $fileDestination);
            } else {
                $statusMsg["imageExt"] = "Sorry, only JPG, JPEG, & PNG files are allowed to upload.";
                http_response_code(400);
                die(json_encode($statusMsg));
            }
        }

        switch ($user_type) {
            case "AD":
                [$editGrades, $canEnroll, $awardRep] = $this->prepareFacultyRolesValue();
                $params = array_merge($params, [$awardRep, $canEnroll, $editGrades, $department, $cp_no, $fileDestination]);
                $types .= "iiisss"; // data types of the current parameters
                break;
            case "FA":
                $params = array_merge($params + [$cp_no, $fileDestination]);
                $types .= "ss";
                break;
        }

        if ($action == 'add' and $user_type === 'AD') {
            $statusMsg = $this->addFaculty($params, $types);
        }
        if ($action == 'edit') {
            $statusMsg = $this->editFaculty($params, $types, $user_type);
        }

        echo $statusMsg;
    }
    /**
     * Implementation of updating Faculty
     * 1.   Remove image content from parameters and types if null
     * 2.   Add teacher id to the parameter and types before executing the query.
     *      End if user is faculty.
     * 3.   Update every subject handled if exist
     * 4.   Update every subject classes handled
     *  */
    private function editFaculty(array $params, String $types, String $user_type)
    {
        // Step 1
        $imgContent = end($params);                             // Image content is the last element of the parameter array
        $imgQuery = ", id_picture=?";
        if (is_null($imgContent)) {                                    // If image content is null
            $imgQuery = "";
            array_pop($params);                                 // remove image in params
            $types = substr_replace($types, "", -1);      // remove last type
        }
        // Step 2
        $params[] = $id = $_POST['teacher_id'];
        $types .= "i";

        $query = "UPDATE faculty SET last_name=?, first_name=?, middle_name=?, ext_name=?, birthdate=?, age=?, sex=?, email=?,";
        if ($user_type === 'FA') {
            $query .= " cp_no=?$imgQuery WHERE teacher_id=?;";
            $this->prepared_query($query, $params, $types);
        } else if ($user_type === 'AD') {
            $query .= " award_coor=?, enable_enroll=?, enable_edit_grd=?, department=?, cp_no=?$imgQuery WHERE teacher_id=?;";
            $this->prepared_query($query, $params, $types);
            // Step 3
            $this->updateFacultySubjects($id);

            // Step 4
            $this->updateAssignedSubClass($id);
        }

        echo json_encode(["teacher_id" => $id]);
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
    public function getFaculty($id): Faculty
    {
        // Step 1
        $result = $this->prepared_select("SELECT * FROM faculty WHERE teacher_id=?;", [$id], "i");
        $row = mysqli_fetch_assoc($result);

        // Step 2
        $result = $this->prepared_select("SELECT sc.section_code, sc.sub_code, sc.teacher_id, s.sub_name, s.sub_type, se.grd_level, ss.sub_semester, se.sy_id, se.section_name 
        FROM subjectclass AS sc 
        JOIN subject AS s USING (sub_code) 
        JOIN sharedsubject AS ss USING (sub_code)
        JOIN section AS se USING (section_code) WHERE sc.teacher_id=?);", [$id], "i");
        $subjects = array();
        while ($s_row = mysqli_fetch_assoc($result)) {
            $subjects[] = new Subject($s_row['sub_code'], $s_row['sub_name'], $s_row['for_grd_level'], $s_row['sub_semester'], $s_row['sub_type']);
        }

        // Step 3
        $teacher_id = $row['teacher_id'];
        $handled_sub_classes = $this->getHandled_sub_classes($teacher_id);
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
     * Returns array of handled classes.
     * @return array|null Array of class detail.
     */
    public function getAdvisoryClass($sy = null)
    {
        $query = "SELECT section_code, section_name FROM section WHERE teacher_id=?";
        $id = $_GET['id'] ?? ($_SESSION['user_type'] == 'FA' ? $_SESSION['id'] : die());
        if (is_null($sy)) {
            $params = [$id];
            $types = "i";
        } else {
            $query .= " AND sy_id=?; ";
            $params = [$id, $sy];
            $types = "ii";
        }
        $data = mysqli_fetch_row($this->prepared_select($query, $params, $types));
        if ($data) {
            return ["section_code" => $data[0], "section_name" => $data[1]];
        }
        return NULL;
    }

    /**
     * Returns an array of sections/classes handled by a specified teacher.
     * @param   string  $teacher_id     ID of the faculty.
     * @return  array   $section_list   Collection of sections.
     */
    public function listSectionOption($teacher_id)
    {
        $query = "SELECT s.section_code, s.section_name, s.grd_level, s.teacher_id, f.last_name, f.first_name, f.middle_name, f.ext_name FROM section AS s "
            . "LEFT JOIN faculty AS f USING (teacher_id) "
            . "WHERE teacher_id != '$teacher_id' "
            . "OR teacher_id IS NULL ORDER BY teacher_id;";
        $result = mysqli_query($this->db, $query);
        $section_list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $teacher_id = $row['teacher_id'];
            $name = $teacher_id ? "T. {$row['last_name']}, {$row['first_name']} {$row['middle_name']} {$row['ext_name']}" : "";
            $section_list[] = [
                "section_code" => $row['section_code'],
                "section_name" => $row['section_name'],
                "section_grd"  => $row['grd_level'],
                "adviser_id"   => $teacher_id,
                "adviser_name" => $name
            ];
        }
        return $section_list;
    }


    public function listSectionOptionJSON($teacher_id)
    {
        echo json_encode($this->listSectionOption($teacher_id));
    }


    /**
     * Returns an array of subject classes handled by the given teacher.
     * @param string $teacher_id    ID of faculty.
     * @array array  $sub_classes   Collection of classes.
     */
    public function listSubjectClasses($teacher_id = "")
    {
        $condition = $teacher_id ? "WHERE sc.teacher_id !='$teacher_id' OR sc.teacher_id IS NULL" : "";
        $query = "SELECT sc.sub_class_code, sc.section_code, sys.sub_code, sc.teacher_id, s.sub_name, s.sub_type, se.grd_level, s.sub_semester, se.sy_id, se.section_name 
                  FROM subjectclass AS sc JOIN sysub AS sys USING (sub_sy_id) 
                  JOIN subject AS s USING (sub_code) 
                  JOIN section AS se USING (section_code) $condition ORDER BY sc.teacher_id; ";
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
                $sc_row['sy_id'],
                $sc_row['teacher_id']
            );
        }
        return $sub_classes;
    }

    /**
     * @param mixed $teacher_id
     * @return array
     */
    public function getHandled_sub_classes($teacher_id): array
    {
        $query = "SELECT DISTINCT sc.section_code, s.sub_code, sc.teacher_id, s.sub_name, s.sub_type, se.grd_level, ss.sub_semester, se.sy_id, se.section_name  
        FROM subjectclass AS sc  
        JOIN section AS se USING (teacher_id) 
        JOIN sharedsubject AS ss USING(sy_id) 
        JOIN subject AS s USING(sub_code)
        WHERE sc.teacher_id='$teacher_id';";
        $result = $this->query($query); //hindi ka pa ba inaanto
        $handled_sub_classes = array();

        while ($sc_row = mysqli_fetch_assoc($result)) {
            $handled_sub_classes[] = new SubjectClass(
                $sc_row['sub_code'],
                $sc_row['sub_name'],
                $sc_row['grd_level'],
                $sc_row['sub_semester'],
                $sc_row['sub_type'],
                NULL,
                $sc_row['section_code'],
                $sc_row['section_name'],
                $sc_row['sy_id'],
                $teacher_id
            );
        }
        return $handled_sub_classes;
    }

    public function listAttendance($is_JSON = FALSE)
    {
        $attendance = [];
        $result = $this->query("SELECT stud_id, attendance_id, CONCAT(last_name,', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) 
                                AS name, month, no_of_present AS present, no_of_absent AS absent, no_of_tardy AS tardy, no_of_days AS total FROM student s 
                                JOIN enrollment e USING (stud_id)
                                JOIN attendance a USING (stud_id) 
                                JOIN academicdays USING (acad_days_id)
                                WHERE section_code = '{$_GET['class']}' AND acad_days_id = '{$_GET['month']}';");
        while ($row = mysqli_fetch_assoc($result)) {
            $attend_id = $row['attendance_id'];
            $attendance[] = [
                'stud_id' => $row['stud_id'],
                'name'    => $row['name'],
                'present_e' => "<input name='data[{$attend_id}][present]' class='form-control form-control-sm text-center mb-0 number' readonly value='{$row['present']}'>",
                'absent_e'  => "<input name='data[{$attend_id}][absent]' class='form-control form-control-sm text-center mb-0 number' readonly value='{$row['absent']}'>",
                'tardy_e'   => "<input name='data[{$attend_id}][tardy]' class='form-control form-control-sm text-center mb-0 number' readonly value='{$row['tardy']}'>",
                'action'    => "<div class='d-flex justify-content-center'>
                                   <button class='btn btn-sm btn-secondary action' data-type='edit'>Edit</button>
                                   <div class='edit-spec-options' style='display: none;'>
                                       <button data-type='cancel' class='action btn btn-sm btn-dark me-1 mb-1'>Cancel</a>
                                       <button data-type='save' class='action btn btn-sm btn-success'>Save</button>                                
                                    </div>
                                </div>"
            ];
        }

        if ($is_JSON) {
            echo json_encode($attendance);
            return;
        }
        return $attendance;
    }

    public function changeAttendance()
    {
        foreach ($_POST['data'] as $id => $value) { // $id = report_id
            $this->prepared_query(
                "UPDATE attendance SET no_of_present=?, no_of_absent=?, no_of_tardy=? WHERE attendance_id = ?;",
                [$value['present'], $value['absent'], $value['tardy'], $id],
                "iiii"
            );
        }
    }

    public function getAttendanceDays()
    {
        $current_month = date("F"); // ex. January
        $sy_id = $_SESSION['sy_id'];
        // $sy_id = 13;
        $result = $this->query("SELECT acad_days_id AS id, month, no_of_days FROM academicdays WHERE sy_id = '$sy_id' ORDER BY acad_days_id;");
        $months = [];
        while ($row = mysqli_fetch_row($result)) {
            $mnth = $row[0];
            $mnth_desc = $row[1];
            if ($current_month == $mnth_desc) {
                $current_month = $mnth;
            }
            $months[$mnth] = [$mnth_desc, $row[2]];
        }
        return ['current' => $current_month, 'months' => $months];
    }

    public function listAdvisoryClasses($is_JSON = FALSE)
    {
        // session_start();
        $id = $_GET['id'];
        $advisorCondition = isset($_GET['currentAdvisory']) ? "AND section_code!={$_GET['currentAdvisory']}" : "";
        $result = $this->query("SELECT se.section_code, se.section_name, se.grd_level, se.stud_no, "
            . "CONCAT(sy.start_year,' - ',sy.end_year) AS school_year, sy.start_year, sy.end_year  "
            . "FROM section AS se JOIN schoolyear AS sy USING (sy_id) WHERE teacher_id={$id} $advisorCondition;");
        $advisory_classes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $advisory_classes[] = [
                "sy"           => $row['school_year'],
                "start_y"      => $row['start_year'],
                "end_y"        => $row['end_year'],
                "section_code" => $row['section_code'],
                "section_name" => $row['section_name'],
                "section_grd"  => $row['grd_level'],
                "stud_no"      => $row['stud_no']
            ];
        }

        if ($is_JSON) {
            echo json_encode($advisory_classes);
            return;
        }
        return $advisory_classes;
    }
}


/** Enrollment Methods */
trait Enrollment
{
    public function enroll()
    {
        session_start();
        $school_year = $_SESSION['sy_id'];
        echo "Add student starting...<br>";
        $student_id = $this->addStudent($school_year);
        echo "Add student finished.<br>";

        # School information
        echo "Add School info starting...<br>";
        # enrollment
        $school_info = [$_POST['track'],  $_POST['program']];
        [$track, $program] = $this->preprocessData($school_info);
        $this->prepared_query(
            "INSERT INTO enrollment (date_of_enroll, valid_stud_data, enrolled_in, stud_id, sy_id, curr_code, prog_code) "
                . "VALUES (NOW(), 0, ?, ?, ?, ?, ?);",  // null for date_first_attended, and section code
            [
                $_POST['grade-level'],
                $student_id,
                $school_year,  // should be replaced by the current school year
                $track,
                $_POST['program']
            ],
            "iisss"
        );
        echo "Add School info ended...<br>";
        # promotion
        echo 'Adding promotion record...<br>';
        $this->prepared_query(
            "INSERT INTO promotion (school_id, school_name, school_add, last_grd_lvl_comp, last_school_yr_comp, "
                . "balik_aral, grd_to_enroll, last_gen_ave, semester, stud_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $_POST['school-id-no'] ?: NULL,
                $_POST['school-name'],
                $_POST['school-address'],
                $_POST['last-grade-level'],
                $_POST['last-sy'][0] . "-" . $_POST['last-sy'][1],

                $_POST['balik'],
                $_POST['grade-level'],
                $_POST['general-average'],
                $_POST['semester'] ?? 1, # default is 1
                $student_id
            ],
            // 10 params
            "issis" . "iiiii"
        );
        echo 'Added promotion record...<br>';
        if ($_SESSION['user_type'] != "ST") {
            header("Location: ./enrollment.php?page=enrollees");
        } else {
            $_SESSION['enrolled'] = TRUE;
            header("Location: finished.php");
        }
    }

    public function getEnrollees()
    {
        session_start();
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $query = "SELECT CONCAT(sy.start_year, ' - ', sy.end_year) AS SY, e.stud_id, LRN, CONCAT(s.last_name,', ', s.first_name,' ',s.middle_name,' ',COALESCE(s.ext_name, '')) AS name, "
            . "e.date_of_enroll, e.enrolled_in, e.curr_code, CASE WHEN e.valid_stud_data = 1 THEN 'Enrolled' WHEN e.valid_stud_data = 0 THEN 'Pending' ELSE 'Cancelled' END AS status, e.section_code FROM enrollment AS e "
            . "JOIN student AS s USING (stud_id) "
            . "JOIN schoolyear AS sy ON e.sy_id=sy.sy_id ";

        /**
         * Returns the sort query string in line with the received
         * sort value (column name in the database) and order value (ASC / DESC).
         * @return string Sort Query
         */
        function get_sort_query(): string
        {
            if (isset($_GET['sort'])) {
                $sort = $_GET['sort'];
                switch ($sort) {
                    case 'grade-level':
                        $sort = 'e.enrolled_in';
                        break;
                    case 'enroll-date':
                        $sort = 'e.date_of_enroll';
                        break;
                    case 'curriculum':
                        $sort = "e.curr_code";
                        break;
                }
                if ($_GET['order'] === 'desc') return " ORDER BY $sort DESC";
                return " ORDER BY $sort ASC";
            }
            return " ORDER BY name ASC";
        }

        $search_query = "";
        if (strlen(trim($_GET['search'])) > 0) {
            $text = $_GET['search'];
            //            $status = $text == 'pending' ? "0" : $text == 'enrolled' ? "1" : $text == 'rejected' ? "2" : "";
            $search_query .= " WHERE (sy.start_year LIKE \"%$text%\"";
            $search_query .= " OR sy.end_year LIKE \"%$text%\"";
            $search_query .= " OR s.last_name LIKE \"%$text%\"";
            $search_query .= " OR s.first_name LIKE \"%$text%\"";
            $search_query .= " OR s.middle_name LIKE \"%$text%\"";
            $search_query .= " OR s.ext_name LIKE \"%$text%\"";
            $search_query .= " OR stud_id LIKE \"%$text%\"";
            $search_query .= " OR LRN LIKE \"%$text%\"";
            $search_query .= " OR e.date_of_enroll LIKE \"%$text%\"";
            $search_query .= " OR e.enrolled_in LIKE \"%$text%\"";
            $search_query .= " OR e.curr_code LIKE \"%$text%\"";
            $search_query .= " OR e.valid_stud_data LIKE \"%$text%\") ";
        }


        $filter_query = [];
        if (!isset($_GET['sy'])) {
            $filter_query[] = " sy.sy_id ='{$_SESSION['sy_id']}'";
        } else {
            if ($_GET['sy'] !== '*') {
                $filter_query[] = " sy.sy_id ='{$_GET['sy']}'";
            }
        }
        if ($_GET['track'] !== '*') {
            $filter_query[] = " e.curr_code ='{$_GET['track']}'";
        }
        //        if ($_GET['strand'] !== '*') {
        //            $query .= " sy.sy_id = \"%{$_GET['strand']}%\" AND";
        //        }
        if ($_GET['yearLevel'] !== '*') {
            $filter_query[] = " e.enrolled_in ='{$_GET['yearLevel']}'";
        }
        if ($_GET['status'] !== '*') {
            $filter_query[] = " e.valid_stud_data ='{$_GET['status']}'";
        }
        $filter_qr = implode(" AND ", $filter_query);

        if (strlen($search_query) > 0) {
            $query .= $search_query . (strlen($filter_qr) > 0 ? " AND (" . $filter_qr . ")" : "");
        } else {
            $query .= (strlen($filter_qr) > 0 ? " WHERE " . $filter_qr : "");
        }

        $query .= get_sort_query();
        $result = $this->query($query);
        $num_rows_not_filtered = $result->num_rows;

        $query .= " LIMIT $limit";
        $query .= " OFFSET $offset";
        //    echo $query;
        $result = $this->query($query);
        $records = array();

        while ($row = mysqli_fetch_assoc($result)) { // MYSQLI_ASSOC allows to retrieve the data through the column name
            $records[] = new Enrollee(
                $row['SY'],
                $row['LRN'],
                $row['name'],
                $row['date_of_enroll'],
                $row['enrolled_in'],
                $row['curr_code'],
                $row['status'],
                $row['stud_id'],
                $row['section_code']
            );
        }
        $output = new stdClass();
        $output->total = $num_rows_not_filtered;
        $output->totalNotFiltered = $num_rows_not_filtered;
        $output->rows = $records;
        echo json_encode($output);
    }

    public function getEnrolled()
    {
        session_start();
        $sy_id = $_SESSION['sy_id'];
        $enrolled = [];
        $query = "SELECT e.stud_id, LRN, CONCAT(s.last_name,', ', s.first_name,' ',s.middle_name,' ',COALESCE(s.ext_name, '')) AS name, "
            . "e.enrolled_in AS grade, e.prog_code AS program, e.section_code AS section FROM enrollment e "
            . "JOIN student AS s USING (stud_id) "
            . "JOIN schoolyear AS sy ON e.sy_id=sy.sy_id WHERE e.valid_stud_data = 1 AND e.sy_id = '$sy_id';";
        $result = $this->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $enrolled[] = [
                'LRN' => $row['LRN'],
                'name' => $row['name'],
                'grade' => $row['grade'],
                'strand' => $row['program'],
                'section' => $row['section'],
                'id' => $row['stud_id'],
            ];
        }

        echo json_encode($enrolled);
    }

    public function getEnrollFilters(): array
    {
        $filter = [];

        # Get school year
        //        $result = $this->query("SELECT sy_id, CONCAT(start_year,' - ', end_year) as sy FROM schoolyear;");
        $result = $this->query("SELECT sy_id, CONCAT(start_year,' - ', end_year) as sy FROM schoolyear GROUP BY sy;");
        $school_years = [];
        while ($row = mysqli_fetch_row($result)) {
            $school_years[$row["0"]] = $row["1"];
        }
        $filter['school_year'] = $school_years;


        # Get tracks
        $result = $this->query("SELECT curr_code, curr_name FROM curriculum;");
        $programs = [];
        while ($row = mysqli_fetch_row($result)) {
            $programs[$row["0"]] = $row["1"];
        }
        $filter['tracks'] = $programs;

        # Get strands
        $result = $this->query("SELECT prog_code, description FROM program;");
        $programs = [];
        while ($row = mysqli_fetch_row($result)) {
            $programs[$row["0"]] = $row["1"];
        }
        $filter['programs'] = $programs;

        # Prepare year level
        $filter['year_level'] = ['11', '12'];

        $filter['status'] = ["0" => "Pending", "1" => "Enrolled", "2" => "Cancelled"];

        # sections
        $sy_id = $_SESSION['sy_id'];
        $result = $this->query("SELECT section_code, section_name FROM section WHERE sy_id = '{$sy_id}';");
        while ($row = mysqli_fetch_row($result)) {
            $filter['section'][$row[0]] = $row["1"];
        }

        return $filter;
    }


    private function in_multi_array(string $string, array $array): bool
    {
        $bool = false;
        foreach ($array as $id => $value) {
            $bool = $string == $id;
        }
        return $bool;
    }

    public function getEnrollmentReportData($is_json = false)
    {
        $school_year = 9;
        $result = $this->query("SELECT curr_code, prog_code, valid_stud_data, COUNT(stud_id) AS 'count' FROM enrollment WHERE sy_id='$school_year' GROUP BY prog_code, valid_stud_data;");
        $data = [];

        $track = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $track = $row['curr_code'];
            $program = $row['prog_code'];
            $count =  $row['count'];
            $index = $row['valid_stud_data'] == 1 ? 1 : 0;
            if (count($data) === 0) {
                $program_array[$index] = $count;
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
            exit;
        }
        return $data;
    }

    public function validateImage($file, $file_size): array
    {
        echo "<br>Start validating image ... <br>";
        // default values
        $status = 'invalid';
        $statusInfo = [];
        $this_file_size = $file['size'];
        $img_name = NULL;
        $img_content = NULL;
        if ($this_file_size > 0) {
            echo "<br>Image exists ... <br>";

            if ($this_file_size > $file_size) {
                echo "<br>Image is greater than size ... <br>";
                $statusInfo['status'] = $status;
                $statusInfo["imageSize"] = "Sorry, image size should not be greater than 5 MB";
            }

            $filename = basename($file['name']);
            $file_type = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array($file_type, array('jpg', 'png', 'jpeg'))) {
                echo "<br>Image is valid ... <br>";
                $img_name = time() . "_" . uniqid("", true) . ".$file_type";
                $img_content = $file['tmp_name'];
            } else {
                $statusInfo['status'] = $status;
                $statusInfo["imageExt"] = "Sorry, only JPG, JPEG, & PNG files are allowed to upload.";
                return $statusInfo;
            }
            $statusInfo['status'] = 'valid';
            $statusInfo['name'] = $img_name;
            $statusInfo['file'] = $img_content;
        }
        return $statusInfo;
    }

    /**
     * Trims each element of the array and make each null if empty.
     * Returns a new array.
     * @param   array   $params
     * @return  array
     */
    public static function preprocessData(array $params): array
    {
        return array_map(function ($e) {
            $e = trim($e);
            return  $e ?? NULL;
        }, $params);
    }

    // public function addSection($grade_level, $prog_code, $stud_no, $letter, $sy, $sycs) {
    //     echo "$grade_level, $prog_code, $stud_no, $letter, $sy, $sycs";
    //     $section_name =  "$grade_level-{$letter}-{$prog_code}-Class";  // 11-A-ABM-Class
    //     $section_code = rand(10, 10000000);
    //     // echo "Added section: ".$section_name."<br>";
    //     // echo "Section code: ". $section_code;
    //     $this->prepared_query(
    //         "INSERT INTO section (section_code, section_name, grd_level, stud_no_max, stud_no, sy_id) VALUES (?, ?, ?, ?, ?, ?);",
    //         [$section_code, $section_name, $grade_level, 50, $stud_no, $sy],
    //         "ssiiii"
    //     );
    //     $this->prepared_query("INSERT INTO sectionprog (section_code, sycs_id) VALUES (?, ?);",
    //         [$section_code, $sycs],
    //         "si"
    //     );


    //     return $section_code;
    // }

    // public function addSection() 
    //     {
    //         session_start();
    //         $code = $_POST['code'];
    // //        $program = $_POST['program'];
    //         $grade_level = $_POST['grade-level'];
    //         $max_no = $_POST['max-no'] ?: Administration::MAX_SECTION_COUNT;
    //         $section_name = $_POST['section-name'];
    //         $adviser = $_POST['adviser'] ?: NULL;
    //         $sy_id = $_SESSION['sy_id'];

    //         $this->prepared_query(
    //             "INSERT INTO section (section_code, section_name, grd_level, stud_no_max, teacher_id, sy_id) VALUES (?, ?, ?, ?, ?, ?) ;",
    //             [$code, $section_name, $grade_level, $max_no, $adviser, $sy_id],
    //             "ssiiii"
    //         );
    //     }


    public function addSection()
    {
        session_start();
        $sy_id = $_SESSION['sy_id'];
        $grade_level = $_POST['grade-level'];
        $max_no = $_POST['max-no'] ?: 50;
        $section_name = $_POST['section-name'];
        $adviser = $_POST['adviser'] == '*' ? NULL : $_POST['adviser'];
        $count = $_POST['count'];
        $section_code = rand();
        $this->prepared_query(
            "INSERT INTO section (section_code, section_name, grd_level, stud_no_max, stud_no, teacher_id, sy_id) VALUES (?, ?, ?, ?, ?, ?, ?) ;",
            [$section_code, $section_name, $grade_level, $max_no, $count, $adviser, $sy_id],
            "ssiiiii"
        );
        foreach ($_POST['students'] as $stud) {
            $this->query("UPDATE enrollment SET section_code = '$section_code' WHERE stud_id = '$stud' AND sy_id = '$sy_id';");
        }

        echo json_encode($section_code);
    }

    //Change adviser
    //UPDATE `section` SET `teacher_id` = '0000000022' WHERE `section`.`section_code` = '9562371';


    //Get advisers as replacement
    public function getTeachersList()
    {
    $id = 26;
    $advisers = $this->query("SELECT teacher_id, CONCAT(first_name, ' ', last_name, ' ', COALESCE(ext_name, '')) as name FROM `faculty`;"); // insert here ung retrieve mo lsit 
    while ($adviser = mysqli_fetch_assoc($advisers)) {
        $name = $adviser['name'];
            $list = "<select class='markings' name='markings' class='select2 px-0 form-select form-select-sm' required>";
            
                if ($id == $adviser['teacher_id']) { //if faculty id nung nitrieve == current faculty 
                    $list .= "<option value='' selected>$name</option>";
                } else {

                    $list .= "<option value=''>$name</option>";
                }
            }
            $adv[] =  $list;
             $list = '';
    }
     
    

    //Change subject teacher
    //UPDATE `subjectclass` SET `teacher_id` = '0000000022' WHERE `subjectclass`.`sub_class_code` = '123';

    //Get subject teachers as replacement

    /**
     *  Officially accept or reject enrollment request. 
     *  1.  If not valid (meaning, enrollee is rejected), return.
     *      If valid, 
     *          1.1 Add student to a section and initialize subject classes.     
     *          1.2 Update enrollment attribute.
     *          1.3 Initialize grade
     *          1.4 Initialize attendance
     */
    public function validateEnrollment()
    {
        session_start();
        // $stud_id, $current_sy, $is_valid
        $stud_id = $_POST['stud_id'];
        $current_sy = $_SESSION['sy_id'];
        $is_valid = (isset($_POST['accept'])) ? 1
            : (isset($_POST['reject']) ? 2 : 0);

        # Step 1
        if ($is_valid == 2) {
            $this->query("UPDATE enrollment SET valid_stud_data='$is_valid' WHERE stud_id='$stud_id' AND sy_id='$current_sy';");
            # update section if student was previously accepted
            $res = $this->query("SELECT section_code FROM enrollment WHERE stud_id='$stud_id' AND sy_id='$current_sy';");
            $sect_code = mysqli_fetch_row($res)[0];
            if ($sect_code) {
                # set section to null and subtract student no. on the section
                $this->query("UPDATE enrollment SET section_code=NULL WHERE stud_id='$stud_id' AND sy_id='$current_sy';");
                $this->query("UPDATE section SET stud_no = stud_no - 1 WHERE section_code = '$sect_code';");
            }
            return;
        }

        # query to join all school year table : SELECT sy.sy_id, syc_id, syc.curr_code, sycs_id, sycs.prog_code FROM schoolyear AS sy JOIN sycurriculum AS syc USING(sy_id) JOIN sycurrstrand AS sycs USING (syc_id);
        $enroll_detail = mysqli_fetch_assoc($this->query("SELECT sy_id, enrolled_in AS grade_level, prog_code FROM enrollment WHERE sy_id='$current_sy';"));
        $grade_level = $enroll_detail['grade_level'];
        $prog_code = $enroll_detail['prog_code'];

        // # get school_year_curr_strand_id
        // $sycs = mysqli_fetch_row($this->query("SELECT sycs_id FROM schoolyear 
        //                                        JOIN sycurriculum USING(sy_id) 
        //                                        JOIN sycurrstrand USING (syc_id) 
        //                                        WHERE prog_code='$prog_code' AND sy_id='$current_sy';"))[0];
        // $section_result = $this->query("SELECT * FROM section JOIN sectionprog USING (section_code) 
        //                                 WHERE sycs_id = '$sycs' AND grd_level = '$grade_level' 
        //                                 ORDER BY stud_no DESC;");
        // $sections = []; // 11 - A to 11 - D
        // $avail_section = []; // 11 - D, 11 - E
        // while($row = mysqli_fetch_array($section_result)) {
        //     $stud_no = $row['stud_no'];
        //     $stud_max_no = $row['stud_no_max'];
        //     $section = new Section (
        //         $row['section_code'],
        //         $row['sy_id'],
        //         $row['section_name'],
        //         $row['grd_level'],
        //         $stud_max_no,
        //         $stud_no,
        //         $row['teacher_id']
        //     );
        //     $sections[] = $section;
        //     if ($stud_no < $stud_max_no) { // 0 < 50
        //         $avail_section[] = $section;
        //     }
        // }    

        // $selected_section_code = 0;
        # if no available section, create new section
        // if (count($avail_section) === 0) {
        //     echo "Adding new section ... <br>";
        //     $alphabet = range('A', 'Z'); // "A, B, C, "
        //     $letter = $alphabet[count($sections)];
        //     // echo "$letter <br>";
        //     echo "$grade_level, $prog_code, $stud_no, $letter, $current_sy, $sycs <br>";
        //     $selected_section_code = $this->addSection($grade_level, $prog_code, 1, $letter, $current_sy, $sycs);
        // } else {
        //     # select the very first available section // 11-D
        //     $selected_section = $avail_section[0];
        //     $selected_section_code = $selected_section->get_code();
        //     # update section student no.
        //     $new_stud_no = $selected_section->increase_stud_no(1); // current stud 2 + 1 = 3
        //     $this->query("UPDATE section SET stud_no = '$new_stud_no' WHERE section_code = '$selected_section_code';");
        // }
        // $this->query("UPDATE enrollment SET valid_stud_data='$is_valid', section_code='$selected_section_code' WHERE stud_id='$stud_id';");
        $this->query("UPDATE enrollment SET valid_stud_data='$is_valid' WHERE stud_id='$stud_id';");

        # step 1.3
        $report_id = $this->initializeGrades($stud_id, $current_sy, $grade_level, $prog_code);

        # step 1.4
        $attend_data = $this->getAttendanceDays();
        $attend_months = $attend_data['months'];
        foreach ($attend_months as $id => $val) {
            $this->query("INSERT INTO attendance (stud_id, report_id, acad_days_id) VALUES ($stud_id, $report_id, $id);");
        }
    }

    function prepareParentData($type): array
    {
        $parent =  [$_POST["$type-lastname"], $_POST["$type-firstname"], $_POST["$type-middlename"]];
        if ($type === 'f') {
            $parent[] = $_POST["$type-extensionname"];
        }
        $parent[] =  $_POST["$type-contactnumber"];

        if ($type === 'g') { // if guardian, add relationship else occupation of parent
            $parent[] = $_POST["$type-relationship"];
        } else {
            $parent[] =  $type;
            $parent[] =  $_POST["$type-occupation"];
        }

        return $this->preprocessData($parent);
    }

    public function addStudent($sy_id)
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

        $father = $this->prepareParentData('f');
        $mother = $this->prepareParentData('m');
        $guardian = $this->prepareParentData('g');

        $params = $this->preprocessData($params);
        $address = $this->preprocessData($address);

        // Image validation
        $psa_img = $this->validateImage($_FILES['image-psa'], 8000000);
        $form_img = $this->validateImage($_FILES['image-form'], 8000000);
        $profile_img = $this->validateImage($_FILES['image-studentid'], 5242880);

        $img_list = [$psa_img, $form_img, $profile_img];
        foreach ($img_list as $i => $image) {
            // add image to the parameters if valid
            if ($image['status'] == 'valid') {
                # Upload image
                $folder = ($i == array_key_last($img_list)) ? "student" : "credential";
                $fileDestination = "uploads/$folder/$sy_id/{$image['name']}";
                // for editing
                //                if (isset($_POST["current_image_path"])) { // if it exists, page is from edit form
                //                    $current_img_path = $_POST["current_image_path"];
                //                    if (strlen($current_img_path) != 0) { // if more than 0, there exists an image
                //                        unlink("../".$current_img_path);                                 // delete current image
                //                    }
                //                }
                move_uploaded_file($image['file'], "../" . $fileDestination);
                echo "Successfully uploaded image<br>";
                $params[] = $fileDestination;
            }
        }

        // Values are valid; hence, create a user and add the created user id to the parameter
        $user_id = $this->createUser("ST");
        $params[] = $user_id;

        $this->prepared_query(
            "INSERT INTO student (LRN, last_name, first_name, middle_name, ext_name, "
                . "birthdate, sex, age, birth_place, indigenous_group, "
                . "mother_tongue, religion, cp_no, psa_num, belong_to_IPCC, "
                . "psa_birth_cert, form_137, id_picture, id_no) "
                . "VALUES (?, ?, ?, ?, ?,  ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?,  ?, ?, ?, ?);",
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

    public function initializeGrades($stud_id, $sy_id, $grade_level, $program)
    {
        echo "Start initializing grade .. <br>";
        # 1. initialize gradereport
        $this->prepared_query("INSERT INTO gradereport (stud_id, sy_id) VALUES (?, ?);", [$stud_id, $sy_id], 'ii');

        # 2. Retrieve report_id of student
        $report_id = mysqli_insert_id($this->db);

        # 3. Initialize classgrade
        # 3.a retrieve subjects
        $result = $this->query("SELECT s.sub_code FROM subject s
                                JOIN sysub USING (sub_code)
                                LEFT JOIN sharedsubject ss ON s.sub_code = ss.sub_code
                                WHERE sy_id = '$sy_id'
                                AND for_grd_level = '$grade_level'
                                AND (prog_code = '$program' OR sub_type = 'core')
                                GROUP BY s.sub_code");

        while ($row = mysqli_fetch_row($result)) {
            $this->query("INSERT INTO classgrade (report_id, stud_id, sub_code) VALUES ('$report_id', '$stud_id', '{$row[0]}');");
        }
        # 4. Initialize array of default observed value ids
        $values = $this->query("SELECT DISTINCT `value_id` FROM `values`;");

        # 4.a For each value_id, 
        # for each quarter, create an observedvalue.                     
        while ($row = mysqli_fetch_assoc($values)) {
            foreach ([1, 2, 3, 4] as $quarter) {
                $this->prepared_query("INSERT INTO `observedvalues`(`value_id`, `quarter`, `report_id`, `stud_id`) VALUES (?,?,?,?)", [$row['value_id'], $quarter, $report_id, $stud_id], 'siii');
            }
        }
        echo "End initializing grade: $report_id<br>";
        return $report_id;
    }

    public function getEnrollmentCurriculumOptions()
    {
        // session_start();
        $data = [];
        $sy_id = $_SESSION['sy_id'];
        $result = $this->query("SELECT * FROM program JOIN curriculum USING (curr_code) JOIN sycurriculum USING (curr_code) WHERE sy_id = '$sy_id'; ");
        while ($row = mysqli_fetch_assoc($result)) {
            $code = $row['curr_code'];
            $data[$code]['desc'] = $row['curr_desc'];
            $data[$code]['programs'][] = [$row['prog_code'] => $row['description']];
        }
        return $data;
    }
}


trait Grade
{

    public function getGrade()
    {
        $stud_id = 120089;
        $sy_id = 15;
        $grade_report_id = 141;

        $result = $this->query("SELECT sub_code, sub_name, first_grading, second_grading, final_grade FROM `classgrade` 
            JOIN subjectclass USING (sub_class_code) 
            JOIN sysub USING (sub_sy_id) 
            JOIN subject USING (sub_code) WHERE report_id='$grade_report_id'; ");
        $grades = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $grades[] = [
                'sub_name' => $row['sub_name'],
                'grade_1' => $row['first_grading'],
                'grade_2' => $row['second_grading'],
                'grade_f' => $row['final_grade']
            ];
        }

        return $grades;
    }

    public function getClassGrades()
    {
        $teacher_id = $_GET['id'];
        $sy_id = $_GET['sy_id'];
        $sub_code = $_GET['sub_code'];
        $qtr = 1;



        $res = $this->query("SELECT DISTINCT stud_id, status, CONCAT(last_name, ', ', first_name, ' ', LEFT(middle_name, 1), '.', COALESCE(ext_name, '')) as stud_name, first_grading, second_grading, final_grade 
        FROM student 
        JOIN classgrade USING(stud_id) 
        JOIN subject USING(sub_code) 
        JOIN sysub USING(sub_code) 
        JOIN subjectclass USING(sub_sy_id)
        WHERE teacher_id=$teacher_id 
        AND classgrade.sub_code = '$sub_code' 
        AND sy_id=$sy_id;");

        $class_grades = [];

        while ($grd = mysqli_fetch_assoc($res)) {
            if ($qtr == '2' || $grd['status'] == 1) {
                $first = 'readonly';
            } else if ($grd['status'] == 1) {
                $second = 'readonly';
            } else {
                $first = '';
                $second = '';
            }


            $second_final = $qtr == '2' ? '' : 'readonly';
            $class_grades[] = [
                'id' => $grd['stud_id'],
                'name' => $grd['stud_name'],
                'grd_1' => "<input name='{$grd['stud_id']}/first_grading' class='form-control form-control-sm text-center mb-0 First number' $first value='{$grd['first_grading']}'>",
                'grd_2' => "<input name='{$grd['stud_id']}/second_grading' class='form-control form-control-sm text-center mb-0 Second number' $second_final value='{$grd['second_grading']}'>",
                'grd_f' => "<input name='{$grd['stud_id']}/final_grade' class='form-control form-control-sm text-center mb-0 number' $second_final value='{$grd['final_grade']}'>"
            ];
        }

        echo json_encode($class_grades);
    }

    public function getAwardExcellenceData()
    {
        $is_graduating = false;
        if (isset($_GET['graduating']) && in_array(strtolower($_GET['graduating']), array('true', 'false'))) {
            $is_graduating = $_GET['graduating'];
        }
        $grd = ($is_graduating === 'true' ? "12" : "11");
        $query = "SELECT report_id, stud_id, CONCAT(last_name,', ',first_name,' ',middle_name,' ', COALESCE(ext_name,'')) AS name, sex, "
            . "curr_code AS curriculum, prog_code AS program, general_average, CASE WHEN (general_average >= 90 AND general_average <= 94) THEN 'with' "
            . "WHEN (general_average >= 95 AND general_average <= 97) THEN 'high' WHEN (general_average >= 98 AND general_average <=100) "
            . "THEN 'highest' END AS remark FROM gradereport JOIN student USING (stud_id) LEFT JOIN enrollment e USING (stud_id) WHERE general_average >= 90 "
            . "AND enrolled_in = '$grd' AND e.sy_id = '9' "
            . "ORDER BY program DESC, general_average DESC;";
        $result = $this->query($query);
        $excellence = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $excellence[$row['curriculum']][$row['program']]['students'][] = ['id' => $row['stud_id'], 'name' => $row['name'], 'ga' => $row['general_average'], 'sex' => ucwords($row['sex']), 'remark' => ucwords($row['remark'] . ' Honors')];
        }

        foreach ($excellence as $curr => $prog_rec) {
            foreach ($prog_rec as $prog => $prog_list) {
                $excellence[$curr][$prog]['size'] = count($prog_list['students']);
            }
        }

        return $excellence;
    }

    public function getAwardDataFromSubject()
    {
        $grd_param = 90;
        $sub_code = 'OCC1';
        $sy_id = 9;
        $data = [];
        $query = "SELECT gr.report_id, gr.stud_id, CONCAT(last_name,', ',first_name,' ',COALESCE(middle_name,''),' ', COALESCE(ext_name,'')) AS name, sex, prog_code AS program, final_grade, sub_code, enrolled_in AS grd
                    FROM gradereport gr JOIN student s ON gr.stud_id = s.stud_id 
                    LEFT JOIN enrollment e ON  e.stud_id = s.stud_id 
                    JOIN classgrade cg ON cg.report_id = gr.report_id 
                    JOIN subjectclass sc ON sc.sub_class_code = cg.sub_class_code 
                    JOIN sysub sys ON sys.sub_sy_id = sc.sub_sy_id
                    WHERE final_grade >= '$grd_param'  AND sys.sub_code = '$sub_code' AND e.sy_id = '$sy_id' 
                    ORDER BY program DESC, final_grade DESC;";
        $result = $this->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['grd']][$row['program']]['students'][] = ['id' => $row['stud_id'], 'name' => $row['name'], 'fg' => $row['final_grade'], 'sex' => ucwords($row['sex'])];
        }

        // foreach($data as $grd_level => $prog_rec) {
        //     foreach($prog_rec as $prog => $prog_list) {
        //         $data[$grd_level][$prog]['size'] = count($prog_list['students']);
        //     }
        // }    
        return $data;
    }

    public function getPerfectAttendance()
    {
        // $sy_id = $_GET['sy_id'] ?? $_SESSION['sy_id'];
        $sy_id = 9;
        $data = [];
        $query = "SELECT stud_id, LRN, name, sex, program, grd FROM (SELECT s.stud_id, LRN, CONCAT(last_name,', ',first_name,' ',COALESCE(middle_name,''), COALESCE(ext_name,'')) AS name, 
                    sex, SUM(no_of_present) AS total_attend, SUM(no_of_days) AS total_days, prog_code AS program, enrolled_in AS grd
                    FROM attendance JOIN gradereport gr USING (report_id)
                    JOIN student s ON s.stud_id = gr.stud_id
                    JOIN enrollment e ON e.stud_id = s.stud_id
                    JOIN academicdays USING (acad_days_id)
                    WHERE gr.sy_id = '$sy_id' GROUP BY s.stud_id) AS attend WHERE attend.total_attend = attend.total_days;";
        $result = $this->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['grd']][$row['program']]['students'][] = ['id' => $row['stud_id'], 'name' => $row['name'], 'lrn' => $row['LRN'], 'sex' => ucwords($row['sex'])];
        }
        return $data;
    }

    public function getConductAward()
    {
        $data = [];
        $sy_id = 9;
        $min = 3;
        $query = "SELECT * FROM (SELECT s.stud_id, LRN, CONCAT(last_name,', ',first_name,' ',COALESCE(middle_name,''), COALESCE(ext_name,'')) AS name, 
                        sex, prog_code AS program, enrolled_in AS grd, COUNT(CASE WHEN marking = 'SO' THEN 1 ELSE NULL END) AS counts, section_code, section_name
                        FROM gradereport gr
                        JOIN student s ON s.stud_id = gr.stud_id
                        JOIN enrollment e ON e.stud_id = s.stud_id
                        JOIN observedvalues o ON o.report_id = gr.report_id
                        JOIN section USING (section_code)
                        WHERE gr.sy_id = '$sy_id' GROUP BY s.stud_id) AS conduct WHERE conduct.counts >= '$min';";
        $result = $this->query($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $section_code = $row['section_code'];
            $grd = $row['grd'];
            $data[$grd][$section_code]['section_name'] = $row['section_name'];
            $data[$grd][$section_code]['students'][] = ['id' => $row['stud_id'], 'name' => $row['name'], 'lrn' => $row['LRN'], 'sex' => ucwords($row['sex'])];
        }
        return $data;
    }

    public function getSpecificDiscParamters()
    {
        $data = [];
        $result = $this->query("SELECT * FROM specificdiscipline;");
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['award_code']] = ['desc' => $row["spec_descipline"], 'grd' => $row['min_grd']];
        }
        return $data;
    }

    public function listStudentAwardSelection($is_JSON = FALSE)
    {
        $data = [];
        // $sy_id = $_SESSION['sy_id'];
        $sy_id = 9;
        $query = "SELECT stud_id, LRN, CONCAT(last_name,', ', first_name,' ',COALESCE(middle_name,''),' ',COALESCE(ext_name, '')) AS name, 
                enrolled_in AS grd, curr_code, prog_code, section_code, section_name FROM enrollment AS e
                JOIN student s USING (stud_id)
                JOIN section USING (section_code)
                JOIN schoolyear sy ON e.sy_id=sy.sy_id WHERE e.sy_id = '$sy_id' AND e.valid_stud_data = 1 ";
        // $query .= (isset($_GET['curr_code']) ? "AND curr_code = '{$_GET['curr_code']}' " : "");
        $query .= (isset($_GET['prog_code']) ? "AND prog_code = '{$_GET['prog_code']}' " : "");
        $query .= (isset($_GET['grd']) ? "AND enrolled_in = '{$_GET['grd']}' " : "");
        $query .= (isset($_GET['section_code']) ? "AND section_code = '{$_GET['section_code']}';" : ";");
        $result = $this->query($query);

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['stud_id'];
            $data[] = [
                'id'            => $id,
                'lrn'           => $row['stud_id'],
                'name'          => $row['name'],
                'grd'           => $row['grd'],
                'curr_code'     => $row['curr_code'],
                'prog_code'     => $row['prog_code'],
                'section_code'  => $row['section_code'],
                'section_name'  => $row['section_name'],
                'action'        => "<div class='d-flex justify-content-center'>
                                        <button data-id='$id' class='btn btn-sm btn-success action' data-type='add'>Add</button>
                                    </div>"
            ];
        }

        if ($is_JSON) {
            echo json_encode($data);
            return;
        }
        return $data;
    }
}

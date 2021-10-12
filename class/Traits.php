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
    public function listSubjects($tbl)
    {
        $subjectList = [];

        $shared_sub = ($tbl == "archived_subject") ? 'archived_sharedsubject' : 'sharedsubject';

        $queryOne = (!isset($_GET['prog_code']))
            ? "SELECT * FROM $tbl;"
            : "SELECT * FROM $tbl WHERE sub_code 
               IN (SELECT sub_code FROM $shared_sub
               WHERE prog_code='{$_GET['prog_code']}')
               UNION SELECT * FROM $tbl WHERE sub_type='CORE';";

        $resultOne = mysqli_query($this->db, $queryOne);

        while ($row = mysqli_fetch_assoc($resultOne)) {
            $code = $row['sub_code'];
            $sub_type = $row['sub_type'];
            $subject =  new Subject($code, $row['sub_name'], $row['for_grd_level'], $row['sub_semester'], $sub_type);

            if ($sub_type == 'specialized') {
                $resultTwo = mysqli_query($this->db,  "SELECT prog_code FROM sharedsubject WHERE sub_code='$code';");
                $rowTwo = mysqli_fetch_row($resultTwo);
                $subject->set_program($rowTwo[0] ?? "");
            }
            $subjectList[] = $subject;
        }

        return $subjectList;
    }

    public function listSubjectsJSON()
    {
        echo json_encode($this->listSubjects('subject'));
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
        while($row = mysqli_fetch_assoc($result)) {
            switch($row['status']) {
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
                $filename = time() ."_".uniqid("", true).".$fileType";  // 234236513_12323.png
                $fileDestination = "uploads/faculty/$filename"; // ex. uploads/faculty/234236513_12323.png
                if (isset($_POST["current_image_path"])) { // if it exists, page is from edit form
                    $current_img_path = $_POST["current_image_path"];
                    if (strlen($current_img_path) != 0) { // if more than 0, there exists an image
                        unlink("../".$current_img_path);                                 // delete current image
                    }
                }
                move_uploaded_file($imgContent, "../".$fileDestination);
            } else {
                $statusMsg["imageExt"] = "Sorry, only JPG, JPEG, & PNG files are allowed to upload.";
                http_response_code(400);
                die(json_encode($statusMsg));
            }
        }

        switch($user_type) {
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

        if ($action == 'add' AND $user_type === 'AD') {
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
        $result = $this->prepared_select("SELECT * FROM subject WHERE sub_code IN (SELECT sub_code FROM subjectfaculty WHERE teacher_id=?);", [$id], "i");
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
    public function getAdvisoryClass($sy = null) {
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
            ."LEFT JOIN faculty AS f USING (teacher_id) "
            ."WHERE teacher_id != '$teacher_id' "
            ."OR teacher_id IS NULL ORDER BY teacher_id;";
        $result = mysqli_query($this->db, $query);
        $section_list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $teacher_id = $row['teacher_id'];
            $name = $teacher_id ? "T. {$row['last_name']}, {$row['first_name']} {$row['middle_name']} {$row['ext_name']}" : "";
            $section_list[] = ["section_code" => $row['section_code'],
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
        echo($teacher_id);
        $query = "SELECT sc.sub_class_code, sc.section_code, sys.sub_code, sc.teacher_id, s.sub_name, s.sub_type, se.grd_level, s.sub_semester, se.sy_id, se.section_name 
                  FROM subjectclass AS sc JOIN sysub AS sys USING (sub_sy_id) 
                  JOIN subject AS s USING (sub_code) 
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
        return $handled_sub_classes;
    }

    public function listAttendance($is_JSON = FALSE)
    {
        $attendance = [];
        $result = $this->query("SELECT stud_id, report_id, CONCAT(last_name,', ', first_name, ' ', middle_name, ' ', COALESCE(ext_name, '')) 
                    AS name, no_of_present, no_of_days AS present, no_of_absent AS absent, no_of_tardy AS tardy FROM student s 
                    JOIN enrollment e USING (stud_id)
                    JOIN attendance a USING (stud_id) WHERE section_code = '{$_GET['class']}'
                    AND month = '{$_GET['month']}'");
        while ($row = mysqli_fetch_assoc($result)) {
            $report_id = $row['report_id'];
            $attendance[] = [
                'stud_id' => $row['stud_id'],
                'name'    => $row['name'],
                'present_e' => "<input name='data[{$report_id}][present]' class='form-control form-control-sm text-center mb-0 number' readonly value='{$row['present']}'>",
                'absent_e'  => "<input name='data[{$report_id}][absent]' class='form-control form-control-sm text-center mb-0 number' readonly value='{$row['absent']}'>",
                'tardy_e'   => "<input name='data[{$report_id}][tardy]' class='form-control form-control-sm text-center mb-0 number' readonly value='{$row['tardy']}'>",
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

    public function changeAttendance() {
        foreach($_POST['data'] as $id => $value) { // $id = report_id
            $this->prepared_query(
                "UPDATE attendance SET no_of_present=?, no_of_absent=?, no_of_tardy=? WHERE report_id = ? AND month=?;",
                [$value['present'], $value['absent'], $value['tardy'], $id, $_POST['month']],
                "iiiis"
            );
        }
    }
}


/** Enrollment Methods */
trait Enrollment
{
    public function enroll()
    {
        // session_start();
        $school_year = 15;
        echo "Add student starting...<br>";
        $student_id = $this->addStudent();
        echo "Add student finished.<br>";

        // School information
        echo "Add School info starting...<br>";

        $school_info = [$_POST['track'],  $_POST['program']];
        [$track, $program] = $this->preprocessData($school_info);
        $this->prepared_query(
            "INSERT INTO enrollment (date_of_enroll, valid_stud_data, enrolled_in, stud_id, sy_id, curr_code, prog_code) "
            ."VALUES (NOW(), 0, ?, ?, ?, ?, ?);",  // null for date_first_attended, and section code
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

        echo 'Adding promotion record...<br>';
        $this->prepared_query(
            "INSERT INTO promotion (school_id, school_name, school_add, last_grd_lvl_comp, last_school_yr_comp, "
            ."balik_aral, grd_to_enroll, last_gen_ave, semester, stud_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
//                $_POST['school-id'],
                NULL, # school_id
                $_POST['school-name'],
                $_POST['school-address'],
                $_POST['last-grade-level'],
                $_POST['last-sy'],

                $_POST['balik'],
                $_POST['grade-level'],
                $_POST['general-average'],
                $_POST['semester'] ?? 1, # default is 1
                $student_id
            ],
            // 10 params
            "issis". "iiiii" 
        );
        echo 'Added promotion record...<br>';
    }

    public function getEnrollees()
    {


        
        $limit = $_GET['limit']; // 0
        $offset = $_GET['offset']; // 25
        $query = "SELECT CONCAT(sy.start_year, ' - ', sy.end_year) AS SY, e.stud_id, LRN, CONCAT(s.last_name,', ', s.first_name,' ',s.middle_name,' ',COALESCE(s.ext_name, '')) AS name, "
            ."e.date_of_enroll, e.enrolled_in, e.curr_code, CASE WHEN e.valid_stud_data = 1 THEN 'Enrolled' WHEN e.valid_stud_data = 0 THEN 'Pending' ELSE 'Cancelled' END AS status FROM enrollment AS e "
            ."JOIN student AS s USING (stud_id) "
            ."JOIN schoolyear AS sy ON e.sy_id=sy.sy_id ";



        /**
         * Returns the sort query string in line with the received
         * sort value (column name in the database) and order value (ASC / DESC).
         * @return string Sort Query
         */
        function get_sort_query(): string
        {
            if(isset($_GET['sort'])) {
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
        if(strlen(trim($_GET['search'])) > 0) {
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

        if ($_GET['sy'] !== '*') {
            $filter_query[] = " sy.sy_id ='{$_GET['sy']}'";
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

        $query .= (strlen($search_query) > 0)
            ? " WHERE ".$search_query." AND (".$filter_qr.")"
            : ((strlen($filter_qr) > 0)
                ? " WHERE ".$filter_qr : "");


        $query .= get_sort_query();
        $result = $this->query($query);
        $num_rows_not_filtered = $result->num_rows;


        $query .= " LIMIT $limit";
        $query .= " OFFSET $offset";

        // echo ($query);

        $result = $this->query($query);
        $records = array();

        while ($row = mysqli_fetch_assoc($result)) { // MYSQLI_ASSOC allows to retrieve the data through the column name
            $records[] = new Enrollee(
                $row['SY'], $row['LRN'], $row['name'],
                $row['date_of_enroll'], $row['enrolled_in'],
                $row['curr_code'], $row['status'], $row['stud_id']
            );
        }


        $output = new stdClass();
        $output->total = $num_rows_not_filtered;
        $output->totalNotFiltered = $num_rows_not_filtered;
        $output->rows = $records;
        echo json_encode($output);


        //        $result = $this->query(
//            "SELECT CONCAT(sy.start_year, ' - ', sy.end_year) AS SY, e.stud_id, LRN, CONCAT(s.last_name,', ', s.first_name,' ',s.middle_name,' ',COALESCE(s.ext_name, '')) AS name, "
//            ."e.date_of_enroll, e.enrolled_in, e.curr_code, CASE WHEN e.valid_stud_data = 1 THEN 'Enrolled' WHEN e.valid_stud_data = 0 THEN 'Pending' ELSE 'Cancelled' END AS status FROM enrollment AS e "
//            ."JOIN student AS s USING (stud_id) "
//            ."JOIN schoolyear AS sy ON e.sy_id=sy.sy_id;"
//        );
//        echo json_encode($result);
//        $enrollees = [];
//        while ($row = mysqli_fetch_assoc($result)) {
//            $enrollees[] = new Enrollee(
//                $row['SY'], $row['LRN'], $row['name'],
//                $row['date_of_enroll'], $row['enrolled_in'],
//                $row['curr_code'], $row['status'], $row['stud_id']
//            );
//
//        }
//
//
//        return $enrollees;
    }

    public function getEnrollFilters():array
    {
        $filter = [];

        # Get school year
//        $result = $this->query("SELECT sy_id, CONCAT(start_year,' - ', end_year) as sy FROM schoolyear;");
        $result = $this->query("SELECT sy_id, CONCAT(start_year,' - ', end_year) as sy FROM schoolyear GROUP BY sy;");
        $school_years = [];
        while($row = mysqli_fetch_row($result)) {
            $school_years[$row["0"]] = $row["1"];
        }
        $filter['school_year'] = $school_years;


        # Get tracks
        $result = $this->query("SELECT curr_code, curr_name FROM curriculum;");
        $programs = [];
        while($row = mysqli_fetch_row($result)) {
            $programs[$row["0"]] = $row["1"];
        }
        $filter['tracks'] = $programs;

        # Get strands
        $result = $this->query("SELECT prog_code, description FROM program;");
        $programs = [];
        while($row = mysqli_fetch_row($result)) {
            $programs[$row["0"]] = $row["1"];
        }
        $filter['programs'] = $programs;

        # Prepare year level
        $filter['year_level'] = ['11', '12'];

        $filter['status'] = ["0" => "Pending", "1" => "Enrolled", "2" => "Cancelled"];

        return $filter;
    }


    private function in_multi_array(string $string, array $array): bool
    {
        $bool = false;
        foreach($array as $id => $value) {
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
        while($row = mysqli_fetch_assoc($result)) {
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

    public function validateImage($file, $file_size):array
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
            $file_type = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array($file_type, Administration::ALLOWED_IMG_TYPES)) {
                echo "<br>Image is valid ... <br>";
                $profile_img = time() ."_".uniqid("", true).".$file_type";
//                $profile_img = file_get_contents($file['tmp_name']);
            } else {
                $statusInfo['status'] = $status;
                $statusInfo["imageExt"] = "Sorry, only JPG, JPEG, & PNG files are allowed to upload.";
                return $statusInfo;
            }
            $statusInfo['status'] = 'valid';
            $statusInfo['image'] = $profile_img;
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
        return array_map(function($e) {
            $e = trim($e);
            return  $e ?? NULL;
        }, $params);
    }

    public function addSection($grade_level, $prog_code, $stud_no, $letter, $sy, $sycs) {
        echo "$grade_level, $prog_code, $stud_no, $letter, $sy, $sycs";
        $section_name =  "$grade_level-{$letter}-{$prog_code}-Class";  // 11-A-ABM-Class
        $section_code = rand(10, 10000000);
        // echo "Added section: ".$section_name."<br>";
        // echo "Section code: ". $section_code;
        $this->prepared_query(
            "INSERT INTO section (section_code, section_name, grd_level, stud_no_max, stud_no, sy_id) VALUES (?, ?, ?, ?, ?, ?);",
            [$section_code, $section_name, $grade_level, 50, $stud_no, $sy],
            "ssiiii"
        );
        $this->prepared_query("INSERT INTO sectionprog (section_code, sycs_id) VALUES (?, ?);",
            [$section_code, $sycs],
            "si"
        );
    
 
        return $section_code;
    }

    /**
     *  Officially accept or reject enrollment request. 
     *  1.  If not valid, return.
     *      If valid, 
     *          1.1 Add student to a section and initialize subject classes.     
     *          1.2 Update enrollment attribute.
     */
    public function validateEnrollment($stud_id, $current_sy, $is_valid)
    {
        // session_start();
        // $stud_id = $_POST['stud_id'];
        // $is_valid = (isset($_POST['accept'])) ? 1
        //     : (isset($_POST['reject']) ? 2 : 0);

        # Step 1
        if (!$is_valid) {
            $this->query("UPDATE enrollment SET valid_stud_data='$is_valid' WHERE stud_id='$stud_id';"); // 110001
            return;
        }

        # query to join all school year table : SELECT sy.sy_id, syc_id, syc.curr_code, sycs_id, sycs.prog_code FROM schoolyear AS sy JOIN sycurriculum AS syc USING(sy_id) JOIN sycurrstrand AS sycs USING (syc_id);

        // $current_sy = 29;
        // $current_sy = $_SESSION['sy_id'];
        $enroll_detail = mysqli_fetch_assoc($this->query("SELECT sy_id, enrolled_in AS grade_level, prog_code FROM enrollment WHERE sy_id='$current_sy';"));
        $grade_level = $enroll_detail['grade_level'];
        $prog_code = $enroll_detail['prog_code'];


        # get school_year_curr_strand_id
        $sycs = mysqli_fetch_row($this->query("SELECT sycs_id FROM schoolyear 
                                               JOIN sycurriculum USING(sy_id) 
                                               JOIN sycurrstrand USING (syc_id) 
                                               WHERE prog_code='$prog_code' AND sy_id='$current_sy';"))[0];
        $section_result = $this->query("SELECT * FROM section JOIN sectionprog USING (section_code) 
                                        WHERE sycs_id = '$sycs' AND grd_level = '$grade_level' 
                                        ORDER BY stud_no DESC;");
        $sections = []; // 11 - A to 11 - D
        $avail_section = []; // 11 - D, 11 - E
        while($row = mysqli_fetch_array($section_result)) {
            $stud_no = $row['stud_no'];
            $stud_max_no = $row['stud_no_max'];
            $section = new Section (
                $row['section_code'],
                $row['sy_id'],
                $row['section_name'],
                $row['grd_level'],
                $stud_max_no,
                $stud_no,
                $row['teacher_id']
            );
            $sections[] = $section;
            if ($stud_no < $stud_max_no) { // 0 < 50
                $avail_section[] = $section;
            }
        }    

        $selected_section_code = 0;
        # if no available section, create new section
        if (count($avail_section) === 0) {
            echo "Adding new section ... <br>";
            $alphabet = range('A', 'Z'); // "A, B, C, "
            $letter = $alphabet[count($sections)];
            // echo "$letter <br>";
            echo "$grade_level, $prog_code, $stud_no, $letter, $current_sy, $sycs <br>";
            $selected_section_code = $this->addSection($grade_level, $prog_code, 1, $letter, $current_sy, $sycs);
        } else {
            # select the very first available section // 11-D
            $selected_section = $avail_section[0];
            $selected_section_code = $selected_section->get_code();
            # update section student no.
            $new_stud_no = $selected_section->increase_stud_no(1); // current stud 2 + 1 = 3
            $this->query("UPDATE section SET stud_no = '$new_stud_no' WHERE section_code = '$selected_section_code';");
        }
        $this->query("UPDATE enrollment SET valid_stud_data='$is_valid', section_code='$selected_section_code' WHERE stud_id='$stud_id';");

        //call initializeGrades
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

        function prepareParentData ($type): array
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
                # Upload image
                $imgContent = $image['image'];
                $fileDestination = "uploads/student/{$_SESSION['sy_id']}/$imgContent";
                // for editing
//                if (isset($_POST["current_image_path"])) { // if it exists, page is from edit form
//                    $current_img_path = $_POST["current_image_path"];
//                    if (strlen($current_img_path) != 0) { // if more than 0, there exists an image
//                        unlink("../".$current_img_path);                                 // delete current image
//                    }
//                }
                $params[] = $fileDestination;
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

    public function initializeGrades()
    {
        $stud_id = $_POST['stud_id'];// with the assumption na may stud id kong san man to matatawag HAHAHAH

        // 1. initialize gradereport
        $this->prepared_query("INSERT INTO gradereport (stud_id) VALUES (?);", [$stud_id], 'i');

        //2. Retrieve report_id of student
        $report_id = mysqli_fetch_row(mysqli_query($this->db,  "SELECT report_id FROM gradereport WHERE stud_id=$stud_id;"));

        //3. Initialize classgrade
        //3.a. retrieve student class
        $result = $this->query("SELECT sub_class_code FROM subjectclass WHERE section_code IN (SELECT section_code FROM student WHERE stud_id=$stud_id)"); 
        
        //3.b for each class, create classgrade
        while($row = mysqli_fetch_assoc($result)) {
            $this->prepared_query("INSERT INTO classgrade (report_id, stud_id, sub_class_code) VALUES (?, ?, ?);", [$report_id, $stud_id, $row['sub_class_code']], 'iii'); 
            
        }

        //4. Initialize array of default observed value ids
        $values = $this->query("SELECT `value_id` FROM `values`"); 

        //4.a For each value_id, 
                    //for each quarter, create an observedvalue.                     
                    while($row = mysqli_fetch_assoc($values)) {
                        foreach(Administration::QUARTER as $quarter) {
                            $this->prepared_query("INSERT INTO `observedvalues`(`value_id`, `quarter`, `report_id`, `stud_id`) VALUES (?,?,?,?)", [$row['value_id'], $quarter, $report_id, $stud_id], 'siii'); 
                        }

                    }             
    }

}


trait Grade 
{

    public function getGrade() {
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
}


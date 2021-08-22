<?php 
    include ("../class/Administration.php");
    $admin = new Administration();
    $subjects = $admin->listSubjects();
    $departments = $admin->listDepartments();
    $state = $_GET['action'];
    
    $PROFILEPATH = "../assets/profile.png";
    $handledSubjects = "<td colspan='5'>No assigned subject</td>";
    $teacherIDInput = "";

    // Content
    if ($state == 'add') {
        $lastname = '';
        $firstname = '';
        $middlename = '';
        $extname = '';
        $cp_no = '';
        $email = '';
        $age = '';
        $sex="";
        $genderOption = "<option selected value='NULL'>-- Select gender --</option>"
                       ."<option value='f'>Female</option>"
                       ."<option value='m'>Male</option>";
        $birthdateInput = "<input type='date' class='form-control' name='birthdate' required>";
        $departmentOption = "";
        foreach($departments as $dep) {
            $departmentOption .= "<option value='$dep'>";
        }
        $department = "";
        $editGradesChecked = "";
        $enrollmentChecked = "";
        $awardReportChecked = "";
        $image = $PROFILEPATH;
        $finalBtn = "Submit";
    } else if ($state == 'edit') {
        $id = $_GET['id'];
        $faculty = $admin->getFaculty($id);
        $lastname = $faculty->get_last_name();
        $firstname = $faculty->get_first_name();
        $middlename = $faculty->get_middle_name();
        $extname = $faculty->get_ext_name();
        $cp_no = $faculty->get_cp_no();
        $email = $faculty->get_email();
        $age = $faculty->get_age();
        // $gender = $faculty->get_sex();
        $sex = $faculty->get_sex();
        $department = $faculty->get_department()[0];
        // $genderOption = "<option value='NULL'>-- Select gender --</option>"
        //                ."<option value='f' ". (($gender == 'Female') ? "selected" : ""). ">Female</option>"
        //                ."<option value='m' ". (($gender == 'Male') ? "selected" : "") .">Male</option>";
        $date = strftime('%Y-%m-%d', strtotime($faculty->get_birthdate()));
        $birthdateInput = "<input type='date' class='form-control' name='birthdate' value='$date' required> ";
        $departmentOption = "";
        foreach($departments as $dep) {
            $departmentOption .= "<option value='$dep'>";
        }
        $editGradesChecked =($faculty->get_enable_edit_grd() == 0) ? "" : "checked";
        $enrollmentChecked = ($faculty->get_enable_enroll() == 0) ? "" : "checked";
        $awardReportChecked = ($faculty->get_award_coor() == 0) ? "" : "checked";
        $image = is_null($faculty->get_id_photo()) ? $PROFILEPATH : $faculty->get_id_photo();
        $handledSubjectsList = $faculty->get_subjects();
        $handledSubjects = '';
        foreach ($handledSubjectsList as $sub) {
            $code = $sub->get_sub_code();
            $handledSubjects .= "<tr class='text-center'>
                <td scope='col'><input type='checkbox' value='{$code}' /></td>
                <td scope='col'><input type='hidden' name='subjects[]' value='{$code}'/>{$code}</td>
                <td scope='col'>{$sub->get_sub_name()}</td>
                <td scope='col'>{$sub->get_sub_type()}</td>
                <td scope='col'><button id='{$code}' class='remove-btn btn btn-sm btn-danger m-auto' title='Remove'><i class='bi bi-x-square'></i></button></td>
            </tr>";
        }
        $teacherIDInput = "<input type='hidden' name='teacher_id' value='$id'>";
        $finalBtn = "Save";
    }

    $camel_state = ucwords($state);    
?>
  
    <!-- HEADER -->
    <header>
        <!-- BREADCRUMB -->
        <nav aria-label='breadcrumb'>
            <ol class='breadcrumb'>
                <li class='breadcrumb-item'><a href='index.php'>Home</a></li>
                <li class='breadcrumb-item'><a href='facultyList.php'>Faculty</a></li>
                <li class='breadcrumb-item active'><?php echo $camel_state; ?> Faculty</li>
            </ol>
        </nav>
        <h3><?php echo $camel_state; ?> Faculty</h3>
        <h6 class='text-secondary'>Please complete the following:</h6>
    </header>
    <!-- CONTENT  -->
    <form id='faculty-form' method='POST' enctype='multipart/form-data'>
        <div class='form-row row'>
            <div class='form-group col-md-4'>
                <label for='lastname'>Last Name</label>
                <input type='text' value='<?php echo $lastname; ?>' class='form-control' id='lastname' name='lastname' placeholder='Last Name' required>
            </div>
            <div class='form-group col-md-4'>
                <label for='firstname'>First Name</label>
                <input type='text' value='<?php echo $firstname; ?>' class='form-control' id='firstname' name='firstname' placeholder='First Name' required>
            </div>
            <div class='form-group col-md-4'>
                <label for='middlename'>Middle Name</label>
                <input type='text' value='<?php echo $middlename; ?>' class='form-control' id='middlename' name='middlename' placeholder='Middle Name' required>
            </div>
        </div>
        <div class='form-row row'>
            <div class='form-group col-md-4'>
                <label for='extensionname'>Extension Name</label>
                <input type='text' value='<?php echo $extname; ?>' class='form-control' id='extensionname' name='extensionname' placeholder='Extension Name'>
            </div>
            <div class='form-group col-md-4'>
                <label for='cpnumber'>Cellphone No.</label>
                <input type='text' value='<?php echo $cp_no;?>' class='number form-control' id='cpnumber' name='cpnumber' placeholder='Cellphone No.'>
            </div>
            <div class='form-group col-md-4'>
                <label for='email'>Email</label>
                <input type='email' value='<?php echo $email; ?>' class='form-control' id='email' name='email' placeholder='Email'  required>
            </div>
        </div>
        <div class='form-row row'>
            <div class='form-group col-md-2'>
                <label for='age'>Age</label>
                <input value='<?php echo $age; ?>' class='number form-control' id='age' name='age' placeholder='Age' required>
            </div>
            <div class='form-group col-md-2'>
                <label for='sex'>Sex</label>
                <?php 
                $sexOpt = ["m" => "Male", "f" => "Female"];
                foreach($sexOpt as $id => $value) {
                    echo "<div class='form-check'>
                            <input class='form-check-input' type='radio' name='sex' id='$id' value='$id' ". (($sex == $value) ? "checked" : "").">
                            <label class='form-check-label' for='$id'>
                            $value
                            </label>
                        </div>";
                }
                ?>
            </div>
            <div class='form-group col-md-4'>
                <label for='birthdate'>Birthdate</label>

                <?php echo $birthdateInput; ?>
            </div>
            <div class='form-group col-md-4'>
                <label for='department'>Department</label>
                <!-- <select id='department' name='department' class='form-select form-select'> -->
                <input class='form-control' value='<?php echo $department; ?>' name='department' list='departmentListOptions' placeholder='Type to search or add...'>
                <datalist id='departmentListOptions'>
                    <?php echo $departmentOption; ?>
                </datalist>
                <!-- </select> -->
            </div>
        </div>
        <div class='form-row row'>
            <div class='form-group col-md-4'>
                <label for='facultyAccess'>Faculty Access</label>
                <div class='form-check'>
                    <input class='form-check-input' name='access[]' type='checkbox' value='editGrades' <?php echo $editGradesChecked; ?>>
                    <label class='form-check-label'>
                        Edit Grades
                    </label>
                </div>
                <div class='form-check'>
                    <input class='form-check-input' name='access[]' type='checkbox' value='canEnroll' <?php echo $enrollmentChecked; ?>>
                    <label class='form-check-label'>
                        Enrollment
                    </label>
                </div>
                <div class='form-check'>
                    <input class='form-check-input' name='access[]' type='checkbox' value='awardReport' <?php echo $awardReportChecked; ?>>
                    <label class='form-check-label'>
                        Award Report
                    </label>
                </div>
            </div>
            <div class='form-group col-md-5 d-flex flex-column'>
                <label for='photo' class='form-label'>Faculty ID Photo</label>
                <div class="image-preview-con">
                    <img id='resultImg' src='<?php echo $image; ?>' alt='Profile image' class='rounded-circle w-100 h-100'/>
                    <div class='edit-img-con text-center'>
                        <p role='button' class="edit-text opacity-0"><i class='bi bi-pencil-square me-2'></i>Edit</p>
                    </div>
                </div>
                <input id='upload' class='form-control form-control-sm' id='photo' name='image' type='file' accept='image/png, image/jpg, image/jpeg'>
            </div>
        </div>
        <br>
        <div class='collapse-table row card bg-light w-100 h-auto text-start mx-auto mt-4 rounded-3'>
            <div class='d-flex justify-content-between'>
                <h5 class='my-auto'>ASSIGNED SUBJECTS</h5>
                <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='Assign'>
            </div>
            <div id='assign-subj-table' class='collapse'><hr>
                <div class='overflow-auto' style='height: 300px;'>
                    <div class='d-flex mb-3 pt-1'>
                        <div class='my-auto'>
                            <a id='instruction' tabindex='0' class='btn btn-sm btn-light mx-1 rounded-circle shadow-sm ' role='button' data-bs-toggle='popover' data-bs-placement='right' data-bs-trigger='focus' title='Instruction' data-bs-content="Find the subject code to be assigned to the faculty, then click the '+ SUBJECT' button">
                                <i class='bi bi-info-circle'></i>
                            </a>
                        </div>
                        
                        <div class='flex-grow-1'>
                            <input class='form-control my-auto' list='subjectOptions' id='search-input' placeholder='Search subject code here ...'>
                            <datalist id='subjectOptions'>";
                                <?php
                                    foreach($subjects as $subject) {
                                        $code = $subject->get_sub_code();
                                        echo "<option value='$code' class='sub-option'>$code - {$subject->get_sub_name()}</option>";
                                    }
                                ?>
                            </datalist>
                        </div>
                        <div class='ms-1'>
                            <button class='add-subject btn btn-dark'><i class='bi bi-plus-lg me-1'></i> Subject</button>
                            <button class='remove-all-btn btn btn-outline-danger'><i class='bi bi-x-lg me-1'></i>Selected</button>
                        </div>
                    </div>
                    <table class='table table-bordered table-hover table-striped' style='height: auto;'>
                        <thead>
                            <tr class='text-center'>
                                <th scope='col'><input type='checkbox' /></th>
                                <th scope='col'>Code</th>
                                <th scope='col'>Subject Name</th>
                                <th scope='col'>Type</th>
                                <th scope='col'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id='emptyMsg' class='text-center'>
                                <?php echo $handledSubjects; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class='back-btn d-flex justify-content-end'> <?php echo $teacherIDInput ?? ""; ?>
            <input type='hidden' name='profile' value='faculty'>
            <input type='hidden' value='<?php echo $state; ?>' name='action'>
            <!-- <a href='' role='button' class='btn btn-secondary me-2' target='_self'>CANCEL</a> -->
            <input type='submit'value='<?php echo $finalBtn ?>' class='btn btn-success btn-space save-btn' name='submit'>
        </div>
    </form>
    <script type="text/javascript">
        let subjects = <?php echo json_encode($subjects);?>;
    </script>                                
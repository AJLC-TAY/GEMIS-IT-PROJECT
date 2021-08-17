<?php 
function getFacultyFormContent() {
    $admin = new Administration();
    $content = new stdClass();
    $subjects = $admin->listSubjects();
    $content->subjects = $subjects;
    $state = $_GET['state'];
    $PROFILEPATH = "../assets/profile.png";

    // Content
    if ($state == 'add') {
        $lastname = '';
        $firstname = '';
        $middlename = '';
        $extname = '';
        $cp_no = '';
        $email = '';
        $age = '';
        $genderOption = "<option selected value='NULL'>-- Select gender --</option>"
                       ."<option value='f'>Female</option>"
                       ."<option value='m'>Male</option>";
        $birthdateInput = "<input type='date' class='form-control' name='birthdate' required>";
        $departmentOption = "<option selected value='0'>-- Select department --</option>"
                           ."<option value=''></option>";
        $editGradesChecked = "";
        $enrollmentChecked = "";
        $awardReportChecked = "";
        $image = $PROFILEPATH;
        $handledSubjects = "<td colspan='5'>No assigned subject</td>";
        $finalBtn = "SUBMIT";
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
        $gender = $faculty->get_sex();
        $genderOption = "<option value='NULL'>-- Select gender --</option>"
                       ."<option value='f' ". (($gender == 'Female') ? "selected" : ""). ">Female</option>"
                       ."<option value='m' ". (($gender == 'Male') ? "selected" : "") .">Male</option>";
        $date = strftime('%Y-%m-%d', strtotime($faculty->get_birthdate()));
        $birthdateInput = "<input type='date' class='form-control' name='birthdate' value='$date'> required";
        $departmentOption = "<option selected value='NULL'>-- Select department --</option>"
                           ."<option value=''></option>";
        $editGradesChecked = ($faculty->get_enable_edit_grd() == 0) ? "" : "checked";
        $enrollmentChecked = ($faculty->get_enable_enroll() == 0) ? "" : "checked";;
        $awardReportChecked = ($faculty->get_award_coor() == 0) ? "" : "checked";;
        $image = is_null($faculty->get_id_photo()) ? $PROFILEPATH : $faculty->get_id_photo();
        $handledSubjects = "<td colspan='5'>No assigned subject</td>";
        $finalBtn = "SAVE";
    }

    $content->state = ucwords($state);
    $main = "<form id='faculty-form' action='action.php' method='POST' enctype='multipart/form-data'>
                <div class='form-row row'>
                    <div class='form-group col-md-4'>
                        <label for='lastname'>Last Name</label>
                        <input type='text' class='form-control' id='lastname' name='lastname' placeholder='Last Name' value='$lastname' required>
                    </div>
                    <div class='form-group col-md-4'>
                        <label for='firstname'>First Name</label>
                        <input type='text' class='form-control' id='firstname' name='firstname' placeholder='First Name' value='$firstname' required>
                    </div>
                    <div class='form-group col-md-4'>
                        <label for='middlename'>Middle Name</label>
                        <input type='text' class='form-control' id='middlename' name='middlename' placeholder='Middle Name' value='$middlename' required>
                    </div>
                </div>
                <div class='form-row row'>
                    <div class='form-group col-md-4'>
                        <label for='extensionname'>Extension Name</label>
                        <input type='text' class='form-control' id='extensionname' name='extensionname' placeholder='Extension Name' value='$extname'>
                    </div>
                    <div class='form-group col-md-4'>
                        <label for='cpnumber'>Cellphone No.</label>
                        <input type='number' class='form-control' id='cpnumber' name='cpnumber' placeholder='Cellphone No.' value='$cp_no'>
                    </div>
                    <div class='form-group col-md-4'>
                        <label for='email'>Email</label>
                        <input type='email' class='form-control' id='email' name='email' placeholder='Email' value='$email' required>
                    </div>
                </div>
                <div class='form-row row'>
                    <div class='form-group col-md-2'>
                        <label for='age'>Age</label>
                        <input type='number' class='form-control' id='age' name='age' placeholder='Age' value='$age' required>
                    </div>
                    <div class='form-group col-md-2'>
                        <label for='gender'>Gender</label>
                        <select id='gender' name='gender' class='form-select form-select' required>
                            $genderOption
                        </select>
                    </div>
                    <div class='form-group col-md-4'>
                        <label for='birthdate'>Birthdate</label>
                        $birthdateInput
                    </div>
                    <div class='form-group col-md-4'>
                        <label for='department'>Department</label>
                        <select id='department' name='department' class='form-select form-select'>
                            $departmentOption
                        </select>
                    </div>
                </div>
                <div class='form-row row'>
                    <div class='form-group col-md-4'>
                        <label for='facultyAccess'>Faculty Access</label>
                        <div class='form-check'>
                            <input class='form-check-input' name='access[]' type='checkbox' value='editGrades' $editGradesChecked>
                            <label class='form-check-label'>
                                Edit Grades
                            </label>
                        </div>
                        <div class='form-check'>
                            <input class='form-check-input' name='access[]' type='checkbox' value='enrollment' $enrollmentChecked>
                            <label class='form-check-label'>
                                Enrollment
                            </label>
                        </div>
                        <div class='form-check'>
                            <input class='form-check-input' name='access[]' type='checkbox' value='awardreport' $awardReportChecked>
                            <label class='form-check-label'>
                                Award Report
                            </label>
                        </div>
                    </div>
                    <div class='form-group col-md-5 d-flex flex-column'>
                        <label for='photo' class='form-label'>Faculty ID Photo</label>
                        <img id='resultImg' src='$image' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'/>
                        <input id='upload' class='form-control form-control-sm' id='photo' name='image' type='file' accept='image/png, image/jpg, image/jpeg'>
                    </div>
                </div>
                <br>
                <div class='collapse-table'>
                    <input class='btn btn-primary' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='ASSIGN SUBJECT'>
                    <div id='assign-subj-table' class='collapse mt-3'>
                        <div class='overflow-auto bg-white p-3 rounded-sm shadow-sm' style='height: 300px;'>
                            <table class='table table-bordered table-hover table-striped' style='height: auto;'>
                                <thead>
                                    <tr class='text-center'>
                                        <div class='d-flex'>
                                            <div class='flex-grow-1'>
                                                <input class='form-control' list='subjectOptions' id='search-input' placeholder='Search subject name to get subject code here ...'>
                                                <datalist id='subjectOptions'>";
                                                        foreach($subjects as $subject) {
                                                            $code = $subject->get_sub_code();
                                                            $main .= "<option value='$code' class='sub-option'>$code - {$subject->get_sub_name()}</option>";
                                                        }
                                                $main .= "</datalist>
                                            </div>
                                            <div class='ms-1'>
                                                <button class='add-subject btn btn-dark'><i class='bi bi-plus-lg me-1'></i> SUBJECT</button>
                                                <button class='remove-all-btn btn btn-outline-danger'><i class='bi bi-x-lg me-1'></i>SELECTED</button>
                                            </div>
                                        </div>
                                        <th scope='col'><input type='checkbox' /></th>
                                        <th scope='col'>CODE</th>
                                        <th scope='col'>SUBJECT NAME</th>
                                        <th scope='col'>TYPE</th>
                                        <th scope='col'>ACTION</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr id='emptyMsg' class='text-center'>
                                        $handledSubjects
                                    </tr>
                                </tbody>
                        </div>
                        </table>
                    </div>
                </div>
                <div class='back-btn d-flex justify-content-end'>
                    <input type='hidden' name='action' value='addFaculty'>
                    <!-- <a href='' role='button' class='btn btn-secondary me-2' target='_self'>CANCEL</a> -->
                    <input type='submit' class='btn btn-success btn-space save-btn' name='submit' value='$finalBtn'>
                </div>
            </form>";
    $content->main = $main;
    return $content;
}

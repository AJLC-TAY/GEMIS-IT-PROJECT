<?php
include("../class/Administration.php");
$admin = new Administration();
$subjects = $admin->listSubjects("subject");
$departments = $admin->listDepartments();
$state = $_GET['action'];

$PROFILEPATH = "../assets/profile.png";
$handled_subjects = "<td colspan='5'>No assigned subject</td>";
$current_teacher_id = 0;
$teacher_id_input = "";
$assigned_sub = [];
$assigned_sub_classes = [];
$sub_classes = [];

// Content
if ($state == 'add') {
    $last_name = '';
    $first_name = '';
    $middle_name = '';
    $ext_name = '';
    $cp_no = '';
    $email = '';
    $age = '';
    $sex = "";
    $gender_option = "<option selected value='NULL'>-- Select gender --</option>"
        . "<option value='f'>Female</option>"
        . "<option value='m'>Male</option>";
    $birthdate_input = "<input type='date' class='form-control' name='birthdate' required>";
    $department_option = "";
    foreach ($departments as $dep) {
        $department_option .= "<option value='$dep'>";
    }
    $department = "";
    $edit_grades_checked = "";
    $enrollment_checked = "";
    $award_report_checked = "";
    $image = $PROFILEPATH;
    $final_btn = "Submit";
} else if ($state == 'edit') {
    $current_teacher_id = $_GET['id'];
    $faculty = $admin->getFaculty($current_teacher_id);
    $last_name = $faculty->get_last_name();
    $first_name = $faculty->get_first_name();
    $middle_name = $faculty->get_middle_name();
    $ext_name = $faculty->get_ext_name();
    $cp_no = $faculty->get_cp_no();
    $email = $faculty->get_email();
    $age = $faculty->get_age();
    // $gender = $faculty->get_sex();
    $sex = $faculty->get_sex();
    $department = $faculty->get_department();
    // $genderOption = "<option value='NULL'>-- Select gender --</option>"
    //                ."<option value='f' ". (($gender == 'Female') ? "selected" : ""). ">Female</option>"
    //                ."<option value='m' ". (($gender == 'Male') ? "selected" : "") .">Male</option>";
    $date = strftime('%Y-%m-%d', strtotime($faculty->get_birthdate()));
    $birthdate_input = "<input type='date' class='form-control' name='birthdate' value='$date' required> ";
    $department_option = "";
    foreach ($departments as $dep) {
        $department_option .= "<option value='$dep'>";
    }
    $edit_grades_checked = ($faculty->get_enable_edit_grd() == 0) ? "" : "checked";
    $enrollment_checked = ($faculty->get_enable_enroll() == 0) ? "" : "checked";
    $award_report_checked = ($faculty->get_award_coor() == 0) ? "" : "checked";
    $image = is_null($faculty->get_id_photo()) ? $PROFILEPATH : $faculty->get_id_photo();
    $handled_subjects_list = $faculty->get_subjects();
    $handled_subjects = '';
    foreach ($handled_subjects_list as $sub) {
        $code = $sub->get_sub_code();
        $handled_subjects .= "<tr class='text-center'>
                <td scope='col'><input type='checkbox' value='{$code}' /></td>
                <td scope='col'><input type='hidden' name='subjects[]' value='{$code}'/>{$code}</td>
                <td scope='col'>{$sub->get_sub_name()}</td>
                <td scope='col'>{$sub->get_sub_type()}</td>
                <td scope='col'><button id='{$code}' class='remove-btn btn btn-sm btn-danger m-auto' title='Remove'><i class='bi bi-x-square'></i></button></td>
            </tr>";
    }

    $sub_classes = $admin->listSubjectClasses($current_teacher_id);
    $assigned_sub = array_map(function($element) {
        return $element->get_sub_code();
    }, $handled_subjects_list);

    $assigned_sub_classes = $faculty->get_handled_sub_classes();

    $teacher_id_input = "<input type='hidden' name='teacher_id' value='$current_teacher_id'>";
    $final_btn = "Save";
}

$camel_state = ucwords($state);
?>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class='breadcrumb-item'><a href='index.php'>Home</a></li>
            <li class='breadcrumb-item'><a href='faculty.php'>Faculty</a></li>
            <li class='breadcrumb-item active'><?php echo $camel_state; ?> Faculty</li>
        </ol>
    </nav>
    <h3><?php echo $camel_state; ?> Faculty</h3>
    <h6 class='text-secondary'>Please complete the following:</h6>
</header>
<!-- CONTENT  -->
<form id='faculty-form' method='POST' action='action.php' enctype='multipart/form-data'>
    <div class='form-row row'>
        <div class='form-group col-md-4'>
            <label for='lastname'>Last Name</label>
            <input type='text' value='<?php echo $last_name; ?>' class='form-control' id='lastname' name='lastname' placeholder='Last Name' required>
        </div>
        <div class='form-group col-md-4'>
            <label for='firstname'>First Name</label>
            <input type='text' value='<?php echo $first_name; ?>' class='form-control' id='firstname' name='firstname' placeholder='First Name' required>
        </div>
        <div class='form-group col-md-4'>
            <label for='middlename'>Middle Name</label>
            <input type='text' value='<?php echo $middle_name; ?>' class='form-control' id='middlename' name='middlename' placeholder='Middle Name' required>
        </div>
    </div>
    <div class='form-row row'>
        <div class='form-group col-md-4'>
            <label for='extensionname'>Extension Name</label>
            <input type='text' value='<?php echo $ext_name; ?>' class='form-control' id='extensionname' name='extensionname' placeholder='Extension Name'>
        </div>
        <div class='form-group col-md-4'>
            <label for='cpnumber'>Cellphone No.</label>
            <input type='text' value='<?php echo $cp_no; ?>' class='number form-control' id='cpnumber' name='cpnumber' placeholder='Cellphone No.'>
        </div>
        <div class='form-group col-md-4'>
            <label for='email'>Email</label>
            <input type='email' value='<?php echo $email; ?>' class='form-control' id='email' name='email' placeholder='Email' required>
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
            foreach ($sexOpt as $id => $value) {
                echo "<div class='form-check'>
                            <input class='form-check-input' type='radio' name='sex' id='$id' value='$id' " . (($sex == $value) ? "checked" : "") . ">
                            <label class='form-check-label' for='$id'>
                            $value
                            </label>
                        </div>";
            }
            ?>
        </div>
        <div class='form-group col-md-4'>
            <label for='birthdate'>Birthdate</label>

            <?php echo $birthdate_input; ?>
        </div>
        <div class='form-group col-md-4'>
            <label for='department'>Department</label>
            <!-- <select id='department' name='department' class='form-select form-select'> -->
            <input class='form-control' value='<?php echo $department; ?>' name='department' list='departmentListOptions' placeholder='Type to search or add...'>
            <datalist id='departmentListOptions'>
                <?php echo $department_option; ?>
            </datalist>
            <!-- </select> -->
        </div>
    </div>
    <div class='form-row row'>
        <div class='form-group col-md-4 d-flex flex-column'>
            <label for='photo' class='form-label'>Faculty ID Photo</label>
            <div class="image-preview-con">
                <img id='resultImg' src='<?php echo $image; ?>' alt='Profile image' class='rounded-circle w-100 h-100' />
                <div class='edit-img-con text-center'>
                    <p role='button' class="edit-text opacity-0"><i class='bi bi-pencil-square me-2'></i>Edit</p>
                </div>
            </div>
            <input id='upload' class='form-control form-control-sm' id='photo' name='image' type='file' accept='image/png, image/jpg, image/jpeg'>
        </div>
        <div class='form-group col-md-4'>
            <label for='facultyAccess'>Faculty Access</label>
            <div class='form-check'>
                <input class='form-check-input' name='access[]' type='checkbox' value='editGrades' <?php echo $edit_grades_checked; ?>>
                <label class='form-check-label'>
                    Edit Grades
                </label>
            </div>
            <div class='form-check'>
                <input class='form-check-input' name='access[]' type='checkbox' value='canEnroll' <?php echo $enrollment_checked; ?>>
                <label class='form-check-label'>
                    Enrollment
                </label>
            </div>
            <div class='form-check'>
                <input class='form-check-input' name='access[]' type='checkbox' value='awardReport' <?php echo $award_report_checked; ?>>
                <label class='form-check-label'>
                    Award Report
                </label>
            </div>
        </div>
    </div>
    <br>
    <!-- SUBJECT CLASS -->
    <div class='collapse-table row card bg-light w-100 h-auto text-start mx-auto rounded-3'>
        <div class='d-flex justify-content-between'>
            <h5 class='my-auto'>SUBJECT CLASS</h5>
<!--            <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#sc-class-con' type='button' value='Assign'>-->
        </div>
<!--        <div id='sc-class-con' class='collapse mt-3'>-->
        <div id='sc-class-con' class='mt-3'>
            <div class="d-flex justify-content-between mb-3">
                <!-- SEARCH BAR - SUBJECT CLASS -->
                <form>
                    <div class="form-group d-flex flex-grow-1 me-3" >
                        <input id="search-assigned-sc-input" type="search" class="form-control mb-0 me-1 form-control-sm" placeholder="Search subject here">
                        <input type="reset" data-input="#search-sc-input" class='clear-table-btn btn btn-sm btn-dark shadow' value='Clear'>
                    </div>
                </form>
                <span><button id='add-sc-option' class='btn btn-sm shadow'>Add subject class</button></span>
            </div>
            <table id='assigned-sc-table' class="table-striped table-sm">
                <thead>
                    <div class="d-flex jusitify-content-end mb-3"><button class="unassign-selected-btn btn btn-sm btn-danger">Unassign Selected</button></div>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th scope='col' data-width="200" data-align="center" data-field="sub_class_code">SC Code</th>
                        <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="section_name">Section Name</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="section_code">Section Code</th>
                        <th scope='col' data-width="300" data-halign="center"  data-align="left" data-sortable="true" data-field="sub_name">Subject Name</th>
<!--                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="sub_type">Type</th>-->
                        <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="for_grd_level">Grade Level</th>
                        <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- SUBJECT CLASS END -->
    <!-- ASSIGN SUBJECTS -->
    <div class='collapse-table row card bg-light w-100 h-auto text-start mx-auto rounded-3 mt-4'>
        <div class='d-flex justify-content-between'>
            <h5 class='my-auto'>ASSIGNED SUBJECTS</h5>
<!--            <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='Assign'>-->
        </div>
<!--        <div id='assign-subj-table' class='collapse'>-->
        <div id='assign-subj-table'>
            <p class='text-secondary'><small>Check subjects to be assigned to the faculty. Uncheck to unassign.</small></p>

            <table id="subject-table" class="table-sm">
                <thead class='thead-dark'>
                    <div class="d-flex justify-content-between mb-1">
                        <!-- SEARCH BAR - SUBJECTS -->
                        <span class="flex-grow-1 me-3">
                                <input id="search-sub-input" type="search" class="form-control form-control-sm" placeholder="Search subject here">
                            </span>
                        <span><button class='clear-table-btn btn btn-dark btn-sm shadow-sm'>Clear</button></span>
                    </div>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th scope='col' data-width="200" data-align="center" data-field="sub_code">Code</th>
                        <th scope='col' data-width="400" data-halign="center" data-align="left" data-sortable="true" data-field="sub_name">Subject Name</th>
                        <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="sub_type">Subject Type</th>
                        <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="for_grd_level">Grade Level</th>
                        <!-- <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th> -->
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <!-- ASSIGN SUBJECTS END -->

    <div class='back-btn d-flex justify-content-end'> <?php echo $teacher_id_input ?? ""; ?>
        <input type='hidden' name='profile' value='faculty'>
        <input type='hidden' value='<?php echo $state; ?>' name='action'>
        <!-- <a href='' role='button' class='btn btn-secondary me-2' target='_self'>CANCEL</a> -->
        <input type='submit' value='<?php echo $final_btn ?>' class='btn btn-success btn-space save-btn' name='submit'>
    </div>
</form>

<script type="text/javascript">
    let teacherID = <?php echo json_encode($current_teacher_id); ?>;
    let subjects = <?php echo json_encode($subjects); ?>;
    let assigned = <?php echo json_encode($assigned_sub); ?>;
    let subjectClasses = <?php echo json_encode($sub_classes); ?>;
    let assignedSubClasses = <?php echo json_encode($assigned_sub_classes); ?>;
</script>
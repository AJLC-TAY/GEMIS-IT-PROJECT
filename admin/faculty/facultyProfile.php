<?php
# session handling here
// session_start();
const STYLE_DISPLAY_NONE = "style='display: none'";
$_SESSION['user_type'] = 'FA';
# Determine what class to import basing on the user type
switch ($_SESSION['user_type']) {
    case 'AD':
        require_once("../class/Administration.php");
        $school = new Administration();
        $breadcrumb = "<li class='breadcrumb-item'><a href='faculty.php' target='_self'>Faculty</a></li>";
        break;
    case 'FA':
        require_once("../class/Faculty.php");
        $school = new FacultyModule();
        $breadcrumb = "";
        break;
}

$school_user = $school->getProfile("FA");
$advisory_class = $school->getAdvisoryClass();
$advisory_code = is_null($advisory_class) ? "" : $advisory_class["section_code"];
$advisory_get_variable = $advisory_code == "" ? "" : "&currentAdvisory=$advisory_code";
$current_teacher_id = $school_user->get_teacher_id();
$image = is_null($school_user->get_id_photo()) ? "../assets/profile.png" : $school_user->get_id_photo();
$display_style = STYLE_DISPLAY_NONE;
$section_list = $school->listSectionOption($current_teacher_id);
$no_match_display = count($section_list) == 0 ? "" : "d-none";
?>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <?php echo $breadcrumb; ?>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
    </nav>
    <!-- BREADCRUMB END -->
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="my-auto">Profile</h4>
        <div class="d-flex justify-content-center">
            <button id="deactivate-btn" class="btn btn-danger me-3" data-bs-toggle="modal" data-bs-target="#confirmation-modal">Deactivate</button>
            <a href="faculty.php?id=<?php echo $current_teacher_id; ?>&action=edit" role="button" class="btn link my-auto"><i class="bi bi-pencil-square me-2"></i>Edit</a>
        </div>
    </div>
</header>
<!-- HEADER END -->

<div class='container my-3'>
    <div class="card p-3 text-center">
        <nav id="myTab">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-link active" id="nav-gen-info-tab" data-bs-toggle="tab" data-bs-target="#gen-info" type="button" role="tab" aria-controls="gen-info" aria-selected="true">General Information</a>
                <a class="nav-link" id="nav-classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab" aria-controls="classes" aria-selected="false">Classes</a>
                <a class="nav-link" id="nav-subject-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab" aria-controls="subjects" aria-selected="false">Subjects</a>
            </div>
        </nav>
        <div class="tab-content" id="myTabContent">
            <!-- GENERAL INFORMATION -->
            <div class="tab-pane fade bg-white p-4 show active" id="gen-info" role="tabpanel" aria-labelledby="home-tab">
                <div class="row w-100 h-auto text-start mx-auto">
                    <!-- <h5>GENERAL INFORMATION</h5> -->
                    <!-- <hr> -->
                    <div class="row p-0">
                        <!-- PROFILE PICTURE -->
                        <div class="col-xl-5">
                            <img src="<?php echo $image; ?>" alt='Profile image' class='rounded-circle shadow border' style='width: 250px; height: 250px;'>
                            <p>Faculty ID: <?php echo $current_teacher_id; ?></p>
                        </div>
                        <!-- PROFILE PICTURE END -->
                        <!-- INFORMATION DETAILS -->
                        <div class="col-xl-7">
                            <div class="row">
                                <?php
                                $birthdate = $school_user->get_birthdate();
                                $birthdate = date("F j, Y", strtotime($birthdate));
                                $name = $school_user->get_name();
                                echo "<p>Name: $name<br>"
                                    ."Gender: {$school_user->get_sex()}<br>"
                                    ."Age: {$school_user->get_age()}<br>"
                                    ."Birthdate: {$birthdate}</p>";
                                ?>
                            </div>
                            <div class="row">
                                <h6><b>Contact Information</b></h6>
                                <?php echo "<p>Cellphone No.: {$school_user->get_cp_no()}<br>"
                                          ."Email: {$school_user->get_email()}</p>"; ?>
                            </div>
                            <!-- DEPARTMENT SECTION -->
                            <div id="dept-section" class="row pt-2 mb-2">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="my-auto">
                                        <h6 class='m-0 fw-bold'>Department
                                            <span class="badge"><button id='dept-edit-btn' class='btn btn-sm link'><i class='bi bi-pencil-square'></i></button></span>
                                        </h6>
                                    </div>
                                    <div id="dept-decide-con" class='my-auto' <?php echo $display_style; ?>>
                                        <button id='dept-cancel-btn' class='btn btn-sm btn-dark me-1'>Cancel</button>
                                        <input type="submit" form='dept-form' id='dept-save-btn' class='btn btn-sm btn-success' value="Save">
                                    </div>
                                </div>
                                <div class="dept-con">
                                    <?php
                                    $depOpt = $school->listDepartments();
                                    $departmentOption = "";
                                    foreach ($depOpt as $dep) {
                                        $departmentOption .= "<option value='$dep'>";
                                    }
                                    $department = $school_user->get_department();
                                    $deptExist = TRUE;
                                    if ($department == '') {
                                        $deptExist = FALSE;
                                        $department = 'No department set';
                                    }
                                    ?>
                                    <form id='dept-form'>
                                        <input type='hidden' name='teacher_id' value='<?php echo $current_teacher_id; ?>'>
                                        <input type='hidden' name='action' value='editDepartment'>
                                        <div class='d-flex-column mb-2'>
                                            <div class='d-flex'>
                                                <div class='flex-grow-1'>
                                                    <input id='dept-input' class='form-control m-0' value='<?php echo $department; ?>' name='department' list='departmentListOptions' placeholder='Type to search or add...' readonly>
                                                    <datalist id='departmentListOptions'>
                                                        <?php echo $departmentOption; ?>
                                                    </datalist>
                                                </div>
                                                <span class='m-auto'><button id='dept-clear-btn' class='btn btn-link text-danger w-auto ms-2 p-1' <?php echo $display_style; ?>><i class='bi bi-x-square-fill'></i></button></span>
                                            </div>
                                            <small class='dept-ins ms-1 text-secondary' <?php echo $display_style; ?>>Clear field to remove department</small>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- ROLE SECTION -->
                            <div id="role-section" class="row d-flex-column pt-2">
                                <!-- INITIAL FORM -->
                                <form id='role-form'>
                                    <input type="hidden" name="teacher_id" value="<?php echo $current_teacher_id; ?>">
                                    <input type="hidden" name="action" value="updateFacultyRoles">
                                </form>
                                <!-- ROLE HEADER -->
                                <div class="d-flex justify-content-between">
                                    <div class="my-auto">
                                        <h6 class='m-0 fw-bold'>Roles
                                            <span class="badge"><button id='role-edit-btn' class='btn btn-sm link'><i class='bi bi-pencil-square'></i></button></span>
                                        </h6>
                                    </div>
                                    <div id="role-decide-con" class='my-auto d-none' <?php // echo $display_style; 
                                                                                        ?>>
                                        <button id='role-cancel-btn' class='btn btn-sm btn-dark me-1'>Cancel</button>
                                        <input type="submit" form="role-form" id='role-save-btn' class='btn btn-sm btn-success' value="Save">
                                    </div>
                                </div>
                                <!-- ROLE HEADER END -->
                                <!-- ROLE CONTENT -->
                                <div id="role-tag-con">
                                    <?php
                                    $data = $school_user->get_access_data();
                                    $roles = $data['roles'];
                                    $rData = $data['data'];
                                    $rSize = $data['size'];

                                    $icon_display = 'd-none';
                                    foreach ($rData as $role) {
                                        echo "<div role='' class='role-to-delete-btn rounded border border-secondary d-inline-block m-1 py-1 pe-1 {$role['disp']}' data-value='{$role['value']}'>"
                                            ."<span class='ms-3 me-2'>{$role['desc']}</span>"
                                            ."<button class='btn btn-link text-danger btn-sm p-0 me-2 $icon_display'>"
                                                ."<i class='bi bi-x-square-fill '></i>"
                                            ."</button>"
                                        ."</div>";
                                    }

                                    $role_msg_display = (!$rSize) ? "" : "d-none";
                                    echo "<p id='role-empty-msg' class='text-center mt-3 mb-2 $role_msg_display'>No roles or access set</p>";
                                    ?>
                                </div>
                                <div id='role-option-tag-con' class='d-none'>
                                    <hr class='m-2 '>
                                    <div class="my-auto d-inline-block mx-2"><small>Options:</small></div>
                                    <?php
                                    foreach ($rData as $role) {
                                        $role['disp'] = $role['disp'] == '' ? 'd-none' : '';
                                        echo "<button data-value='{$role['value']}' class='btn rounded btn-sm btn-outline-success d-inline-block m-1 {$role['disp']}'>"
                                                ."<i class='bi bi-plus-square me-2'></i>{$role['desc']}"
                                            ."</button>";
                                    }
                                    ?>
                                </div>
                                <!-- ROLE CONTENT END -->
                            </div>
                        </div>
                        <!-- ROLE SECTION END -->
                        <!-- INFORMATION DETAILS END -->
                    </div>
                </div>
            </div>
            <!-- GENERAL INFORMATION END -->
            <!-- CLASSES -->
            <div class="tab-pane fade bg-white p-4" id="classes" role="tabpanel" aria-labelledby="classes-tab">
                <div class='row w-100 h-auto text-start mx-auto mt-3 '>
                    <!-- ADVISORY SECTION -->
                    <div id="adviser-section" class="p-3 w-100 border d-flex flex-column">
                        <!-- INITIAL FORM -->
                        <form id='adviser-form'>
                            <input type="hidden" name="teacher_id" value="<?php echo $current_teacher_id; ?>">
                            <input type="hidden" name="action" value="updateAdvisoryClass">
                        </form>
                        <!-- ADVISORY HEADER -->
                        <div class="d-flex justify-content-between mb-3">
                            <div class="my-auto ">
                                <h5 class='m-0 fw-bold'>ADVISORY CLASS
                                    <!-- <span class="badge"><button id='adviser-edit-btn' class='btn btn-sm link'><i class='bi bi-pencil-square'></i></button></span> -->
                                </h5>
                            </div>
                            <div id="adviser-decide-con" class='d-none my-auto'>
                                <button id='adviser-cancel-btn' class='btn btn-sm btn-dark me-1'>Cancel</button>
                                <button id='adviser-save-btn' class='btn btn-sm btn-success'>Save</button>
                            </div>
                        </div>
                        <div class="my-auto w-100">
                            <div class="form-group d-flex flex-column justify-content-between mb-3">
                                <label for="current-advisory" class="col-form-label">Current</label>
                                <?php
                                $advisory = ($advisory_class) ? "($advisory_code) {$advisory_class['section_name']}" : "No advisory class set";
                                echo "<span><a href='section.php?sec_code=$advisory_code' id='current-advisory' class='text-secondary'>$advisory</a><button data-bs-toggle='modal' data-bs-target='#advisory-modal' class='ms-5 form-control btn btn-sm btn-dark shadow w-auto my-auto'>Change</button></span>";
                                ?>
                            </div>
                        </div>
                        <!-- ADVISORY HEADER END -->
                        <!-- ADVISORY CONTENT -->
                        <div class="row p-0">
                            <!-- ADVISORY TABLE -->
                            <p>Advisory Class History</p>
                            <table id="advisory-class-table" data-url="getAction.php?data=advisoryClasses&id=<?php echo $current_teacher_id; ?><?php echo $advisory_get_variable; ?>" class="table-striped table-sm">
                                <thead class='thead-dark track-table'>
                                    <tr>
                                        <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field='sy'>SY</th>
                                        <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="section_code">Section Code</th>
                                        <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="section_name">Section Name</th>
                                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="section_grd">Grade Level</th>
                                        <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="stud_no">No. of Students</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- ADVISORY SECTION END -->
                    <!-- SUBJECT CLASS SECTION -->
                    <div id="subject-class-section" data-page="profile" class="mt-5 p-3 w-100 border">
                        <!-- SUBJECT CLASS HEADER -->
                        <div class="d-flex justify-content-between mb-3">
                            <div class="my-auto ">
                                <h5 class='m-0 fw-bold'>SUBJECT CLASS
                                    <!-- <span class="badge"><button id='adviser-edit-btn' class='btn btn-sm link'><i class='bi bi-pencil-square'></i></button></span> -->
                                </h5>
                            </div>
                            <div id="adviser-decide-con" class='d-none my-auto'>
                                <button id='adviser-cancel-btn' class='btn btn-sm btn-dark me-1'>Cancel</button>
                                <button id='adviser-save-btn' class='btn btn-sm btn-success'>Save</button>
                            </div>
                        </div>
                        <!-- SUBJECT CLASS HEADER END -->
                        <!-- SUBJECT CLASS CONTENT -->
                        <div id='sc-class-con' class='mt-3'>
                            <div class="d-flex justify-content-between mb-2">
                                <!-- SEARCH BAR -->
                                <span class="flex-grow-1 me-3">
                                    <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                                </span>
                                <div>
                                    <button id='add-sc-option' class='btn btn-sm btn-success'><i class="bi bi-plus me-2"></i>Add subject class</button>
                                    <button class="unassign-selected-btn btn btn-sm btn-outline-danger"><i class="bi bi-dash-circle me-2"></i>Unassign Selected</button>
                                </div>
                            </div>
                            <table id='assigned-sc-table' data-page="profile" class="table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th data-checkbox="true"></th>
                                        <th scope='col' data-width="200" data-align="center" data-field="sub_class_code">SC Code</th>
                                        <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="section_name">Section Name</th>
                                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="section_code">Section Code</th>
                                        <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="sub_name">Subject Name</th>
                                        <!--                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="sub_type">Type</th>-->
                                        <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="for_grd_level">Grade Level</th>
                                        <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- SUBJECT CLASS CONTENT END -->
                    </div>
                </div>
            </div>
            <!-- CLASSES END -->
            <!-- SUBJECTS -->
            <div class="tab-pane fade bg-white p-4" id="subjects" role="tabpanel" aria-labelledby="contact-tab">
                <div class='row w-100 h-auto text-start mx-auto mt-3 '>
                    <div class="d-flex justify-content-between p-0">
                        <h5 class="my-auto fw-bold">ASSIGNED SUBJECTS</h5>
                        <div class='edit-con my-auto'>
                            <a role="button" id='edit-as-btn' data-action='Edit handled subject' class='edit-as-btn btn link btn-sm' data-bs-toggle="modal" data-bs-target="#as-modal"><i class='bi bi-pencil-square me-2'></i></a>
                        </div>
                        <!-- <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='View'> -->
                    </div>

                    <!-- <div id='assign-subj-table' class='collapse'><hr> -->
                    <div id='subject-con' class=''>
                        <hr>
                        <input id="search-subject" type="text" class="form-control mb-4" placeholder="Search subject here ...">
                        <div class="assigned-sub-con list-group border">
                            <?php
                            $subjects = $school->listSubjects('subject');
                            $assigned_sub = $school_user->get_subjects();
                            echo "<div id='empty-as-msg' class='list-group-item " . (count($assigned_sub) > 0 ? "d-none" : "") . "' aria-current='true'>"
                                    ."<div class='d-flex w-100'>"
                                        ."<div class='mx-auto p-3 d-flex flex-column justify-content-center'>"
                                            ."<h6>No assigned subject</h6>"
                                            ."<button class='edit-as-btn btn btn-success btn-sm w-auto' data-action='Assign subject' data-bs-toggle='modal' data-bs-target='#as-modal'>Assign</button>"
                                        ."</div>"
                                    ."</div>"
                                ."</div>";

                            foreach ($assigned_sub as $subject) {
                                $type = $subject->get_sub_type();
                                $sub_code = $subject->get_sub_code();
                                echo "<a target='_blank' href='subject.php?sub_code=$sub_code' class='list-group-item list-group-item-action' aria-current='true'>"
                                    . "<div class='d-flex w-100 justify-content-between'>"
                                        . "<p class='mb-1'>{$subject->get_sub_name()}</p>"
                                        . "<small>$type</small>"
                                    . "</div>"
                                    . "<small class='mb-1 text-secondary'><b>{$subject->get_for_grd_level()}</b> | $sub_code</small>"
                                . "</a>";
                            }
                            $assigned_sub = array_map(function ($e) {
                                return $e->get_sub_code();
                            }, $assigned_sub)
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<!-- CONFIRMATION -->
<div id="confirmation-modal" class="modal fade" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Confirmation</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deactivate Teacher <?php echo $name; ?>?<br>
                <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </small>
            </div>
            <div class="modal-footer">
                <form id="deactivate-form" action="action.php">
                    <input type="hidden" name="id" value="<?php echo $current_teacher_id; ?>" />
                    <input type="hidden" name="action" value="deactivate" />
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" form="deactivate-form" class="submit btn btn-danger" value="Deactivate">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- CONFIRMATION END -->
<!-- UPDATE SUBJECT -->
<div id="as-modal" class="modal fade" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Change assigned subject</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class='text-secondary'><small>Check subjects to be assigned to the faculty. Uncheck to unassign.</small></p>
                <table id="subject-table" class="table-sm">
                    <thead class='thead-dark'>
                    <div class="d-flex justify-content-between mb-2">
                            <!-- SEARCH BAR -->
                            <span class="flex-grow-1 me-3">
                                <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                            </span>
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
            <div class="modal-footer">
                <form id="as-form" method="POST" action="action.php">
                    <input type="hidden" name="teacher_id" value="<?php echo $current_teacher_id; ?>" />
                    <input type="hidden" name="action" value="editSubject">
                    <button id='cancel-as-btn' class="close btn btn-outline-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" form="as-form" id='save-as-btn' class="submit btn btn-success" value="Save">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- UPDATE SUBJECT END -->
<!-- ADVISORY CHANGE -->
<div id="advisory-modal" class="modal fade" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Advisory Class</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="advisory-form" method="POST" action="action.php">
                    <?php
                    $editable = ($advisory_code ? "" : "disabled");
                    ?>
                    <input name="current-section" type="hidden" value="<?php echo $advisory_code; ?>" <?php echo $editable; ?>>
                    <input type="hidden" name="teacher-id" value="<?php echo $current_teacher_id; ?>" />
                    <input type="hidden" name="action" value="advisoryChange" />
                    <div id="section-opt-con" class="border p-3">
                        <p class="text-secondary"><small>Select one section to be assigned or switched</small></p>
                        <div class="d-flex justify-content-between mb-2">

                            <!-- SEARCH BAR -->
                            <span class="flex-grow-1 me-3">
                                <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm" type="button" id="section-filter" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-filter me-2"></i>Filter</button>
                                <ul class="dropdown-menu" aria-labelledby="section-filter">
                                    <li><a href="#" id="all-section-btn" class="dropdown-item">All</a></li>
                                    <li><a id="no-adv-btn" class="dropdown-item">No Adviser</a></li>
                                    <li><a id="with-adv-btn" class="dropdown-item">With Adviser</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="section-content position-relative">
                            <!-- SUB SPINNER -->
                            <div id="advisory-spinner" class="sub-spinner bg-white position-absolute start-0 end-0 bottom-0 top-0" style="z-index: 3;">
                                <div class="spinner-border m-auto" style="position: absolute; top: 0; right: 0; bottom: 0; left:0;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <!-- NO RESULTS MESSAGE -->
                            <div class="d-flex justify-content-center <?php echo $no_match_display; ?>" style="position: absolute; top: 0; right: 0; bottom: 0; left:0; z-index: 2;">
                                <p class="no-result-msg my-auto">No results found</p>
                            </div>


                            <ul class="list-group top-0 bottom-0 right-0 left-0 overflow-auto" id="section-list" style="z-index: 1; height: 250px;">
                                <?php
                                foreach ($section_list as $section) {
                                    $sect_code = $section["section_code"];
                                    $sect_name = $section["section_name"];
                                    $sect_grd = $section["section_grd"];
                                    $sect_adviser_id = $section["adviser_id"];
                                    $sect_adviser = $section["adviser_name"];
                                    $color_badge = "success";
                                    $availability = "available";

                                    if ($sect_adviser) {
                                        $color_badge = "warning";
                                        $availability = "unavailable";
                                    }
                                    echo "<li class='list-group-item'>"
                                        . "<div class='form-row row'>"
                                        . "<span class='col-1'><input id='$sect_code' class='form-check-input me-1' data-current-adviser='$sect_adviser_id' name='section' type='radio' value='$sect_code'></span>"
                                        . "<div class='section-info d-flex justify-content-between col-sm-6'>"
                                        . "<label for='$sect_code'>$sect_code - $sect_name </label>"
                                        . "<span class='text-secondary'>G$sect_grd</span>"
                                        . "</div>"
                                        . "<div class='section-status d-flex justify-content-between col-sm-5'>"
                                        . "<div class='teacher-con' title='Current class adviser'>$sect_adviser</div>"
                                        . "<span class='badge $availability'><div class='bg-$color_badge rounded-circle' style='width: 10px; height: 10px;'></div></span>"
                                        . "</div>"
                                        . "</div>"
                                        . "</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group my-3">
                        <input id="unassign-cb" type="checkbox" <?php echo ($advisory_code ? "" : "disabled"); ?> name="unassign" class="form-check-input me-1 shadow-sm">
                        <label for="unassign-cb">Unassign class to this faculty</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="close btn btn-outline-dark close-btn" data-bs-dismiss="modal">Cancel</button>
                <input type="submit" form="advisory-form" class="submit btn btn-success" value="Save">
            </div>
        </div>
    </div>
</div>
<!-- ADVISORY CHANGE END -->
<!-- MODAL END -->
<!-- TOAST -->
<div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
    <div id="toast-con" class="position-fixed d-flex flex-column-reverse " style="z-index: 99999; min-height: 50px; bottom: 20px; right: 25px;"></div>
</div>
<!-- TOAST END -->

<?php
$sub_classes = $school->listSubjectClasses($current_teacher_id);
$assigned_sub_classes = $school_user->get_handled_sub_classes();
?>
<script type="text/javascript">
    let teacherID = <?php echo json_encode($current_teacher_id); ?>;
    let roles = <?php echo json_encode($roles); ?>;
    let deptExist = <?php echo json_encode($deptExist); ?>;
    let subjects = <?php echo json_encode($subjects); ?>;
    let assigned = <?php echo json_encode($assigned_sub); ?>;
    let subjectClasses = <?php echo json_encode($sub_classes); ?>;
    let assignedSubClasses = <?php echo json_encode($assigned_sub_classes); ?>;
</script>
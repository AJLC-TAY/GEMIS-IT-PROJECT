<?php include_once("../inc/head.html"); 
    require_once("../class/Administration.php");
    $admin = new Administration();
    $_SESSION['userType'] = 'admin';
    $_SESSION['userID'] = 'alvin';

    $userType = ucwords( $_SESSION['userType']);
    $link = "faculty.php";
    $userProfile = $admin->getProfile("FA");
    $advisory_class = $admin->getAdvisoryClass();
    $advisory_code = $advisory_class["section_code"] ?: "";
    $id = $userProfile->get_teacher_id();
?>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><?php echo "<a href='$link' target='_self'>Faculty</a>"; ?></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
    </nav>
    <!-- BREADCRUMB END -->
</header>
<!-- HEADER END -->
<div class="d-flex justify-content-between align-items-center">
    <h4 class="my-auto">Profile</h4>
    <div class="d-flex justify-content-center">
        <button id="deactivate-btn" class="btn btn-danger me-3">Deactivate</button>
        <a href="faculty.php?id=<?php echo $id;?>&action=edit" role="button" class="btn link my-auto"><i class="bi bi-pencil-square me-2"></i>Edit</a>
    </div>
</div>

<div class='container my-3'>
    <div class="card p-3 text-center">
        <div class="">
            <nav id="myTab">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-gen-info-tab" data-bs-toggle="tab" data-bs-target="#gen-info" type="button" role="tab" aria-controls="gen-info" aria-selected="true">General Information</button>
                    <button class="nav-link" id="nav-classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab" aria-controls="classes" aria-selected="false">Classes</button>
                    <button class="nav-link" id="nav-subject-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab" aria-controls="subjects" aria-selected="false">Subjects</button>
                </div>
            </nav>
        </div>
        <div class="tab-content" id="myTabContent">
            <!-- GENERAL INFORMATION -->
            <div class="tab-pane fade bg-white p-4" id="gen-info" role="tabpanel" aria-labelledby="home-tab">
                <div class="row w-100 h-auto text-start mx-auto">
                    <!-- <h5>GENERAL INFORMATION</h5> -->
                    <!-- <hr> -->
                    <div class="row p-0">
                        <!-- PROFILE PICTURE -->
                        <div class="col-xl-5">
                            <?php 
                                $image = is_null($userProfile->get_id_photo()) ? "../assets/profile.png" : $userProfile->get_id_photo();
                                echo "<img src='$image' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'>";
                                echo "<p>Faculty ID: $id</p>";
                            ?> 
                        </div>
                        <!-- PROFILE PICTURE END -->
                        <!-- INFORMATION DETAILS -->
                        <div class="col-xl-7">
                            <div class="row">
                                <?php 
                                    $birthdate = $userProfile->get_birthdate();
                                    $birthdate = date("F j, Y", strtotime($birthdate)); 
                                    $name = $userProfile->get_name();
                                    echo "<p>Name: $name<br>
                                            Gender: {$userProfile->get_sex()}<br>
                                            Age: {$userProfile->get_age()}<br>
                                            Birthdate: {$birthdate}</p>"; 
                                ?>
                            </div>
                            <div class="row">
                                <h6><b>Contact Information</b></h6>
                                <?php echo "<p>Cellphone No.: {$userProfile->get_cp_no()}<br>
                                            Email: {$userProfile->get_email()}</p>"; ?>
                            </div>
                            <!-- DEPARTMENT SECTION -->
                            <div id="dept-section" class="row pt-2 mb-2">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="my-auto"><h6 class='m-0 fw-bold'>Department
                                        <span class="badge"><button id='dept-edit-btn' class='btn btn-sm link'><i class='bi bi-pencil-square'></i></button></span>
                                    </h6></div>
                                    <div id="dept-decide-con" class='d-none my-auto'>
                                        <button id='dept-cancel-btn' class='btn btn-sm btn-dark me-1'>Cancel</button>
                                        <button id='dept-save-btn' class='btn btn-sm btn-success'>Save</button>
                                    </div>
                                </div>
                                <div class="dept-con">
                                    <?php 
                                        $depOpt = $admin->listDepartments();
                                        $departmentOption = "";
                                        foreach($depOpt as $dep) {
                                            $departmentOption .= "<option value='$dep'>";
                                        }
                                        $department = $userProfile->get_department();
                                        $deptExist = TRUE;
                                        if ($department == '') {
                                            $deptExist = FALSE;
                                            $department = 'No department set';
                                        } 

                                            // foreach($departments as $department) {
                                            //     // echo "<button class='btn btn-outline-secondary btn-sm rounded-pill w-auto'>$department</button>"; 
                                            //     echo "<div class='rounded-pill border border-secondary d-inline-block me-1'><span class='mx-3'>$department</span></div>";
                                            // }
                                    ?>
                                    <form id='dept-form'>
                                        <input type='hidden' name='teacher_id' value='$id'>
                                        <input type='hidden' name='action' value='editDepartment'>
                                        <div class='d-flex-column mb-2'>
                                            <div class='d-flex'>
                                                <div class='flex-grow-1'>
                                                    <input id='dept-input' class='form-control m-0' value='<?php echo $department; ?>' name='department' list='departmentListOptions' placeholder='Type to search or add...' readonly>
                                                    <datalist id='departmentListOptions'>
                                                        <?php echo $departmentOption; ?>
                                                    </datalist>
                                                </div>
                                                <span class='m-auto'><button id='dept-clear-btn' class='btn btn-link text-danger w-auto ms-2 p-1 d-none'><i class='bi bi-x-square-fill'></i></button></span>
                                            </div>
                                            <small class='dept-ins ms-1 d-none text-secondary'>Clear field to remove department</small>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- ROLE SECTION -->
                            <div id="role-section" class="row d-flex-column pt-2">
                                <!-- INITIAL FORM -->
                                <form id='role-form'>
                                    <input type="hidden" name="teacher_id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="action" value="updateFacultyRoles">
                                </form>
                                <!-- ROLE HEADER -->
                                <div class="d-flex justify-content-between">
                                    <div class="my-auto"><h6 class='m-0 fw-bold'>Roles
                                        <span class="badge"><button id='role-edit-btn' class='btn btn-sm link'><i class='bi bi-pencil-square'></i></button></span>
                                    </h6></div>
                                    <div id="role-decide-con" class='d-none my-auto'>
                                        <button id='role-cancel-btn' class='btn btn-sm btn-dark me-1'>Cancel</button>
                                        <button id='role-save-btn' class='btn btn-sm btn-success'>Save</button>
                                    </div>
                                </div>
                                <!-- ROLE HEADER END -->
                                <!-- ROLE CONTENT -->
                                <div id="role-tag-con">
                                    <?php 
                                        function renderRoleHTML($role){
                                            $iconState = 'd-none';
                                            echo "<div role='' class='role-to-delete-btn rounded border border-secondary d-inline-block m-1 py-1 pe-1 {$role['disp']}' data-value='{$role['value']}'>
                                                        <span class='ms-3 me-2'>{$role['desc']}</span>
                                                        <button class='btn btn-link text-danger btn-sm p-0 me-2 $iconState'>
                                                            <i class='bi bi-x-square-fill '></i>
                                                        </button>
                                                    </div>";
                                        }
                                    
                                        
                                        $data = $userProfile->get_access_data();
                                        $roles = $data['roles'];
                                        $rData = $data['data'];
                                        $rSize = $data['size'];
                                        foreach($rData as $role) {
                                            renderRoleHTML($role);
                                        }
                                        $rolesMsg = (!$rSize) ? "" : "d-none";
                                        echo "<p id='role-empty-msg' class='text-center mt-3 mb-2 $rolesMsg'>No roles/access set</p>";
                                        // echo "
                                        // <div id='role-add-btn' class='btn-group dropend d-none'>
                                        //     <button type='button' class='btn btn-outline-success rounded-circle px-2 py-1' data-bs-toggle='dropdown' aria-expanded='false'>
                                        //         <i class='bi bi-plus'></i>
                                        //     </button>
                                        //     <ul id='role-dropdown' class='dropdown-menu'>
                                        //         <li><button class='dropdown-item' data-value='editGrades'>Edit Grade</button></li>
                                        //         <li><button class='dropdown-item' data-value='canEnroll'>Can Enroll</button></li>
                                        //         <li><button class='dropdown-item' data-value='awardReport'>Award Coordinator</button></li>
                                        //     </ul>
                                        // </div>";
                                    ?>
                                </div>
                                <div id='role-option-tag-con' class='d-none'><hr class='m-2 '>
                                    <div class="my-auto d-inline-block mx-2"><small>Options:</small></div>
                                    <?php 
                                        function renderRoleOptHTML($role){
                                            echo "<button data-value='{$role['value']}' class='btn rounded btn-sm btn-outline-success d-inline-block m-1 {$role['disp']}'>
                                                        <i class='bi bi-plus-square me-2'></i>{$role['desc']}
                                                    </button>";
                                        }
                                        foreach($rData as $role) {
                                            if ($role['disp'] == "") {
                                                $role['disp'] = "d-none";
                                            } else {
                                                $role['disp'] = "";
                                            }
                                            renderRoleOptHTML($role);
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
                    <div id="adviser-section" class="p-3 w-100 border">
                        <!-- INITIAL FORM -->
                        <form id='adviser-form'>
                            <input type="hidden" name="teacher_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="action" value="updateAdvisoryClass">
                        </form>
                        <!-- ADVISORY HEADER -->
                        <div class="d-flex justify-content-between mb-3">
                            <div class="my-auto "><h6 class='m-0 fw-bold'>ADVISORY CLASS
                                <!-- <span class="badge"><button id='adviser-edit-btn' class='btn btn-sm link'><i class='bi bi-pencil-square'></i></button></span> -->
                            </h6></div>
                            <div id="adviser-decide-con" class='d-none my-auto'>
                                <button id='adviser-cancel-btn' class='btn btn-sm btn-dark me-1'>Cancel</button>
                                <button id='adviser-save-btn' class='btn btn-sm btn-success'>Save</button>
                            </div>
                        </div>
                        <!-- ADVISORY HEADER END -->
                        <!-- ADVISORY CONTENT -->
                        <div class="row p-0">
                            <div class="col-md-7">
                                <p>Previous Classes</p>
                                <div class='overflow-auto' style="height: 250px;">
                                    <table class='table table-striped table-sm border'>
                                        <thead>
                                            <tr>
                                                <td>SY</td>
                                                <td>Section Code</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr> -->
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                            <tr>
                                                <td>Test</td>
                                                <td>wekrljwoeifjsdlk</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-5  d-flex align-items-center">
                                <div class="my-auto w-100">
                                    <div class="form-group">
                                        <label for="current-advisory" class="col-form-label fw-bold">Current</label>
                                        <!-- <div class="col-md-9"> -->
                                        <?php $advisory = ($advisory_class) ? "$advisory_code - {$advisory_class['section_name']}" : "No advisory class set"; 
                                            echo "<p id='current-advisory'>$advisory</p>";
                                        ?>
                                        <!-- </div> -->
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button id="advisory-change-btn" class="form-control btn btn-dark btn-sm w-auto">Change</button>
                                    </div>
                                </div>
                                <div class="d-flex-column d-none">
                                    <p>Sections:</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- <input id="no-advisers" type="radio" name="section" checked class="form-check-input" > -->
                                            <label for="no-advisers" class="form-check-label">With No Adviser</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="with-advisers" type="radio" name="section" class="form-check-input" >
                                            <label for="with-advisers" class="form-check-label">With Adviser</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                        </div>
                    </div>
                    <!-- ADVISORY SECTION END -->
                    <!-- SUBJECT CLASS SECTION -->
                    <div id="subject-class-section" class="mt-3 p-3 w-100 border">
                         <!-- SUBJECT CLASS HEADER -->
                         <div class="d-flex justify-content-between mb-3">
                            <div class="my-auto "><h6 class='m-0 fw-bold'>SUBJECT CLASS
                                <!-- <span class="badge"><button id='adviser-edit-btn' class='btn btn-sm link'><i class='bi bi-pencil-square'></i></button></span> -->
                            </h6></div>
                            <div id="adviser-decide-con" class='d-none my-auto'>
                                <button id='adviser-cancel-btn' class='btn btn-sm btn-dark me-1'>Cancel</button>
                                <button id='adviser-save-btn' class='btn btn-sm btn-success'>Save</button>
                            </div>
                        </div>
                        <!-- SUBJECT CLASS HEADER END -->
                    </div>
                </div>
            </div>
            <!-- CLASSES END -->
            <!-- SUBJECTS -->
            <div class="tab-pane fade bg-white p-4 show active" id="subjects" role="tabpanel" aria-labelledby="contact-tab" >
                <div class='row w-100 h-auto text-start mx-auto mt-3 '>
                    <div class="d-flex justify-content-between p-0">
                        <h6 class="my-auto fw-bold">ASSIGNED SUBJECTS</h6>
                        <div class='edit-con my-auto'>
                            <a role="button" id='edit-as-btn' data-action='Edit handled subject' class='edit-as-btn btn link btn-sm' data-bs-toggle="modal" data-bs-target="#as-modal"><i class='bi bi-pencil-square me-2'></i></a>
                        </div>
                        <!-- <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='View'> -->
                    </div>
                    
                    <!-- <div id='assign-subj-table' class='collapse'><hr> -->
                    <div id='subject-con' class=''><hr>
                        <input id="search-subject" type="text" class="form-control mb-4" placeholder="Search subject here ...">
                        <div class="assigned-sub-con list-group border">
                            <?php 
                                $subjects = $admin->listSubjects();
                                $assigned_sub = $userProfile->get_subjects();
                                echo "<div id='empty-as-msg' class='list-group-item ". (count($assigned_sub) > 0 ? "d-none" : "") ."' aria-current='true'>
                                    <div class='d-flex w-100'>
                                        <div class='mx-auto p-3 d-flex flex-column justify-content-center'>
                                            <h6>No assigned subject</h6>
                                            <button class='edit-as-btn btn btn-success btn-sm w-auto' data-action='Assign subject' data-bs-toggle='modal' data-bs-target='#as-modal'>Assign</button>
                                        </div>
                                    </div>
                                </div>";

                                foreach($assigned_sub as $subject) {
                                    $type = $subject->get_sub_type();
                                    $sub_code = $subject->get_sub_code();
                                    echo "<a target='_blank' href='subject.php?sub_code=$sub_code' class='list-group-item list-group-item-action' aria-current='true'>
                                            <div class='d-flex w-100 justify-content-between'>
                                                <p class='mb-1'>{$subject->get_sub_name()}</p>
                                                <small>$type</small>
                                            </div>
                                            <small class='mb-1 text-secondary'><b>{$subject->get_for_grd_level()}</b> | $sub_code</small>
                                        </a>";
                                }
                                $assigned_sub = array_map(function ($e) {
                                    return $e->get_sub_code();
                                }, $assigned_sub)
                            ?>
                        </div>
                           
                        <!-- <div class='overflow-auto' style='height: 300px;'> -->
                            <!-- <div class='finder-con d-flex mb-3 pt-1 d-none'> -->
                                <!-- INSTRUCTION -->
                                <!-- <div class="my-auto">
                                    <a id="instruction" tabindex="0" class="instruction btn btn-sm btn-light mx-1 rounded-circle " role="button" data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="focus" title="Instruction" data-bs-content="Find the subject code to be assigned to the faculty, then click the '+ SUBJECT' button">
                                        <i class="bi bi-info-circle"></i>
                                    </a>
                                </div> -->
                                <!-- INSTRUCTION END -->
                                <!-- <div class='flex-grow-1'>
                                    <input class='form-control my-auto' list='subjectOptions' id='search-input' placeholder='Search subject code here ...'>
                                    <datalist id='subjectOptions'>
                                        <?php
                                            // $subjects = $admin->listSubjects();
                                            // foreach($subjects as $subject) {
                                            //     $code = $subject->get_sub_code();
                                            //     echo "<option value='$code' class='sub-option'>$code - {$subject->get_sub_name()}</option>";
                                            // }
                                        ?>
                                    </datalist>
                                </div> -->
                                <!-- <div class='ms-1'>
                                    <button class='add-subject btn btn-dark'><i class='bi bi-plus-lg me-2'></i>Subject</button>
                                    <button class='remove-all-btn btn btn-danger'><i class='bi bi-x-lg me-2'></i>Selected</button>
                                </div> -->
                            <!-- </div>
                            <form id='as-form'>
                                <input type="hidden" name="teacher_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="action" value="editSubject">
                                <table id='as-table'class='table table-bordered table-hover table-striped table-sm' style='height: auto;'>
                                    <thead>
                                        <tr class='text-center'>
                                            <th class='d-none' scope='col'><input type='checkbox' id="selectAll" /></th>
                                            <th scope='col'>Code</th>
                                            <th scope='col'>Subject Name</th>
                                            <th scope='col'>Type</th>
                                            <th scope='col'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            // $assigned_sub = $userProfile->get_subjects();
                                            // $state = '';
                                            // if (count($assigned_sub) > 0) {
                                            //     $state = 'd-none';
                                            //     foreach ($assigned_sub as $sub) {
                                            //         $code = $sub->get_sub_code();
                                            //         echo "<tr class='text-center content'>
                                            //             <td class='d-none cb-con' scope='col'><input type='checkbox' value='{$code}' /></td>
                                            //             <td scope='col'><input type='hidden' name='subjects[]' value='{$code}'/>{$code}</td>
                                            //             <td scope='col'>{$sub->get_sub_name()}</td>
                                            //             <td scope='col'>{$sub->get_sub_type()}</td>
                                            //             <td scope='col'>
                                            //                 <button data-value='{$code}' class='remove-btn btn btn-sm btn-danger m-auto shadow-sm d-none' title='Remove'><i class='bi bi-x-square'></i></button>
                                            //                 <a href='subject.php?sub_code=$code&state=view'data-value='{$code}' class='view-btn btn btn-sm btn-primary m-auto shadow-sm' title='View subject'><i class='bi bi-eye'></i></a>
                                            //             </td>
                                            //         </tr>";
                                            //     }
                                            // }
                                            // echo "<tr id='emptyMsg' class='text-center $state'><td colspan='5'>No subject set</td></tr>";
                                        ?>
                                    </tbody>
                                </table> -->
                            <!-- </form> -->
                        <!-- </div> -->
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
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="hidden" name="action" value="deactivate"/>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
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
                        <div class="d-flex justify-content-between mb-1">
                            <!-- SEARCH BAR -->
                            <span class="flex-grow-1"> 
                                <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search subject here">
                            </span>
                        </div>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th scope='col' data-width="200" data-align="center" data-field="sub_code">Code</th>
                            <th scope='col' data-width="500" data-align="left" data-sortable="true" data-field="sub_name">Subject Name</th>
                            <th scope='col' data-width="300" data-align="center" data-sortable="true" data-field="for_grd_level">Grade Level</th>
                            <!-- <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <form id="as-form" method="POST" action="action.php">
                    <input type="hidden" name="teacher_id" value="<?php echo $id; ?>"/>
                    <input type="hidden" name="action" value="editSubject">
                    <button id='cancel-as-btn' class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
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
                        $editable = "disabled";
                        if ($advisory_code) {
                            $editable = "";
                        }
                    ?>
                    <input name="current-section" type="hidden" value="<?php echo $advisory_code; ?>" <?php echo $editable; ?>>
                    <input type="hidden" name="teacher-id" value="<?php echo $id; ?>"/>
                    <input type="hidden" name="action" value="advisoryChange"/>
                    <div class="form-group mb-3">
                        <input type="checkbox" <?php echo ($advisory_code ? "" : "disabled"); ?> name="unassign" class="form-check-input me-1">
                        <span>Unassign class to this faculty</span>
                    </div>
                    <div id="section-opt-con" class="border p-3">
                        <p class="text-secondary"><small>Select one section to be assigned or switched</small></p>
                        <div class="search-con d-flex">
                            <input id="search-section" type="text" class="form-control flex-grow-1 me-3" placeholder="Search section here ...">
                            <div class="dropdown">
                                <button class="btn shadow" type="button" id="section-filter" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="section-filter">
                                    <li><button id="all-section-btn" class="dropdown-item" >All</button></li>
                                    <li><button id="no-adv-btn" class="dropdown-item" >No Adviser</button></li>
                                    <li><button id="with-adv-btn" class="dropdown-item" >With Adviser</button></li>
                                </ul>
                            </div>
                        </div>
    
                            <!-- <input id="selected-section" type="text" class="form-control m-0" aria-describedby="selected-section-label" readonly> -->
                       
                        <ul class="list-group overflow-auto" id="section-list" style="height: 250px;">
                            <?php 
                                $section_list = $admin->listSectionOption($id);
                                foreach($section_list as $section) {
                                    $sect_code = $section["section_code"];
                                    $sect_name = $section["section_name"];
                                    $sect_grd = $section["section_grd"];
                                    $sect_adviser_id = $section["adviser_id"];
                                    $sect_adviser = $section["adviser_name"];
                                    $color_badge = $sect_adviser ? "warning" : "success";
                                    echo "<li class='list-group-item'>
                                            <div class='form-row row'>
                                                <span class='col-1'><input class='form-check-input me-1' data-current-adviser='$sect_adviser_id' name='section' type='radio' value='$sect_code'></span>
                                                <div class='section-info d-flex justify-content-between col-sm-6'>
                                                    <span>$sect_code - $sect_name </span> 
                                                    <span class='text-secondary'>G$sect_grd</span>
                                                </div>
                                                <div class='section-status d-flex justify-content-between col-sm-5'>
                                                    <div class='teacher-con' title='Current class adviser'>$sect_adviser</div>
                                                    <span class='badge available'><div class='bg-$color_badge rounded-circle' style='width: 10px; height: 10px;'></div></span>
                                                </div>
                                            </div>
                                        </li>";
                                }
                            ?>
                        </ul>  
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="close btn btn-dark close-btn" data-bs-dismiss="modal">Cancel</button>
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
<script type="text/javascript">
    let roles = <?php echo json_encode($roles); ?>;
    let deptExist = <?php echo json_encode($deptExist); ?>;
    let subjects = <?php echo json_encode($subjects);?>;
    let assigned = <?php echo json_encode($assigned_sub);?>;
</script>
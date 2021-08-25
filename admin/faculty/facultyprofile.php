<?php include_once("../inc/head.html"); 
    require_once("../class/Administration.php");
    $admin = new Administration();
    $_SESSION['userType'] = 'admin';
    $_SESSION['userID'] = 'alvin';

    $userType = ucwords( $_SESSION['userType']);
    $link = "facultylist.php";
    $userProfile = $admin->getProfile("FA");

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
<div class="d-flex justify-content-between">
    <h4 class="my-auto">Profile</h4>
    <a href="faculty.php?id=<?php echo $id;?>&action=edit" role="button" class="btn link my-auto"><i class="bi bi-pencil-square me-2"></i>Edit</a>
</div>
<div class='container mt-3'>
    <!-- GENERAL INFORMATION -->
    <div class="row card bg-light w-100 h-auto text-start mx-auto rounded-3">
        <h5>GENERAL INFORMATION</h5>
        <hr>
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
                        echo "<p>Name: {$userProfile->get_name()}<br>
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
    <!-- GENERAL INFORMATION END -->
    <!-- ASSIGNED SUBJECT -->
    <div class='collapse-table row card bg-light w-100 h-auto text-start mx-auto mt-4 rounded-3'>
        <div class="d-flex justify-content-between p-0">
            <h5 class="my-auto">ASSIGNED SUBJECTS</h5>
            <div class='decision-as-con my-auto d-none'>
                <button id='cancel-as-btn' class='btn btn-secondary'>Cancel</button>
                <button id='save-as-btn' class='btn btn-success'><i class="bi bi-save me-2"></i>Save</button>
            </div>
            <div class='edit-con my-auto'>
                <button id='edit-as-btn' class='btn link btn-sm'><i class='bi bi-pencil-square me-2'></i>Edit</button>
            </div>
            <!-- <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='View'> -->
        </div>
        <!-- <div id='assign-subj-table' class='collapse'><hr> -->
        <div id='assign-subj-table' class=''><hr>
            <div class='overflow-auto' style='height: 300px;'>
                <div class='finder-con d-flex mb-3 pt-1 d-none'>
                    <!-- INSTRUCTION -->
                    <div class="my-auto">
                        <a id="instruction" tabindex="0" class="btn btn-sm btn-light mx-1 rounded-circle shadow-sm " role="button" data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="focus" title="Instruction" data-bs-content="Find the subject code to be assigned to the faculty, then click the '+ SUBJECT' button">
                            <i class="bi bi-info-circle"></i>
                        </a>
                    </div>
                    <!-- INSTRUCTION END -->
                    <div class='flex-grow-1'>
                        <input class='form-control my-auto' list='subjectOptions' id='search-input' placeholder='Search subject code here ...'>
                        <datalist id='subjectOptions'>
                            <?php
                                $subjects = $admin->listSubjects();
                                foreach($subjects as $subject) {
                                    $code = $subject->get_sub_code();
                                    echo "<option value='$code' class='sub-option'>$code - {$subject->get_sub_name()}</option>";
                                }
                            ?>
                        </datalist>
                    </div>
                    <div class='ms-1'>
                        <button class='add-subject btn btn-dark'><i class='bi bi-plus-lg me-2'></i>Subject</button>
                        <button class='remove-all-btn btn btn-danger'><i class='bi bi-x-lg me-2'></i>Selected</button>
                    </div>
                </div>
                <form id='as-form'>
                    <input type="hidden" name="teacher_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="action" value="editSubject">
                    <table id='as-table'class='table table-bordered table-hover table-striped' style='height: auto;'>
                        <thead>
                            <tr class='text-center'>
                                <th class='d-none' scope='col'><input type='checkbox' id="selectAll" /></th>
                                <th scope='col'>Code</th>
                                <th scope='col'>Subjec Name</th>
                                <th scope='col'>Type</th>
                                <th scope='col'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $assigned_sub = $userProfile->get_subjects();
                                $state = '';
                                if (count($assigned_sub) > 0) {
                                    $state = 'd-none';
                                    foreach ($assigned_sub as $sub) {
                                        $code = $sub->get_sub_code();
                                        echo "<tr class='text-center content'>
                                            <td class='d-none cb-con' scope='col'><input type='checkbox' value='{$code}' /></td>
                                            <td scope='col'><input type='hidden' name='subjects[]' value='{$code}'/>{$code}</td>
                                            <td scope='col'>{$sub->get_sub_name()}</td>
                                            <td scope='col'>{$sub->get_sub_type()}</td>
                                            <td scope='col'>
                                                <button data-value='{$code}' class='remove-btn btn btn-sm btn-danger m-auto shadow-sm d-none' title='Remove'><i class='bi bi-x-square'></i></button>
                                                <a href='subject.php?sub_code=$code&state=view'data-value='{$code}' class='view-btn btn btn-sm btn-primary m-auto shadow-sm' title='View subject'><i class='bi bi-eye'></i></a>
                                            </td>
                                        </tr>";
                                    }
                                }
                                echo "<tr id='emptyMsg' class='text-center $state'><td colspan='5'>No subject set</td></tr>";
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <!-- ASSIGNED SUBJECT END -->
    <!-- CLASSES HANDLED -->
    <div class='collapse-table row card bg-light w-100 h-auto text-start mx-auto mt-4 rounded-3'>
        <div class="d-flex justify-content-between">
            <h5 class="my-auto">CLASSES</h5>
            <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#assign-class-con' type='button' value='Assign'>
        </div>
        <div id='assign-class-con' class='collapse'>
            <hr>
            <div class='overflow-auto' style='height: 300px;'>
            </div>
        </div>
    </div>
    <!-- CLASSES HANDLED END -->
    <!-- CLASSES HISTORY -->
    <div class='collapse-table row card bg-light w-100 h-auto text-start mx-auto mt-4 rounded-3'>
        <div class="d-flex justify-content-between">
            <h5 class="my-auto">CLASSES HISTORY</h5>
            <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#class-hist-con' type='button' value='View'>
        </div>
        <div id='class-hist-con' class='collapse'>
            <hr>
            <div class='overflow-auto' style='height: 300px;'>
            </div>
        </div>
    </div>
    <!-- CLASSES HISTORY END -->
</div>
<!-- MODAL -->
<div class="modal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0"></h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">CANCEL</button>
                <button type="submit" name="" form="" class="submit btn btn-primary">SUBMIT</button>
            </div>
        </div>
    </div>
</div>
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
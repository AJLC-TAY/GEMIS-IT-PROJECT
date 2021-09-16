<?php include_once("../inc/head.html"); 
    require_once("../class/Administration.php");
    $admin = new Administration();
    session_start();
    $_SESSION['userType'] = 'admin';
    $_SESSION['userID'] = 'alvin';
    $profileType = $_GET['pt'];
    $profileType = ($profileType == 'A') 
        ? "Admin" 
        : (($profileType == 'F') 
            ? "Faculty" 
            : "Student");

    $userType = ucwords( $_SESSION['userType']);

    $link = "{$profileType}list.php";  // ex. AdminList.php / Facultylist.php
    $userProfile = $admin->getProfile("FA");

    $id = $userProfile->get_teacher_id();

    echo "<title>Faculty Profile | GEMIS</title>";
?>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once("../inc/$userType/sidebar.php");?>
        <!-- MAIN CONTENT -->
        <section id="main-content">
            <section class="wrapper"></section>
            <div class="row">
                <div class="col-lg-10">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item"><?php echo "<a href='$link' target='_self'>$profileType</a>"; ?></li>
                                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                                </ol>
                            </nav>
                            <!-- BREADCRUMB END -->
                        </header>
                        <!-- HEADER END -->
                        <div class="d-flex justify-content-between">
                            <h4 class="my-auto">Profile</h4>
                            <a href="faculty.php?id=<?php echo $id;?>&state=edit" role="button" class="btn text-primary my-auto"><i class="bi bi-pencil-square me-1"></i>EDIT</a>
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
                                            <?php echo "<p>Name: {$userProfile->get_name()}<br>
                                                        Gender: {$userProfile->get_sex()}<br>
                                                        Age: {$userProfile->get_age()}<br>
                                                        Birthdate: {$userProfile->get_birthdate()}</p>"; ?>
                                        </div>
                                        <div class="row">
                                            <h6><b>Contact Information</b></h6>
                                            <?php echo "<p>Cellphone No.: {$userProfile->get_cp_no()}<br>
                                                        Email: {$userProfile->get_email()}</p>"; ?>
                                        </div>
                                        <div class="row">
                                            <h6><b>Department</b></h6>
                                            <div class="tag-con">
                                                <?php 
                                                    $departments = $userProfile->get_department();
                                                    if (is_null($departments)) {
                                                        echo "<p class='text-center'>No department set</p>";
                                                    } else {
                                                        foreach($departments as $department) {
                                                            // echo "<button class='btn btn-outline-secondary btn-sm rounded-pill w-auto'>$department</button>"; 
                                                            echo "<div class='rounded-pill border border-secondary d-inline-block me-1'><span class='mx-3'>$department</span></div>";
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- ROLE SECTION -->
                                        <div id="role-section" class="row mt-3 d-flex-column py-2">
                                            <form id='role-form'>
                                                <input type="hidden" name="teacher_id" value="<?php echo $id; ?>">
                                                <input type="hidden" name="action" value="updateFacultyRoles">
                                            </form>
                                            <div class="d-flex justify-content-between">
                                                <div class="my-auto"><h6 class='m-0 fw-bold'>Roles
                                                    <span class="badge"><button id='role-edit-btn' class='btn btn-sm btn-link'><i class='bi bi-pencil-square'></i></button></span>
                                                </h6></div>
                                                <div id="role-decide-con" class='d-none my-auto'>
                                                    <button id='role-cancel-btn' class='btn btn-sm btn-dark me-1'>Cancel</button>
                                                    <button id='role-save-btn' class='btn btn-sm btn-success'>Save</button>
                                                </div>
                                            </div>
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
                                                    echo "<p id='role-empty-msg' class='text-center $rolesMsg'>No roles/access set</p>";
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
                                        </div>
                                    </div>
                                    <!-- ROLE SECTION END -->
                                    <!-- INFORMATION DETAILS END -->
                                </div>
                            </div>
                            <!-- GENERAL INFORMATION END -->
                            <!-- ASSIGNED SUBJECT -->
                            <div class='collapse-table row card bg-light w-100 h-auto text-start mx-auto mt-4 rounded-3'>
                                <div class="d-flex justify-content-between">
                                    <h5 class="my-auto">ASSIGNED SUBJECTS</h5>
                                    <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='Assign'>
                                </div>
                                <div id='assign-subj-table' class='collapse'><hr>
                                    <div class='overflow-auto' style='height: 300px;'>
                                    <div class='d-flex mb-3 pt-1'>
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
                                                <button class='add-subject btn btn-dark'><i class='bi bi-plus-lg me-1'></i> Subject</button>
                                                <button class='remove-all-btn btn btn-outline-danger'><i class='bi bi-x-lg me-1'></i>Selected</button>
                                            </div>
                                        </div>
                                        <table class='table table-bordered table-hover table-striped' style='height: auto;'>
                                            <thead>
                                                <tr class='text-center'>
                                                    <th scope='col'><input type='checkbox' /></th>
                                                    <th scope='col'>CODE</th>
                                                    <th scope='col'>SUBJECT NAME</th>
                                                    <th scope='col'>TYPE</th>
                                                    <th scope='col'>ACTION</th>
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
                                                            echo "<tr class='text-center'>
                                                                <td scope='col'><input type='checkbox' value='{$code}' /></td>
                                                                <td scope='col'><input type='hidden' name='subjects[]' value='{$code}'/>{$code}</td>
                                                                <td scope='col'>{$sub->get_sub_name()}</td>
                                                                <td scope='col'>{$sub->get_sub_type()}</td>
                                                                <td scope='col'><button id='{$code}' class='remove-btn btn btn-sm btn-outline-danger m-auto'>Remove</button></td>
                                                            </tr>";
                                                        }
                                                    }
                                                    echo "<tr id='emptyMsg' class='text-center $state' hidden><td colspan='5'>No subject set</td></tr>";
                                                ?>
                                            </tbody>
                                        </table>
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


                    </div>
                </div>
            </div>
            <!--main content end-->
            <!--footer start-->
            <?php include_once("../inc/footer.html"); ?>
            <!--footer end-->
        </section>
    </section>
    <!-- MODAL -->
    <div class="modal fade" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
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
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse " style="z-index: 99999; min-height: 50vh; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript">
    // var code = <?php // echo json_encode($curr_code);?>;
    var roles = <?php echo json_encode($roles);?>;
</script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript" src="../js/admin/profile.js"></script>
</html>

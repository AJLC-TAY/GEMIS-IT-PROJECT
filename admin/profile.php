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
    $userProfile = $admin->getProfile();

    $id = $userProfile->get_teacher_id();

    echo "<title>Faculty Profile | GEMIS</title>";
?>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once("../inc/$userType/sidebar.html");?>
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
                                <div class="row">
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
                                                    print_r($departments);
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
                                        <div class="row mt-3">
                                            <form id="role-form" class="d-flex justify-content-between">
                                                <!-- v1 -->
                                                <h6><b>Roles
                                                    <span class="badge">
                                                        <button id='edit-role-btn' class='btn btn-sm btn-link'><i class='bi bi-pencil-square'></i></button>
                                                        <button id='role-cancel-btn' class='btn btn-sm btn-outline-secondary d-none'>CANCEL</button>
                                                        <input id='role-save-btn' type='submit' class='btn btn-sm btn-outline-success d-none' value='SAVE'/>
                                                    </span></b>
                                                </h6>
                                                <!-- v2 -->
                                                <!-- <h6><b>Roles</b></h6> -->
                                                <!-- <button class='btn btn-sm btn-link'><i class='bi bi-pencil-square'></i></button> -->
                                            </form>
                                            <div id="role-tag-con">
                                                <?php 
                                                    $roles = [];
                                                    $isAwardCoordinator = $userProfile->get_award_coor();
                                                    $canEditGrades = $userProfile->get_enable_edit_grd();
                                                    $canEnroll = $userProfile->get_enable_enroll();
                                                    if ($isAwardCoordinator) {
                                                        $roles[] = "awardReport";
                                                        echo "<div class='rounded-pill border border-secondary d-inline-block me-1'><span class='ms-3'>Award Coordinator</span><button class='btn btn-link text-dark btn-sm '><i class='bi bi-x-circle-fill d-none' data-value='awardReport'></i></button></div>";
                                                    } 
                                                    if ($userProfile->get_enable_edit_grd()) {
                                                        $roles[] = "editGrades";
                                                        echo "<div class='rounded-pill border border-secondary d-inline-block me-1'><span class='ms-3'>Edit Grade</span><button class='btn btn-link text-dark btn-sm '><i class='bi bi-x-circle-fill d-none' data-value='editGrades'></i></button></div>";
                                                    } 
                                                    
                                                    if ($canEnroll) {
                                                        $roles[] = "canEnroll";
                                                        echo "<div class='rounded-pill border border-secondary d-inline-block me-1'><span class='ms-3'>Can Enroll</span><button class='btn btn-link text-dark btn-sm '><i class='bi bi-x-circle-fill d-none' data-value='canEnroll'></i></button></div>";
                                                    }

                                                    if(!count($roles)) {
                                                        echo "<p id='role-empty-msg' class='text-center'>No roles/access set</p>";
                                                    }
                                                    echo "
                                                    <div id='role-add-btn' class='btn-group dropend d-none'>
                                                        <button type='button' class='btn btn-outline-success rounded-circle px-2 py-1' data-bs-toggle='dropdown' aria-expanded='false'>
                                                            <i class='bi bi-plus'></i>
                                                        </button>
                                                        <ul id='role-dropdown' class='dropdown-menu'>
                                                            <li><button class='dropdown-item' data-value='editGrades'>Edit Grade</button></li>
                                                            <li><button class='dropdown-item' data-value='canEnroll'>Can Enroll</button></li>
                                                            <li><button class='dropdown-item' data-value='awardReport'>Award Coordinator</button></li>
                                                        </ul>
                                                    </div>";
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- INFORMATION DETAILS END -->
                                </div>
                            </div>
                            <!-- GENERAL INFORMATION END -->
                            <!-- ASSIGNED SUBJECT -->
                            <div class='collapse-table row card bg-light w-100 h-auto text-start mx-auto mt-4 rounded-3'>
                                <div class="d-flex justify-content-between">
                                    <h5 class="my-auto">ASSIGNED SUBJECTS</h5>
                                    <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='ASSIGN'>
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
                                                <button class='add-subject btn btn-dark'><i class='bi bi-plus-lg me-1'></i> SUBJECT</button>
                                                <button class='remove-all-btn btn btn-outline-danger'><i class='bi bi-x-lg me-1'></i>SELECTED</button>
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
                                                                <td scope='col'><button id='{$code}' class='remove-btn btn btn-sm btn-outline-danger m-auto'>REMOVE</button></td>
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
                                    <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#assign-class-con' type='button' value='ASSIGN'>
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
                                    <input class='btn btn-primary w-auto my-auto' data-bs-toggle='collapse' data-bs-target='#class-hist-con' type='button' value='VIEW'>
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
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div class="position-absolute" style="bottom: 20px; right: 25px;">
            <div class="toast warning-toast bg-danger text-white" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body"></div>
            </div>

            <div class="toast add-toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                    Curriculum successfully added
                </div>
            </div>
        </div>
    </div>
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript">
    // var code = <?php // echo json_encode($curr_code);?>;
    var roles = <?php echo json_encode(empty($roles) ? 0 : $roles);?>;
</script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript" src="../js/admin/profile.js"></script>
</html>

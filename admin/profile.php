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
                        <div class='row card bg-light w-100 h-auto text-start mx-auto mt-3'>
                            <div class="d-flex justify-content-between mb-2">
                                <h4>Profile</h4>
                                <span>
                                    <a href="faculty.php?id=<?php echo $id;?>&state=edit" role="button" class="btn text-primary"><i class="bi bi-pencil-square me-1"></i>EDIT</a>
                                </span>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- PROFILE PICTURE -->
                                <div class="col-xl-5">
                                    <?php 
                                        $image = is_null($userProfile->get_id_photo()) ? "../assets/profile.png" : $userProfile->get_id_photo();
                                        echo "<img src='$image' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'>";
                                        echo "<p>Faculty ID: $id</p>";
                                    ?> 
                                    <div class="row">
                                        <button class="btn btn-outline-primary w-auto">ASSIGN SUBJECT</button>
                                    </div>
                                </div>
                                <!-- PROFILE PICTURE END -->
                                <!-- INFORMATION DETAILS -->
                                <div class="col-xl-7">
                                    <div class="row">
                                        <h6><b>General Information</b></h6>
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
                                                        echo "<button class='btn btn-outline-secondary btn-sm rounded-pill w-auto'>$department</button>"; 
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <h6><b>Roles</b></h6>
                                        <div class="tag-con">
                                            <?php 
                                                $roleCounter = 0;
                                                $isAwardCoordinator = $userProfile->get_award_coor();
                                                $canEditGrades = $userProfile->get_enable_edit_grd();
                                                $canEnroll = $userProfile->get_enable_enroll();
                                                if ($isAwardCoordinator) {
                                                    $roleCounter += 1;
                                                    echo "<button class='btn btn-outline-primary btn-sm rounded-pill w-auto'>Edit Grade</button>";
                                                } 
                                                if ($userProfile->get_enable_edit_grd()) {
                                                    $roleCounter += 1;
                                                    echo "<button class='btn btn-outline-primary btn-sm rounded-pill w-auto'>Edit Grade</button>";
                                                } 
                                                
                                                if ($canEnroll) {
                                                    $roleCounter += 1;
                                                    echo "<button class='btn btn-outline-primary btn-sm rounded-pill w-auto'>Can Enroll</button>";
                                                }

                                                if(!$roleCounter) {
                                                    echo "<p class='text-center'>No roles/access set</p>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- INFORMATION DETAILS END -->
                            </div>
                        </div>

                        <!-- TRACK TABLE -->
                        <!-- <div class="container mt-5">
                            <table id="table" class="table-striped">
                                <thead class='thead-dark'>
                                    <div class="d-flex justify-content-between mb-3">
                                        <h4>Strand List</h4>
                                        <div>
                                            <button class="btn btn-secondary" title='Archive strand'>Archive</button>
                                            <button id="add-btn" class="btn btn-success add-prog" title='Add new strand'>Add strand</button>
                                        </div>
                                    </div>

                                    <tr>
                                        <th data-checkbox="true"></th>
                                        <th scope='col' data-width="100" data-align="right" data-field='prog_code'>Code</th>
                                        <th scope='col' data-width="600" data-sortable="true" data-field="prog_desc">Program/Strand Description</th>
                                        <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div> -->
                    </div>
                </div>
            </div>
            <!--main content end-->
            <!--footer start-->
            <?php include_once("../inc/footer.html"); ?>
            <!--footer end-->
        </section>
    </section>
    <!-- ADD PROGRAM MODAL -->
    <div class="modal" id="add-modal" tabindex="-1" aria-labelledby="modal addProgram" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Add Strand/Program</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="prog-form" action="">
                        <div class="form-group">
                            <label for="prog-code">Strand Code</label>
                            <input id="prog-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. STEM" required>
                            <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique strand code</small></p>
                            <label for="prog-name">Strand Name</label>
                            <input id="prog-name" type="text" name="desc" class='form-control' placeholder="ex. Science, Technology, Engineering, and Math" required>
                            <p class="name-error-msg text-danger m-0 invisible"><small>Please provide the program name</small></p>
                            <label for="prog-curr">Curriculum</label>
                            <input type="text" class='form-control' name="curr-code" value="<?php echo ($curr_code); ?>" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit-prog" form="prog-form" class="submit btn btn-primary" data-link='addProg.php'>Add</button>
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
</script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript" src="../js/admin/Class.js"></script>
<script type="text/javascript" src="../js/admin/profile.js"></script>

</html>

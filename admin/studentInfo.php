<?php include_once("../inc/head.html"); 
    require_once("../class/Administration.php");
    $admin = new Administration();
    $userProfile = $admin->getProfile("S");

    $stud_id = $userProfile->get_stud_id();
    $user_id_no = $userProfile->get_id_no();
    $lrn = $userProfile->get_lrn();
    $name = $userProfile->get_name();
    $sex = $userProfile->get_sex();
    $age = $userProfile->get_age();
    $birthdate = $userProfile->get_birthdate();
    $birth_place = $userProfile->get_birth_place();
    $indigenous_group = $userProfile->get_indigenous_group();
    $mother_tongue = $userProfile->get_mother_tongue();
    $religion = $userProfile->get_religion();
    $address = $userProfile->get_address(); 
    $add = $address['address'];
    $cp_no = $userProfile->get_cp_no();
    $psa_birth_cert = $userProfile->get_psa_birth_cert();
    $belong_to_ipcc = $userProfile->get_belong_to_ipcc();
    $id_picture = $userProfile->get_id_picture();
    $section = $userProfile->get_section();
    $parents = $userProfile->get_parents();
    // $father_name = $userProfile->get_father_name();
    // $father_occupation = $userProfile->get_father_occupation();
    // $father_cp_no = $userProfile->get_father_cp_no();
    // $mother_name = $parents['f']['name'];
    // $mother_occupation = $parents['f']['occupation'];
    // $mother_cp_no = $parents['f']['cp_no'];

    // $father_name = $userProfile->get_father_name();
    // $father_occupation = $userProfile->get_father_occupation();
    // $father_cp_no = $userProfile->get_father_cp_no();
    // $mother_name = $userProfile->get_mother_name();
    // $mother_occupation = $userProfile->get_mother_occupation();
    // $mother_cp_no = $userProfile->get_mother_cp_no();

    // $guardian_name = $userProfile->get_guardian_name();
    // $guardian_cp_no = $userProfile->get_guardian_cp_no();
    // $guardian_relationship = $userProfile->get_guardian_relationship();
    // $guardian = $userProfile->get_guardians();
    // var_dump(array_values($guardian));
    // $guardian_name = $guardian['name'];
    // $guardian_cp_no = $guardian['cp_no'];
    // $guardian_relationship = $guardian['relationship'];
?>

<title>Student Information | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

<body>
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper"></section>
            <div class="row">
                <div class="col-lg-11">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="studentList.php">Student</a></li>
                                    <li class="breadcrumb-item active"><a href="studentInfo.php">Personal Details</a></li>
                                    
                                </ol>
                            </nav>
                            <h3 class="fw-bold">Student Information</h3>
                        </header>
                        <!-- MAIN CONTENT -->
                        <!-- Photo -->
                        <div class="container mt-2 col-3">
                            <div class="photo">
                                Insert 2x2 ID
                            </div>
                            <div class="buttons mt-3">
                                <?php echo "<h5 class='mb-4'>STUDENT ID: $stud_id</h5>";?>
                                <button class="btn btn-success ms-2 mb-2 w-100" title='Edit Profile'>EDIT PROFILE</button>
                                <button class="btn btn-success ms-2 mb-2 w-100" title='Transfer Student'>TRANSFER STUDENT</button>
                                <button class="btn btn-secondary ms-2 mb-2 w-100" title='Reset Password'>RESET PASSWORD</button>
                            </div>

                        </div>
                        <!-- Personal Details -->
                        <div class="container mt-4 col-8">
                            <div class="card body w-100 h-auto">
                                <div class="row col-12">
                                    <h4 class="fw-bold">GENERAL INFORMATION</h4>
                                    <ul class="list-group ms-3">
                                        <?php echo "<li class='list-group-item'>Name: $name</li>
                                        <li class='list-group-item'>Gender: $sex</li>
                                        <li class='list-group-item'>Age: $age </li>
                                        <li class='list-group-item'>Birthdate: $birthdate</li>
                                        <li class='list-group-item'>Birth Place: $birth_place</li>
                                        <li class='list-group-item'>Indeginous Group: $indigenous_group</li>
                                        <li class='list-group-item'>Mother Tongue: $mother_tongue</li>
                                        <li class='list-group-item'>Religion: $religion</li>";?>
                                        
                                    </ul>
                                </div>
                                <div class="row col-12">
                                    <h4 class="mt-3 fw-bold">CONTACT INFORMATION</h4>
                                    <ul class="list-group ms-3">
                                        <?php echo "<li class='list-group-item'>Home Address: $add </li>
                                        <li class='list-group-item'>Contact Number: $cp_no </li>"?>
                                        
                                    </ul>
                                </div>

                                <div class="row col-12">
                                     <h4 class="mt-3 fw-bold">CONTACT PERSONS</h4>
                                     <h5>PARENT/S</h5>
                                     <ul class="list-group ms-3">
                                         <?php echo "
                                        
                                        <li class='list-group-item'>Mother's Name: $mother_name</li>
                                        <li class='list-group-item'>Occupation: $mother_occupation</li>
                                        <li class='list-group-item'>Contact Number: $mother_cp_no</li>";?>
                                    </ul>
                                    <h5 class="mt-3 fw-bold">GUARDIAN/S</h5>
                                    <ul class="list-group ms-3">
                                        <?php echo "<li class='list-group-item'>Guardian's Name: $guardian_name</li>
                                        <li class='list-group-item'>Relationship to the Guardian: $guardian_cp_no</li>
                                        <li class='list-group-item'>Contact Number: $guardian_relationship</li>"?>
                                        
                                    </ul>
                                </div>
                               
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
    </body>
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>

    <script type="text/javascript" src="../js/admin/subject.js"></script>
</html>
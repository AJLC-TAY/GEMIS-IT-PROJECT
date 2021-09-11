<?php include_once("../inc/head.html"); 
require_once("../class/Administration.php");
$admin = new Administration();
$userProfile = $admin->getProfile("ST");
$stud_id = $userProfile->get_stud_id();
$user_id_no = $userProfile->get_id_no();
$lrn = $userProfile->get_lrn();
$lname = $userProfile->get_last_name();
$fname = $userProfile->get_first_name();
$mname = $userProfile->get_middle_name();
$extname =$userProfile->get_ext_name();
$sex = $userProfile->get_sex();
$age = $userProfile->get_age();
$birthdate = $userProfile->get_birthdate();
$birth_place = $userProfile->get_birth_place();
$indigenous_group = $userProfile->get_indigenous_group();
$mother_tongue = $userProfile->get_mother_tongue();
$religion = $userProfile->get_religion();

$address = $userProfile->get_address();
$house_no = $address['home_no'];
$street = $address['street'];
$barangay = $address['barangay'];
$city = $address['mun_city'];
$province =$address['province'];
$zip =$address['zipcode'];

$cp_no = $userProfile->get_cp_no();
$psa_birth_cert = $userProfile->get_psa_birth_cert();
$belong_to_ipcc = $userProfile->get_belong_to_ipcc();
$id_picture = $userProfile->get_id_picture();
$section = $userProfile->get_section();

$parents = $userProfile->get_parents();
if (is_null($parents)) {
    $parents = NULL;
} else {
    foreach ($parents as $par) {
        $parent = $par['sex'] == 'f' ? 'mother' : 'father';
        ${$parent . '_first_name'} = $par['fname'];
        ${$parent . '_last_name'} = $par['lname'];
        ${$parent . '_middle_name'} = $par['mname'];
        ${$parent . '_ext_name'} = $par['extname'];
        ${$parent . '_occupation'} = $par['occupation'];
        ${$parent . '_cp_no'} = $par['cp_no'];
    }
}

$guardian = $userProfile->get_guardians();
if (is_null($guardian)) {
    $guardian = NULL;
} else {
    $guardian_first_name = $guardian['fname'];
    $guardian_last_name = $guardian['lname'];
    $guardian_middle_name = $guardian['mname'];
    $guardian_cp_no = $guardian['cp_no'];
    $guardian_relationship = $guardian['relationship'];
}
?>
<title>Enrollment Page | GEMIS</title>
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
                                    <li class="breadcrumb-item active"><a href="">Enroll Student</a></li>
                                   
                                </ol>
                            </nav>
                            <h3 class="fw-bold">Enrollment</h3>
                        </header>
                        <!-- MAIN CONTENT -->
                        <nav>
                            <div class="nav nav-pills nav-fill" id="nav-tab" role="tablist">
                                <a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1">Step 1</a>
                                <a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2">Step 2</a>
                                <a class="nav-link" id="step3-tab" data-toggle="tab" href="#step3">Step 3</a>
                            </div>
                        </nav>
                        <!-- Form -->

                        <div class="tab-content py-4" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="step1">
                            <div class="card body w-100 h-auto">
                                <form action="action.php" method="POST">
                                    <h4 class="fw-bold">STUDENT INFORMATION</h4>
                                    <div class="form-group row">

                                        <div class="col-6">
                                            <label class="col-form-label">PSA Birth Certificate</label>
                                            <!-- image heree -->
                                        </div>

                                        <div class="col-6">
                                            <label class="col-form-label">Learner's Reference Number</label>
                                            <input class="form-control" type="number"  value = <?php echo $lrn?> aria-label="default input example">
                                        </div>

                                        <div class="col-6">
                                            <label class="col-form-label">Last Name</label>
                                            <input class="form-control" type="text" value = <?php echo $lname?> aria-label="default input example">
                                        </div>

                                        <div class='col-6'>
                                            <label class="col-form-label">First Name</label>
                                            <input class="form-control" type="text" value = <?php echo $fname?> aria-label="default input example">
                                        </div>

                                        <div class='col-6'>
                                            <label class="col-form-label">Middle Name</label>
                                            <input class="form-control" type="text" value = <?php echo $mname?> aria-label="default input example">
                                        </div>

                                        <div class='col-4'>
                                            <label class="col-form-label">Suffix</label>
                                            <input class="form-control" type="text" value = <?php echo $extname?>>
                                        </div>

                                        <div class="form-group col-4">
                                            <label class="col-form-label">Birthdate</label>
                                            <div class='input-group date' id='datepicker'>
                                                <input type='date' class="form-control" value = <?php echo $birthdate?> />
                                            </div>
                                        </div>
                                        <div class='col-4'>
                                            <label class="col-form-label">Birth Place</label>
                                            <input class="form-control" type="text" value = <?php echo $birth_place?> aria-label="default input example">
                                        </div>

                                        <div class='col-2'>
                                            <label class="col-form-label">Age</label>
                                            <input class="form-control" type="number" value = <?php echo $age?> aria-label="default input example">
                                        </div>
                                        <div class="col-3">
                                            <label class="col-form-label">Sex</label>
                                            <?php  $sexOpt = ["m" => "Male", "f" => "Female"];
                                                foreach ($sexOpt as $id => $value) {
                                                echo "<div class='form-check'>
                                                            <input class='form-check-input' type='radio' name='sex' id='$id' value='$id' " . (($sex == $value) ? "checked" : "") . ">
                                                            <label class='form-check-label' for='$id'>
                                                            $value
                                                            </label>
                                                        </div>";
                                                }?>
                                        </div>

                                        <div class='col-5'>
                                            <label class="col-form-label">Contact Number</label>
                                            <input class="form-control" type="number" value = <?php echo $cp_no?> aria-label="default input example">
                                        </div>
                                        <div class="col-6">
                                            <label class="col-form-label me-4">Belonging to any Indeginous Group? </label>
                                            <?php 
                                                echo "<div class='form-check'>
                                                            <input class='form-check-input' type='radio' name='group' id='yes' value='Yes' " . (($indigenous_group != NULL) ? "checked" : "") . ">
                                                            <label class='form-check-label' for='yes'> Yes </label>
                                                        </div>
                                                        <div class='form-check'>
                                                        <input class='form-check-input' type='radio' name='group' id='no' value='No' " . (($indigenous_group == NULL) ? "checked" : "") . ">
                                                            <label class='form-check-label' for='yes'> No </label></div>";
                                                ?>
                                        </div>


                                        <div class='col-5'>
                                            <label class="col-form-label text-start">If yes, please specify</label>
                                            <input class="form-control" type="text" value = <?php echo $indigenous_group?>>
                                        </div>

                                        <div class="col-6">
                                            <label class="col-form-label">Mother Tongue</label>
                                            <input class="form-control" type="text" value = <?php echo $mother_tongue?> aria-label="default input example">
                                        </div>

                                        <div class="col-6">
                                            <label class="col-form-label">Religion</label>
                                            <input class="form-control" type="text" value = <?php echo $religion?> aria-label="default input example">
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <h5>ADDRESS</h5>
                                        <div class="col-2">
                                            <label class="col-form-label">House No.</label>
                                            <input class="form-control" type="number" value = <?php echo $house_no?>  aria-label="default input example">
                                        </div>

                                        <div class="col-4">
                                            <label class="col-form-label">Street</label>
                                            <input class="form-control" type="text" value = <?php echo $street?>  aria-label="default input example">
                                        </div>

                                        <div class="col-6">
                                            <label class="col-form-label">Barangay</label>
                                            <input class="form-control" type="text" value = <?php echo $barangay?>  aria-label="default input example">
                                        </div>

                                        <div class="col-6">
                                            <label class="col-form-label">City/Municipality</label>
                                            <input class="form-control" type="text" value = <?php echo $city?>  aria-label="default input example">
                                        </div>

                                        <div class="col-4">
                                            <label class="col-form-label">Province</label>
                                            <input class="form-control" type="text" value = <?php echo $province?>  aria-label="default input example">
                                        </div>

                                        <div class="col-2">
                                            <label class="col-form-label">Zip Code</label>
                                            <input class="form-control" type="number" value = <?php echo $zip?>  aria-label="default input example">
                                        </div>

                                    </div>
                                </form>
                            </div>
                            </div>
                            <div class="tab-pane fade" id="step2">
                            <div class="card w-100 h-auto mt-4">
                                <form action="action.php" class="needs-validation" method="POST" novalidate>
                                    <h4 class="fw-bold"> PARENT/GUARDIAN'S INFORMATION</h4>
                                    <div class="form-group row">
                                        <h5>FATHER</h5>
                                        <div class='form-row row'>
                                            <div class='form-group col-md-6'>
                                                <label for='lastname'>Last Name</label>
                                                <input type='text' class='form-control' id='lastname' name='lastname' value = <?php echo $father_last_name?> placeholder='Last Name' required>
                                                <div class="invalid-feedback">
                                                    Please enter last name
                                                </div>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='firstname'>First Name</label>
                                                <input type='text' class='form-control' id='firstname' name='firstname' value = <?php echo $father_last_name?> placeholder='First Name' required>
                                                <div class="invalid-feedback">
                                                    Please enter first name
                                                </div>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='middlename'>Middle Name</label>
                                                <input type='text' class='form-control' id='middlename' name='middlename' value = <?php echo $father_first_name?> placeholder='Middle Name' required>
                                                <div class="invalid-feedback">
                                                    Please enter middle name
                                                </div>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='extensionname'>Extension Name</label>
                                                <input type='text' class='form-control' id='extensionname' name='extensionname' value = <?php echo $father_ext_name?> placeholder='Extension Name'>
                                            </div>
                                        </div>
                                        <div class='form-row row'>
                                            <div class='form-group col-md-6'>
                                                <label for='middlename'>Contact Number</label>
                                                <input type='text' class='form-control' id='contactnumber' name='contactnumber' value = <?php echo $father_cp_no?> placeholder='Contact Number'>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='extensionname'>Occupation</label>
                                                <input type='text' class='form-control' id='occupation' name='occupation'  placeholder='Occupation' value = <?php echo $father_occupation?>>
                                            </div>
                                        </div>

                                        <h5>MOTHER</h5>
                                        <div class='form-row row'>
                                            <div class='form-group col-md-6'>
                                                <label for='lastname'>Last Name</label>
                                                <input type='text' class='form-control' id='lastname' name='lastname' value = <?php echo $mother_last_name?> placeholder='Last Name' required>
                                                <div class="invalid-feedback">
                                                    Please enter mother's last name
                                                </div>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='firstname'>First Name</label>
                                                <input type='text' class='form-control' id='firstname' name='firstname' value = <?php echo $mother_first_name?> placeholder='First Name' required>
                                                <div class="invalid-feedback">
                                                    Please enter mother's first name
                                                </div>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='middlename'>Middle Name</label>
                                                <input type='text' class='form-control' id='middlename' name='middlename' value = <?php echo $mother_middle_name?> placeholder='Middle Name' required>
                                                <div class="invalid-feedback">
                                                    Please enter mother's middle name
                                                </div>
                                            </div>
                                        </div>
                                        <div class='form-row row'>
                                            <div class='form-group col-md-6'>
                                                <label for='middlename'>Contact Number</label>
                                                <input type='text' class='form-control' id='contactnumber' name='contactnumber'value = <?php echo $mother_cp_no?>  placeholder='Contact Number'>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='extensionname'>Occupation</label>
                                                <input type='text' class='form-control' id='occupation' name='occupation' value = <?php echo $mother_occupation?> placeholder='Occupation'>

                                            </div>
                                        </div>
                                        <h5>GUARDIAN</h5>
                                        <div class='form-row row'>
                                            <div class='form-group col-md-6'>
                                                <label for='lastname'>Last Name</label>
                                                <input type='text' class='form-control' id='lastname' name='lastname' value = <?php echo $guardian_last_name?> placeholder='Last Name' required>
                                                <div class="invalid-feedback">
                                                    Please enter guardian's last name
                                                </div>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='firstname'>First Name</label>
                                                <input type='text' class='form-control' id='firstname' name='firstname' value = <?php echo $guardian_first_name?> placeholder='First Name' required>
                                                <div class="invalid-feedback">
                                                    Please enter guardian's first name
                                                </div>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='middlename'>Middle Name</label>
                                                <input type='text' class='form-control' id='middlename' name='middlename' value = <?php echo $guardian_middle_name?> placeholder='Middle Name' required>
                                                <div class="invalid-feedback">
                                                    Please enter guardian's middle name
                                                </div>
                                            </div>
                                        
                                        </div>
                                        <div class='form-row row'>
                                            <div class='form-group col-md-6'>
                                                <label for='middlename'>Contact Number</label>
                                                <input type='text' class='form-control' id='contactnumber' name='contactnumber' value = <?php echo $guardian_cp_no?> placeholder='Contact Number'>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for='extensionname'>Occupation</label>
                                                <input type='text' class='form-control' id='relationship' name='relationship' value = <?php echo $guardian_relationship?> placeholder='Relationship'>
                                            </div>
                                        </div>
                                    </div>

                                </form>

                            </div>
                            </div>
                            <div class="tab-pane fade" id="step3">

                            </div>
                        </div>

                        <div class="container mt-4 me-1">
                            
                            
                            <div class="row">
                                <div class="mt-4 d-flex flex-row-reverse">
                                    <button type="button" class="btn btn-success me-2">Save</button>
                                    <button type="button" class="btn btn-danger btn-space">Cancel</button>
                                </div>
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
<!-- VALIDATION -->
<script>
    var forms = document.querySelectorAll('.needs-validation');

    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
</script>
<script type="text/javascript" src="../js/admin/subject.js"></script>

</html>
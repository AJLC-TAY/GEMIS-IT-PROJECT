<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
session_start();
require_once("../class/Administration.php");
$admin = new Administration();
$userProfile = $admin->getProfile("ST");
$stud_id = $userProfile->get_stud_id();
$user_id_no = $userProfile->get_id_no();
$lrn = $userProfile->get_lrn();
$lname = $userProfile->get_last_name();
$fname = $userProfile->get_first_name();
$mname = $userProfile->get_middle_name();
$extname = $userProfile->get_ext_name();
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
$province = $address['province'];
$zip = $address['zipcode'];

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
<title>Step Sample | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
<script src="validate.js"></script>
</head>

<body>
    <!-- SPINNER START -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <form id="enrollment-form" enctype="multipart/form-data" action="action.php" method="POST" novalidate>
                                <div id="stepper" class="bs-stepper">
                                    <div id="header" class="bs-stepper-header">
                                        <div class="step" data-target="#test-l-1">
                                            <button type="button" class="btn step-trigger">
                                                <span class="bs-stepper-circle">1</span>
                                                <span class="bs-stepper-label">First step</span>
                                            </button>
                                        </div>
                                        <div class="line"></div>
                                        <div class="step" data-target="#test-l-2">
                                            <button type="button" class="btn step-trigger">
                                                <span class="bs-stepper-circle">2</span>
                                                <span class="bs-stepper-label">Second step</span>
                                            </button>
                                        </div>
                                        <div class="line"></div>
                                        <div class="step" data-target="#test-l-3">
                                            <button type="button" class="btn step-trigger">
                                                <span class="bs-stepper-circle">3</span>
                                                <span class="bs-stepper-label">Third step</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bs-stepper-content">
                                        <div id="test-l-1" class="content">
                                            <div class="card body w-100 h-auto p-4">
                                                <!-- STEP 1 -->
                                                <h4 class="fw-bold">STUDENT INFORMATION</h4>
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label for="psa" class="col-form-label">PSA Birth Certificate</label>
                                                        <!-- image heree -->
                                                        <input id="psa" class="form-control" name="psa" type="text" value="<?php echo $psa_birth_cert; ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="lrn" class="col-form-label">Learner's Reference Number</label>
                                                        <input id="lrn" class="form-control" name="lrn" type="number" value="<?php echo $lrn; ?>">
                                                    </div>
                                                </div>
                                                <!-- NAME -->
                                                <div class="row mt-3">
                                                    <div class="col-md-4">
                                                        <label class="col-form-label">Last Name</label>
                                                        <input class="form-control" name="last-name" type="text" value="<?php echo $lname; ?>">
                                                    </div>

                                                    <div class='col-md-5'>
                                                        <label class="col-form-label">First Name</label>
                                                        <input class="form-control" name="first-name" type="text" value="<?php echo $fname; ?>">
                                                    </div>

                                                    <div class='col-md-3'>
                                                        <label class="col-form-label">Middle Name</label>
                                                        <input class="form-control" name="middle-name" type="text" value="<?php echo $mname; ?>">
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class='col-md-4'>
                                                        <label class="col-form-label">Extension Name (if applicable)</label>
                                                        <input class="form-control" name="ext-name" type="text" value="<?php echo $extname; ?>" />
                                                    </div>
                                                    <!-- CONTACT NO -->
                                                    <div class='col-md-4'>
                                                        <label class="col-form-label">Contact Number</label>
                                                        <input class="form-control number" name="cp-no" type="text" value="<?php echo $cp_no; ?>">
                                                    </div>
                                                    <!-- SEX -->
                                                    <div class="col-md-4">
                                                        <label class="col-form-label">Sex</label>
                                                        <div class="d-flex">
                                                            <?php $sexOpt = ["m" => "Male", "f" => "Female"];
                                                            foreach ($sexOpt as $id => $value) {
                                                                echo "<div class='form-check me-3'>
                                                                        <input class='form-check-input' type='radio' name='sex' id='$id' value='$id' " . (($sex == $value) ? "checked" : "") . ">
                                                                        <label class='form-check-label' for='$id'>
                                                                        $value
                                                                        </label>
                                                                    </div>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <!-- BIRTH INFO -->
                                                    <div class="col-md-4">
                                                        <label class="col-form-label">Birthdate</label>
                                                        <div class='input-group date' id='datepicker'>
                                                            <input type='date' name="birthdate" class="form-control" value="<?php echo $birthdate; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class='col-md-5'>
                                                        <label class="col-form-label">Birth Place</label>
                                                        <input class="form-control" name="birth-place" type="text" value="<?php echo $birth_place; ?>">
                                                    </div>
                                                    <!-- AGE -->
                                                    <div class='col-md-3'>
                                                        <label class="col-form-label">Age</label>
                                                        <input class="form-control number" name="age" type="text" value="<?php echo $age; ?>">
                                                    </div>
                                                </div>

                                                <div class="row mt-5">
                                                    <!-- INDEGINOUS INFO -->
                                                    <div class="col-md-4">
                                                        <label class="col-form-label me-4">Belonging to any Indeginous Group? </label>
                                                        <div class="d-flex">
                                                            <?php
                                                            echo "<div class='form-check me-4'>
                                                                        <input class='form-check-input' type='radio' name='group' id='yes' value='1' " . (($indigenous_group != NULL) ? "checked" : "") . ">
                                                                        <label class='form-check-label' for='yes'> Yes </label>
                                                                </div>
                                                                <div class='form-check'>
                                                                    <input class='form-check-input' type='radio' name='group' id='no' value='0' " . (($indigenous_group == NULL) ? "checked" : "") . ">
                                                                        <label class='form-check-label' for='yes'> No </label>
                                                                </div>";
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class='col-md-8'>
                                                        <label class="col-form-label text-start">If yes, please specify</label>
                                                        <input class="form-control" name="group-name" type="text" value="<?php echo $indigenous_group; ?>">
                                                    </div>
                                                </div>


                                                <!-- MOTHER TONGUE & RELIGION -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-form-label">Mother Tongue</label>
                                                        <input class="form-control" name="mother-tongue" type="text" value="<?php echo $mother_tongue; ?>">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="col-form-label">Religion</label>
                                                        <input class="form-control" name="religion" type="text" value="<?php echo $religion; ?>">
                                                    </div>
                                                </div>

                                                <!-- ADDRESS -->
                                                <div class="row mt-5">
                                                    <h5>ADDRESS</h5>
                                                    <div class="col-md-2">
                                                        <label class="col-form-label">House No.</label>
                                                        <input class="form-control" name="house-no" type="text" value="<?php echo $house_no; ?>">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="col-form-label">Street</label>
                                                        <input class="form-control" name="street" type="text" value="<?php echo $street; ?>">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="col-form-label">Barangay</label>
                                                        <input class="form-control" name="barangay" type="text" value="<?php echo $barangay; ?>">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="col-form-label">City/Municipality</label>
                                                        <input class="form-control" name="city-muni" type="text" value="<?php echo $city; ?>">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="col-form-label">Province</label>
                                                        <input class="form-control" name="province" type="text" value="<?php echo $province; ?>">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label class="col-form-label">Zip Code</label>
                                                        <input class="form-control number" name="zip-code" type="text" value="<?php echo $zip; ?>">
                                                    </div>
                                                </div>
                                                <div class="row justify-content-end mt-3">
                                                    <div class="col-auto">
                                                        <a href="#stepper" class="btn btn-primary" onclick="stepper.next()">Next</a>

                                                        <!-- <button class="btn btn-primary" onclick="stepper.next()">Next</button> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- STEP 1 END -->
                                        <!-- STEP 2 -->
                                        <div id="test-l-2" class="content">
                                            <div class="card w-100 h-auto mt-4 p-4">
                                                <h4 class="fw-bold"> PARENT/GUARDIAN'S INFORMATION</h4>

                                                <!-- FATHER -->
                                                <div class='form-row row mt-3'>
                                                    <h5>FATHER</h5>
                                                    <div class='form-group col-md-3'>
                                                        <label for='f-lastname'>Last Name</label>
                                                        <input type='text' class='form-control' id='f-lastname' name='f-lastname' value="<?php echo $father_last_name; ?>" placeholder='Last Name' required>
                                                        <div class="messages"></div>
                                                    </div>
                                                    <div class='form-group col-md-4'>
                                                        <label for='f-firstname'>First Name</label>
                                                        <input type='text' class='form-control' id='f-firstname' name='f-firstname' value="<?php echo $father_last_name; ?>" placeholder='First Name' required>
                                                    </div>
                                                    <div class='form-group col-md-3'>
                                                        <label for='f-middlename'>Middle Name</label>
                                                        <input type='text' class='form-control' id='f-middlename' name='f-middlename' value="<?php echo $father_first_name; ?>" placeholder='Middle Name' required>
                                                    </div>
                                                    <div class='form-group col-md-2'>
                                                        <label for='f-extensionname'>Extension Name</label>
                                                        <input type='text' class='form-control' id='f-extensionname' name='f-extensionname' value="<?php echo $father_ext_name; ?>" placeholder='Ext. Name'>
                                                    </div>
                                                </div>
                                                <div class='form-row row'>
                                                    <div class='form-group col-md-4'>
                                                        <label for='f-contactnumber'>Contact Number</label>
                                                        <input type='text' class='form-control' id='f-contactnumber' name='f-contactnumber' value="<?php echo $father_cp_no; ?>" placeholder='Contact Number'>
                                                    </div>
                                                    <div class='form-group col-md-8'>
                                                        <label for='f-occupation'>Occupation</label>
                                                        <input type='text' class='form-control' id='f-occupation' name='f-occupation' placeholder='Occupation' value="<?php echo $father_occupation ?>">
                                                    </div>
                                                </div>
                                                <!-- MOTHER -->
                                                <div class='form-row row mt-3'>
                                                    <h5>MOTHER</h5>
                                                    <div class='form-group col-md-3'>
                                                        <label for='m-lastname'>Maiden Last Name</label>
                                                        <input type='text' class='form-control' id='m-lastname' name='m-lastname' value="<?php echo $mother_last_name; ?>" placeholder='Last Name' required>
                                                    </div>
                                                    <div class='form-group col-md-4'>
                                                        <label for='m-firstname'>First Name</label>
                                                        <input type='text' class='form-control' id='m-firstname' name='m-firstname' value="<?php echo $mother_first_name; ?>" placeholder='First Name' required>
                                                    </div>
                                                    <div class='form-group col-md-3'>
                                                        <label for='m-middlename'>Middle Name</label>
                                                        <input type='text' class='form-control' id='m-middlename' name='m-middlename' value="<?php echo $mother_middle_name; ?>" placeholder='Middle Name' required>
                                                    </div>
                                                </div>
                                                <div class='form-row row'>
                                                    <div class='form-group col-md-4'>
                                                        <label for='m-contactnumber'>Contact Number</label>
                                                        <input type='text' class='form-control' id='m-contactnumber' name='m-contactnumber' value="<?php echo $mother_cp_no; ?>" placeholder='Contact Number'>
                                                    </div>
                                                    <div class='form-group col-md-6'>
                                                        <label for='m-occupation'>Occupation</label>
                                                        <input type='text' class='form-control' id='m-occupation' name='m-occupation' value="<?php echo $mother_occupation; ?>" placeholder='Occupation'>
                                                    </div>
                                                </div>
                                                <!-- GUARDIAN -->
                                                <div class='form-row row mt-3'>
                                                    <h5>GUARDIAN</h5>
                                                    <div class='form-group col-md-3'>
                                                        <label for='g-lastname'>Last Name</label>
                                                        <input type='text' class='form-control' id='g-lastname' name='g-lastname' value="<?php echo $guardian_last_name; ?>" placeholder='Last Name' required>
                                                    </div>
                                                    <div class='form-group col-md-4'>
                                                        <label for='g-firstname'>First Name</label>
                                                        <input type='text' class='form-control' id='g-firstname' name='g-firstname' value="<?php echo $guardian_first_name; ?>" placeholder='First Name' required>
                                                    </div>
                                                    <div class='form-group col-md-3'>
                                                        <label for='g-middlename'>Middle Name</label>
                                                        <input type='text' class='form-control' id='g-middlename' name='g-middlename' value="<?php echo $guardian_middle_name; ?>" placeholder='Middle Name' required>
                                                    </div>
                                                </div>
                                                <div class='form-row row'>
                                                    <div class='form-group col-md-4'>
                                                        <label for='g-contactnumber'>Contact Number</label>
                                                        <input type='text' class='form-control' id='g-contactnumber' name='g-contactnumber' value="<?php echo $guardian_cp_no; ?>" placeholder='Contact Number'>
                                                    </div>
                                                    <div class='form-group col-md-6'>
                                                        <label for='g-relationship'>Relationship</label>
                                                        <input type='text' class='form-control' id='g-relationship' name='g-relationship' value="<?php echo $guardian_relationship; ?>" placeholder='Relationship'>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between flex-row-reverse mt-4">
                                                    <a href="#stepper" class="btn btn-primary" onclick="stepper.next()">Next</a>
                                                    <a href="#stepper" class="btn btn-primary" onclick="stepper.previous()">Back</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- STEP 2 END -->
                                        <!-- STEP 3 -->
                                        <div id="test-l-3" class="content">
                                            <div class="card w-100 h-auto mt-4 p-4">
                                                <label class="col-form-label me-4">Balik Aral Student? </label>
                                                <div class="d-flex">
                                                    <?php
                                                    echo "<div class='form-check me-4'>
                                                                <input class='form-check-input' type='radio' name='balik-aral' id='yes' value='1' " . (($indigenous_group != NULL) ? "checked" : "") . ">
                                                                <label class='form-check-label' for='yes'> Yes </label>
                                                        </div>
                                                        <div class='form-check'>
                                                            <input class='form-check-input' type='radio' name='balik-aral' id='no' value='0' " . (($indigenous_group == NULL) ? "checked" : "") . ">
                                                                <label class='form-check-label' for='yes'> No </label>
                                                        </div>";
                                                    ?>
                                                </div>
                                                <div class='form-group col-md-5 d-flex flex-column'>
                                                    <label for='photo' class='form-label'>Student ID Photo</label>
                                                    <input id='upload' class='form-control form-control-sm' id='photo' name='image-studentid' type='file' accept='image/png, image/jpg, image/jpeg'>
                                                </div>
                                                <div class='form-group col-md-5 d-flex flex-column'>
                                                    <label for='photo' class='form-label'>Student Form 137</label>
                                                    <input id='upload' class='form-control form-control-sm' id='photo' name='image-form' type='file' accept='image/png, image/jpg, image/jpeg'>
                                                </div>
                                                <div class='form-group col-md-5 d-flex flex-column'>
                                                    <label for='photo' class='form-label'>PSA Birth Certificate</label>
                                                    <input id='upload' class='form-control form-control-sm' id='photo' name='image-psa' type='file' accept='image/png, image/jpg, image/jpeg'>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label class="col-form-label">Semester</label>
                                                        <input class="form-control" name="semester" type="text" value="">
                                                    </div>

                                                    <div class='col-md-4'>
                                                        <label class="col-form-label">Track</label>
                                                        <div class="input-group mb-3">
                                                            <select class="form-select" name="track" id="track-select">
                                                                <?php
                                                                $curriculum_list = $admin->listCurriculum('curriculum');
                                                                foreach ($curriculum_list as $curriculum) {
                                                                    echo "<option value='{$curriculum->get_cur_code()}'>{$curriculum->get_cur_name()}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class='col-md-3'>
                                                        <label class="col-form-label">Strand</label>
                                                        <select class="form-select" name="program" id="program-select">
                                                            <?php
                                                            $programs = $admin->listPrograms('program');
                                                            foreach ($programs as $program) {
                                                                echo "<option value='{$program->get_prog_code()}'>{$program->get_prog_desc()}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class='col-md-2'>
                                                        <label for="grade-select" class="col-form-label">Grade Level</label>

                                                        <select class="form-select" name="grade-level" id="grade-select">
                                                            <?php
                                                            $grade_level = ["11" => 11, "12" => 12];
                                                            foreach ($grade_level as $id => $value) {
                                                                echo "<option value='$id'>$value</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="d-flex justify-content-between flex-row-reverse mt-4">
                                                    <input type="hidden" name="action" value="enroll">
                                                    <input class="btn btn-success" form="enrollment-form" type="submit" value="Submit">
                                                    <a href="#stepper" class="btn btn-primary" onclick="stepper.previous()">Back</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- STEPPER END -->
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!-- MAIN CONTENT END-->
            <!-- FOOTER -->
            <?php include_once("../inc/footer.html"); ?>
            <!-- FOOTER END -->
        </section>
    </section>
</body>

<!-- BOOTSTRAP TABLE JS -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<!--CUSTOM JS-->
<script src="../js/common-custom.js"></script>
<script>
    preload("#faculty")

    var stepper = new Stepper($('#stepper')[0])
    $(function() {
        // var stepper = new Stepper($('.bs-stepper')[0])
        //
        // $("[form=enrollment-form]").click(() => $('#enrollment-form').submit())
        //
        // $('#enrollment-form').submit( function (e) {
        //     e.preventDefault()
        //     let formData = new FormData($(this)[0]);
        //     $.ajax({
        //         url: "action.php",
        //         data: formData,
        //         success: function (data) {
        //             console.log(data);
        //         }
        //     });
        // })
        hideSpinner()
    })
</script>
<script>
    (function() {
        // Before using it we must add the parse and format functions
        // Here is a sample implementation using moment.js
        validate.extend(validate.validators.datetime, {
            // The value is guaranteed not to be null or undefined but otherwise it
            // could be anything.
            parse: function(value, options) {
                return +moment.utc(value);
            },
            // Input is a unix timestamp
            format: function(value, options) {
                var format = options.dateOnly ? "YYYY-MM-DD" : "YYYY-MM-DD hh:mm:ss";
                return moment.utc(value).format(format);
            }
        });

        // These are the constraints used to validate the form
        var constraints = {
            email: {
                // Email is required
                presence: true,
                // and must be an email (duh)
                email: true
            },
            password: {
                // Password is also required
                presence: true,
                // And must be at least 5 characters long
                length: {
                    minimum: 5
                }
            },
            "confirm-password": {
                // You need to confirm your password
                presence: true,
                // and it needs to be equal to the other password
                equality: {
                    attribute: "password",
                    message: "^The passwords does not match"
                }
            },
            username: {
                // You need to pick a username too
                presence: true,
                // And it must be between 3 and 20 characters long
                length: {
                    minimum: 3,
                    maximum: 20
                },
                format: {
                    // We don't allow anything that a-z and 0-9
                    pattern: "[a-z]+",
                    // but we don't care if the username is uppercase or lowercase
                    flags: "i",
                    message: "can only contain a-z and 0-9"
                }
            }
        };

        // Hook up the form so we can prevent it from being posted
        var form = document.querySelector("form#main");
        form.addEventListener("submit", function(ev) {
            ev.preventDefault();
            handleFormSubmit(form);
        });

        // Hook up the inputs to validate on the fly
        var inputs = document.querySelectorAll("input, textarea, select")
        for (var i = 0; i < inputs.length; ++i) {
            inputs.item(i).addEventListener("change", function(ev) {
                var errors = validate(form, constraints) || {};
                showErrorsForInput(this, errors[this.name])
            });
        }

        function handleFormSubmit(form, input) {
            // validate the form against the constraints
            var errors = validate(form, constraints);
            // then we update the form to reflect the results
            showErrors(form, errors || {});
            if (!errors) {
                showSuccess();
            }
        }

        // Updates the inputs with the validation errors
        function showErrors(form, errors) {
            // We loop through all the inputs and show the errors for that input
            _.each(form.querySelectorAll("input[name], select[name]"), function(input) {
                // Since the errors can be null if no errors were found we need to handle
                // that
                showErrorsForInput(input, errors && errors[input.name]);
            });
        }

        // Shows the errors for a specific input
        function showErrorsForInput(input, errors) {
            // This is the root of the input
            var formGroup = closestParent(input.parentNode, "form-group")
                // Find where the error messages will be insert into
                ,
                messages = formGroup.querySelector(".messages");
            // First we remove any old messages and resets the classes
            resetFormGroup(formGroup);
            // If we have errors
            if (errors) {
                // we first mark the group has having errors
                formGroup.classList.add("has-error");
                // then we append all the errors
                _.each(errors, function(error) {
                    addError(messages, error);
                });
            } else {
                // otherwise we simply mark it as success
                formGroup.classList.add("has-success");
            }
        }

        // Recusively finds the closest parent that has the specified class
        function closestParent(child, className) {
            if (!child || child == document) {
                return null;
            }
            if (child.classList.contains(className)) {
                return child;
            } else {
                return closestParent(child.parentNode, className);
            }
        }

        function resetFormGroup(formGroup) {
            // Remove the success and error classes
            formGroup.classList.remove("has-error");
            formGroup.classList.remove("has-success");
            // and remove any old messages
            _.each(formGroup.querySelectorAll(".help-block.error"), function(el) {
                el.parentNode.removeChild(el);
            });
        }

        // Adds the specified error with the following markup
        // <p class="help-block error">[message]</p>
        function addError(messages, error) {
            var block = document.createElement("p");
            block.classList.add("help-block");
            block.classList.add("error");
            block.innerText = error;
            messages.appendChild(block);
        }

        function showSuccess() {
            // We made it \:D/
            alert("Success!");
        }
    })();
</script>

</html>
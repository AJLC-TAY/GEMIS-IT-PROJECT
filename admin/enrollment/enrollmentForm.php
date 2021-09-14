<?php
require_once("../class/Administration.php");
$admin = new Administration();

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if (!in_array($action, ['add', 'edit'])) {
        die ("<div class='container'><h5>Page not found</h5></div>");;
    }
    if ($action === 'add') {
        $stud_id = '';
        $user_id_no = '';
        $lrn = '';
        $lname = '';
        $fname = '';
        $mname = '';
        $extname = '';
        $sex = '';
        $age = '';
        $birthdate = '';
        $birth_place = '';
        $indigenous_group = '';
        $mother_tongue = '';
        $religion = '';

        $house_no = '';
        $street = '';
        $barangay = '';
        $city = '';
        $province = '';
        $zip = '';

        $cp_no = '';
        $psa_birth_cert = '';
        $belong_to_ipcc = '';
        $id_picture = '';
        $section = '';

        $parents = ['mother', 'father'];
        foreach ($parents as $par) {
            ${$par . '_first_name'} = '';
            ${$par . '_last_name'} = '';
            ${$par . '_middle_name'} = '';
            ${$par . '_ext_name'} = '';
            ${$par . '_occupation'} = '';
            ${$par . '_cp_no'} = '';
        }

        $guardian_first_name = '';
        $guardian_last_name = '';
        $guardian_middle_name = '';
        $guardian_cp_no = '';
        $guardian_relationship = '';

    } else if ($action === 'edit') {
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
        if (!is_null($parents)) {
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
        if (!is_null($guardian)) {
            $guardian_first_name = $guardian['fname'];
            $guardian_last_name = $guardian['lname'];
            $guardian_middle_name = $guardian['mname'];
            $guardian_cp_no = $guardian['cp_no'];
            $guardian_relationship = $guardian['relationship'];
        }
    }
}
?>
<!-- HEADER -->
<header id="main-header">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php">Enrollment</a></li>
            <li class="breadcrumb-item active">Form</li>
        </ol>
    </nav>
    <div class="container px-4 text-center">
        <h2>Enrollment Form</h2>
    </div>
</header>
<form id="enrollment-form" class="needs-validation" enctype="multipart/form-data" action="action.php" method="POST" novalidate>
    <div id="stepper" class="bs-stepper">
        <div id="header" class="bs-stepper-header w-75 mx-auto">
            <div class="step mx-5" data-target="#test-l-1">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Part</span>
                    <span class="bs-stepper-circle">1</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step mx-5" data-target="#test-l-2">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Part</span>
                    <span class="bs-stepper-circle">2</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step mx-5" data-target="#test-l-3">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Part</span>
                    <span class="bs-stepper-circle">3</span>
                </button>
            </div>
        </div>
        <div class="bs-stepper-content">
            <div id="test-l-1" class="content">
                <div class="card body w-100 h-auto p-4">
                    <!-- STEP 1 -->
                    <h4 class="fw-bold">Student Information</h4>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="psa" class="col-form-label">PSA Birth Certificate</label>
                            <!-- image heree -->
                            <input id="psa" class="form-control" name="psa" type="text"  value="<?php echo $psa_birth_cert; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="lrn" class="col-form-label">Learner's Reference Number</label>
                            <input id="lrn" class="form-control" name="lrn" type="number"  value="<?php echo $lrn; ?>">
                        </div>
                    </div>
                    <!-- NAME -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="col-form-label">Last Name</label>
                            <input class="form-control" name="last-name" type="text" value="<?php echo $lname; ?>" >
                        </div>

                        <div class='col-md-5'>
                            <label class="col-form-label">First Name</label>
                            <input class="form-control" name="first-name" type="text" value="<?php echo $fname; ?>" >
                        </div>

                        <div class='col-md-3'>
                            <label class="col-form-label">Middle Name</label>
                            <input class="form-control" name="middle-name" type="text" value = "<?php echo $mname; ?>" >
                        </div>
                    </div>


                    <div class="row">
                        <div class='col-md-4'>
                            <label class="col-form-label">Extension Name (if applicable)</label>
                            <input class="form-control" name="ext-name" type="text" value = "<?php echo $extname; ?>"/>
                        </div>
                        <!-- CONTACT NO -->
                        <div class='col-md-4'>
                            <label class="col-form-label">Contact Number</label>
                            <input class="form-control number" name="cp-no" type="text" value ="<?php echo $cp_no; ?>" >
                        </div>
                        <!-- SEX -->
                        <div class="col-md-4">
                            <label class="col-form-label">Sex</label>
                            <div class="d-flex">
                                <?php  $sexOpt = ["m" => "Male", "f" => "Female"];
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
                                <input type='date' name="birthdate" class="form-control" value = "<?php echo $birthdate;?>" />
                            </div>
                        </div>
                        <div class='col-md-5'>
                            <label class="col-form-label">Birth Place</label>
                            <input class="form-control" name="birth-place" type="text" value = "<?php echo $birth_place;?>" >
                        </div>
                        <!-- AGE -->
                        <div class='col-md-3'>
                            <label class="col-form-label">Age</label>
                            <input class="form-control number" name="age" type="text" value ="<?php echo $age; ?>" >
                        </div>
                    </div>

                    <div class="row mt-5">
                        <!-- INDEGINOUS INFO -->
                        <div class="col-md-4">
                            <label class="col-form-label me-4">Belonging to any Indeginous Group? </label>
                            <div class="d-flex">
                                <?php
                                echo "<div class='form-check me-4'>
                                                <input class='form-check-input' type='radio' name='group' id='yes' value='Yes' " . (($indigenous_group != NULL) ? "checked" : "") . ">
                                                <label class='form-check-label' for='yes'> Yes </label>
                                        </div>
                                        <div class='form-check'>
                                            <input class='form-check-input' type='radio' name='group' id='no' value='No' " . (($indigenous_group == NULL) ? "checked" : "") . ">
                                                <label class='form-check-label' for='yes'> No </label>
                                        </div>";
                                ?>
                            </div>
                        </div>
                        <div class='col-md-8'>
                            <label class="col-form-label text-start">If yes, please specify</label>
                            <input class="form-control" name="group-name" type="text" value = "<?php echo $indigenous_group; ?>">
                        </div>
                    </div>


                    <!-- MOTHER TONGUE & RELIGION -->
                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-form-label">Mother Tongue</label>
                            <input class="form-control" name="mother-tongue" type="text" value = "<?php echo $mother_tongue; ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="col-form-label">Religion</label>
                            <input class="form-control" name="religion" type="text" value = "<?php echo $religion; ?>">
                        </div>
                    </div>

                    <!-- ADDRESS -->
                    <div class="row mt-5">
                        <h5>Address</h5>
                        <div class="col-md-2">
                            <label class="col-form-label">House No.</label>
                            <input class="form-control" name="house-no" type="text" value = "<?php echo $house_no; ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="col-form-label">Street</label>
                            <input class="form-control" name="street" type="text" value = "<?php echo $street; ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="col-form-label">Barangay</label>
                            <input class="form-control" name="barangay" type="text" value = "<?php echo $barangay; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-form-label">City/Municipality</label>
                            <input class="form-control" name="city-muni" type="text" value = "<?php echo $city; ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="col-form-label">Province</label>
                            <input class="form-control" name="province" type="text" value = "<?php echo $province; ?>">
                        </div>

                        <div class="col-md-2">
                            <label class="col-form-label">Zip Code</label>
                            <input class="form-control number" name="zip-code" type="text" value = "<?php echo $zip; ?>">
                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <div class="col-auto">
                            <a href="#" class="btn btn-secondary" onclick="stepper.next()">Next</a>
                            <!-- <button class="btn btn-secondary" onclick="stepper.next()">Next</button> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- STEP 1 END -->
            <!-- STEP 2 -->
            <div id="test-l-2" class="content">
                <div class="card w-100 h-auto mt-4 p-4">
                    <h4 class="fw-bold"> Parent | Guardian's Information</h4>

                    <!-- FATHER -->
                    <div class='form-row row mt-3'>
                        <h5>Father</h5>
                        <div class='form-group col-md-3'>
                            <label for='f-lastname'>Last Name</label>
                            <input type='text' class='form-control' id='f-lastname' name='f-lastname' value = "<?php echo $father_last_name; ?>" placeholder='Last Name' required>
                            <div class="invalid-feedback">
                                Please enter father's first name
                            </div>
                        </div>
                        <div class='form-group col-md-4'>
                            <label for='f-firstname'>First Name</label>
                            <input type='text' class='form-control' id='f-firstname' name='f-firstname' value = "<?php echo $father_last_name; ?>" placeholder='First Name' required>
                            <div class="invalid-feedback">
                                Please enter father's first normalizer_is_normalized
                            </div>
                        </div>
                        <div class='form-group col-md-3'>
                            <label for='f-middlename'>Middle Name</label>
                            <input type='text' class='form-control' id='f-middlename' name='f-middlename' value = "<?php echo $father_first_name; ?>" placeholder='Middle Name' required>
                            <div class="invalid-feedback">
                                Please enter father's middle name
                            </div>
                        </div>
                        <div class='form-group col-md-2'>
                            <label for='f-extensionname'>Extension Name</label>
                            <input type='text' class='form-control' id='f-extensionname' name='f-extensionname' value ="<?php echo $father_ext_name; ?>" placeholder='Ext. Name'>
                        </div>
                    </div>
                    <div class='form-row row'>
                        <div class='form-group col-md-4'>
                            <label for='f-contactnumber'>Contact Number</label>
                            <input type='text' class='form-control' id='f-contactnumber' name='f-contactnumber' value = "<?php echo $father_cp_no; ?>" placeholder='Contact Number'>
                        </div>
                        <div class='form-group col-md-8'>
                            <label for='f-occupation'>Occupation</label>
                            <input type='text' class='form-control' id='f-occupation' name='f-occupation'  placeholder='Occupation' value = "<?php echo $father_occupation?>">
                        </div>
                    </div>
                    <!-- MOTHER -->
                    <div class='form-row row mt-3'>
                        <h5>Mother</h5>
                        <div class='form-group col-md-3'>
                            <label for='m-lastname'>Maiden Last Name</label>
                            <input type='text' class='form-control' id='m-lastname' name='m-lastname' value = "<?php echo $mother_last_name; ?>" placeholder='Last Name' required>
                            <div class="invalid-feedback">
                                Please enter mother's maiden last name
                            </div>
                        </div>
                        <div class='form-group col-md-4'>
                            <label for='m-firstname'>First Name</label>
                            <input type='text' class='form-control' id='m-firstname' name='m-firstname' value = "<?php echo $mother_first_name; ?>" placeholder='First Name' required>
                            <div class="invalid-feedback">
                                Please enter mother's first name
                            </div>
                        </div>
                        <div class='form-group col-md-3'>
                            <label for='m-middlename'>Middle Name</label>
                            <input type='text' class='form-control' id='m-middlename' name='m-middlename' value = "<?php echo $mother_middle_name; ?>" placeholder='Middle Name' required>
                            <div class="invalid-feedback">
                                Please enter mother's middle name
                            </div>
                        </div>
                    </div>
                    <div class='form-row row'>
                        <div class='form-group col-md-4'>
                            <label for='m-contactnumber'>Contact Number</label>
                            <input type='text' class='form-control' id='m-contactnumber' name='m-contactnumber'value = "<?php echo $mother_cp_no; ?>"  placeholder='Contact Number'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='m-occupation'>Occupation</label>
                            <input type='text' class='form-control' id='m-occupation' name='m-occupation' value = "<?php echo $mother_occupation; ?>" placeholder='Occupation'>
                        </div>
                    </div>
                    <!-- GUARDIAN -->
                    <div class='form-row row mt-3'>
                        <h5>Guardian</h5>
                        <div class='form-group col-md-3'>
                            <label for='g-lastname'>Last Name</label>
                            <input type='text' class='form-control' id='g-lastname' name='g-lastname' value = "<?php echo $guardian_last_name; ?>" placeholder='Last Name' required>
                            <div class="invalid-feedback">
                                Please enter guardian's last name
                            </div>
                        </div>
                        <div class='form-group col-md-4'>
                            <label for='g-firstname'>First Name</label>
                            <input type='text' class='form-control' id='g-firstname' name='g-firstname' value = "<?php echo $guardian_first_name; ?>" placeholder='First Name' required>
                            <div class="invalid-feedback">
                                Please enter guardian's first name
                            </div>
                        </div>
                        <div class='form-group col-md-3'>
                            <label for='g-middlename'>Middle Name</label>
                            <input type='text' class='form-control' id='g-middlename' name='g-middlename' value = "<?php echo $guardian_middle_name; ?>" placeholder='Middle Name' required>
                            <div class="invalid-feedback">
                                Please eter gurardian's middle name
                            </div>
                        </div>
                    </div>
                    <div class='form-row row'>
                        <div class='form-group col-md-4'>
                            <label for='g-contactnumber'>Contact Number</label>
                            <input type='text' class='form-control' id='g-contactnumber' name='g-contactnumber' value = "<?php echo $guardian_cp_no; ?>" placeholder='Contact Number'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='g-relationship'>Relationship</label>
                            <input type='text' class='form-control' id='g-relationship' name='g-relationship' value = "<?php echo $guardian_relationship; ?>" placeholder='Relationship'>
                        </div>
                    </div>

                    <div class="d-flex flex-row-reverse mt-4">
                        <a href="#" class="btn btn-secondary" onclick="stepper.next()">Next</a>
                        <a href="#" class="btn btn-secondary me-1" onclick="stepper.previous()">Back</a>
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
                        echo "<div class='form-check me-4'>"
                                ."<input class='form-check-input' type='radio' name='balik' id='yes' value='Yes' " . (!is_null($indigenous_group) ? "checked" : "") . ">"
                                ."<label class='form-check-label' for='yes'> Yes </label>"
                            ."</div>"
                            ."<div class='form-check'>"
                                ."<input class='form-check-input' type='radio' name='balik' id='no' value='No' " . (is_null($indigenous_group) ? "checked" : "") . ">"
                                ."<label class='form-check-label' for='no'> No </label>"
                            ."</div>";
                        ?>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="last-grade-level" class="col-form-label">Last Grade Level Completed</label>
                            <input id="last-grade-level" class="form-control" name="last-grade-level" type="number"  value="">
                        </div>
                        <div class="col-md-4">
                            <label for="last-sy" class="col-form-label">Last School Year Completed</label>
                            <input id="last-sy" class="form-control" name="last-sy" type="number"  value="">
                        </div>
                        <div class="col-md-4">
                            <label for="general-average" class="col-form-label">General Average</label>
                            <input id="general-average" class="form-control" name="general-average" type="number"  value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-9">
                            <label for="school-name" class="col-form-label">School Name</label>
                            <input id="school-name" class="form-control" name="school-name" type="text"  value="">
                        </div>
                        <div class="col-md-3">
                            <label for="school-id-no" class="col-form-label">School ID Number</label>
                            <input id="school-id-no" class="form-control" name="school-id-no" type="number"  value="">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="school-address" class="col-form-label">School Address</label>
                        <input id="school-address" class="form-control" name="school-address" type="text"  value="">
                    </div>
                    <div class='form-group col-md-5 d-flex flex-column'>
                        <label for='image-studentid' class='form-label'>Student ID Photo</label>
                        <input class='form-control form-control-sm' id='image-studentid' name='image-studentid' type='file' accept='image/png, image/jpg, image/jpeg'>
                    </div>
                    <div class='form-group col-md-5 d-flex flex-column'>
                        <label for='image-form' class='form-label'>Student Form 137</label>
                        <input class='form-control form-control-sm' id='image-form' name='image-form' type='file' accept='image/png, image/jpg, image/jpeg'>
                    </div>
                    <div class='form-group col-md-5 d-flex flex-column'>
                        <label for='image-psa' class='form-label'>PSA Birth Certificate</label>
                        <input class='form-control form-control-sm' id='image-psa' name='image-psa' type='file' accept='image/png, image/jpg, image/jpeg'>
                    </div>
                    <div class="row">
                        <p class="text-secondary"><small>Please enter the information <?php echo $_SESSION["user-type"] != 'ST' ? 'that the student' : 'you'; ?> will be enrolling this school year.</small></p>
<!--                        <div class="col-md-2">-->
<!--                            <label class="col-form-label">Semester</label>-->
<!--                            <input class="form-control" name="semester" type="text" value="" >-->
<!--                        </div>-->

                        <div class='col-md-4'>
                            <label class="col-form-label">Track</label>
                            <div class="input-group mb-3">
                                <select class="form-select" name="track" id="track-select">
                                    <?php
                                    $curriculum_list = $admin->listCurriculum('curriculum');
                                    foreach($curriculum_list as $curriculum) {
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
                                foreach($programs as $program) {
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
                                foreach($grade_level as $id => $value) {
                                    echo "<option value='$id'>$value</option>";                                                          }
                                ?>
                            </select>
                        </div>

                    </div>
                    <div class="d-flex flex-row-reverse mt-4">
                        <input type="hidden" name="action" value="enroll">
                        <input class="btn btn-success" form="enrollment-form" type="submit" value="Submit">
                        <a href="#" class="btn btn-secondary me-1" onclick="stepper.previous()">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- STEPPER END -->
</form>

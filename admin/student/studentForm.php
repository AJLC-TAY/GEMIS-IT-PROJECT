<?php
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
$form137 = $userProfile->get_form137();


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
        ${$parent . '_sex'} = $par['sex'];
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

const PROFILE_PATH = "../assets/profile.png";
const NO_FILE = "../assets/no_preview.jpg";

$image = !is_null($id_picture) ? (file_exists($id_picture) ? $id_picture : PROFILE_PATH) : PROFILE_PATH;
$psa = !is_null($id_picture) ? (file_exists($psa_birth_cert) ? $psa_birth_cert : NO_FILE) : NO_FILE;
$form137 = !is_null($id_picture) ? (file_exists($form137) ? $form137 : NO_FILE) : NO_FILE;
?>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="student.php">Student</a></li>
            <li class="breadcrumb-item active">Edit Student</li>
        </ol>
    </nav>
    <h3>Edit Student Information</h3>
</header>
<!-- CONTENT  -->
<form id='student-form' class='needs-validation' method='POST' action='action.php' enctype='multipart/form-data' novalidate>
    <!-- Photo -->
    <div class="form-row row">
        <div class='form-group col-md-4 d-flex flex-column'>
            <label for='photo' class='form-label'>Student Photo</label>
            <div class="image-preview-con">
                <img id='resultImg' src="<?php echo "../" . $image; ?>" alt='Profile image' class='rounded-circle w-100 h-100' />
                <div class='edit-img-con text-center'>
                    <p role='button' class="edit-text profile-photo opacity-0"><i class='bi bi-pencil-square me-2'></i>Edit</p>
                </div>
            </div>
            <input id='upload' class='form-control form-control-sm mt-2 w-75' id='photo' name='image' type='file' accept='image/png, image/jpg, image/jpeg'>
        </div>

        <div class='form-group col-md-4 d-flex flex-column'>
            <label for='photo' class='form-label'>PSA Birth Certificate</label>
            <div class="image-preview-con">
                <img id='psaResult' src="<?php echo "../" . $psa; ?>" alt='PSA image' class = "img-thumbnail w-100 h-100" />
                <div class='edit-img-con text-center'>
                    <p role='button' class="edit-text psa-photo opacity-0"><i class='bi bi-pencil-square me-2'></i>Edit</p>
                </div>
            </div>
            <input id='psaUpload' class='form-control form-control-sm mt-2 w-75' name='psaImage' type='file' accept='image/png, image/jpg, image/jpeg'>
        </div>
        <div class='form-group col-md-4 d-flex flex-column'>
            <label for='photo' class='form-label'>Form 137</label>
            <div class="image-preview-con">
                <img id='form137Result' src="<?php echo "../" . $form137; ?>" alt='form137 image' class = "img-thumbnail w-100 h-100" />
                <div class='edit-img-con text-center'>
                    <p role='button' class="edit-text psa-photo opacity-0"><i class='bi bi-pencil-square me-2'></i>Edit</p>
                </div>
            </div>
            <input id='form137Upload' class='form-control form-control-sm mt-2 w-75' name='form137Image' type='file' accept='image/png, image/jpg, image/jpeg'>
        </div>
    </div>
    
    <div class="form-group col-md-12 ">
            <div class="card body w-100 h-auto">

                <h4 class="fw-bold">STUDENT INFORMATION</h4>
                <div class="form-group row">

                    <div class="col-6">
                        <label class="col-form-label">Learner's Reference Number</label>
                        <input class="form-control" type="number" name="lrn" value='<?php echo $lrn ?>' aria-label="default input example" placeholder="LRN">
                    </div>

                    <div class="col-6">
                        <label class="col-form-label">Last Name</label>
                        <input class="form-control" type="text" name="last_name" value='<?php echo $lname ?>' aria-label="default input example" placeholder="Last Name">
                    </div>

                    <div class='col-6'>
                        <label class="col-form-label">First Name</label>
                        <input class="form-control" type="text" name="first_name" value='<?php echo $fname ?>' aria-label="default input example" placeholder="First Name">
                    </div>

                    <div class='col-6'>
                        <label class="col-form-label">Middle Name</label>
                        <input class="form-control" type="text" name="middl_name" value='<?php echo $mname ?>' aria-label="default input example" placeholder="Middle Name">
                    </div>

                    <div class='col-4'>
                        <label class="col-form-label">Suffix</label>
                        <input class="form-control" type="text" name="suffix" value='<?php echo $extname ?>' placeholder="Suffix">
                    </div>

                    <div class="form-group col-4">
                        <label class="col-form-label">Birthdate</label>
                        <div class='input-group date' id='datepicker'>
                            <input type='date' class="form-control" name='birthdate' value=<?php echo $birthdate ?> />
                        </div>
                    </div>
                    <div class='col-4'>
                        <label class="col-form-label">Birth Place</label>
                        <input class="form-control" type="text" name='birthplace' value='<?php echo $birth_place ?>' aria-label="default input example" placeholder="Birth Place">
                    </div>

                    <div class='col-2'>
                        <label class="col-form-label">Age</label>
                        <input class="form-control" type="number" name='age' value='<?php echo $age ?>' aria-label="default input example" placeholder="Age">
                    </div>
                    <div class="col-3">
                        <label class="col-form-label">Sex</label>
                        <?php $sexOpt = ["m" => "Male", "f" => "Female"];
                        foreach ($sexOpt as $id => $value) {
                            echo "<div class='form-check'>
                                                            <input class='form-check-input' type='radio' name='sex' id='$id' value='$id' " . (($sex == $value) ? "checked" : "") . ">
                                                            <label class='form-check-label' for='$id'>
                                                            $value
                                                            </label>
                                                        </div>";
                        } ?>
                    </div>

                    <div class='col-5'>
                        <label class="col-form-label">Contact Number</label>
                        <input class="form-control" type="number" name='contact_no' value='<?php echo $cp_no ?>' aria-label="default input example" placeholder="Contact number">
                    </div>
                    <div class="col-6">
                        <label class="col-form-label me-4">Belonging to any Indeginous Group? </label>
                        <?php
                        echo "<div class='form-check'>
                                                            <input class='form-check-input' type='radio' name='belong_group' id='yes' value='Yes' " . (($indigenous_group != NULL) ? "checked" : "") . ">
                                                            <label class='form-check-label' for='yes'> Yes </label>
                                                        </div>
                                                        <div class='form-check'>
                                                        <input class='form-check-input' type='radio' name='belong_group' id='no' value='No' " . (($indigenous_group == NULL) ? "checked" : "") . ">
                                                            <label class='form-check-label' for='yes'> No </label></div>";
                        ?>
                    </div>

                    <div class='col-5'>
                        <label class="col-form-label text-start">If yes, please specify</label>
                        <input class="form-control" type="text" name='group' value='<?php echo $indigenous_group ?>' placeholder="Indigenous Group">
                    </div>

                    <div class="col-6">
                        <label class="col-form-label">Mother Tongue</label>
                        <input class="form-control" type="text" name='mother_tongue' value='<?php echo $mother_tongue ?>' aria-label="default input example" placeholder="Mother Tongue">
                    </div>

                    <div class="col-6">
                        <label class="col-form-label">Religion</label>
                        <input class="form-control" type="text" name='religion' value='<?php echo $religion ?>' aria-label="default input example" placeholder="Religion">
                    </div>

                </div>
                <div class="form-group row">
                    <h5>ADDRESS</h5>
                    <div class="col-2">
                        <label class="col-form-label">House No.</label>
                        <input class="form-control" type="number" name='house_no' value='<?php echo $house_no ?>' aria-label="default input example" placeholder="House No.">
                    </div>

                    <div class="col-4">
                        <label class="col-form-label">Street</label>
                        <input class="form-control" type="text" name='street' value='<?php echo $street ?>' aria-label="default input example" placeholder="Street">
                    </div>

                    <div class="col-6">
                        <label class="col-form-label">Barangay</label>
                        <input class="form-control" type="text" name='barangay' value='<?php echo $barangay ?>' aria-label="default input example" placeholder="Barangay">
                    </div>

                    <div class="col-6">
                        <label class="col-form-label">City/Municipality</label>
                        <input class="form-control" type="text" name='city' value='<?php echo $city ?>' aria-label="default input example" placeholder="City/Municipality">
                    </div>

                    <div class="col-4">
                        <label class="col-form-label">Province</label>
                        <input class="form-control" type="text" name='province' value='<?php echo $province ?>' aria-label="default input example" placeholder="Province">
                    </div>

                    <div class="col-2">
                        <label class="col-form-label">Zip Code</label>
                        <input class="form-control" type="number" name='zip' value='<?php echo $zip ?>' aria-label="default input example" placeholder="Zip Code">
                    </div>
                </div>
            </div>
        <div class="card w-100 h-auto mt-4">
            <div>
                <h4 class="fw-bold"> PARENT/GUARDIAN'S INFORMATION</h4>
                <div class="form-group row">
                    <h5>FATHER</h5>
                    <input type='hidden' name='f_sex' value='m'>
                    <div class='form-row row'>
                        <div class='form-group col-md-6'>
                            <label for='lastname'>Last Name</label>
                            <input type='text' class='form-control' name='f_lastname' value='<?php if (empty($father_last_name)) {echo "";} else {echo $father_last_name;} ?>' placeholder='Last Name'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='firstname'>First Name</label>
                            <input type='text' class='form-control' name='f_firstname' value='<?php if (empty($father_first_name)) {echo "";} else {echo $father_first_name;} ?>' placeholder='First Name'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='middlename'>Middle Name</label>
                            <input type='text' class='form-control' name='f_middlename' value='<?php if (empty($father_middle_name)) { echo "";} else {echo $father_middle_name;} ?>' placeholder='Middle Name'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='extensionname'>Extension Name</label>
                            <input type='text' class='form-control' name='f_extensionname' value='<?php if (empty($father_ext_name)) {echo "";} else {echo $father_ext_name;} ?>' placeholder='Extension Name'>
                        </div>
                    </div>
                    <div class='form-row row'>
                        <div class='form-group col-md-6'>
                            <label for='middlename'>Contact Number</label>
                            <input type='text' class='form-control' name='f_contactnumber' value='<?php if (empty($father_cp_no)) {echo "";} else {echo $father_cp_no;} ?>' placeholder='Contact Number'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='extensionname'>Occupation</label>
                            <input type='text' class='form-control' name='f_occupation' placeholder='Occupation' value='<?php if (empty($father_occupation)) {echo "";} else {echo $father_occupation;} ?>'>
                        </div>
                    </div>

                    <h5>MOTHER</h5>
                    <input type='hidden' name='m_sex' value='f'>

                    <div class='form-row row'>
                        <div class='form-group col-md-6'>
                            <label for='lastname'>Last Name</label>
                            <input type='text' class='form-control' name='m_lastname' value='<?php if (empty($mother_last_name)) {echo "";} else {echo $mother_last_name; } ?>' placeholder='Last Name'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='firstname'>First Name</label>
                            <input type='text' class='form-control' name='m_firstname' value='<?php if (empty($mother_first_name)) {echo "";} else {echo $mother_first_name;} ?>' placeholder='First Name'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='middlename'>Middle Name</label>
                            <input type='text' class='form-control' name='m_middlename' value='<?php if (empty($mother_middle_name)) {echo "";} else {echo $mother_middle_name; } ?>' placeholder='Middle Name'>
                        </div>
                    </div>
                    <div class='form-row row'>
                        <div class='form-group col-md-6'>
                            <label for='middlename'>Contact Number</label>
                            <input type='text' class='form-control' name='m_contactnumber' value='<?php if (empty($mother_cp_no)) {echo "";} else {echo $mother_cp_no;} ?>' placeholder='Contact Number'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='extensionname'>Occupation</label>
                            <input type='text' class='form-control' name='m_occupation' value='<?php if (empty($mother_occupation)) {echo "";} else {echo $mother_occupation;} ?>' placeholder='Occupation'>

                        </div>
                    </div>
                    <h5>GUARDIAN</h5>
                    <div class='form-row row'>
                        <div class='form-group col-md-6'>
                            <label for='lastname'>Last Name</label>
                            <input type='text' class='form-control' name='g_lastname' value='<?php echo $guardian_last_name ?>' placeholder='Last Name' required>
                            <div class="invalid-feedcak">
                                Please enter gruardian's last name
                            </div>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='firstname'>First Name</label>
                            <input type='text' class='form-control' name='g_firstname' value='<?php echo $guardian_first_name ?>' placeholder='First Name' required>
                            <div class="invalid-feedback">
                                Please enter guardian's firstname
                            </div>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='middlename'>Middle Name</label>
                            <input type='text' class='form-control' name='g_middlename' value='<?php echo $guardian_middle_name ?>' placeholder='Middle Name' required>
                            <div class="invalid-feedback">
                                Please enter guardian's middle name
                            </div>
                        </div>

                    </div>
                    <div class='form-row row'>
                        <div class='form-group col-md-6'>
                            <label for='middlename'>Contact Number</label>
                            <input type='text' class='form-control' name='g_contactnumber' value='<?php echo $guardian_cp_no ?>' placeholder='Contact Number'>
                        </div>
                        <div class='form-group col-md-6'>
                            <label for='extensionname'>Relationship</label>
                            <input type='text' class='form-control' name='relationship' value='<?php echo $guardian_relationship ?>' placeholder='Relationship'>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Form -->
        <div class='back-btn d-flex justify-content-end'> <?php echo "<input type='hidden' name='student_id' value='$stud_id'>"?>
            <!-- <input type='hidden' name='profile' value='faculty'> -->
            <input type='hidden' value='updateStudent' name='action'>
            <!-- <a href='' role='button' class='btn btn-secondary me-2' target='_self'>CANCEL</a> -->
            <input type='submit' value='Save' class='btn btn-success btn-space save-btn' name='submit'>
        </div>
    </div>
</form>
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
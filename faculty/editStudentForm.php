<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
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
</header>
<!-- CONTENT  -->

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <h4 class="my-auto fw-bold">Edit Student Information</h4>
                            <div class='container mt-3'>
                                <form id='student-form' class='needs-validation' method='POST' action='action.php' enctype='multipart/form-data' novalidate>
                                    <!-- Photo -->
                                    <div class="form-row row">
                                        <div class='form-group col-md-4 d-flex flex-column'>
                                            <label for='photo' class='form-label'>Student Photo</label>
                                            <div class="image-preview-con">
                                                <img id='resultImg' src='' alt='Profile image' class='rounded-circle w-100 h-100' />
                                                <div class='edit-img-con text-center'>
                                                    <p role='button' class="edit-text profile-photo opacity-0"><i class='bi bi-pencil-square me-2'></i>Edit</p>
                                                </div>
                                            </div>
                                            <input id='upload' class='form-control form-control-sm mt-2 w-75' id='photo' name='image' type='file' accept='image/png, image/jpg, image/jpeg'>
                                        </div>

                                        <div class='form-group col-md-4 d-flex flex-column'>
                                            <label for='photo' class='form-label'>PSA Birth Certificate</label>
                                            <div class="image-preview-con">
                                                <img id='psaResult' src='' alt='PSA image' class="img-thumbnail w-100 h-100" />
                                                <div class='edit-img-con text-center'>
                                                    <p role='button' class="edit-text psa-photo opacity-0"><i class='bi bi-pencil-square me-2'></i>Edit</p>
                                                </div>
                                            </div>
                                            <input id='psaUpload' class='form-control form-control-sm mt-2 w-75' name='psaImage' type='file' accept='image/png, image/jpg, image/jpeg'>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 ">
                                        <div class="card body w-100 h-auto">

                                            <h4 class="fw-bold">STUDENT INFORMATION</h4>
                                            <div class="form-group row">

                                                <div class="col-6">
                                                    <label class="col-form-label">Learner's Reference Number</label>
                                                    <input class="form-control" type="number" name="lrn" value='' aria-label="default input example" placeholder="LRN">
                                                </div>

                                                <div class="col-6">
                                                    <label class="col-form-label">Last Name</label>
                                                    <input class="form-control" type="text" name="last_name" value='' aria-label="default input example" placeholder="Last Name">
                                                </div>

                                                <div class='col-6'>
                                                    <label class="col-form-label">First Name</label>
                                                    <input class="form-control" type="text" name="first_name" value='' aria-label="default input example" placeholder="First Name">
                                                </div>

                                                <div class='col-6'>
                                                    <label class="col-form-label">Middle Name</label>
                                                    <input class="form-control" type="text" name="middl_name" value='' aria-label="default input example" placeholder="Middle Name">
                                                </div>

                                                <div class='col-4'>
                                                    <label class="col-form-label">Suffix</label>
                                                    <input class="form-control" type="text" name="suffix" value='' placeholder="Suffix">
                                                </div>

                                                <div class="form-group col-4">
                                                    <label class="col-form-label">Birthdate</label>
                                                    <div class='input-group date' id='datepicker'>
                                                        <input type='date' class="form-control" name='birthdate' value="">
                                                    </div>
                                                </div>
                                                <div class='col-4'>
                                                    <label class="col-form-label">Birth Place</label>
                                                    <input class="form-control" type="text" name='birthplace' value=' ' aria-label="default input example" placeholder="Birth Place">
                                                </div>

                                                <div class='col-2'>
                                                    <label class="col-form-label">Age</label>
                                                    <input class="form-control" type="number" name='age' value='' aria-label="default input example" placeholder="Age">
                                                </div>
                                                <div class="col-3">
                                                    <label class="col-form-label">Sex</label>
                                                    <div class='form-check'>
                                                        <input class='form-check-input' type='radio' name='sex' id='' value="">
                                                        <label class='form-check-label' for=''>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class='col-5'>
                                                    <label class="col-form-label">Contact Number</label>
                                                    <input class="form-control" type="number" name='contact_no' value='' aria-label="default input example" placeholder="Contact number">
                                                </div>
                                                <div class="col-6">
                                                    <label class="col-form-label me-4">Belonging to any Indeginous Group? </label>
                                                    <div class='form-check'>
                                                        <input class='form-check-input' type='radio' name='belong_group' id='yes' value='Yes'>
                                                        <label class='form-check-label' for='yes'> Yes </label>
                                                    </div>
                                                    <div class='form-check'>
                                                        <input class='form-check-input' type='radio' name='belong_group' id='no' value='No'>
                                                        <label class='form-check-label' for='yes'> No </label>
                                                    </div>";
                                                    ?>
                                                </div>

                                                <div class='col-5'>
                                                    <label class="col-form-label text-start">If yes, please specify</label>
                                                    <input class="form-control" type="text" name='group' value='' placeholder="Indigenous Group">
                                                </div>

                                                <div class="col-6">
                                                    <label class="col-form-label">Mother Tongue</label>
                                                    <input class="form-control" type="text" name='mother_tongue' value='>' aria-label="default input example" placeholder="Mother Tongue">
                                                </div>

                                                <div class="col-6">
                                                    <label class="col-form-label">Religion</label>
                                                    <input class="form-control" type="text" name='religion' value='' aria-label="default input example" placeholder="Religion">
                                                </div>

                                            </div>
                                            <div class="form-group row">
                                                <h5>ADDRESS</h5>
                                                <div class="col-2">
                                                    <label class="col-form-label">House No.</label>
                                                    <input class="form-control" type="number" name='house_no' value='' aria-label="default input example" placeholder="House No.">
                                                </div>

                                                <div class="col-4">
                                                    <label class="col-form-label">Street</label>
                                                    <input class="form-control" type="text" name='street' value='' aria-label="default input example" placeholder="Street">
                                                </div>

                                                <div class="col-6">
                                                    <label class="col-form-label">Barangay</label>
                                                    <input class="form-control" type="text" name='barangay' value='' aria-label="default input example" placeholder="Barangay">
                                                </div>

                                                <div class="col-6">
                                                    <label class="col-form-label">City/Municipality</label>
                                                    <input class="form-control" type="text" name='city' value='' aria-label="default input example" placeholder="City/Municipality">
                                                </div>

                                                <div class="col-4">
                                                    <label class="col-form-label">Province</label>
                                                    <input class="form-control" type="text" name='province' value='' aria-label="default input example" placeholder="Province">
                                                </div>

                                                <div class="col-2">
                                                    <label class="col-form-label">Zip Code</label>
                                                    <input class="form-control" type="number" name='zip' value='' aria-label="default input example" placeholder="Zip Code">
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
                                                            <input type='text' class='form-control' name='f_lastname' value=''>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='firstname'>First Name</label>
                                                            <input type='text' class='form-control' name='f_firstname' value=''>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='middlename'>Middle Name</label>
                                                            <input type='text' class='form-control' name='f_middlename' value=''>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='extensionname'>Extension Name</label>
                                                            <input type='text' class='form-control' name='f_extensionname' value=''>
                                                        </div>
                                                    </div>
                                                    <div class='form-row row'>
                                                        <div class='form-group col-md-6'>
                                                            <label for='middlename'>Contact Number</label>
                                                            <input type='text' class='form-control' name='f_contactnumber' value=''>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='extensionname'>Occupation</label>
                                                            <input type='text' class='form-control' name='f_occupation' placeholder='Occupation' value=''>
                                                        </div>
                                                    </div>

                                                    <h5>MOTHER</h5>
                                                    <input type='hidden' name='m_sex' value='f'>

                                                    <div class='form-row row'>
                                                        <div class='form-group col-md-6'>
                                                            <label for='lastname'>Last Name</label>
                                                            <input type='text' class='form-control' name='m_lastname' value=''>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='firstname'>First Name</label>
                                                            <input type='text' class='form-control' name='m_firstname' value=''>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='middlename'>Middle Name</label>
                                                            <input type='text' class='form-control' name='m_middlename' value=''>
                                                        </div>
                                                    </div>
                                                    <div class='form-row row'>
                                                        <div class='form-group col-md-6'>
                                                            <label for='middlename'>Contact Number</label>
                                                            <input type='text' class='form-control' name='m_contactnumber' value=''>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='extensionname'>Occupation</label>
                                                            <input type='text' class='form-control' name='m_occupation' value=''>

                                                        </div>
                                                    </div>
                                                    <h5>GUARDIAN</h5>
                                                    <div class='form-row row'>
                                                        <div class='form-group col-md-6'>
                                                            <label for='lastname'>Last Name</label>
                                                            <input type='text' class='form-control' name='g_lastname' value='' placeholder='Last Name' required>
                                                            <div class="invalid-feedcak">
                                                                Please enter gruardian's last name
                                                            </div>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='firstname'>First Name</label>
                                                            <input type='text' class='form-control' name='g_firstname' value='' placeholder='First Name' required>
                                                            <div class="invalid-feedback">
                                                                Please enter guardian's firstname
                                                            </div>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='middlename'>Middle Name</label>
                                                            <input type='text' class='form-control' name='g_middlename' value='' placeholder='Middle Name' required>
                                                            <div class="invalid-feedback">
                                                                Please enter guardian's middle name
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class='form-row row'>
                                                        <div class='form-group col-md-6'>
                                                            <label for='middlename'>Contact Number</label>
                                                            <input type='text' class='form-control' name='g_contactnumber' value='' placeholder='Contact Number'>
                                                        </div>
                                                        <div class='form-group col-md-6'>
                                                            <label for='extensionname'>Relationship</label>
                                                            <input type='text' class='form-control' name='relationship' value='' placeholder='Relationship'>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Form -->
                                            <div class='back-btn d-flex justify-content-end'>
                                                <input type='hidden' name='student_id' value='$stud_id'>
                                                <!-- <input type='hidden' name='profile' value='faculty'> -->
                                                <input type='hidden' value='updateStudent' name='action'>
                                                <!-- <a href='' role='button' class='btn btn-secondary me-2' target='_self'>CANCEL</a> -->
                                                <input type='submit' value='Save' class='btn btn-success btn-space save-btn' name='submit'>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- FOOTER START -->
                    <?php include_once("../inc/footer.html"); ?>
                    <!-- FOOTER END -->
            </section>
        </section>
    </section>
    <!-- MAIN CONTENT END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0; right: 0;">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 9999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
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
    <!--BOOTSTRAP TABLE JS-->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script type='module' src='../js/admin/faculty.js'></script>
</body>
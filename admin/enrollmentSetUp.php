<?php 
include_once("../inc/head.html");
session_start();
require_once("../class/Administration.php");
?>
<title>Step Sample | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
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
    <?php include_once('../inc/admin/sidebar.html'); ?>
    <!-- MAIN CONTENT START -->
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-11">
                    <div class="row mt ps-3">
                        <form id="enrollment-setup" enctype="multipart/form-data" action="action.php" method="POST">
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
                                    
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="test-l-1" class="content">
                                        <div class="card body w-100 h-auto p-4">
                                            <!-- STEP 1 -->
                                            <h4 class="fw-bold">ENROLLMENT SET UP 1: FACULTY ENROLLMENT PRIVILEGES</h4>
                                            <div class="d-flex justify-content-between mb-3">
                                                <!-- SEARCH BAR -->
                                                <span class="flex-grow-1 me-5"> 
                                                    <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                                                </span>
                                                <div>
                                                    <button class="btn btn-secondary" title=''>Grant Enrollment Access</button>
                                                </div>
                                                        
                                            </div>
                                            <table id="table" class="table-striped">
                                                <thead class='thead-dark'>
                                                    <tr>
                                                        <th data-checkbox="true"></th>
                                                        <th scope='col' data-width="600" data-align="left" data-sortable="true" data-field="sub_name">Faculty Name</th>
                                                        <th scope='col' data-width="100" data-align="center" data-field="sub_type">Can Enroll</th>
                                                    </tr>
                                                </thead>
                                            </table> 
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
                                                    <input type='text' class='form-control' id='f-lastname' name='f-lastname' value = "<?php echo $father_last_name; ?>" placeholder='Last Name' required>
                                                </div>
                                                <div class='form-group col-md-4'>
                                                    <label for='f-firstname'>First Name</label>
                                                    <input type='text' class='form-control' id='f-firstname' name='f-firstname' value = "<?php echo $father_last_name; ?>" placeholder='First Name' required>
                                                </div>
                                                <div class='form-group col-md-3'>
                                                    <label for='f-middlename'>Middle Name</label>
                                                    <input type='text' class='form-control' id='f-middlename' name='f-middlename' value = "<?php echo $father_first_name; ?>" placeholder='Middle Name' required>
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
                                                <h5>MOTHER</h5>
                                                <div class='form-group col-md-3'>
                                                    <label for='m-lastname'>Maiden Last Name</label>
                                                    <input type='text' class='form-control' id='m-lastname' name='m-lastname' value = "<?php echo $mother_last_name; ?>" placeholder='Last Name' required>
                                                </div>
                                                <div class='form-group col-md-4'>
                                                    <label for='m-firstname'>First Name</label>
                                                    <input type='text' class='form-control' id='m-firstname' name='m-firstname' value = "<?php echo $mother_first_name; ?>" placeholder='First Name' required>
                                                </div>
                                                <div class='form-group col-md-3'>
                                                    <label for='m-middlename'>Middle Name</label>
                                                    <input type='text' class='form-control' id='m-middlename' name='m-middlename' value = "<?php echo $mother_middle_name; ?>" placeholder='Middle Name' required>
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
                                                <h5>GUARDIAN</h5>
                                                <div class='form-group col-md-3'>
                                                    <label for='g-lastname'>Last Name</label>
                                                    <input type='text' class='form-control' id='g-lastname' name='g-lastname' value = "<?php echo $guardian_last_name; ?>" placeholder='Last Name' required>
                                                </div>
                                                <div class='form-group col-md-4'>
                                                    <label for='g-firstname'>First Name</label>
                                                    <input type='text' class='form-control' id='g-firstname' name='g-firstname' value = "<?php echo $guardian_first_name; ?>" placeholder='First Name' required>
                                                </div>
                                                <div class='form-group col-md-3'>
                                                    <label for='g-middlename'>Middle Name</label>
                                                    <input type='text' class='form-control' id='g-middlename' name='g-middlename' value = "<?php echo $guardian_middle_name; ?>" placeholder='Middle Name' required>
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
                                                                <input class='form-check-input' type='radio' name='group' id='yes' value='Yes' " . (($indigenous_group != NULL) ? "checked" : "") . ">
                                                                <label class='form-check-label' for='yes'> Yes </label>
                                                        </div>
                                                        <div class='form-check'>
                                                            <input class='form-check-input' type='radio' name='group' id='no' value='No' " . (($indigenous_group == NULL) ? "checked" : "") . ">
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
                                                    <input class="form-control" name="semester" type="text" value="" >
                                                </div>

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


</html>
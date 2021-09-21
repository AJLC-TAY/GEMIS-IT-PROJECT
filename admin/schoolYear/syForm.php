<?php
require_once("../class/Administration.php");
$admin = new Administration();
$tracks = ['ACAD' => ["ABM" => "ABM desc", "HumSS" => "HumSS desc"], 'TVL' => ["BP" => "Bread & Pastry", "Elec" => "Electronics"]];
?>
<!-- HEADER -->
<header id="main-header">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="schoolYear.php">School Year</a></li>
            <li class="breadcrumb-item active">Form</li>
        </ol>
    </nav>
    <div class="container px-4 text-center">
        <h2>Initialize School Year</h2>
    </div>
</header>
<form id="school-year-form" class="needs-validation" enctype="multipart/form-data" action="action.php" method="POST" novalidate>
    <div id="stepper" class="bs-stepper">
        <div id="header" class="bs-stepper-header w-75 mx-auto">
            <div class="step mx-5" data-target="#step-1">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Part</span>
                    <span class="bs-stepper-circle">1</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step mx-5" data-target="#step-2">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Part</span>
                    <span class="bs-stepper-circle">2</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step mx-5" data-target="#step-3">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Part</span>
                    <span class="bs-stepper-circle">3</span>
                </button>
            </div>
        </div>
        <div class="bs-stepper-content">
            <div id="step-1" class="content">
                <div class="card body w-100 h-auto p-4">
                    <!-- STEP 1 -->
                    <h4 class="fw-bold">Set School Year</h4>
                    <div class="form-group row">
                        <label for="" class="col-lg-5 col-form-label">School Year (Start-End)</label>
                        <div class="col-lg-7">
                            <div class="d-flex align-items-center">
                                <input type="text" name="start-year" class="form-control number" placeholder="Start">
                                <span class='mb-3 p-2 text-center'><i class="bi bi-dash"></i></span>
                                <input type="text" name="end-year" class="form-control number" placeholder="End">
                            </div>
                        </div>
                    </div>
                    <!-- TRACKS -->
                    <div class="form-group row row-cols-1 row-cols-lg-2 g-3">
                        <div class="col">
                            <div class="container">
                                <div class="row mt-3 justify-content-between">
                                    <label class="col-form-label col-auto fw-bold">Tracks</label>
                                    <div class="col-auto p-2">
                                        <div class="form-check">
                                            <input id="track-checkbox-all" type="checkbox" class="checkbox-all form-check-input" data-target-list="#track-list">
                                            <label for="track-checkbox-all" class="form-check-label">Check | Uncheck All</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row border overflow-auto" style="height: 250px;">
                                    <ul id="track-list" class="list-group p-0">
                                        <?php 
                                        foreach($tracks as $id => $value) {
                                            echo "<label class='list-group-item'><input name='track[]' class='form-check-input me-2 track-checkbox' type='checkbox' value='$id' checked>$id Track</label>";
                                        }
                                        ?>
                                
                                    </ul>
                                </div>
                            </div>
                       </div>
                        <div class="col">
                            <div class="container">
                                <div class="row mt-3 justify-content-between">
                                    <label class="col-form-label col-auto fw-bold">Strand</label>
                                    <div class="col-auto p-2">
                                        <div class="form-check">
                                            <input id="strand-checkbox" type="checkbox" class="checkbox-all form-check-input" data-target-list="#strand-list">
                                            <label for="strand-checkbox" class="form-check-label">Check | Uncheck All</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row border overflow-auto" style="height: 250px;">
                                    <ul id="strand-list" class="list-group p-0">
                                       <?php 
                                        foreach($tracks as $id => $value) {
                                            echo "<label data-track='$id' class='track-label list-group-item'>$id</label>";
                                            foreach($value as $strand_id => $strand_value) {
                                                echo "<label class='list-group-item'><input name='track[$id][$strand_id]' class='form-check-input me-2 track-checkbox' data-track-id='$id' type='checkbox' checked>$strand_value</label>";
                                            }
                                        }
                                       ?>
        
                                    </ul>
                                </div>
                            </div>
                       </div>

                       <input type="submit" form="school-year-form" value="Submit">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="#" class="btn btn-success" onclick="stepper.next()">Next</a>
                </div>
            </div>
            <!-- STEP 1 END -->
            <!-- STEP 2 -->
            <div id="step-2" class="content">
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
            <div id="step-3" class="content">
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

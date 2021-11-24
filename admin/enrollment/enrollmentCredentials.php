<?php
require_once("../class/Administration.php");
$admin = new Administration();
$student = $admin->getProfile("ST");
$stud_id = $student->get_stud_id();
$user_id_no = $student->get_id_no();
$lrn = $student->get_lrn();
$name = $student->get_name();
$sex = $student->get_sex();
$id_picture = $student->get_id_picture();
$birth_cert = $student->get_psa_birth_cert();
$form_137 = $student->get_form137();
$valid_status = $student->get_status();

$strand = $student->get_strand();
$yrlvl = $student->get_yrLvl();
$age = $student->get_age();
$birthdate = $student->get_birthdate();
$birthdate = date("F j, Y", strtotime($birthdate));
$birth_place = $student->get_birth_place();
$indigenous_group = $student->get_indigenous_group();
$mother_tongue = $student->get_mother_tongue();
$religion = $student->get_religion();
$address = $student->get_address();
$add = $address['address'];
$cp_no = $student->get_cp_no();
$psa_birth_cert = $student->get_psa_birth_cert();
$belong_to_ipcc = $student->get_belong_to_ipcc();
$id_picture = $student->get_id_picture();
$section = $student->get_section();

$parents = $student->get_parents();
$guardian = $student->get_guardians();
$grades = $student->get_grades();

if (is_null($parents)) {
    $parents = NULL;
} else {
    foreach ($parents as $par) {
        $parent = $par['sex'] == 'f' ? 'mother' : 'father';
        ${$parent . '_name'} = $par['name'];
        ${$parent . '_occupation'} = $par['occupation'];
        ${$parent . '_cp_no'} = $par['cp_no'];
    }
}

if (is_null($guardian)) {
    $guardian = NULL;
} else {
    $guardian_name = $guardian['name'];
    $guardian_cp_no = $guardian['cp_no'];
    $guardian_relationship = $guardian['relationship'];
}

const HIDE = "style='display: none;'";
$change_btn_display = '';
$form_display = HIDE;
if ($valid_status === "Pending") {
    $change_btn_display = HIDE;
    $form_display = '';
}

const PROFILE_PATH = "../assets/profile.png";
const NO_PREVIEW_PATH = "../assets/no_preview.jpg";
$image = !is_null($id_picture) ? (file_exists($id_picture) ? $id_picture : PROFILE_PATH) : PROFILE_PATH;
$psaPreview = !is_null($id_picture) ? (file_exists($birth_cert) ? $birth_cert : NO_PREVIEW_PATH) : NO_PREVIEW_PATH;
$form137Preview = !is_null($id_picture) ? (file_exists($form_137) ? $form_137 : NO_PREVIEW_PATH) : NO_PREVIEW_PATH;
?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php">Enrollment</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php?page=enrollees">Enrollees</a></li>
            <li class="breadcrumb-item active">Credential</li>
        </ol>
    </nav>
    <h3 class="fw-bold"><?php echo $name; ?></h3>
</header>

<!-- MAIN CONTENT -->
<div class='container my-3'>
    <div class="card p-3 text-center">
        <div class="">
            <nav id="myTab">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#credential" type="button" role="tab" aria-controls="credential" aria-selected="true">Credentials</a>
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#gen-info" type="button" role="tab" aria-controls="gen-info" aria-selected="true">Information</a>
                </div>
            </nav>
        </div>
        <div class="tab-content" id="myTabContent">
            <!-- DOCUMENTS -->
            <div class="tab-pane fade bg-white p-4 show active" id="credential" role="tabpanel" aria-labelledby="home-tab">
                <div class="w-100 h-auto text-start mx-auto">
                    <div class="row g-3 p-0">
                        <!-- PROFILE PICTURE -->
                        <div class="col-xl-5 mx-0">
                            <div class="row justify-content-center">
                                <img src='<?php echo $image; ?>' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'>
                            </div>
                            <dl class="row">
                                <dt class="col-sm-3">LRN</dt>
                                <dd class="col-sm-9"></span><?php echo $lrn; ?></dd>
                                <dt class="col-sm-3">Name</dt>
                                <dd class="col-sm-9"></span><?php echo $name; ?></dd>
                                <dt class="col-sm-3">Sex</dt>
                                <dd class="col-sm-9"></span><?php echo $sex; ?></dd>
                                <dt class="col-sm-3">Status</dt>
                                <dd class="col-sm-9">
                                    <p><span id="status"><?php echo $valid_status; ?> </span> 
                                        <span class="badge" <?php echo $change_btn_display; ?>>
                                            <button id="valid-change-btn" data-type="change" class="action btn btn-sm btn-primary">Change</button>
                                            <button class="btn btn-dark btn-sm action edit-opt" data-type="cancel" <?php echo HIDE; ?>>Cancel</button>
                                        </span>
                                    </p>
                                </dd>
                            </dl>
                            <?php
                            $is_disabled = "";
                            if ($valid_status != 'Enrolled') {
                                if ($valid_status == 'Rejected') {
                                    $is_disabled = "disabled";
                                }
                                echo "<h6><b>Requesting to enroll in:</b></h6>";
                            } else {
                                $is_disabled = "disabled";
                                echo "<h6><b>Enrolled in:</b></h6>";
                            }
                            ?>
                            <form id="validate-form" action="action.php" method="post"  class="border p-3 border-1 rounded-2">
                                <div class="container">
                                    <div class="row mb-3">
                                        <div class="enroll-request-con container">
                                            <div class="row mb-3">
                                                <label for="grade-level" class="form-label">Grade Level</label>
                                                <select name="grade-level" id="grade-level" class="form-select" <?php echo $is_disabled; ?>>
                                                    <?php
                                                    foreach([11, 12] as $level) {
                                                        echo "<option value='$level' ". ($yrlvl == $level ? "selected" : "") .">$level</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="strand" class="form-label">Strand</label>
                                                <select name="strand" id="strand" class="form-select" <?php echo $is_disabled; ?>>
                                                    <?php
                                                    $programs = $admin->listPrograms('program');
                                                    $trackStrand = $admin->getTrackStrand($stud_id)[0];
                                                    foreach ($programs as $program) {
                                                        $prog_code = $program->get_prog_code();
                                                        echo "<option value='{$prog_code}' ". ($prog_code == $trackStrand ? "selected" : "") ." >{$program->get_prog_desc()}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='edit-opt'  <?php echo $form_display; ?>>
                                    <input type="hidden" name='stud_id' value='<?php echo $stud_id; ?>'>
                                    <input type="hidden" name='action' value='validateEnrollment'>

                                    <div class="row mb-4 justify-content-center">
                                        <div class="col-6">
                                            <input required type="radio" class="btn-check" name="valid" id="option1" autocomplete="off" <?php echo ($valid_status == 'Enrolled' ? "checked" : ""); ?> value="accept">
                                            <label class="btn btn-outline-primary w-100" for="option1">Accept Enrollee</label>
                                        </div>
                                        <div class="col-6">
                                            <input required type="radio" class="btn-check" name="valid" id="option2" autocomplete="off" <?php echo ($valid_status == 'Rejected' ? "checked" : ""); ?> value="reject">
                                            <label class="btn btn-outline-danger w-100" for="option2">Decline Enrollee</label>
                                        </div>
                                    </div>
<!--                                    <div class="col-auto">-->
<!--                                        <button type="button" class='btn btn-success mb-2 w-100 validate' data-name='accept' title='Enroll student'>Accept Enrollee</button>-->
<!--                                    </div>-->
<!--                                    <button type="button" class='btn btn-secondary mb-2 w-100' data-bs-toggle="modal" data-bs-target="#confirmation-modal" title='Decline Enrollee'>Decline Enrollee</button>-->
                                    <div class="container">
                                        <dl class="row">
                                            <dt class="col-3">Accept</dt>
                                            <dd class="col-9">Accepting student will initialize their grades and attendances to zero.</dd>
                                            <dt class="col-3">Decline</dt>
                                            <dd class="col-9">Declining student will not reflect during the creation of section. Any initialized grades will be deleted.</dd>
                                        </dl>
                                        <div class="row">
                                            <button type="button" class="btn btn-success validate">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- PROFILE PICTURE END -->
                        <!-- DOCUMENT DETAILS -->
                        <div class="col-xl-7 ps-5 container">
                            <div class="row">
                                <h5><b>Documents</b></h5>
                            </div>
                            <div class="ps-3 row me-3">
                                    <div class="col-md-5 card">
                                        <div class="thumbnail">
                                            <div class="caption">
                                                <p class="fw-bold text-center">PSA DOCUMENT</p>
                                            </div>
                                            <img id="psa" src="<?php echo $psaPreview; ?>" class="img-responsive" alt="PSA document" style="width:100%">
                                            <!-- </a> -->
                                        </div>
                                    </div>
                                    <div class="col-md-5 card ms-4">
                                        <div class="thumbnail">
                                            <div class="caption">
                                                <p class="fw-bold text-center">FORM 137</p>
                                            </div>
                                            <img id="form137" src="<?php echo $form137Preview; ?>" class="img-responsive" alt="Form 137" style="width:100%">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="confirmation-modal" tabindex="-1" aria-labelledby="modal selectSection" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-title">
                                        <h4 class="mb-0">Confirmation</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Declining this enrollment request will delete this student's initialized grades.
                                </div>
                                <div class="modal-footer">
                                    <button class="close btn btn-dark close-btn validateReject"  data-name='reject'>Decline</button>
                                    <button class="close btn btn-success close-btn" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- GENERAL INFORMATION -->
            <div class="tab-pane fade bg-white p-4" id="gen-info" role="tabpanel" aria-labelledby="home-tab">
                <div class="row w-100 h-auto text-start mx-auto">
                    <div class="row p-0">
                        <!-- PROFILE PICTURE -->
                        <div class="col-xl-4">
                        <img src='<?php echo $image; ?>' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'>
                            <br>
                            <?php echo "<dl class='row mb-2 ms-1'>
                                    <dt class='col-md-4'>User ID: </dt>
                                    <dd class='col-md-8'> $user_id_no </dd>
                                    <dt class='col-md-4'>Strand: </dt>
                                    <dd class='col-md-8'> $strand </dd>
                                    <dt class='col-md-4'>Year Level: </dt>
                                    <dd class='col-md-8'>$yrlvl</dd>
                            </dl>" ?>
                        </div>
                        <?php $admin->listValuesReport() ?>

                        <!-- PROFILE PICTURE END -->
                        <!-- INFORMATION DETAILS -->
                        <div class="col-xl-7 ms-5">
                            <div class="row">
                                <h6><b>General Information</b></h6>
                                <?php
                                $birthdate = $student->get_birthdate();
                                $birthdate = date("F j, Y", strtotime($birthdate));
                                $name = $student->get_name();
                                echo
                                "<dl class='row mb-3 ms-2 border border-1 p-2'>
                                            <dt class='col-md-4'>Student LRN</dt>
                                            <dd class='col-md-8'>$lrn</dd>
                                            <dt class='col-md-4'>Name</dt>
                                            <dd class='col-md-8'> $name </dd>
                                            <dt class='col-md-4'>Gender </dt>
                                            <dd class='col-md-8'> {$student->get_sex()} </dd>
                                            <dt class='col-md-4'>Age</dt>
                                            <dd class='col-md-8'> {$student->get_age()} </dd>
                                            <dt class='col-md-4'>Birthdate</dt>
                                            <dd class='col-md-8'> {$birthdate} </dd>
                                            <dt class='col-md-4'>Birth Place</dt>
                                            <dd class='col-md-8'> $birth_place </dd>
                                            <dt class='col-md-4'>Indeginous Group </dt>
                                            <dd class='col-md-8'> $indigenous_group </dd>
                                            <dt class='col-md-4'>Mother Tongue</dt>
                                            <dd class='col-md-8'> $mother_tongue </dd>
                                            <dt class='col-md-4'>Religion </dt>
                                            <dd class='col-md-8'> $religion </dd>

                                </dl>";
                                ?>
                            </div>
                            <div class="row mt-3">
                                <h6><b>Contact Information</b></h6>
                                <?php echo
                                "<dl class='row mb-3 ms-2'>
                                        <dt class='col-md-4'>Home Address </dt>
                                        <dd class='col-md-8'> $add </dd>
                                        <dt class='col-md-4'>Cellphone No. </dt>
                                        <dd class='col-md-8'> $cp_no </dd>
                                        
                                    </dl>"; ?>
                                <hr>
                            </div>

                            <div class="row mt-3">
                                <h6><b>Contact Persons</b></h6>
                                <?php if ($parents != NULL) {
                                    echo "<h6>Parent/s</h6>";
                                    foreach ($parents as $par) {
                                        $parent = $par['sex'] == 'f' ? 'mother' : 'father';
                                        $name = ${$parent . '_name'};
                                        $occupation = ${$parent . '_occupation'};
                                        $no = ${$parent . '_cp_no'};
                                        echo "
                                        <dl class='row mb-3 ms-2'>
                                            <dt class='col-md-4'>" . ucwords($parent) . "'s Name </dt>
                                            <dd class='col-md-8'> $name </dd>
                                            <dt class='col-md-4'>Occupation </dt>
                                            <dd class='col-md-8'> $occupation </dd>
                                            <dt class='col-md-4'>Contact Number </dt>
                                            <dd class='col-md-8'> $no </dd>
                                        </dl>";
                                    }
                                }
                                if ($guardian != NULL) {
                                    echo "<hr><h6 class='mt-3'>Guardian/s</h6>
                                        <dl class='row mb-3 ms-2'>
                                            <dt class='col-md-4'>Guardian's Name</dt>
                                            <dd class='col-md-8'> $guardian_name </dd>
                                            <dt class='col-md-4'>Relationship</dt>
                                            <dd class='col-md-8'> $guardian_relationship </dd>
                                            <dt class='col-md-4'>Contact Number</dt>
                                            <dd class='col-md-8'> $guardian_cp_no </dd>
                                        </dl>";
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- Creates the bootstrap modal where the image will appear -->
 <div class="modal fade" id="psaPreview" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">PSA Preview</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="img01">
                        <img  src ="<?php echo $psaPreview; ?>"  class="img-responsive" alt="PSA document" style="width:100%">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="137Preview" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">137 Preview</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="img01">
                        <img  src ="<?php echo $form137Preview; ?>"  class="img-responsive" alt="PSA document" style="width:100%">
                    </div>
                </div>
            </div>
        </div>
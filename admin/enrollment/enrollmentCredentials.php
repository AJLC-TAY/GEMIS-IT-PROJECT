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
<!DOCTYPE html>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php">Enrollment</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php?page=enrollees">Enrollees</a></li>
            <li class="breadcrumb-item active">Credential</li>
        </ol>
    </nav>
    <h3><?php echo $name; ?></h3>
</header>

<!-- MAIN CONTENT -->
<div class='container my-3'>
    <div class="card p-3 text-center">
        <div class="">
            <nav id="myTab">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="nav-gen-info-tab" data-bs-toggle="tab" data-bs-target="#gen-info" type="button" role="tab" aria-controls="gen-info" aria-selected="true">Credentials</a>
                </div>
            </nav>
        </div>
        <div class="tab-content" id="myTabContent">
            <!-- DOCUMENTS -->
            <div class="tab-pane fade bg-white p-4 show active" id="gen-info" role="tabpanel" aria-labelledby="home-tab">
                <div class="w-100 h-auto text-start mx-auto">
                    <!-- <h5>DOCUMENTS</h5> -->
                    <!-- <hr> -->
                    <div class="row g-3 p-0">
                        <!-- PROFILE PICTURE -->
                        <div class="col-xl-4 mx-0">
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
                    
                            <form id="validate-form" class='edit-opt' action="action.php" method="post" <?php echo $form_display; ?>>
                                <input type="hidden" name='stud_id' value='<?php echo $stud_id; ?>'>
                                <input type="hidden" name='action' value='validateEnrollment'>
                                <div class="col-auto">
                                    <button type="button" class='btn btn-success mb-2 w-100 validate' data-name='accept' title='Enroll student'>Accept Enrollee</button>
                                </div>
                                <button type="button" class='btn btn-secondary mb-2 w-100' data-bs-toggle="modal" data-bs-target="#confirmation-modal" title='Decline Enrollee'>Decline Enrollee</button>
                                <div class="container">
                                    <dl class="row">
                                        <dt class="col-3">Accept</dt>
                                        <dd class="col-9">Accepting student will initialize their grades and attendances to zero.</dd>
                                        <dt class="col-3">Decline</dt>
                                        <dd class="col-9">Declining student will not reflect during the creation of section. Any initialized grades will be deleted.</dd>
                                    </dl>
                                </div>
                            </form>
                        </div>
                        <!-- PROFILE PICTURE END -->
                        <!-- DOCUMENT DETAILS -->
                        <div class="col-xl-8 ps-5 container">
                            <div class="row">
                                <h5><b>Documents</b></h5>
                            </div>
                            <div class="ps-3 row me-3">
                                    <div class="col-md-4 card">
                                        <div class="thumbnail">
                                            <div class="caption">
                                                <p class="fw-bold text-center">PSA DOCUMENT</p>
                                            </div>
                                            <img id="psa" src="<?php echo $psaPreview; ?>" class="img-responsive" alt="PSA document" style="width:100%">
                                            <!-- </a> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4 card ms-4">
                                        <div class="thumbnail">
                                            <div class="caption">
                                                <p class="fw-bold text-center">FORM 137</p>
                                            </div>
                                            <img id="form137" src="<?php echo $form137Preview; ?>" class="img-responsive" alt="Form 137" style="width:100%">
                                        </div>
                                    </div>
                                <!-- <div class="col-sm-3">
                                    <h6>FORM 138</h6>
                                </div>
                                <div class="col-sm-9">
                                    <a href="#" id="pop">
                                        <img id="imageresource" src="<?php //echo $form_137; ?>" style="width: 50%; height: auto;">
                                    </a>

                                   
                                </div> -->
                            </div>
                            <!-- <div class="row mt-3">
                                <div class="row">
                                
                                </div> -->
                                <!-- <div class="col-sm-3">
                                    <h6>PSA Birth Certificate</h6>
                                </div>
                                
                                <div class="col-sm-9">
                                    <a href="#" id="pop">
                                        <img id="imageresource" src="<?php // echo $birth_cert; ?>" style="width: 50%; height: auto;">
                                    </a>

                                   
                                </div> -->
                            <!-- </div> -->
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
                                    <button class="close btn btn-dark close-btn validate"  data-name='reject'>Decline</button>
                                    <button class="close btn btn-success close-btn" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FIRST -->
 <!-- Creates the bootstrap modal where the image will appear -->
 <!-- <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Image preview</h4>
            </div>
            <div class="modal-body">
                <img src="<?php echo $form_137; ?>" id="imagepreview" style="width: 400px; height: 264px;" >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->

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
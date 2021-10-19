<?php
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

$profile_image = is_null($id_picture) ? "../assets/profile.png" : $id_picture;
$psa_image = is_null($psa_birth_cert) ? "../assets/psa_preview.jpg" : $psa_birth_cert;
?>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php">Enrollment</a></li>
            <li class="breadcrumb-item active">Enrollment Credentials</li>
        </ol>
    </nav>
    <h3>Enrollment Credentials</h3>
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
                <div class="row w-100 h-auto text-start mx-auto">
                    <!-- <h5>DOCUMENTS</h5> -->
                    <!-- <hr> -->
                    <div class="row p-0">
                        <!-- PROFILE PICTURE -->
                        <div class="col-xl-3">
                            <?php $image = is_null($id_picture) ? "../assets/profile.png" : $id_picture;
                            echo "<img src='$image' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'" ?>
                            <br>
                            <p><span class="fw-bold">Student LRN: </span><?php echo $lrn; ?></p>
                            <p><span class="fw-bold">Name: </span></p>
                            <button type='button' class='btn btn-success ms-2 mb-2 w-100 '>ACCEPT ENROLLEE</button>
                            <button class='btn btn-secondary ms-2 mb-2 w-100' title='Decline Enrollee'>DECLINE ENROLLEE</button>
                        </div>
                        <!-- PROFILE PICTURE END -->
                        <!-- DOCUMENT DETAILS -->
                        <div class="col-xl-7 ms-5">
                            <h4><b>Documents</b></h4>
                            <div class="row me-3">
                                
                                <div class="col">
                                    <a href="#" id="pop">
                                        <img id="imageresource" src="https://upload.wikimedia.org/wikipedia/commons/c/c8/Alien_Case_File_for_Francesca_Rhee_-_NARA_-_6336263_%28page_29%29.jpg" style="width: 50%; height: auto;">
                                    </a>

                                    <!-- Creates the bootstrap modal where the image will appear -->
                                    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Image preview</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="" id="imagepreview" style="width: 400px; height: 264px;" >
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5>FORM 138</h5>
                                    <label for="date">Date Uploaded: </label><br>
                                    <label for="status">Status: </label>
                                </div>
                                
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="col">
                                    <a href="#" id="pop">
                                        <img id="imageresource" src="https://upload.wikimedia.org/wikipedia/commons/c/c8/Alien_Case_File_for_Francesca_Rhee_-_NARA_-_6336263_%28page_29%29.jpg" style="width: 50%; height: auto;">
                                    </a>

                                    <!-- Creates the bootstrap modal where the image will appear -->
                                    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Image preview</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="" id="imagepreview" style="width: 400px; height: 264px;" >
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5>PSA Birth Certificate</h5>
                                    <label for="date">Date Uploaded: </label><br>
                                    <label for="status">Status: </label>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="select-section-modal" tabindex="-1" aria-labelledby="modal selectSection" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-title">
                                        <h4 class="mb-0">Select Section</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="overflow-auto" style="height: 50vh;">
                                        <ul class="list-group sec-list">
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#pop").on("click", function() {
        $('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
        $('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
    });
</script>
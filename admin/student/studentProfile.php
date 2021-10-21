<?php include_once("../inc/head.html");
require_once("../class/Administration.php");
$admin = new Administration();
// $_SESSION['userType'] = 'admin';
// $_SESSION['userID'] = 'alvin';

$link = "student.php";
$userProfile = $admin->getProfile("ST");
$stud_id = $userProfile->get_stud_id();
$user_id_no = $userProfile->get_id_no();
$lrn = $userProfile->get_lrn();
$name = $userProfile->get_name();
$sex = $userProfile->get_sex();
$age = $userProfile->get_age();
$birthdate = $userProfile->get_birthdate();
$birthdate = date("F j, Y", strtotime($birthdate));
$birth_place = $userProfile->get_birth_place();
$indigenous_group = $userProfile->get_indigenous_group();
$mother_tongue = $userProfile->get_mother_tongue();
$religion = $userProfile->get_religion();
$address = $userProfile->get_address();
$add = $address['address'];
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
        ${$parent . '_name'} = $par['name'];
        ${$parent . '_occupation'} = $par['occupation'];
        ${$parent . '_cp_no'} = $par['cp_no'];
    }
}

$guardian = $userProfile->get_guardians();
if (is_null($guardian)) {
    $guardian = NULL;
} else {
    $guardian_name = $guardian['name'];
    $guardian_cp_no = $guardian['cp_no'];
    $guardian_relationship = $guardian['relationship'];
}

const PROFILE_PATH = "../assets/profile.png";
$image = !is_null($id_picture) ? (file_exists("../$id_picture") ? "../" . $id_picture : PROFILE_PATH) : PROFILE_PATH;
$psaPreview = !is_null($id_picture) ? (file_exists("../$psa_birth_cert") ? "../" . $psa_birth_cert : "../uploads/credential/9/test.png") : "../uploads/credential/9/test.png";

$form137Preview = !is_null($id_picture) ? (file_exists("../$form137") ? "../" . $form137 : "../uploads/credential/9/form137.jpg") : "../uploads/credential/9/form137.jpg";
$user_type = $_SESSION['user_type'];
switch ($user_type) {
    case 'AD':
        $breadcrumb = "<li class='breadcrumb-item'><a href='student.php' target='_self'>Student</a></li>";
        break;
    case 'FA':
        $breadcrumb = '';
        break;
}
?>

<title>Student Information | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <?php echo $breadcrumb; ?>
            <li class="breadcrumb-item active">Profile</a></li>
        </ol>
    </nav>
</header>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="my-auto fw-bold">Student Profile</h4>
    <div class="d-flex justify-content-center">
        <a href="student.php?action=edit&id=<?php echo $stud_id; ?>" role="button" class="btn btn-secondary link my-auto me-3"><i class="bi bi-pencil-square me-2"></i>Edit</a>
        <button id="deactivate-btn" class="btn btn-danger me-3" data-bs-toggle="modal" data-bs-target="#confirmation-modal">Deactivate</button>
    </div>
    <div class="modal fade" id="confirmation-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Deactivate <?php echo $name; ?>?<br>
                    <small>Deactivating user will result in unavailability of all the user's data in the GEMIS. </small>
                </div>
                <div class="modal-footer">
                    <form id="deactivate-form" method="POST" action="action.php">
                        <input type="hidden" name="user_type" value="ST" />
                        <input type="hidden" name="action" value="deactivate" />
                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" form="deactivate-form" class="submit btn btn-danger" value="Deactivate">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MAIN CONTENT -->
<div class='container my-3'>
    <div class="card p-3 text-center">
        <div class="">
            <nav id="myTab">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="nav-gen-info-tab" data-bs-toggle="tab" data-bs-target="#gen-info" type="button" role="tab" aria-controls="gen-info" aria-selected="true">General Information</a>
                    <a class="nav-link" id="nav-docu-tab" data-bs-toggle="tab" data-bs-target="#docu" type="button" role="tab" aria-controls="docu" aria-selected="false">Documents</a>
                </div>
            </nav>
        </div>
        <div class="tab-content" id="myTabContent">
            <!-- GENERAL INFORMATION -->
            <div class="tab-pane fade bg-white p-4 show active" id="gen-info" role="tabpanel" aria-labelledby="home-tab">
                <div class="row w-100 h-auto text-start mx-auto">
                    <!-- <h5>GENERAL INFORMATION</h5> -->
                    <!-- <hr> -->
                    <div class="row p-0">
                        <!-- PROFILE PICTURE -->
                        <div class="col-xl-3">
                            <?php echo "<img src='../$image' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'" ?>
                            <br>
                            <p><span class="fw-bold">Student LRN: </span><?php echo $lrn; ?></p>
                            <!-- <button type='button' class='transfer-stud btn btn-success ms-2 mb-2 w-100 ' href="studentTranfer.php?id=<?php echo $stud_id ?>">TRANSFER STUDENT</button> -->
                            <a href='student.php?action=transfer&id=<?php echo $stud_id ?>' class='transfer-stud btn btn-success ms-2 mb-2 w-100'>TRANSFER STUDENT</a>
                            <button class='btn btn-secondary ms-2 mb-2 w-100' title='Reset Password'>RESET PASSWORD</button>
                        </div>
                        <?php $admin->listValuesReport() ?>

                        <!-- PROFILE PICTURE END -->
                        <!-- INFORMATION DETAILS -->
                        <div class="col-xl-7 ms-5">
                            <div class="row">
                                <h6><b>General Information</b></h6>
                                <?php
                                $birthdate = $userProfile->get_birthdate();
                                $birthdate = date("F j, Y", strtotime($birthdate));
                                $name = $userProfile->get_name();
                                echo "<ul class='list-group ms-3'>
                                <li class='list-group-item'>Name: $name<br>
                                <li class='list-group-item'>Gender: {$userProfile->get_sex()}<br>
                                <li class='list-group-item'>Age: {$userProfile->get_age()}<br>
                                <li class='list-group-item'>Birthdate: {$birthdate}<br>
                                <li class='list-group-item'>Birth Place: $birth_place<br>
                                <li class='list-group-item'>Indeginous Group: $indigenous_group<br>
                                <li class='list-group-item'>Mother Tongue: $mother_tongue<br>
                                <li class='list-group-item'>Religion: $religion</ul>";
                                ?>
                            </div>
                            <div class="row mt-3">
                                <h6><b>Contact Information</b></h6>
                                <?php echo "<ul class='list-group ms-3'>
                                        <li class='list-group-item'>Home Address: $add<br>
                                        <li class='list-group-item'>Cellphone No.: $cp_no</ul"; ?>
                            </div>

                            <div class="row mt-3">
                                <h6><b>Contact Persons</b></h6>
                                <?php if ($parents != NULL) {
                                    echo "<h6>PARENT/S</h6>
                                        <ul class='list-group ms-3'>";
                                    foreach ($parents as $par) {
                                        $parent = $par['sex'] == 'f' ? 'mother' : 'father';
                                        $name = ${$parent . '_name'};
                                        $occupation = ${$parent . '_occupation'};
                                        $no = ${$parent . '_cp_no'};
                                        echo "<li class='list-group-item'>$parent's Name: $name </li>
                                                 <li class='list-group-item'>Occupation: $occupation</li>
                                                 <li class='list-group-item'>Contact Number: $no</li>";
                                    }
                                    echo "</ul>";
                                }
                                if ($guardian != NULL) {
                                    echo "<h6 class='mt-3'>GUARDIAN/S</h6>
                                        <ul class='list-group ms-3'>
                                            <li class='list-group-item'>Guardian's Name: $guardian_name</li>
                                            <li class='list-group-item'>Relationship: $guardian_relationship</li>
                                            <li class='list-group-item'>Contact Number: $guardian_cp_no</li>'</ul>";
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- DOCUMENTS TAB -->
        <div class="tab-pane fade bg-white p-4" id="docu" role="tabpanel" aria-labelledby="docu-tab">
            <div class="row w-100 h-auto text-start mx-auto">
                <div class="row p-0">
                    <div class="row">
                        <div class="col-md-4 card">
                            <div class="thumbnail">
                                <div class="caption">
                                    <p class="fw-bold text-center">PSA DOCUMENT</p>
                                </div>
                                <img id="psa" src=<?php echo $psaPreview; ?> class="img-responsive" alt="PSA document" style="width:100%">
                                <!-- </a> -->
                            </div>
                        </div>

                        <div class="col-md-4 card ms-4">
                            <div class="thumbnail">
                                <div class="caption">
                                    <p class="fw-bold text-center">FORM 137</p>
                                </div>
                                <img id="form137" src=<?php echo $form137Preview; ?> class="img-responsive" alt="Form 137" style="width:100%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="imgPreview" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Document Preview</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="img01">
                        <img id="psaPreview" class="img-responsive" alt="PSA document" style="width:100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
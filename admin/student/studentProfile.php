<?php include_once("../inc/head.html");
require_once("../class/Administration.php");
$admin = new Administration();
$userProfile = $admin->getProfile("S");

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
            <li class="breadcrumb-item"><?php echo "<a href='student.php' target='_self'>Student</a>"; ?></li>
            <li class="breadcrumb-item active">Profile</a></li>

        </ol>
    </nav>
    <h3 class="fw-bold">Student Information</h3>
</header>
<!-- MAIN CONTENT -->
<!-- Photo -->
<div class="container mt-3 col-3">
    <div class="buttons mt-3">
        <?php $image = is_null($id_picture) ? "../assets/profile.png" : $id_picture;
        echo "<img src='$image' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'>
                                <h5 class='mb-4'>STUDENT LRN: $lrn</h5>
                                <a href='student.php?id=${stud_id}&action=edit' class = 'btn btn-success ms-2 mb-2 w-100'>EDIT PROFILE</a>
                                <button type = 'button' class='transfer-stud btn btn-success ms-2 mb-2 w-100 ' id='${stud_id}'>TRANSFER STUDENT</button>
                                <button class='btn btn-secondary ms-2 mb-2 w-100' title='Reset Password'>RESET PASSWORD</button>"
        ?>
    </div>

</div>
<!-- Personal Details -->
<div class="container mt-4 col-8">
    <div class="card body w-100 h-auto">
        <div class="row col-12">
            <h4 class="fw-bold">GENERAL INFORMATION</h4>
            <ul class="list-group ms-3">
                <?php echo "<li class='list-group-item'>Name: $name</li>
                                        <li class='list-group-item'>Gender: $sex</li>
                                        <li class='list-group-item'>Age: $age </li>
                                        <li class='list-group-item'>Birthdate: $birthdate</li>
                                        <li class='list-group-item'>Birth Place: $birth_place</li>
                                        <li class='list-group-item'>Indeginous Group: $indigenous_group</li>
                                        <li class='list-group-item'>Mother Tongue: $mother_tongue</li>
                                        <li class='list-group-item'>Religion: $religion</li>"; ?>

            </ul>
        </div>
        <div class="row col-12">
            <h4 class="mt-3 fw-bold">CONTACT INFORMATION</h4>
            <ul class="list-group ms-3">
                <?php echo "<li class='list-group-item'>Home Address: $add </li>
                                        <li class='list-group-item'>Contact Number: $cp_no </li>" ?>

            </ul>
        </div>

        <div class="row col-12">
            <h4 class="mt-3 fw-bold">CONTACT PERSONS</h4>
            <?php if ($parents != NULL) {
                echo "<h5>PARENT/S</h5>
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
                echo "<h5 class='mt-3 fw-bold'>GUARDIAN/S</h5>
                                        <ul class='list-group ms-3'>
                                            <li class='list-group-item'>Guardian's Name: $guardian_name</li>
                                            <li class='list-group-item'>Relationship: $guardian_relationship</li>
                                            <li class='list-group-item'>Contact Number: $guardian_cp_no</li>'</ul>";
            } ?>
        </div>

    </div>
</div>


<div class="modal" id="select-section-modal" tabindex="-1" aria-labelledby="modal selectSection" aria-hidden="true">
    <div class="modal-dialog">
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

<div class="modal" id="transfer-student-confirmation" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Confirmation</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Do you want to transfer the student to <span id="modal-identifier"></span>?</h5>
                <p class="modal-msg"></p>
            </div>
            <div class="modal-footer">
                <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary close-btn transfer-btn">Transfer</button>
            </div>
        </div>
    </div>
</div>
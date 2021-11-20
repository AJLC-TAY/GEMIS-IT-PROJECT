<?php

require_once("../class/Administration.php");
$admin = new Administration();
$user_type = $_SESSION['user_type'];

$link = "student.php";
$userProfile = $admin->getProfile("ST");
$strand = $userProfile->get_strand();
$yrlvl = $userProfile->get_yrLvl();
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
$guardian = $userProfile->get_guardians();
$grades = $userProfile->get_grades();
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

const PROFILE_PATH = "../assets/profile.png";
const PREVIEW_PATH = "../assets/no_preview.jpg";
$image = !is_null($id_picture) ? (file_exists($id_picture) ? $id_picture : PROFILE_PATH) : PROFILE_PATH;
$psaPreview = !is_null($id_picture) ? (file_exists($psa_birth_cert) ? $psa_birth_cert : PREVIEW_PATH) : PREVIEW_PATH;

$form137Preview = !is_null($id_picture) ? (file_exists($form137) ? $form137 : PREVIEW_PATH) : PREVIEW_PATH;
$user_type = $_SESSION['user_type'];

switch ($user_type) {
    case 'AD':
        $breadcrumb = "<li class='breadcrumb-item'><a href='student.php' target='_self'>Student</a></li>";
        break;
    case 'FA':
    case 'ST':
        $breadcrumb = '';
        break;
}
const ACTIVE = "show active";
$tab_one_active = ACTIVE;
$tab_two_active = "";
$tab_three_active = "";
$is_tab_one = "active";
$is_tab_two = "";
$is_tab_three = "";
$is_tab_four = "";
$tab_four_active = "";
$is_four_three = "";
if (isset($_GET['tab'])) {
    switch ($_GET['tab']) {
        case "docs":
            $tab_one_active = "";
            $is_tab_one = "";
            $tab_two_active = ACTIVE;
            $is_tab_two = "active";
            break;
        case "grades":
            $tab_one_active = "";
            $is_tab_one = "";
            $tab_three_active = ACTIVE;
            $is_tab_three = "active";
            break;
        case "attendance":
            $tab_one_active = "";
            $is_tab_one = "";
            $tab_four_active = ACTIVE;
            $is_four_three = "active";
            break;
        default:
            $tab_one_active = ACTIVE;
            $is_tab_one = "active";
    }
}
$url = "getAction.php?data=attendance&id={$stud_id}";
?>
<header>
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <?php
            if ($user_type != 'ST') {
                echo "<li class='breadcrumb-item'><a href='index.php'>Home</a></li>
             $breadcrumb
            <li class='breadcrumb-item active'>Profile</a></li>";
            }
            ?>
        </ol>
    </nav>
    <!-- BREADCRUMB -->
</header>
<div class="d-flex justify-content-between align-items-center">
    <h4 class="my-auto fw-bold">Student Profile</h4>
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
                        <input type="hidden" name="id[]" value="<?php echo $stud_id; ?>">
                        <button class="close btn btn-dark close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" form="deactivate-form" class="submit btn btn-danger btn-sm" value="Deactivate">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reset-confirmation-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Reset password of <?php echo $name; ?>?<br>
                </div>
                <div class="modal-footer">
                    <form id="reset-form" method="POST" action="action.php">
                        <input type="hidden" name="user_type" value="ST" />
                        <input type="hidden" name="action" value="reset" />
                        <input type="hidden" name="id[]" value="<?php echo $stud_id; ?>" />
                    </form>
                    <button class="close btn btn-dark close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" form="reset-form" class="submit btn btn-secondary btn-sm" value="Reset Password">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MAIN CONTENT -->
<div class='container my-3'>
    <div class="card p-3 text-center">
        <nav id="myTab">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-link <?php echo $is_tab_one; ?>" id="nav-gen-info-tab" data-bs-toggle="tab" data-bs-target="#gen-info" type="button" role="tab" aria-controls="gen-info" aria-selected="true">General Information</a>
                <a class="nav-link <?php echo $is_tab_two; ?>" id="nav-docu-tab" data-bs-toggle="tab" data-bs-target="#docu" type="button" role="tab" aria-controls="docu" aria-selected="false">Documents</a>
                <?php if ($user_type == 'AD') { ?>
                    <a class="nav-link <?php echo $is_tab_three; ?>" id="nav-grades-tab" data-bs-toggle="tab" data-bs-target="#grades" type="button" role="tab" aria-controls="grades" aria-selected="false">Grades</a>
                    <a class="nav-link <?php echo $is_tab_four; ?>" id="nav-attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab" aria-controls="attendance" aria-selected="false">Attendance</a>
                <?php } ?>
            </div>
        </nav>
        <div class="tab-content" id="myTabContent">
            <!-- GENERAL INFORMATION -->
            <div class="tab-pane fade bg-white p-4 <?php echo $tab_one_active; ?>" id="gen-info" role="tabpanel" aria-labelledby="home-tab">
                <div class="row w-100 h-auto text-start mx-auto">
                    <div class="row p-0">
                        <!-- PROFILE PICTURE -->
                        <div class="col-xl-3">
                            <?php echo "<img src='../$image' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'" ?>
                            <br>
                            <?php echo "<dl class='row mb-2 ms-3'>
                                    <dt class='col-md-9'>User ID: </dt>
                                    <dd class='col-md-9'> $user_id_no </dd>
                                    <dt class='col-md-9'>Strand: </dt>
                                    <dd class='col-md-9'> $strand </dd>
                                    <dt class='col-md-9'>Grade Level: </dt>
                                    <dd class='col-md-9'>$yrlvl</dd>
                            </dl>" ?>
                            
                            <!-- <button type='button' class='transfer-stud btn btn-success ms-2 mb-2 w-100 ' href="studentTranfer.php?id=<?php echo $stud_id ?>">TRANSFER STUDENT</button> -->
                            <?php if ($user_type != 'ST') {
                                echo "<a href='student.php?action=transfer&id=$stud_id' class='transfer-stud btn btn-success btn-sm  ms-2 mb-2 w-100'>Transfer Student</a>";
                                $edit_btn = "<a href='student.php?action=edit&id=$stud_id' role=button' class='btn btn-primary link my-auto btn-sm ms-2 mb-2 w-100'><i class='bi bi-pencil-square me-2'></i>Edit</a>";
                                if ($user_type == 'AD') {
                                    echo "<button data-id='$stud_id' class='btn btn-secondary ms-2 mb-2 btn-sm w-100' data-bs-toggle='modal' data-bs-target='#reset-confirmation-modal' title='Reset Password'>Reset Password</button>$edit_btn";
                                    echo "<button id='deactivate-btn' class='btn btn-danger btn-sm ms-2 mb-2 w-100' data-bs-toggle='modal' data-bs-target='#confirmation-modal'>Deactivate</button>";
                                } else {
                                    echo $edit_btn;
                                }
                            } else {
                                echo "<a href='../student/changePW.php' class='btn btn-secondary ms-2 mb-2 w-100' title='Change Password'>CHANGE PASSWORD</a>";
                            } ?>

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
                                echo
                                "<dl class='row mb-3 ms-2'>
                                            <dt class='col-md-3'>Student LRN: </dt>
                                            <dd class='col-md-9'>$lrn</dd>
                                            <dt class='col-md-3'>Name: </dt>
                                            <dd class='col-md-9'> $name </dd>
                                            <dt class='col-md-3'>Gender: </dt>
                                            <dd class='col-md-9'> {$userProfile->get_sex()} </dd>
                                            <dt class='col-md-3'>Age: </dt>
                                            <dd class='col-md-9'> {$userProfile->get_age()} </dd>
                                            <dt class='col-md-3'>Birthdate: </dt>
                                            <dd class='col-md-9'> {$birthdate} </dd>
                                            <dt class='col-md-3'>Birth Place: </dt>
                                            <dd class='col-md-9'> $birth_place </dd>
                                            <dt class='col-md-3'>Indeginous Group: </dt>
                                            <dd class='col-md-9'> $indigenous_group </dd>
                                            <dt class='col-md-3'>Mother Tongue: </dt>
                                            <dd class='col-md-9'> $mother_tongue </dd>
                                            <dt class='col-md-3'>Religion: </dt>
                                            <dd class='col-md-9'> $religion </dd>

                                </dl>";
                                ?>
                            </div>
                            <div class="row mt-3">
                                <h6><b>Contact Information</b></h6>
                                <?php echo
                                "<dl class='row mb-3 ms-2'>
                                        <dt class='col-md-3'>Home Address: </dt>
                                        <dd class='col-md-9'> $add </dd>
                                        <dt class='col-md-3'>Cellphone No.: </dt>
                                        <dd class='col-md-9'> $cp_no </dd>
                                        
                                    </dl>"; ?>
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
                                            <dt class='col-md-3'>" . ucwords($parent) . "'s Name: </dt>
                                            <dd class='col-md-9'> $name </dd>
                                            <dt class='col-md-3'>Occupation: </dt>
                                            <dd class='col-md-9'> $occupation </dd>
                                            <dt class='col-md-3'>Contact Number: </dt>
                                            <dd class='col-md-9'> $no </dd>
                                        </dl>";
                                    }
                                }
                                if ($guardian != NULL) {
                                    echo "<h6 class='mt-3'>Guardian/s</h6>
                                        <dl class='row mb-3 ms-2'>
                                            <dt class='col-md-3'>Guardian's Name: </dt>
                                            <dd class='col-md-9'> $guardian_name </dd>
                                            <dt class='col-md-3'>Relationship: </dt>
                                            <dd class='col-md-9'> $guardian_relationship </dd>
                                            <dt class='col-md-3'>Contact Number: </dt>
                                            <dd class='col-md-9'> $guardian_cp_no </dd>
                                        </dl>";
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DOCUMENTS TAB -->
            <div class="tab-pane fade bg-white p-4 <?php echo $tab_two_active; ?>" id="docu" role="tabpanel" aria-labelledby="docu-tab">
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
            <!-- GRADES TAB -->
            <?php if ($user_type == 'AD') { ?>
                <div class="tab-pane fade bg-white p-4 <?php echo $tab_three_active; ?>" id="grades" role="tabpanel" aria-labelledby="docu-tab">
                    <div class="container px-1 text-start">
                        <div class="row p-0">
                            <h5>Subject checklist</h5>
                            <div class="current-con">
                                <?php
                                function echoSubjects($grades, $status, $sub_type)
                                {
                                    if (isset($grades[$status][$sub_type])) {
                                        echo "<tr><td colspan='5' class='bg-light fw-bold'>" . ucwords($sub_type) . " subjects</td></tr>";
                                        foreach ($grades[$status][$sub_type] as $sub_grd) {
                                            $sub_grd_id = (int) $sub_grd['grade_id'];
                                            echo "<tr>
                                                <td class='ps-3'>{$sub_grd['name']}</td>
                                                <td align='center'><input type='number' min='60' max='100' name='grade[" . $sub_grd_id . "][first]' class='number form-control form-control-sm mb-0 text-center Second'  value='{$sub_grd['first']}' disabled></td>
                                                <td align='center'><input type='number' min='60' max='100' name='grade[" . $sub_grd_id . "][second]' class='number form-control form-control-sm mb-0 text-center Second'  value='{$sub_grd['second']}' disabled></td>
                                                <td align='center'><input type='number' min='60' max='100' name='grade[" . $sub_grd_id . "][final]' class='number form-control form-control-sm mb-0 text-center'  value='{$sub_grd['final']}' disabled></td>
                                                <td  align='center'>
                                                    <div class='d-flex justify-content-center'>
                                                        <button data-grade-id='$sub_grd_id' class='action btn btn-sm btn-secondary' data-action='edit'><i class='bi bi-pencil-square'></i></button>
                                                        <div class='edit-options d-flex d-none'>
                                                            <button data-grade-id='$sub_grd_id' class='action btn btn-sm btn-dark me-1' data-action='cancel'>Cancel</button>
                                                            <button data-grade-id='$sub_grd_id' class='action btn btn-sm btn-success' data-action='save'>Save</button>
                                                        </div>
                                                    </div>
                                                </td>
                                        </tr>";
                                        }
                                    }
                                }
                                ?>
                                <table class="table table-sm table-bordered grade-table">
                                    <col width="55%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="15%">
                                    <thead align="center">
                                        <tr>
                                            <td rowspan="2">Subject Code</td>
                                            <td colspan="2">Grading</td>
                                            <td rowspan="2">Final Grade</td>
                                            <td rowspan="2">Action</td>
                                        </tr>
                                        <tr>
                                            <td>First</td>
                                            <td>Second</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echoSubjects($grades, 'current', 'core'); ?>
                                        <?php echoSubjects($grades, 'current', 'applied'); ?>
                                        <?php echoSubjects($grades, 'current', 'specialized'); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row p-0">
                            <h5>Already taken subjects</h5>
                            <div class="advanced-con">
                                <table class="table table-sm table-bordered grade-table">
                                    <col width="55%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="15%">
                                    <thead align="center">
                                        <tr>
                                            <td rowspan="2">Subject Code</td>
                                            <td colspan="2">Grading</td>
                                            <td rowspan="2">Final Grade</td>
                                            <td rowspan="2">Action</td>
                                        </tr>
                                        <tr>
                                            <td>First</td>
                                            <td>Second</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echoSubjects($grades, 'advanced', 'core'); ?>
                                        <?php echoSubjects($grades, 'advanced', 'applied'); ?>
                                        <?php echoSubjects($grades, 'advanced', 'specialized'); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Attendance Tab -->
                <div class="tab-pane fade bg-white p-4 <?php echo $tab_three_active; ?>" id="attendance" role="tabpanel" aria-labelledby="docu-tab">
                <div class="container mt-2 ms-0">
                            <div class="card w-100 h-auto bg-light">
                                <form id="attendance-form" >
                                    <table id="table" data-url="<?php echo $url; ?>" class="table-striped table-sm">
                                        <thead class='thead-dark'>
                                            <tr>
                                                <th scope='col' data-width="100" data-align="center" data-field="month" title="month">Month</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="present">Present</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="absent">Absent</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="tardy">Tardy</th>
                                                <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </form>
                            </div>
                        </div>
                </div>
                <div class="modal fade" id="confirmation-edit-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-title">
                                    <h4 class="mb-0">Confirmation</h4>
                                </div>
                            </div>
                            <div class="modal-body">
                                Saving this changes will not allow <span id = "teacher"> </span> from editing the student's <span id="type"></span>. Proceed?
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-sm btn-dark" data-bs-dismiss="modal">Cancel</button>
                                <button class="submit-edit-button btn btn-sm btn-success" data-type = "" data-bs-dismiss="modal">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

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
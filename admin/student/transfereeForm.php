<?php
require_once("../class/Administration.php");
$admin = new Administration();
$stud_id = $_GET['stud_id'];
$strand = $_GET['strand'];
$result = $admin->query("SELECT * FROM transferee WHERE stud_id = '$stud_id';");
$student_name = '';
$sy_id = $_SESSION['sy_id'];

$transferee_id = '';
$transfereeData = [
    'transferee_id' => $transferee_id,
    'school_name' => '',
    'grade_level' => '',
    'school_year' => '',
    'grd'         => '',
    'semester'    => '',
    'track'       => '',
    'strand_code' => '',
    'strand_name' => ''
];  

$enrolled_strand = mysqli_fetch_row($admin->query("SELECT e.prog_code, description FROM enrollment e JOIN program USING (prog_code) WHERE stud_id='$stud_id' AND sy_id = '$sy_id' ORDER BY date_of_enroll DESC LIMIT 1;"));
if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $transferee_id = $row['transferee_id'];
    $transfereeData = [
        'transferee_id' => $transferee_id,
        'school_name' => $row['school_name'],
        'grade_level' => $row['last_grd_lvl_comp'],
        'school_year' => $row['last_school_yr_comp'],
        'grd'         => $row['grd_to_enroll'],
        'semester'    => $row['semester'],
        'track'       => $row['track'],
        'strand_code' => $enrolled_strand[0],
        'strand_name' => strtoupper($enrolled_strand[1])
    ];  
}


$student_name = mysqli_fetch_row($admin->query("SELECT CONCAT(last_name,', ',first_name,' ',COALESCE(middle_name, ''), ' ', COALESCE(ext_name, '')) as name FROM student WHERE stud_id = '$stud_id';"))[0];
$subjectsData = $admin->getSubjectScheduleData(NULL, TRUE, $transferee_id);
$sub_opt = $subjectsData['options'] ?? NULL;
?>
<script>
    let transfereeID = <?php echo json_encode($transferee_id); ?>;
    let schedOptions = <?php echo json_encode($sub_opt); ?>;
    let schedule = <?php echo json_encode($subjectsData['schedule']); ?>;
    let strandCode = <?php echo json_encode($enrolled_strand[0]); ?>;
</script>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="student.php">Student</a></li>
            <li class="breadcrumb-item active">Transferee Assessment</li>
        </ol>
    </nav>
    <h3 class="fw-bold">Transferee Assessment Form</h3>
</header>
<!-- CONTENT  -->
<form id="transferee-form" method="POST" action="action.php">
    <input type="hidden" name="transferee-id" value="<?php echo $transferee_id; ?>">
    <input type="hidden" name="prog-code" value="<?php echo $transfereeData['strand_code']; ?>">
    <input type="hidden" name="stud-id" value="<?php echo $stud_id; ?>">
    <div class="card w-100 h-auto mt-4 p-4">
        <div class="trans-detail">
            <div class="container">
                <div class="row mb-3">
                    <input type="hidden" class="form-control form-control-sm" value="<?php echo $stud_id; ?>">
                    <div class="col-md-2">Student Name</div>
                    <div class="col-md-10"><input type="text" class="form-control form-control-sm" readonly value="<?php echo $student_name; ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row justify-content-center align-content-center">
                            <label for="school-last-attended" class="col-form-label col-lg-3">School Last Attended</label>
                            <div class="col-lg-9">
                                <textarea id="school-last-attended" name="trans-school" class="form-control form-control-sm" placeholder="Enter school name"><?php echo $transfereeData['school_name']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row justify-content-center align-content-center">
                            <label for="school-last-attended" class="col-form-label col-lg-3">Track</label>
                            <div class="col-lg-9">
                                <input value="<?php echo $transfereeData['track'] ?>" type="text" name="trans-track" class="form-control form-control-sm" placeholder="Enter track (ex. ACADEMIC)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row justify-content-center align-content-center">
                            <label for="school-last-attended" class="col-form-label col-lg-3">Semester</label>
                            <div class="col-lg-9">
                                <input value="<?php echo $transfereeData['semester']; ?>" type="text" name="trans-semester" class="form-control form-control-sm" placeholder="Enter semester (ex. First)">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row justify-content-center align-content-center">
                            <label for="school-last-attended" class="col-form-label col-lg-3">School Year</label>
                            <div class="col-lg-9">
                                <input value="<?php echo $transfereeData['school_year']; ?>" type="text" name="trans-sy" class="form-control form-control-sm" placeholder="Enter school year (ex. 20XX - 20XX)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card w-100 h-auto mt-4 p-4">
        <div class="subject-list">
            <h5 class="fw-bold">LIST OF SUBJECTS FOR <?php echo $transfereeData['strand_name']; ?></h5>
            <p class="text-secondary ms-1"><small><i class="bi bi-info-circle me-2"></i>Check subjects the student have already taken from previous school, regardless of the grade level in the subject list below.</small></p>
            <div class="container overflow-auto">
                <table id="transfer-table" class="table table-sm table-bordered table-striped">
                    <thead class="text-center">
                    <tr>
                        <td colspan="2">GRADE 11</td>
                        <td colspan="2">GRADE 12</td>
                    </tr>
                    <tr>
                        <td>FIRST SEMESTER</td>
                        <td>SECOND SEMESTER</td>
                        <td>FIRST SEMESTER</td>
                        <td>SECOND SEMESTER</td>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card w-100 h-auto mt-4 p-4 overflow-auto">
        <h5 class="fw-bold">SUGGESTED SCHEDULE</h5>
        <table class="table table-striped table-bordered">
            <col width="10%">
            <col width="45%">
            <col width="45%">
            <thead class="text-center bg-light">
            <tr>
                <td rowspan="2"></td>
                <td colspan="2"><b>Grade 11</b></td>
            </tr>
            <tr>
                <td><b>1st Semester</b></td>
                <td><b>2nd Semester</b></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center"><b>Core</b></td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[11][1][core][]" multiple="multiple">
                    </select>

                </td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[11][2][core][]" multiple="multiple">
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text-center"><b>Contextualized</b></td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[11][1][applied][]" multiple="multiple">
                    </select>
                </td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[11][2][applied][]" multiple="multiple">
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text-center"><b>Specialization</b></td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[11][1][specialized][]" multiple="multiple">
                    </select>
                </td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[11][2][specialized][]" multiple="multiple">
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
        <table class="table table-striped table-bordered mt-4">
            <col width="10%">
            <col width="45%">
            <col width="45%">
            <thead class="text-center bg-light">
            <tr>
                <td rowspan="2"></td>
                <td colspan="2"><b>Grade 12</b></td>
            </tr>
            <tr>
                <td><b>1st Semester</b></td>
                <td><b>2nd Semester</b></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center"><b>Core</b></td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[12][1][core][]" multiple="multiple">
                    </select>
                </td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[12][2][core][]" multiple="multiple">
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text-center"><b>Contextualized</b></td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[12][1][applied][]" multiple="multiple">
                    </select>
                </td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[12][2][applied][]" multiple="multiple">
                    </select>
                </td>
            </tr>
            <tr>
                <td class="text-center"><b>Specialization</b></td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[12][1][specialized][]" multiple="multiple">
                    </select>
                </td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="data[12][2][specialized][]" multiple="multiple">
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-row-reverse mt-4">
        <input type="hidden" name="action" value="assessTransferee">
        <input id="transferee-submit" class="btn btn-success" form="transferee-form" type="submit" value="Submit">
    </div>

</div>
</form>
<template id="table-cell-template">
    <td>
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <input id="%ID%" type="checkbox" class="form-check-input" name="subjects[]" value="%ID%">
                </div>
                <label for="%ID%" class="form-check-label col-form-label col-10 py-0">
                    %SUBJECTNAME%
                </label>
            </div>
        </div>
    </td>
</template>

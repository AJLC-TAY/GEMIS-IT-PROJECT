<?php 
require_once ("../class/Administration.php");
$admin = new Administration();
$programs = $admin->listPrograms('program');
$grd_level = [11, 12];
$subjectsData = $admin->getSubjectScheduleData();
$sub_opt = $subjectsData['options'];
$core = '';
$applied = '';
$specialized = '';

foreach($sub_opt['core'] as $sub_core) {

    $core .= "<option value='{$sub_core['code']}'>{$sub_core['name']}</option>";
}
foreach($sub_opt['applied'] as $sub_app) {
    $applied .= "<option value='{$sub_app['code']}'>{$sub_app['name']}</option>";
}
foreach($sub_opt['specialized'] as $sub_spec) {
    $specialized .= "<option value='{$sub_spec['code']}'>{$sub_spec['name']}</option>";
}

$schedule = [];
foreach($subjectsData['schedule'] as $prog => $prog_data) {
    foreach($prog_data as $grade => $grade_data) {
        foreach($grade_data as $sem => $sem_data) {
            foreach($sem_data as $type => $codes) {
                $schedule[$prog."[$grade][$sem][$type][]"] = $codes;
            }
        }
    }
}

?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="subject.php">Subject</a></li>
            <li class="breadcrumb-item active" aria-current="page">Schedule</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Subject Schedule</h3>
    </div>
</header>
<!-- HEADER END -->
<section class="container">
<div class='row card bg-white h-auto text-start mx-auto mt-3'>
    <table class="table table-striped table-bordered">
        <col width="10%">
        <col width="45%">
        <col width="45%">
        <thead class="text-center bg-light">
            <tr>
                <td rowspan="2">ABM</td>
                <td colspan="2">Grade 11</td>
            </tr>
            <tr>
                <td>1st Semester</td>
                <td>2nd Semester</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Core
                </td>
               <td>
                    <select class="js-example-basic-multiple subject-select" name="ABM[11][1][core][]" multiple="multiple">
                        <?php echo $core;  ?>
                    </select>
                </td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="ABM[12][2][core][]" multiple="multiple">
                        <?php echo $core; ?>
                    </select>
               </td>
            </tr>
            <tr>
                <td>
                    Contextualized
                </td>
               <td>
                    <select class="js-example-basic-multiple subject-select" name="ABM[11][1][applied][]" multiple="multiple">
                        <?php echo $applied; ?>
                    </select>
               </td>
               <td>
                    <select class="js-example-basic-multiple subject-select" name="ABM[12][2][applied][]" multiple="multiple">
                        <?php echo $applied; ?>
                    </select>
               </td>
            </tr>
            <tr>
                <td>
                    Specialization
                </td>
               <td>
                    <select class="js-example-basic-multiple subject-select" name="ABM[11][1][specialized][]" multiple="multiple">
                        <?php echo $specialized; ?>
                    </select>
                </td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="ABM[12][2][specialized][]" multiple="multiple">
                        <?php echo $specialized; ?>
                    </select>
               </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="table table-striped table-bordered">
        <col width="10%">
        <col width="45%">
        <col width="45%">
        <thead class="text-center bg-light">
            <tr>
                <td rowspan="2">ABM</td>
                <td colspan="2">Grade 12</td>
            </tr>
            <tr>
                <td>1st Semester</td>
                <td>2nd Semester</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Core
                </td>
               <td>
                    <select class="js-example-basic-multiple subject-select" name="states[]" multiple="multiple">
                        <?php echo $core; ?>
                    </select>
                </td>
                <td>
                    <select class="js-example-basic-multiple subject-select" name="states[]" multiple="multiple">
                        <?php echo $core; ?>
                    </select>
               </td>
            </tr>
            <tr>
                <td>
                    Contextualized
                </td>
               <td>
                    <select class="js-example-basic-multiple subject-select" name="states[]" multiple="multiple">
                        <option value="AL">Alabama</option>
                        <option value="WY">Wyoming</option>
                    </select>
               </td>
               <td>
                    <select class="js-example-basic-multiple subject-select" name="states[]" multiple="multiple">
                        <option value="AL">Alabama</option>
                        <option value="WY">Wyoming</option>
                    </select>
               </td>
            </tr>
            <tr>
                <td>
                    Specialization
                </td>
               <td>
                    <select class="js-example-basic-multiple subject-select" name="states[]" multiple="multiple">
                        <option value="AL">Alabama</option>
                        <option value="WY">Wyoming</option>
                    </select>
               </td>
               <td>
                    <select class="js-example-basic-multiple subject-select" name="states[]" multiple="multiple">
                        <option value="AL">Alabama</option>
                        <option value="WY">Wyoming</option>
                    </select>
               </td>
            </tr>
        </tbody>
    </table>
</div>
</section>
<script>
    let schedule = <?php echo json_encode($schedule); ?>;
</script>
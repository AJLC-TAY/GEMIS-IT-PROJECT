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
$prog_opt = '';

foreach($sub_opt['core'] as $sub_core) {
    $core .= "<option value='{$sub_core['code']}'>{$sub_core['name']}</option>";
}
foreach($sub_opt['applied'] as $sub_app) {
    $applied .= "<option value='{$sub_app['code']}'>{$sub_app['name']}</option>";
}
foreach($sub_opt['specialized'] as $sub_spec) {
    $specialized .= "<option value='{$sub_spec['code']}'>{$sub_spec['name']}</option>";
}

foreach($programs as $prog) {
    $prog_opt .= "<option value='{$prog->get_prog_code()}'>{$prog->get_prog_desc()}</option>";
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
    <div class="d-flex align-items-center mb-3">
        <h3 class="fw-bold me-3">Subject Schedule</h3>
    </div>
    <div class="d-flex">
        <select id="program-select" class="form-select me-1">
            <?php echo $prog_opt; ?>
        </select>
        <button class='btn btn-primary edit-sched-btn ms-1'>Edit</button>
        <div class="d-flex edit-opt-con d-none ms-1">
            <a href="subject.php?page=schedule" class="btn btn-dark me-1">Cancel</a>
            <input type="submit" form="schedule-form" class="btn btn-success save-sched-btn" value="Save">
        </div>
    </div>
</header>
<!-- HEADER END -->
<section class="container">
    <form id="schedule-form" method="POST" action="action.php">
        <div class='row card bg-white h-auto text-start mx-auto mt-3 overflow-auto'>
            <table class="table table-striped table-bordered">
                <col width="10%">
                <col width="45%">
                <col width="45%">
                <thead class="text-center bg-light">
                    <tr>
                        <td rowspan="2"></td>
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
                            <select class="js-example-basic-multiple subject-select" name="data[11][1][core][]" multiple="multiple" disabled>
                                <?php echo $core;  ?>
                            </select>
                        </td>
                        <td>
                            <select class="js-example-basic-multiple subject-select" name="data[11][2][core][]" multiple="multiple" disabled>
                                <?php echo $core; ?>
                            </select>
                    </td>
                    </tr>
                    <tr>
                        <td>
                            Contextualized
                        </td>
                    <td>
                            <select class="js-example-basic-multiple subject-select" name="data[11][1][applied][]" multiple="multiple" disabled>
                                <?php echo $applied; ?>
                            </select>
                    </td>
                    <td>
                            <select class="js-example-basic-multiple subject-select" name="data[11][2][applied][]" multiple="multiple" disabled>
                                <?php echo $applied; ?>
                            </select>
                    </td>
                    </tr>
                    <tr>
                        <td>
                            Specialization
                        </td>
                    <td>
                            <select class="js-example-basic-multiple subject-select" name="data[11][1][specialized][]" multiple="multiple" disabled>
                                <?php echo $specialized; ?>
                            </select>
                        </td>
                        <td>
                            <select class="js-example-basic-multiple subject-select" name="data[11][2][specialized][]" multiple="multiple" disabled>
                                <?php echo $specialized; ?>
                            </select>
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class='row card bg-white h-auto text-start mx-auto mt-5 overflow-auto'>
            <table class="table table-striped table-bordered">
                <col width="10%">
                <col width="45%">
                <col width="45%">
                <thead class="text-center bg-light">
                    <tr>
                        <td rowspan="2"></td>
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
                            <select class="js-example-basic-multiple subject-select" name="data[12][1][core][]" multiple="multiple" disabled>
                                <?php echo $core; ?>
                            </select>
                        </td>
                        <td>
                            <select class="js-example-basic-multiple subject-select" name="data[12][2][core][]" multiple="multiple" disabled>
                                <?php echo $core; ?>
                            </select>
                    </td>
                    </tr>
                    <tr>
                        <td>
                            Contextualized
                        </td>
                    <td>
                            <select class="js-example-basic-multiple subject-select" name="data[12][1][applied][]" multiple="multiple" disabled>
                                <?php echo $applied; ?>
                            </select>
                    </td>
                    <td>
                            <select class="js-example-basic-multiple subject-select" name="data[12][2][applied][]" multiple="multiple" disabled>
                                <?php echo $applied; ?>
                            </select>
                    </td>
                    </tr>
                    <tr>
                        <td>
                            Specialization
                        </td>
                    <td>
                            <select class="js-example-basic-multiple subject-select" name="data[12][1][specialized][]" multiple="multiple" disabled>
                                <?php echo $specialized; ?>
                            </select>
                    </td>
                    <td>
                            <select class="js-example-basic-multiple subject-select" name="data[12][2][specialized][]" multiple="multiple" disabled>
                                <?php echo $specialized; ?>
                                
                            </select>
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</section>
<script>
    let schedule = <?php echo json_encode($subjectsData['schedule']); ?>;
</script>
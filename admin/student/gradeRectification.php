<?php
require_once("../class/Faculty.php");
$faculty = new FacultyModule();
$id = $_SESSION['id'];
$sy_id = $_SESSION['sy_id'];
$teacher_id = (int) $_SESSION['id'];
$advisory = $faculty->listSectionOption();//dapat my sy
// echo json_encode($advisory);
// echo ("grade Rectification");
$sub_classes = $faculty->getHandled_sub_classes();
$adv_opn = '';
$sub_class_opn = '';

$adv_table_display = 'd-none';
$sub_table_display = '';

$schoolYearInfo = $faculty->getSchoolYearInfo(9); //to be removed pag maayos ung sa session
$sem = $schoolYearInfo['sem'] == '1' ? 'First' : 'Second';
$grading = $schoolYearInfo['grading'] == '1' ? 'First' : 'Second';
$qtrs = $schoolYearInfo['sem'] == '1' ? ['1st', '2nd']  : ['3rd', '4th'];

$adv_count_is_empty = !(is_null($advisory));
if ($adv_count_is_empty) {
    $adv_table_display = '';
    // $sub_table_display = 'd-none';
    // $section_code = $advisory['section_code'];
    // $section_name = $advisory;

    $adv_opn .= "<optgroup label='SECTION'>";

    foreach ($advisory as $advsr) {
        $name = $advsr['section_name'];
        $code = $advsr['section_code'];
        $adv_name = $advsr['adviser_name'];
        $adv_opn .= "<option value='$name' title='$code' "
        . "data-class-type='advisory' "
        . "data-url='getAction.php?data=grdAdvisor&section={$code}' "
        . "data-name='$code'>$name [$adv_name]</option>";
    }
    $adv_opn .= "</optgroup>";
}

if (count($sub_classes) != 0) {
    $sub_class_opn .= "<optgroup label='SUBJECT CLASS'>";
    foreach ($sub_classes as $sub_class) {
        $section_code = $sub_class->get_sub_class_code();
        $section_name = $sub_class->get_section_name();
        $sub_code = $sub_class->get_sub_code();
        $sub_name = $sub_class->get_sub_name();
        $sub_class_opn .= "<option value='$sub_code' title='$sub_code' "
            . "data-class-type='sub-class' "
            . "data-url='getAction.php?data=classGrades&sy_id={$sy_id}&id=admin&sub_class_code={$section_code}' "
            . "data-name='$sub_code'>$section_name [$sub_name]</option>";
    }
    $sub_class_opn .= "</optgroup>";
} else {
    if ($adv_count_is_empty) {
        $adv_table_display = '';
        $sub_table_display = 'd-none';
    }
}

?>
<!DOCTYPE html>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Grade</a></li>
        </ol>
    </nav>
</header>
<div class="container mt-1 ms-0">
    <div class="card w-100 h-auto bg-light" style="min-height: 70vh !important;">
        <h5 class="fw-bold"><?php echo $sem ?> Semester & 2nd Quarter</h5>
        <div class="d-flex justify-content-between mb-1">
            <!-- SEARCH BAR -->
            <div class="flex-grow-1 me-3">
                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
            </div>
            <div class="w-25">
                <select class="form-select form-select-sm" id="classes">
                    <?php
                    echo $adv_opn;
                    echo $sub_class_opn;
                    ?>
                </select>
            </div>
            <div>
                <form method="post" action="action.php" class="ms-2">
                    <input class='hidden' id='export_code' name='code' value=''>
                    <input type="submit" name="export" value="Export">
                </form>
            </div>
            <div>
                <button type="button" class="btn btn-success ms-2 save"></i>Save</button>
            </div>
        </div>

        <form id='grades'>
            <table id="table" class="table-striped table-sm">
                <thead class='thead-dark'>
                    <tr>
                    <th scope='col' data-width="150" data-align="center" data-field="id">ID</th>
                    <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_1">1st Grade</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_2">2nd Grade</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_f">Final Grade</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="action_2">Action</th>

                    </tr>
                </thead>
            </table>
        </form>
    </div>
</div>


<div id="" class="modal fade grading-confirmation" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="modal-title">
                                            <h4 class="mb-0"> <span id='label'></span></h4>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <p id="modal-msg"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                                        <button class="btn close-btn btn-success" id="confirm">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
<script type="text/javascript">
    var type = 'grades';
    var user = 'admin';
</script>
<?php
require_once("sessionHandling.php");
include("../inc/head.html");
require_once("../class/Faculty.php");
$faculty = new FacultyModule();
$sub_classes = [];
$sy_id = $_SESSION['sy_id'];
$advisory = $faculty->getAdvisoryClass($sy_id);
$sub_classes = $faculty->getHandled_sub_classes($sy_id);
$adv_opn = '';
$sub_class_opn = '';
$adv_table_display = '';

$adv_count_is_empty = !(is_null($advisory));
if ($adv_count_is_empty) {
    $adv_table_display = '';
    $section_code = $advisory['section_code'];
    $section_name = $advisory['section_name'];

    $adv_opn = "<optgroup label='Advisory Class'>"
        . "<option value='$section_code' title='$section_code'  data-class-type='advisory' "
        . "data-url='getAction.php?data=student&section={$section_code}' "
        . "data-name='$section_name'>$section_name</option>"
        . "</optgroup>";
}

if (!(is_null($sub_classes))) {
    $sub_class_opn .= "<optgroup label='Subject Class'>";
    foreach ($sub_classes as $sub_class) {
        $section_code = $sub_class->get_sub_class_code();
        $section_name = $sub_class->get_section_name();
        $sub_code = $sub_class->get_sub_code();
        $sub_class_opn .= "<option value='$section_code' title='$sub_code' "
            . "data-class-type='sub-class' "
            . "data-url='getAction.php?data=student&sub_code={$sub_code}' "
            . "data-name='$section_name'>$section_name [$sub_code]</option>";
    }
    $sub_class_opn .= "</optgroup>";
} else {
    if ($adv_count_is_empty) {
        # no advisory nor subject class at this point
        $adv_table_display = '';
    }
}
$schoolYearInfo = $faculty->getSchoolYearInfo($sy_id);
$sem = $schoolYearInfo['sem'] == '1' ? 'First' : 'Second';
$grading = $_SESSION['current_quarter'] == '1' ? 'First' : 'Second';
$qtrs = $schoolYearInfo['sem'] == '1' ? ['1st', '2nd']  : ['3rd', '4th'];
?>

<title>Advisory Class | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>
<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <?php
                        if (isset($_GET['page']) && $_GET['page'] == 'values_grade' ) {
                            include_once("grade/valuesGrade.php");
                            $jsFilePath = "<script type='module' src='../js/faculty/class-grade.js'></script>";
                        }
//                        else
//                            if(isset($_GET['page']) && $_GET['page'] === 'report_card'){
//                            include_once("../admin/gradeReport.php");
//                        }
                            else {
                            include_once("grade/gradeAdvisory.php");
                            $jsFilePath = "<script type='module' src='../js/faculty/students.js'></script> ";
                        }
                        ?>
                        <!-- MODAL -->
                        <div id="deactivate-modal" class="modal fade" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="modal-title">
                                            <h4 class="mb-0">Confirmation</h4>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Deactivate <span id="question"></span><br>
                                        <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </small>
                                    </div>
                                    <div class="modal-footer">
                                        <form id="deactivate-form" action="action.php">
                                            <input type="hidden" name="action" value="deactivate" />
                                            <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                                            <input type="submit" form="deactivate-form" class="submit btn btn-danger" value="Deactivate">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="" class="modal fade grading-confirmation" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="modal-title">
                                            <h4 class="mb-0"><span id='stmt'></span><span id='label'></span></h4>
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

                        <div id="" class="modal fade promotion-confirmation" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="modal-title">
                                            <h4 class="mb-0">CONFIRMATION</span></h4>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <p id="modal-msg">You won't be able to undo this action once the student is promoted.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                                        <button class="btn close-btn btn-success" id="promote">Promote</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- MODAL END -->
                    </div>
                </div>
                <!-- FOOTER START -->
                <?php include_once("../inc/footer.html"); ?>
                <!-- FOOTER END -->
            </section>
        </section>
    </section>
    <!-- MAIN CONTENT END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0; right: 0;">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 9999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
    <!-- CONFIRM SIGNATORY MODAL -->
    <div class="modal fade" id="confirm-sig-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirm signatory</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="confirm-sig-form" action="" method="POST">
                        <div class="form-group needs-validation" novalidate>
                            <label for="teacher-name">Teacher Name</label>
                            <input id="teacher-name" type="text" name="teacher_name" class='form-control' placeholder="Enter teacher name" value="<?php echo $_SESSION['name']; ?>" required>

                            <label for="signatory-name">Signatory Name</label>
                            <input id="signatory-name" type="text" name="signatory_name" class='form-control' placeholder="Enter name" required>
                            <label for="position">Position</label>
                            <input id="position" type="text" name="position" class='form-control' placeholder="Enter position" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-dark btn close-btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="proceed" form="confirm-sig-form" class="submit btn btn-sm btn-primary">Proceed</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CONFIRM SIGNATORY MODAL END -->

    <!--BOOTSTRAP TABLE JS-->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script>let code = "<?php echo json_encode($advisory['section_code'] ?? NULL);?>";</script>
    <?php echo $jsFilePath; ?>;

</body>

</html>
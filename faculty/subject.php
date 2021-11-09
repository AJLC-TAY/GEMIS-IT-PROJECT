<?php 
require_once("sessionHandling.php");
include("../inc/head.html");

require_once("../class/Faculty.php");
$faculty = new FacultyModule();
$id = $_SESSION['id'];
// $sy_id = $_SESSION['sy_id'];
$sy_id = $_SESSION['sy_id'];
// echo($sy_id);
$teacher_id = (int) $_SESSION['id'];
// $advisory = $faculty->getAdvisoryClass($sy_id);
$sub_classes = $faculty->getHandled_sub_classes($id);
$adv_opn = '';
$sub_class_opn = '';

$adv_table_display = 'd-none';
$sub_table_display = '';

$schoolYearInfo = $faculty->getSchoolYearInfo(9); //to be removed pag maayos ung sa session
$sem = $schoolYearInfo['sem'] == '1' ? 'First' : 'Second';
$grading = $_SESSION['current_quarter'] == '1' ? 'First' : 'Second';
$qtrs = $schoolYearInfo['sem'] == '1' ? ['1st', '2nd']  : ['3rd', '4th'];

// $adv_count_is_empty = !(is_null($advisory));
// if ($adv_count_is_empty) {
//     $adv_table_display = '';
//     // $sub_table_display = 'd-none';
//     $section_code = $advisory['section_code'];
//     $section_name = $advisory['section_name'];

//     $adv_opn = "<optgroup label='Advisory Class'>"
//         . "<option value='$section_code' title='$section_code'  data-class-type='advisory' "
//         . "data-url='getAction.php?data=student&section={$section_code}' "
//         . "data-name='$section_name'>$section_name</option>"
//         . "</optgroup>";
// }

if (count($sub_classes) != 0) {
    $sub_class_opn .= "<optgroup label='Subject Class'>";
    foreach ($sub_classes as $sub_class) {
        $section_code = $sub_class->get_sub_class_code();
        $section_name = $sub_class->get_section_name();
        $sub_code = $sub_class->get_sub_code();
        $sub_name = $sub_class->get_sub_name();
        $sub_class_opn .= "<option value='$sub_code' title='$sub_code' "
            . "data-class-type='sub-class' "
            . "data-url='getAction.php?data=classGrades&sy_id={$sy_id}&id={$teacher_id}&sub_code={$sub_code}' "
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

<title>Subject Class | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>
<!DOCTYPE html>
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
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <!-- <header> -->
                                <!-- BREADCRUMB -->
                                <!-- <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Subjecct Class</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold" id='class'></h3>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary"><i class="bi bi-eye me-2"></i>Download Template</button>
                                    </div> -->
                            <!-- </header> -->
                            <!-- STUDENTS TABLE -->
                            <?php
                            // if (isset($_GET['values_grade'])){
                            //     include_once("grade/valuesGrade.php"); 
                            //     $jsFilePath = "<script type='text/javascript' src='../js/student/values-grade.js'></script>";
                            // } else {
                                include_once("grade/gradeStudents.php"); 
                                $jsFilePath = "<script type='module' src='../js/faculty/class-grade.js'></script>";
                            // }
                            ?>
                            
                        </div>
                    </div>
                </div>
                <!-- FOOTER START -->
                <?php include_once("../inc/footer.html"); ?>
                <!-- FOOTER END -->
            </section>
        </section>
    </section>
    <div id = "" class="modal fade grading-confirmation" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
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
                    <button class="btn close-btn btn-success" id = "confirm">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0; right: 0;">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 9999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->

    <!--BOOTSTRAP TABLE JS-->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script>
        var currentGrading = '<?php echo $grading; ?>'
    </script>
    <script type='module' src='../js/faculty/class-grade.js'></script>
    <?php echo $jsFilePath; ?>;
</body>

</html>
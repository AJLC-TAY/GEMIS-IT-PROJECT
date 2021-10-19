<?php 
require_once("sessionHandling.php");
include("../inc/head.html");

require_once("../class/Faculty.php");
$faculty = new FacultyModule();

$sub_classes = $faculty->getHandled_sub_classes($teacher_id);
$adv_opn = '';
$sub_class_opn = '';

$adv_table_display = 'd-none';
$sub_table_display = '';


$schoolYearInfo = $faculty->getSchoolYearInfo($sy_id); //to be removed pag maayos ung sa session
$sem = $schoolYearInfo['sem'] == '1' ? 'First' : 'Second';
$grading = $schoolYearInfo['grading'] == '1' ? 'First' : 'Second';
$qtrs = $schoolYearInfo['sem'] == '1' ? ['1st', '2nd']  : ['3rd', '4th'];


if (count($sub_classes) != 0) {
    $sub_class_opn .= "<optgroup label='Subject Class'>";
    foreach ($sub_classes as $sub_class) {
        $section_code = $sub_class->get_sub_class_code();
        $section_name = $sub_class->get_section_name();
        $sub_code = $sub_class->get_sub_code();
        $sub_class_opn .= "<option value='$section_code' title='$sub_code' "
            . "data-class-type='sub-class' "
            . "data-url='getAction.php?data=classGrades&sy_id={$sy_id}&id={$teacher_id}&class_code={$section_code}' "
            . "data-name='$section_name'>$section_name</option>";
    }
    $sub_class_opn .= "</optgroup>";
} else {
    if ($adv_count_is_empty) {
        $adv_table_display = '';
        $sub_table_display = 'd-none';
    }
}

?>

<title>Students | GEMIS</title>
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
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Grade</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold" id='class'></h3>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary"><i class="bi bi-eye me-2"></i>Download Template</button>
                                    </div>
                            </header>
                            <!-- STUDENTS TABLE -->

                            <div class="container mt-1 ms-0">
                                <div class="card w-100 h-auto bg-light" style="min-height: 70vh !important;">
                                    <h5 class="fw-bold"><?php echo $sem ?> Semester & <?php echo $grading ?> Quarter</h5>
                                    <div class="d-flex justify-content-between mb-1">
                                        <!-- SEARCH BAR -->
                                        <div class="flex-grow-1 me-3">
                                            <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                                        </div>
                                        <div class="w-25">
                                            <select class="form-select form-select-sm" id="classes">
                                                <?php
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
                                            <!-- <form method="post" action="action.php"><input type="submit" id='export' name="export" class="btn btn-secondary" value="EXPORT"></form> -->
                                            <!-- <button type="submit" class="btn btn-secondary export" >EXPORT</button>
                                            <button onclick="Export()" class="btn btn-secondary">EXPORT</button> -->
                                            <button type="button" class="btn btn-secondary ms-2"><i class="bi bi-box-arrow-down-left me-2"></i>Import</button>
                                            <button type="button" class="btn btn-success confirm">Submit</button>
                                        </div>
                                    </div>

                                    <form id='grades'>
                                        <table id="table" class="table-striped table-sm">
                                            <thead class='thead-dark'>
                                                <tr>
                                                    <th scope='col' data-width="150" data-align="center" data-field="stud_id"></th>
                                                    <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Student Name</th>
                                                    <th scope='col' data-width="100" data-align="center" data-sortable="true" contenteditable="true" data-field="grd_1"><?php echo $qtrs[0]; ?> Quarter</th>
                                                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_2"><?php echo $qtrs[1]; ?> Quarter</th>
                                                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_f">Final Grade</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- FOOTER START -->
                <?php include_once("../inc/footer.html"); ?>
                <!-- FOOTER END -->
            </section>
        </section>
    </section>
    <div class="modal fade grading-confirmation" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Are you sure you want to submit?</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <p class="modal-msg">Submitted grades are editable within the duration of the current quarter.</p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn close-btn btn-success submit">Submit</button>
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
        var currentGrading = '<?php echo $grading; ?>';
    </script>
    <script type='module' src='../js/faculty/class-grade.js'></script>
</body>

</html>
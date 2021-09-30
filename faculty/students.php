<?php include("../inc/head.html");

session_start();
$_SESSION['user_type'] = 'FA';
$_SESSION['id'] = 1;
$_SESSION['sy_id'] = 15;
$_SESSION['sy_desc'] = '2021 - 2022';
$_SESSION['enrollment'] = 0;

require_once ("../class/Faculty.php");
$faculty = new FacultyModule();
//$advisory = [];
//$sub_classes = [];
$advisory = $faculty->getAdvisoryClass($_SESSION['sy_id']);
$sub_classes = $faculty->getHandled_sub_classes($_SESSION['id']);
$adv_opn = '';
$sub_class_opn = '';

$adv_table_display = 'd-none';
$sub_table_display = '';

$adv_count_is_empty = count($advisory) != 0;
if ($adv_count_is_empty) {
    $adv_table_display = '';
    $sub_table_display = 'd-none';
    $section_code = $advisory['section_code'];
    $section_name = $advisory['section_name'];

    $adv_opn = "<optgroup label='Advisory Class'>"
        ."<option value='$section_code' title='$section_code'  data-class-type='advisory' "
        ."data-url='getAction.php?data=student&section={$section_code}' "
        ."data-name='$section_name'>$section_name</option>"
        ."</optgroup>";
}


if (count($sub_classes) != 0) {
    $sub_class_opn .= "<optgroup label='Subject Class'>";
    foreach ($sub_classes as $sub_class) {
        $section_code = $sub_class->get_sub_class_code();
        $section_name = $sub_class->get_section_name();
        $sub_code = $sub_class->get_sub_code();
        $sub_class_opn .= "<option value='$section_code' title='$sub_code' "
            . "data-class-type='sub-class' "
            . "data-url='getAction.php?data=student&sub_class_code={$section_code}' "
            . "data-name='$section_name'>$section_name [$sub_code]</option>";
    }
    $sub_class_opn .= "</optgroup>";
} else {
    if ($adv_count_is_empty) {
        # no advisory nor subject class at this point
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
                                        <li class="breadcrumb-item active">Students</li>
                                    </ol>
                                </nav>
                                <div class="row align-content-center">
                                    <div class="col-auto">
                                        <h3 class="fw-bold">Classes</h3>
                                    </div>
                                    <div class="col-auto" style="min-width: 250px !important;">
                                        <select name="" class="form-control form-control-sm mb-3 w-auto" id="classes">
                                            <?php
                                            echo $adv_opn;
                                            echo $sub_class_opn;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </header>
                            <!-- STUDENTS TABLE -->
                            <div class="container mt-1 w-75 ms-0">
                                <div class="card w-100 h-auto bg-light" style="min-height: 70vh !important;">
                                    <div class="d-flex justify-content-between mb-3">
                                        <!-- SEARCH BAR -->
                                        <div class="flex-grow-1 me-3">
                                            <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                                        </div>
                                        <div id='class' class="col-auto p-2 fw-bold">

                                        </div>
                                    </div>
                                    <table id="table" class="table-striped table-sm <?php echo $adv_table_display; ?>">
                                        <thead class='thead-dark'>
                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="150" data-align="center" data-field="id">ID</th>
                                                <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="sex">Sex</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_1">1st Grade</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_2">2nd Grade</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_f">Final Grade</th>
                                                <th scope='col' data-width="150" data-align="center" data-field="action">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
<!--                                    <table id="sub-table" class="table-striped table-sm --><?php //echo $sub_table_display; ?><!--">-->
<!--                                        <thead class='thead-dark'>-->
<!--                                        <tr>-->
<!--                                            <th data-checkbox="true"></th>-->
<!--                                            <th scope='col' data-width="200" data-align="center" data-field="id">ID</th>-->
<!--                                            <th scope='col' data-width="450" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>-->
<!--                                            <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="sex">Sex</th>-->
<!--                                            <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th>-->
<!--                                        </tr>-->
<!--                                        </thead>-->
<!--                                    </table>-->
                                </div>
                            </div>
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
                                                <input type="hidden" name="action" value="deactivate"/>
                                                <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                                                <input type="submit" form="deactivate-form" class="submit btn btn-danger" value="Deactivate">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- MODAL END -->

                        </div>
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

    <!--BOOTSTRAP TABLE JS-->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
<!--    --><?php //echo $js; ?>
    <script type='module' src='../js/faculty/students.js'></script>
</body>

</html>

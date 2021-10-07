<?php include("../inc/head.html");

// session_start();
$_SESSION['user_type'] = 'FA';
$_SESSION['id'] = 26;
$_SESSION['sy_id'] = 15;
$_SESSION['sy_desc'] = '2021 - 2022';
$_SESSION['enrollment'] = 0;


require_once ("../class/Faculty.php");
$faculty = new FacultyModule();
//$advisory = [];
//$sub_classes = [];
$sub_classes = $faculty->getHandled_sub_classes($_SESSION['id']);
$adv_opn = '';
$sub_class_opn = '';

$adv_table_display = 'd-none';
$sub_table_display = '';




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
    <!-- <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> -->
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
                                <div class="row align-content-center">
                                    <div class="col-auto">
                                        <h3 class="fw-bold" id ='class'></h3>
                                        <span>Semester & Grading</span>
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
                                        <div class="col-auto" style="min-width: 250px !important;">
                                            <select name="" class="form-control form-control-sm mb-3 w-auto" id="classes">
                                                <?php
                                                echo $sub_class_opn;
                                                ?>
                                            </select>
                                        </div>
                                    
                                    </div>

                                    <div class>
                                        <button type="button" class="btn btn-secondary">Template</button>
                                        <button type="button"  class="btn btn-secondary">Import</button>
                                        <button type="button" id = 'export' class="btn btn-secondary">Export</button>
                                        <button type="button" class="btn btn-success">SAVE</button>

                                    </div>
                                    
                                    
                                    <table id="table" class="table-striped table-sm ">
                                        <thead class='thead-dark'>
                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="150" data-align="center" data-field="lrn"></th>
                                                <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Student Name</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_1">1st Grade</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_2">2nd Grade</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_f">Final Grade</th>
                                                <th scope='col' data-width="150" data-align="center" data-field="action">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>

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
    <script type='module' src='../js/faculty/class-grade.js'></script>
</body>

</html>

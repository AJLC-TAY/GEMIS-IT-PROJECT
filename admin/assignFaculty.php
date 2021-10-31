<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
require_once("../class/Administration.php");
$admin = new Administration();
$assignFaculty = $admin->listFacultySubjects();
// echo ($assignFaculty);

$facultyassignlist = '';

foreach ($assignFaculty as $af) {
    // $sub_code = $af->get_sub_code();
    $sub_name = $af->get_sub_name();
    // $sub_type = $af->get_sub_type();
    $facultyassignlist .= "<li><label for='subject' class='col-form-label list-group-item'>$sub_name</label></li><br>";
}

?>
<title>Assign Faculty to Subject | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
    <!-- SPINNER 
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
     SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Assign Faculty</a></li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Assign Faculty to Subjects</h3>
                                </div>
                            </header>
                            <div class="container w-75">
                                <div class="card">
                                    <form action="action.php" method="POST>
                                        <div class=" form-group row">
                                        <div class="col-md-6">
                                            <div class="container">
                                                <h6 class=" fw-bold d-flex justify-content-center mb-2">SUBJECTS</h6>
                                                <div class="row list-group">
                                                    <ul>
                                                        <!-- <li> <label for="" class="col-form-label list-group-item">EAPP</label></li><br>
                                                        <li><label for="" class="col-form-label list-group-item">GEN MATH</label></li> -->
                                                        <?php echo $facultyassignlist ?>
                                                    </ul>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="container">
                                                <h6 class="fw-bold d-flex justify-content-center mb-2">FACULTY</h6>
                                                <div class="row">
                                                    <form id="assignFacultyToSub" action="action.php" method="POST">
                                                        <input type="hidden" name="action" value="listFacultySubjects" />
                                                        <select name="teacher_id" id="faculty-select" class="form-select form-select">
                                                        </select>
                                                    </form>
                                                    <!-- <select name="faculty_id" class="form-select form-select" id="assign-faculty">
                                                        <option value=""></option>
                                                    </select>
                                                    <select name="faculty_id" class="form-select">
                                                        <option value=""></option>
                                                    </select> -->
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </section>

            <!-- FOOTER -->
            <?php include_once("../inc/footer.html"); ?>
            <!-- FOOTER END -->
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
</body>
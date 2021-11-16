<?php
require_once("sessionHandling.php");

use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Size;

//require_once("sessionHandling.php");
include_once("../inc/head.html"); ?>
<title>Transcript of Record | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
<script src="../assets/js/html2pdf.bundle.min.js"></script>
<style>
    .doc {
        width: 8.5in !important;
        height: auto;
    }

    .template {
        width: 7.5in !important;
        font-family: Arial !important;
        font-size: 13px !important;
    }

    .template li {
        width: 100%;
        height: 9.98in !important;
        margin: 0.5in !important;
    }

    * {
        box-sizing: border-box;
    }

    /*Header*/
    .report-title {
        text-align: center;
        margin-top: 40px;
        margin-bottom: 40px;
    }

    .title {
        margin-bottom: 0;
    }

    .sub-title {
        margin-top: 0;
    }

    /*Content*/
    img {
        height: 100px;
        width: 100px;
    }

    td {
        border: 0.5px solid #C5C5C5;
    }

    .fsize {
        font-size: 15px;
    }

    .fsizes {
        font-size: 12px;
    }

    .perLine {
        line-height: 45px;
    }

    .parentLine {
        display: flex;
    }

    .subLine {
        min-width: 150px;
        line-height: 45px;
    }

    .subLine2 {
        min-width: 170px;
    }

    .subLine3 {
        min-width: 350px;
    }

    .subLine4 {
        min-width: 250px;
    }

    .ind {
        text-indent: 10px;
    }

    .par {
        text-indent: 50px;
        text-align: justify;
        font-size: 15px;
        word-wrap: break-word;
    }

    .right {
        float: right;
    }

    /*Footer*/
    .signatory-con {
        width: 50%;
        display: table-column;
    }
</style>
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
        <?php include_once('../inc/studentSideBar.php'); ?>
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
                                        <li class="breadcrumb-item active">Transcript of Records</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <h4><b>Transcript of Records</b></h4>
                                        <h3><?php echo $_SESSION['User'] ?></h3>
                                    </span>
                                    
                                </div>
                            </header>
                            <hr class='m-1'>
                            <?php
                            include "../class/Administration.php";
                            $admin = new Administration();
                            $report_id = 1;
                            // $report_id = $_GET['report_id'];
                            $stud_id = $_SESSION['id'];  // test

                            // $stud_id = $_GET['id'];
                            $filename = $stud_id . '_grade_report'; // 1111_grade_report
                            $grades = $admin->listGrade();
                            $userProfile = $admin->getProfile("ST");
                            $stud_id = $userProfile->get_stud_id();
                            $lrn = $userProfile->get_lrn();
                            $lastName = $userProfile->get_last_name();
                            $firstName = $userProfile->get_first_name();
                            $midName = $userProfile->get_middle_name();
                            $sex = $userProfile->get_sex();
                            $age = $userProfile->get_age();
                            $section = $userProfile->get_section();
                            // $teacherName = $_POST['teacher_name'];
                            $teacherName = 'Kesley Bautista Trinidad';
                            $grade = 12;
                            // $signatoryName = $_POST['signatory_name'];
                            $signatoryName = 'Whitney Houston';
                            $position = 'Secondary School Principal III';
                            // $position = $_POST['position'];
                            // $admittedIn = 'None';
                            // $eligible = '12';
                            // $date = date("F j, Y");
                            $trackStrand = $admin->getTrackStrand();
                            $attendance = $admin->getStudentAttendance(1);

                            function prepareGradeRecordsHTML($grade)
                            {
                                $row = '';
                                for ($x = 0; $x < sizeof($grade); $x++) {

                                    $row .= "<tr>
                                        <td>{$grade[$x]['sub_name']}</td>
                                        
                                        <td align='center'>{$grade[$x]['grade_f']}</td>
                                        </tr>";
                                }

                                return $row;
                            }

                            function renderSemesterGradeTable($semester_desc, $grades)
                            {
                                $grd = "
                                    <h6><b>$semester_desc</b></h6>
                                    <table class='table w-100 table-sm'>
                                        <col style='width: 65%;'>
                                        <col style='width: 10%;'>
                                        
                                        
                                        <thead class='text-center fw-bold'>
                                            <tr>
                                                <td rowspan='2' valign='middle' align='center'>Subjects</td>
                                                <td rowspan='2' valign='middle' align='center'>Semester Final Grade</td>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            " .
                                    prepareGradeRecordsHTML($grades['core']) .
                                    prepareGradeRecordsHTML($grades['applied']);

                                if (array_key_exists('specialized', $grades)) {
                                    prepareGradeRecordsHTML($grades['specialized']);
                                }
                                $grd .= " </tbody>
                                    </table>";

                                echo $grd;
                            }
                            ?>
                            <div class="d-flex justify-content-center">
                                <div class="doc bg-white ms-2 mt-3 p-0 shadow overflow-auto">
                                    <ul class="template p-3">
                                        <li class="p-0 mb-0 mx-auto">
                                            <h5 class="text-center"><b>Transcript of Record</b></h5>
                                            <?php

                                            renderSemesterGradeTable('FIRST SEMESTER', $grades['1']);
                                            renderSemesterGradeTable('SECOND SEMESTER', $grades['2']);
                                            ?>
                                            <br>
                                        </li>
                                    </ul>
                                    <!-- <div class="html2pdf__page-break"><hr class='m-0'></div> -->
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                </div>
            </section>
            <!-- MAIN CONTENT END-->
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

    <!-- BOOTSTRAP TABLE JS -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script>
        $(function() {
            preload("#transcript");
            hideSpinner();
        });
    </script>
</body>

</html>
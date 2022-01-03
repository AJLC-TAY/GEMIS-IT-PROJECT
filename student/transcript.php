<?php
include "../class/Student.php";
require_once("sessionHandling.php");
include_once("../inc/head.html");
include_once('../inc/studentSideBar.php');
$student = new StudentModule();
session_start();
$user_type = $_SESSION['user_type'];
$curr_sem = $_SESSION['current_semester'];
$sy_id = $_SESSION['sy_id'];
// if ($_SESSION['user_type'] = !'ST') {
//     $signatory_name = is_null($_GET['signatory']) ? "" : strtoupper($_GET['signatory']);
// }
// $position = $_GET['position'] ?? "";
// $teacherName = '';
// $school_year = '';

if ($user_type != 'FA') {
    $breadcrumb = '';
}

$stud_id = $_SESSION['id'];
$userProfile = $student->getProfile("ST");
$stud_id = $userProfile->get_stud_id();
$lrn = $userProfile->get_lrn();
$lastName = $userProfile->get_last_name();
$firstName = $userProfile->get_first_name();
$midName = $userProfile->get_middle_name();
$sex = $userProfile->get_sex();
$age = $userProfile->get_age();
$section = $userProfile->get_section();
$grade_level = $userProfile->get_yrlvl();

$strand = mysqli_fetch_row($student->query("SELECT prog_code FROM enrollment WHERE stud_id = '$stud_id' AND sy_id = '$sy_id';"))[0];
$report_id = mysqli_fetch_row($student->query("SELECT report_id FROM gradereport WHERE stud_id =  '$stud_id' AND sy_id='{$_SESSION['sy_id']}';"))[0];
$grades = $student->listStudentGradesForReport($stud_id, $report_id, $grade_level, $strand);
$general_averages = $student->getGeneralAverages($stud_id, $grade_level);


?>
<title>Student | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
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
        <!-- MAIN CONTENT START -->
        <?php  ?>
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="row mt ps-3">


                        <?php
                        function prepareGradeRecordsHTML($grade)
                        {
                            $row = '';
                            foreach ($grade as $gradeInfo) {
                                $row .= "<tr>
            <td>{$gradeInfo['sub_name']}</td>
            <td align='center'>{$gradeInfo['grade_1']}</td>
            <td align='center'>{$gradeInfo['grade_2']}</td>
            <td align='center'>{$gradeInfo['grade_f']}</td>
            </tr>";
                            }
                            return $row;
                        }

                        function renderSemesterGradeTable($semester_desc, $grades, $general_average)
                        {
                            $grd =  "
                            <h6 class='fw-bolder mb-0' style='font-size: 14px;'>$semester_desc</h6>
                            <table class='table w-100 table-sm' style='font-size: 12px;'>
                                <col style='width: 65%;'>
                                <col style='width: 10%;'>
                                <col style='width: 10%;'>
                                <col style='width: 15%;'>
                                
                                <thead class='text-center bg-light'>
                                    <tr>
                                        <td rowspan='2' valign='middle' align='center'>SUBJECTS</td>
                                        <td colspan='2' align='center'>QUARTER</td>
                                        <td rowspan='2' valign='middle' align='center'>SEMESTER FINAL GRADE</td>
                                    </tr>
                                    <tr>
                                        <td align='center'>1</td>
                                        <td align='center'>2</td>
                                    </tr>
                                </thead>
                                <tbody>"
                            . (isset($grades['core']) ?
                                "<tr class='bg-light'>
                                        <td colspan='4'class='fw-bold'><b>Core Subjects</td>
                                    </tr>" .
                            prepareGradeRecordsHTML($grades['core']) : "")
                            . (isset($grades['applied']) ? "<tr class='bg-light'>
                                        <td colspan='4' class='fw-bold'>Applied Subjects</td>
                                    </tr>" . prepareGradeRecordsHTML($grades['applied']) : "")
                            . (isset($grades['specialized']) ?   "<tr class='bg-light'>
                                        <td colspan='4' class='fw-bold'>Specialized</td>
                                        </tr>" . prepareGradeRecordsHTML($grades['specialized']) : "" );
                        $grd .= "<tr>
                                    <td colspan='3' class='border-0 fst-italic text-end pe-3'>General Average for the Semester</td>
                                        <td class='bg-white text-center'>$general_average</td>
                                    </tr>
                                </tbody>
                            </table>";
                    
                        echo $grd;
                        }
                        ?>



                        <div class="d-flex justify-content-center p-19 m-3">
                            <div class="doc bg-white ms-2 mt-3 p-0 shadow overflow-auto">
                                <ul class="template p-0 w-100">
                                    <li class="p-0 mb-0 mx-auto p-3">

                                        <?php
                                        if ($grade_level == 12) {
                                            $x = (int)$grade_level - 1;
                                        } else {
                                            $x = (int)$grade_level;
                                        }
                                        for ($ctr = $x; $ctr < ($grade_level + 1); $ctr++) {
                                            echo "<p class='fw-bolder mb-0' style='font-size: 20px;'>GRADE $ctr</p>
                                                                    <div class='row'> <div class='col-6'>";
                                            renderSemesterGradeTable('FIRST SEMESTER', $grades['1'], $general_averages['first']);
                                            echo "</div>";
                                            echo "<div class='col-6'>";
                                            renderSemesterGradeTable('SECOND SEMESTER', $grades['2'],  $general_averages['second']);
                                            echo "</div> </div>";
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FOOTER START -->
                <?php include_once("../inc/footer.html"); ?>
                <!-- FOOTER END -->
            </section>
        </section>
        <!-- MAIN CONTENT END -->
        <!-- TOAST -->
        <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
            <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 999; bottom: 20px; right: 25px;"></div>
        </div>
        <!-- TOAST END -->

        <script src='../assets/js/bootstrap-table.min.js'></script>
        <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
        <script src='../assets/js/bootstrap.bundle.min.js'></script>
        <script src="../js/common-custom.js"></script>
        <script>
            $(function() {
                preload("#transcript");
                hideSpinner();
            });
        </script>

</body>

</html>
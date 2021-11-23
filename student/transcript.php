<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
include_once('../inc/studentSideBar.php');
include_once("../class/Student.php");

$student = new StudentModule();
$user_type = $_SESSION['user_type'];
$curr_sem = $_SESSION['current_semester'];
$sy_id = $_SESSION['sy_id'];
const approve = 'WHITNEY A. DAWAYEN';
const approve_pos = 'Secondary School Principal III';
$teacherName = '';
$school_year = '';


//$report_id = 37;

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

$result = ($student->query("SELECT prog_code, enrolled_in, sy_id FROM enrollment WHERE stud_id = '$stud_id'"));
$grades = [];
$general_averages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $report_id = mysqli_fetch_row($student->query("SELECT report_id FROM gradereport WHERE stud_id =  '$stud_id' AND sy_id='{$row['sy_id']}';"))[0];
    $grades[$row['enrolled_in']][] = $student->listStudentGradesForReport($report_id, $row['enrolled_in'], $row['prog_code']);
    $general_averages[$row['enrolled_in']][] = $student->getGeneralAverages($report_id);
}

// echo json_encode($grades);
// echo json_encode($general_averages);

// $admittedIn = 'None';
// $eligible = '12';
// $date = date("F j, Y");
// $trackStrand = ($student->getTrackStrand($stud_id))[1];

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
                    <div class="col-lg-12">
                        <div class="row mt ps-3">

                            <?php
                            ?>
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label='breadcrumb'>
                                    <ol class='breadcrumb'>
                                        
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <h4><b>Transcript of Records</b></h4>
                                        <?php //echo $school_year; 
                                        ?>
                                        <?php if ($user_type == 'ST') {
                                            echo "<h3>{$_SESSION['User']}</h3>";
                                        } else {
                                            echo "<h3>$lastName, $firstName $midName</h3>";
                                        } ?>
                                    </span>
                                    
                                </div>
                            </header>
                            <hr class='m-1'>
                            <?php
                            function prepareGradeRecordsHTML($grade)
                            {
                                $row = '';
                                foreach ($grade as $gradeInfo) {
                                    $row .= "<tr>
            <td>{$gradeInfo['sub_name']}</td>
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
            
            <col style='width: 15%;'>
            
            <thead class='text-center bg-light'>
                <tr>
                    <td rowspan='2' valign='middle' align='center'>SUBJECTS</td>
                    <td rowspan='2' valign='middle' align='center'>SEMESTER FINAL GRADE</td>
                </tr>
                
            </thead>
            <tbody>"
                                    . (isset($grades['core']) ?
                                        "<tr class='bg-light'>
                    <td colspan='2'>Core Subjects</td>
                </tr>" .
                                        prepareGradeRecordsHTML($grades['core']) : "")
                                    . (isset($grades['applied']) ? "<tr class='bg-light'>
                    <td colspan='2' class='fw-bold'>Applied Subjects</td>
                </tr>" . prepareGradeRecordsHTML($grades['applied']) : "")
                                    . (isset($grades['specialized']) ?   "<tr class='bg-light'>
                    <td colspan='2' class='fw-bold'>Specialized</td>
                    </tr>" . prepareGradeRecordsHTML($grades['specialized']) : "");


                                //    if ($_SESSION['user_type'] == 'ST') {

                                //    } else {

                                //        if (array_key_exists('specialized', $grades)) {
                                //            prepareGradeRecordsHTML($grades['specialized']);
                                //        }
                                //        else {
                                //            for ($x = 0; $x < 5; $x++) {
                                //                $grd .= "<tr height=26>
                                //                                    <td> </td>
                                //                                    <td align='center'> </td>
                                //                                    <td align='center'> </td>
                                //                                    <td align='center'> </td>
                                //                                    </tr>";
                                //            }
                                //        }
                                //    }
                                //    if (array_key_exists('specialized', $grades)) {
                                //        $grd .= "<tr class='bg-light'>
                                //                    <td colspan='4' class='fw-bold'>Specialized</td>
                                //                    </tr>";
                                //        prepareGradeRecordsHTML($grades['specialized']);
                                //    }

                                $grd .= "<tr>
                <td colspan='' class='border-0 fst-italic text-end pe-3'>General Average for the Semester</td>
                    <td class='bg-white text-center'>$general_average</td>
                </tr>
            </tbody>
        </table>";

                                echo $grd;
                            }

                            // function prepareStudentAttendanceHTML($label, $att)
                            // {
                            //     $total = 0;
                            //     foreach ($att[$label] as $month => $days) {
                            //         $total += $days;
                            //         echo "<td align='center'>$days</td>";
                            //     }
                            //     echo "<td align='center'>$total</td>";
                            // }


                            $observed_values_desc = [
                                "Makadiyos" => [
                                    "Expresses one’s spiritual beliefs while respecting the spirtiual beliefs of others.",
                                    "Shows  adherence to ethical principles by uphoalding truth in all undertakings."
                                ],
                                "Makatao"  =>  [
                                    "In sensitive to individual, social, and cultural differences.",
                                    "Demonstrates contributions towards solidarity"
                                ],
                                "Makakalikasan" => [
                                    "Cares for environment and utilizes resources wisely, judiciously and economically.",
                                ],
                                "Makabansa"  => [
                                    "Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino citizen.",
                                    "Demonstrate appropriate behavior in carrying out activities in school, community and country."
                                ]
                            ];

                            // $observed_values = $admin->listValuesReport();
                            // print_r($attendance);
                            ?>



                            <div class="d-flex justify-content-center p-3">
                                <div class="doc bg-white ms-2 mt-3 p-0 shadow overflow-auto">
                                    <ul class="template p-0 w-100">


                                        <li class="p-0 mb-0 mx-auto p-3">
                                            
                                                <?php
                                                if ($grade_level == 12) {
                                                    $x = (int)$grade_level - 1;
                                                } else {
                                                    $x = (int)$grade_level;
                                                }
                                                
                                                        for ($ctr = $x ; $ctr < ($grade_level + 1); $ctr++ ){
                                                            echo "<p class='fw-bolder mb-0' style='font-size: 20px;'>GRADE $ctr</p>
                                                                    <div class='row'> <div class='col-6'>";
                                                            renderSemesterGradeTable('FIRST SEMESTER', $grades[$ctr][0][1], $general_averages[$ctr][0][0]);
                                                            echo "</div>";
                                                            echo "<div class='col-6'>";
                                                            renderSemesterGradeTable('SECOND SEMESTER', $grades[$ctr][0][2], $general_averages[$ctr][0][1]);
                                                            echo "</div> </div>";
                                                            
                                                            
                                                        }
                                                        
                                                ?>
                                            
                                        </li>



                                    </ul>
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
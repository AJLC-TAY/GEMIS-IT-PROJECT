<?php
$user_type = $_SESSION['user_type'];
$curr_sem = $_SESSION['current_semester'];
if ($user_type != 'ST') {
    // $teacherName = $_POST['teacher_name'];
    $teacherName = 'Kesley Bautista Trinidad';
    $grade = 12;
    // $signatoryName = $_POST['signatory_name'];
    $signatoryName = 'Whitney Houston';
    $position = 'Secondary School Principal III';
    // $position = $_POST['position'];
    $school_year = '9';
    $breadcrumb = '';
}
include "../class/Administration.php";
$admin = new Administration();
$report_id = 1;
// $report_id = $_GET['report_id'];
$stud_id = '110001';  // test

// $stud_id = $_GET['id'];


$grades = $admin->listGrade();

$student = $admin->getProfile("ST");
$stud_id = $student->get_stud_id();
$lrn = $student->get_lrn();
$lastName = $student->get_last_name();
$firstName = $student->get_first_name();
$midName = $student->get_middle_name();
$sex = $student->get_sex();
$age = $student->get_age();
$section = $student->get_section();

// $admittedIn = 'None';
// $eligible = '12';
// $date = date("F j, Y");
$trackStrand = $admin->getTrackStrand();
$attendance = $admin->getStudentAttendance(1);
$filename = $lastName .', '. mb_substr($firstName, 0, 1, "UTF-8"). '_grade_report'; // 1111_grade_report
?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <?php
            if ($user_type != 'ST') {
                echo "
                <li class='breadcrumb-item'><a href='index.php'>Home</a></li>
                <li class='breadcrumb-item'><a href='index.php'>Student</a></li>
                $breadcrumb
                <li class='breadcrumb-item active'>Grade Report</a></li>";
            }
            ?>
        </ol>
    </nav>
    <div class="d-flex justify-content-between">
        <span>
            <h4><b>Grade Report</b></h4>
            <?php //echo $school_year; ?>
            <?php if ($user_type == 'ST') {
                echo "<h3>{$_SESSION['User']}</h3>";
            } else {
                echo "<h3>$lastName, $firstName $midName</h3>";
            } ?>
        </span>
        <?php if ($user_type != 'ST') { ?>
            <div class="mt-4">
                <button onclick='generatePDF(`<?php echo $filename; ?>`)' class=" btn btn-primary"><i class="bi bi-download me-2"></i>Download</button>
            </div>
        <?php } ?>
    </div>
</header>
<hr class='m-1'>
<?php

// echo json_encode($grades);

function prepareGradeRecordsHTML($grade)
{
    $row = '';
    for ($x = 0; $x < sizeof($grade); $x++) {
        $row .= "<tr>
            <td>{$grade[$x]['sub_name']}</td>
            <td align='center'>{$grade[$x]['grade_1']}</td>
            <td align='center'>{$grade[$x]['grade_2']}</td>
            <td align='center'>{$grade[$x]['grade_f']}</td>
            </tr>";
    }

    return $row;
}

function renderSemesterGradeTable($semester_desc, $grades)
{
    $grd =  "
        <h6><b>$semester_desc</b></h6>
        <table class='table w-100 table-sm'>
            <col style='width: 65%;'>
            <col style='width: 10%;'>
            <col style='width: 10%;'>
            <col style='width: 15%;'>
            
            <thead class='text-center fw-bold'>
                <tr>
                    <td rowspan='2' valign='middle' align='center'>Subjects</td>
                    <td colspan='2' align='center'>Quarter</td>
                    <td rowspan='2' valign='middle' align='center'>Semester Final Grade</td>
                </tr>
                <tr>
                    <td align='center'>1</td>
                    <td align='center'>2</td>
                </tr>
            </thead>
            <tbody>
                <tr class='bg-light'>
                    <td colspan='4'>Core Subjects</td>
                </tr>" .

        prepareGradeRecordsHTML($grades['core'])
        . "<tr class='bg-light'>
                    <td colspan='4'>Applied Subjects</td>
                </tr>" .
        prepareGradeRecordsHTML($grades['applied']);


    if ($_SESSION['user_type'] == 'ST') {
        if (array_key_exists('specialized', $grades)) {
            prepareGradeRecordsHTML($grades['specialized']);
        }
    } else {
        $grd .= "<tr class='bg-light'>
                    <td colspan='4'>Specialized Subjects</td>
                    </tr>";
        if (array_key_exists('specialized', $grades)) {
            prepareGradeRecordsHTML($grades['specialized']);
        } else {
            for ($x = 0; $x < 5; $x++) {
                $grd .= "<tr height=26>
                                    <td> </td>
                                    <td align='center'> </td>
                                    <td align='center'> </td>
                                    <td align='center'> </td>
                                    </tr>";
            }
        }
    }


    $grd .= "<tr class='bg-light fw-bold'>
                <td colspan='3'>General Average for the Semester:</td>
                    <td class='bg-white'></td>
                </tr>
            </tbody>
        </table>";

    echo $grd;
}

function prepareStudentAttendanceHTML($label, $att)
{
    $total = 0;
    foreach ($att[$label] as $month => $days) {
        $total += $days;
        echo "<td align='center'>$days</td>";
    }
    echo "<td align='center'>$total</td>";
}


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

$observed_values = $admin->listValuesReport();
// print_r($attendance);
function otherinfo()
{
    echo "
<h5 class='text-center'><b>PARENT / GUARDIAN'S SIGNATURE</b></h5>
        <div class='perLine text-center fsize'>FIRST QUARTER: ______________________________</div>
        <div class='perLine text-center fsize'>SECOND QUARTER: ______________________________</div>
        <div class='perLine text-center fsize'>THIRD QUARTER: ______________________________</div>
        <div class='perLine text-center fsize'>FOURTH QUARTER: ______________________________</div>

        <br><br>
        <h6 class='text-center'><b>CERTIFICATE OF TRANSFER</b></h6>
        <br>
        <div class='parentLine fsize'>
            <div class='subLine'>Admitted to Grade: </div>
            <div class='subLine text-center'>________________</div>
            <div class='subLine'>Section: </div>
            <div class='subLine text-center'>________________</div>
        </div>
        <div class='parentLine fsize'>
            <div class='subLine'>Eligible for Admission to Grade: </div>
            <div class='subLine'>________________</div>
        </div>
        <br>
        <div class='fsize left'><b> Approved: </b></div>
        <br>
        <div class='parentLine fsize text-center'>
            <div class='subLine3'>___________________</div>
            <div class='subLine3'>___________________</div>
        </div>
        <div class='parentLine fsize text-center'>
            <div class='subLine3'>___________________</div>
            <div class='subLine3'>Teacher</div>
        </div>
        <br><br><br>
        <h6 class='text-center'><b>CANCELLATION OF ELIGIBILITY TO TRANSFER</b></h6>
        <div class='parentLine fsize'>
            <div class='subLine'>Admitted in:</div>
            <div class='subLine'>___________________</div>
        </div>
        <div class='parentLine fsize'>
            <div class='subLine'>Date:</div>
            <div class='subLine'>___________________</div>
        </div>";
}

function logoToSignatory($lastName, $firstName, $midName, $age, $sex, $grade, $section, $lrn, $school_year, $trackStrand, $teacherName, $signatoryName, $position)
{


    echo "
<li class='p-0 mb-0 mx-auto'>
        <p>School Form 9 - SHS</p>
        <div class='row p-0 mx-1'>
            <div class='col-3 p-0'>
                <img src='../assets/deped_logo.png' alt='DEPED Logo' title='DEPED Logo'>
            </div>
            <div class='col-6 p-0 text-center'>
                <p>
                    Republic of the Philippines<br>
                    Department of Education<br>
                    Cordillera Administrative Region<br>
                    Baguio City Schools Division<br>
                    PINES CITY NATIONAL HIGH SCHOOL<br>
                    [SENIOR HIGH SCHOOL - Lucban Campus]<br>
                    Magsaysay Ave., Baguio City
                </p>
            </div>
            <div class='col-3 p-0' style='text-align: right;'>
                <img src='../assets/school_logo.jpg' alt='PCNSH Logo' title='PCNSH Logo'>
            </div>
        </div>
        <h5 class='text-center'><b>LEARNER'S PROGRESS REPORT CARD</b></h5>
        <br><br><br>
        <div class='parentLine fsize'>
            <div class='subLine2'>Name: </div><br>
            <div class='subLine2 ind'>$lastName</div><br>
            <div class='subLine2 ind'>$firstName</div><br>
            <div class='subLine2 ind'>$midName</div><br>
        </div>
        <div class='parentLine fsizes'>
            <div class='subLine2 ind'> </div><br>
            <div class='subLine2 ind'>(Last Name)</div>
            <div class='subLine2 ind'>(First Name)</div>
            <div class='subLine2 ind'>(Middle Name)</div>
        </div>
        <br>
        <div class='parentLine fsize'>
            <div class='subLine'>Age: </div>
            <div class='subLine'>$age</div>
            <div class='subLine'>Gender: </div>
            <div class='subLine'>$sex</div>
        </div>
        <div class='parentLine  fsize'>
            <div class='subLine'>Grade: </div>
            <div class='subLine'>$grade</div>
            <div class='subLine'>Section: </div>
            <div class='subLine'>$section</div>
        </div>
        <div class='perLine text-center fsize'>LRN: $lrn</div> 
        <div class='parentLine fsize'>
            <div class='subLine'>School Year: </div>
            <div class='subLine'>$school_year</div>
        </div>
        <div class='parentLine fsize'>
            <div class='subLine'>Track/Strand: </div>
            <div class='subLine'>$trackStrand[0]</div>
        </div>
        <br>
        <div class='parag'>
            <h7 class='fsize'>Dear Parent,<br><br></h7>
            <p class='par'>
                This report card shows the ability and progress your child has made in the different learning areas as well as his/her core values.
            </p>
            <p class='par'>
                The school welcomes you should you desire to know more about your child's progress.
            </p>
        </div>
        <br><br><br>
        <div class='fsize right'>
            <div> $teacherName</div>
            <div> Class Adviser </div>
        </div>
        <br><br><br>
        <div class='fsize left'>
            <div>$signatoryName</div>
            <div> $position</div>
        </div>
  </li>  
";
}
function attendance($attendance, $lastName, $firstName, $midName, $age, $sex, $grade, $section, $lrn, $school_year, $trackStrand, $teacherName, $signatoryName, $position)
{
    logoToSignatory($lastName, $firstName, $midName, $age, $sex, $grade, $section, $lrn, $school_year, $trackStrand, $teacherName, $signatoryName, $position);

    echo "
    <li class='p-0 mb-0 mx-auto'>
        <h5 class='text-center'><b>REPORT ON ATTENDANCE</b></h5><br>
        <table class='table-bordered table w-100'>
            <col style='width: 25%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 7%;'>
            <col style='width: 15%;'>

            <thead class='text-center fw-bold'>
                <tr>
                    <td></td>";


    foreach (array_keys($attendance['no_of_days']) as $month_key) {
        echo '<td>' . ucwords(substr($month_key, 0, 3)) . '</td>';
    }


    echo "<td>Total</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class='text-center'>No. of school days</td>";
    prepareStudentAttendanceHTML('no_of_days', $attendance);

    echo "</tr>
                <tr>
                    <td class='text-center'>No. of present</td>";

    prepareStudentAttendanceHTML('no_of_present', $attendance);

    echo "</tr>
                <tr>
                    <td class='text-center'>No. of absent</td>";

    prepareStudentAttendanceHTML('no_of_absent', $attendance);

    echo "</tr>
                <tr>
                    <td class='text-center'>No. of tardy</td>";

    prepareStudentAttendanceHTML('no_of_tardy', $attendance);

    echo " </tr>
            </tbody>
        </table> ";

    otherinfo();
}
?>

    <div class="d-flex justify-content-center">
        <div class="doc bg-white ms-2 mt-3 p-0 shadow overflow-auto">
            <ul class="template p-0 w-100">
                <?php if ($user_type != "ST") {
                    attendance($attendance, $lastName, $firstName, $midName, $age, $sex, $grade, $section, $lrn, $school_year, $trackStrand, $teacherName, $signatoryName, $position);
                } ?>

<!--                </li>-->

<!--                <hr class='m-0'>-->
                <li class="p-0 mb-0 mx-auto">
                    <h5 class="text-center"><b>Report on Learning Progress and Achievement</b></h5>
                    <?php
                    if ($user_type == "ST") {
                        renderSemesterGradeTable('1st Semester', $grades[$curr_sem]);
                    } else {
                        renderSemesterGradeTable('1st Semester', $grades['1']);
                        renderSemesterGradeTable('2nd Semester', $grades['2']);
                    }

                    ?>
                    <br>
                </li>
<!--                <hr class='m-0'>-->
                <li class="p-0 mb-0 mx-auto">

                    <h6 class="text-center"><b>Report on Learner's Observed Values</b></h6>
                    <!--
                <div class="d-flex justify-content-between">
                    <p><b>AO</b> - Always Observed</p>
                    <p><b>SO</b> - Sometimes Observed</p>
                    <p><b>RO</b> - Rarely Observed</p>
                    <p><b>NO</b> - Not Observed</p>
                </div> -->

                    <table class="table-bordered table w-100">
                        <col style='width: 20%;'>
                        <col style='width: 40%;'>
                        <col style='width: 10%;'>
                        <col style='width: 10%;'>
                        <col style='width: 10%;'>
                        <col style='width: 10%;'>

                        <thead class='text-center fw-bold'>
                            <tr>
                                <td>Core Values</td>
                                <td>Behavior Statements</td>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // echo json_encode($observed_values);
                            // echo "<tr>";
                            foreach ($observed_values as $id => $values) { //id = MakaDiyos , values = [ 'sfdsdfsdfdsf => 1 => 'AO',2 => 'AO,],
                                echo "<td rowspan='" . count($values) . "'><b>$id</b></td>";
                                foreach ($values as $bh_staments => $bh_qtr) { // $bh_staments = sfdsdfsdfdsf
                                    foreach ($bh_qtr as $bh_staments => $marking) {
                                        echo "<td>$bh_staments</td>";

                                        $first = $marking[0] = ""?$marking[0]:" ";
                                        $second = $marking[1] = ""?$marking[1]:" ";;
                                        $third = $marking[2] = ""?$marking[2]:" ";
                                        $fourth = $marking[3] = ""?$marking[3]:" ";
                                        echo "<td>$first</td>";
                                        echo "<td>$second</td>";
                                        echo "<td>$third</td>";
                                        echo "<td>$fourth</td>";
                                        echo "</tr>";
                                    }
                                }

                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="container">
                        <h6><b>Observed Values</b></h6>
                        <div class="row g-5">
                            <div class="col-auto">LEGEND:</div>
                            <div class="col-auto"><b>Marking</b>
                                <p>AO<br>SO<br>RO<br>NO</p>
                            </div>
                            <div class="col-auto">
                                <b>Non-numerical Rating</b>
                                <p>Always Observed<br>Sometimes Observed<br>Rarely Observed<br>Not Observed</p>
                            </div>
                        </div>
                        <h6><b>Learners Progress and Achievement</b></h6>
                        <div class="row g-5">
                            <div class="col-auto">
                                <b>Descriptors</b>
                                <p>Outstanding<br>Very Satisfactory<br>Satisfactory<br>Fairly Satisfactory<br>Did Not Meet Expectations</p>
                            </div>
                            <div class="col-auto">
                                <b>Grade Scale</b>
                                <p>90 - 100<br>85 - 89<br>80 - 84<br>75 - 79<br>Below 75</p>
                            </div>
                            <div class="col-auto">
                                <b>Remarks</b>
                                <p>Passed<br>Passed<br>Passed<br>Passed<br>Failed</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
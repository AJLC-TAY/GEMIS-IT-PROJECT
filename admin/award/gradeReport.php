<?php
$user_type = $_SESSION['user_type'];
$curr_sem = $_SESSION['current_semester'];
const approve = 'WHITNEY A. DAWAYEN';
const approve_pos = 'Secondary School Principal III';

const adviser = 'ALVIN JOHN L. CUTAY';
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

$userProfile = $admin->getProfile("ST");
$stud_id = $userProfile->get_stud_id();
$lrn = $userProfile->get_lrn();
$lastName = $userProfile->get_last_name();
//$firstName = $userProfile->get_first_name();
$firstName = "Kimberlyn Faith";
$midName = $userProfile->get_middle_name();
$sex = $userProfile->get_sex();
$age = $userProfile->get_age();
$section = $userProfile->get_section();

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
                <button onclick='generatePDF(`<?php echo $filename; ?>`, `landscape`)' class=" btn btn-primary"><i class="bi bi-download me-2"></i>Download</button>
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
            <tbody>
                <tr class='bg-light'>
                    <td colspan='4'>Core Subjects</td>
                </tr>" .

        prepareGradeRecordsHTML($grades['core'])
        . "<tr class='bg-light'>
                    <td colspan='4' class='fw-bold'>Applied Subjects</td>
                </tr>" .
        prepareGradeRecordsHTML($grades['applied']);


    if ($_SESSION['user_type'] == 'ST') {
        if (array_key_exists('specialized', $grades)) {
            prepareGradeRecordsHTML($grades['specialized']);
        }
    } else {
        $grd .= "<tr class='bg-light'>
                    <td colspan='4' class='fw-bold'>Specialized</td>
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


    $grd .= "<tr>
                <td colspan='3' class='border-0 fst-italic text-end pe-3'>General Average for the Semester</td>
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
        <div class='mx-auto' style='width: 85%;'>
            <p class='text-center pt-5' style='font-size: 14px;'>PARENT / GUARDIAN'S SIGNATURE</p>
            <div class='row justify-content-center mb-2' style='font-size: 16px;'>
                <div class='col-5'  style='font-size: 12px !important;'>FIRST QUARTER:</div>
                <div class='col-7 border-bottom border-dark'></div>
            </div>
            <div class='row justify-content-center mb-2'>
                <div class='col-5'  style='font-size: 12px !important;'>SECOND QUARTER:</div>
                <div class='col-7 border-bottom border-dark'></div>
            </div>
            <div class='row justify-content-center mb-2'>
                <div class='col-5'  style='font-size: 12px !important;'>THIRD QUARTER:</div>
                <div class='col-7 border-bottom border-dark'></div>
            </div>
            <div class='row justify-content-center'>
                <div class='col-5'  style='font-size: 12px !important;'>FOURTH QUARTER:</div>
                <div class='col-7 border-bottom border-dark'></div>
            </div>

            <br><br>
            <h6 class='text-center fw-bold' style='font-size: 16px;'>CERTIFICATE OF TRANSFER</h6>
            <div class='row mb-1'>
                <div class='col-3 ' style='font-size: 12px;'>Admitted to Grade: </div>
                <div class='col-4 border-dark border-bottom'></div>
                <div class='col-2 d-flex align-items-end' style='font-size: 12px;'><p class='mb-0'>Section:</p></div>
                <div class='col-3 border-dark border-bottom'></div>
            </div>
            <div class='row mb-1'>
                <div class='col-5 d-flex align-items-end text-center' style='font-size: 12px;'>Eligible for Admission to Grade:</div>
                <div class='col-7 border-dark border-bottom'></div>
            </div>
        </div>
        <div class='mx-auto'>
            <p class='mb-2' style='font-size: 14px;'>Approved:</p>
            <div class='row mb-4'  style='font-size: 14px;' >
                <div class='col-6' >   
                    <div class='row ps-2 mb-0 fw-bold'>". approve ."</div>             
                    <div class='row ps-2 mb-0'>". approve_pos ."</div>             
                </div>
                <div class='col-6 ' >
                    <div class='container'>
                        <div class='row mb-0 justify-content-center border-bottom border-dark fw-bold'>". adviser ."</div>             
                        <div class='row mb-0 justify-content-center'>Adviser</div>             
                    </div>
                </div>
            </div>
            <h6 class='text-center fw-bolder'  style='font-size: 13px;'>CANCELLATION OF ELIGIBILITY TO TRANSFER</h6>
            <div class='row mb-3 justify-content-center'>
                <div class='col-3' style='font-size: 12px;'>Admitted in:</div>
                <div class='col-6 border-dark border-bottom'></div>
            </div>
             <div class='row justify-content-center mb-4'>
                <div class='col-3' style='font-size: 12px;'>Date:</div>
                <div class='col-6 border-dark border-bottom'></div>
            </div>
             <div class='row justify-content-end pe-4'>
                <div class='col-6'>
                    <div class='row justify-content-center border-dark border-top' style='font-size: 14px;'>School</div>
                </div>
            </div>
        </div>
    </div>";
}

function logoToSignatory($lastName, $firstName, $midName, $age, $sex, $grade, $section, $lrn, $school_year, $trackStrand, $teacherName, $signatoryName, $position)
{
    echo "
        <div class='col-6'>
            <p style='font-size: 8px;'>School Form 9-SHS</p>
            <div class='row p-0 mx-1 justify-content-center'>
                <div class='col-3 p-0'  style='text-align: right'>
                    <img src='../assets/deped_logo.png' alt='DEPED Logo' title='DEPED Logo'>
                </div>
                <div class='col-6 p-0 text-center'>
                    <p style='font-size: 10px;'><span class='fw-bold'>Republic of the Philippines</span><br>Department of Education<br>
                        Cordillera Administrative Region<br>Division of Baguio City
                    </p>
                </div>
                <div class='col-3 p-0' style='text-align: left;'>
                    <img src='../assets/school_logo.jpg' alt='PCNSH Logo' title='PCNSH Logo'>
                </div>
            </div>
            <p class='text-center fw-bold mb-0' style='font-size: 12px;'>PINES CITY NATIONAL HIGH SCHOOL-SENIOR HIGH</p>
            <p class='text-center mb-3' style='font-size: 10px;'>Lucban Campus, Camdas Magsaysay Avenue, Baguio City</p>
            <p class='text-center fw-bold' style='font-size: 12px;'>LEARNER'S PROGRESS REPORT CARD</p>
            <div class='container'>
                <div class='row mt-4'  style='font-size: 12px;'>
                    <div class='col-2 fw-bold'>Name: </div>
                    <div class='col-10'>
                        <div class='row border-bottom border-dark'>
                           <div class='col-4 px-1 text-wrap'>$lastName</div>
                           <div class='col-4 px-1 text-wrap'>$firstName</div>
                           <div class='col-4 px-1 text-wrap'>$midName</div>
                        </div>
                    </div>
                </div>
               <div class='row mb-2'  style='font-size: 12px;' >
                    <div class='col-2'></div>
                    <div class='col-10'>
                        <div class='row'>
                           <div class='col-4 px-1'>(Last Name)</div>
                           <div class='col-4 px-1'>(First Name)</div>
                           <div class='col-4 px-1'>(Middle Name)</div>
                        </div>
                    </div>
               </div>
              <div class='row mb-2'  style='font-size: 12px;' >
                  <div class='col-2 fw-bold'>Age:</div>
                  <div class='col-3 border-bottom border-dark text-center'>$age</div>
                  <div class='col-2'></div>
                  <div class='col-2 fw-bold'>Gender:</div>
                  <div class='col-3 border-bottom border-dark text-center'>$sex</div>
              </div>
              <div class='row mb-2'  style='font-size: 12px;' >
                  <div class='col-2 fw-bold'>Grade:</div>
                  <div class='col-3 border-bottom border-dark text-center'>$grade</div>
                  <div class='col-2'></div>
                  <div class='col-2 fw-bold'>Section:</div>
                  <div class='col-3 border-bottom border-dark text-center'>$section</div>
              </div>
               <div class='row justify-content-end mb-3'  style='font-size: 12px;' >
                  <div class='col-1'>LRN:</div>
                  <div class='col-5 border-bottom border-dark text-center mx-3'>$lrn</div>
              </div>
               
               <div class='row mb-3'  style='font-size: 12px;' >
                  <div class='col-3 fw-bold'>School Year:</div>
                  <div class='col-4 border-bottom border-dark text-center'>$school_year</div>
              </div>
                <div class='row mb-5'  style='font-size: 12px;' >
                  <div class='col-3 fw-bold text-center d-flex align-items-end'>Track/<br>Strand:</div>
                  <div class='col-9 border-bottom border-dark text-center'><p class='mb-0'>$trackStrand[0]</p></div>
              </div>
               <div class='row mb-3'  style='font-size: 14px;' >
                  <p class='fst-italic mb-0' style = 'font-size: 10px;'>Dear Parent,</p>
                    <p class='text-center fst-italic'>
                        This report card shows the ability and progresses your child has made in the different learning areas as well the learning modality.
                        The school welcomes you should you desire to know more about your child's progress.
                    </p>
              </div>
              <div class='row mb-3 text-center'  style='font-size: 14px;' >
                <div class='col-6' >   
                    <div class='row ps-2 mb-0 fw-bold'>". approve ."</div>             
                    <div class='row ps-2 mb-0' style='font-size: 12px;' >". approve_pos ."</div>             
                </div>
                <div class='col-6 ' >
                    <div class='container'>
                        <div class='row mb-0 justify-content-center border-bottom border-dark fw-bold'>". adviser ."</div>             
                        <div class='row mb-0 justify-content-center'>CLASS ADVISER</div>             
                    </div>
                </div>
                </div>
            </div>
    
           
        </div>
    </div>
</li>
";
}
function attendance($attendance, $lastName, $firstName, $midName, $age, $sex, $grade, $section, $lrn, $school_year, $trackStrand, $teacherName, $signatoryName, $position)
{
    echo "
    <li class='p-0 mb-0 mx-auto'>
        <div class='row'>
            <div class='col-6'>
                <table class='w-100 mt-3' style='font-size: 12px; '>
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
    
                <thead class='text-center'>
                    <tr class='months'>
                        <td></td>";
                foreach (array_keys($attendance['no_of_days']) as $month_key) {
                    echo '<td style="font-size: 10px; padding: 0 2px 0 2px;">' . strtoupper(substr($month_key, 0, 3)) . '</td>';
                }
                echo "<td style='font-size: 10px; padding: 0 2px 0 2px;'>TOTAL</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='text-center' style='font-size: 10px;'>No. of School days</td>";
                        prepareStudentAttendanceHTML('no_of_days', $attendance);
                echo "</tr>
                    <tr>
                        <td class='text-center' style='font-size: 10px;'>No. of Days Present</td>";
                        prepareStudentAttendanceHTML('no_of_present', $attendance);
                echo "</tr>
                    <tr>
                        <td class='text-center' style='font-size: 10px;'>No. of Days Absent</td>";
                    prepareStudentAttendanceHTML('no_of_absent', $attendance);
                echo "</tr>";
            echo "</tbody></table>";
            otherinfo();
            logoToSignatory($lastName, $firstName, $midName, $age, $sex, $grade, $section, $lrn, $school_year, $trackStrand, $teacherName, $signatoryName, $position);
}
?>

    <div class="d-flex justify-content-center">
        <div class="doc bg-white ms-2 mt-3 p-0 shadow overflow-auto">
            <ul class="template p-0 w-100">
                <?php
                if ($user_type != "ST") {
                    attendance($attendance, $lastName, $firstName, $midName, $age, $sex, $grade, $section, $lrn, $school_year, $trackStrand, $teacherName, $signatoryName, $position);
                }
                ?>

                <li class="p-0 mb-0 mx-auto">
                    <p class="fw-bolder mb-0" style="font-size: 14px;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</p>
                    <div class="row">
                        <?php
                        if ($user_type == "ST") {
                            renderSemesterGradeTable('FIRST SEMESTER', $grades[$curr_sem]);
                        } else {
                            echo "<div class='col-6'>";
                            renderSemesterGradeTable('FIRST SEMESTER', $grades['1']);
                            echo "</div>";
                            echo "<div class='col-6'>";
                            renderSemesterGradeTable('SECOND SEMESTER', $grades['2']);
                            echo "</div>";
                        }

                        ?>
                    </div>
                </li>

                <!-- Modality -->
                <li class="p-0 mb-0 mx-auto" style="font-size: 14px;">
                    <div class="row justify-content-between">
                    <?php
                    foreach([[1, 2], [3, 4]] as $qtrs) {
                        echo "<div class='col-5'>"
                            ."<table class='table' style='font-style: 12px;'>"
                            ."<thead class='text-center'><tr><td colspan='2'>LEARNING MODALITY</td></tr>";
                        echo "<tr>";
                        foreach($qtrs as $qtr) {
                            echo "<td>QUARTER $qtr</td>";
                        }
                        echo "</tr></thead>";
                        echo "<tbody>";
                        echo "<tr class='text-center'><td>MODULAR(PRINTED)</td><td>MODULAR(PRINTED)</td></tr>";
                        echo "</tbody></table></div>";
                    }
                    ?>
                    </div>
                </li>

            </ul>
        </div>
    </div>
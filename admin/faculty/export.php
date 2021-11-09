<?php
require_once("../class/Administration.php");
$admin = new Administration();
$file_name = 'faculty_info';
$students = [];
//if (!isset($_POST['id'])){
//    header("Location: student.php");
//}

$faculties = $_POST['id'];

?>
    <script src="../assets/js/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/report.css">
</head>
<body>
<!DOCTYPE html>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="faculty.php">Faculty</a></li>
            <li class="breadcrumb-item active">Export</li>
        </ol>
    </nav>
    <h3>Export Faculty Information</h3>
    <hr class="my-2">
    <div class="row mb-4">
        <div class="col-auto">
            <button class="btn btn-sm btn-primary" onclick="generatePDF(`<?php echo $file_name; ?>`)" id="export">Download</button>
        </div>
    </div>
</header>
<!--CONTENT-->
<div class="doc bg-white ms-2 p-0 shadow overflow-auto">
    <ul class="template p-0">
        <?php
                const PROFILE_PATH = "../assets/profile.png";
                foreach($faculties as $index => $id) { ?>
        <li class="p-0 mb-0 mx-auto">
            <!-- DOCUMENT HEADER -->
            <div class="row p-0 mx-1">
                <div class="col-3 p-0">
                    <img src="../assets/deped_logo.png" alt="">
                </div>
                <div class="col-6 p-0 text-center">
                    <p>
                        Republic of the Philippines<br>
                        Department of Education<br>
                        Cordillera Administrative Region<br>
                        Baguio City Schools Division<br>
                        PINES CITY NATIONAL HIGH SCHOOL SENIOR HIGH<br>
                        Lucban Campus
                    </p>
                </div>
                <div class="col-3 p-0" style="text-align: right;">
                    <img src="../assets/school_logo.jpg" alt="">
                </div>
            </div>
            <div class="report-title">
                <h5 class="">Faculty Information</h5>
<!--                <p class="sub-title">SY --><?php //echo $school_year ?><!--</p>-->
            </div>
            <!-- DOCUMENT HEADER END -->
            <div class="content mb-3">
                <?php
                    $faculty = $admin->getFaculty($id);
                    $teacher_id = $faculty->get_teacher_id();
//                    $user_id_no = $faculty->get_id_no();
                    $name = $faculty->get_name();
                    $sex = $faculty->get_sex();
                    $age = $faculty->get_age();
                    $birthdate = $faculty->get_birthdate();
                    $birthdate = date("F j, Y", strtotime($birthdate));
                    $cp_no = $faculty->get_cp_no();
                    $email = $faculty->get_email();
                    $department = $faculty->get_department();
                    $dept_exist = TRUE;
                    if ($department == '') {
                        $dept_exist = FALSE;
                        $department = 'No department set';
                    }
                ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                                <dl class="row">
                                    <dt class="col-4">ID:</dt>
                                    <dd class="col-8"><?php echo $teacher_id; ?></dd>
                                    <dt class="col-4">Name:</dt>
                                    <dd class="col-8"><?php echo $name; ?></dd>
                                    <dt class="col-4">Sex:</dt>
                                    <dd class="col-8"><?php echo $sex; ?></dd>
                                    <dt class="col-4">Age:</dt>
                                    <dd class="col-8"><?php echo $age; ?></dd>
                                    <dt class="col-4">Birthdate:</dt>
                                    <dd class="col-8"><?php echo $birthdate; ?></dd>
                                </dl>
                            </div>
                            <div class="col-6">
                                <dl class="row">
                                    <dt class="col-4">Contact No:</dt>
                                    <dd class="col-8"><?php echo $cp_no; ?></dd>
                                    <dt class="col-4">Email:</dt>
                                    <dd class="col-8"><?php echo $email; ?></dd>
                                </dl>
                            </div>
                        </div>
                        <h6>School Information</h6>
                        <div class="row">
                            <div class="container">
                                <div class='row'>
                                    <div class="container">
                                        <table class="w-100 table table-sm table-bordered">
                                            <col width="15%">
                                            <col width="15%">
                                            <col width="35%">
                                            <col width="10%">
                                            <col width="10%">
                                            <thead class="text-center">
                                                <tr>
                                                    <th colspan="5">Advisory Classes</th>
                                                </tr>
                                                <tr>
                                                    <th>SY</th>
                                                    <th>Section Code</th>
                                                    <th>Name</th>
                                                    <th>Grade</th>
                                                    <th>Students</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $advisoryClasses = $admin->listAdvisoryClasses($teacher_id, FALSE);
                                                if (empty($advisoryClasses)) {
                                                    echo "<tr><td colspan='5' class='text-center'>No handled advisory class</td></tr>";
                                                } else {
                                                    foreach($advisoryClasses as $advisoryClass) {
                                                        echo "<tr>
                                                            <td align='center'>{$advisoryClass['sy']}</td>
                                                            <td align='center'>{$advisoryClass['section_code']}</td>
                                                            <td>{$advisoryClass['section_name']}</td>
                                                            <td align='center'>{$advisoryClass['section_grd']}</td>
                                                            <td align='center'>{$advisoryClass['stud_no']}</td>
                                                        </tr>";
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="container">
                                        <table class="w-100 table table-sm table-bordered">
                                            <thead class="text-center">
                                            <tr>
                                                <th colspan="4">Handled Subjects</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr><td class="w-100 d-inline-flex flex-wrap">
                                            <?php
                                            $subjects = $faculty->get_subjects();
                                            if (empty($subjects)) {
                                                echo "<p class='w-100 mb-0 text-center'>No handled subject</p>";
                                            } else {
                                                foreach($subjects as $sub) {
                                                    echo "<div class='col-6 border p-1'>{$sub->get_sub_name()}</div>";
                                                }
                                            }
                                            ?>
                                            </td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="container">
                                        <table class="w-100 table table-sm table-bordered">
                                            <col width="15%">
                                            <col width="50%">
                                            <col width="30%">
                                            <col width="5%">
                                            <thead class="text-center">
                                                <tr>
                                                    <th colspan="4">Subject class</th>
                                                </tr>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Subject</th>
                                                    <th>Section</th>
                                                    <th>Grade</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $handled_sub_classes = $faculty->get_handled_sub_classes();
//                                                $handled_sub_classes = [];
                                                if (empty($handled_sub_classes)) {
                                                    echo "<tr class='text-center'><td colspan='4'>No assigned subject class</td></tr>";
                                                } else {
                                                    foreach($handled_sub_classes as $sclass) {
                                                        echo "<tr>
                                                            <td align='center'>{$sclass->get_sub_class_code()}</td>
                                                            <td>{$sclass->get_sub_name()}</td>
                                                            <td>{$sclass->get_section_name()}</>
                                                            <td align='center'>{$sclass->get_grade_level()}</td>
                                                         </tr>";
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </li>
            <?php
            if (!(array_key_last($faculties) == $index)) {
                echo '<div class="html2pdf__page-break"></div>';
            }
       } ?>
    </ul>
</div>
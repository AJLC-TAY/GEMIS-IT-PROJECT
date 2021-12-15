<?php
require_once("../class/Administration.php");
$admin = new Administration();
$section = $admin->getSection();
$sect_code = $section->get_code();
$sect_name = $section->get_name();
$sect_grd_level = $section->get_grd_level();
$sect_max_no = $section->get_max_stud();
$sect_stud_no = $section->get_stud_no();
$sect_adviser = $section->get_teacher_id();
$school_year = $section->get_sy_desc();
$sy_id = $section->get_sy();

$_GET['section'] = $sect_code;
$students = $admin->listAdvisoryStudents();
$file_name = $sect_name;
$male = 0;
$female = 0;
foreach($students as $student) {
    if ($student['sex'] == 'Male') {
        $male = $male + 1;
    } else {
        $female = $female + 1;
    }
}

?>
<script src="../assets/js/html2pdf.bundle.min.js"></script>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="section.php">Section</a></li>
            <li class="breadcrumb-item"><a href="section.php?sec_code=<?php echo $sect_code; ?>"><?php echo $sect_name; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Export</li>
        </ol>
    </nav>
    <h2 class="fw-bold">Export section</h2>
    <hr class="my-2">
    <div class="row mb-4">
        <div class="col-auto">
            <button class="btn btn-sm btn-primary" onclick="generatePDF(`<?php echo $file_name; ?>`)" id="export">Download</button>
        </div>
    </div>
</header>
<!-- HEADER END -->
<div class="doc bg-white ms-2 p-0 shadow overflow-auto">
    <ul class="template p-0">
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
            <h6>Section Information</h6>
            <div class="container">
                <div class="row border p-2 mb-3">
                    <div class="col-md-8">
                        <dl class="row mb-0">
                            <dt class="col-4">Section Name</dt>
                            <dd class="col-8"><?php echo $sect_name; ?></dd>
                            <dt class="col-4">Section Code</dt>
                            <dd class="col-8"><?php echo $sect_code; ?></dd>
                            <dt class="col-4">School Year</dt>
                            <dd class="col-8"><?php echo $_SESSION['school_year']; ?></dd>
                            <dt class="col-4">Advisory Teacher</dt>
                            <dd class="col-8"><?php echo (empty($sect_adviser['name']) ? "" : "T. ".$sect_adviser['name']); ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl class="row mb-0">
                            <dt class="col-7">Grade Level</dt>
                            <dd class="col-5"><?php echo $sect_grd_level; ?></dd>
                            <dt class="col-7">No of Students</dt>
                            <dd class="col-5"><?php echo $sect_stud_no; ?></dd>
                            <dt class="col-7">No of Male</dt>
                            <dd class="col-5"><?php echo $male; ?></dd>
                            <dt class="col-7">No of Female</dt>
                            <dd class="col-5"><?php echo $female; ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <!-- DOCUMENT HEADER END -->
            <div class="content mb-5">
                <h6>Student List</h6>
                <div class="table-con">
                    <table class="table table-sm">
                        <thead>
                            <tr class="text-center">
                                <td>LRN</td>
                                <td>User ID</td>
                                <td>Student Name</td>
                                <td>Sex</td>
                            </tr>
                        </thead>
                            <tbody>
                            <?php
                                foreach($students as $student) {
                                    echo "<tr>";
                                    echo "<td align='center'>{$student['lrn']}</td>";
                                    echo "<td align='center'>{$student['uid']}</td>";
                                    echo "<td>{$student['name']}</td>";
                                    echo "<td align='center'>{$student['sex']}</td>";
                                    echo "</tr>";
                                }
                            ?>
                            </tbody>
                    </table>
                </div>
            </div>
        </li>
    </ul>
</div>




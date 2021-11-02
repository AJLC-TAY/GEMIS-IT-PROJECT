<?php
require_once("../class/Administration.php");
$admin = new Administration();
$file_name = 'student_info';
$students = [];
//if (!isset($_POST['id'])){
//    header("Location: student.php");
//}
$students = $_POST['id'];
?>
    <script src="../assets/js/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/report.css">
</head>
<body>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="student.php">Student</a></li>
            <li class="breadcrumb-item active">Export</li>
        </ol>
    </nav>
    <h3>Export Student Information</h3>
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
                foreach($students as $id) { ?>
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
                <h5 class="">Student Information</h5>
<!--                <p class="sub-title">SY --><?php //echo $school_year ?><!--</p>-->
            </div>
            <!-- DOCUMENT HEADER END -->
            <div class="content mb-3">
                <?php
                    $student = $admin->getStudent($id);
                    $stud_id = $student->get_stud_id();
                    $user_id_no = $student->get_id_no();
                    $lrn = $student->get_lrn();
                    $name = $student->get_name();
                    $sex = $student->get_sex();
                    $age = $student->get_age();
                    $birthdate = $student->get_birthdate();
                    $birthdate = date("F j, Y", strtotime($birthdate));
                    $birth_place = $student->get_birth_place();
                    $indigenous_group = $student->get_indigenous_group();
                    $mother_tongue = $student->get_mother_tongue();
                    $religion = $student->get_religion();
                    $address = $student->get_address();
                    $add = $address['address'];
                    $cp_no = $student->get_cp_no();
                    $belong_to_ipcc = $student->get_belong_to_ipcc();
                    $id_picture = $student->get_id_picture();
                    $section = $student->get_section();
                    $parents = $student->get_parents();
                    $guardian = $student->get_guardians();

                    $image = !is_null($id_picture) ? (file_exists($id_picture) ? $id_picture : PROFILE_PATH) : PROFILE_PATH;
                ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-8">
                                <dl class="row">
                                    <dt class="col-4">LRN:</dt>
                                    <dd class="col-8"><?php echo $lrn; ?></dd>
                                    <dt class="col-4">Student Name:</dt>
                                    <dd class="col-8"><?php echo $name; ?></dd>
                                    <dt class="col-4">Sex:</dt>
                                    <dd class="col-8"><?php echo $sex; ?></dd>
                                    <dt class="col-4">Age:</dt>
                                    <dd class="col-8"><?php echo $age; ?></dd>
                                    <dt class="col-4">Birthdate:</dt>
                                    <dd class="col-8"><?php echo $birthdate; ?></dd>
                                    <dt class="col-4">Birth place:</dt>
                                    <dd class="col-8"><?php echo $birth_place; ?></dd>
                                    <dt class="col-4">Contact No:</dt>
                                    <dd class="col-8"><?php echo $cp_no; ?></dd>
                                    <dt class="col-4">Address:</dt>
                                    <dd class="col-8"><?php echo $add; ?></dd>
                                    <dt class="col-4">Religion:</dt>
                                    <dd class="col-8"><?php echo $religion; ?></dd>
                                    <dt class="col-4">Mother Tongue:</dt>
                                    <dd class="col-8"><?php echo $mother_tongue; ?></dd>
                                    <dt class="col-4">Indigenous Group:</dt>
                                    <dd class="col-8"><?php echo $indigenous_group; ?></dd>
                                </dl>
                            </div>
                            <div class="col-4 justify-content-center">
                                <img src="<?php echo $image; ?>" alt="Student Image" style="width: 180px; height: auto;">
                            </div>
                        </div>
                        <div class="row">
                            <h6>Parent | Guardian Information</h6>
                            <div class="col-6">
                                <dl class='row'>
                                    <dt class="col-3">Parents</dt>
                                    <dd class='col-8'>
                                            <?php foreach($parents as $parent) {
                                                $sex = ($parent['sex'] == 'f' ? "Female" : "Male");
                                                echo "<dl class='row mb-0'>
                                                        <dd class='mb-0'>{$parent['name']}</dd>
                                                        <dd class='mb-0'>$sex</dd>
                                                        <dd class='mb-0'>{$parent['occupation']}</dd>
                                                        <dd class='mb-0'>{$parent['cp_no']}</dd>
                                                        <hr class='mb-1'>
                                                      </dl>";
                                            }?>
                                    </dd>
                            </div>
                            <div class="col-6">
                                <dl class="row">
                                    <dt class="col-3">Guardian</dt>
                                    <dd class='col-8'>

                                        <?php
                                        echo "<dl class='row mb-0'>
                                                <dd class='mb-0'>{$guardian['name']}</dd>
                                                <dd class='mb-0'>{$guardian['relationship']} (Relationship)</dd>
                                                <dd class='mb-0'>{$guardian['cp_no']}</dd>
                                                <hr class='mb-1'>
                                              </dl>";
                                        ?>
                                    </dd>
                                </dl>
                            </div>
                    </div>

            </div>
        </li>
        <?php } ?>
    </ul>
</div>
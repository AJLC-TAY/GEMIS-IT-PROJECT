<?php
include_once("../inc/head.html");
// session_start();
$_SESSION['id'] = $user_id = 4;
$_SESSION['sy_id'] = 15;
$_SESSION['sy_desc'] = '2021 - 2022';
$_SESSION['enrollment'] = 0;
$_SESSION['user-type'] = 'AD';

require_once("../class/Administration.php");
$admin = new Administration();
$admin_user = $admin->getProfile('AD');

?>
<title>Home | GEMIS</title>
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
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <?php
                            require_once("../class/Administration.php");
                            $admin = new Administration();
                            [$admins, $faculties, $students, $signatories] = $admin->getUserCounts();
                            ?>
                            <!-- HEADER -->
                            <header class="mb-4">
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active">Home</li>
                                    </ol>
                                </nav>
                                <div class="card">
                                    <div class="form-row row">
                                        <div class="form-group col-md-6">
                                            <h2 class="fw-bold mt-3 ms-3">Welcome!</h2>
                                            <ul class="ms-4 list-style">
                                                <li>
                                                    <h4><?php echo $admin_user->name; ?></h4>
                                                </li>
                                                <li>School Year: 2023 - 2024 </li>
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <img src="../assets/admin.png" style="width: 50%; display: block; margin-left: auto; float:right;">
                                        </div>
                                    </div>
                                </div>
                            </header>
                            <div class="container mb-3">
                                <!-- PEOPLE MANAGEMENT -->
                                <section class="row">
                                    <h5 class="fw-bold">PEOPLE MANAGEMENT</h5>
                                    <div class="col-lg-3">
                                        <div class="card-box bg-default">
                                            <div class="inner">
                                                <h3> <?php echo $admins; ?> </h3>
                                                <p>Admin</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-user-circle" aria-hidden="true"></i>
                                            </div>
                                            <a href="admin.php?id=<?php echo $user_id; ?>" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card-box bg-forest">
                                            <div class="inner">
                                                <h3> <?php echo $faculties; ?> </h3>
                                                <p>Faculty</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-users" aria-hidden="true"></i>
                                            </div>
                                            <a href="faculty.php" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card-box bg-green">
                                            <div class="inner">
                                                <h3> <?php echo $students; ?> </h3>
                                                <p> Student </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div>
                                            <a href="student.php" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card-box bg-tea">
                                            <div class="inner">
                                                <h3> <?php echo $signatories; ?> </h3>
                                                <p> Signatory </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </div>
                                            <a href="signatory.php" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <!-- PEOPLE MANAGEMENT END -->
                            <!-- SCHOOL MANAGEMENT -->
                            <div class="container">
                                <section class="row">
                                    <h5 class="fw-bold">SCHOOL MANGEMENT</h5>
                                    <section class="col-sm-6">
                                        <div class="card bg-white rounded shadow-sm mt-2">
                                            <!-- CURRICULUM -->
                                            <section class="mb-2">
                                                <h6 class='mb-0 fw-bold ms-3 mt-2'>CURRICULUM</h6>
                                                <hr class="mt-1 mb-2">
                                                <div class="d-flex flex-wrap">
                                                    <div class='row'>
                                                        <div class="inner">
                                                            <div class="card-btn bg-pastel mx-4 mb-3">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>SCHOOL YEAR</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="schoolYear.php" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner">
                                                            <div class="card-btn bg-pastel mx-4 mb-3">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-book me-3" aria-hidden="true"></i>CURRICULUM</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="curriculumList.php" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner">
                                                            <div class="card-btn bg-pastel mx-4 mb-3">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-list-alt  me-3" aria-hidden="true"></i>PROGRAM</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="programlist.php" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner">
                                                            <div class="card-btn bg-pastel mx-4 mb-3">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-file-text me-3" aria-hidden="true"></i>SUBJECT</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="studentlist.php" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </section>

                                    <section class="col-md-6">
                                        <div class="card bg-pastel shadow-sm mt-2">
                                            <section class="mb-3">
                                                <h6 class='mb-0 fw-bold ms-3 mt-2'>ENROLLMENT</h6>
                                                <hr class="mt-1 mb-3">
                                                <div class="d-flex flex-wrap">
                                                    <div class='row'>
                                                        <div class="inner mb-4">
                                                            <div class="card-btn bg-white mx-4  mt-3" style="height: 81%;">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-tasks me-3" aria-hidden="true"></i>ENROLLMENT</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="#" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner mb-4">
                                                            <div class="card-btn bg-white mx-4 mt-1 mb-1" style="height: 81%;">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-cog me-3" aria-hidden="true"></i>SET UP</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="#" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner mb-4">
                                                            <div class="card-btn bg-white mx-4 mb-2" style="height: 81%;">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-list-ul me-3" aria-hidden="true"></i>SECTION</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="#" class="card-link redirect-card">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </section>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--footer start-->
            <?php include_once("../inc/footer.html"); ?>
            <!--footer end-->
        </section>
        <!--main content end-->
    </section>
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript">
    $(function() {
        preload("#home")
        hideSpinner()
    })
</script>

</html>
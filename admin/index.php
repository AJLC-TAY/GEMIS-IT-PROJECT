<?php include_once("../inc/head.html"); ?>
<title>Home | GEMIS</title>
</head>


<?php

?>

<body>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header class="mb-4">
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active">Home</li>
                                    </ol>
                                </nav>
                                <h2 class="fw-bold">Welcome!</h2>
                            </header>
                            <div class="container mb-3">
                                <!-- PEOPLE MANAGEMENT -->
                                <section class="row">
                                    <h5 class="fw-bold">PEOPLE MANAGEMENT</h5>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="card-box bg-blue">
                                            <div class="inner">
                                                <h3> 10 </h3>
                                                <p>Admin</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-user-circle" aria-hidden="true"></i>
                                            </div>
                                            <a href="#" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-5">
                                        <div class="card-box bg-green">
                                            <div class="inner">
                                                <h3> 20 </h3>
                                                <p>Faculty</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-users" aria-hidden="true"></i>
                                            </div>
                                            <a href="#" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-5">
                                        <div class="card-box bg-orange">
                                            <div class="inner">
                                                <h3> 300 </h3>
                                                <p> Student </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div>
                                            <a href="#" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-5">
                                        <div class="card-box bg-red">
                                            <div class="inner">
                                                <h3> 5 </h3>
                                                <p> Signatory </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </div>
                                            <a href="#" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <!-- PEOPLE MANAGEMENT END -->
                            <!-- SCHOOL MANAGEMENT -->
                            <div class="container">
                                <section class="row">
                                    <h5 class="fw-bold">SCHOOL MANGEMENT</h5>
                                    <section section class="col-sm-7">
                                        <div class="card bg-white rounded shadow-sm mt-2">
                                            <!-- CURRICULUM -->
                                            <section class="mb-4">
                                                <h6 class='mb-0'>Curriculum</h6>
                                                <hr class="mt-1 mb-3">
                                                <div class="d-flex flex-wrap">
                                                    <a href="schoolyear.php" class="redirect-card py-2 px-3 m-1 rounded shadow text-white btn-danger" role="button">
                                                        School year
                                                    </a>
                                                    <a href="curriculumlist.php" class="redirect-card py-2 px-3 m-1 rounded shadow text-dark btn-warning" role="button">
                                                        Curriculum
                                                    </a>
                                                    <a href="programlist.php" class="redirect-card py-2 px-3 m-1 rounded shadow btn-success" role="button">
                                                        Program
                                                    </a>
                                                    <a href="studentlist.php" class="redirect-card py-2 px-3 m-1 rounded shadow btn-primary" role="button">
                                                        Subject
                                                    </a>
                                                </div>
                                            </section>
                                        </div>
                                    </section>

                                    <section class="col-md-5">
                                        <div class="card bg-white rounded shadow-sm mt-2">
                                            <section class="mb-3">
                                                <h6 class='mb-0'>Enrollment</h6>
                                                <hr class="mt-1 mb-3">
                                                <div class="d-flex flex-wrap">
                                                    <a href="" class="redirect-card py-2 px-3 m-1 rounded shadow text-white btn-danger" role="button">
                                                        Enrollment
                                                    </a>
                                                    <a href=".php" class="redirect-card py-2 px-3 m-1 rounded shadow text-dark btn-warning" role="button">
                                                        Set Up
                                                    </a>
                                                    <a href=".php" class="redirect-card py-2 px-3 m-1 rounded shadow btn-success" role="button">
                                                        Section
                                                    </a>
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
        </section>
        <!--main content end-->
        <!--footer start-->
        <?php include_once("../inc/footer.html"); ?>
        <!--footer end-->
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
<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
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
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
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
                                            <ul class="ms-4 list-style p-0">
                                                <li>
                                                    <h4><?php echo $_SESSION['User']; ?></h4>
                                                </li>
                                                <li>School Year: <?php echo $_SESSION['school_year']; ?></li>
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <img src="../assets/faculty.png" alt="Display image" style="width: 40%; display: block; margin-left: auto; float:right;">
                                        </div>
                                    </div>
                                </div>
                            </header>
                            <div class="container mb-2">
                                <!-- OVERVIEW -->
                                <section class="row">
                                    <h4 class="fw-bold">OVERVIEW</h4>
                                    <div class="col-lg-4">
                                        <div class="card-box bg-forest h-75">
                                            <div class="inner">
                                                <h3>200</h3>
                                                <h5>Total Students</h5>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div>
                                            <a href="" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card-box bg-tea h-75">
                                            <div class="inner">
                                                <h5>View</h5>
                                                <h5>Grades</h5>
                                            </div>
                                            <div class="icon">
                                                <i class="bi bi-card-list" aria-hidden="true"></i>
                                            </div>
                                            <a href="" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card-box bg-green h-75">
                                            <div class="inner">
                                                <h5> View</h5>
                                                <h5>Attendance</h5>
                                            </div>
                                            <div class="icon">
                                                <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                                            </div>
                                            <a href="" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <!-- CLASSES -->
                            <div class="container mb-3">
                                <section class="row">
                                    <h5 class="fw-bold">ASSIGNED CLASSES</h5>
                                    <div class="col-lg-4">
                                        <div class="card-box bg-default">
                                            <div class="inner">
                                                <h3>11 ABM A</h3>
                                                <h4>Practical Research 1</h4>
                                                <h6>40 Students</h6>
                                            </div>
                                            <a href="" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card-box bg-default">
                                            <div class="inner">
                                                <h3>12 ABM A</h3>
                                                <h4>Applied Economics</h4>
                                                <h6>38 Students</h6>
                                            </div>
                                            <a href="" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card-box bg-default">
                                            <div class="inner">
                                                <h3>12 ABM B</h3>
                                                <h4>Applied Economics</h4>
                                                <h6>40 Students</h6>
                                            </div>
                                            <a href="" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </section>
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
    <script src="../js/common-custom.js"></script>
    <script>
        $(function () {
            preload("#home");
            hideSpinner();
        });
    </script>
</body>

</html>
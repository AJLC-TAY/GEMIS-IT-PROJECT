<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
print_r($_SESSION['roles']);
?>

<title>Home | GEMIS</title>
</head>

<body>
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
                                                    <h4>Faculty User Name</h4>
                                                </li>
                                                <li>School Year: <?php echo $_SESSION['school_year']; ?></li>
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <img src="../assets/faculty.png" alt="Display image" style="width: 50%; display: block; margin-left: auto; float:right;">
                                        </div>
                                    </div>
                                </div>
                            </header>
                            <div class="container mb-2">
                                <!-- OVERVIEW -->
                                <section class="row">
                                    <h4 class="fw-bold">OVERVIEW</h4>
                                    <div class="col-lg-4">
                                        <div class="card-box bg-tea h-75">
                                            <div class="inner">
                                                <h3>200</h3>
                                                <p> TOTAL STUDENTS </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div>
                                            <a href="" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="card-box bg-green h-75 w-100 p-4">
                                            <div class="inner d-flex justify-content-between">
                                                <div>
                                                    <h5>ATTENDANCE TODAY</h5>
                                                    <h3>11 ABM A</h3>
                                                </div>
                                                <div>
                                                    <h6>Present</h6>
                                                    <h3>37</h3>
                                                </div>
                                                <div>
                                                    <h6>Absent</h6>
                                                    <h3>3</h3>
                                                </div>
                                                <div>
                                                    <h6>Tardy</h6>
                                                    <h3>8</h3>
                                                </div>
                                            </div>
                                            <a href="" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <!-- CLASSES -->
                            <div class="container mb-3">
                                <section class="row">
                                    <h5 class="fw-bold">CLASSES</h5>
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
</body>

</html>
<?php
require_once("sessionHandling.php");
include_once("../inc/head.html"); 
$sem = $_SESSION['current_semester'] == 1? 'First': 'Second';
?>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>

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
        <?php include_once('../inc/studentSideBar.php'); ?>
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
                                        <!-- <li class="breadcrumb-item active">Home</li> -->
                                    </ol>
                                </nav>
                                <div class="card p-4">
                                    <div class="form-row row">
                                        <div class="form-group col-md-6">
                                            <h2 class="fw-bold mt-3 ms-3">Welcome!</h2>
                                            <ul class="ms-4 list-style p-0">
                                                <li>
                                                    <h4> <?php echo $_SESSION['User']?></h4>
                                                </li>
                                                <li><h6>
                                                    <?php if($_SESSION['promote'] == 2) {
                                                        echo "Completed the SHS curriculum";
                                                    }else {echo "Currently Enrolled in: $sem Semester, {$_SESSION['school_year']}";}?></h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <img src="../assets/student.png" alt="Display image" style="width: 35%; display: block; margin-left: auto; float:right;">
                                        </div>
                                    </div>
                                </div>
                            </header>

                            <div class="container">
                                <section class="row">
                                    <h4 class="fw-bold">OVERVIEW</h4>
                                    <section class="col-sm-6">
                                        <div class="card-box bg-default h-30">
                                            <div class="inner">
                                                <h3>11 ABM A</h3>
                                                <h5> Grade and Section </h5>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="card-box bg-tea h-30">
                                            <div class="inner">
                                                <h4>8th</h4>
                                                <h5> Rank</h5>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div>
                                        </div>                                   
                                    </section>
                                    
                                    <section class="col-md-6">
                                        <div class="card flex-grow shadow-sm mt-3">
                                            <section class="mb-3">
                                                <h5 class='mb-0 fw-bold ms-3 mt-2'>Award/s</h5>
                                                <hr class="mt-1 mb-3">                
                                                <div class="me-5">
                                                    <div class='row'>
                                                        <div class="inner w-75">
                                                            <ul>
                                                                <li>
                                                                    <h5>Academic Excellence</h5>
                                                                    <h6>First Semester, 2023-2021</h6>
                                                                </li>
                                                                <hr>
                                                            </ul>
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
<?php include_once("../inc/head.html");
session_start();
?>
<title>School Year | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
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
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label='breadcrumb'>
                                    <ol class='breadcrumb'>
                                        <li class='breadcrumb-item'><a href='index.html'>Home</a></li>
                                    </ol>
                                </nav>
                                <h4 class="fw-bold">School Year 2021 - 2022</h4>
                                <hr>
                            </header>
                            <div class="container">
                                <section class="row">
                                    <section class="col-sm-6">
                                        <div class='col-xl-12 shadow-sm p-3 bg-light border rounded-3 text-start mb-4 me-3'>
                                            <h5 class='text-start p-0 fw-bold'>TRACKS</h5>
                                            <hr class='mt-1'>
                                            <div class='row p-2 ms-2'>
                                                <h6>K to 12 - Academic Track</h6>
                                                <h6>K to 12 - TVL Track</h6>
                                            </div>
                                        </div>
                                        <div class='col-xl-12 shadow-sm p-3 bg-light border rounded-3 text-start mb-4'>
                                            <h5 class='text-start p-0 fw-bold'>STRAND</h5>
                                            <hr class='mt-1'>
                                            <div class='row'>
                                                <div class='fw-bold ms-3'>K to 12 - Academic Track</div>
                                                <ul class="ms-4">
                                                    <li>Accountancy, Business, and Management</li>
                                                    <li>Humanities and Social Sciences</li>
                                                </ul>
                                                <div class='fw-bold ms-3'>K to 12 - TVL</div>
                                                <ul class="ms-4 mb-1">
                                                    <li>Bread and Pastry</li>
                                                    <li>Electronics</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="col-md-6">
                                        <div class='col-xl-12 shadow-sm p-3 bg-light border rounded-3 text-start mb-4 me-3'>
                                            <div class="d-flex justify-content-between">
                                                <h5 class='text-start p-0 fw-bold'>ACADEMIC DAYS</h5>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-pencil-square me-2"></i>Edit</button>
                                                </div>
                                            </div>
                                            <hr class='mt-2'>
                                            <div class='row d-flex justify-content-center'>
                                                <h5 class='fw-bold d-flex justify-content-center'>2021</h5>
                                                <div class="inner row justify-content-center">
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">August</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">September</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">October</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">November</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">December</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                </div>
                                                <h5 class='fw-bold d-flex justify-content-center'>2022</h5>
                                                <div class="inner row justify-content-center">
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">January</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">February</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">March</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">April</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                    <div class="ms-4 col-sm-8">
                                                        <h7 class="fw-bold mt-1">May</h7>
                                                    </div>
                                                    <div class="mt-1 col-sm-3">
                                                        <h7>20</h7>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </section>
                            </div>

                            <h5 class="fw-bold mb-3">SUBJECT CHECKLIST</h5>
                            <div class="container">
                                <section class="row">
                                    <section class="col-sm-6">
                                        <div class="col-xl-12 shadow-sm p-4 bg-light border rounded-3 text-start mb-4 ">
                                            <h5 class='text-start p-0 fw-bold'>CORE SUBJECTS</h5>
                                            <hr class='mt-1'>

                                            <ul class='ms-2 list-group'>
                                                <li class='list-group-item'>Contemporary Philippine Arts from the Regions<br>
                                                <li class='list-group-item'>Earth and Life Science<br>
                                                <li class='list-group-item'>General Mathematics<br>
                                            </ul>
                                        </div>
                                    </section>
                                    <section class="col-sm-6">
                                        <div class="col-xl-12 shadow-sm p-4 bg-light border rounded-3 text-start mb-4">
                                            <h5 class='text-start p-0 fw-bold'>SPECIALIZED | APPLIED SUBJECTS </h5>
                                            <hr class='mt-1'>
                                            <ul class='ms-2 list-group'>
                                                <li class='list-group-item'>Applied Economics<span class="badge bg-primary badge-pill">ABM </span><br>
                                                <li class='list-group-item'>Bread and Pastry Production<span class="badge bg-warning badge-pill">B&P </span><br>
                                                <li class='list-group-item'>Electronic Products Assembly and Servicing<span class="badge bg-info badge-pill">ELEC </span><br>
                                                <li class='list-group-item'>Philippine Politics and Governance<span class="badge bg-success badge-pill">HUMMS</span><br>
                                            </ul>
                                        </div>
                                    </section>
                                </section>
                            </div>
                            <!--MAIN CONTENT END-->
                            <!--FOOTER START-->
                            <?php include_once("../inc/footer.html"); ?>
                            <!--FOOTER END-->
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>
    <!-- JQUERY FOR BOOTSTRAP TABLE -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!-- CUSTOM JS -->
    <script src="../js/common-custom.js"></script>
    <script type="module" src="../js/admin/school-year.js"></script>
</body>

</html>
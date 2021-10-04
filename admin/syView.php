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
                            <div class='row justify-content-around'>
                                <div class='col-xl-3 shadow-sm p-3 bg-light border rounded-3 text-start mb-4'>
                                    <h5 class='text-start p-0 fw-bold'>TRACKS</h5>
                                    <hr class='mt-1'>
                                    <div class='row'>
                                        <h6>K to 12 - Academic Track</h6>
                                        <h6>K to 12 - TVL Track</h6>
                                    </div>
                                </div>
                                <div class='col-xl-6 shadow-sm p-3 bg-light border rounded-3 text-start mb-4'>
                                    <h5 class='text-start p-0 fw-bold'>STRAND</h5>
                                    <hr class='mt-1'>
                                    <div class='row p-0'>
                                        <div class='row'>
                                            <div class='fw-bold'>K to 12 - Academic Track</div>
                                            <ul class="ms-3">
                                                <li>Accountancy, Business, and Management</li>
                                                <li>Humanities and Social Sciences</li>
                                            </ul>
                                        </div>
                                        <div class='row'>
                                            <div class='fw-bold'>K to 12 - TVL Track</div>
                                            <ul class="ms-3">
                                                <li>Bread and Pastry</li>
                                                <li>Electronics</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="fw-bold">Subject Checklist</h5>
                                <div class="row justify-content-around mt-2">
                                    <div class="col-md-6 shadow-sm p-4 bg-light border rounded-3 text-start mb-4 me-1">
                                        <div class="row">
                                            <h6><b>CORE SUBJECTS</b></h6>
                                            <hr>
                                            <ul class='ms-2 list-group'>
                                                <li class='list-group-item'>Contemporary Philippine Arts from the Regions<br>
                                                <li class='list-group-item'>Earth and Life Science<br>
                                                <li class='list-group-item'>General Mathematics<br>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-5 shadow-sm p-4 bg-light border rounded-3 text-start mb-4">
                                        <div class="row">
                                            <h6><b>SPECIALIZED | APPLIED SUBJECTS</b></h6>
                                            <hr>
                                            <ul class='ms-2 list-group'>
                                                <li class='list-group-item'>Applied Economics<br>
                                                <li class='list-group-item'>Business Enterprise Stimulation<br>
                                                <li class='list-group-item'>Business Ethics and Social Responsibility<br>
                                            </ul>
                                        </div>
                                    </div>

                                    <!--MAIN CONTENT END-->
                                    <!--FOOTER START-->
                                    <?php include_once("../inc/footer.html"); ?>
                                    <!--FOOTER END-->
                                </div>
                            </div>
            </section>
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
    <!-- JQUERY FOR BOOTSTRAP TABLE -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!-- CUSTOM JS -->
    <script src="../js/common-custom.js"></script>
    <script type="module" src="../js/admin/school-year.js"></script>
</body>

</html>
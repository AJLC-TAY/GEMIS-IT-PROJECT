<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>
<title>Assign Faculty to Subject | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
    <!-- SPINNER -->
    <!--    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> -->
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!--MAIN CONTENT -->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="row ps-3">
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Assign Faculty to Subject</a></li>
                                </ol>
                            </nav>
                            <div class="d-flex justify-content-between mb-3">
                                <h3 class="fw-bold">Assign Faculty to Subject</h3>
                            </div>
                        </header>
                        <div class="card body w-100 h-auto p-4 ms-3">
                            <div class="form-group row d-flex justify-content-center">
                                <div class="col-md-5">
                                    <div class="container">
                                        <label class="col-form-label col-auto fw-bold d-flex justify-content-center">SUBJECTS</label>
                                        <div class="row border overflow-auto">
                                            <ul id="" class="list-group p-0">

                                                <li class="list-group-item">Cras justo odio</li>
                                                <li class="list-group-item">Dapibus ac facilisis in</li>
                                                <li class="list-group-item">Morbi leo risus</li>
                                                <li class="list-group-item">Porta ac consectetur ac</li>
                                                <li class="list-group-item">Vestibulum at eros</li>


                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="container">
                                        <label class="col-form-label col-auto fw-bold d-flex justify-content-center">FACULTY</label>
                                        <div class="row border overflow-auto">
                                            <ul id="" class="list-group p-0">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <!-- FOOTER -->
            <?php include_once("../inc/footer.html"); ?>
            <!-- FOOTER END -->
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
</body>
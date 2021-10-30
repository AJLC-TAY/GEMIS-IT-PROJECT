<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>
<title>Assign Faculty to Subject | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
    <!-- SPINNER 
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
     SPINNER END -->
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
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Assign Faculty</a></li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Assign Faculty to Subjects</h3>
                                </div>
                            </header>
                            <div class="container w-75">
                                <div class="card">
                                    <form>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="container">
                                                    <h6 class=" fw-bold d-flex justify-content-center mb-2">SUBJECTS</h6>
                                                    <div class="row border">
                                                        <label for="" class="col-form-label">EAPP</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="container">
                                                    <h6 class="fw-bold d-flex justify-content-center mb-2">FACULTY</h6>
                                                    <div class="row">
                                                        <select name="month" class="form-select">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
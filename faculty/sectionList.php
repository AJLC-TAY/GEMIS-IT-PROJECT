<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>

<title>Student Profile | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>

<!DOCTYPE html>
<body>
    <!-- SPINNER -->
    <!--<div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> -->
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/facultySidebar.php'); ?>
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
            <li class="breadcrumb-item active">ABM</a></li>
        </ol>
    </nav>
</header>
                            <div class="d-flex justify-content-between mb-3">
                                <h3 class="fw-bold">ABM 12 A</h3>
                                <div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            Select Class
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="#">Advisory</a></li>
                                            <li><a class="dropdown-item" href="#">ABM 12 B</a></li>
                                            <li><a class="dropdown-item" href="#">HUMMS 11 B</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class='container'>
                                <div class="card w-100 h-auto bg-light">
                                    <table id="table" class="table-striped">
                                        <thead class='thead-dark'>
                                            <div class="d-flex justify-content-between mb-3">
                                                <!-- SEARCH BAR -->
                                                <span class="flex-grow-1 me-3">
                                                    <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                                                </span>
                                            </div>

                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="400" data-align="left" data-sortable="true" data-field='name'>Student Name</th>
                                                <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- FOOTER START -->
                        <?php include_once("../inc/footer.html"); ?>
                        <!-- FOOTER END -->
                    </div>
                </div>
            </section>
        </section>
    </section>
    <!-- MAIN CONTENT END -->


    <!--BOOTSTRAP TABLE JS-->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script type='module' src='../js/admin/faculty.js'></script>
</body>

</html>
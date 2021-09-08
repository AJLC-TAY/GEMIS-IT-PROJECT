<?php include_once("../inc/head.html"); ?>
<title>Enrollment Page | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

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
                    <div class="col-lg-10">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item" aria-current="enrollment.php">Enrollment</li>
                                        <li class="breadcrumb-item active" aria-current="page">Enrollees</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Enrollees</h3>
                                    <div>
                                        <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived Enrollees</button>
                                        <a href="faculty.php?action=add" id="add-btn" class="btn btn-success" title='Add new faculty'><i class="bi bi-plus me-2"></i>Enroll</a>
                                        <!-- <a href="faculty.php?state=add" id="add-btn" class="btn btn-success add-prog" title='Add new faculty'>ADD FACULTY</a> -->
                                    </div>
                                </div>
                            </header>
                            <!-- ENROLLEES TABLE -->
                            <div class="container mt-1">
                                <div class="card w-100 h-auto bg-light">
                                    <table id="table" class="table-striped table-sm">
                                        <thead class='thead-dark'>
                                        <div class="d-flex justify-content-between mb-3">
                                            <!-- SEARCH BAR -->
                                            <span class="flex-grow-1 me-3">
                                                <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                                            </span>
                                            <div>
                                                <input id="deactivate-opt" type="submit" form="deactivate-from" class="btn btn-danger btn-sm" title='Deactivate Faculty' value="Archive">
                                                <button id="export-opt" type="submit" class="btn btn-dark btn-sm" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                                            </div>
                                        </div>

                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="false" data-field="SY">SY</th>
                                            <th scope='col' data-width="100" data-halign="center" data-align="left" data-sortable="false" data-field="LRN">LRN</th>
                                            <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="enroll-date">Enrollment Date</th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grade-level">Level</th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="curriculum">Curriculum</th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="status">Status</th>
                                            <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once("../inc/footer.html"); ?>
                <!--footer end-->
            </section>
        </section>
    </section>

 
    <!-- JQUERY FOR BOOTSTRAP TABLE -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!-- CUSTOM JS -->
    <script type='text/javascript' src="../js/common-custom.js"></script>
    <script type="module" src="../js/admin/enrollment-list.js"></script>
</body>
</html>
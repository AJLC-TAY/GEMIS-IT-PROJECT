<?php include_once("../inc/head.html"); 
    session_start();
?>
<title>Faculty Members List | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
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
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Faculty</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3>Faculty Members</h3>
                                    <div>
                                        <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived Faculty</button>
                                        <a href="faculty.php?action=add" id="add-btn" class="btn btn-success" title='Add new faculty'><i class="bi bi-plus me-2"></i>Add Faculty</a>
                                        <!-- <a href="faculty.php?state=add" id="add-btn" class="btn btn-success add-prog" title='Add new faculty'>ADD FACULTY</a> -->
                                    </div>
                                </div>
                            </header>
                            <!-- FACULTY TABLE -->
                            <div class="container mt-1">
                                <div class="card w-100 h-auto bg-light">
                                    <table id="table" class="table-striped">
                                        <thead class='thead-dark'>
                                            <div class="d-flex justify-content-between mb-3">
                                                <!-- SEARCH BAR -->
                                                <span class="flex-grow-1 me-5"> 
                                                    <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                                                </span>
                                                <div>
                                                    <button class="btn btn-danger btn-sm" title='Deactivate Faculty'>Deactivate</button>
                                                    <button class="btn btn-secondary btn-sm" title='Reset Password'>Reset Password</button>
                                                    <button class="btn btn-dark btn-sm" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                                                </div>
                                            </div>

                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field="teacher_id">UID</th>
                                                <th scope='col' data-width="400" data-align="left" data-sortable="true" data-field="name">Name</th>
                                                <th scope='col' data-width="300" data-align="left" data-sortable="true" data-field="department">Department</th>
                                                <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- FACULTY TABLE END -->
                        </div>
                    </div>
                </div>
                <!--MAIN CONTENT END-->
                <!--FOOTER START-->
                <?php include_once("../inc/footer.html"); ?>
                <!--FOOTER END-->
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

    <script src="../js/common-custom.js"></script>
    <script type="module" src="../js/admin/facultylist.js"></script>
</body>
</html>
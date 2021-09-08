<?php include_once("../inc/head.html"); ?>
<title>Enrollment Setup 1 | GEMIS</title>
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
        <!--main content start-->
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
                                        <li class="breadcrumb-item active" aria-current="page">Enrollment Setup 1</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Enrollment Setup 1: Faculty Enrollment Privileges</h3>
                                </div>
                            </header>
                            <!-- FACULTY SETUP TABLE -->
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
                                                    <button class="btn btn-secondary" title=''>Grant Enrollment Access</button>
                                                </div>
                                                
                                            </div>

                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="600" data-align="left" data-sortable="true" data-field="sub_name">Faculty Name</th>
                                                <th scope='col' data-width="100" data-align="center" data-field="sub_type">Can Enroll</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary" title=''>Next</button>
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
    
    <script type='text/javascript' src="../js/common-custom.js"></script>
    <script type="module" src="../js/admin/enrollmentFacultyPriv.js"></script>
</body>
</html>
<?php include_once("../inc/head.html"); ?>
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
                    <div class="col-lg-10">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="facultyList.php">Faculty</a></li>
                                    </ol>
                                </nav>
                                <h2>Faculty Members</h2>
                                <!-- SEARCH BAR -->
                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">

                            </header>
                            <!-- FACULTY TABLE -->
                            <div class="container mt-5">
                                <table id="table" class="table-striped">
                                    <thead class='thead-dark'>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h4>Faculty Members</h4>
                                            <div>
                                                <a href="" id="add-btn" class="btn btn-success">Add Faculty Member</a>
                                                <button class="btn btn-secondary" title='Deactivate Faculty'>Deactivate</button>
                                                <button class="btn btn-secondary" title='Reset Password'>Reset Password</button>
                                                <button class="btn btn-secondary" title='Export'>Export</button>
                                            </div>
                                        </div>

                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field="teacher_id">UID</th>
                                            <th scope='col' data-width="400" data-align="left" data-sortable="true" data-field="name">Name</th>
                                            <th scope='col' data-width="300" data-align="center" data-field="department">Department</th>
                                            <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
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
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px;">
        <div class="position-absolute" style="bottom: 20px; right: 25px;">
            <div class="toast warning-toast bg-danger text-white" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body"></div>
            </div>

            <div class="toast add-toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                    Faculty successfully added
                </div>
            </div>
        </div>
    </div>
    <!-- TOAST END -->
</body>

<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>

<script src="../js/common-custom.js"></script>
<script src="../js/admin/Class.js"></script>
<script src="../js/admin/facultylist.js"></script>

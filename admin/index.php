<?php include_once("../inc/head.html"); ?>
<title>Home | GEMIS</title>
</head>


<?php

?>

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
                            <header class="mb-4">
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active">Home</li>
                                    </ol>
                                </nav>
                                <h3 class="fw-bold">Welcome!</h3>
                            </header>
                            <div class="container row mb-5">
                                <!-- PEOPLE MANAGEMENT -->
                                <section class='col-md-4'>
                                    <h5 class="hw-bold">People Management</h5>
                                    <div class="card-box bg-blue">
                                        <div class="inner">
                                            <h3> 10 </h3>
                                            <p>Admin</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-user-circle" aria-hidden="true"></i>
                                        </div>
                                        <a href="#" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                    <div class="col-lg-12 col-sm-6">
                                        <div class="card-box bg-green">
                                            <div class="inner">
                                                <h3> 20 </h3>
                                                <p>Faculty</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-users" aria-hidden="true"></i>
                                            </div>
                                            <a href="faculty.php" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-sm-6">
                                        <div class="card-box bg-orange">
                                            <div class="inner">
                                                <h3> 300 </h3>
                                                <p> Student </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div>
                                            <a href="student.php" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-sm-6">
                                        <div class="card-box bg-red">
                                            <div class="inner">
                                                <h3> 5 </h3>
                                                <p> Signatory </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </div>
                                            <a href="signatory" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </section>
                                <!-- PEOPLE MANAGEMENT END -->
                                <!-- SCHOOL MANAGEMENT -->
                                <section class='col-md-8'>
                                    <h5 class="hw-bold">School Management</h5>
                                    <div class="card bg-white rounded shadow-sm mt-3">
                                        <!-- CURRICULUM -->
                                        <section class="mb-4">
                                            <h6 class='mb-0'>Curriculum</h6>
                                            <hr class="mt-1 mb-3">
                                            <div class="d-flex flex-wrap">
                                                <a href="schoolyear.php" class="redirect-card py-2 px-3 m-1 rounded shadow text-white btn-danger" role="button">
                                                    School year
                                                </a>
                                                <a href="curriculumlist.php" class="redirect-card py-2 px-3 m-1 rounded shadow text-dark btn-warning" role="button">
                                                    Curriculum
                                                </a>
                                                <a href="programlist.php" class="redirect-card py-2 px-3 m-1 rounded shadow btn-success" role="button">
                                                    Program
                                                </a>
                                                <a href="studentlist.php" class="redirect-card py-2 px-3 m-1 rounded shadow btn-primary" role="button">
                                                    Subject
                                                </a>
                                            </div>
                                        </section>
                                        <!-- CURRICULUM END -->
                                        <!-- ENROLLMENT -->
                                        <section class="mb-4">
                                            <h6 class='mb-0'>Enrollment</h6>
                                            <hr class="mt-1 mb-3">
                                            <div class="d-flex flex-wrap">
                                                <a href="" class="redirect-card py-2 px-3 m-1 rounded shadow text-white btn-danger" role="button">
                                                    Enrollment
                                                </a>
                                                <a href=".php" class="redirect-card py-2 px-3 m-1 rounded shadow text-dark btn-warning" role="button">
                                                    Set Up
                                                </a>
                                                <a href=".php" class="redirect-card py-2 px-3 m-1 rounded shadow btn-success" role="button">
                                                    Section
                                                </a>
                                            </div>
                                        </section>
                                        <!-- ENROLLMENT END -->
                                    </div>
                                </section>
                                <!-- SCHOOL MANAGEMENT END -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--main content end-->
            <!--footer start-->
            <?php include_once("../inc/footer.html"); ?>
            <!--footer end-->
        </section>
    </section>
    <!-- ADD MODAL -->
    <div class="modal" id="add-modal" tabindex="-1" aria-labelledby="modal addProgram" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Add Strand/Program</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="prog-form" action="">
                        <div class="form-group">
                            <label for="prog-code">Strand Code</label>
                            <input id="prog-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. STEM" required>
                            <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique strand code</small></p>
                            <label for="prog-name">Strand Name</label>
                            <input id="prog-name" type="text" name="desc" class='form-control' placeholder="ex. Science, Technology, Engineering, and Math" required>
                            <p class="name-error-msg text-danger m-0 invisible"><small>Please provide the program name</small></p>
                            <label for="prog-curr">Curriculum</label>
                            <input type="text" class='form-control' name="curr-code" value="<?php echo ($curr_code); ?>" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit-prog" form="prog-form" class="submit btn btn-primary" data-link='addProg.php'>Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD MODAL  -->
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript">
    $(function() {
        preload("#home")
        hideSpinner()
    })
</script>

</html>
<?php include_once("../inc/head.html"); ?>
<title>Add Faculty | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

<body>
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper"></section>
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
                                    <li class="breadcrumb-item"><a href="facultyList.php">Add Faculty</a></li>
                                </ol>
                            </nav>
                            <h2>Add Faculty</h2>
                        </header>
                        <!-- MAIN CONTENT -->
                        <form>
                            <div class="form-row row">
                                <div class="form-group col-md-4">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" id="" placeholder="Last Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" id="" placeholder="First Name">
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="form-group col-md-4">
                                    <label>Middle Name</label>
                                    <input type="text" class="form-control" id="" placeholder="Middle Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Extension Name</label>
                                    <input type="text" class="form-control" id="" placeholder="Extension Name">
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="form-group col-md-4">
                                    <label>Cellphone No.</label>
                                    <input type="number" class="form-control" id="" placeholder="Cellphone No.">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Age</label>
                                    <input type="text" class="form-control" id="" placeholder="Age">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Sex</label>
                                    <input type="text" class="form-control" id="" placeholder="Sex">
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="" placeholder="Email">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Department</label>
                                    <select class="form-select form-select">
                                        <option selected disabled hidden>Department</option>
                                        <option value="">...</option>>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="form-group col-md-4">
                                    <label>Faculty Access</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="">
                                        <label class="form-check-label">
                                            Edit Grades
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="">
                                        <label class="form-check-label">
                                            Enrollment
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="">
                                        <label class="form-check-label">
                                            Award Report
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-5">
                                    <label>Faculty ID Photo</label>
                                    <input type="file" class="form-control-file">
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary" data-toggle="modal">Assign Subjects</button>
                        </form>
                        <div class="back-btn d-flex justify-content-end col-md-8">
                            <button type="button" class="btn btn-secondary btn-space">Back</button>
                            <button type="button" class="btn btn-success btn-space save-btn">Save</button>
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

</body>
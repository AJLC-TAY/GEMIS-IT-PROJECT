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
                                <div class="form-group col-md-4">
                                    <label>Middle Name</label>
                                    <input type="text" class="form-control" id="" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="form-group col-md-4">
                                    <label>Extension Name</label>
                                    <input type="text" class="form-control" id="" placeholder="Extension Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Cellphone No.</label>
                                    <input type="number" class="form-control" id="" placeholder="Cellphone No.">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="form-group col-md-2">
                                    <label>Age</label>
                                    <input type="text" class="form-control" id="" placeholder="Age">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Sex</label>
                                    <select class="form-select form-select">
                                        <option selected disabled hidden>Sex</option>
                                        <option value="">Female</option>
                                        <option value="">Male</option>
                                    </select>
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
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label">
                                            Edit Grades
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label">
                                            Enrollment
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox">
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
                            <div class="collapse-table">
                                <input class='btn btn-primary' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='Assign Subject'>
                                <div id='assign-subj-table' class='collapse'>
                                    <div>
                                        <div class="form-row row" style="margin-top:10px">
                                            <!-- To whom it may concern, we can use jQuery to filter / search for something instead of a filter button? -->
                                            <div class="form-group col-md-8">
                                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input class='btn btn-outline-secondary' type='button' value='Clear'>
                                            </div>
                                        </div>
                                        <table class='table table-bordered table-hover table-striped'>
                                            <thead>
                                                <tr class='text-center'>
                                                    <th scope="col"><input type="checkbox" /></th>
                                                    <th scope='col'>CODE</th>
                                                    <th scope='col'>SUBJECT NAME</th>
                                                    <th scope='col'>TYPE</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr class='text-center'>
                                                    <td scope="col"><input type="checkbox" /></td>
                                                    <td scope='col'></td>
                                                    <td scope='col'></td>
                                                    <td scope='col'></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="back-btn d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary btn-space">Back</button>
                                            <button type="button" class="btn btn-success btn-space save-btn">Save</button>
                                        </div>
                                    </div>
                                </div>
                        </form>
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
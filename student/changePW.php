<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
include_once('../inc/studentSideBar.php');
?>
<title>Change Password | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>
<!DOCTYPE html>
<body>

    <section id="container">
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
                                        <li class="breadcrumb-item"><a href="index.php">Profile</a></li>
                                        <li class="breadcrumb-item active">Change Password</a></li>
                                    </ol>
                                </nav>
                            </header>
                            <div class="d-flex justify-content-between">
                                <h3 class="fw-bold">Change Password</h3>
                            </div>

                            <div class="card h-auto bg-light mx-auto mt-3" style='width: 80%;'>
                                <div class='form-row row mb-3'>
                                    <label class="col-sm-3 fw-bold">Old Passsword</label>
                                    <div class="col-sm-9">
                                        <input class='form-input form-control' type='password' required>
                                    </div>
                                    <label class="col-sm-3 fw-bold">New Password</label>
                                    <div class="col-sm-9">
                                        <input class='form-input form-control' type='password' required>
                                    </div>
                                    <label class="col-sm-3 fw-bold">Retype New Password</label>
                                    <div class="col-sm-9">
                                        <input class='form-input form-control' type='password' required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="d-flex justify-content-end">
                                        <input type='hidden' name='action' value=''>
                                        <a href='student.php' class='btn btn-outline-danger me-2'>Cancel</a>
                                        <input type='submit' form='admin-form' class='btn btn-success' value='Save'>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                </div>
            </section>
            <!-- MAIN CONTENT END-->
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
</html>
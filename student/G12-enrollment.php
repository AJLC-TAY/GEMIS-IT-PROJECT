<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
include_once('../inc/studentSideBar.php');
?>
<title>Upcoming Grade 12 Enrollment | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>

    <section id="container">
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <header>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="login.php">Back</a></li>
                                    </ol>
                                </nav>
                            </header>
                            <div class="d-flex justify-content-between">
                                <h3 class="fw-bold">Upcoming Grade 12 Enrollment</h3>
                            </div>

                            <div class="card h-auto bg-light mx-auto mt-3 p-4" style='width: 80%;'>
                                <form>
                                    <div class='form-row row'>
                                        <h4>Will you enroll for grade 12 next school year in PCNHS-SHS?</h4>
                                        <div class="form-check ms-4 mt-2">
                                            <input class="form-check-input" type="radio" name="radio" id="yes">
                                            <label class="form-check-label" for="">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="radio" name="radio" id="no">
                                            <label class="form-check-label" for="">
                                                No
                                            </label>
                                        </div>
                                        <div class='p-4'>
                                            <label>If no, indicate the reason:</label>
                                            <textarea class='form-input form-control'></textarea>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="d-flex justify-content-end">
                                            <input type='hidden' name='action' value=''>
                                            <a href='logout.php' class='btn btn-outline-danger me-2' data-bs-toggle="modal">Cancel</a>
                                            <a href='enrollment.php' type='submit' form='admin-form' class='btn btn-success' value='Submit'>Submit</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </section>
            <!-- MAIN CONTENT END-->
            <!-- FOOTER -->
            <?php include_once("../inc/footer.html"); ?>
            <!-- FOOTER END -->
            <!-- MODAL FOR "NO" -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h4>This account will be Archived once it is submitted. Are you sure you want to proceed?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a href="accountArchived.php" class="btn btn-primary">Yes</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
</body>

</html>
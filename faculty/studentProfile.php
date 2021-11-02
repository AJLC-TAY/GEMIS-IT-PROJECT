<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>

<title>Student Profile | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
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
                                        <li class="breadcrumb-item active">Profile</a></li>
                                    </ol>
                                </nav>
                            </header>
                            <h4 class="my-auto fw-bold">Student Profile</h4>
                            <div class='container my-3'>
                                <div class="card p-3 text-center">
                                    <div class="">
                                        <nav id="myTab">
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-link active" id="nav-gen-info-tab" data-bs-toggle="tab" data-bs-target="#gen-info" type="button" role="tab" aria-controls="gen-info" aria-selected="true">General Information</a>
                                                <a class="nav-link" id="nav-docu-tab" data-bs-toggle="tab" data-bs-target="#docu" type="button" role="tab" aria-controls="docu" aria-selected="false">Enrollment Credentials</a>
                                            </div>
                                        </nav>
                                    </div>
                                    <div class="tab-content" id="myTabContent">
                                        <!-- GENERAL INFORMATION -->
                                        <div class="tab-pane fade bg-white p-4 show active" id="gen-info" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="row w-100 h-auto text-start mx-auto">
                                                <!-- <h5>GENERAL INFORMATION</h5> -->
                                                <!-- <hr> -->
                                                <div class="row p-0">
                                                    <!-- PROFILE PICTURE -->
                                                    <div class="col-xl-3">
                                                        <img src='$image' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'" ?>
                                                        <br>
                                                        <p><span class=" fw-bold">Student LRN: </span></p>
                                                        <a class='transfer-stud btn btn-secondary ms-2 mb-2 w-100'>EDIT STUDENT INFO</a>
                                                        <button class='btn btn-outline-success ms-2 mb-2 w-100 mt-4' title='Reset Password'>ACCEPT ENROLLEE</button>
                                                        <button class='btn btn-outline-danger ms-2 mb-2 w-100' title='Reset Password'>REJECT ENROLLEE</button>
                                                    </div>

                                                    <!-- PROFILE PICTURE END -->
                                                    <!-- INFORMATION DETAILS -->
                                                    <div class="col-xl-7 ms-5">
                                                        <div class="row">
                                                            <h6><b>GENERAL INFORMATION</b></h6>
                                                            <ul class='list-group ms-3'>
                                                                <li class='list-group-item'>Name: <br>
                                                                <li class='list-group-item'>Gender: <br>
                                                                <li class='list-group-item'>Age: <br>
                                                                <li class='list-group-item'>Birthdate: <br>
                                                                <li class='list-group-item'>Birth Place: <br>
                                                                <li class='list-group-item'>Indeginous Group: <br>
                                                                <li class='list-group-item'>Mother Tongue: <br>
                                                                <li class='list-group-item'>Religion:</li>
                                                            </ul>
                                                        </div>
                                                        <div class="row mt-4">
                                                            <h6><b>CONTACT INFORMATION</b></h6>
                                                        </div>

                                                        <div class="row mt-2">
                                                            <h6><b>Contact Persons</b></h6>
                                                            <h6>PARENT/S</h6>
                                                            <ul class='list-group ms-3'>
                                                                <li class='list-group-item'>Parent's Name: </li>
                                                                <li class='list-group-item'>Occupation: </li>
                                                                <li class='list-group-item'>Contact Number: </li>
                                                            </ul>
                                                            <h6 class='mt-3'>GUARDIAN/S</h6>
                                                            <ul class='list-group ms-3'>
                                                                <li class='list-group-item'>Guardian's Name:</li>
                                                                <li class='list-group-item'>Relationship:</li>
                                                                <li class='list-group-item'>Contact Number:</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- DOCUMENTS TAB -->
                                        <div class="tab-pane fade bg-white p-4" id="docu" role="tabpanel" aria-labelledby="docu-tab">
                                            <div class="row w-100 h-auto text-start mx-auto">
                                                <div class="row p-0">
                                                    <div class="row">
                                                        <div class="col-md-4 card">
                                                            <div class="thumbnail">
                                                                <div class="img-title">
                                                                    <p class="fw-bold text-center">PSA DOCUMENT</p>
                                                                </div>
                                                                <!-- KES DITO MO PO ILAGAY HAHAH -->
                                                                <a href="" class="">
                                                                    <img id="imageresource" src="../assets/psa_preview.jpg" class="img-responsive" alt="PSA document" style="width:100%">
                                                                </a>
                                                                <div class="caption mt-1">
                                                                    <small>Date Uploaded: </small><br>
                                                                    <small>Status: <span class="badge badge-pill bg-warning">Pending</span> </small>
                                                                </div>

                                                                <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Image preview</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <img id="imageresource" src="../assets/psa_preview.jpg" class="img-responsive" alt="PSA document" style="width:100%">
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 card ms-4">
                                                            <div class="thumbnail">
                                                                <div class="img-title">
                                                                    <p class="fw-bold text-center">FORM 138</p>
                                                                </div>
                                                                <!-- KES DITO MO PO ILAGAY HAHAH -->
                                                                <a href="" class="">
                                                                    <img id="imageresource" src="../assets/psa_preview.jpg" class="img-responsive" alt="PSA document" style="width:100%">
                                                                </a>
                                                                <div class="caption mt-1">
                                                                    <small>Date Uploaded: </small><br>
                                                                    <small>Status: <span class="badge badge-pill bg-warning">Pending</span> </small>
                                                                </div>

                                                                <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                                                <h4 class="modal-title" id="myModalLabel">Image preview</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <img id="imageresource" src="../assets/psa_preview.jpg" class="img-responsive" alt="PSA document" style="width:100%">
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FOOTER START -->
                    <?php include_once("../inc/footer.html"); ?>
                    <!-- FOOTER END -->
            </section>
        </section>
    </section>
    <!-- MAIN CONTENT END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0; right: 0;">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 9999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->

    <!--BOOTSTRAP TABLE JS-->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script type='module' src='../js/admin/faculty.js'></script>
</body>

</html>
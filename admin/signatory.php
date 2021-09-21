<?php
session_start();
include_once("../inc/head.html");
require_once("../class/Administration.php");
$admin = new Administration();

$administrators = $admin->listAdministrators();
$faculty = $admin->listFaculty();


?>
<title>Admin | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
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
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item">Signatory</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Signatory List</h3>
                                    <div>
                                        <button data-action="Add" class="btn btn-success show-modal" data-bs-toggle="modal" data-bs-target="#modal-form"><i class="bi bi-plus me-2"></i>Add Signatory</button>
                                    </div>
                                </div>
                            </header>
                            <!-- HEADER END -->
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
                                                    <button id="delete-signatory" class="btn btn-sm btn-outline-danger table-opt"><i class="bi bi-trash me-2"></i>Delete</button>
                                                </div>
                                            </div>
                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="100" data-align="center" data-field="sign_id">Sign ID</th>
                                                <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="years">Years</th>
                                                <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                                                <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="position">Position</th>
                                                <th scope='col' data-width="100" data-align="center" data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
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
    <!--MODAL-->
    <div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0"></h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="signatory-form" method="POST">
                        <input type="hidden" name="action" value="">
                        <p class="text-secondary"><small>Please complete the following</small></p>
                        <div class="container">
                            <div class="row align-content-center needs-validation" novalidate>
                                <label class="col-form-label col-4" for="last-name">Last Name</label>
                                <div class="col-8">
                                    <input id="last-name" name="last-name" type="text" class="form-control-sm form-control" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="row align-content-center needs-validation" novalidate>
                                <label class="col-form-label col-4" for="first-name">First Name</label>
                                <div class="col-8">
                                    <input id="first-name" name="first-name" type="text" class="form-control-sm form-control" placeholder="First Name">
                                </div>
                            </div>
                            <div class="row align-content-center needs-validation" novalidate>
                                <label class="col-form-label col-4" for="middle-name">Middle Name</label>
                                <div class="col-8">
                                    <input id="middle-name" name="middle-name" type="text" class="form-control-sm form-control" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="row align-content-center needs-validation" novalidate>
                                <label class="col-form-label col-4" for="academic-degree">Academic degree</label>
                                <div class="col-8">
                                    <input id="academic-degree" name="academic-degree" type="text" class="form-control-sm form-control" placeholder="Academic Degree">
                                </div>
                            </div>
                            <br>
                            <div class="row needs-validation" novalidate>
                                <label class="col-form-label col-4" for="position">Position</label>
                                <div class="col-8">
                                    <input id="position" class="form-control form-control-sm mb-0" type="text" name="position" placeholder="Position">
                                    <div class="invalid-input">
                                        Please provide position
                                    </div>
                                </div>
                            </div>
                            <div class="row align-content-center needs-validation" novalidate>
                                <label class="col-form-label col-4" for="years">Years (Start-End)</label>
                                <div  id="years" class="col-8 row m-0">
                                    <div class="col-5 p-0">
                                        <input id="start-year" name="start-year" type="text" class="number form-control-sm form-control" placeholder="Start year">
                                    </div>
                                    <div class="col-2 text-center">
                                        <p class="m-0"> - </p>
                                    </div>
                                    <div class="col-5 p-0">
                                        <input id="end-year" name="end-year" type="text" class="number form-control-sm form-control" placeholder="End year">
                                    </div>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-dark btn-sm close-btn" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" name="delete" form="signatory-form" class="btn btn-danger btn-sm" value="Delete">
                    <button type="submit" id="submit-again" class="btn btn-secondary btn-sm">Submit and add again</button>
                    <input type="submit" name="submit" form="signatory-form" class="btn btn-primary btn-sm" value="Add">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-view" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">View Signatory Details</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <div class="row mb-3">
                            <label class='col-form-label col-4'for="id-no-view">ID No</label>
                            <div class="col-8">
                                <input id="id-no-view" type="text" class="form-control form-control-sm mb-0" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class='col-form-label col-4'for="name-view">Name</label>
                            <div class="col-8">
                                <input id="name-view" class="form-control form-control-sm mb-0" type="text" value="" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class='col-form-label col-4'for="position-view">Position</label>
                            <div class="col-8">
                                <input id="position-view" class="form-control form-control-sm mb-0" type="text" name="" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class='col-form-label col-4'for="years-view">Years</label>
                            <div class="col-8">
                                <input id="years-view" class="form-control form-control-sm mb-0" type="text" name="" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-dark btn-sm close-btn" data-bs-dismiss="modal">Close</button>
                    <button data-action='Edit' data-bs-toggle="modal" data-bs-target="#modal-form" class="show-modal edit-btn btn btn-primary btn-sm">Edit</button>
                </div>
            </div>
        </div>
    </div>
    <!--MODAL END-->

    <!-- BOOTSTRAP TABLE JS -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script type="module" src="../js/admin/signatory.js"></script>
</body>

</html>
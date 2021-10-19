<?php
session_start();
$_SESSION['user_type'] = 'FA';
$_SESSION['id'] = 1;
$_SESSION['sy_id'] = 15;
$_SESSION['sy_desc'] = '2021 - 2022';
$_SESSION['enrollment'] = 0;
$_SESSION['roles'] = ['can_enroll', 'award_coor'];
include_once("../inc/head.html");
?>

<title>Awards | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>

<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Awards</a></li>
        </ol>
    </nav>
</header>

<body>
    <!-- SPINNER -->
    <!--<div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> -->
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <div class="d-flex justify-content-between">
                                <h3 class="fw-bold">Awards</h3>
                                <span>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-modal"></i>Award Parameters</button>
                                    <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived</button>
                                </span>
                            </div>
                            <!-- SEARCH BAR -->
                            <div class="d-flex justify-content-between mb-1 mt-4">
                                <!-- SEARCH BAR -->
                                <span class="flex-grow-1 me-3">
                                    <input id="search-input" type="search" class="form-control form-control" placeholder="Search something here">
                                </span>
                                <div>
                                    <button class="btn btn-outline-primary me-1" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse"><i class="bi bi-funnel me-2"></i>Filter</button>
                                </div>
                            </div>
                            <!--FILTER-->
                            <div class="collapse" id="filterCollapse">
                                <div class="card">
                                    <ul id="" class="row flex-wrap">
                                        <li class="col-3 mb-3 me-2">
                                            <div class="input-group input-group-sm">
                                                <label class="input-group-text " for="sy">School Year</label>
                                                <select class="filter-item form-select mb-0" id="sy">
                                                    <option value="*" selected>All</option>
                                                    <?php
                                                    foreach ($filters['school_year'] as $id => $value) {
                                                        echo "<option value='$id' >$value</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </li>
                                        <!--SEMESTER FILTER-->
                                        <li class="col-3 me-2">
                                            <div class="input-group input-group-sm ">
                                                <label class="input-group-text " for="status">Semester</label>
                                                <select class="form-select mb-0 filter-item " id="sem">
                                                    <option value="*" selected>All</option>
                                                    <option value=''>1st Semester</option>
                                                </select>
                                            </div>
                                        </li>
                                        <li class="col-3 me-2">
                                            <button type="button" class="reset-filter-btn btn btn-outline-danger btn-sm me-5"><i class="bi bi-x-circle me-2"></i>Reset All</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="content">
                                <!-- NO RESULTS MESSAGE -->
                                <div class="no-result-msg-con w-100 d-flex justify-content-center">
                                    <p class="no-result-msg" style="display: none; margin-top: 20vh;">No results found</p>
                                </div>
                                <!--CARDS-->
                                <div class="ms-4 me-3">
                                    <ul class="cards-con d-flex flex-wrap container mt-4 h-auto" style="min-height: 75vh;">

                                    </ul>
                                    <!-- TEMPLATE -->
                                    <template id="card-template">
                                        <div data-id='' class='tile card shadow-sm p-0 mb-4 position-relative'>
                                            <a role='button' class='card-link btn btn-link start-0 top-0 end-0 bottom-0 h-100' style='z-index: 2;' href=''></a>
                                            <div class='dropstart position-absolute top-0 end-0' style='z-index: 3;'>
                                                <button type='button' class='btn kebab rounded-circle m-1' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button>
                                                <ul class='dropdown-menu' style='z-index: 99;'>
                                                    <li><a class='dropdown-item' href=''>Edit</a></li>
                                                    <li><button data-name='' class='export-option dropdown-item' id=''>Export</button></li>
                                                    <li><button data-name='' class='archive-option dropdown-item' id=''>Archive</button></li>
                                                    <li><button data-name='' class='delete-option dropdown-item' id=''>Delete</button></li>
                                                </ul>
                                            </div>
                                            <div class='card-body position-absolute d-flex-column justify-content-between start-0' style='top: 40px;'>
                                                <div class='tile-content'>
                                                    <h4 class='card-title text-break'></h4>
                                                    <p class='card-text text-break'></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- TEMPLATE END -->
                                    <!-- ARCHIVE MODAL -->
                                    <div class="modal fade" id="archive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div class="modal-title">
                                                        <h4 class="mb-0">Confirmation</h4>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>Do you want to archive <span id="modal-identifier"></span>?</h5>
                                                    <p class="modal-msg"></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                                                    <button class="btn btn-primary close-btn archive-btn">Archive</button>
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
                </div>
            </section>
        </section>
    </section>
    <!-- MAIN CONTENT END -->

    <!--BOOTSTRAP TABLE JS-->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script type='module' src='../js/admin/faculty.js'></script>
</body>

</html>
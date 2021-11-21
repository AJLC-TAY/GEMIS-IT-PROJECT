<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>

<title>Archived Classes | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>
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
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Archived Classes</a></li>
                                    </ol>
                                </nav>
                            </header>
                            <div class="d-flex justify-content-between">
                                <h3 class="fw-bold">Archived Classes</h3>
                            </div>
                            <!-- SEARCH BAR -->
                            <div class="d-flex justify-content-between mb-1 mt-4">
                                <!-- SEARCH BAR -->
                                <span class="flex-grow-1 me-3">
                                    <input id="search-input" type="search" class="form-control form-control" placeholder="Search something here">
                                </span>
                            </div>
                            <!--FILTER-->
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
                                                    <li><button data-name='' class='archive-option dropdown-item' id=''>Unarchive</button></li>
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
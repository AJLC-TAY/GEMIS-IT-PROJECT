<?php include_once("../inc/head.html"); ?>
<title>Section | GEMIS</title>
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

                            <!-- TABLE TEMPLATE -->
                            <div id="toolbar" class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold">NAME LIST</h5>
<!--                                <a href="#add-modal" id="add-btn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="bi bi-plus me-2"></i>Add strand</a>-->
                            </div>
                            <hr class="mt-1 mb-4">
                            <div class="d-flex flex-row-reverse mb-3">
                                <!-- <div class="d-flex mb-3"> -->
                                <button id="track-archive-btn" class="btn btn-secondary btn-sm"><i class="bi bi-archive me-2"></i>Archive</button>
                            </div>
                            <table id="table" data-toolbar="#toolbar" class="table-striped table-sm">
                                <thead class='thead-dark track-table'>

                                    <tr>
                                        <th data-checkbox="true"></th>
                                        <th scope='col' data-width="100" data-align="center" data-field='prog_code'>Code</th>
                                        <th scope='col' data-width="600" data-align="center" data-sortable="true" data-field="prog_desc">Program/Strand Description</th>
                                        <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                                    </tr>
                                </thead>
                            </table>
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

    <!-- BOOTSTRAP TABLE JS -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>

</body>

</html>
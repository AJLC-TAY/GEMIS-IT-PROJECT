<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
require_once("../class/Administration.php");
$admin = new Administration();

$administrators = $admin->listAdministrators();
$faculty = $admin->listFaculty();


?>
<title>System Logs | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
<!DOCTYPE html>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/adminSidebar.php'); ?>
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
                                        <li class="breadcrumb-item">System Logs</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">System Logs</h3>
<!--                                    <div>-->
<!--                                        <button data-action="Add" class="btn btn-success show-modal" data-bs-toggle="modal" data-bs-target="#modal-form"><i class="bi bi-plus me-2"></i>Add Signatory</button>-->
<!--                                    </div>-->
                                </div>
                            </header>
                            <!-- HEADER END -->
                            <div class="container mt-1">
                                <div class="card w-100 h-auto bg-light">
                                    <div class="d-flex justify-content-between mb-3">
                                        <!-- SEARCH BAR -->
                                        <span class="flex-grow-1 me-3">
                                            <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                                        </span>
<!--                                        <div>-->
<!--                                            <button id="delete-signatory" class="btn btn-sm btn-outline-danger table-opt"><i class="bi bi-trash me-2"></i>Delete</button>-->
<!--                                        </div>-->
                                    </div>
                                    <table id="table" class="table-striped table-sm">
                                        <thead class='thead-dark'>
                                            <tr>
<!--                                                <th data-checkbox="true"></th>-->
                                                <th scope='col' data-width="100" data-align="center" data-field="log_id">Log ID</th>
                                                <th scope='col' data-width="100" data-align="center" data-field="id_no">UID</th>
                                                <th scope='col' data-width="100" data-align="center" data-field="name">User Name</th>
                                                <th scope='col' data-width="100" data-halign="center" data-align="center" data-sortable="true" data-field="user_type">User Type</th>
                                                <th scope='col' data-width="500" data-halign="center" data-align="left" data-field="action">Action</th>
                                                <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="datetime">Date Time</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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

    <!-- BOOTSTRAP TABLE JS -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script>
        const tableSetup = {
            search: true,
            autoRefresh: true,
            showRefresh: true,
            showAutoRefresh: true,
            autoRefreshInterval: 10,
            searchSelector: "#search-input",
            url: "getAction.php?data=systemLogs",
            buttonsToolbar: ".buttons-toolbar",
            sidePagination: "server",
            // uniqueId: "LRN",
            // idField: "LRN",
            // queryParams: queryParams,
            toggle: "#toolbar",
            height: 880,
            maintainMetaDat: true, // set true to preserve the selected row even when the current table is empty
            clickToSelect: true,
            pageSize: 25,
            pagination: true,
            pageList: "[25, 50, 100, All]",
            // responseHandler:         responseHandler
        };
        let systemLogs = $('#table').bootstrapTable(tableSetup);
        $(function () {
            preload("#system-logs");

            hideSpinner();
        });
    </script>
</body>

</html>
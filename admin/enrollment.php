<?php include_once("../inc/head.html"); ?>
<title>Enrollment | GEMIS</title>
<link rel="stylesheet" href="../css/general.css">
</link>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
</head>


<body>
    <?php include('../class/Administration.php');
    $admin = new Administration();
    ?>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->

    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Enrollment</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Enrollees</h3>
                                    <div>
                                        <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived Enrollees</button>
                                        <a href="faculty.php?action=add" id="add-btn" class="btn btn-success" title='Add new faculty'><i class="bi bi-plus me-2"></i>Enroll</a>
                                        <!-- <a href="faculty.php?state=add" id="add-btn" class="btn btn-success add-prog" title='Add new faculty'>ADD FACULTY</a> -->
                                    </div>
                                </div>
                            </header>
                            <!-- ENROLLEES TABLE -->
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
                                                <input id="deactivate-opt" type="submit" form="deactivate-from" class="btn btn-danger btn-sm" title='Deactivate Faculty' value="Archive">
                                                <button id="export-opt" type="submit" class="btn btn-dark btn-sm" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                                            </div>
                                        </div>

                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="false" data-field="SY">SY</th>
                                            <th scope='col' data-width="100" data-halign="center" data-align="left" data-sortable="false" data-field="LRN">LRN</th>
                                            <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="enroll-date">Enrollment Date</th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grade-level">Level</th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="curriculum">Curriculum</th>
                                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="status">Status</th>
                                            <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- MODAL -->
                            <div id="deactivate-modal" class="modal fade" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="modal-title">
                                                <h4 class="mb-0">Confirmation</h4>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Deactivate <span id="question"></span><br>
                                            <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </small>
                                        </div>
                                        <div class="modal-footer">
                                            <form id="deactivate-form" action="action.php">
                                                <input type="hidden" name="action" value="deactivate"/>
                                                <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                                                <input type="submit" form="deactivate-form" class="submit btn btn-danger" value="Deactivate">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- MODAL END -->

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
</body>

<!-- BOOTSTRAP TABLE JS -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<!--CUSTOM JS-->
<script src="../js/common-custom.js"></script>
<?php //include "../class/Administration.php";
//$admin = new Administration();
//?>
<script >
    preload("#enrollment", "#enrollment-sub")
    //data:                <?php //echo json_encode($admin->getEnrollees());?>//,
    const tableSetup = {
        url:                'getAction.php?data=enrollees',
        method:             'GET',
        uniqueId:           'LRN',
        idField:            'LRN',
        height:             440,
        maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
        clickToSelect:      true,
        pageSize:           20,
        pagination:         true,
        pageList:           "[20, 40, 80, 100, All]",
        paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
        search:             true,
        searchSelector:     '#search-input'
    }
    let enrolleesTable = $('#table').bootstrapTable(tableSetup)
    $(function() {
        hideSpinner()
    })
</script>


</html>
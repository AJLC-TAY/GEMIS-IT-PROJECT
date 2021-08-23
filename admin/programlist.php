<?php include_once("../inc/head.html"); ?>
<title>Program Page | GEMIS</title>
<link rel="stylesheet" href="../css/general.css">
</link>
</head>

<body>
    <?php include('../class/Administration.php');
    $admin = new Administration();

    require_once('../class/Dataclasses.php');
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
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Programs</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between">
                                    <h2>Programs</h2>
                                    <span>
                                        <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived</button>
                                    </span>
                                </div>
                                <!-- SEARCH BAR -->
                                <input id="search-input" type="search" class="form-control search" placeholder="Search something here">
                            </header>
                            <!-- No result message -->
                            <div class="msg w-100 d-flex justify-content-center d-none">
                                <p class="m-auto">No results found</p>
                            </div>
                            <div class="cards-con d-flex flex-wrap container mt-4 h-auto" style="min-height: 75vh;">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once("../inc/footer.html"); ?>
                <!--footer end-->
            </section>
        </section>
    </section>
    <!-- TOAST -->
     <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->

    <!-- MODAL -->
    <div class="modal" id="add-modal" tabindex="-1" aria-labelledby="modal addProgram" aria-hidden="true">
        <div class="modal-dialog">
            <form id="program-form" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Add Program</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><small class='text-secondary'>Please complete the following: </small></p>
                        <div class="form-group">
                            <label for="prog-code">Code</label>
                            <input id="prog-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. ABM" required>
                            <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique program code</small></p>
                            <label for="prog-desc">Description</label>
                            <input id="prog-name" type="text" name="desc" class='form-control' placeholder="ex. Accountancy, Business, and Management" required>
                            <p class="name-error-msg text-danger m-0 invisible"><small>Please provide a unique program description</small></p>
                            <label for="curr-code">Curriculum</label>
                            <select id="curr-code" class="select form-select" name="curr-code">
                                <option value="0" selected>Select...</option>
                                <?php $currList = $admin->listCurriculum('curriculum');
                                    foreach ($currList as  $cur) {
                                        $curr_code = $cur->get_cur_code();
                                        $curr_name = $cur->get_cur_name();
                                        echo "<option value='$curr_code'> $curr_code | $curr_name </option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="addProgram" />
                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                        <input type="submit" form="program-form" class="submit btn btn-primary" value="Add" />
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="archive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog">
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
                    <button class="btn btn-primary archive-btn">Archive</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="unarchive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Do you want to unarchive <span id="modal-identifier"></span>?</h5>
                    <p class="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary close-btn unarchive-btn">Unarchive</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="view-arch-modal" tabindex="-1" aria-labelledby="modal viewArchivedProgram" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Archived Programs</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="overflow-auto" style="height: 50vh;">

                        <ul class="list-group arch-list">
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="delete-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Do you want to delete <span class="modal-identifier"></span>?</h5>
                    <p class="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger close-btn delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Scripts -->
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript" src="../js/admin/cardPage.js"></script>
<script type="text/javascript" src="../js/admin/programlist.js"></script>

</html>

<?php include_once("../inc/head.html"); ?>
<title>Curriculum Page | GEMIS</title>
<link rel="stylesheet" href="../css/general.css"></link>
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
        <?php include_once ('../inc/admin/sidebar.html'); ?>
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
                                        <li class="breadcrumb-item active" aria-current="page">Curriculum</li>
                                    </ol>
                                </nav>
                                <h2>Curriculum</h2>
                                <!-- SEARCH BAR -->
                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                            </header>
                            <!-- No result message -->
                            <div class="msg w-100 d-flex justify-content-center d-none">
                                <p class="m-auto">No results found</p>
                            </div>
                            <div class="cards-con d-flex flex-wrap container">
                
                            </div>
                            <button type="button" class="view-archive btn btn-link">View Archived Curriculums</button>
                        </div>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once ("../inc/footer.html");?>
                <!--footer end-->
            </section>
        </section>
    </section>
        <!-- MODAL -->
        <div class="modal" id="add-modal" tabindex="-1" aria-labelledby="modal addCurriculum" aria-hidden="true">
            <div class="modal-dialog">
                <form id="curriculum-form" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 class="mb-0">Add Curriculum</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Please complete the following:</h6>
                            <div class="form-group">
                                <label for="curr-code">Code</label>
                                <input id="curr-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. K12A" required>
                                <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique curriculum code</small></p>
                                <label for="curr-name">Name</label>
                                <input id="curr-name" type="text" name="name" class='form-control' placeholder="ex. K12 Academic" required>
                                <p class="name-error-msg text-danger m-0 invisible"><small>Please provide a curriculum name</small></p>
                                <label for="curr-desc">Short Description</label>
                                <textarea name="curriculum-desc" class='form-control' maxlength="250" placeholder="ex. K-12 Basic Education Academic Track"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="action" id="action" value="addCurriculum"/>
                            <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                            <input type="submit" form="curriculum-form" class="submit btn btn-primary" value="Add"/>
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
                        <button class="btn btn-primary close-btn">Archive</button>
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

        <div class="modal" id="view-arch-modal" tabindex="-1" aria-labelledby="modal viewArchivedCurriculum" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Archived Curriculums</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="overflow-auto" style="height: 50vh;">

                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Junior High School
                                    <button class="btn btn-link">Unarchive</button>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Junior High School
                                    <button class="btn btn-link">Unarchive</button>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Junior High School
                                    <button class="btn btn-link">Unarchive</button>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Junior High School
                                    <button class="btn btn-link">Unarchive</button>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Junior High School
                                    <button class="btn btn-link">Unarchive</button>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Junior High School
                                    <button class="btn btn-link">Unarchive</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOAST -->
        <div aria-live="polite" aria-atomic="true" class="position-relative" style="min-height: 200px;">
            <div class="position-absolute" style="bottom: 20px; right: 25px;">
                <div class="toast warning-toast bg-danger text-white" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body"></div>
                </div>

                <div class="toast add-toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body">
                        Curriculum successfully added
                    </div>
                </div>
            </div>
        </div>
</body>

<!-- <script type="text/javascript" src="../js/admin/curriculumlist.js"></script> -->
<script type="text/javascript" src="../test/admin/Classes.js"></script>
<script type="text/javascript" src="../test/admin/curriculumlist.js"></script>
</html>
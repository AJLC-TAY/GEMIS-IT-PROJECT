<?php include_once("../inc/head.html"); ?>
<title>Program Page | GEMIS</title>
</head>

<body>
    <?php include('../class/Administration.php');
    $admin = new Administration();

    require_once('../class/Dataclasses.php');
    ?>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!--main content start-->
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
                                        <li class="breadcrumb-item active" aria-current="page">Programs</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between">
                                    <h3 class="fw-bold">Programs</h3>
                                    <span>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="bi bi-plus me-2"></i>Add Program</button>
                                        <button type="button" class="view-archive btn btn-secondary" data-bs-toggle="modal" data-bs-target="#view-arch-modal"><i class="bi bi-eye me-2"></i>View Archived</button>
                                    </span>
                                </div>
                                <!-- SEARCH BAR -->
                                <input id="search-input" type="search" class="form-control search" placeholder="Search something here">
                            </header>

                            <div class="content">
                                <!-- NO RESULTS MESSAGE -->
                                <div class="w-100 d-flex justify-content-center">
                                    <p class="no-result-msg" style="display: none; margin-top: 20vh;">No results found</p>
                                </div>
                                <!-- SUB SPINNER -->
                                <div id="program-spinner" class="sub-spinner" style="display: none; height: 60vh;">
                                    <div class="spinner-con h-100 position-relative">
                                        <div class="spinner-border position-absolute top-0 start-0 bottom-0 end-0 m-auto" style="margin: auto !important;" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="container ms-4 me-3">
                                    <ul data-page="program" class="cards-con d-flex flex-wrap mt-2 h-auto" style="min-height: 75vh;">
                                        <!-- TEMPLATE -->
                                        <template id="card-template">
                                            <li data-id='%PROGCODE%' class='tile card shadow-sm p-0 mb-4 position-relative'>
                                                <a role='button' class='card-link btn btn-link start-0 top-0 end-0 bottom-0 h-100' style='z-index: 2;' href='program.php?prog_code=%PROGCODE%'></a>
                                                <div class='dropstart position-absolute top-0 end-0' style='z-index: 3;'>
                                                    <button type='button' class='btn kebab rounded-circle m-1' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button>
                                                    <ul class='dropdown-menu' style='z-index: 99;'>
                                                        <li><a class='dropdown-item' href='program.php?state=edit&prog_code=%PROGCODE%'>Edit</a></li>
                                                        <li><button data-name='%PROGDESC%' class='archive-option dropdown-item' id='%PROGCODE%'>Archive</button></li>
                                                        <li><button data-name='%PROGDESC%' class='delete-option dropdown-item' id='%PROGCODE%'>Delete</button></li>
                                                    </ul>
                                                </div>
                                                <div class='card-body position-absolute d-flex-column justify-content-between start-0' style='top: 40px;'>
                                                    <div class='tile-content'>
                                                        <h4 class='card-title'>%PROGDESC%</h4>
                                                        <p class='card-text'>%CURCODE% | %PROGCODE%</p>
                                                    </div>
                                                </div>
                                            </li>
                                        </template>
                                        <!-- TEMPLATE END -->
                                    </ul>
                                </div>
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
    <div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="modal addProgram" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Add Program</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="program-form" class="needs-validation" method="post" novalidate>
                            <input type="hidden" name="action" id="action" value="addProgram" />
                            <p><small class='text-secondary'>Please complete the following: </small></p>
                            <div class="form-group">
                                <label for="prog-code">Code</label>
                                <input id="prog-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. ABM" required>
                                <div class="invalid-feedback">
                                    Please enter program code
                                </div>
                                <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique program code</small></p>
                                <label for="prog-desc">Description</label>
                                <input id="prog-name" type="text" name="desc" class='form-control' placeholder="ex. Accountancy, Business, and Management" required>
                                <div class="invalid-feedback">
                                    Pleae enter program name
                                </div>
                                <p class="name-error-msg text-danger m-0 invisible"><small>Please provide a unique program description</small></p>
                                <label for="curr-code">Curriculum</label>
                                <select id="curr-code" class="select form-select" name="curr-code">
                                    <option value="0" selected>-- Select curriculum --</option>
                                    <?php $currList = $admin->listCurriculum('curriculum');
                                    foreach ($currList as  $cur) {
                                        $curr_code = $cur->get_cur_code();
                                        $curr_name = $cur->get_cur_name();
                                        echo "<option value='$curr_code'> $curr_code | $curr_name </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                        <input type="submit" form="program-form" class="submit btn btn-primary" value="Add" />
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                    <button class="btn btn-primary archive-btn">Archive</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="unarchive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
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

    <div class="modal fade" id="view-arch-modal" tabindex="-1" aria-labelledby="modal viewArchivedProgram" aria-hidden="true">
        <div class="modal-dialo modal-dialog-centered">
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

    <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
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
<!-- VALIDATION -->
<script>
    var forms = document.querySelectorAll('.needs-validation');

    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
</script>
<script type="text/javascript">
    let programs = <?php $admin->listProgramsJSON(); ?>;
</script>
<script type="module" src="../js/admin/programs.js"></script>

</html>
<?php include_once("../inc/head.html"); ?>
<title>Curriculum List | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
<?php
include('../class/Administration.php');
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
    <?php include_once('../inc/admin/sidebar.php'); ?>
    <!-- MAIN CONTENT START -->
    <section id="main-content">
        <section class="wrapper ps-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../admin/index.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Curriculum</li>
                                </ol>
                            </nav>
                            <div class="d-flex justify-content-between">
                                <h3 class="fw-bold">Curriculum</h3>
                                <span>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="bi bi-plus me-2"></i>Add Curriculum</button>
                                    <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived</button>
                                </span>
                            </div>
                        </header>

                        <div class="toolbar">
                            <!-- SEARCH BAR -->
<!--                            <div class="d-flex w-100">-->
<!--                                <input id="search-input" type="search" class="form-control search" placeholder="Search something here">-->
<!---->
<!--                            </div>-->
                        </div>
                        <table id="table">
                            <thead>
                            <tr>
                                <th data-field="cur_code">Code</th>
                                <th data-field="cur_name">Name</th>
                                <th data-field="cur_desc">Description</th>
                            </tr>
                            </thead>
                        </table>

                        <!-- No result message -->
<!--                        <div class="msg w-100 d-flex justify-content-center d-none">-->
<!--                            <p class="m-auto">No results found</p>-->
<!--                        </div>-->
<!--                        <ul class="cards-con d-flex flex-wrap container mt-4 h-auto" style="min-height: 75vh;">-->
<!---->
<!--                        </ul>-->


                    </div>
                </div>
            </div>
            <!--MAIN CONTENT END-->
            <!--FOOTER START-->
            <?php include_once("../inc/footer.html"); ?>
            <!--FOOTER END-->
        </section>
    </section>
</section>
<!-- TEMPLATE -->
<template id="card-template">
    <div data-id='%CODE%' class='tile card shadow-sm p-0 position-relative'>
        <a role='button' class='card-link btn btn-link start-0 top-0 end-0 bottom-0 h-100' style='z-index: 2;' href='../admin/curriculum.php?code=%CODE%'></a>
        <div class='dropstart position-absolute top-0 end-0' style='z-index: 3;'>
            <button type='button' class='btn kebab rounded-circle m-1' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button>
            <ul class='dropdown-menu' style='z-index: 99;'>
                <li><a class='dropdown-item' href='../admin/curriculum.php?code=%CODE%&state=edit'>Edit</a></li>
                <li><button data-name='%NAME%' class='archive-option dropdown-item' id='%CODE%'>Archive</button></li>
                <li><button data-name='%NAME%' class='delete-option dropdown-item' id='%CODE%'>Delete</button></li>
            </ul>
        </div>
        <div class='card-body position-absolute d-flex-column justify-content-between start-0' style='top: 40px;'>
            <div class='tile-content'>
                <h4 class='card-title text-break'>%NAME%</h4>
                <p class='card-text text-break'>%DESC%</p>
            </div>
        </div>
    </div>
</template>
<!--TEMPLATE END-->
<!-- MODAL -->
<!-- ADD MODAL -->
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
                    <p><small class='text-secondary'>Please complete the following: </small></p>
                    <div class="form-group">
                        <label for="curr-code">Code</label>
                        <input id="curr-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. K12A" required />
                        <div>
                            Please enter classcode
                        </div>
                        <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique curriculum code</small></p>
                        <label for="curr-name">Name</label>
                        <input id="curr-name" type="text" name="name" class='form-control' placeholder="ex. K12 Academic" required>
                        <p class="name-error-msg text-danger m-0 invisible"><small>Please provide a curriculum name</small></p>
                        <label for="curr-desc">Short Description</label>
                        <textarea name="curriculum-desc" class='form-control' maxlength="250" placeholder="ex. K-12 Basic Education Academic Track"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" id="action" value="addCurriculum" />
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <input type="submit" form="curriculum-form" class="submit btn btn-primary" value="Add" />
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ARCHIVE MODAL -->
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
                <button class="btn btn-primary close-btn archive-btn">Archive</button>
            </div>
        </div>
    </div>
</div>
<!-- DELETE MODAL -->
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
<!-- VIEW ARCHIVED MODAL -->
<div class="modal" id="view-arch-modal" tabindex="-1" aria-labelledby="modal viewArhivedCurriculum" aria-hidden="true">
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
<!-- UNARCHIVE MODAL -->
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
<!-- TOAST -->
<div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
    <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
</div>
<!-- TOAST END -->
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script src="bootstrap-table-custom-view.js"></script>

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

    function customViewFormatter (data) {
        var template = $('#card-template').html()
        var view = ''
        $.each(data, function(i, row) {
            console.log(row.cur_code)
            console.log(row.cur_name)
            view += template.replaceAll('%CODE%', row.cur_code)
                            .replaceAll('%NAME%', row.cur_name)
                            .replaceAll('%DESC%', row.cur_desc)
        })

        console.log(view)
        return `<div class="row mx-0">${view}</div>`
    }

    $('#table').bootstrapTable({
        data: <?php $admin->listCurriculumJSON(); ?>,
        searchSelector: "#search-input",
        pagination: true,
        pageSize: 10,
        showCustomView: true,
        toolbar:    ".toolbar",
        toolbarAlign: 'left',
        customView: customViewFormatter,
        showCustomViewButton: true,
        showCustomViewButtonSelector: "#test"
    })

    $(function () {
        preload("#curr-management", "#curriculum")
        hideSpinner()
    })
</script>
</body>

<!--<script type="module" src="../js/admin/curriculum-list.js"></script>-->
</html>
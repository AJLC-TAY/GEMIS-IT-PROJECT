<?php
include_once('../class/Administration.php');
$admin = new Administration();
?>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Curriculum</li>
        </ol>
    </nav>
    <div class="justify-content-between row">
        <div class="col-md-4 mt-2">
            <h3 class="fw-bold">Curriculum</h3>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-success mt-1" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="bi bi-plus-lg me-2"></i>Add Curriculum</button>
        </div>
    </div>
</header>
<div class="container">
    <!-- SEARCH BAR -->
    <form action="">
        <input id="search-input" type="search" class="form-control search" placeholder="Search something here">
    </form>
    <div class="content">
        <!-- NO RESULTS MESSAGE -->
        <div class="no-result-msg-con w-100 d-flex justify-content-center">
            <p class="no-result-msg" style="display: none; margin-top: 20vh;">No results found</p>
        </div>
        <!-- SUB SPINNER -->
        <div id="curriculum-spinner" class="sub-spinner" style="display: none; height: 60vh;">
            <div class="spinner-con h-100 position-relative">
                <div class="spinner-border position-absolute top-0 start-0 bottom-0 end-0 m-auto" style="margin: auto !important;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <!--CARDS-->
    <div>
        <ul data-page="curriculum" class="cards-con d-flex justify-content-center flex-wrap mt-4 me-5 h-auto" style="min-height: 60vh;">

        </ul>
        <!-- TEMPLATE -->
        <template id="card-template">
            <div data-id='%CODE%' class='tile card shadow-sm p-0 mb-4 position-relative'>
                <a role='button' class='card-link btn btn-link start-0 top-0 end-0 bottom-0 h-100' style='z-index: 2;' href='../admin/curriculum.php?code=%CODE%'></a>
                <div class='dropstart position-absolute top-0 end-0' style='z-index: 3;'>
                    <button type='button' class='btn kebab rounded-circle m-1 px-1' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button>
                    <ul class='dropdown-menu' style='z-index: 99;'>
                        <li><a class='dropdown-item' href='../admin/curriculum.php?code=%CODE%&state=edit'>Edit</a></li>
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
        <!-- TEMPLATE END -->
    </div>
</div>
<!-- ADD CURRICULUM MODAL -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="modal addCurriculum" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Add Curriculum</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="curriculum-form" method="post">
                    <input type="hidden" name="action" id="action" value="addCurriculum" />
                    <p><small class='text-secondary'>Please complete the following: </small></p>
                    <div class="form-group">
                        <label for="curr-code">Code</label>
                        <input id="curr-code" type="text" name="code" class='form-control form-control-sm mb-2' placeholder="Enter unique code here. ex. K12A" />
                    </div>
                    <div class="form-group">
                        <label for="curr-name">Name</label>
                        <input id="curr-name" type="text" name="name" class='form-control form-control-sm mb-2' placeholder="ex. ACADEMIC">
                    </div>
                    <div class="form-group">
                        <label for="curr-desc">Short Description</label>
                        <textarea name="curriculum-desc" class='form-control form-control-sm' maxlength="250" placeholder="ex. K-12 Basic Education Academic Track"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="close btn btn-secondary close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button form="curriculum-form" class="submit btn btn-success btn-sm"><i class="bi bi-plus-lg me-2"></i>Add</button>
            </div>
        </div>
    </div>
</div>
<!-- DELETE MODAL -->
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
<script>
    let curricula = <?php $admin->listCurriculumJSON(); ?>;
</script>
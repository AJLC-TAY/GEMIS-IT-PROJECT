<?php
$admin = new Administration() ?>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Programs</li>
        </ol>
    </nav>
    <div class="row justify-content-between">
        <div class="col-md-4">
            <h3 class="fw-bold">Programs</h3>
        </div>
        <div class="col-md-8  d-flex justify-content-lg-end">
            <div class="col-auto">
                <button type="button" class="view-archive btn btn-secondary m-1"><i class="bi bi-eye me-2"></i>View Archived Program</button>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-success m-1" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="bi bi-plus me-2"></i>Add Program</button>
            </div>
        </div>
    </div>
    <!-- SEARCH BAR -->
    <input id="search-input" type="search" class="form-control search" placeholder="Search something here">
</header>

<div class="container">
    <!-- NO RESULTS MESSAGE -->
    <div class="w-100 d-flex justify-content-center">
        <p class="no-result-msg my-5 mx-auto" style="display: none;">No results found</p>
    </div>
    <!-- SUB SPINNER -->
    <div id="program-spinner" class="sub-spinner" style="display: none; height: 60vh;">
        <div class="spinner-con h-100 position-relative">
            <div class="spinner-border position-absolute top-0 start-0 bottom-0 end-0 m-auto" style="margin: auto !important;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class=" ">
        <ul data-page="program" class="cards-con flex-wrap mt-3 d-flex justify-content-center me-5 h-auto" style="min-height: 60vh;">
        </ul>
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
        
    </div>
</div>

<!-- ADD MODAL -->
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
                    <div class="form-group mb-2">
                        <label for="prog-code">Code</label>
                        <input id="prog-code" type="text" name="prog-code" class='form-control form-control-sm' placeholder="Enter unique code here. ex. ABM">
                    </div>

                    <div class="form-group mb-2">
                        <label for="prog-desc">Description</label>
                        <input id="prog-name" type="text" name="desc" class='form-control form-control-sm' placeholder="ex. Accountancy, Business, and Management">
                    </div>

                    <div class="form-group">
                        <label for="curr-code">Curriculum</label>
                        <select id="curr-code" class="select form-select form-select-sm" name="curr-code">
                            <option selected disabled>-- Select curriculum --</option>
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
                <button class="close btn btn-dark btn-sm close-btn" data-bs-dismiss="modal">Cancel</button>
                <button form="program-form" class="submit btn btn-success btn-sm"><i class="bi bi-plus-lg me-1"></i>Add</button>
            </div>
        </div>
        </form>
    </div>
</div>
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
                <button class="btn btn-primary archive-btn">Archive</button>
            </div>
        </div>
    </div>
</div>

<!-- UNARCHIVE MODAL -->
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

<!-- VIEW ARCHIVED MODAL -->
<div class="modal fade" id="view-arch-modal" tabindex="-1" aria-labelledby="modal viewArhivedCurriculum" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
                <button class="close btn btn-dark btn-sm close-btn" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger close-btn delete-btn btn-sm">Delete</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    let programs = <?php $admin->listProgramsJSON(); ?>;
</script>
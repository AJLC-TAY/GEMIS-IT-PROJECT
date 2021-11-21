<!DOCTYPE html>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Subject</li>
        </ol>
    </nav>
    <div class="row justify-content-between mb-3">
        <div class="col-md-4">
            <h3 class="fw-bold">Subject</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-lg-end">
            <div class="col-auto">
                <button type="button" class="view-archive btn btn-outline-dark m-1" data-bs-toggle="modal" data-bs-target="#view-arch-modal"><i class="bi bi-eye me-1"></i>View Archived Subjects</button>
            </div>
            <div class="col-auto">
                <a href="subject.php?page=schedule" role="button" class="btn btn-secondary m-1">Schedule</a>
            </div>
            <div class="col-auto">
                <a href="subject.php?action=add" id="add-btn" class="btn btn-success add-prog m-1" title='Add new strand'><i class="bi bi-plus me-2"></i>Add Subject</a>
            </div>
        </div>
    </div>
</header>
<!-- HEADER END -->
<!-- SUBJECT TABLE -->
<div class="container mt-1">
    <div class="card w-100 h-auto bg-light">
        <table id="table" class="table-striped table-sm">
            <thead class='thead-dark'>
                <div class="d-flex justify-content-between mb-3">
                    <!-- SEARCH BAR -->
                    <span class="flex-grow-1 me-2"> 
                        <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                    </span>
                    <div>
                        <!-- <button class="btn btn-primary btn-sm" title='Archive strand'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button> -->
                        <button class="btn btn-secondary btn-sm archive-option" title='Archive strand'><i class="bi bi-archive me-2"></i>Archive</button>
                    </div>
                </div>


                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="180" data-align="center" data-field="sub_code">Code</th>
                    <th scope='col' data-width="500" data-halign="center" data-align="left" data-sortable="true" data-field="sub_name">Subject Name</th>
                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="sub_type">Type</th>
                    <th scope='col' data-width="150" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- SUBJECT TABLE END -->

<!-- MAIN CONTENT END -->

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
                <button class="btn btn-primary close-btn archive-btn">Archive</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view-arch-modal" tabindex="-1" aria-labelledby="modal viewArchivedSubjects" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Archived Subjects</h4>
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
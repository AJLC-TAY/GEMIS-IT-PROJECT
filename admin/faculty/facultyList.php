<!DOCTYPE html>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Faculty</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Faculty Members</h3>
        <div>
            <a href="faculty.php?action=add" id="add-btn" class="btn btn-success" title='Add new faculty'><i class="bi bi-plus me-2"></i>Add Faculty</a>
        </div>
    </div>
</header>
<!-- FACULTY TABLE -->
<div class="container mt-1">
    <div class="card w-100 h-auto bg-light">
        <table id="table" class="table-striped table-sm">
            <thead class='thead-dark'>
                <div class="row justify-content-between mb-3">
                    <!-- SEARCH BAR -->
                    <div class="col-md-5">
                        <input id="search-input" type="search" class="form-control form-control-sm m-1" placeholder="Search something here">
                    </div>
                    <div class="col-md-auto">
                        <button data-type="reset" class="table-opt btn btn-secondary btn-sm ms-1" title='Reset Password'>Reset Password</button>
                        <button data-type="activate"  class="table-opt btn btn-success btn-sm ms-1" title='Activate account'>Activate</button>
                        <button data-type="deactivate" class="table-opt btn btn-outline-danger btn-sm ms-1" title='Deactivate Faculty'>Deactivate</button>
                    </div>
                </div>

                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="user_id">UID</th>
                    <th scope='col' data-width="350" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="department">Department</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="active">Account Status</th>
                    <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- MODAL -->
<div id="confirmation-modal" class="modal fade" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h5 class="mb-0">Confirmation</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reset-form" method="POST"></form>
                <div class="message"></div>
            </div>
            <div class="modal-footer">
                <button class="close btn btn-dark close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="submit btn btn-danger btn-sm"></button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL END -->
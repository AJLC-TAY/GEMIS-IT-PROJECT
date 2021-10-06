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
            <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived Faculty</button>
            <a href="faculty.php?action=add" id="add-btn" class="btn btn-success" title='Add new faculty'><i class="bi bi-plus me-2"></i>Add Faculty</a>
            <!-- <a href="faculty.php?state=add" id="add-btn" class="btn btn-success add-prog" title='Add new faculty'>ADD FACULTY</a> -->
        </div>
    </div>
</header>
<!-- FACULTY TABLE -->
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
                        <button id="export-opt" type="submit" class="table-opt btn btn-primary btn-sm" title='Export' value='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                        <input id="reset-pass-opt" type="submit" class="table-opt btn btn-secondary btn-sm" title='Reset Password' value="Reset Password">
                        <input id="deactivate-opt" type="submit" form="deactivate-from" class="table-opt btn btn-outline-danger btn-sm" title='Deactivate Faculty' value="Deactivate">
                    </div>
                </div>

                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="teacher_id">UID</th>
                    <th scope='col' data-width="450" data-align="left" data-sortable="true" data-field="name">Name</th>
                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="department">Department</th>
                    <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th>
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
          
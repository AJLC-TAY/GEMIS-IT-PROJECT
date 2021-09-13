<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Student</a></li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Students</h3>
        <div>
            <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived Students</button>
        </div>
    </div>
</header>

<!-- Student List -->
<div class="container">
    <div class="card w-100 h-auto bg-light">
        <table id="table" class="table-striped">
            <thead class='thead-dark'>
                <div class="d-flex justify-content-between mb-3">
                    <!-- SEARCH BAR -->
                    <span class="flex-grow-1 me-3">
                        <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                    </span>
                    <div>
                        <button id="export-opt" type="submit" class="btn btn-primary btn-sm" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                        <input id="reset-pass-opt" type="submit" class="btn btn-secondary btn-sm" title='Reset Password' value="Reset Password">
                        <input id="deactivate-opt" type="submit" form="deactivate-from" class="btn btn-outline-danger btn-sm" title='Deactivate Faculty' value="Deactivate">
                    </div>
                </div>

                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="150" data-align="center" data-sortable="true" data-field='lrn'>LRN</th>
                    <th scope='col' data-width="400" data-align="left" data-sortable="true" data-field='name'>Student Name</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field='section'>Section</th>
                    <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
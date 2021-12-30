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
                <a href="subject.php?page=schedule" role="button" class="btn btn-secondary m-1"><i class="bi bi-calendar4-week me-2"></i> Schedule</a>
            </div>
            <div class="col-auto">
                <a href="subject.php?action=add" id="add-btn" class="btn btn-success add-prog m-1" title='Add new strand'><i class="bi bi-plus-lg me-2"></i>Add Subject</a>
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
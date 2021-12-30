 <!-- HEADER -->
 <header>
     <!-- BREADCRUMB -->
     <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="index.php">Home</a></li>
             <li class="breadcrumb-item active" aria-current="page">School Year</li>
         </ol>
     </nav>
     <div class="row justify-content-between align-items-center mb-3">
         <div class="col-auto">
            <h3 class="fw-bold">School Year</h3>
         </div>
         <div class="col-auto">
             <button class="btn btn-secondary m-1" data-bs-toggle="modal" data-bs-target="#view-archive-modal" title="View archived school year"><i class="bi bi-eye me-2"></i>Archived SY</button>
             <?php if ($_SESSION['current_quarter'] != 5) { ?>
             <button id="end-sy" class="btn btn-danger m-1" data-bs-toggle='modal' data-bs-target="#end-sy-modal" title='End this school year'>End Current SY</button>
             <?php } ?>
             <a href="schoolYear.php?action=init" id="add-btn" class="btn btn-success m-1" title='Initialize a school year'>Initialize</a>
         </div>
     </div>
 </header>
 <!-- HEADER END -->
 <!-- SCHOOL YEAR TABLE -->
 <div class="container mt-1">
     <div class="card w-100 h-auto bg-light">
         <div class="d-flex justify-content-between mb-3">
             <!-- SEARCH BAR -->
             <span class="flex-grow-1 me-3">
                 <input id="search-input" type="search" class="form-control" placeholder="Search something here">
             </span>
         </div>
         <table id="table" class="table-striped">
             <thead class='thead-dark'>
                 <tr>
                     <th scope='col' data-width="150" data-align="center" data-field="id">ID</th>
                     <th scope='col' data-width="150" data-align="center" data-sortable="true" data-field="sy_year">School Year</th>
                     <th scope='col' data-width="100" data-halign="center" data-align="left" data-field="current_qtr">Current Quarter</th>
                     <th scope='col' data-width="250" data-align="center" data-field="action">Action</th>
                     <th scope='col' data-width="150" data-align="center" data-field="enrollment">Enrollment Status</th>
                 </tr>
             </thead>
         </table>
     </div>
 </div>
 <!-- VIEW ARCHIVED MODAL -->
 <div class="modal fade" id="view-archive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Archived School Year</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="d-flex mb-3">
                        <input id="search-archive" type="search" class="form-control form-control-sm me-3" placeholder="Search something here ...">
                        <input type="reset" class="btn btn-sm btn-dark" value="Clear">
                    </div>
                </form>
                <table class="table-sm" data-height="400" data-url="getAction.php?data=archivedSY" data-search-selector="#search-archive" data-toggle="table" id="archived-table">
                    <thead>
                        <th scope='col' data-width="200" data-align="center" data-field="id">ID</th>
                        <th scope='col' data-width="600" data-align="center" data-sortable="true" data-field="sy_year">School Year</th>
                        <th scope='col' data-width="200" data-align="center" data-field="action">Action</th>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button class="close btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
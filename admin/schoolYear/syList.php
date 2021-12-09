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
             <?php if ($_SESSION['current_quarter'] != 5) { ?>
             <button id="end-sy" class="btn btn-danger me-1" data-bs-toggle='modal' data-bs-target="#end-sy-modal" title='End this school year'>End School Year</button>
             <?php } ?>
             <a href="schoolYear.php?action=init" id="add-btn" class="btn btn-success" title='Initialize a school year'>Initialize</a>
         </div>
     </div>
 </header>
 <!-- HEADER END -->
 <!-- SCHOOL YEAR TABLE -->
 <div class="container mt-1">
     <div class="card w-100 h-auto bg-light">
         <table id="table" class="table-striped">
             <thead class='thead-dark'>
                 <div class="d-flex justify-content-between mb-3">
                     <!-- SEARCH BAR -->
                     <span class="flex-grow-1 me-3">
                         <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                     </span>
                 </div>
                 <tr>
                     <!-- <th data-checkbox="true"></th> -->
                     <th scope='col' data-width="150" data-align="center" data-field="id">ID</th>
                     <th scope='col' data-width="150" data-align="center" data-sortable="true" data-field="sy_year">School Year</th>
                     <th scope='col' data-width="100" data-align="left" data-field="current_qtr">Current Quarter</th>
                     <th scope='col' data-width="250" data-align="center" data-field="action">Action</th>
                     <th scope='col' data-width="150" data-align="center" data-field="enrollment">Enrollment Status</th>
                 </tr>
             </thead>
         </table>
     </div>
 </div>
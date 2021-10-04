 <!-- HEADER -->
 <header>
     <!-- BREADCRUMB -->
     <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="index.php">Home</a></li>
             <li class="breadcrumb-item active" aria-current="page">School Year</li>
         </ol>
     </nav>
     <div class="d-flex justify-content-between align-items-center mb-3">
         <h3 class="fw-bold">School Year</h3>
         <span>
            <a href="schoolYear.php?action=init" id="add-btn" class="btn btn-success" title='Initialize a school year'>Initialize</a>
         </span>
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
                     <th scope='col' data-width="150" data-align="left" data-field="id">ID</th>
                     <th scope='col' data-width="150" data-align="center" data-sortable="true" data-field="sy_year">School Year</th>
<!--                     <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field="grd_level">Grade Level</th>-->
                     <th scope='col' data-width="100" data-align="left" data-field="current_qtr">Current Quarter</th>
                     <th scope='col' data-width="100" data-align="center" data-field="current_sem">Current Semester</th>
                     <th scope='col' data-width="250" data-align="center" data-field="action">Action</th>
                     <th scope='col' data-width="150" data-align="center" data-field="enrollment">Enrollment Status</th>
                 </tr>
             </thead>
         </table>
     </div>
 </div>
 <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#month-modal">Show month modal</button>
 <!-- MODAL -->
 <div class="modal fade" id="month-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <div class="modal-title">
                     <h4 class="mb-0">Academic days by Month</h4>
                 </div>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body overflow-auto" style="height: 50vh;">
                <form id="month-form" action="action.php" method="POST">
                    <input type="hidden" name="sy-id">
                    <input type="hidden" name="action" value="editAcademicDays">
                    <div class="container">
                        <?php
                        $months = array(
                            'jan' => 'January',
                            'feb' => 'February',
                            'mar' => 'March',
                            'apr' => 'April',
                            'may' => 'May',
                            'jun' => 'June',
                            'jul' => 'July ',
                            'aug' => 'August',
                            'sep' => 'September',
                            'oct' => 'October',
                            'nov' => 'November',
                            'dec' => 'December',
                        );
                        foreach ($months as $m => $month) {
                            echo "<div class='form-control-sm row'>
                                        <label for='#$m' class='col-form-label-sm col-4'>$month</label>
                                        <div class='col-8'>
                                            <input id='$m' type='number' name='month[$m]' class='number form-control form-control-sm' placeholder='Enter no. of days' title='$month' min='0' max='30''>
                                        </div>
                                    </div>";
                        }
                        ?>

                    </div>
                </form>
             </div>
             <div class="modal-footer">
                 <button class="close btn btn-sm btn-dark close-btn" data-bs-dismiss="modal">Cancel</button>
                 <input type="submit" form="month-form" class="btn btn-sm btn-success" value="Save">
             </div>
         </div>
     </div>
 </div>
 <!-- MODAL END -->
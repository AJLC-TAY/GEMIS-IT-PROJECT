<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Advisory</li>
        </ol>
    </nav>
    <div class="row align-content-center">
        <div class="col-auto">
            <h3 class="fw-bold"><?php echo $advisory['section_name'] ?? 'No Advisory Class'  ?></h3>
        </div>
    </div>
</header>
<!-- HEADER END -->
<!-- STUDENTS TABLE -->

<div class="container mt-1 ms-0">
    <div class="card h-auto bg-light" style="min-height: 70vh !important;">
        <div class="row">
            <!-- SEARCH BAR -->
            <div class="col-lg-3 mb-2">
                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
            </div>
            <div class="col-lg-3 mb-2" >
                <select name="" class="form-control form-control-sm mb-3 w-auto" id="classes">
                    <?php
                    echo $adv_opn;
                    // echo $sub_class_opn;
                    ?>
                </select>
            </div>
            <?php  $class = "hidden";
             if ($_SESSION['current_quarter'] == 2 OR $_SESSION['current_quarter'] == 4) {
                $class = "";
                
            } ?>  
            <div class="col-lg-3 d-flex-inline mb-2">
                <button type="button" class="btn btn-secondary mb-1 calculate" title="Calculate GA" data-code="<?php echo $advisory['section_code']; ?>"></i>Calculate GA</button>
                <button type="button" class="btn btn-primary mb-1 multi-promote <?php echo $class?>"></i>Promote</button>
            </div>
            <?php 
             if ($_SESSION['current_quarter'] == 2 OR $_SESSION['current_quarter'] == 4) {
                $class = "";
                echo '<div class="col-lg-3 mb-2">';
                echo '<button type="button" class="btn btn-success ms-2 save"></i>Save</button><button type="button" class="btn btn-success submit">Submit</button>';
                echo '</div>';
            } ?> 
            
        </div>

        <table id="table" class="table-striped table-sm <?php echo $adv_table_display; ?>">
            <thead class='thead-dark'>
                <tr>
                    <th scope='col' data-width="150" data-align="center" data-field="id">ID</th>
                    <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="status">Status</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_1">1st Grade</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_2">2nd Grade</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_f">1st Sem Gen Ave</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="2grd_f">2nd Sem Gen Ave</th>
                    <th scope='col' data-width="150" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="fade modal" id="view-candidates-modal" tabindex="-1" aria-labelledby="modal viewArhivedCurriculum" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Students Qualified for Promotion</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <div class="overflow-auto" style="height: 50vh;">
                    <table class="table table-striped table-sm" id = 'stud-table'>
                    <tr class ='text-center'><th>ID</th>
                        <th>Student Name</th>
                        <th>Average</th>
                        <th>Action</th></tr>
                <?php
                // $students = $faculty -> listStudentsForPromotion();
                // foreach ($students as $student){ 
                //     // $name = ${student['name']}
                //     echo "<tr class ='text-center'><td>{$student['stud_id']}</td> 
                //               <td>{$student['name']}</td> 
                //               <td>{$student['gen_ave']} </td>
                //  <td><button data-name='' class='unarchive-option btn link' id=''>Remove</button></td></tr>";
                //   } 
                ?> -->

                    <!-- </table> -->
                    <table id="for-promotion-table" class="table-striped table-sm">
                    <thead class='thead-dark'>
                        <tr>
                            <th scope='col' data-width="150" data-align="center" data-field="stud_id">ID</th>
                            <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="gen_ave">Gen Ave</th>
                            <th scope='col' data-width="150" data-align="center" data-field="action">Actions</th>
                        </tr>
                    </thead>
                    </table>
                <!-- </div> -->
            </div>
            <div class="modal-footer">
                <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary promote-btn" data-bs-dismiss="modal">Promote</button>
            </div>
        </div>
    </div>
</div>
<script>
    var code = '<?php echo json_encode($advisory['section_code']); ?>';
</script>

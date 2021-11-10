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
        <h3 class="fw-bold"><?php echo $advisory['section_name']?></h3>
    </div>
</div>
</header>
<!-- HEADER END -->
<!-- STUDENTS TABLE -->

<div class="container mt-1 ms-0">
    <div class="card h-auto bg-light" style="min-height: 70vh !important;">
        <div class="d-flex justify-content-between mb-3">
            <!-- SEARCH BAR -->
            <div class="flex-grow-1 me-3">
                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
            </div>
            <div class="col-auto" style="min-width: 250px !important;">
                <select name="" class="form-control form-control-sm mb-3 w-auto" id="classes">
                    <?php
                    echo $adv_opn;
                    // echo $sub_class_opn;
                    ?>
                </select>
            </div>
            <div>
                <?php echo ($_SESSION['current_quarter'] == 4 ? '<button type="button" class="btn btn-success ms-2 save"></i>Save</button><button type="button" class="btn btn-success submit">Submit</button>' : ""); ?>
            </div>
        </div>

        <table id="table" class="table-striped table-sm <?php echo $adv_table_display; ?>">
            <thead class='thead-dark'>
                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="150" data-align="center" data-field="id">ID</th>
                    <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="status">Status</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_1">1st Grade</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_2">2nd Grade</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_f">Final Grade</th>
                    <th scope='col' data-width="150" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
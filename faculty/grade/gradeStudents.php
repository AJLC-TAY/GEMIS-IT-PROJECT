<!DOCTYPE html>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Grade</a></li>
        </ol>
    </nav>
</header>
<div class="container mt-1 ms-0">
    <div class="card w-100 h-auto bg-light" style="min-height: 70vh !important;">
        <h5 class="fw-bold"><?php echo $sem ?> Semester & Second Quarter</h5>
        <div class="d-flex justify-content-between mb-1">
            <!-- SEARCH BAR -->
            <div class="flex-grow-1 me-3">
                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
            </div>
            <div class="w-25">
                <select class="form-select form-select-sm" id="classes">
                    <?php
                    echo $adv_opn;
                    echo $sub_class_opn;
                    ?>
                </select>
            </div>
            <div>
                <form method="post" action="action.php" class="ms-2">
                    <input class='hidden' id='export_code' name='code' value=''>
                    <input type="submit" name="export" value="Export">
                </form>
            </div>
            <div>
                <!-- <form method="post" action="action.php"><input type="submit" id='export' name="export" class="btn btn-secondary" value="EXPORT"></form> -->
                <!-- <button type="submit" class="btn btn-secondary export" >EXPORT</button>
                                            <button onclick="Export()" class="btn btn-secondary">EXPORT</button> -->
                <button type="button" class="btn btn-success ms-2 save"></i>Save</button>
                <button type="button" class="btn btn-success submit">Submit</button>
            </div>
        </div>

        <form id='grades'>
            <table id="table" class="table-striped table-sm">
                <thead class='thead-dark'>
                    <tr>
                        <th scope='col' data-width="150" data-align="center" data-field="id">ID</th>
                        <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Student Name</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" contenteditable="true" data-field="grd_1"><?php echo $qtrs[0]; ?> Quarter</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_2"><?php echo $qtrs[1]; ?> Quarter</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_f">Final Grade</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="action_2">Action</th>

                    </tr>
                </thead>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript">var type = 'grades'</script>
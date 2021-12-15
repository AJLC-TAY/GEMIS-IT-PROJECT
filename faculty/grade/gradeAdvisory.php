<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Advisory</li>
        </ol>
    </nav>
    <div class="row align-content-center justify-content-between">
        <div class="col-auto">
            <h3 class="fw-bold"><?php echo $advisory['section_name'] ?? 'No Advisory Class'  ?></h3>
        </div>
        <div class="col-7">
            <div class="row">
                <div class="col-md-4 text-end"><label for="signatory" class="pt-lg-2">Grade report signatory</label></div>
                <div class="col-md-8">
                    <select name="signatory" class="form-select form-control-sm" id="signatory" title="Signatory in the grade report">
                        <?php
                        foreach ($signatories  as $signatory) {
                            $name = $signatory->get_name();
                            $position = $signatory->get_position();
                            echo "<option value = '{$signatory->get_sign_id()}' data-name = '$name' ". (strpos(strtolower($position), "assistant principal") !== false ? "selected" : "") ." data-position = '$position'>$name - $position</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- HEADER END -->
<!-- STUDENTS TABLE -->

<div class="container mt-1 ms-0">
    <div class="card h-auto bg-light" style="min-height: 70vh !important;">
        <div class="d-flex justify-content-between mb-3">
            <!-- SEARCH BAR -->
            <span class="flex-grow-1 me-2">
                <input id="search-input" type="search" class="form-control " placeholder="Search something here">
            </span>
            <?php $class = "hidden";
            if ($_SESSION['current_quarter'] == 2 or $_SESSION['current_quarter'] == 4) {
                $class = "";
            } ?>
            <div class="d-flex-inline mb-2 ">
                <button type="button" class="btn btn-outline-dark ms-2 calculate <?php echo $class ?>" title="Calculate GA" data-code="<?php echo $advisory['section_code']; ?>"></i>Calculate GA</button>
                <button type="button" class="btn btn-secondary multi-promote <?php echo $class ?>"></i>Promote</button>
            </div>
            <?php
            if ($_SESSION['current_quarter'] == 2 or $_SESSION['current_quarter'] == 4) {
                $class = "";
                echo '<div class="d-flex-inline justify-content-end">';
                echo '<button type="button" class="btn btn-primary ms-1 save"></i>Save</button>';
                echo '</div>';
            } ?>

        </div>
        <form id="advisory-class-form" method="post">
            <table id="table" class="table-striped table-sm" data-url="getAction.php?data=student&section=<?php echo $section_code ?>">
                <thead class='thead-dark'>
                    <tr>
                        <th scope='col' data-width="50" data-align="center" data-field="uid">UID</th>
                        <th scope='col' data-width="250" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="status">Status</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_1">1st Grade</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_2">2nd Grade</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_f">1st Sem Gen Ave</th>
                        <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="2grd_f">2nd Sem Gen Ave</th>
                        <th scope='col' data-width="150" data-align="center" data-field="action">Actions</th>
                    </tr>
                </thead>
            </table>
        </form>
        <div class="d-flex flex-row-reverse mt-2">
            <button type="button" class="btn btn-success ms-1 submit">Submit</button>
        </div>
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
            </div>
            <div class="modal-footer">
                <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary promote-btn" data-bs-dismiss="modal">Promote</button>
            </div>
        </div>
    </div>
</div>

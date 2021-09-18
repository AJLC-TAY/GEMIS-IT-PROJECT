<?php require_once("../class/Administration.php");
$admin = new Administration();
$userProfile = $admin->getProfile("ST");
$stud_name = $userProfile->get_name();
$section = $userProfile->get_section();
$stud_id = $userProfile->get_stud_id();
?>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">Student</a></li>
            <li class="breadcrumb-item active">Transfer Student</a></li>

        </ol>
    </nav>

    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Transfer Student</h3>
    </div>
</header>
<hr>

<div class='d-flex w-100 justify-content-between'>
    <h5>Student Name: <?php echo $stud_name; ?></h5>
    <h5>Section: <?php echo $section; ?></h5>
</div>
<!-- Section List: With slot -->
<div id='subject-con' class=''>
    <input id="search-subject" type="text" class="form-control form-control-sm" placeholder="Search section here ...">
    <div class="assigned-sub-con list-group border">
        <?php
        $available_subjects = $admin->listAvailableSection();
        foreach ($available_subjects as $subject) {
            $name = $subject['name'];
            $adviser = "Adviser: " . $subject['adviser'];
            $slot = "Available Slots: " . $subject['slot'];
            $code = $subject['code'];
            echo "<button id='${code}' data = '${stud_id}' class='transfer btn btn-link list-group-item list-group-item-action' aria-current='true' type='button'>
            <div class='d-flex w-100 justify-content-between'>
                <p class='mb-1'>$name</p>
                <small>$slot</small>
            </div>
            <small class='mb-1 text-secondary'>$adviser</small>
        </button>";
        }
        ?>
    </div>
    <div>
        <button id="transfer-full" class="link btn w-auto mx-auto" data-bs-toggle='collapse' href='#section-table'><small>Transfer Student to Full Section</small></button>
    </div>
    <div id='section-table' class='collapse mt-3'>
        <table id="table" class="table-striped table-sm">
            <thead class='thead-dark'>
                <div class="d-flex justify-content-between mb-1">
                    <!-- SEARCH BAR -->
                    <span class="flex-grow-1 me-3">
                        <input id="search-sub-input" type="search" class="form-control form-control-sm" placeholder="Search subject here">
                    </span>
                </div>
                <tr>
                    <th scope='col' data-width="400" data-halign="center" data-align="left" data-sortable="true" data-field="current_code" data-class="hidden">Current Code</th>
                    <th scope='col' data-width="400" data-halign="center" data-align="left" data-sortable="true" data-field="section_code">Code</th>
                    <th scope='col' data-width="400" data-halign="center" data-align="left" data-sortable="true" data-field="section_name">Section</th>
                    <th scope='col' data-width="400" data-halign="center" data-align="left" data-sortable="true" data-field="adviser_name">Adviser</th>
                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="student">Student to swap</th>
                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="action">Action</th>
                    
                </tr>
            </thead>

        </table>
    </div>
    <div class="modal fade" id="transferConfirmation" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to transfer <?php echo $stud_name?> to <span id="modal-identifier"></span>?</h5>
                    <p class="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary close-btn transfer-btn">Transfer</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transferConfirmationFull" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to transfer <?php echo $stud_name?> to <span id="modal-identifier"></span>?</h5>
                    <p class="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary close-btn transfer-btn-full">Transfer</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var id = <?php echo $stud_id; ?>;
    </script>
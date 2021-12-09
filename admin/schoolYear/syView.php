<?php
//include_once("../inc/head.html");
include_once ("../class/Administration.php");
$admin = new Administration();
$sy_info = $admin->getSYInfo();
$switch_btn = "";
$sy_id_in_link = $_GET['id'];
$edit_acads_btn = "<button data-id='$sy_id_in_link' class='btn btn-secondary btn-sm edit-month-btn'><i class='bi bi-pencil-square me-2'></i>Edit</button>";
if ($_SESSION['sy_id'] != $sy_id_in_link) {
    $switch_btn = "<a role='button' href='action.php?action=switchSY&id=$sy_id_in_link' class='btn btn-primary m-1'>Switch</a>";
    $edit_acads_btn = "";
}
?>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class='breadcrumb-item'><a href='index.php'>Home</a></li>
            <li class='breadcrumb-item'><a href='schoolYear.php'>School Year</a></li>
            <li class='breadcrumb-item active'><?php echo $sy_info['desc']; ?></li>
        </ol>
    </nav>
    <div class="d-flex align-content-center justify-content-between ">
        <div class="d-flex-column">
            <h4 class="fw-bold mb-0">School Year <?php echo $sy_info['desc']; ?></h4>
            <?php echo "SY ID: ".$sy_id_in_link ;?> 
        </div>
        <?php echo $switch_btn; ?>
    </div>
    <hr>
</header>
<div class="container">
    <section class="row">
        <section class="col-md-4">
            <div class='col-xl-12 shadow-sm p-3 bg-light border rounded-3 text-start mb-4' style="height: 95.5%;">
                <div class="d-flex justify-content-between">
                    <h5 class='text-start p-0 fw-bold mb-0'>ACADEMIC DAYS</h5>
                    <div>
                        <?php echo $edit_acads_btn; ?>
                    </div>
                </div>
                <hr class='mt-2'>
                <div class='row d-flex justify-content-center'>
                    <div class="inner container">
                        <?php
                        foreach($sy_info['month'] as $month => $days) {
                            echo "<div class='row ps-4'>"
                                ."<div class='col-sm-8'>"
                                    ."<h7 class='fw-bold mt-1'>{$days['month']}</h7>"
                                ."</div>"
                                ."<div class='col-sm-3 mb-2 text-end'>"
                                    ."<h7>{$days['number']}</h7>"
                                ."</div>"
                            ."</div>";
                        }
                        ?>
                        <div class="row ps-4">
                            <div class='col-sm-8 border-top'>
                                <h6 class='fw-bold mt-2'>Total</h6>
                            </div>
                            <div class='col-sm-3 text-end border-top'>
                                <h6 class="mt-2 fw-bold"><?php echo $sy_info['total_days']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="col-sm-8">
            <div class='col-xl-12 shadow-sm p-3 bg-light border rounded-3 text-start mb-4 '>
                <h5 class='text-start fw-bold'>TRACKS</h5>
                <hr class='mt-1'>
                <div class='row p-2 ms-2'>
                    <?php
                    foreach ($sy_info["curriculum"] as $curr => $curriculum) {
                        echo "<h6><a href='curriculum.php?code=$curr' class='link btn p-0' target='_blank'>{$curriculum['desc']}</a>";
                    }
                    ?>
                </div>
            </div>
            <div class='col-xl-12 shadow-sm p-3 bg-light border rounded-3 text-start mb-4'>
                <h5 class='text-start p-0 fw-bold'>STRAND</h5>
                <hr class='mt-1'>
                <div class='row'>
                    <?php
                    foreach($sy_info['curriculum'] as $curr => $curriculum ) {
                        echo "<div class='fw-bold ms-3'>{$curriculum['desc']}</div>";
                        echo "<ul class='ms-4'>";
                            foreach($curriculum['program'] AS $curr_prog_id => $curr_prog_desc) {
                                echo "<li><a href='program.php?prog_code=$curr_prog_id' target='_blank' class='btn link'>$curr_prog_desc</a></li>";
                            }
                        echo "</ul>";
                    }
                    ?>
            </div>
        </section>
    </section>
</div>

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
                        <ul id="month-list" class="p-0">
                            <?php 
                            foreach($sy_info['month'] as $acad_days_id => $daysData) {
                            echo "<li class='form-control-sm row'>
                                    <label class='col-form-label-sm col-4 fw-bold'>${daysData['month']}</label>
                                    <div class='col-5'>
                                        <input value='${daysData['number']}' type='number' name='month[$acad_days_id]' class='text-center number form-control form-control-sm' placeholder='Enter no. of days' title='${month}' min='0' max='30''>
                                    </div>
                                    <div class='col-3 text-center'>
                                        <button class='btn btn-sm btn-outline-danger edit-opt' data-type='remove'><i class='bi bi-dash-circle'></i></button>
                                        <button class='btn btn-sm btn-secondary edit-opt' data-type='undo' style='display: none;'>Undo</button>
                                    </div>
                                </li>";
                            }
                            ?>
                        </ul>
                        <div class='form-control-sm row'>
                            <div class='col-6'>
                                
                            </div>
                        </div>
                    </div>
                </form>
             </div>
             <div class="modal-footer">
                 <button class="close btn btn-sm btn-outline-dark close-btn" data-bs-dismiss="modal">Cancel</button>
                 <button class='btn btn-sm btn-primary edit-opt' data-type='add'>Add Month</button>
                 <input type="submit" form="month-form" class="btn btn-sm btn-success" value="Save">
             </div>
         </div>
     </div>
 </div>
 <!-- MODAL END -->
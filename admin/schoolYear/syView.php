<?php
//include_once("../inc/head.html");
include_once ("../class/Administration.php");
$admin = new Administration();
$sy_info = $admin->getSYInfo();
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
    <h4 class="fw-bold">School Year <?php echo $sy_info['desc']; ?></h4>
    <hr>
</header>
<div class="container">
    <section class="row">
        <section class="col-md-4">
            <div class='col-xl-12 shadow-sm p-3 bg-light border rounded-3 text-start mb-4'>
                <div class="d-flex justify-content-between">
                    <h5 class='text-start p-0 fw-bold'>ACADEMIC DAYS</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-pencil-square me-2"></i>Edit</button>
                    </div>
                </div>
                <hr class='mt-2'>
                <div class='row d-flex justify-content-center'>
                    <div class="inner container">
                        <?php
                        foreach($sy_info['month'] as $month => $days) {
                            echo "<div class='row ps-4'>"
                                ."<div class='col-sm-8'>"
                                    ."<h7 class='fw-bold mt-1'>$month</h7>"
                                ."</div>"
                                ."<div class='col-sm-3 text-end'>"
                                    ."<h7>$days</h7>"
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
                <h5 class='text-start p-0 fw-bold'>TRACKS</h5>
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
<h5 class="fw-bold mb-3">SUBJECT CHECKLIST</h5>
<div class="container">
    <section class="row">
        <section class="col-sm-6">
            <div class="col-xl-12 shadow-sm p-4 bg-light border rounded-3 text-start mb-4 ">
                <h5 class='text-start p-0 fw-bold'>CORE SUBJECTS</h5>
                <hr class='mt-1'>
                <ul class='ms-2 list-group overflow-auto' style='max-height: 700px;'>
                    <?php
                        foreach($sy_info['subject']['core'] as $core_id => $core) {
                            echo "<a href='subject.php?sub_code=$core_id' class='link text-start list-group-item list-group-item-action bg-light' target='_blank'>{$core['name']}</a>";
                        }
                    ?>
                </ul>
            </div>
        </section>
        <section class="col-sm-6">
            <div class="col-xl-12 shadow-sm p-4 bg-light border rounded-3 text-start mb-4">
                <h5 class='text-start p-0 fw-bold'>SPECIALIZED | APPLIED SUBJECTS </h5>
                <hr class='mt-1'>
                <ul class='ms-2 list-group overflow-auto' style='max-height: 700px;'>
                    <?php
                    foreach($sy_info['subject']['spap'] as $spap_id => $spap) {
                        echo "<li class='list-group-item bg-light'>"
                            ."<a href='subject.php?sub_code=$spap_id' class='link text-start btn' target='_blank'>{$spap['name']}</a>";
                            foreach($spap['prog'] as $prog_badge) {
                                echo "<span class='badge bg-primary badge-pill ms-1'><a href='program.php?prog_code=$prog_badge' class='text-white' target='_blank'>$prog_badge</a></span>";
                            }
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div>
        </section>
    </section>
</div>
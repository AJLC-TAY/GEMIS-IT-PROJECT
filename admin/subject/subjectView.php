<?php
require_once("../class/Administration.php");
$admin = new Administration();

// get the subject data
$subject = $admin->getSubject();
$sub_code = $subject->get_sub_code();
$sub_name = $subject->get_sub_name();
$sub_type = $subject->get_sub_type();
$sub_semester = $subject->get_sub_semester();
$sub_grd_lvl = $subject->get_for_grd_level();
$prog_name = '';

// view
$links = "<li class='breadcrumb-item'><a href='subject.php'>Subject</a></li>";
if (isset($_GET['prog_code'])) {
    // get program data
    $program = $admin->getProgram();
    $prog_name = $program->get_prog_desc();
    $prog_code = $program->get_prog_code();
    $links = "<li class='breadcrumb-item'><a href='programlist.php'>Program</a></li>"
            ."<li class='breadcrumb-item'><a href='program.php?prog_code=$prog_code'>$prog_code</a></li>";
}
$links .="<li class='breadcrumb-item active' aria-current='page'>View Subject</li>";

$prereq = $subject->get_prerequisite();
$coreq = $subject->get_corequisite();



$title =  "<div class='d-flex justify-content-between'>
    <h3>$sub_name</h3>
    <div class='buttons-con d-flex'>
        <button class='btn m-auto text-danger pt-1 px-1 archive-option'  id = '$sub_code'  > <i class='bi bi-archive me-1 ' ></i>Archive</button>
        <a href='subject.php?sub_code=$sub_code&action=edit' target='_self' class='btn m-auto text-primary pt-1 px-1'><i class='bi bi-pencil-square me-1'></i>Edit</a>
    </div>
</div>
<hr>";


?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class='breadcrumb-item'><a href='index.html'>Home</a></li>
            <?php echo $links;?>
        </ol>
    </nav>
    <?php echo $title; ?>
</header>
<!-- HEADER END -->
<h6><?php echo $prog_name; ?></h6>
<div id='add-subject-info' class='row justify-content-around mt-3'>
    <div class='col-sm-12 col-lg-6 col-xl-5 shadow-sm p-4 bg-light border rounded-3 text-start mb-4'>
        <h5 class='text-start p-0 fw-bold'>SUBJECT DETAILS</h5>
        <hr class='mt-1'>
        <div class='row p-0'>
                <div class='row mb-3'>
                    <div class='col-xl-4 fw-bold'>Subject code</div>
                    <div class='col-xl-8'><?php echo $sub_code; ?></div>
                </div>
                <div class='row mb-3'>
                    <div class='col-xl-4 fw-bold'>Name</div>
                    <div class='col-xl-8'><?php echo $sub_name; ?></div>
                </div>
                <div class='row mb-3'>
                    <div class='col-xl-4 fw-bold'>Semester</div>
                    <div class='col-xl-8'><?php echo $sub_semester; ?></div>
                </div>
                <div class='row mb-3'>
                    <div class='col-xl-4 fw-bold'>Grade Level</div>
                    <div class='col-xl-8'><?php echo $sub_grd_lvl; ?></div>
                </div>
                <div class='row mb-3'>
                    <div class='col-xl-4 fw-bold'>Type</div>
                    <div class='col-xl-8'><?php echo $sub_type; ?></div>
                </div>
            </div>
        </div>
        <div class='col-sm-12 col-lg-5 col-xl-6 shadow-sm p-4 bg-light border rounded-3 mb-4'>  
            <h6 class='col-xl-4 fw-bold'>PROGRAM/S</h6>
            <hr class='mt-1'>
            <div id='program-con' class='d-flex flex-wrap'>
            <?php  
                if ($sub_type === 'core') {
                    echo "<div class='flex-grow-1><p class='text-center'>This subject is under all of the offered programs/strand.</p></div>";
                }  else  if ($sub_type === 'specialized') {
                    $associatedProgram = $subject->get_program();
                    echo "<a href='program.php?prog_code=$associatedProgram' target='_blank' role='button' class='btn btn-outline-secondary rounded-pill'>$associatedProgram</a>";
                } else if ($sub_type === 'applied') {
                    $associatedProgram = $subject->get_programs();
                    foreach($associatedProgram as $element) {
                        echo "<a href='program.php?prog_code=$element' target='_blank' role='button' class='btn btn-outline-secondary rounded-pill'>$element</a>";
                    }
                } 
                  
            $countPre = count($prereq);
            $countCo = count($coreq);
            
            $requisite = '';
            if ($countPre) {
                $requisite = "<div>
                    <h6>Prerequisite <span class='badge rounded-circle bg-secondary'>$countPre</span></h6>
                    <div class='list-group ms-3'>";
                        foreach($prereq as $req) {
                            $requisite .= "<a href='subject.php?code=$req&state=view' class='list-group-item list-group-item-action'>$req</a>";
                        }
                    $requisite .= "</div>
                </div>";
            } 

            if ($countCo) {
                $requisite .= "<div class='mt-3'>
                    <h6>Corequisite <span class='badge rounded-circle bg-secondary'>$countCo</span></h6>
                    <div class='list-group ms-3'>";
                        foreach($coreq as $req) {
                            $requisite .= "<a href='subject.php?code=$req&state=view' class='list-group-item list-group-item-action'>$req</a>";
                        }
                    $requisite .= "</div>
                </div>";
            }

            if (!($countPre && $countCo)) {
                $requisite = "<p class='text-center'>No subject set</p>";
            }
            ?>
            </div>
            <h6 class='text-start fw-bold mt-4'>PREREQUISITE | COREQUISITE SUBJECTS</h6>
            <hr class='mt-1'>
            <div class='flex-grow-1'><?php echo $requisite; ?></div>
        </div>
    </div>
</div>
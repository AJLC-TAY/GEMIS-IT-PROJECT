<?php
session_start();
$_SESSION['user_type'] = 'FA';
$_SESSION['id'] = 1;
$_SESSION['sy_id'] = 15;
$_SESSION['sy_desc'] = '2021 - 2022';
$_SESSION['enrollment'] = 0;
$_SESSION['roles'] = ['can_enroll', 'award_coor'];
include_once("../inc/head.html");
?>

<title>Student Profile | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>

<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Profile</a></li>
        </ol>
    </nav>
</header>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <h4 class="my-auto fw-bold">Student Profile</h4>
                            <div class='container my-3'>
                                <div class="card p-3 text-center">
                                    <div class="">
                                        <nav id="myTab">
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-link active" id="nav-gen-info-tab" data-bs-toggle="tab" data-bs-target="#gen-info" type="button" role="tab" aria-controls="gen-info" aria-selected="true">General Information</a>
                                                <a class="nav-link" id="nav-docu-tab" data-bs-toggle="tab" data-bs-target="#docu" type="button" role="tab" aria-controls="docu" aria-selected="false">Documents</a>
                                            </div>
                                        </nav>
                                    </div>
                                    <div class="tab-content" id="myTabContent">
                                        <!-- GENERAL INFORMATION -->
                                        <div class="tab-pane fade bg-white p-4 show active" id="gen-info" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="row w-100 h-auto text-start mx-auto">
                                                <!-- <h5>GENERAL INFORMATION</h5> -->
                                                <!-- <hr> -->
                                                <div class="row p-0">
                                                    <!-- PROFILE PICTURE -->
                                                    <div class="col-xl-3">
                                                        <img src='$image' alt='Profile image' class='rounded-circle' style='width: 250px; height: 250px;'>
                                                        <br>
                                                        <p><span class="fw-bold">Student LRN: </span></p> 
                                                        <button class='btn btn-outline-secondary ms-2 mb-2 w-100' title='Edit'>EDIT STUDENT INFO</button>
                                                    </div>

                                                    <!-- PROFILE PICTURE END -->
                                                    <!-- INFORMATION DETAILS -->
                                                    <div class="col-xl-7 ms-5">
                                                        <div class="row">
                                                            <h6><b>General Information</b></h6>
                                                            <ul class='list-group ms-3'>
                                                                <li class='list-group-item'>Name: $name<br>
                                                                <li class='list-group-item'>Gender: {$userProfile->get_sex()}<br>
                                                                <li class='list-group-item'>Age: {$userProfile->get_age()}<br>
                                                                <li class='list-group-item'>Birthdate: {$birthdate}<br>
                                                                <li class='list-group-item'>Birth Place: $birth_place<br>
                                                                <li class='list-group-item'>Indeginous Group: $indigenous_group<br>
                                                                <li class='list-group-item'>Mother Tongue: $mother_tongue<br>
                                                                <li class='list-group-item'>Religion: $religion</ul;>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <h6><b>Contact Information</b></h6>
                                                            <ul class='list-group ms-3'>
                                                                <li class='list-group-item'>Home Address: $add<br>
                                                                <li class='list-group-item'>Cellphone No.: $cp_no
                                                            </ul>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <h6><b>Contact Persons</b></h6>
                                                            <h6>PARENT/S</h6>
                                                            <ul class='list-group ms-3'>
                                                                <li class='list-group-item'>$parent's Name: $name </li>
                                                                <li class='list-group-item'>Occupation: $occupation</li>
                                                                <li class='list-group-item'>Contact Number: $no</li>
                                                            </ul>
                                                            <h6 class='mt-3'>GUARDIAN/S</h6>
                                                            <ul class='list-group ms-3'>
                                                                <li class='list-group-item'>Guardian's Name: $guardian_name</li>
                                                                <li class='list-group-item'>Relationship: $guardian_relationship</li>
                                                                <li class='list-group-item'>Contact Number: $guardian_cp_no</li>'
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- DOCUMENTS TAB -->
                                    <div class="tab-pane fade bg-white p-4" id="docu" role="tabpanel" aria-labelledby="docu-tab">
                                        <div class="row w-100 h-auto text-start mx-auto">
                                            <div class="row p-0">
                                                <div class="row">
                                                    <div class="col-md-4 card">
                                                        <div class="thumbnail">
                                                            <div class="caption">
                                                                <p class="fw-bold text-center">PSA DOCUMENT</p>
                                                            </div>
                                                            <!-- KES DITO MO PO ILAGAY HAHAH -->
                                                            <!-- <a href="../assets/psa_preview.jpg"> -->
                                                            <img id="psa" src="../assets/psa_preview.jpg" class="img-responsive" alt="PSA document" style="width:100%">
                                                            <!-- </a> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FOOTER START -->
                <?php include_once("../inc/footer.html"); ?>
                <!-- FOOTER END -->
            </section>
        </section>
    </section>
    <!-- MAIN CONTENT END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0; right: 0;">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 9999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->

    <!--BOOTSTRAP TABLE JS-->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <!--    --><?php //echo $js; 
                ?>
    <script type='module' src='../js/admin/faculty.js'></script>
</body>

</html>
<?php include_once("../inc/head.html"); ?>
<title>Curriculum List | GEMIS</title>
</head>

<body>
    <?php
    include('../class/Administration.php');
    $admin = new Administration();
    ?>
    <!-- SPINNER
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> -->
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
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
                            <!-- Section List: With slot -->
                            <div id='subject-con' class=''>
                                <hr>
                                <input id="search-subject" type="text" class="form-control mb-4" placeholder="Search subject here ...">
                                <div class="assigned-sub-con list-group border">
                                    <?php
                                    $subjects = $admin->listSubjects('subject');
                                    $assigned_sub = $admin_user->get_subjects();
                                    echo "<div id='empty-as-msg' class='list-group-item " . (count($assigned_sub) > 0 ? "d-none" : "") . "' aria-current='true'>
                                    <div class='d-flex w-100'>
                                        <div class='mx-auto p-3 d-flex flex-column justify-content-center'>
                                            <h6>No assigned subject</h6>
                                            <button class='edit-as-btn btn btn-success btn-sm w-auto' data-action='Assign subject' data-bs-toggle='modal' data-bs-target='#as-modal'>Assign</button>
                                        </div>
                                    </div>
                                </div>";

                                    foreach ($assigned_sub as $subject) {
                                        $type = $subject->get_sub_type();
                                        $sub_code = $subject->get_sub_code();
                                        echo "<a target='_blank' href='subject.php?sub_code=$sub_code' class='list-group-item list-group-item-action' aria-current='true'>
                                            <div class='d-flex w-100 justify-content-between'>
                                                <p class='mb-1'>{$subject->get_sub_name()}</p>
                                                <small>$type</small>
                                            </div>
                                            <small class='mb-1 text-secondary'><b>{$subject->get_for_grd_level()}</b> | $sub_code</small>
                                        </a>";
                                    }

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--MAIN CONTENT END-->
                <!--FOOTER START-->
                <?php include_once("../inc/footer.html"); ?>
                <!--FOOTER END-->
            </section>
        </section>
    </section>


</body>
<script type="text/javascript" src="../js/common-custom.js"></script>

</html>
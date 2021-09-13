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
                            <hr>

                            <div class='d-flex w-100 justify-content-between'>
                                <p>Student Name: Alcantara, Leonard Galutan</p>
                                <p>Section: ABM11</p>
                                </div>
                            <!-- Section List: With slot -->
                            <div id='subject-con' class=''>
                                <input id="search-subject" type="text" class="form-control form-control-sm" placeholder="Search section here ...">
                                <div class="assigned-sub-con list-group border">
                                    <?php
                                    $available_subjects = $admin->listAvailableSection();
                                    foreach ($available_subjects as $subject) {
                                        $name = $subject['name'];
                                        $adviser = "Adviser: " . "";
                                        $slot = "Available Slot: " . $subject['slot'];
                                        echo "<a target='_blank' href='#' class='list-group-item list-group-item-action' aria-current='true'>
                                            <div class='d-flex w-100 justify-content-between'>
                                                <p class='mb-1'>$name</p>
                                                <small>$slot</small>
                                            </div>
                                            <small class='mb-1 text-secondary'>$adviser</small>
                                        </a>";
                                    }
                                    ?>
                                </div>
                                <div>
                                    <a id="transfer-full" class="link btn w-auto mx-auto" data-bs-toggle='collapse' href='#section-table'><small>Transfer to full section? *other term*</small></a>
                                </div>
                                <div id='section-table' class='collapse mt-3'>
                                    <table id="subject-table" class="table-striped table-sm">
                                        <thead class='thead-dark'>
                                            <div class="d-flex justify-content-between mb-1">
                                                <!-- SEARCH BAR -->
                                                <span class="flex-grow-1 me-3">
                                                    <input id="search-sub-input" type="search" class="form-control form-control-sm" placeholder="Search subject here">
                                                </span>
                                            </div>
                                            <tr>
                                                <th scope='col' data-width="400" data-halign="center" data-align="left" data-sortable="true" data-field="sectiob_name">Section</th>
                                                <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="student">Student to swap</th>
                                                <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
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
<?php
require ("../inc/head.html");
require ("../class/Administration.php");
//$admin = new Administration();
//$sections = $admin->listSection();
$school_year = 12;
?>

<title>Enrollment Setup 1 | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>

<body>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Enrollment Setup 1</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Enrollment Setup 1: Faculty Enrollment Privileges</h3>
                                </div>
                            </header>
                            <!-- FACULTY SETUP TABLE -->
                            <div class="container mt-1">
                                <div class="card w-100 h-auto bg-light">
                                    <table id="table" class="table-striped">
                                        <thead class='thead-dark'>
                                            <div class="row justify-content-between mb-3">
                                                <!-- SEARCH BAR -->
                                                <div class="col-md-7 d-flex">
                                                    <form>
                                                        <input id="search-input" type="search" class="form-control mb-0 me-2" placeholder="Search something here ...">
                                                        <div class="d-flex">
                                                            <input type="reset" class="form-control mb-0 me-1 btn-dark" value="Clear"/>
                                                            <div class="dropdown">
                                                                <button class="btn shadow" type="button" id="section-filter" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    Filter
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="section-filter">
                                                                    <li><a id="all" class="filter-item dropdown-item active">All</a></li>
                                                                    <li><a id="can-enroll" class="filter-item dropdown-item">Can enroll</a></li>
                                                                    <li><a id="cant-enroll" class="filter-item dropdown-item">Cannot enroll</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-5 d-flex justify-content-end">
                                                    <button id="rm-enroll-priv" class="enroll-priv-btn btn btn-secondary btn-sm me-1" title=''>Remove enrollment access</button>
                                                    <button id="enroll-priv" class="enroll-priv-btn btn btn-primary btn-sm" title=''>Grant enrollment access</button>
                                                </div>
                                            </div>

                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="200" data-align="left" data-field="teacher_id">FID</th>
                                                <th scope='col' data-width="500" data-align="left" data-sortable="true" data-field="name">Name</th>
                                                <th scope='col' data-width="300" data-align="center" data-field="can-enroll">Can Enroll</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div class="row justify-content-end mt-3">
                                        <div class="col-auto">
                                            <button class="btn btn-primary d-inline-flex">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once("../inc/footer.html"); ?>
                <!--footer end-->
            </section>
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
 
    <!-- JQUERY FOR BOOTSTRAP TABLE -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!-- CUSTOM JS -->
    <script type='text/javascript' src="../js/common-custom.js"></script>
    <script type="text/javascript" src="../js/admin/enroll-faculty-priv.js"></script>
<!--    <script>-->
<!--        function togglePrivilege (teacherID) {-->
<!--            console.log($(this).hasClass("can-edit-btn"))-->
<!--            console.log(teacherID)-->
<!--        }-->
<!--    </script>-->
</body>
</html>
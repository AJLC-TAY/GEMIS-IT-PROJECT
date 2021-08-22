<?php include_once("../inc/head.html"); ?>
<title>Student Information | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

<body>
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper"></section>
            <div class="row">
                <div class="col-lg-11">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="studentList.php">Student</a></li>
                                    <li class="breadcrumb-item active"><a href="studentInfo.php">Personal Details</a></li>
                                    
                                </ol>
                            </nav>
                            <h3 class="fw-bold">Student Information</h3>
                        </header>
                        <!-- MAIN CONTENT -->
                        <!-- Photo -->
                        <div class="container mt-2 col-3">
                            <div class="photo">
                                Insert 2x2 ID
                            </div>
                            <div class="buttons mt-3">
                                <h5 class="mb-4">STUDENT ID: </h5>
                                <button class="btn btn-success ms-2 mb-2 w-100" title='Edit Profile'>EDIT PROFILE</button>
                                <button class="btn btn-success ms-2 mb-2 w-100" title='Transfer Student'>TRANSFER STUDENT</button>
                                <button class="btn btn-secondary ms-2 mb-2 w-100" title='Reset Password'>RESET PASSWORD</button>
                            </div>

                        </div>
                        <!-- Personal Details -->
                        <div class="container mt-4 col-8">
                            <div class="card body w-100 h-auto">
                                <div class="row col-12">
                                    <h4 class="fw-bold">GENERAL INFORMATION</h4>
                                    <ul class="list-group ms-3">
                                        <li class="list-group-item">Name:</li>
                                        <li class="list-group-item">Gender:</li>
                                        <li class="list-group-item">Age:</li>
                                        <li class="list-group-item">Birthdate:</li>
                                        <li class="list-group-item">Birth Place:</li>
                                        <li class="list-group-item">Indeginous Group:</li>
                                        <li class="list-group-item">Mother Tongue:</li>
                                        <li class="list-group-item">Religion:</li>
                                    </ul>
                                </div>
                                <div class="row col-12">
                                    <h4 class="mt-3 fw-bold">CONTACT INFORMATION</h4>
                                    <ul class="list-group ms-3">
                                        <li class="list-group-item">Home Address: </li>
                                        <li class="list-group-item">Contact Number:</li>
                                    </ul>
                                </div>

                                <div class="row col-12">
                                     <h4 class="mt-3 fw-bold">CONTACT PERSONS</h4>
                                     <h5>PARENT/S</h5>
                                     <ul class="list-group ms-3">
                                        <li class="list-group-item">Father's Name:</li>
                                        <li class="list-group-item">Occupation:</li>
                                        <li class="list-group-item">Contact Number:</li>
                                        <li class="list-group-item">Mother's Name:</li>
                                        <li class="list-group-item">Occupation:</li>
                                        <li class="list-group-item">Contact Number:</li>
                                    </ul>
                                    <h5 class="mt-3 fw-bold">GUARDIAN/S</h5>
                                    <ul class="list-group ms-3">
                                        <li class="list-group-item">Guardian's Name:</li>
                                        <li class="list-group-item">Occupation:</li>
                                        <li class="list-group-item">Relationship to the Guardian:</li>
                                        <li class="list-group-item">Contact Number:</li>
                                    </ul>
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
    </body>
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>

    <script type="text/javascript" src="../js/admin/subject.js"></script>
</html>
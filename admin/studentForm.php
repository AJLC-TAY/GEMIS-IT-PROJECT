<?php include_once("../inc/head.html"); ?>
<title>Edit Student Information | GEMIS</title>
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
                <div class="col-lg-10">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="studentList.php">Student</a></li>
                                    <li class="breadcrumb-item"><a href="studentInfo.php">Personal Details</a></li>
                                    <li class="breadcrumb-item active"><a href="studentForm.php">Edit</a></li>
                                </ol>
                            </nav>
                            <h2>Edit Student Information</h2>
                        </header>
                        <!-- MAIN CONTENT -->
                        <!-- Photo -->
                        <div class="container mt-2 col-3">
                            <div class="photo">
                                Insert 2x2 ID
                            </div>
                            <div class="buttons mt-3">
                                <h5 class="mb-4">STUDENT ID: </h5>
                                
                            </div>

                        </div>
                         <!-- Form -->
                         <div class="container mt-4 col-9">
                                <div class="card body w-100 h-auto">
                                    <form action="action.php" method="POST">
                                        <h4>STUDENT INFORMATION</h4>
                                        <div class="form-group row">
                                            
                                            <div class="col-6">
                                                <label class="col-form-label">PSA Birth Certificate</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example">
                                            </div>
                                            
                                            <div class="col-6">
                                                <label class="col-form-label">Learner's Reference Number</label>
                                                <input class="form-control" type="number" placeholder="" aria-label="default input example">
                                            </div>
                                            
                                            <div class="col-6">
                                                <label class="col-form-label">Last Name</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example">
                                            </div>
                                            
                                            <div class='col-6'>
                                                <label class="col-form-label">First Name</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>

                                            <div class='col-6'>
                                                <label class="col-form-label">Middle Name</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>

                                            <div class='col-4'>
                                                <label class="col-form-label">Suffix</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            
                                            <div class="form-group col-3">
                                                <label class="col-form-label">Birthdate</label>
                                                <div class='input-group date' id='datepicker'>
                                                    <input type='date' class="form-control" />
                                                </div>
                                            </div>
                                            <div class='col-4'>
                                                <label class="col-form-label">Birth Place</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>

                                            <div class='col-2'>
                                                <label class="col-form-label">Age</label>
                                                <input class="form-control" type="number" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <div class="col-3">
                                                <label class="col-form-label">Sex</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-select" id="inputGroupSelect03" aria-label="Example select with button addon">
                                                        <option selected>Choose...</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class='col-6'>
                                                <label class="col-form-label">Contact Number</label>
                                                <input class="form-control" type="number" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <div class="col-7">
                                                <label class="col-form-label me-4">Belonging to any Indeginous Group? </label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                                </div>
                                            </div>

                                            
                                            <div class='col-5'>
                                                <label class="col-form-label text-start">If yes, please specify</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            
                                            <div class="col-6">
                                                <label class="col-form-label">Mother Tongue</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example">
                                            </div>
                                            
                                            <div class="col-6">
                                                <label class="col-form-label">Religion</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example">
                                            </div>
                                            
                                        </div>
                                        <div class="form-group row">
                                            <h5>ADDRESS</h5>
                                            <div class="col-2">
                                                <label class="col-form-label">House No.</label>
                                                <input class="form-control" type="number" placeholder="" aria-label="default input example">
                                            </div>

                                            <div class="col-4">
                                                <label class="col-form-label">Street</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example">
                                            </div>

                                            <div class="col-6">
                                                <label class="col-form-label">Barangay</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example">
                                            </div>

                                            <div class="col-6">
                                                <label class="col-form-label">City/Municipality</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example">
                                            </div>

                                            <div class="col-4">
                                                <label class="col-form-label">Province</label>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example">
                                            </div>
                                            
                                            <div class="col-2">
                                                <label class="col-form-label">Zip Code</label>
                                                <input class="form-control" type="number" placeholder="" aria-label="default input example">
                                            </div>
                                            
                                        </div>
                                    </form>
                                </div>
                                <div class="card w-100 h-auto mt-4">
                                    <form action="action.php" method="POST">
                                        <h4> PARENT/GUARDIAN'S INFORMATION</h4>
                                        <div class="form-group row">
                                            <h5>FATHER</h5>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Father's Last Name</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Father's First Name</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Father's Middle Name</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Contact Number</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Occupation</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <h5>MOTHER</h5>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Mother's Last Name</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Mother's First Name</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Mother's Middle Name</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Contact Number</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Occupation</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <h5>GUARDIAN</h5>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Guardian's Last Name</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Guardian's First Name</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Guardian's Middle Name</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Contact Number</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Occupation</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Relationship to the Guardian</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class="form-control" type="text" placeholder="" aria-label="default input example"> 
                                            </div>
                                        </div>

                                    </form>

                                </div>
                                <div class="row">
                                    <div class="mt-4 d-flex flex-row-reverse">
                                        <button type="button" class="btn btn-danger">Cancel</button>
                                        <button type="button" class="btn btn-success me-2">Save</button>
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
    </body>
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>

    <script type="text/javascript" src="../js/admin/subject.js"></script>
</html>
<?php include_once("../inc/head.html"); 
      require_once("../class/Administration.php");
      $admin = new Administration();
      $subjects = $admin->listSubjects();
?>
<title>Add Faculty | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

<body>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
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
                                    <li class="breadcrumb-item"><a href="facultyList.php">Faculty</a></li>
                                    <li class="breadcrumb-item active">Add Faculty</li>
                                </ol>
                            </nav>
                            <h2>Add Faculty</h2>
                        </header>
                        <!-- MAIN CONTENT -->
                        <form id='faculty-form' action="action.php" method="POST" enctype="multipart/form-data">
                            <div class="form-row row">
                                <div class="form-group col-md-4">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="form-group col-md-4">
                                    <label for="extensionname">Extension Name</label>
                                    <input type="text" class="form-control" id="extensionname" name="extensionname" placeholder="Extension Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cpnumber">Cellphone No.</label>
                                    <input type="number" class="form-control" id="cpnumber" name="cpnumber" placeholder="Cellphone No.">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="form-group col-md-2">
                                    <label for="age">Age</label>
                                    <input type="number" class="form-control" id="age" name="age" placeholder="Age">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender" class="form-select form-select">
                                        <option selected value="NULL">-- Select gender --</option>
                                        <option value="F">Female</option>
                                        <option value="M">Male</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="birthdate">Birthdate</label>
                                    <input type="date" class="form-control" name="birthdate">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="department">Department</label>
                                    <select id="department" name="department" class="form-select form-select">
                                        <option selected value="NULL">-- Select department --</option>
                                        <option value=""></option>>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="form-group col-md-4">
                                    <label for="facultyAccess">Faculty Access</label>
                                    <div class="form-check">
                                        <input class="form-check-input" name="access[]" type="checkbox" value="editGrades">
                                        <label class="form-check-label">
                                            Edit Grades
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" name="access[]" type="checkbox" value="enrollment">
                                        <label class="form-check-label">
                                            Enrollment
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" name="access[]" type="checkbox" value="awardreport">
                                        <label class="form-check-label">
                                            Award Report
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="photo" class="form-label">Faculty ID Photo</label>
                                    <input class="form-control form-control-sm" id="photo" name="image" type="file">
                                </div>
                            </div>
                            <br>
                            <div class="collapse-table">
                                <input class='btn btn-primary' data-bs-toggle='collapse' data-bs-target='#assign-subj-table' type='button' value='ASSIGN SUBJECT'>
                                <div id='assign-subj-table' class='collapse mt-3'>
                                    <div class="overflow-auto bg-white p-3 rounded-sm shadow-sm" style="height: 300px;">
                                        <table class='table table-bordered table-hover table-striped' style="height: auto;">
                                            <thead>
                                                <tr class='text-center'>
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1">
                                                            <input class="form-control" list="subjectOptions" id="search-input" placeholder="Search subject name to get subject code here ...">
                                                            <datalist id="subjectOptions">
                                                                <?php 
                                                                    foreach($subjects as $subject) {
                                                                        $code = $subject->get_sub_code();
                                                                        echo "<option value='$code' class='sub-option'>$code - {$subject->get_sub_name()}</option>";
                                                                    }
                                                                ?>
                                                            </datalist>
                                                        </div>
                                                        <div class='ms-1'>
                                                            <button class="add-subject btn btn-dark"><i class="bi bi-plus-lg me-1"></i> SUBJECT</button>
                                                            <button class='remove-all-btn btn btn-outline-danger'><i class="bi bi-x-lg me-1"></i>SELECTED</button>
                                                        </div>
                                                    </div>
                                                    <th scope="col"><input type="checkbox" /></th>
                                                    <th scope='col'>CODE</th>
                                                    <th scope='col'>SUBJECT NAME</th>
                                                    <th scope='col'>TYPE</th>
                                                    <th scope='col'>ACTION</th>
                                                </tr>
                                            </thead>
    
                                            <tbody>
                                                <tr id="emptyMsg" class='text-center'>
                                                    <td colspan='5'>No assigned subject</td>
                                                </tr>
                                            </tbody>
                                    </div>
                                    </table>
                                </div>
                                <div class="back-btn d-flex justify-content-end">
                                    <input type="hidden" name="action" value="addFaculty">
                                    <input type="submit" class="btn btn-success btn-space save-btn" name="submit" value="SUBMIT">
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--main content end-->
            <!--footer start-->
            <?php include_once("../inc/footer.html"); ?>
            <!--footer end-->
        </section>
    </section>
        <!-- TOAST -->
        <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div class="position-absolute" style="bottom: 20px; right: 25px;">
            <div class="toast warning-toast bg-danger text-white" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body"></div>
            </div>

            <div class="toast add-toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                </div>
            </div>
        </div>
    </div>

</body>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript">
    let subjects = <?php echo json_encode($subjects); ?>;
</script>
<script type="text/javascript" src="../js/admin/facultyForm.js"></script>

</html>

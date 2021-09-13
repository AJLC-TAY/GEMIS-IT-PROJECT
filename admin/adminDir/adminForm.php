<?php 
include_once("../class/Administration.php");
$admin = new Administration();
$action = $_GET['action'];
$quarter_list = array('0' => '-- Select quarter --', '1' => 'First quarter',
                      '2' => 'Second quarter', '3' => 'Third quarter', '4' => 'Fourth quarter');
$quarter_opt = '';
$enroll_stat_msg = "No enrollment";
$display = 'd-none';

if ($action == 'add') {
    $header = "Add";
    $last_name = '';
    $first_name = '';
    $middle_name = '';
    $ext_name = '';
    $cp_no = '';
    $email = '';
    $age = '';
    $sex = "";
    $button = "Submit";
    $id_header = "<p><small class='text-secondary'>Please complete the following: </small></p>";
} else if ($action == 'edit') {
    $header = "Edit";
    // Get and prepare faculty information
    $admin_user = $admin->getAdministrator();
    $id_header = "<h5 class='mb-3'>ID: {$_GET['id']}</h5>";
    $last_name = $admin_user->last_name;
    $first_name = $admin_user->first_name;
    $middle_name = $admin_user->middle_name;
    $ext_name = $admin_user->ext_name;
    $cp_no = $admin_user->cp_no;
    $email = $admin_user->email;
    $age = $admin_user->age;
    $sex = $admin_user->sex;
    $button = "Save";
}
?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="admin.php">Administrator</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $header; ?></li>
        </ol>
    </nav>
    <h3 class="fw-bold"><?php echo $header; ?> administrator</h3>
</header>
<!-- HEADER END -->
<!-- SUBJECT TABLE -->
<div class="container mt-4">
    <div class="card h-auto bg-light mx-auto" style='width: 80%;'>
        <?php echo $id_header; ?>
        <form id='admin-form' action="action.php" method='POST'>
            <!-- NAME -->
            <h5 class="fw-bold">Personal Information</h5>
            <div class='form-row row mb-3'>
                <div class='form-group col-md-4'>
                    <label for='lastname'>Last Name</label>
                    <input type='text' value='<?php echo $last_name; ?>' class='form-control' id='lastname' name='lastname' placeholder='Last Name' required>
                </div>
                <div class='form-group col-md-4'>
                    <label for='firstname'>First Name</label>
                    <input type='text' value='<?php echo $first_name; ?>' class='form-control' id='firstname' name='firstname' placeholder='First Name' required>
                </div>
                <div class='form-group col-md-4'>
                    <label for='middlename'>Middle Name</label>
                    <input type='text' value='<?php echo $middle_name; ?>' class='form-control' id='middlename' name='middlename' placeholder='Middle Name' required>
                </div>
                <div class='form-group col-md-4'>
                    <label for='extensionname'>Extension Name</label>
                    <input type='text' value='<?php echo $ext_name; ?>' class='form-control' id='extensionname' name='extensionname' placeholder='Extension Name'>
                </div>
            </div>
            <!-- NAME END -->
            <!-- SEX INFO -->
            <div class="form-row row mb-3">
                <div class='form-group col-md-2'>
                    <label for='age'>Age</label>
                    <input value='<?php echo $age; ?>' class='number form-control' id='age' name='age' placeholder='Age' required>
                </div>
                <div class='form-group col-md-3'>
                    <label for='sex'>Sex</label>
                    <div class="d-flex">
                        <?php
                        $sexOpt = ["m" => "Male", "f" => "Female"];
                        foreach ($sexOpt as $id => $value) {
                            echo "<div class='form-check'>"
                                ."<input class='form-check-input' type='radio' name='sex' id='$id' value='$id' " . (($sex == $value) ? "checked" : "") . ">"
                                ."<label class='form-check-label me-2' for='$id'>$value"
                                ."</label>"
                                ."</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- SEX INFO END -->
            <!-- CONTACT INFO -->
            <hr class="mt-0 mb-3">
            <h5 class="fw-bold">Contact Information</h5>
            <div class="form-group row">
                <div class='form-group col-md-4'>
                    <label for='cpnumber'>Cellphone No.</label>
                    <input type='text' value='<?php echo $cp_no; ?>' class='number form-control' id='cpnumber' name='cpnumber' placeholder='Cellphone No.'>
                </div>
                <div class='form-group col-md-4'>
                    <label for='email'>Email</label>
                    <input type='email' value='<?php echo $email; ?>' class='form-control' id='email' name='email' placeholder='Email' required>
                </div>
            </div>
            <!-- CONTACT INFO END -->
            <!-- DECISION -->
            <div class="form-group row mt-2">
                <div class="d-flex justify-content-end">
                    <input type='hidden' name='action' value='<?php echo $action; ?>Administrator'>
                    <a href='admin.php' class='btn btn-outline-danger me-2'>Cancel</a>
                    <input type='submit' form='admin-form' class='btn btn-success' value='<?php echo $button; ?>'>
                </div>
            </div>
            <!-- DECISION END -->
        </form>
    </div>
</div>
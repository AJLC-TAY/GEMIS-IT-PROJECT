<?php
include_once ("../class/Administration.php");
$admin = new Administration();
$user_id = $_SESSION['id'];
$user = $admin->getAdministrator($user_id);
?>
<!-- HEADER -->
 <header>
     <!-- BREADCRUMB -->
     <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="index.php">Home</a></li>
             <li class="breadcrumb-item active" aria-current="page">Administrator</li>
         </ol>
     </nav>
     <div class="d-flex justify-content-between align-items-center mb-3">
         <h3 class="fw-bold">Administrator</h3>
     </div>
 </header>
 <!-- HEADER END -->
<div class="container mt-1">
    <div class="row justify-content-between align-items-center mb-3">
        <div class="col-auto">
            <h5><b>INFORMATION</b></h5>
        </div>
        <div class="col-auto d-flex">            
            <button class="btn btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#confirmation-modal"><i class="bi bi-trash me-2"></i>Delete Account</button>
            <button class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#change-pass-modal">Change Password</button>
            <a href="admin.php?id=<?php echo $user_id; ?>&action=edit" role="button" class="btn btn-primary ms-2"><i class="bi bi-pencil-square me-2"></i>Edit</a>


        </div>
    </div>
    <div class="card w-100 h-auto bg-light">
        <div class="row">
            <div class="col-md-6">
                <h6><b>GENERAL INFORMATION</b></h6>
                <ul class='list-group ms-3'>
                    <li class='list-group-item'><b>UID: </b><?php echo $user->admin_id; ?><br>
                    <li class='list-group-item'><b>Name: </b><?php echo "{$user->last_name}, {$user->first_name} {$user->middle_name} {$user->ext_name}"; ?><br>
                    <li class='list-group-item'><b>Age: </b><?php echo $user->age; ?><br>
                    <li class='list-group-item'><b>Sex: </b><?php echo $user->sex; ?>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold">CONTACT INFORMATION</h6>
                <ul class='list-group ms-3'>
                    <li class='list-group-item'><b>Cellphone No: </b><?php echo $user->cp_no; ?><br>
                    <li class='list-group-item'><b>Email: </b><?php echo $user->email; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
 <!-- OTHER MEMBERS TABLE -->
 <div class="container mt-4">
     <div class="d-flex justify-content-between align-items-center mb-3">
         <h5><b>OTHER ADMINS</b></h5>
         <span>
            <a href="admin.php?action=add" id="add-btn" class="btn btn-success" title='Add new admin member'><i class="bi bi-plus me-2"></i>Add admin</a>
         </span>
     </div>
     <div class="card w-100 h-auto bg-light">
         <table id="table" class="table-striped">
             <thead class='thead-dark'>
                 <div class="d-flex justify-content-between mb-3">
                     <!-- SEARCH BAR -->
                     <span class="flex-grow-1 me-3">
                         <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                     </span>
                 </div>
                 <tr>
                     <!-- <th data-checkbox="true"></th> -->
                     <th scope='col' data-width="100" data-align="left" data-field="admin_id">ID</th>
                     <th scope='col' data-width="250" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                     <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field="age">Age</th>
                     <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field="sex">Sex</th>
                     <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field="cp_no">Contact Number</th>
                     <th scope='col' data-width="250" data-halign="center" data-align="left"  data-field="email">Email</th>
<!--                     <th scope='col' data-width="100" data-align="center" data-field="action">Action</th>-->
                 </tr>
             </thead>
         </table>
     </div>
 </div>
<!-- ACCOUNT DELETION MODAL -->
<div class="modal fade" id="confirmation-modal" tabindex="-1" aria-labelledby="modal deleteAccount" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Confirmation</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="delete-account-form" method="POST" action="deleteAccount.php">
                <div class="modal-body">
                    <p class="text-secondary"><small>Enter your password to confirm account deletion</small></p>
                    <div class="container">
                        <div class="form-group row">
                            <input id="password" type="password" name="code" class='form-control' placeholder="Password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn btn-sm" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="delete-account" form="delete-account-form" class="btn btn-danger btn-sm" value="Delete">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CHANGE PASSWORD MODAL -->
<div class="modal fade" id="change-pass-modal" tabindex="-1" aria-labelledby="modal deleteAccount" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Change password</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="change-pass-form" method="POST" action="deleteAccount.php">
                <div class="modal-body">
                    <p class="text-secondary"><small>Please complete the following</small></small></p>
                    <div class="container">
                        <div class="form-group row">
                            <!-- <label for="current-pass">Current password</label> -->
                            <input id="current-pass" type="password" name="current-pass" class='form-control' placeholder="Current password" required>
                        </div>
                    </div>
                    <p class="text-secondary"><small>Enter new password:</small></small></p>
                    <div class="container">
                        <div class="form-group row mt-3">
                            <!-- <label for="new-pass">New password</label> -->
                            <input id="new-pass" type="password" name="new-pass" class='form-control' placeholder="New password" required>
                        </div>
                        <div class="form-group row">
                            <!-- <label for="reenter-new-pass">Re-enter new password</label> -->
                            <input id="reenter-new-pass" type="password" name="reenter-new-pass" class='form-control' placeholder="Re-enter new password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-outline-secondary close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" name="change-pass" form="change-pass-form" class="btn btn-primary btn-sm" value="Change">
                </div>
            </form>
        </div>
    </div>
</div>
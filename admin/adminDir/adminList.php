<?php
include_once ("../class/Administration.php");
$admin = new Administration();
$id = $_SESSION['id'];
$user_id = $_SESSION['user_id'];
$user = $admin->getAdministrator($id);
?>
<script>
    let uid = <?php echo json_encode($user_id); ?>;
</script>
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
            <button id="delete-account-btn" class="btn btn-outline-danger ms-2"><i class="bi bi-trash me-2"></i>Delete Account</button>
            <button class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#change-pass-modal">Change Password</button>
            <a href="admin.php?id=<?php echo $id; ?>&action=edit" role="button" class="btn btn-primary ms-2"><i class="bi bi-pencil-square me-2"></i>Edit</a>


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
            <a href="admin.php?action=add" id="add-btn" class="btn btn-success" title='Add new admin member'><i class="bi bi-plus-lg me-2"></i>Add admin</a>
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
            <div class="modal-body">
                <form id="delete-account-form" method="POST">
                    <input type="hidden" name="action" value="deleteAdmin">
                    <p class="text-secondary"><small>Enter your password to confirm account deletion</small></p>
                    <div class="container">
                        <div class="form-group row">
                            <input id="password-delete" type="password" name="password-delete" class='form-control' placeholder="Password">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="close btn btn-secondary close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                <input type="submit" name="delete-account" form="delete-account-form" class="btn btn-danger btn-sm" value="Delete">
            </div>
        </div>
    </div>
</div>

<!-- CHANGE PASSWORD MODAL -->
<div class="modal fade" id="single-admin-confirm-modal" tabindex="-1" aria-labelledby="modal deleteAccount" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Warning</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>You are the <b>only administrator</b> in the system, deleting this account will set the admin account to default.<br>
                Would you like to proceed?</h6>
            </div>
            <div class="modal-footer">
                <button class="close btn  btn-primary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirmation-modal" class="btn  btn-outline-secondary btn-sm">Proceed</button>
            </div>
        </div>
    </div>
</div>

<!-- CHANGE PASSWORD MODAL -->
<div class="modal fade" id="change-pass-modal" tabindex="-1" aria-labelledby="modal deleteAccount" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Change Password</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <form id="change-pass-form" action="action.php" method="POST">
                        <div class="container">
                            <div class="form-group row">
                                <input type="hidden" name="uid" value="<?php echo $id; ?>">
                                <input type="hidden" name="action" value="changePassword">
                                <p class="text-secondary p-0"><small>Please complete the following</small></small></p>
                                <input id="current-pass" type="password" name="current" class='form-control form-control-sm' placeholder="Current password">
                            </div>
                            <div class="form-group row mt-3">
                                <p class="text-secondary p-0"><small>Enter new password:</small></small></p>
                                <input id="new-pass" type="password" name="new_password" class='form-control form-control-sm' placeholder="New password">
                            </div>
                            <div class="form-group row mt-2">
                                <input id="reenter-new-pass" type="password" name="reenter-new-pass" class='form-control form-control-sm' placeholder="Re-enter new password">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" name="change-pass" form="change-pass-form" class="btn btn-success btn-sm" value="Change">
                </div>
        </div>
    </div>
</div>
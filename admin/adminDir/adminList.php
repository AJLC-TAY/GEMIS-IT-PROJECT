<?php
include_once ("../class/Administration.php");
$admin = new Administration();
$user_id = $_SESSION['id'];
$user = $admin->getAdministrator();
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
<!--         <span>-->
<!--            <a href="admin.php?action=add" id="add-btn" class="btn btn-success" title='Add new admin member'>Add admin</a>-->
<!--         </span>-->
     </div>
 </header>
 <!-- HEADER END -->
<div class="container mt-1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Information</h5>
        div.flex
        <a href="admin.php?id=<?php echo $user_id; ?>&action=edit" role="button" class="btn link my-auto"><i class="bi bi-pencil-square me-2"></i>Edit</a>
    </div>
    <div class="card w-100 h-auto bg-light">
        <div class="row">
            <div class="col-md-7">
                <h6>Personal</h6>
                <p>ID: <?php echo $user['admin_id']; ?></p>
                <p>Name: <?php echo "{$user['last_name']}, {$user['first_name']} {$user['middle_name']} {$user['ext_name']}"; ?></p>
    <!--            <p>Age: --><?php //echo $user['age']; ?><!--</p>-->
                <p>Sex: <?php echo $user['sex']; ?></p>
            </div>
            <div class="col-md-5">
                <h6>Contact</h6>
                <p>Cellphone No.: <?php echo $user['cp_no']; ?></p>
                <p>Email: <?php echo $user['email']; ?></p>
            </div>
        </div>
    </div>
</div>
 <!-- OTHER MEMBERS TABLE -->
 <div class="container mt-5">
     <div class="d-flex justify-content-between align-items-center mb-3">
         <h5>Other Members</h5>
         <span>
            <a href="admin.php?action=add" id="add-btn" class="btn btn-success" title='Add new admin member'>Add admin</a>
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
                     <th scope='col' data-width="250" data-halign="center" data-align="left" data-sortable="true" data-field="full_name">Name</th>
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
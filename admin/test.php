<!DOCTYPE html>
<html>
<body>

<?php
require_once("../class/Administration.php");
$admin = new Administration();
$admin->listSYJSON();
// $txt = new stdClass();
// $txt->name = 'Alvin';
// $user = new stdClass();
// $user->name = 'Cutay';
// // $response = [$txt, $user];
// $response = ["data" => [$txt, $user], "archived" => [$txt, $user]]; 
?>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>


<table
  id="table"
  data-toggle="table"
  data-url="getAction.php?data=school_yr">
  <thead>
    <tr>
        <!-- <th data-checkbox="true"></th> -->
        <th scope='col' data-width="150" data-align="left" data-field="id">ID</th>
        <th scope='col' data-width="200" data-align="left" data-sortable="true" data-field="sy_year">School Year</th>
        <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field="grd_level">Grade Level</th>
        <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field="current_qtr">Current Quarter</th>
        <th scope='col' data-width="100" data-align="left" data-field="current_sem">Enrollment Status</th>
        <th scope='col' data-width="100" data-align="left" data-field="enrollment">Enrollment Status</th>
        <th scope='col' data-width="250" data-align="center" data-field="action">Action</th>
    </tr>
  </thead>
</table>
<script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>

<?php 
    // $user = uniqid("FA");
    // printf("Unique ID: %s\n", $user);
    // $user = uniqid("FA");
    // printf("Unique ID: %s\n", $user);
    // $user = uniqid("FA");
    // printf("Unique ID: %s\n", $user);
    // $user = uniqid("FA");
    // printf("Unique ID: %s\n", $user);
    // $user = uniqid("FA");
    // printf("Unique ID: %s\n", $user);
	
    
    // $users = array();
    // $i = 0;
    // while($i <= 5) {
    // 	$users[] = uniqid("FA");
    //     // sleep(1);
    // }
    
    // foreach($users as $user) {
    // 	printf("Unique ID: %s", $user);
    // }

?>


</body>
<!-- <script>
	let response = <?php //echo json_encode($response); ?>;
    console.log(response.data)
    console.log(response.archived)
</script> -->

</html>

<!DOCTYPE html>
<html>
<body>

<?php
$txt = new stdClass();
$txt->name = 'Alvin';
$user = new stdClass();
$user->name = 'Cutay';
// $response = [$txt, $user];
$response = ["data" => [$txt, $user], "archived" => [$txt, $user]]; 
?>

</body>
<script>
	let response = <?php echo json_encode($response); ?>;
    console.log(response.data)
    console.log(response.archived)
</script>

</html>

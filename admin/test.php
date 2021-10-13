<?php include "../inc/head.html"; ?>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>
<body>
<!--<form action="action.php" method="post">-->
<!---->
<!--    <input type="submit" name="validate" value="Submit">-->
<!--    <input type="submit" name="reject" value="Accept">-->
<!---->
<!--</form>-->
<!---->
<!--<ul id="container"></ul>-->
<!--<template id="test">-->
<!--    <li data-id='%code%' class='tile card shadow-sm p-0 position-relative'>-->
<!--        <a role='button' class='card-link btn btn-link start-0 top-0 end-0 bottom-0 h-100' style='z-index: 2;' href='curriculum.php?code=%code%'></a>-->
<!--        <div class='dropstart position-absolute top-0 end-0' style='z-index: 3;'>-->
<!--            <button type='button' class='btn kebab rounded-circle m-1' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button>-->
<!--            <ul class='dropdown-menu' style='z-index: 99;'>-->
<!--                <li><a class='dropdown-item' href='curriculum.php?code=%code%&state=edit'>Edit</a></li>-->
<!--                <li><button data-name='%name%' class='archive-option dropdown-item' id='%code%'>Archive</button></li>-->
<!--                <li><button data-name='%name%' class='delete-option dropdown-item' id='%code%'>Delete</button></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div class='card-body position-absolute d-flex-column justify-content-between start-0' style='top: 40px;'>-->
<!--            <div class='tile-content'>-->
<!--                <h4 class='card-title text-break'>%name%</h4>-->
<!--                <p class='card-text text-break'>%desc%</p>-->
<!--            </div>-->
<!--        </div>-->
<!--    </li>-->
<!--</template>-->
<!---->
<!--<div class="">-->
<!--    <a href="enrollment/enrollmentReport.php" target="_blank" class="btn btn-sm btn-primary" id='export'>Export</a>-->
<!--</div>-->
<?php
//$sy_id = 9;
//$dir_path = "../uploads/student/$sy_id";
//$cred_dir_path = "../uploads/credential/$sy_id";
//if (!file_exists($dir_path)) {
//    mkdir($dir_path);
//}
//if (!file_exists($cred_dir_path)) {
//    mkdir($cred_dir_path);
//}

//unlink("../uploads/faculty/1633440070_615c5146b02472.52287926.jpg");


include '../class/Administration.php';
$admin = new Administration();
// $admin->editAcademicDays();
echo json_encode($admin->getAwardDataFromSubject());
//print_r($admin->get_sy_info(9));
//session_start();
//$_SESSION['sy_id'] = 9;
//print_r($admin->listAllSubjectClasses());
//$img = $admin->getFaculty(37)->get_id_photo();
//print_r($img);
//echo "<img src='../upload/student/$img'>";

// print_r($admin->listSignatory());
// $admin->addSection(11, 'ABM', 1, 'C', 29, 54 );
//$admin->validateEnrollment(120089, 15, 1);  // 0 = pending 1 = accepted/ enrolled 2 cancelled
// echo json_encode($admin->getInitSYData());
// print_r($admin->listNotAdvisers());


// echo "jtest";
// echo json_encode($admin->createUser('ST'));
//$admin->getEnrollmentReportData(true);
//
//$tracks = [];
//$track_list = $_POST['tracks'];
//foreach($track_list as $track) {
//	$progs = $_POST["$track-programs"];
//	foreach($progs as $prog) {
//		$prog = [$prog => [$_POST["$prog-a-count"], $_POST["$prog-r-count"]]];
//		$tracks[$track] = $prog;
//	}
//}




    /**
     * Trims each element of the array and make each null if empty.
     * Returns a new array.
     * @param   array   $params
     * @return  array
     */
//    function preprocessData(array $params)
//    {
//        return array_map(function($e) {
//            return isset($e);
////            $e = trim($e);
////            return  $e ?? NULL;
//        }, $params);
//    }
//    $test = ['', 0, NULL, 'TeST'];
//    print_r(Administration::preprocessData($test));

?>

<form action="action.php" id="image-form" enctype="multipart/form-data" method="POST" class="form-control">
    <input type="hidden" name="action" value="validateImage">
    <div class="d-flex">
        <input type="file" name="image" accept="image/jpeg" class="form-control">
        <input type="submit" class="form-control btn btn-success" form="image-form" value="Submit">
    </div>
</form>
<?php include '../inc/footer.html'; ?>
<script>
    let template = $("#test")
    let array = [{code: '1', name: 'test1', desc: 'desc'}, {code: '1', name: 'test1', desc: 'desc'}, {code: '1', name: 'test1', desc: 'desc'}]
    console.log(template)
    array.forEach(e => {
        let clone = template.html()
        clone.replace("%code%", e.code)
        clone.replace("%name%", e.name)
        clone.replace("%desc%", e.desc)
  
        $("#container").append(clone)
    })

    $(function() {
        // $(document).on("click", "#export", function() {
        //     $.post("enrollment/enrollmentReport.php")
        // })
    })
</script>



</body>
<!-- <script>
	let response = <?php //echo json_encode($response); ?>;
    console.log(response.data)
    console.log(response.archived)
</script> -->

</html>

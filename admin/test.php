<?php include "../inc/head.html"; ?>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>
<form action="action.php" method="post">
<div class="card w-100 h-auto mt-4 p-4">
    <?php include("../class/Administration.php");
    $admin = new Administration();
    $admin->listCurriculumJSON();
    ?>
    <h4 class="fw-bold">Transferee Assessment form</h4>
    <div class="border p-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="row justify-content-center align-content-center">
                        <label for="school-last-attended" class="col-form-label col-md-3">School Last Attended</label>
                        <div class="col-md-9">
                            <textarea id="school-last-attended" name="trans-school" class="form-control form-control-sm" placeholder="Enter school name"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row justify-content-center align-content-center">
                        <label for="school-last-attended" class="col-form-label col-md-3">Track</label>
                        <div class="col-md-9">
                            <input type="text" name="trans-track" class="form-control form-control-sm" placeholder="Enter track (ex. ACADEMIC)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="row justify-content-center align-content-center">
                        <label for="school-last-attended" class="col-form-label col-md-3">Semester</label>
                        <div class="col-md-9">
                            <input type="text" name="trans-semester" class="form-control form-control-sm" placeholder="Enter semester (ex. First)">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row justify-content-center align-content-center">
                        <label for="school-last-attended" class="col-form-label col-md-3">School Year</label>
                        <div class="col-md-9">
                            <input type="text" name="trans-sy" class="form-control form-control-sm" placeholder="Enter school year (ex. 20XX - 20XX)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="subject-list mt-3">
        <h6>LIST OF SUBJECTS FOR <span id="chosen-subject"></span></h6>
        <div class="container">
            <table id="transfer-table" class="table table-sm table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <td colspan="2">GRADE 11</td>
                        <td colspan="2">GRADE 12</td>
                    </tr>
                    <tr>
                        <td>FIRST SEMESTER</td>
                        <td>SECOND SEMESTER</td>
                        <td>FIRST SEMESTER</td>
                        <td>SECOND SEMESTER</td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
    <input type="submit" value="Submit">
</form>

<!--table cell template-->
<template id="table-cell-template">
    <td>
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <input id="%ID%" type="checkbox" class="form-check-input" name="subjects[]" value="%ID%">
                </div>
                <label for="%ID%" class="form-check-label col-form-label col-10 py-0">
                    %SUBJECTNAME%
                </label>
            </div>
        </div>
    </td>
</template>
<?php include_once ("../inc/footer.html"); ?>

<script>
    // let data =   {
    //     "data":
    //         [
    //             {
    //                 "grade" : "11",
    //                 "data" : [
    //                     {
    //                         "semester": "1",
    //                         "subjects": [
    //                             {"sub_code": "BSMATH", "sub_name": "Business Math"},
    //                             {"sub_code": "BSMATH", "sub_name": "Business Math"}
    //                         ],
    //                         "count": "2"
    //                     },
    //                     {
    //                         "semester": "2",
    //                         "subjects": [
    //                             {"sub_code": "BSMATH" , "sub_name" : "Business Math"},
    //                             {"sub_code": "BSMATH" , "sub_name" : "Business Math"},
    //                             {"sub_code": "BSMATH" , "sub_name" : "Business Math"}
    //                         ],
    //                         "count" : "3"
    //                     }
    //                 ]
    //             },
    //             {
    //                 "grade" : "12",
    //                 "data" : [
    //                     {
    //                         "semester": "1",
    //                         "subjects": [
    //                             {"sub_code": "BSMATH", "sub_name": "Business Math"},
    //                             {"sub_code": "BSMATH", "sub_name": "Business Math"}
    //                         ],
    //                         "count": "2"
    //                     },
    //                     {
    //                         "semester": "2",
    //                         "subjects": [
    //                             {"sub_code": "BSMATH" , "sub_name" : "Business Math"},
    //                             {"sub_code": "BSMATH" , "sub_name" : "Business Math"},
    //                             {"sub_code": "BSMATH" , "sub_name" : "Business Math"},
    //                             {"sub_code": "BSMATH" , "sub_name" : "Business Math"}
    //                         ],
    //                         "count" : "4"
    //                     }
    //                 ]
    //             }
    //         ]
    // }

    let scheduleData = <?php $admin->getSubjectSchedule(); ?>;
    let test = scheduleData.data;
    // console.log(test)
    let template = $("#table-cell-template").html();
    let html = '';

    let elevenFirSem, elevenSecSem, twelveFirSem, twelveSecSem;
    console.log(test[1]);
    elevenFirSem = test[0].data.subjects.length;
    elevenSecSem = test[1].data.subjects.length;
    twelveFirSem = test[2].data.subjects.length;
    twelveSecSem = test[3].data.subjects.length;


    function renderCellHTML(sub) {
        if (sub) {
            return template.replaceAll("%ID%", sub.sub_code).replace("%SUBJECTNAME%", sub.sub_name);
        }
        return "<td></td>";
    }

    let count = Math.max(elevenFirSem, elevenSecSem, twelveFirSem, twelveSecSem);
    for (let i = 0; i < count; i++) {
        html += '<tr>';
        let subjectElevenFir = test[0].data.subjects[i] ?? "";
        let subjectElevenSec = test[1].data.subjects[i] ?? "";
        let subjectTwelveFir = test[2].data.subjects[i] ?? "";
        let subjectTwelveSec = test[3].data.subjects[i] ?? "";
        console.log(subjectElevenFir)
        html += renderCellHTML(subjectElevenFir);
        html += renderCellHTML(subjectElevenSec);
        html += renderCellHTML(subjectTwelveFir);
        html += renderCellHTML(subjectTwelveSec);
        html += '</tr>';
    }
    console.log(html);
   $("#transfer-table tbody").html(html);
</script>

</body>
</html>
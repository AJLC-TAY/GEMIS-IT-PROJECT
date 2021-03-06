import { commonTableSetup } from "../admin/utilities.js";

let tableSetup = {
    search: true,
    searchSelector: "#search-input",
    method: 'GET',
    uniqueId: 'lrn',
    idField: 'lrn',
    height: 800,
    ...commonTableSetup,
    pagination: false
};

let promotionSetup = {
    search: true,
    searchSelector: "#search-input",
    method: 'GET',
    uniqueId: 'stud_id',
    idField: 'stud_id',
    height: 400,
    url: `getAction.php?data=for-promotion&section=${code}`,
    ...commonTableSetup,
    pagination: false
};

let studentTable = $("#table").bootstrapTable(tableSetup);
let forPromotionStudentTable = $("#for-promotion-table").bootstrapTable(promotionSetup);

/** Changes the name of class in the card */
function changeName(name) {
    $("#class").html(name);
}

/**
 *
 * @param classType
 * @param url
 * @returns {*}
 */
function setTableData(classType, url) {
    studentTable.bootstrapTable("refresh", { url });
}

/**
 * Hides or shows grades column depending on the type of section specified.
 * @param {String} classType Values may be 'advisory' or 'sub-class'.
 */
function toggleGradesColumn(classType) {
    studentTable.bootstrapTable('hideColumn', ['grd_1', 'grd_2']);
}

function gradeStud(stat){
    var studGrades = document.getElementsByClassName("gen-ave");
        console.log(studGrades);
        studGrades.forEach(element => {

            var recordInfo = element['name'].split("/")
            var grades = {
                'id': recordInfo[0],
                'rep_id': recordInfo[1],
                'sem': recordInfo[2],
                'gen_ave': element['value'],
                'action': 'gradeAdvisory',
                'stat': stat
            };

            $.post("action.php", grades, function(data) {
                // console.log(grades);
                console.log(grades);
                studentTable.bootstrapTable("refresh");

            });

        });
        $(".number").attr('readOnly', true);
}

var submitMsg = "Submitted grades are final and are not editable. For necessary changes, contact the admin.";
var saveMsg = "Saved grades are editable within the duration of the current quarter.";
if (qtr == '2') {
    var promotionMsg = "Promoting the student will directly enroll him/her to the second semester. This action can not be undone."
} else {
    if (sectionLvl == 12) {
        var promotionMsg = "Promoting the student means that he/she has completed the senior high curriculum."
    } else {
        var promotionMsg = "Promoting the student means that he/she is viable for next level's enrollment. This action can not be undone."
    }
}
var studID = '';

/** Change of url when export is clicked */
let signatory, position;
function redirectToGradeReport(link) {
    location.replace(link + `&signatory=${signatory}&position=${position}`)
    console.log(link);
}

window.redirectToGradeReport = redirectToGradeReport;
function changeSignatoryInfo() {
    signatory = $("select[name='signatory'] option:selected").attr("data-name") || "No signatories yet";
    position = $("select[name='signatory'] option:selected").attr("data-position") || '';
}

$(function() {
    preload('#advisory');

    $("#classes").select2({
        theme: "bootstrap-5",
        width: "100%"
    });

    /** Select signatory */
    try {
        changeSignatoryInfo();
    } catch (e) {}
    $(document).on("change", "select[name='signatory']", function () {
        changeSignatoryInfo();
    });

    // Display current/selected section name
    let firstClass = $("#classes option:selected");
    if (firstClass != null) {
        let classTmp = firstClass.attr("data-name") || "No class assigned yet";
        let classType = firstClass.attr("data-class-type");
        studentTable.bootstrapTable("refresh", { url: firstClass.attr("data-url") });
        toggleGradesColumn(classType);
        changeName(classTmp);
    }

    $(document).on("change", "#classes", function() {
        let selected, url, classType, sectionName, displayGrades;
        selected = $("#classes option:selected");
        url = selected.attr("data-url");
        sectionName = selected.attr("data-name");
        classType = selected.attr("data-class-type");

        toggleGradesColumn(classType);
        $("#classes").select2("close");
        changeName(sectionName);
        setTableData(classType, url);
    });

    $(document).on("click", ".export-grade", function() {
        let reportID = $(this).attr("data-report-id");
        let studID = $(this).attr("data-stud-id");
        let form = $("#confirm-sig-form");
        form.attr("action", `../admin/gradeReport.php?id=${studID}&report_id=${reportID}`);
        $("#confirm-sig-modal").modal("show");
    });

    $(document).on("click", ".submit", () => {
        // document.getElementById("label").innerText = "submit";
        // document.getElementById("modal-msg").innerText = submitMsg;
        // document.getElementById("confirm").innerText = "Submit";
        // $(".grading-confirmation").modal("toggle");
        $("#msg").addClass("hidden");
        $("#confirmation-submit-modal").modal("show");

    });

    $(document).on("click", ".save", () => {
        $("#label").text("save");
        $("#modal-msg").text(saveMsg);
        $("#confirm").text("Save");
        // document.getElementById("label").innerText="save";
        // document.getElementById("modal-msg").innerText=saveMsg;
        // document.getElementById("confirm").innerText="Save";
        $(".grading-confirmation").modal("toggle");
    });

    $(document).on("click", "#confirm", function(e) {
        var stat = this.innerText == "Submit" ? "1" : "0";
        this.attr
        console.log(stat);

        if (stat == 1){
            if (($("#reflect").prop("checked") == true) && ($("#editable").prop("checked") == true)){
                gradeStud(stat);
                $('#confirmation-submit-modal').modal('hide');
                
            }else{
                var element = document.getElementById("msg");
                element.classList.remove("hidden");
            }
        } else {
            gradeStud(stat);
            $('.grading-confirmation').modal('hide');
        }
        
        
    });
    $(document).on("click", "#promote", function() {
        var record = {
            'action': 'promote',
            'stud_id': studID,
            'promote': 1
        };

        $.post("action.php", record, function(data) {
            console.log(data);
            studentTable.bootstrapTable("refresh");

        });
        $('.promotion-confirmation').modal('hide');
        showToast('success', 'Student Sucessfully Promoted');

    });
    $(document).on("click", ".stud-promote", function() {
        console.log("stud-promote clicked");
        studID = $(this).attr("data-stud-id");
        var modal = $('.promotion-confirmation');
        modal.find('#modal-msg').text(promotionMsg);
        modal.modal('show');

    });

    $(document).on("click", ".multi-promote", function(e) {
        $('#view-candidates-modal').modal('show');
    });

    $(document).on("click", ".action", function(e) {
        e.preventDefault();
        let id, row, elementToToggle, type;
        $(this).hide();

        id = $(this).attr("data-id");
        row = $(this).closest("tr");
        let inputState = true;

        switch ($(this).attr("data-type")) {
            case "remove":
                type = "undo";
                row.addClass("bg-light");
                // forPromotionStudentTable.bootstrapTable("remove", {
                //     field            : 'stud_id',
                //     values           : id
                // });
                $("#".id).val('disabled');
                break;
            case "undo":
                type = "remove";
                inputState = false;
                $("#".id).val('enable');
                row.removeClass("bg-light");

                break;
        }
        $(`.action[data-id='${id}'][data-type='${type}']`).show();
        row.find("input").prop("disabled", inputState);
        console.log($("#for-promotion-table").bootstrapTable("getRowByUniqueId", id));
    });

    $(document).on("click", ".promote-btn", function(e) {
        //gets table
        var oTable = document.getElementById('for-promotion-table');

        //gets rows of table
        var rowLength = oTable.rows.length;

        //loops through rows    
        for (var i = 1; i < rowLength; i++) {

            //gets cells of current row
            var oCells = oTable.rows.item(i).cells;

            //gets amount of cells of current row

            //get names of students
            var ID = oCells.item(0).innerHTML;

            if (!oCells.item(1).innerHTML.includes("disabled")) {
                var record = {
                    'action': 'promote',
                    'stud_id': ID,
                    'promote': 1
                };

                $.post("action.php", record, function(data) {
                    console.log(data);
                    studentTable.bootstrapTable("refresh");
                    forPromotionStudentTable.bootstrapTable("refresh")


                });
            }

            $('#view-candidates-modal').modal('hide');

        }
        showToast('Success', 'Students successfully promoted');

    });


    $(document).on("click", ".calculate", function() {
        let formData = [
            { name: 'action', value: 'calculateGeneralAverage' },
            { name: 'section_code', value: $(this).attr("data-code") }
        ];
        $.post("action.php", formData, function(data) {
            data = JSON.parse(data);
            data.forEach(e => {
                $(`input[name*='/${e.report_id}/${e.semester}']`).val(e.general_ave);
            });
        });
    });

    $(document).on("shown.bs.modal", "#view-candidates-modal", function() {
        forPromotionStudentTable.bootstrapTable("resetView");
    });
    hideSpinner();
});
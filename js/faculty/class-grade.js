import {commonTableSetup} from "../admin/utilities.js";

let tableSetup = {
    search:             true,
    searchSelector:     "#search-input",
    method:             'GET',
    uniqueId:           'lrn',
    idField:            'lrn',
    height:             440,
    ...commonTableSetup
};


let classGradeTable = $("#table").bootstrapTable(tableSetup);
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
function setTableData (classType, url) {
    classGradeTable.bootstrapTable("refresh", {url});
}



$(function() {
    
    // $("."+currentGrading).removeAttr("readonly");
    
    preload('#grade');
    $("#classes").select2({
        theme: "bootstrap-5",
        width: "100%"
    });

    // $("#grade").click();
    // $(document).on("click", "#grade", function(e) {
    //     console.log("clicked");
    //     $("."+currentGrading).removeAttr("readonly");
    // });

    // document.getElementById("grade").addEventListener("click", grading());
    function grading(){
        console.log("clicked grade");
        $("."+currentGrading).removeAttr("readonly");
    }

    // Display current/selected section name
    let firstClass = $("#classes option:selected");
    if (firstClass != null) {
        let classTmp = firstClass.attr("data-name") || "No class assigned yet";
        let classType = firstClass.attr("data-class-type");
        classGradeTable.bootstrapTable("refresh", {url: firstClass.attr('data-url')});
        // toggleGradesColumn(classType);
        // initializeTable(classType, firstClass.attr("data-url"));
        changeName(classTmp);
    }

    $(document).on("change", "#classes", function() {
        let selected, url, classType, sectionName, displayGrades;
        selected = $("#classes option:selected");
        url = selected.attr("data-url");
        sectionName = selected.attr("data-name");
        classType = selected.attr("data-class-type");

        // toggleGradesColumn(classType);
        console.log(classType);
        $("#classes").select2("close");
        changeName(sectionName);
        setTableData(classType, url);
    })

    $(document).on("click", "#grade", () => {
        $("."+currentGrading).removeAttr("readonly");
    });

    $(document).on("click", ".confirm", () => {
        $(".grading-confirmation").modal("toggle");
    });

    $(document).on("click", ".submit", function(e)  {
        // let studGrades = new FormData();
        var studGrades = $("#grades").serializeArray();
        var action = 'gradeClass';        
        console.log(studGrades);
        $.post("action.php", {studGrades, action}, function(data) {	
            console.log(data);
        });

        $('.grading-confirmation').modal().hide();
        

    });

    hideSpinner();
});
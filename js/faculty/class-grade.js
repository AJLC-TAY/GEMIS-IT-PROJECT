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

var code = '';

$(function() {
    
    // $("."+currentGrading).removeAttr("readonly");
    
    preload('#grade');
    $("#classes").select2({
        theme: "bootstrap-5",
        width: "100%"
    });


    // document.getElementById("grade").addEventListener("click", grading());
    function grading(){
        console.log("clicked grade");
        $("."+currentGrading).removeAttr("readonly");
    }

    // Display current/selected section name
    let firstClass = $("#classes option:selected");
    if (firstClass != null) {
        let classTmp = firstClass.attr("data-name") || "No class assigned yet";
        code = firstClass.val();
        let classType = firstClass.attr("data-class-type");
        classGradeTable.bootstrapTable("refresh", {url: firstClass.attr('data-url')});
        changeName(classTmp) ;
    }

    $(document).on("change", "#classes", function() {
        let selected, url, classType, sectionName, displayGrades;
        selected = $("#classes option:selected");
        url = selected.attr("data-url");
        code = selected.val();
        sectionName = selected.attr("data-name");
        classType = selected.attr("data-class-type");

        // toggleGradesColumn(classType);
        console.log(classType);
        $("#classes").select2("close");
        changeName(sectionName);
        setTableData(classType, url);
    })

    $(document).on("click", ".grade", () => {
        $("."+currentGrading).removeAttr("readonly");
    });

    $(document).on("click", ".confirm", () => {
        $(".grading-confirmation").modal("toggle");
    });

    $(document).on("click", ".submit", function(e)  {
        // let studGrades = new FormData();
        var studGrades = $("#grades").serializeArray();        
        studGrades.forEach(element => {
            
            var recordInfo = element['name'].split("/")
            
            var grades  = {'id' : recordInfo[0],
                            'grading' : recordInfo[1],
                            'grade': element['value'],
                            'code' : code,
                            'action' : 'gradeClass'};

            $.post("action.php", grades, function(data) {	
            });

        });        
        $('.grading-confirmation').modal('hide');

        $(".number").attr('readOnly',true);
        // $(".grade").addClass('hidden');
    });

    hideSpinner();
});
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

/**
 * Hides or shows grades column depending on the type of section specified.
 * @param {String} classType Values may be 'advisory' or 'sub-class'.
 */
 function toggleGradesColumn(classType) {

    let displayGrades = classType === 'advisory' ? 'hideColumn' : 'showColumn';
    if (classType === 'advisory'){
        // classGradeTable.bootstrapTable('showColumn', ['action']);
        classGradeTable.bootstrapTable('hideColumn', ['grd_1', 'grd_2']);
    } else {
        classGradeTable.bootstrapTable('showColumn', ['grd_1', 'grd_2']);
        classGradeTable.bootstrapTable('hideColumn', ['action']);

    }
    
};

var code = '';
var submitMsg = "Submitted grades are final and are not editable. For necessary changes, contact the admin.";
var saveMsg = "Saved grades are editable within the duration of the current quarter.";

$(function() {
    
    
    preload('#grade');
    $("#classes").select2({
        theme: "bootstrap-5",
        width: "100%"
    });

    // Display current/selected section name
    let firstClass = $("#classes option:selected");
    if (firstClass != null) {
        let classTmp = firstClass.attr("data-name") || "No class assigned yet";
        code = firstClass.val();
        let classType = firstClass.attr("data-class-type");
        classGradeTable.bootstrapTable("refresh", {url: firstClass.attr("data-url")});
        toggleGradesColumn(classType)
        // classGradeTable.bootstrapTable("refresh", {url: firstClass.attr('data-url')});
        $("#export_code").val(classTmp + " - " + code );
        changeName(classTmp);
    }

    $(document).on("change", "#classes", function() {
        let selected, url, classType, sectionName, displayGrades;
        selected = $("#classes option:selected");
        url = selected.attr("data-url");
        code = selected.val();
        sectionName = selected.attr("data-name");
        classType = selected.attr("data-class-type");
        console.log(classType);
        toggleGradesColumn(classType);
        $("#export_code").val(sectionName + " - " + code );
        
        $("#classes").select2("close");
        changeName(sectionName); 
        setTableData(classType, url);
    })

    $(document).on("click", ".submit", () => {
        document.getElementById("label").innerText="submit";
        document.getElementById("modal-msg").innerText=submitMsg;
        document.getElementById("confirm").innerText="Submit";
        $(".grading-confirmation").modal("toggle");
       
    });

    $(document).on("click", ".save", () => {
        document.getElementById("label").innerText="save";
        document.getElementById("modal-msg").innerText=saveMsg;
        document.getElementById("confirm").innerText="Save";
        $(".grading-confirmation").modal("toggle");
    });

    $(document).on("click", "#confirm", function(e)  {
        var stat = document.getElementById("label").innerText == "submit"? "1": "0";
        this.attr
        console.log(stat);
        // let studGrades = new FormData();
        var studGrades = $("#grades").serializeArray();        
        studGrades.forEach(element => {
            
            var recordInfo = element['name'].split("/")
            
            var grades  = {'id' : recordInfo[0],
                            'grading' : recordInfo[1],
                            'grade': element['value'],
                            'code' : code,
                            'stat': stat,
                            'action' : 'gradeClass'};

            $.post("action.php", grades, function(data) {	
                console.log(grades);
                classGradeTable.bootstrapTable("refresh")
                
            });

        });        
        $('.grading-confirmation').modal('hide');
        $(".number").attr('readOnly',true);
        
        // $(".grade").addClass('hidden');
    });

    $(document).on("click", ".export", function(e)  {
        var action = "export";
        $.post("action.php", action , function(data) {	
            console.log(data);
            
        });
    });

    hideSpinner();
});
import {commonTableSetup, averageSubjectGradesEvent} from "../admin/utilities.js";

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

    if (classType == 'advisory'){
        classGradeTable.bootstrapTable('showColumn', ['grd_f','action_2']);
        classGradeTable.bootstrapTable('hideColumn', ['grd_1', 'grd_2']);
    } else {
        classGradeTable.bootstrapTable('showColumn', ['grd_1', 'grd_2']);
        classGradeTable.bootstrapTable('hideColumn', ['action_2',]);
    }
    
};


var code = '';
var submitMsg = "Submitted grades are final and are not editable. For necessary changes, contact the admin.";
var saveMsg = "Saved grades are editable within the duration of the current quarter.";
var stat = document.getElementById("label").innerText == "submit?"? "1": "0";

$(function() {
    preload('#subject');
    
    $("#classes").select2({
        theme: "bootstrap-5",
        width: "100%"
    });

    // Display current/selected section name
    let firstClass = $("#classes option:selected");
    if (firstClass != null) {
        let classTmp = firstClass.attr("data-name") || "No class assigned yet";
        code = firstClass.val();
        console.log(code);

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
        console.log(code);
        code = selected.attr("data-name");
        classType = selected.attr("data-class-type");
        console.log(classType);
        toggleGradesColumn(classType);
        $("#export_code").val(sectionName + " - " + code );
        
        $("#classes").select2("close");
        changeName(sectionName); 
        setTableData(classType, url);
    })

    $(document).on("click", ".submit", () => {
        stat = "1";
        document.getElementById("stmt").innerText="Are you sure you want to ";
        document.getElementById("label").innerText="submit?";
        document.getElementById("modal-msg").innerText=submitMsg;
        document.getElementById("confirm").innerText="Submit";
        $(".grading-confirmation").modal("toggle");
       
    });

    $(document).on("click", ".save", () => {
        console.log("from class-grade.js save clicked");
        if (typeof user!== 'undefined'){
            document.getElementById("label").innerText="CONFIRMATION";
            stat = "1";
            document.getElementById("modal-msg").innerText="Are you sure you want to save?";
        } else {
            document.getElementById("stmt").innerText="Are you sure you want to ";
            document.getElementById("label").innerText="save?";
            document.getElementById("modal-msg").innerText=saveMsg;
        }
        
        document.getElementById("confirm").innerText="Save";
        $(".grading-confirmation").modal("toggle");
    });

    $(document).on("click", "#confirm", function(e)  {
        this.attr
        console.log(stat);

        // let studGrades = new FormData();
        if (type == 'grades'){
            var studGrades = $("#grades").serializeArray();        
            studGrades.forEach(element => {
                
                var recordInfo = element['name'].split("/")
                if(recordInfo[2] == 'general_average'){
                    var grades = {'id' : recordInfo[0],
                                'rep_id' : recordInfo[1],
                                'gen_ave' : element['value'],
                                'action' : 'gradeAdvisory',
                                'stat': stat};
                } else {
                    var grades  = {'id' : recordInfo[0],
                                    'grading' : recordInfo[1],
                                    'grade': element['value'],
                                    'code' : code,
                                    'stat': stat,
                                    'action' : 'gradeClass'};
                }
                $.post("../faculty/action.php", grades, function(data) {	
                    // console.log(grades);
                    console.log(grades)
                    classGradeTable.bootstrapTable("refresh")
                    
                });

            });        
            $('.grading-confirmation').modal('hide');
            $(".number").attr('readOnly',true);
        } else {
            var select = document.getElementsByClassName('markings');
            select.forEach((element) => {
                var value = element.options[element.selectedIndex].value;
                var recordInfo = value.split("/")
                var values = {'id' : recordInfo[0],
                                'val_id' : recordInfo[1],
                                'qtr' : recordInfo[2],
                                'rep_id' : recordInfo[4],
                                'mark' : recordInfo[3],
                                'stat': stat,
                            'action' : 'gradeValues'};
                            // console.log(values);
                            $.post("action.php", values, function(data) {	
                                // console.log(grades);
                                console.log(data)                                
                            });
            }); // en
            $('.grading-confirmation').modal('hide');
            location.reload(true);
        }
        
        // $(".grade").addClass('hidden');
    });

    $(document).on("click", ".export", function(e)  {
        var action = "export";
        $.post("action.php", action , function(data) {	
            console.log(data);
        });
    });
    
    averageSubjectGradesEvent();

   
    
    hideSpinner();
});

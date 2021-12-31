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
        console.log(code);

        let classType = firstClass.attr("data-class-type");
        classGradeTable.bootstrapTable("refresh", {url: firstClass.attr("data-url")});
        toggleGradesColumn(classType)
        // classGradeTable.bootstrapTable("refresh", {url: firstClass.attr('data-url')});
        $("#export_code").val(classTmp + " - " + code );
        changeName(classTmp);
    }



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
                $.post("action.php", grades, function(data) {	
                    classGradeTable.bootstrapTable("refresh")
                });

            });        
            $('.grading-confirmation').modal('hide');
            $(".number").attr('readOnly',true);
        } else {
            var select = document.getElementsByClassName('markings');
            select.forEach((element) => {
                var value = element.options[element.selectedIndex].value;
                var recordInfo = element['name'].split("/")
                console.log(recordInfo[1]);
            }); // en

        }
    });

    $(document).on("click", ".export", function(e)  {
        var action = "export";
        $.post("action.php", action , function(data) {	
        });
    });

    hideSpinner();
});
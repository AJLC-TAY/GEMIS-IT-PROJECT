
// const tableSetup = {
//     url:                `getAction.php?data=section`,
//     method:             'GET',
//     uniqueId:           'code',
//     idField:            'code',
//     height:             300,
//     maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
//     clickToSelect:      true,
//     pageSize:           10,
//     pagination:         true,
//     pageList:           "[10, 25, 50, All]",
//     paginationParts:    ["pageInfoShort", "pageSize", "pageList"]
// }

// let sectionTable = $("#table").bootstrapTable(tableSetup)
const tableSetup = {
    url:                `getAction.php?data=fullSection&id=${id}`, 
    method:             'GET',
    uniqueId:           '0',
    idField:            'section_code',
    height:             300,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    clickToSelect:      true,
    pageSize:           10,
    pagination:         true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
    fixedColumns: true

}

let programTable = $("#table").bootstrapTable(tableSetup)
var section_id;
var stud_id;
var current_code;
var section;
var stud_to_swap;
$(function() {
    preload('#student')
    hideSpinner()

    // $('.transfer').click(function(event){
    //     $('#transferconfirmation').modal('show');
    //     stud_id = $(this).attr('id');
    // });

    $(document).on('click','.transfer', function(){
        section_id = $(this).attr('id');
        stud_id = $(this).attr('data');
        $('#transferconfirmation').modal('toggle');
    })

    $("#transfer-student-confirmation").on('shown.bs.modal', function (e) {
        $("#select-section-modal").modal("hide");
    });

    $(document).on('click', '.transfer-btn', function() {
        var action = 'transferStudent'
        $.post("action.php", {section_id,stud_id, action:action}, function() {	
            $('#transfer-student-confirmation').modal("hide")
        })

    })

    $(document).on('click', '.swapStudent', function() {
        var currentTds = $(this).closest("tr").find("td"); // find all td of selected row
            current_code = $(currentTds).eq(0).text(); // eq= cell , text = inner text
            section = $(currentTds).eq(1).text();
           
            var data = {};
            $(".students option").each(function(i,el) {  
                data[$(el).data("value")] = $(el).val();
             });
             console.log(data, $(".students option").val());
             var value = $('.studList').val();
            stud_to_swap = $('.students [value="' + value + '"]').data('value');
        
        var action = 'transferStudentFull'
        $.post('action.php', {current_code, section, stud_to_swap, id, action:action} , function(data){
            console.log(data)
        })
        

    })

 
    $("#stud-save-btn").click(() =>$('#student-form').submit())

    $('#student-form').submit(function(e){
        console.log("submit")
    })

    $('.transfer-full').click(() => {
        console.log("cliecked")
      })
})

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
    uniqueId:           'code',
    idField:            'code',
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
        // $.post('action.php', {section_id, action:action} , function(data){
        //     $('#transfer-student-confirmation').modal('hide')	
        // })
        $.post("action.php", {section_id,stud_id, action:action}, function() {	
            $('#transfer-student-confirmation').modal("hide")
        })

    })

    
    $("#stud-save-btn").click(() =>$('#student-form').submit())

    $('#student-form').submit(function(e){
        console.log("submit")
    })
})


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
    onPostBody:         () => {
        $(".select2").select2({
            theme: "bootstrap-5",
            width: null,
        });
    },
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
        $('#transferConfirmation').modal('toggle');
    })


    $(document).on('click', '.transfer-btn', function() {
        var action = 'transferStudent'
        $.post("action.php", {section_id,stud_id, action:action}, function() {	
            $('#transferConfirmation').modal("hide");
            location.reload();
        })
    })

    $(document).on('click', '.swapStudent', function() {
        var currentTds = $(this).closest("tr").find("td"); // find all td of selected row
        current_code = $(currentTds).eq(0).text(); // eq= cell , text = inner text
        section = $(currentTds).eq(1).text();

        stud_to_swap = $('.select2').val();
        
        $('#transferConfirmationFull').modal('toggle')
        // var action = 'transferStudentFull'
        // $.post('action.php', {current_code, section, stud_to_swap, id, action:action} , function(data){
        //     console.log(data)
        // })

    })


    $(document).on('click', '.transfer-btn-full', function() {
        var action = 'transferStudentFull'
        $.post('action.php', {current_code, section, stud_to_swap, id, action:action} , function(){
            $('#transferConfirmationFull').modal("hide")
            location.reload();
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
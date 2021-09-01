preload("#curr-management", "#curriculum")

const tableSetup = {
    url:                `getAction.php?code=${code}&data=program`,
    method:             'GET',
    uniqueId:           'code',
    idField:            'code',
    height:             300,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    pageSize:           10,
    pagination:         true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"]
}

let programTable = $("#table").bootstrapTable(tableSetup)

var archiveMessage = 'Archiving this program will also archive all subjects and student grades under it.'
// var tempData = []

$(function () {
    $('#edit-btn').click(function(e) {
        e.preventDefault()
        $(this).toggle()
        $('.decide-con').removeClass('d-none')
        $("#curriculum-form .form-input").prop('disabled', false)
    })

    // $('#cancel-btn').click(function(event) {
    //     event.preventDefault()
    //     $('[type=submit]').addClass('d-none')
    //     $(this).addClass('d-none')
    //     $('#edit-btn').removeClass('d-none')
    //     let i = 0;
    //     let inputs = $("#curriculum-form").find('.form-input')
    //     if (tempData.length != 0) {
    //         inputs.each(function() {
    //             $(this).val(tempData[i])
    //             i++
    //         })
    //     } 
    //     inputs.prop('disabled', true)
    //     tempData = []
    // })

    $('#track-archive-btn').click(function(event){
        var code = $(this).attr('id')
        let name = $(this).attr('data-name')
        let archiveModal = $('#track-archive-modal')
        archiveModal.find('.modal-identifier').html(`${name} Program`)
        archiveModal.find('.modal-msg').html(archiveMessage)
        archiveModal.find('.archive-btn').attr('id', code)
        archiveModal.modal('toggle')
    })

    $('.archive-btn').click(function(event){
        var $table = $(tableId)
        var action = 'archiveProgram'
    
        let selected = $table.bootstrapTable('getSelections')
        console.log(selected)
        selected.forEach(element => {
            var code = element.prog_code
            $.post("action.php", {code, action:action}, function(data) {	
                $table.bootstrapTable('refresh')
            })
        })

        $('#track-archive-modal').modal('hide')	

    })

    $("#curriculum-form").submit(function(e) {
        e.preventDefault()
        // console.log($(this).serializeArray())
        $.post("action.php", $(this).serializeArray(), (data) => {
            $(this).find("input, textarea").prop("disabled", true)
            $("#edit-btn, .decide-con").toggleClass("d-none")
            showToast("success", "Successfully updated curriculum")
        })
    })
    hideSpinner()
})
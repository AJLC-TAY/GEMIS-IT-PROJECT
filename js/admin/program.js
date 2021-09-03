preload("#curr-management", "#program")

const tableSetup = {
    url:                `getAction.php?prog_code=${code}&data=subjects`,
    method:             'GET',
    uniqueId:           'sub_code',
    idField:            'sub_code',
    height:             300,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    clickToSelect:      true,
    pageSize:           10,
    pagination:         true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"]
}

let programTable = $("#table").bootstrapTable(tableSetup)

var archiveMessage = 'Archiving this subject will also archive all student grades under it.'

var tempData = []
$(function() {
    $('#edit-btn').click(function(event) {
        event.preventDefault()
        $('.decide-con').removeClass('d-none')
        $(this).addClass('d-none')
        $("#program-view-form").find('.form-input').each(function() {
            tempData.push($(this).val())
            $(this).prop('disabled', false)
        })
    })

    $('#cancel-btn').click(function(event) {
        event.preventDefault()
        $('.decide-con').addClass('d-none')
        $('#edit-btn').removeClass('d-none')
        let i = 0;
        let inputs = $("#program-view-form").find('.form-input')
        if (tempData.length != 0) {
            inputs.each(function() {
                $(this).val(tempData[i])
                i++
            })
        } 
        inputs.each(function () {
            $(this).prop('disabled', true)
        })
        tempData = []
    })

    $('#subject-archive-btn').click(function(event){
        var code = $(this).attr('id')
        let name = $(this).attr('data-name')
        let archiveModal = $('#subject-archive-modal')
        archiveModal.find('.modal-identifier').html(`${name} Subject`)
        archiveModal.find('.modal-msg').html(archiveMessage)
        archiveModal.find('.archive-btn').attr('id', code)
        archiveModal.modal('toggle')
    })

    $('.archive-btn').click(function(event){
        var $table = $(tableId)
        var action = 'archiveSubject'
    
        let selected = $table.bootstrapTable('getSelections')
        console.log(selected)
        selected.forEach(element => {
            var code = element.sub_code
            $.post("action.php", {code, action:action}, function(data) {	
                $table.bootstrapTable('refresh')
            })
        })

        $('#subject-archive-modal').modal('hide')	

    })

     $('#program-view-form').submit(function(e) {
        e.preventDefault()
        showSpinner()
        var formData = $(this).serializeArray()
        $.post("action.php", formData, () => {
            $(this).find("input, textarea").prop("disabled", true)
            $('#edit-btn').removeClass('d-none')
            $('.decide-con').addClass('d-none')
            showToast('success', 'Program successfully updated')
            hideSpinner()
        }).fail(function () {

        })
    })
    
    hideSpinner()
})

import {Table} from "./Class.js"

let tableId, url, method, id, height
tableId = '#table'
url = `getAction.php?prog_code=${code}&data=subjects`
method = 'GET'
id = 'sub_code'
height = 300

preload("#curr-management", "#program")
let subject_table = new Table(tableId, url, method, id, id, height)

var archiveMessage = 'Archiving this subject will also archive all student grades under it.'

var tempData = []
$(function() {
    $('#edit-btn').click(function(event) {
        event.preventDefault()
        $('[type=submit]').removeClass('d-none')
        $('#cancel-btn').removeClass('d-none')
        $(this).addClass('d-none')
        $("#program-view-form").find('.form-input').each(function() {
            tempData.push($(this).val())
            $(this).prop('disabled', false)
        })
    })

    $('#cancel-btn').click(function(event) {
        event.preventDefault()
        $('[type=submit]').addClass('d-none')
        $(this).addClass('d-none')
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

     $('#program-form').submit(function(event) {
        event.preventDefault()
        showSpinner()
        var form = $(this)
        var formData = $(this).serialize()
        $.post("action.php", formData, function(data) {
            form.trigger('reset')
            addModal.modal('hide')
            reload()
            showToast('success', 'Program successfully updated')
        }).fail(function () {

        })
    })
    
    hideSpinner()
})

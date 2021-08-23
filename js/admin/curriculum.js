import {Table} from "./Class.js"

let tableId, url, method, id, height

tableId = '#table'
url = `getAction.php?code=${code}&data=program`
method = 'GET'
id = 'code'
height = 300

preload("#curr-management", "#curriculum")
let program_table = new Table(tableId, url, method, id, id, height)

var archiveMessage = 'Archiving this program will also archive all subjects and student grades under it.'

$(function () {
    $('#edit-btn').click(function(event) {
        event.preventDefault()
        $('[type=submit]').removeClass('d-none')
        $('#cancel-btn').removeClass('d-none')
        $(this).addClass('d-none')
        $(this).closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', false)
        })
    })

    $('#cancel-btn').click(function(event) {
        event.preventDefault()
        $('[type=submit]').addClass('d-none')
        $(this).addClass('d-none')
        $('#edit-btn').removeClass('d-none')
        $(this).closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', true)
        })
    })

    $('#add-btn').click(() => $('#add-modal').modal('show'))

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
    hideSpinner()
})
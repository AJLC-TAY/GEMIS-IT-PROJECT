import {Table} from "./Class.js"

let tableId, url, method, id, height

tableId = '#table'
url = `getAction.php?code=${code}&data=program`
method = 'GET'
id = 'code'
height = 300

preload("#curr-management", "#curriculum")
let program_table = new Table(tableId, url, method, id, id, height)

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

    hideSpinner()
})
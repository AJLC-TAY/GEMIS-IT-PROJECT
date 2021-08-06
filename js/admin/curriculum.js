let tableId, url, method, id

tableId = '#table'
url = `getAction.php?code=${code}&data=program`
method = 'GET'
id = 'code'
height = 300

preload("#curr-management", "#curriculum")
let program_table = new Table(tableId, url, method, id, id, height)

$(function () {
    $('#edit-btn').click(function() {
        let editBtn = $(this)
        editBtn.prop("disabled", true)
        $("#save-btn").prop("disabled", false)
        editBtn.closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', false)
        })
    })
    hideSpinner()
})
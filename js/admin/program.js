let tableId, url, method, id

tableId = '#table'
url = `getAction.php?prog_code=${code}&data=subjects`
method = 'GET'
id = 'sub_code'
height = 300

preload("#curr-management", "#program")
let subject_table = new Table(tableId, url, method, id, id, height)

$(function() {
    $('#edit-btn').click(function() {
        $(this).prop("disabled", true)
        $("#save-btn").prop("disabled", false)
        $(this).closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', false)
        })
    })
    
    hideSpinner()
})

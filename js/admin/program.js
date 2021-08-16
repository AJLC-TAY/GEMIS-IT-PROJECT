let tableId, url, method, id, height

tableId = '#table'
url = `getAction.php?prog_code=${code}&data=subjects`
method = 'GET'
id = 'sub_code'
height = 300

preload("#curr-management", "#program")
let subject_table = new Table(tableId, url, method, id, id, height)

$(function() {
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

    $('.track-archive-btn').click(function(event){
        console.log("track archived clikcedss")
        alert($('#track-table:checked').val() );
    })
    
    hideSpinner()
})

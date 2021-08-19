import {Table} from "./Class.js"

let tableId, url, method, id, search, searchSelector, height

tableId = '#table'
url = 'getAction.php?data=subjects'
method = 'GET'
id = 'sub_code'
search = true
searchSelector = '#search-input'
height = 425
let onPostBodyOfTable = () => {

}

let subject_table = new Table(tableId, url, method, id, id, height, search, searchSelector)

preload('#curr-management', '#subject')

$(function() {
    $('#edit-btn').click(function() {
        $(this).prop("disabled", true)
        $("#save-btn").prop("disabled", false)
        $(this).closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', false)
        })
    })

    $('.view-archive').click(function(){
        $('#view-arch-modal').modal('toggle')
    })

    $('#view-arch-modal').on('shown.bs.modal', function(){
        $.post('action.php', {action:'getArchiveSubjectJSON'} ,(data) => {
            var archiveData = JSON.parse(data)
            $('.arch-list').html(prepareArchiveHTML(archiveData))
        })
    })

    // $('#save-btn').click(function() {
    //     $(this).prop("disabled", true)
    //     $("#edit-btn").prop("disabled", false)
    //     $(this).closest('form').find('input').each(function() {
    //         $(this).prop('disabled', true)
    //     })
    // })

    hideSpinner()
})

let prepareArchiveHTML = archivedData => {
    let html = ''
    archivedData.forEach(element => {
        var code = element.sub_code
        var name = element.sub_name
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${name}
                <button data-name='$name' class='unarchive-option btn' id='${code}'>Unarchive</button></li>`
    })
    return html
}
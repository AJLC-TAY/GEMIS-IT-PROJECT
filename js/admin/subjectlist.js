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

var unarchiveMessage = 'Unarchiving this subject will also unarchive all student grades under it.'
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

    $("#unarchive-modal").on('show.bs.modal', function (e) {
        $("#view-arch-modal").modal("hide");
    });
    
    $(document).on('click', '.unarchive-btn', function() {
        $('#view-arch-modal').modal('hide')	
        var code = $(this).attr('id')
        var action = `unarchiveSubject`
        console.log(action)
        console.log(code)
        $.post("action.php", {code, action}, function(data) {	
            $('#unarchive-modal').modal('hide')		
            reload()
            showWarningToast()
        })
    })


    $(document).on('click', '.unarchive-option', function() {
        var code = $(this).attr('id')
        let name = $(this).attr('data-name')
        let unarchiveModal = $('#unarchive-modal')
        unarchiveModal.find('.modal-identifier').html(`${name} Subject`)
        unarchiveModal.find('.modal-msg').html(unarchiveMessage)
        unarchiveModal.find('.unarchive-btn').attr('id', code)
        unarchiveModal.modal('toggle')
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


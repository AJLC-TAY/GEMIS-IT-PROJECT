import {setup, reload, addModal, eventDelegations} from "./card-page.js"

let prepareHTML = data => {
    let template = $('#card-template').html()
    let html = ""
    data.forEach(e => {
        html += template.replaceAll('%PROGCODE%', e.prog_code)
                        .replaceAll('%PROGDESC%', e.prog_desc)
                        .replaceAll('%CURCODE%', e.curr_code)
    })
    return html
}

let prepareArchiveHTML = archivedData => {
    console.log("from prepareArchiveHTML")
    let html = ''
    archivedData.forEach(element => {
        var code = element.prog_code
        var name = element.prog_desc
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${name}
                <button data-name='${name}' class='unarchive-option btn link' id='${code}'>Unarchive</button></li>`
    })
    return html
}
setup('program', programs, prepareHTML, prepareArchiveHTML)
reload()

// custom script
$(function() {
    $('#program-form').submit(function(e) {
        e.preventDefault()
        showSpinner()
        var form = $(this)
        var formData = form.serialize()
        $.post("action.php", formData, function(data) {
            form.trigger('reset')
            addModal.modal('hide')
            reload(JSON.parse(data))
            hideSpinner()
            $(".no-result-msg").hide()
            showToast("success", "Program successfully added")
        }).fail(function () {

        })
    })
    eventDelegations()
    hideSpinner()
})
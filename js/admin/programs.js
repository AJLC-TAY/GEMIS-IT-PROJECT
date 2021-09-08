import {setup, reload, addModal, eventDelegations} from "./card-page.js"

let prepareHTML = data => {
    let html = ""
    data.forEach(element => {
        let prog_code = element.prog_code
        let cur_code = element.curr_code
        let prog_desc = element.prog_desc
        html += `<li data-id='${prog_code}' class='tile card shadow-sm p-0 position-relative'>
                    <a role='button' class='card-link btn btn-link start-0 top-0 end-0 bottom-0 h-100' style='z-index: 2;' href='program.php?prog_code=${prog_code}'></a>
                    <div class='dropstart position-absolute top-0 end-0' style='z-index: 3;'>
                        <button type='button' class='btn kebab rounded-circle m-1' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button>
                        <ul class='dropdown-menu' style='z-index: 99;'>
                            <li><a class='dropdown-item' href='program.php?state=edit&prog_code=${prog_code}'>Edit</a></li>
                            <li><button data-name='${prog_desc}' class='archive-option dropdown-item' id='${prog_code}'>Archive</button></li>
                            <li><button data-name='${prog_desc}' class='delete-option dropdown-item' id='${prog_code}'>Delete</button></li>
                        </ul>
                    </div>
                    <div class='card-body position-absolute d-flex-column justify-content-between start-0' style='top: 40px;'>
                        <div class='tile-content'>
                            <h4 class='card-title'>${prog_desc}</h4>
                            <p class='card-text'>${cur_code} | ${prog_code}</p>
                        </div>
                    </div>
                </li>`
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
            showToast("success", "Program successfully added")
        }).fail(function () {

        })
    })
    eventDelegations()
    hideSpinner()
})
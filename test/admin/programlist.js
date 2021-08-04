let prepareHTML = data => {
    let html = ""
    data.forEach(element => {
        let prog_code = element.prog_code
        let cur_code = element.curr_code
        let prog_desc = element.prog_desc
        html += `<div data-id='${prog_code}' class='card shadow-sm p-0'>
                    <div class='card-body'>
                        <div class='dropdown'>
                            <button type='button' class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                            <ul class='dropdown-menu'>
                                <li><a class='dropdown-item' href='program.php?state=edit&prog_code=${prog_code}'>Edit</a></li>
                                <li><button data-name='${prog_desc}' class='archive-btn dropdown-item'>Archive</button></li>
                                <li><button data-name='${prog_desc}' class='delete-option dropdown-item' id='${prog_code}'>Delete</button></li>

                            </ul>
                        </div>
                        <h4>${prog_desc}</h4>
                        <p>${cur_code} | ${prog_code}</p>
                    </div>
                    <div class='modal-footer p-0'>
                        <a role='button' class='btn' href='program.php?prog_code=${prog_code}'>View</a>
                    </div>
                </div>`
    })
    return html
}

let getDataResult = (dataList) => {
    var keywords = $('#search-input').val().trim().toLowerCase()

    let filterFunc = (program) => { 
        return program.prog_code.toLowerCase().includes(keywords) || 
               program.prog_desc.toLowerCase().includes(keywords) || 
               program.curr_code.toLowerCase().includes(keywords)
    }
    return dataList.filter(filterFunc)
}

setup('program', prepareHTML, getDataResult)
reload()

// custom script
$(function() {
    $('#program-form').submit(function(event) {
        event.preventDefault()
        spinner.show()
        var form = $(this)
        var formData = form.serialize()
        $.post("action.php", formData, function(data) {
            form.trigger('reset')
            addModal.modal('hide')
            reload()
            addToast.toast('show')
        }).fail(function () {

        })
    })
})
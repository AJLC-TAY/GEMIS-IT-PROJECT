let prepareHTML = data => {
    let html = ''
    data.forEach(element => {
        var code = element.cur_code
        var name = element.cur_name
        var desc = element.cur_desc
        html += `<div data-id='${code}' class='card shadow-sm p-0'>
                <div class='card-body'>
                    <div class='dropdown'>
                        <button type='button' class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                        <ul class='dropdown-menu'>
                            <li><a class='dropdown-item' href='curriculum.php?code=${code}&state=edit'>Edit</a></li>
                            <li><button data-name='${name}' class='archive-option dropdown-item' id='${code}'>Archive</button></li>
                            <li><button data-name='${name}' class='delete-option dropdown-item' id='${code}'>Delete</button></li>
                        </ul>
                    </div>
                    <h4>${name}</h4>
                    <p>${desc}</p>
                </div>
                <div class='modal-footer p-0'>
                    <a role='button' class='btn' href='curriculum.php?code=${code}'>View</a>
                </div>
            </div>`
    })
    return html
}

let getDataResult = (dataList) => {
    var keywords = $('#search-input').val().trim().toLowerCase()

    let filterFunc = (curriculum) => { // returns the curriculum info that contain the keyword
        return (curriculum.cur_code.toLowerCase().includes(keywords) || 
                curriculum.cur_desc.toLowerCase().includes(keywords) || 
                curriculum.cur_name.toLowerCase().includes(keywords))
    }
    return dataList.filter(filterFunc)
}

let prepareArchiveHTML = archivedData => {
    console.log("from prepareArchiveHTML")
    let html = ''
    archivedData.forEach(element => {
        var code = element.cur_code
        var name = element.cur_name
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${name}
                <button data-name='$name' class='unarchive-option btn btn-link' id='${code}'>Unarchive</button></li>`
    })
    return html
}
setup('curriculum', prepareHTML, prepareArchiveHTML, getDataResult)
reload()

// custom script
$(function() {
    $('#curriculum-form').submit(function(event) {
        event.preventDefault()
        spinner.show()
        var form = $(this)
        console.log(form)
        var formData = form.serialize()
        console.log(formData)
        $.post("action.php", formData, function(data) {
            form.trigger('reset')
            addModal.modal('hide')
            reload()
            addToast.toast('show')
        }).fail(function () {

        })
    })

    // eventDelegations()
})

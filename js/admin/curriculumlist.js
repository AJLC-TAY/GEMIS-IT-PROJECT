let prepareHTML = data => {
    let html = ''
    data.forEach(element => {
        var code = element.cur_code
        var name = element.cur_name
        var desc = element.cur_desc
        html += `<div data-id='${code}' class='tile card shadow-sm p-0 position-relative'>
            <a role='button' class='card-link btn btn-link start-0 top-0 end-0 bottom-0 h-100' style='z-index: 2;' href='curriculum.php?code=${code}'></a>
            <div class='dropstart position-absolute top-0 end-0' style='z-index: 3;'>
                <button type='button' class='btn kebab rounded-circle m-1' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button>
                <ul class='dropdown-menu' style='z-index: 99;'>
                    <li><a class='dropdown-item' href='curriculum.php?code=${code}&state=edit'>Edit</a></li>
                    <li><button data-name='${name}' class='archive-option dropdown-item' id='${code}'>Archive</button></li>
                    <li><button data-name='${name}' class='delete-option dropdown-item' id='${code}'>Delete</button></li>
                </ul>
            </div>
            <div class='card-body position-absolute d-flex-column justify-content-between start-0' style='top: 40px;'>
                <div class='tile-content'>
                    <h4 class='card-title'>${name}</h4>
                    <p class='card-text'>${desc}</p>
                </div>
            </div>
        </div>`
    })
    return html
}

let getDataResult = dataList => {
    var keywords = $('#search-input').val().trim().toLowerCase()
    
    let filterFunc = curriculum => { // returns the curriculum info that contain the keyword
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
                <button data-name='${name}' class='unarchive-option btn link' id='${code}'>Unarchive</button></li>`
    })
    return html
}
setup('curriculum', prepareHTML, prepareArchiveHTML, getDataResult)
reload()

// custom script
$(function() {
    $('#curriculum-form').submit(function(event) {
        event.preventDefault()
        showSpinner()
        var form = $(this)
        var formData = $(this).serialize()
        $.post("action.php", formData, function(data) {
            form.trigger('reset')
            addModal.modal('hide')
            reload()
            showToast('success', 'Curriculum successfully added')
        }).fail(function () {

        })
    })

})

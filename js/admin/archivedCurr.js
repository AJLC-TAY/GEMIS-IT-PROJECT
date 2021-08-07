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
                        <li><button data-name='${name}' class='unarchive-option dropdown-item' id='${code}'>Unarchive</button></li>
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


setup('curriculum', prepareHTML, getDataResult)
reload()
let page,  menuItem, subMenuItem, table, url, method, uniqueId, idField, height, search, searchSelector, exportDataType

menuItem = '#curr-management'
subMenuItem = '#curriculum'
table = '#table'
url = `getAction.php?code=${code}&data=program`
method = 'GET'
id = 'code'    
height = 300 
page = new TablePage(menuItem, subMenuItem, table, url, method, id, id, height)
page.setup()

// custom script 
$(function () {
    $('#edit-btn').click(function() {
        let editBtn = $(this)
        editBtn.prop("disabled", true)
        $("#save-btn").prop("disabled", false)
        editBtn.closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', false)
        })
    })
    
    page.hideSpinner()
})
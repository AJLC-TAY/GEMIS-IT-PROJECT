
$(function() {
    /** Display active menu item */
    $('#curr-management a:first').click()
    $('#curriculum').addClass('active-sub')

    var $table = $('#table').bootstrapTable({
        "url": `getAction.php?code=${code}&data=program`,
        "method": 'GET',
        // "search": true,
        // "searchSelector": '#search-curr',
        "uniqueId": "code",
        "idField": "code",
        "height": 300,
        // "exportDataType": "All",
        "pagination": true,
        "paginationParts": ["pageInfoShort", "pageSize", "pageList"],
        "pageSize": 10,
        "pageList": "[10, 25, 50, All]",
        // "onPostBody": onPostBodyOfTable
    })

    $('#edit-btn').click(function() {
        let editBtn = $(this)
        let saveBtn = $("#save-btn")
        editBtn.prop("disabled", true)
        saveBtn.prop("disabled", false)
        editBtn.closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', false)
        })
    })

})
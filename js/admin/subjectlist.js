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

    // $('#save-btn').click(function() {
    //     $(this).prop("disabled", true)
    //     $("#edit-btn").prop("disabled", false)
    //     $(this).closest('form').find('input').each(function() {
    //         $(this).prop('disabled', true)
    //     })
    // })

    hideSpinner()
})
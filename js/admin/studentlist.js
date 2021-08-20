import {Table} from "./Class.js"
    
let tableId, url, method, id, search, searchSelector, height

tableId = '#table'
url = 'getAction.php?data=student'
method = 'GET'
id = 'teacher_id'
search = true
searchSelector = '#search-input'
height = 425

let student_table = new Table(tableId, url, method, id, id, height, search, searchSelector)

$(function() {
    preload('#student')
    // $('#edit-btn').click(function() {
    //     $(this).prop("disabled", true)
    //     $("#save-btn").prop("disabled", false)
    //     $(this).closest('form').find('.form-input').each(function() {
    //         $(this).prop('disabled', false)
    //     })
    // })

    hideSpinner()
})
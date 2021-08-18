import {Table} from "./Class.js"
    
let tableId, url, method, id, search, searchSelector, height

tableId = '#table'
url = 'getAction.php?data=faculty'
method = 'GET'
id = 'teacher_id'
search = true
searchSelector = '#search-input'
height = 425

let onPostBodyOfTable = () => {
    // $('.profile-btn').click(function() {
    //     let id = $(this).attr('data-id')
    //     let state = $(this).attr('data-state')
    //     let formData = new FormData()
    //     formData.append('id', id)
    //     formData.append('state', state)
    //     $.post("profile.php", formData, function() {
            
    //     })


    // })
}

let faculty_table = new Table(tableId, url, method, id, id, height, search, searchSelector)

$(function() {
    preload('#faculty')
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
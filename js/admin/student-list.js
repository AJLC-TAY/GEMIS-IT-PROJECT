import {Table} from "./Class.js"
    
let tableId, url, method, id, search, searchSelector, height

tableId = '#table'
url = 'getAction.php?data=student'
method = 'GET'
id = 'student_id'
search = true
searchSelector = '#search-input'
height = 425

let student_table = new Table(tableId, url, method, id, id, height, search, searchSelector)

$(function() {
    preload('#student')
    hideSpinner()
})
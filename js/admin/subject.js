import {Table} from "./Class.js"

/** SUBJECT TABLE LIST */
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
/** SUBJECT TABLE LIST END */

/** CARD PAGE METHOD */
var unarchiveMessage = 'Unarchiving this subject will also unarchive all student grades under it.'
let prepareArchiveHTML = archivedData => {
    let html = ''
    archivedData.forEach(element => {
        var code = element.sub_code
        var name = element.sub_name
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${name}
                <button data-name='$name' class='unarchive-option btn' id='${code}'>Unarchive</button></li>`
    })
    return html
}
/** CARD PAGE METHOD END */
preload("#curr-management", "#subject")

let addAgain = false
$(function () {
    $('#sub-type').change(function() {
        let options = $('#app-spec-options')
        let type = $(this).val()
        if (type == 'applied' || type == 'specialized') {
            options.removeClass('d-none')
            options.find('input').each(function() {
                $(this).prop('disabled', false)
                $(this).attr('type', (type == 'applied') ? 'checkbox' : 'radio')
            })
        } else if (type == 'core') {
            options.addClass('d-none')
            options.find('input').each(function() {
                $(this).prop('disabled', true)
            })
        }
    })

    $(".submit-and-again-btn").click(() => {
        addAgain = true
        $('#add-subject-form').submit()
    })

    $(".submit-btn").click(() => {$('#add-subject-form').submit()})

    $('#add-subject-form').submit(function(event) {
        event.preventDefault()
        showSpinner()
        var form  = $(this)
        var formData = form.serializeArray()

        // initialize requisites array
        var prereq = []
        var coreq = []

        // remove radio buttons from the formdata and store them from the respective requisite arrays
        formData = formData.filter(function(item) {
            let value = item.value
            if (item.name.includes('radio-')) {
                if (value.includes('PRE-')) { 
                    prereq.push(value)
                } else if (value.includes('CO-')) {
                    coreq.push(value)
                } 
                return false
            }
            return true
        })

        /**
         * Stores all subject code under one requisite to the form data.
         * @param {String}  requisite   Requisite identifier, 'pre' or 'co'. 
         * @param {Array}   codeList    Raw subject code list.
         */
        var saveRequisiteCodes = (requisite, codeList) => {
            if (codeList.length == 0) return                       // return if list of codes is empty
            
            codeList.forEach(code => {  
                code = code.substring(code.indexOf("-") + 1)
                formData.push( {'name': requisite, 'value': code}) // store subject code value; from PRE-ABM to ABM
            })
        }

        saveRequisiteCodes('PRE[]', prereq)
        saveRequisiteCodes('CO[]', coreq)

        $.post("action.php", formData, function(data) {
            hideSpinner(500)
            if (addAgain) {
                $('#add-subject-form').trigger('reset')
                $('#app-spec-options').addClass('d-none')
                $('#sub-code').attr("autofocus")
                addAgain = false
                return showToast('success', 'Subject successfully added!')
            }
            console.log(data)
            data = JSON.parse(data)
            window.location.href = `subject.php?${data.redirect}`
        })

    })

    $(document).on('click', '#edit-btn', function(event) {
        let editBtn = $('#edit-btn')
        editBtn.addClass('d-none')
        let cancelBtn = $('.cancel-btn')
        let link = editBtn.attr('data-link')
        cancelBtn.attr('href', link)
        cancelBtn.removeClass('disabled')
        cancelBtn.removeClass('d-none')
    })
 
    // Clears all the radio buttons contained in a grade level table
    $(document).on('click', '.clear-table-btn', function(event) {
        event.preventDefault()
        let grade = $(this).attr('data-desc')
        $(`#grade${grade}-table input[name*='radio']`).prop('checked', false)
    })

    // Clears the radio buttons on the same row as the clicked clear button
    $(document).on('click', '.spec-clear-btn', function(event) {
        event.preventDefault()

        $(this).closest('tr').find('td').slice(3, 5).each(function() {
            $(this).find('input').prop('checked', false)
        })
    })
    
    // Disables radio buttons in grade 12 table when 'Grade level' is 11; otherwise, enables them
    $('#grade-level').change(function() {
        let isDisabled =  ($(this).val() == 11) ? true : false
        $(`#grade12-table input[name*='radio']`).prop('disabled', isDisabled)
    })

    $(document).on('click', '.archive-btn', function() {
        var code = $(this).attr('id')
        var action = 'archiveSubject'
        $.post("action.php", {code, action}, function(data) {	
            $('#archive-modal').modal('hide')
            document.location.href = "subjectlist.php"
        })
    })

    let archiveMessage = 'Archiving this subject will also archive all student grades under it.'
    $(document).on('click', '.archive-option', function() {
        var code = $(this).attr('id')
        let name = $(this).attr('data-name')
        let archiveModal = $('#archive-modal')
        archiveModal.find('.modal-identifier').html(`${name} Subject`)
        archiveModal.find('.modal-msg').html(archiveMessage)
        archiveModal.find('.archive-btn').attr('id', code)
        archiveModal.modal('toggle')
    })


    $('#edit-btn').click(function() {
        $(this).prop("disabled", true)
        $("#save-btn").prop("disabled", false)
        $(this).closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', false)
        })
    })

    $('.view-archive').click(function(){
        $('#view-arch-modal').modal('toggle')
    })

    $('#view-arch-modal').on('shown.bs.modal', function(){
        $.post('action.php', {action:'getArchiveSubjectJSON'} ,(data) => {
            var archiveData = JSON.parse(data)
            $('.arch-list').html(prepareArchiveHTML(archiveData))
        })
    })

    $("#unarchive-modal").on('show.bs.modal', function (e) {
        $("#view-arch-modal").modal("hide");
    });
    
    $(document).on('click', '.unarchive-btn', function() {
        $('#view-arch-modal').modal('hide')	
        var code = $(this).attr('id')
        var action = `unarchiveSubject`
        console.log(action)
        console.log(code)
        $.post("action.php", {code, action}, function(data) {	
            $('#unarchive-modal').modal('hide')		
            reload()
            showWarningToast()
        })
    })


    $(document).on('click', '.unarchive-option', function() {
        var code = $(this).attr('id')
        let name = $(this).attr('data-name')
        let unarchiveModal = $('#unarchive-modal')
        unarchiveModal.find('.modal-identifier').html(`${name} Subject`)
        unarchiveModal.find('.modal-msg').html(unarchiveMessage)
        unarchiveModal.find('.unarchive-btn').attr('id', code)
        unarchiveModal.modal('toggle')
    })


    hideSpinner();
})

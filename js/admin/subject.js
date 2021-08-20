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
    // $(".archive-option").click(()=> console.log("archive clicked"))

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
                code = code.substring(code.indcmnOf("-") + 1)
                formData.push( {'name': requisite, 'value': code}) // store subject code value; from PRE-ABM to ABM
            })
        }

        saveRequisiteCodes('PRE[]', prereq)
        saveRequisiteCodes('CO[]', coreq)

        $.post("action.php", formData, function(data) {
            hideSpinner(500)
            if (addAgain) {
                $('#add-subject-form').trigger('reset')
                addAgain = false
                return showToast('success', 'Subject successfully added!')
            }
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
            showWarningToast()
        })
    })

    archiveMessage = `Archiving this subject will also archive all student grades under it.`
    $(document).on('click', '.archive-option', function() {
        var code = $(this).attr('id')
        let name = $(this).attr('data-name')
        let archiveModal = $('#archive-modal')
        archiveModal.find('.modal-identifier').html(`${name} Subject`)
        archiveModal.find('.modal-msg').html(archiveMessage)
        archiveModal.find('.archive-btn').attr('id', code)
        archiveModal.modal('toggle')
    })

    hideSpinner();
    // if (message) showToast('success', message)
    // if (message) {
    //     let toast = $('.success-toast')
    //     toast.find('.toast-body').tcmnt(message)
    //     toast.toast('show')
    // } 
})

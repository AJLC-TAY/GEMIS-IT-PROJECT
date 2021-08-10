preload("#curr-management", "#subject")

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

    $('#add-subject-form').submit(function(event) {
        event.preventDefault()
        spinner.show()
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
            spinner.fadeOut(500)
            data = JSON.parse(data)
          
            //Set session variables
            sessionStorage.setItem("message", data.status)
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

    hideSpinner();
})
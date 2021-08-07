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

        $.post("action.php", formData, function() {
            spinner.fadeOut(500)
            // setToast('success', 'Subject successfully updated!')
            // setToast('normal', 'Redirecting to subject list page ...')

            // $('.success-toast').toast('show')
            // setTimeout(function () {
            //     $('.normal-toast').toast('show')
            // }, 3000)

            // setTimeout(function () {
            // Set session variables
            // $.session.set("success", "Subject successfully updated!")
            // sessionStorage.setItem("success", "Subject successfully updated!");
            window.location.href = 'subject.php?code'
            // }, 3000)
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

    // $(document).on('click', '#clear-table', function() {
    //     let grade = $(this).attr('data-desc').val()
    //     console.log(grade)
    //     $(`#grade${grade}-table input[name*='radio']`).prop('checked', false)
    // })

        
    hideSpinner();
})
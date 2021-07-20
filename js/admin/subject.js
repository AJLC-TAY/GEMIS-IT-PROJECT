var spinner = $('.spinner-con')
$(document).ready(function () {
    spinner.show()
    $('.no-subject-msg').hide()
    let timeout = null;

    $('#curr-management a:first').click()
    if (isAddPageUnderProgram) $('#program').addClass('active-sub')
    else $('#subject').addClass('active-sub')

    $('#req-btn').click(function () {
        $('#req-table-con').removeClass('d-none')  
    })

    $('#sub-type').change(function() {
        switch($(this).val()) {
            case 'applied':
                $('#app-spec-options').find('input').each(function() {
                    $(this).prop('disabled', false)
                    $(this).attr('type', 'checkbox')
                })
                break;
            case 'specialized':
                $('#app-spec-options').find('input').each(function() {
                    $(this).prop('disabled', false)
                    $(this).attr('type', 'radio')
                })
                break;
            default:
                $('#app-spec-options').find('input').each(function() {
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
                if (value.includes('pre-')) { 
                    prereq.push(value)
                } else if (value.includes('co-')) {
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
                formData.push( {'name': requisite, 'value': code}) // store subject code value; from pre-ABM to ABM
            })
        }

        saveRequisiteCodes('pre[]', prereq)
        saveRequisiteCodes('co[]', coreq)

        console.log(formData)

        $.post("action.php", formData, function() {
            window.location.href = 'subjectList.php' 
        })
    })

    $(document).on('click', '#edit-btn', function() {
        let editBtn = $('#edit-btn')
        editBtn.addClass('d-none')
        let cancelBtn = $('.cancel-btn')
        let link = editBtn.attr('data-link')
        cancelBtn.attr('href', link)
        cancelBtn.removeClass('disabled')
        cancelBtn.removeClass('d-none')
    })

        
    spinner.fadeOut(500)
})
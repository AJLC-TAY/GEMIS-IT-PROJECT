var spinner = $('.spinner-border')
$(document).ready(function () {
    $('.no-subject-msg').hide()
    let timeout = null;
    let spinner = $('.spinner-grow')

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

        var prereq = []
        var coreq = []


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

        var saveRequisiteCodes = (requisite, codeList) => {
            if (codeList.length == 0) return                    // return if list of codes is empty
            
            codeList.forEach(code => {  
                code = code.substring(code.indexOf("-") + 1)
                formData.push( {'name': requisite, 'value': code}) // store subject code value; from pre-ABM to ABM
            })
        }
        saveRequisiteCodes('pre[]', prereq)
        saveRequisiteCodes('co[]', coreq)

        console.log(formData)

        $.post("action.php", formData, function(data) {
            
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
})
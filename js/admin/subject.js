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
        var formData = form.serialize()
        console.log(formData)

        // $.post("action.php", formData, function(data) {

        // })

    })
})
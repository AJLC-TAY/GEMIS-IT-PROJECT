$(document).ready(function(){
    spinner.fadeOut("slow")
        /** Display active menu item */
        //$('#curr-management a:first').click()
        //$('#curriculum').addClass('active-sub')
    
        $('#program-form').submit(function(event) {
            event.preventDefault()
            spinner.show()
            var form = $(this)
            var formData = form.serialize()
            $.post("action.php", formData, function(data) {
                form.trigger('reset')
                $('#add-curr-modal').modal('hide')
                reloadCurriculum()
            }).fail(function () {

            })
        })

        
});
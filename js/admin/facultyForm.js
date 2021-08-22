import { addSubjectFn, removeAllBtnFn as removeAllBtnFn, removeSubjectBtnFn } from "./utilities.js"


$(function () {
    preload('#faculty')

    $(document).on('click', '.add-subject', addSubjectFn)
    $(document).on('click', '.remove-all-btn', removeAllBtnFn)
    $(document).on('click', '.remove-btn', removeSubjectBtnFn)

    /** Handling image upload */
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#resultImg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#upload").change(function(){
        readURL(this);
    })

    $('#faculty-form').submit(function(event) {
        event.preventDefault()

        $.post("action.php", $(this).serialize()).fail(function(error) {
            console.log(error.responseText)
        }) 
    })

    $(".edit-text").click(()=> $("#upload").click())
    hideSpinner()
})
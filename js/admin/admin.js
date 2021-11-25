import {commonTableSetup} from "./utilities.js";

var forms = document.querySelectorAll('.needs-validation');

// Array.prototype.slice.call(forms).forEach(function(form) {
//     form.addEventListener('submit', function(event) {
//         if (!form.checkValidity()) {
//             document.getElementsByClassName("invalid-ln").innerText = "Hello JavaScript!";
//             event.preventDefault()
//             event.stopPropagation();
//         }

//         form.classList.add('was-validated');
//     }, false);
// });

const tableSetup = {
    url:                'getAction.php?data=administrators',
    method:             'GET',
    uniqueId:           'admin_id',
    idField:            'admin_id',
    height:             425,
    search:             true,
    searchSelector:     '#search-input',
    ...commonTableSetup
};

var adminTable = $("#table").bootstrapTable(tableSetup);

$(function () { // document ready
    preload("#admin");

    $(document).on('click', "#delete-account-btn", function() {
        $.get("getAction.php?data=adminCount").fail(function() {
            $("#single-admin-confirm-modal").modal("show");
        });
    });

    try {
        $("#delete-account-form").validate({
            rules: {
                "password-delete": {
                    required: true,
                    minlength: 8,
                    remote: {
                        url: `getAction.php?data=validatePassword&uid=${uid}`,
                        type: "post",
                        data: {
                            "password-delete": function () {
                                return $("[name='password-delete']").val();
                            }
                        }
                    }
                }
            },
            messages: {
                "password-delete": {
                    required: REQUIRED,
                    minlength: "<p class='text-danger'><small>Please choose a password with at least 8 characters</small></p>",
                    remote: "<p class='text-danger'><small>Incorrect password</small></p>"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: "action.php",
                    type: "post",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        location.replace(JSON.parse(data));
                    }
                })
            }
        });

    } catch (e) {
        
    }




    hideSpinner();
});
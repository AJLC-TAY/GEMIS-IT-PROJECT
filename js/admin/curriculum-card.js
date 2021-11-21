import {setup, reload, addModal, eventDelegations} from "./card-page.js";

let prepareHTML = data => {
    let template = $('#card-template').html();
    let html =  '';
    data.forEach(row => {
        html += template.replaceAll('%CODE%', row.cur_code)
                        .replaceAll('%NAME%', row.cur_name)
                        .replaceAll('%DESC%', row.cur_desc);
    });
    return html;
};

let prepareArchiveHTML = archivedData => {
    console.log("from prepareArchiveHTML");
    let html = '';
    archivedData.forEach(element => {
        var code = element.cur_code;
        var name = element.cur_name;
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${name}
                <button data-name='${name}' class='unarchive-option btn link' id='${code}'>Unarchive</button></li>`;
    });
    return html;
};
setup('curriculum', curricula, prepareHTML, prepareArchiveHTML);
reload();
// custom script
$(function() {
    /** Track Form */
    $("#curriculum-form").validate({
        rules: {
            code: {
                alphanumeric: true,
                noSpace: true,
                required: true,
                remote: {
                    url: "getAction.php?data=checkCodeUnique&type=curriculum",
                    type: "post",
                    data: {
                        code: function() {
                            return $("[name='code']").val();
                        }
                    }
                }
            },
            name: {
                required: true
            }
        },
        messages: {
            code: {
                alphanumeric: '<p class="text-danger user-select-none">Letters, numbers, and underscores only please</p>',
                noSpace: '<p class="text-danger user-select-none">Code should not have a space!</p>',
                required: '<p class="text-danger user-select-none">Please enter curriculum code!</p>',
                remote: '<p class="text-danger user-select-none">Code is already taken, please enter another code.</p>'
            },
            name: {
                required: '<p class="text-danger user-select-none">Please enter curriculum name!</p>'
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: "action.php",
                type: "post",
                processData: false,
                contentType: false,
                data: new FormData(form),
                success: function (data) {
                    $(form).trigger('reset');
                    addModal.modal('hide');
                    console.log("New data: \n");
                    console.log(data);
                    reload(JSON.parse(data));
                    hideSpinner();
                    $(".no-result-msg").hide();
                    showToast('success', 'Curriculum successfully added');
                }
            });
            return false;  //This doesn't prevent the form from submitting.
        }
    });

    eventDelegations();
    hideSpinner();
});
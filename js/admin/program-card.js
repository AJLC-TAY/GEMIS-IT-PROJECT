import {setup, reload, addModal, eventDelegations} from "./card-page.js";

let prepareHTML = data => {
    let template = $('#card-template').html();
    let html = "";
    data.forEach(e => {
        html += template.replaceAll('%PROGCODE%', e.prog_code)
                        .replaceAll('%PROGDESC%', e.prog_desc)
                        .replaceAll('%CURCODE%', e.curr_code)
    });
    return html;
};
//
// let prepareArchiveHTML = archivedData => {
//     console.log("from prepareArchiveHTML");
//     let html = '';
//     archivedData.forEach(element => {
//         var code = element.prog_code;
//         var name = element.prog_desc;
//         html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${name}
//                 <button data-name='${name}' class='unarchive-option btn link' id='${code}'>Unarchive</button></li>`;
//     });
//     return html;
// };
setup('program', programs, prepareHTML);
reload();

// custom script
$(function() {
    $("#program-form").validate({
      rules: {
        "prog-code": {
            alphanumeric: true,
            noSpace: true,
          required: true,
            remote: {
                url: "getAction.php?data=checkCodeUnique&type=program",
                type: "post",
                data: {
                    code: function() {
                        return $("[name='prog-code']").val();
                    }
                }
            }
        },
        desc: {
          required: true
        },
        "curr-code": {
          required: true
        }
      },
      messages: {
          "prog-code": {
            alphanumeric: '<p class="text-danger user-select-none">Letters, numbers, and underscores only please</p>',
            noSpace: '<p class="text-danger user-select-none">Code should not have a space!</p>',
            required: '<p class="text-danger user-select-none">Please enter program code!</p>',
          remote: '<p class="text-danger user-select-none">Code is already taken, please enter another code.</p>'
        },
        desc: {
          required: '<p class="text-danger user-select-none">Please enter program name!</p>',
          remote: $.validator.format("{0} is already associated with an account.")
        },
        "curr-code": {
            required: '<p class="text-danger user-select-none">Please select a track.</p>'
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
                  reload(JSON.parse(data));
                  hideSpinner();
                  $(".no-result-msg").hide();
                  showToast("success", "Program successfully added");
              }
          });
        return false;  //This doesn't prevent the form from submitting.
      }
    });
    // $('#program-form').submit(function(e) {
    //     e.preventDefault();
    //     showSpinner();
    //     var form = $(this);
    //     var formData = form.serialize();
    //     $.post("action.php", formData, function(data) {
    //         form.trigger('reset');
    //         addModal.modal('hide');
    //         reload(JSON.parse(data));
    //         hideSpinner();
    //         $(".no-result-msg").hide();
    //         showToast("success", "Program successfully added");
    //     }).fail(function () {
    //
    //     });
    // });
    eventDelegations();
    hideSpinner();
});
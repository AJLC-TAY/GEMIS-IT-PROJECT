import {commonTableSetup} from "./utilities.js";

preload("#curr-management", "#curriculum");

const tableSetup = {
    url:                `getAction.php?code=${code}&data=program`,
    method:             'GET',
    uniqueId:           'code',
    idField:            'code',
    height:             300,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    clickToSelect:      true,
    ...commonTableSetup
};

let programTable = $("#table").bootstrapTable(tableSetup);

var archiveMessage = 'Archiving this program will also archive all subjects and student grades under it.';

$(function () {
    $('#edit-btn').click(function(e) {
        e.preventDefault();
        $(this).toggle();
        $('.decide-con').removeClass('d-none');
        $("#curriculum-form .form-input").prop('disabled', false);
    });

    $('#track-archive-btn').click(function(){
        var code = $(this).attr('id');
        let name = $(this).attr('data-name');
        let archiveModal = $('#track-archive-modal');
        archiveModal.find('.modal-identifier').html(`${name} Program`);
        archiveModal.find('.modal-msg').html(archiveMessage);
        archiveModal.find('.archive-btn').attr('id', code);
        archiveModal.modal('toggle');
    });

    $('.archive-btn').click(function(event){
        var $table = $("#table");
        var action = 'archiveProgram';
    
        let selected = $table.bootstrapTable('getSelections');
        console.log(selected);
        selected.forEach(element => {
            var code = element.prog_code;
            $.post("action.php", {code, action:action}, function(data) {	
                $table.bootstrapTable('refresh');
            });
        });
        $('#track-archive-modal').modal('hide');
    });

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
                    $("#add-modal").modal('hide');
                    hideSpinner();
                    $(".no-result-msg").hide();
                    showToast("success", "Program successfully added");
                    $("#table").bootstrapTable("refresh");
                }
            });
            return false;  //This doesn't prevent the form from submitting.
        }
    });

    hideSpinner();
});
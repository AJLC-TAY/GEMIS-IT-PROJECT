import {commonTableSetup} from "./utilities.js";

preload("#curr-management", "#program");

const tableSetup = {
    url:                `getAction.php?prog_code=${code}&data=subjects`,
    method:             'GET',
    uniqueId:           'sub_code',
    idField:            'sub_code',
    height:             600,
    ...commonTableSetup,
    pageSize:           20
};

let programTable = $("#table").bootstrapTable(tableSetup);

var archiveMessage = 'Archiving this subject will also archive all student grades under it.';

var tempData = [];
$(function() {
    $('#edit-btn').click(function(event) {
        event.preventDefault();
        $('.decide-con').removeClass('d-none');
        $(this).addClass('d-none');
        $("#program-view-form").find('.form-input').each(function() {
            tempData.push($(this).val());
            $(this).prop('disabled', false);
        });
    });


    $('#subject-archive-btn').click(function(e){
        var code = $(this).attr('id');
        let name = $(this).attr('data-name');
        let archiveModal = $('#subject-archive-modal');
        archiveModal.find('.modal-identifier').html(`${name} Subject`);
        archiveModal.find('.modal-msg').html(archiveMessage);
        archiveModal.find('.archive-btn').attr('id', code);
        archiveModal.modal('toggle');
    });

    $('.archive-btn').click(function(e){
        var $table = $("#table");
        var action = 'archiveSubject';
    
        let selected = $table.bootstrapTable('getSelections');
        selected.forEach(element => {
            var code = element.sub_code;
            $.post("action.php", {code, action}, function(data) {	
                $table.bootstrapTable('refresh');
            });
        });

        $('#subject-archive-modal').modal('hide');
    })

     $('#program-view-form').submit(function(e) {
        e.preventDefault();
        showSpinner();
        var formData = $(this).serializeArray();
        $.post("action.php", formData, () => {
            $(this).find("input, textarea").prop("disabled", true);
            $('#edit-btn').removeClass('d-none');
            $('.decide-con').addClass('d-none');
            showToast('success', 'Program successfully updated');
            hideSpinner();
        }).fail(function () {

        });
    });

    $(document).on("submit", "#prog-form", function(e) {
        e.preventDefault();
        let form = $(this);
        $.post("action.php", form.serializeArray(), function(data) {
            let res = JSON.parse(data);
            programTable.bootstrapTable("refresh", res['data']);
            form.trigger("reset");
            $("#add-modal").modal("hide");
        });
    });
    
    hideSpinner();
});

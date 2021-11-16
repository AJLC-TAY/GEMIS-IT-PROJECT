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

    // $("#curriculum-form").submit(function(e) {
    //     e.preventDefault();
    //     $.post("action.php", $(this).serializeArray(), (data) => {
    //         window.location.href = `curriculum.php?code=${JSON.parse(data)}`;
    //     });
    // });
    hideSpinner();
});
import {commonTableSetup, tableUserOptionsEventListener} from "./utilities.js";

const tableSetup = {
    url:                'getAction.php?data=faculty',
    method:             'GET',
    uniqueId:           'teacher_id',
    idField:            'teacher_id',
    height:             440,
    search:             true,
    searchSelector:     '#search-input',
    ...commonTableSetup
};
let facultyTable = $('#table').bootstrapTable(tableSetup);
let selection;

$(function() {
    preload('#faculty');
    $('#edit-btn').click(function() {
        $(this).prop("disabled", true);
        $("#save-btn").prop("disabled", false);
        $(this).closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', false);
        })
    });

    /** Table options events */
    tableUserOptionsEventListener('FA');

    hideSpinner();
});
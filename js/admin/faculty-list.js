import {commonTableSetup, tableUserOptionsEventListener} from "./utilities.js";

function buttons () {
    return {
        deactivateBtn: {
            text: 'Highlight Users',
            icon: '',
            event: function () {
                alert('Do some stuff to e.g. search all users which has logged in the last week')
            },
            attributes: {
                title: 'Search all users which has logged in the last week'
            }
        },
        resetPassword: {
            text: 'Add new row',
            icon: 'bi-box-arrow-up-left',
            event: function () {
                alert('Do some stuff to e.g. add a new row')
            },
            attributes: {
                title: 'Add a new row to the table'
            }
        },
        addBtn: {
            text: 'Add new row',
            icon: 'bi bi-plus-square-fill',
            buttonClass: 'success',
            event: function () {
                alert('Do some stuff to e.g. add a new row')
            },
            attributes: {
                title: 'Add a new row to the table'
            }
        }
    };
}

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
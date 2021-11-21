import {commonTableSetup} from "./utilities.js";

let url = 'getAction.php?data=student-award-selection';
const tableSetup = {
    url:                url,
    method:             'GET',
    uniqueId:           'id',
    idField:            'id',
    height:             440,
    search:             true,
    searchSelector:     '#search-input',
    ...commonTableSetup
};

const selectedTableSetup = {
    uniqueId:           'id',
    idField:            'id',
    height:             440,
    ...commonTableSetup
}

let table = $("#table").bootstrapTable(tableSetup);
let selectionTable = $("#student-selection").bootstrapTable(selectedTableSetup);
let selection = [];

function checkAll() {
    $("#ae-table").bootstrapTable('onPost');
}
const changeSignatory = () => {
    let selectedSignatory = $("#id-no-select option:selected");
    $("[name='signatory']").val(selectedSignatory.attr("data-name"));
    $("[name='position']").val(selectedSignatory.attr("data-position"));
}
$(function () {
    preload('#awards');

    try {
        $("#id-no-select").select2({
            theme: "bootstrap-5",
            width: null
        });
        /** Populate signatory forms */
        changeSignatory();

        $(document).on("change", " #id-no-select", function() {
            changeSignatory()
        });
    } catch (e) {}


    try {
        // $("#ae-table").bootstrapTable('hideColumn', "input");

        // /** Check all rows after rendering the academic excellence table */
        // $(document).on("post-body.bs.table", "#ae-table", function() {
        //     $("#ae-table").bootstrapTable('checkAll');
        // });
    } catch (e) {}

    $(document).on("change", ".filter-item", function () {
        let yearLevel = $("#year-level").val();
        let section = encodeURIComponent($("#section").val());
        let strand = $("#strands").val();
        let newURL = url;
        newURL += (yearLevel === '*') ? "" : `&grd=${yearLevel}`;
        newURL += (section === '*') ? "" : `&section_code=${section}`;
        newURL += (strand === '*') ? "" : `&prog_code=${strand}`;
        table.bootstrapTable('refresh', {url: newURL});
    });

    $(document).on("click", ".action", function() {
        let id = $(this).attr("data-id");
        console.log(id);
        switch ($(this).attr('data-type')) {
            case 'add':
                if (selection.includes(id)) {
                    return showToast("danger", "Student is already added");
                }
                selection.push(id);
                let row = table.bootstrapTable("getRowByUniqueId", id);
                let studentID = row.id;

                selectionTable.bootstrapTable("append", {
                    'id'            : studentID,
                    'lrn'           : row.lrn,
                    'name'          : row.name,
                    'grd'           : row.grd,
                    'curr_code'     : row.curr_code,
                    'prog_code'     : row.prog_code,
                    'section_code'  : row.section_code,
                    'section_name'  : row.section_name,
                    'action'        : `<div class='d-flex justify-content-center'>
                                            <button data-id='${studentID}' class='btn btn-sm btn-danger action' data-type='remove'>Remove</button>
                                        </div>`
                });
                break;
            case 'remove':
                selectionTable.bootstrapTable("remove", {
                    field            : 'id',
                    values           : id
                });

                selection = selection.filter(e => e !== id);
                break;
        }
    });

    $(document).on("click", "#ae-table .action", function(e) {
        e.preventDefault();
        let id, row, elementToToggle, type;
        $(this).hide();

        id = $(this).attr("data-id");
        row = $(this).closest("tr");
        let inputState = true;

        switch($(this).attr("data-type")) {
            case "remove":
                type = "undo";
                row.addClass("bg-light");
                break;
            case "undo":
                type = "remove";
                inputState = false;
                row.removeClass("bg-light");
                break;
        }
        $(`.action[data-id='${id}'][data-type='${type}']`).show();
        row.find("input").prop("disabled", inputState);
        console.log($("#ae-table").bootstrapTable("getRowByUniqueId", id));
    });

    $(document).on("click", "button[form='ae-form']", function() {
        $("#academic-excellence-form").append(`<input type='hidden' name='test' value='test1'>`);
    })

    /** Sets all select filter into All with value of '*' and refreshes the table */
    $(document).on("click", ".reset-filter-btn", function() {
        $(this).closest(".collapse").find("select").val("*");
        table.bootstrapTable("refresh", {url});
    });
    hideSpinner();
});
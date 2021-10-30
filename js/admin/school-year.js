import { commonTableSetup, listSearchEventBinder } from "./utilities.js";

preload('#curr-management', '#school-yr');

const tableSetup = {
    url: 'getAction.php?data=school_year',
    method: 'GET',
    uniqueId: 'id',
    idField: 'id',
    height: 425,
    search: true,
    searchSelector: '#search-input',
    ...commonTableSetup
};

const MONTHS = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

var syTable = $("#table").bootstrapTable(tableSetup);
var enrollAfter = false;
try {
    var stepper = new Stepper($('#school-year-stepper')[0]);
} catch (e) {}

/**
 * Displays the current values of the row to the given html tag
 * @param {Number} id        School Year ID, which is also the unique ID of the row
 * @param {Object} row       Record containing all html elements
 * @param {String} inputs    Receiving HTML tag of the current values
 */
const setCurrentValuesToInput = (id, row, inputs = "input") => { // id of row
    let data, quarter, semester, inputsToDisplay, inputsToHide;
    // get values from table
    data = syTable.bootstrapTable("getRowByUniqueId", id);
    // grade = data.current_grd_val;
    quarter = data.current_qtr_val;
    semester = data.current_sem_val;

    // show all the elements with the provided html tag
    inputsToDisplay = row.find(inputs);
    inputsToDisplay.removeClass("d-none");

    // hide select elements if the provided tag is input, or vise versa
    inputsToHide = (inputs == "select") ? "input[class*='form-control']" : "select";
    row.find(inputsToHide).addClass("d-none");

    if (inputs == "input") {
        const SELECTOR = `select[data-id=${id}]`;
        // let grd = $(`${SELECTOR} [value=${grade}]`).text();
        let qtr = $(`${SELECTOR} [value=${quarter}]`).text();
        let sem = $(`${SELECTOR} [value=${semester}]`).text();

        const INPUT = `input[data-id=${id}]`;
        // $(`${INPUT} [data-name=grade-level]`).val(grd);
        $(`${INPUT} [data-name=quarter]`).val(qtr);
        $(`${INPUT} [data-name=semester]`).val(sem);
        return;
    }

    // inputsToDisplay.eq(0).val(grade);
    inputsToDisplay.eq(0).val(quarter);
    inputsToDisplay.eq(1).val(semester);
};


$(function() {
    $(document).on("click", ".edit-btn", function() {
        syTable.bootstrapTable("showLoading");
        let element, row, id;
        element = $(this);
        id = element.attr("data-id"); // store the id of the row
        element.toggle(false); // hide edit button
        element.siblings(".edit-options").toggle(true); // show the edit options div, which contains the cancel and save buttons
        row = element.closest("tr"); // get the row
        $(".edit-btn").prop("disabled", true); // disable other edit buttons

        setCurrentValuesToInput(id, row, "select");
        syTable.bootstrapTable("hideLoading");
    });

    $(document).on("click", ".cancel-btn", function() {
        syTable.bootstrapTable("showLoading");
        let element, id, row, editOptions;
        element = $(this);
        id = element.attr("data-id");
        row = element.closest("tr");
        $(".edit-btn").prop("disabled", false); // enable all edit button

        setCurrentValuesToInput(id, row);
        editOptions = element.closest(".edit-options"); // hide the edit options
        editOptions.toggle(false);
        editOptions.prev(".edit-btn").toggle(true);
        syTable.bootstrapTable("hideLoading");
    });

    $(document).on("click", ".save-btn", function() {
        syTable.bootstrapTable("showLoading");
        let element, id, record, selectInputs, formData;
        element = $(this);
        id = element.attr("data-id");
        record = element.closest("tr");
        selectInputs = record.find("select");

        // add additional post variables to form data
        formData = selectInputs.serialize();
        formData += `&sy_id=${id}`;
        formData += "&action=editSY";

        $.post("action.php", formData);

        let newGradeVal, newQtrVal, newSemVal;
        // newGradeVal = selectInputs.eq(0).val(); // .eq() is like getting the element through its index
        newQtrVal = selectInputs.eq(0).val();
        newSemVal = selectInputs.eq(1).val();

        // update the current values in the record of bootstrap table with
        // the given row ID, and row values
        $("#table").bootstrapTable('updateByUniqueId', {
            id,
            row: {
                // current_grd_val: newGradeVal,
                current_qtr_val: newQtrVal,
                current_sem_val: newSemVal
            }
        });

        let grade, quarter, semester, inputsToDisplay;
        // find select inputs get their labels and set them to the input html tags
        // grade = selectInputs.eq(0).find(`[value='${newGradeVal}']`).text();
        quarter = selectInputs.eq(0).find(`[value='${newQtrVal}']`).text();
        semester = selectInputs.eq(1).find(`[value='${newSemVal}']`).text();

        inputsToDisplay = $(`input[class*='form-control'][data-id='${id}']`);
        // inputsToDisplay.eq(0).val(grade);
        inputsToDisplay.eq(0).val(quarter);
        inputsToDisplay.eq(1).val(semester);

        $(".edit-btn").prop("disabled", false); // enable all edit buttons
        syTable.bootstrapTable("hideLoading");
        showToast('success', 'Successfully updated!');
    });

    /** Changes the enrollment status of the school year */
    $(document).on("click", "[name='enrollment']", function() {
        syTable.bootstrapTable("showLoading");
        let statusE = $(this).next(".status");
        let syID = $(this).attr("data-id");
        let formData = `sy_id=${syID}&action=editEnrollStatus`;
        if ($(this).is(":checked")) {
            $(this).attr("title", "Turn off enrollment");
            statusE.html("On-going");
            formData += "&enrollment=on";
        } else {
            $(this).attr("title", "Turn on enrollment");
            statusE.html("Ended");
        }
        console.log(formData);
        $.post("action.php", formData, function() {
            syTable.bootstrapTable("refresh");
            syTable.bootstrapTable("hideLoading");
        });
    });

    /** Checkbox all event */
    $(document).on("click", ".checkbox-all", function() {
        $($(this).attr("data-target-list")).find("input").prop("checked", $(this).is(":checked"));
    });


    const toggleListElement = (selector, bool) => {
        $(`[data-track-id='${selector}']`).prop("disabled", !bool);
    }
    $(document).on("click", "#track-checkbox-all", function() {
        let isChecked = $(this).is(":checked");
        let list = $(this).attr("data-target-list");
        let tracks = $(list).find('label');
        console.log("tracks", tracks);
        tracks.each(function() {

            toggleListElement($(this).find('input').val(), isChecked)
        })
    });

    $(document).on('click', "input[name='initAndEnroll']", function(e) {
        e.preventDefault();
        enrollAfter = true;
        $("#school-year-form").submit();
    });

    $(document).on("submit", "#school-year-form", function(e) {
        e.preventDefault();
        showSpinner();
        let formData = $(this).serializeArray();
        formData.push({ name: 'action', value: 'initializeSY' });

        showToast('dark', 'Initializing school year ...');
        // console.log(formData);
        $.post("action.php", formData, function(data) {
            let sy_code = JSON.parse(data);
            let message = 'Redirecting to the initialized school year';
            let url = `schoolYear.php?id=${sy_code}`;
            console.log(url);
            if (enrollAfter) {
                message = 'Redirecting to the enrollment setup page';
                url = 'enrollment.php?page=setup';
            }
            showToast('dark', message, { delay: 3000 });
            location.replace(url);
        });
    });

    $(document).on("change", ".track-checkbox", function() {
        let selector = $(this).val();
        // $(`[data-track-id='${selector}']`).closest('label').toggle($(this).is(":checked"));
        let isChecked = $(this).is(":checked");
        toggleListElement(selector, isChecked);
    })

    /** Stepper */
    $(document).on("click", ".next", () => {
        stepper.next();
    });

    $(document).on("click", ".previous", () => {
        stepper.previous();
    });

    /** Search Events */
    listSearchEventBinder("#search-core-subjects", "#core-list label", "#core-spinner", "#core-empty-msg");
    listSearchEventBinder("#search-spap-subjects", "#spap-list label", "#spap-spinner", "#spap-empty-msg");

    /** Clear buttons */
    $(document).on("click", ".clear-btn", function() {
        let target = $(this).attr('data-target');
        let spinnerSelector = `${target}-spinner`;
        showSpinner(spinnerSelector);
        // hide empty message
        $(`${target}-empty-msg`).toggle(false);
        setTimeout(() => {
            // show label items
            $(`${target}-list label`).toggle(true);
            hideSpinner(spinnerSelector);
        }, 300);
    });

    /** Month */
    // function createMonthListItem(monthID, monthDesc, days) {
    //     return `<li class='form-control-sm row'>
    //                 <label for='${monthID}' class='col-form-label-sm col-4'>${monthDesc}</label>
    //                 <div class='col-5'>
    //                     <input value='${days}' id='${monthID}' type='number' name='month[${monthID}]' class='number form-control form-control-sm' placeholder='Enter no. of days' title='${monthDesc}' min='0' max='30''>
    //                 </div>
    //                 <div class='col-3 text-center'>
    //                     <button class='btn btn-sm btn-danger edit-opt' data-type='remove'>Remove</button>
    //                     <button class='btn btn-sm btn-primary edit-opt' data-type='undo' style='display: none;'>Undo</button>
    //                 </div>
    //             </li>`;
    // }

    function createNewMonthInputItem(monthDesc, days) {
        return `<li class='form-control-sm row'>
                    <label class='col-form-label-sm col-4'>${monthDesc}</label>
                    <div class='col-5'>
                        <input value='${days}' type='number' name='newmonth[${monthDesc}]' class='number form-control form-control-sm' placeholder='Enter no. of days' title='${monthDesc}' min='0' max='30''>
                    </div>
                    <div class='col-3 text-center'>
                        <button class='btn btn-sm btn-danger edit-opt' data-type='remove-new'>Remove</button>
                        <button class='btn btn-sm btn-primary edit-opt' data-type='undo' style='display: none;'>Undo</button>
                    </div>
                </li>`;
    }

    // $(document).on("click", ".edit-month-btn", function () {
    //     let idRow, modal, row, monthsOfSY;
    //     idRow = $(this).attr("data-id");
    //     modal = $("#month-modal");
    //     row = syTable.bootstrapTable('getRowByUniqueId', idRow);
    //     monthsOfSY = row.acad_months;

    //     console.log(row);
    //     let monthsHTML = '';
    //     for (const monthID in monthsOfSY) {
    //         let item = monthsOfSY[monthID];
    //         let monthDesc = item['month'];
    //         monthsHTML += createMonthListItem(monthID, monthDesc, item['days']);
    //     }

    //     $("#month-form").find("[name='sy-id']").val(row.id);
    //     $("#month-list").html(monthsHTML);
    //     modal.modal("show");
    // });

    $(document).on("click", ".edit-opt", function(e) {
        e.preventDefault();
        let button = $(this);

        let toggle;
        switch (button.attr("data-type")) {
            case "add":
                let lastMonth = $("#month-list li").last().find("label").text(); // get month name of the last li
                let index = MONTHS.indexOf(lastMonth) + 1; // get the next index month
                let newMonth = MONTHS[(index === 11) ? 0 : index];
                $("#month-list").append(createNewMonthInputItem(newMonth, 20));
                return;
            case "remove":
                toggle = true;
                break;
            case "remove-new":
                $(this).closest("li").remove();
                return;
            case "undo":
                toggle = false;
                break;
        }
        button.toggle(false);
        button.siblings().toggle(true);
        button.closest("li").find("input").prop('disabled', toggle);
    });

    $(document).on("submit", "#month-form", function(e) {
        e.preventDefault();
        console.log($(this).serializeArray());
        $.post("action.php", $(this).serializeArray(), function() {
            syTable.bootstrapTable("refresh");
            $("#month-modal").modal("hide");
            showToast("success", "Successfully updated");
        });
    });
    hideSpinner();
});
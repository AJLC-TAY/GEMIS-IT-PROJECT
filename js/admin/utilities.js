export let commonTableSetup = {
    maintainMetaDat: true, // set true to preserve the selected row even when the current table is empty
    pageSize: 10,
    pagination: true,
    pageList: "[10, 25, 50, All]",
    paginationParts: ["pageInfoShort", "pageSize", "pageList"],
    clickToSelect: true,
    loadingTemplate
}
export const clearButtonTableEvent = () => {
    // clear button for search subject input in the as-modal
    $(document).on("click", ".clear-table-btn", function() {
        $($(this).attr("data-target-table")).bootstrapTable("resetSearch");
    });
};

/** Assign Subject to Faculty Methods */
export const implementAssignSubjectMethods = (assignedSub, subTable) => {
    let assigned = assignedSub; // list of assigned subjects
    let subjectTable = subTable; // the table where the data of assigned subjects will be rendered

    $(document).on("click", ".edit-as-btn, #edit-as-btn", function() {
        subjectTable.bootstrapTable('checkBy', { field: 'sub_code', values: assigned })
    });

    // uncheck all records if cancel button is clicked
    $(document).on("click", "#cancel-as-btn", () => {
        subjectTable.bootstrapTable("uncheckAll");
    });

    $(document).on("submit", "#as-form", function(e) {
        e.preventDefault();
        showSpinner();
        let form = $(this);
        let formData = form.serializeArray();
        let selection = subjectTable.bootstrapTable("getSelections");
        let newSubCodes = selection.map(e => { return e.sub_code });
        let newSubjects = newSubCodes.map(value => { return { name: "subjects[]", value } });
        formData.push(...newSubjects);
        $.post("action.php", formData, function() {
            assigned = newSubCodes;
            let emptyMsg = $("#empty-as-msg");
            let emptySubjectCon = () => $(".assigned-sub-con a").remove();
            if (assigned.length === 0) {
                emptyMsg.removeClass("d-none");
                emptySubjectCon();
            } else {
                emptyMsg.addClass("d-none");
                emptySubjectCon();
                selection.forEach(e => {
                    $(".assigned-sub-con").append(`<a target='_blank' href='subject.php?sub_code=${e.sub_code}' class='list-group-item list-group-item-action' aria-current='true'>
                                            <div class='d-flex w-100 justify-content-between'>
                                                <p class='mb-1'>${e.sub_name}</p>
                                                <small>${e.sub_type}</small>
                                            </div>
                                            <small class='mb-1 text-secondary'><b>${e.for_grd_level}</b> | ${e.sub_code}</small>
                                        </a>`);
                });
            }
            form.trigger("reset");
            $("#as-modal").modal("hide");
            hideSpinner();
            showToast('success', "Handled subjects successfully updated");
        });
    })
}

/** Assign subject class to faculty */

export const implementAssignSubjectClassMethods = (ASSIGNEDSCID, SCID) => {

    $(document).on("click", "#add-sc-option", e => {
        e.preventDefault();
        $("#add-sc-modal").modal("show");
    });

    $("#add-sc-modal").on("show.bs.modal", function() {
        showSpinner();
        $(SCID).bootstrapTable('uncheckAll');
        setTimeout(() => {
            $(SCID).bootstrapTable('resetView');
            hideSpinner();
        }, 1000);
    });

    /** Filter button of the Subject Class table */
    $(document).on('click', '.filter-item', function(e) {
        e.preventDefault();
        let subClassTable = $(SCID);
        subClassTable.bootstrapTable('showLoading');
        // Add active state to the button and remove active state from other options
        $(this).addClass('active');
        $(this).closest("ul").find("li a").not($(this)).removeClass('active');

        // Get value of the button to know what to filter
        let value = $(this).attr('data-value');
        console.log(value);
        let filterData = {};
        if (value !== '*') filterData.status = [value];
        else subClassTable.bootstrapTable('refresh');

        subClassTable.bootstrapTable('filterBy', filterData)
            .bootstrapTable('hideLoading');
    })

    /** Moves the list of selected data from the specified origin table to the table destination*/
    const moveData = (dataList, origin, destination) => {
        dataList.forEach(e => moveElement(e.sub_class_code, origin, destination));
    };

    /** Moves the selected data from the specified origin table to the specified table
     *  destination through the specified id */
    const moveElement = (uniqueID, origin, destination) => {
        let color = (origin === ASSIGNEDSCID) ? 'success' : 'warning';
        // change value of selected row
        let selected = $(origin).bootstrapTable('getRowByUniqueId', uniqueID);
        selected.teacher_id = null;
        selected.status = "available";
        selected.statusImg = `<span class='badge available'><div class='bg-${color} rounded-circle me-2' style='width: 10px; height: 10px;' title='Available'></div></span>`;

        // remove selected from the table
        $(origin).bootstrapTable('removeByUniqueId', uniqueID);
        // prepend data to destination table
        $(destination).bootstrapTable('prepend', selected);
    };

    $(document).on("submit", "#sc-form", function(e) {
        e.preventDefault();
        showSpinner();

        let form, formData, selections, subClasses;
        form = $(this);
        selections = $(SCID).bootstrapTable('getSelections');

        if (form.attr('data-page') === 'profile') { // allow database update on profile page only
            // form page
            formData = form.serializeArray();
            subClasses = selections.map(e => {
                return { name: "sub_class_code[]", value: e.sub_class_code }
            });
            formData.push(...subClasses);
            $.post("action.php", formData);
        }

        moveData(selections, SCID, ASSIGNEDSCID);

        $(ASSIGNEDSCID).bootstrapTable("uncheckAll");
        $("#add-sc-modal").modal("hide");
        hideSpinner();
    });

    const createUnassignForm = () => {
        let formData = new FormData();
        formData.append("action", "unassignSubClasses");
        formData.append("teacher_id", teacherID);
        return formData;
    };

    $(document).on("click", ".unassign-btn", function(e) {
        e.preventDefault();
        let asSCTable = $(ASSIGNEDSCID);
        asSCTable.bootstrapTable('showLoading');

        let subCode = $(this).attr("data-sc-code");

        if (asSCTable.attr('data-page') === 'profile') {
            let formData = createUnassignForm();
            formData.append("sub_class_code[]", subCode);

            $.ajax({
                url: "action.php",
                data: formData,
                cache: false,
                contentType: false, // sending form data object will create error if content type and process data is not set to false
                processData: false,
                method: 'POST'
            });
        }

        moveElement(subCode, ASSIGNEDSCID, SCID);
        setTimeout(() => asSCTable.bootstrapTable('hideLoading'), 300);
    });

    /** Unassign all */
    $(document).on('click', '.unassign-selected-btn', (e) => {
        e.preventDefault();
        showSpinner(ASSIGNEDSCID, true);
        let selection = $(ASSIGNEDSCID).bootstrapTable("getSelections");
        if (selection.length === 0) {
            hideSpinner(ASSIGNEDSCID, true);
            return showToast("danger", "Please select a subject class first");
        }
        let formData = createUnassignForm();
        let scCodes = [];
        selection.forEach(e => {
            let scCode = e.sub_class_code;
            scCodes.push(scCode);
            let data = ["sub_class_code[]", scCode];
            formData.append(...data);
        });
        $.ajax({
            url: "action.php",
            data: formData,
            cache: false,
            contentType: false, // sending form data object will create error if content type and process data is not set to false
            processData: false,
            method: 'POST',
            success: function(data) {
                scCodes.forEach(e => moveData(e, ASSIGNEDSCID, SCID))
                    // $(SCID).bootstrapTable()
            }
        });
        hideSpinner(ASSIGNEDSCID, true);
    });
};

/** Search List */
export const searchKeyBindEvent = (searchInputSelector, listContainer) => {
    $(document).on("keyup", searchInputSelector, function() {
        let noResultMsg = $(".no-result-msg");
        // hide no result message and cards
        noResultMsg.hide();

        let page = $(listContainer).attr('data-page');
        $(`${listContainer} .tile`).hide();
        // show loading status
        showSpinner(`#${page}-spinner`);
        setTimeout(() => {
            var value = $(this).val().toLowerCase();
            let match = [];
            $(`${listContainer} .tile`).filter(function() {
                let thereIsMatch = $(this).text().toLowerCase().indexOf(value) > -1;
                match.push(thereIsMatch);
                $(this).toggle(thereIsMatch);
            });

            if (!match.includes(true)) {
                noResultMsg.show();
            }
            hideSpinner(`#${page}-spinner`);
        }, 500);
    });
};

/***
 *  Adds search feature to the specified input inorder to filter 
 *  the list of elements referred by the given selector. 
 * @param {String} searchInputID    Search input selector
 * @param {String} itemSelector     Item selector of the list 
 * */
export const listSearchEventBinder = (searchInputID, itemSelector, spinnerSelector = null, emptyMsgSelector = null) => {
    $(document).on("keyup", searchInputID, function() {
        let noResultMsg = $(emptyMsgSelector);
        // hide no result message and cards
        noResultMsg.hide();
        showSpinner(spinnerSelector);
        setTimeout(() => {
            var value = $(this).val().toLowerCase();
            var match = [];
            $(itemSelector).filter(function() {
                let thereIsMatch = $(this).text().toLowerCase().indexOf(value) > -1;
                match.push(thereIsMatch);
                $(this).toggle(thereIsMatch);
            });
            hideSpinner(spinnerSelector);
            if (!match.includes(true)) {
                noResultMsg.show();
            }
        }, 400);
    });
};

export const toggleEnrollment = () => {
    $(document).on("click", "[name='enrollment']", function() {
        showSpinner();
        let statusE = $(this).next(".status");
        let formData = `action=editEnrollStatus`;
        if ($(this).is(":checked")) {
            $(this).attr("title", "Turn off enrollment");
            statusE.html("On-going");
            formData += "&enrollment=on";
        } else {
            $(this).attr("title", "Turn on enrollment");
            statusE.html("Ended");
        }
        $.post("action.php", formData, function(data) {
            hideSpinner();
        });
    });
}

export const tableUserOptionsEventListener = (userType) => {
    let selection = [];
    let filter = (userType === 'ST' ? "stud_id": (userType === 'FA' ? 'teacher_id' : 'admin_id'));
    let userDesc = (userType === 'ST' ? 'student' : 'faculty');

    $(document).on("click", ".submit", function () {
        let type = $(this).attr("data-type");
        if (type === 'export') {
            return;
        }
        let formData = $(this).serializeArray();
        formData.push({name: 'action', value: $(this).attr('data-type')});
        formData.push({name: 'user_type', value: userType});
        formData.push(...selection.map(e => { return { name: "id[]", value: `${e[filter]}` } }));
        console.log(formData)
        $.post("action.php", formData, function () {
            $("#table").bootstrapTable("refresh");
            $("#confirmation-modal").modal("hide");
            if (type === 'reset') {
                showToast('success', "Password/s successfully put to default");
            }
        });
    });

    $(document).on("click", ".submit[data-type='export']", function () {
        $("#export-form")[0].submit();

    });

    $(document).on("click", ".table-opt", function() {
        selection = $("#table").bootstrapTable("getSelections");
        if (selection.length === 0 ) {
            return showToast('danger', `Please select a ${userDesc} first`);
        }
        switch($(this).attr('data-type')) {
            case 'export':
                var modal = $("#confirmation-modal");
                modal.find(".message").html(`Export ${userDesc} information of the selected ${userDesc}/s?`);
                modal.find(".submit").removeClass('btn-primary btn-danger').addClass('btn-success')
                    .attr('data-type', 'export').html("Generate Document");
                let html = '';
                selection.forEach(e => {
                    html += `<input type="hidden" name="id[]" value="${e[filter]}">`;
                });
                $("#export-form").html(html);
                modal.modal('show');
                break;
            case 'activate':
                $(".submit").attr('data-type', 'activate').click();
                break;
            case 'deactivate':
                var modal = $("#confirmation-modal");
                modal.find(".message").html(`<b>Deactivate ${userDesc}</b><br><small>Deactivating user will result in unavailability of all the user's data in the GEMIS. </small>`);
                modal.find(".submit").removeClass('btn-primary btn-success').addClass('btn-danger')
                    .attr('data-type', 'deactivate').html("Deactivate");
                modal.modal('show');
                break;
            case 'reset':
                var modal = $("#confirmation-modal");
                modal.find(".message").html(`<b>Reset password</b><br><small>The default password will be the combination of user type and their User ID No. Eg. ${userType}XXXXXXXXX</small>`);
                modal.find(".submit").removeClass('btn-danger btn-success').addClass('btn-secondary')
                    .attr('data-type', 'reset').html("Reset Password");
                modal.modal('show');
                break;
        }
    });
}
export const averageSubjectGradesEvent = () => {
    $(document).on("keyup", ".cal", function () {
        let row = $(this).closest("tr");
        let inputs = row.find("input");
        var final = (parseInt(inputs.eq(0).val()) + parseInt(inputs.eq(1).val())) / 2;
        inputs.eq(2).val(Math.round(final) == "NaN" ? "" : Math.round(final));
    });
}
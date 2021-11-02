const tableSetup = {
    url: `getAction.php?data=student`,
    method: 'GET',
    uniqueId: '0',
    idField: 'student_id',
    height: 425,
    maintainMetaDat: true,       // set true to preserve the selected row even when the current table is empty
    clickToSelect: true,
    pageSize: 10,
    pagination: true,
    search: true,
    pageList: "[10, 25, 50, All]",
    paginationParts: ["pageInfoShort", "pageSize", "pageList"],
    fixedColumns: true,
    searchSelector: '#search-input',

};

let studentTable = $("#table").bootstrapTable(tableSetup);
let selection = [];
$(function () {
    preload('#student','#student-list');

    /** Deactivate */
    $(document).on("click", ".submit", function () {
        if ($(this).attr("data-type") === 'export') {
            return;
        }
        let formData = $(this).serializeArray();
        formData.push({name: 'action', value: $(this).attr('data-type')});
        formData.push({name: 'user_type', value: 'ST'});
        formData.push(...selection.map(e => { return { name: "id[]", value: `${e.stud_id}` } }));
        console.log(formData)
        $.post("action.php", formData, function () {
            $("#table").bootstrapTable("refresh");
            $("#confirmation-modal").modal("hide");
        });
    });

    $(document).on("click", ".submit[data-type='export']", function () {
        $("#export-form").submit();
    });
    /** Deactivate END */
    /** Table options */
    $(document).on("click", ".table-opt", function() {
        selection = $("#table").bootstrapTable("getSelections");
        if (selection.length === 0 ) {
            return showToast('danger', 'Please select a student first');
        }
        switch($(this).attr('data-type')) {
            case 'export':
                var modal = $("#confirmation-modal")
                modal.find(".message").html(`Export student information of the selected student/s?`);
                modal.find(".submit").removeClass('btn-primary btn-danger').addClass('btn-success')
                    .attr('data-type', 'export').html("Generate Document");
                let html = '';
                selection.forEach(e => {
                    html += `<input type="hidden" name="id[]" value="${e.stud_id}">`;
                });
                $("#export-form").html(html);
                modal.modal('show');
                break;
            case 'activate':
                $(".submit").attr('data-type', 'activate').click();
                break;
            case 'deactivate':
                var modal = $("#confirmation-modal")
                modal.find(".message").html(`<b>Deactivate student</b><br><small>Deactivating user will result in unavailability of all the user's data in the GEMIS. </small>`);
                modal.find(".submit").removeClass('btn-primary btn-success').addClass('btn-danger')
                     .attr('data-type', 'deactivate').html("Deactivate");

                modal.modal('show');
                break;
            case 'reset':
                var modal = $("#confirmation-modal");
                modal.find(".message").html(`<b>Reset password</b><br><small>The default password will be the combination of user type and their User ID No. Eg. STXXXXXXXXX</small>`);
                modal.find(".submit").removeClass('btn-danger btn-success').addClass('btn-secondary')
                     .attr('data-type', 'reset').html("Reset Password");
                modal.modal('show');
                break;
        }

        console.log( $("#table").bootstrapTable("getSelections"))
    })
    /** Table options end */


    hideSpinner();
});
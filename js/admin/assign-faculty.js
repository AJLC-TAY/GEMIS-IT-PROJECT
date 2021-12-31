function reloadOptions(currentID = null) {
    let options;
    if (currentID != null) {
        options = facultyOptions.map(e => {
            return parseInt(e.teacher_id) !== parseInt(currentID) ? `<option value="${e.teacher_id}">${e.name}</option>` : '';
        });
    } else {
        options = facultyOptions.map(e => {
            return `<option value="${e.teacher_id}">${e.name}</option>`;
        });
    }
    options = "<option value='*'>-- Select faculty here --</option>" + options.join('');

    $("#assign-faculty").html(options).select2({
        theme: "bootstrap-5",
        width: null,
        dropdownParent: $('#assign-modal')
    });
}
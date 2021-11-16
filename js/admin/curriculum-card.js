import {setup, reload, addModal, eventDelegations} from "./card-page.js";

let prepareHTML = data => {
    let template = $('#card-template').html();
    let html =  '';
    data.forEach(row => {
        html += template.replaceAll('%CODE%', row.cur_code)
                        .replaceAll('%NAME%', row.cur_name)
                        .replaceAll('%DESC%', row.cur_desc);
    });
    return html;
};

let prepareArchiveHTML = archivedData => {
    console.log("from prepareArchiveHTML");
    let html = '';
    archivedData.forEach(element => {
        var code = element.cur_code;
        var name = element.cur_name;
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${name}
                <button data-name='${name}' class='unarchive-option btn link' id='${code}'>Unarchive</button></li>`;
    });
    return html;
};
setup('curriculum', curricula, prepareHTML, prepareArchiveHTML);
reload();

// custom script
$(function() {
    // $('#curriculum-form').submit(function(e) {
    //     e.preventDefault();
    //     showSpinner();
    //     var form = $(this);
    //     var formData = $(this).serialize();
    //     $.post("action.php", formData, function(data) {
    //         form.trigger('reset');
    //         addModal.modal('hide');
    //         console.log("New data: \n");
    //         console.log(data);
    //         reload(JSON.parse(data));
    //         hideSpinner();
    //         $(".no-result-msg").hide();
    //         showToast('success', 'Curriculum successfully added');
    //     }).fail(function () {

    //     });
    // });
    eventDelegations();
    hideSpinner();
});
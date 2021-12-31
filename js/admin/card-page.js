import { searchKeyBindEvent } from "./utilities.js";

// HTML elements
export let addBtn, cardCon, kebab, noResultMsg, searchInput, addModal, form;

// Data
export let pageName, camelized, action, deleteMessage, keywords, dataList,
    timeout, elementAccess;
// Function
export let prepareHTMLOfData;

export const setup = (page, data, prepareHTML) => {
    // string detail to be added in the delete and archive modal messages
    // page = page
    var programString = "";
    switch (page) {
        case 'curriculum':
            preload("#curr-management", "#curriculum")
            programString = "programs/strands, "
            elementAccess = "cur_code"
            action = "getCurriculumJSON"
            break;
        case 'program':
            preload("#curr-management", "#program")
            elementAccess = "prog_code"
            action = "getProgramJSON"
            break;
    }

    pageName = page;
    camelized = page.charAt(0).toUpperCase() + page.slice(1);
    dataList = data;

    // access html elements
    addBtn = $('.add-btn');
    cardCon = $('.cards-con');
    kebab = $('.kebab');
    noResultMsg = $('.msg');
    searchInput = $('#search-input');
    addModal = $('#add-modal');
    form = $(`${page}-form`);

    // delete and arhive messages
    deleteMessage = `Deleting this ${page} will also delete all ${programString}subjects, and student grades under this ${page}.`;

    // functions
    prepareHTMLOfData = prepareHTML;

    keywords = "";
    timeout = null;
};

export const reload = (data = '') => {
    if (data) {
        dataList = data;
    }
    let addBtn =
        `   <div class='tile card shadow-sm p-0 position-relative'>
                <a role='button' class='card-link add-btn btn btn-link start-0 top-0 end-0 bottom-0 h-100' style='z-index: 2;'></a>
                <div class='card-body position-absolute d-flex justify-content-center align-items-center' style="height: auto; width: 100px; left: 50%; margin-left: -50px; top: 50%; margin-top: -50px;">
                    <div class="text-center">
                        <i class="bi bi-plus-circle-fill fa-3x"></i><br>
                    </div>
                </div>
            </div>`;
    $('.cards-con').html(prepareHTMLOfData(dataList) + addBtn);
}

export const eventDelegations = () => {
    /*** Event delegation applied here. This concept binds all the event listener to the target element even when dynamically created. */
    // search
    $(document).on('search', '#search-input', () => $(".cards-con li").fadeIn());

    searchKeyBindEvent("#search-input", ".cards-con");

    // add, delete, archive and view archive buttons
    $(document).on('click', '.add-btn', () => $('#add-modal').modal('toggle'));

    $(document).on('click', '.delete-btn', function() {
        var code = $(this).attr('id');
        var action = `delete${camelized}`;
        $.post("action.php", { code, action }, function(data) {
            $('#delete-modal').modal('hide');
            dataList = JSON.parse(data);
            reload();
            showToast('success', `${camelized} successfully deleted`);
        });
    });

    $(document).on('click', '.delete-option', function() {
        var code = $(this).attr('id');
        let name = $(this).attr('data-name');
        let deleteModal = $('#delete-modal');
        deleteModal.find('#modal-identifier').html(`${name} ${camelized}`);
        deleteModal.find('.modal-msg').html(deleteMessage);
        deleteModal.find('.delete-btn').attr('id', code);
        deleteModal.modal('toggle');
    });

    /*** Footer modal buttons */
    /*** Reset form and hide error messages */
    $(document).on('click', ".close", () => {
        $(`#${pageName}-form`).trigger('reset'); // reset form
        $("[class*='error-msg']").addClass('invisible'); // hide error messages
    });
};
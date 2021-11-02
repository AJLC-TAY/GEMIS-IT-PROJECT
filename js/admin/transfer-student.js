

// let sectionTable = $("#table").bootstrapTable(tableSetup)
import {commonTableSetup} from "./utilities.js";

const tableSetup = {
    url:                `getAction.php?data=fullSection&id=${id}`, 
    method:             'GET',
    uniqueId:           '0',
    idField:            'section_code',
    height:             300,
    onPostBody:         () => {
        $(".select2").select2({
            theme: "bootstrap-5",
            width: null,
        });
    },
    fixedColumns:       true,
    searchSelector:     '#search-sub-input',
    ...commonTableSetup
};

let sectionTable = $("#table").bootstrapTable(tableSetup)
var section_id;
var stud_id;
var current_code;
var section;
var stud_to_swap;
var current_section;
$(function() {
    preload('#student');

    $(document).on('click','.transfer', function(){
        current_section = $(this).attr('data-current-section');
        section_id = $(this).attr('id');
        stud_id = $(this).attr('data');
        let confirmationModal = $('#transferConfirmation');
        confirmationModal.find('.modal-indentifier').html(section);
        confirmationModal.modal('toggle');
    });


    $(document).on('click', '.transfer-btn', function() {
        showSpinner();
        var action = 'transferStudent'
        $.post("action.php", {section_id, stud_id, current_section, action}, function() {	
            $('#transferConfirmation').modal("hide");
            // showToast("success", "Student Successfully Transferred");
            location.reload();
        });

    });

    $(document).on('click', '.swapStudent', function() {
        var currentTds = $(this).closest("tr").find("td"); // find all td of selected row
        current_code = $(currentTds).eq(0).text(); // eq= cell , text = inner text
        section = $(currentTds).eq(1).text();
        stud_to_swap = $('.select2').val();
        let fullConfirmationModal = $('#transferConfirmationFull');

        fullConfirmationModal.find('.full-modal-indentifier').html(section);
        fullConfirmationModal.modal('toggle');

    });


    $(document).on('click', '.transfer-btn-full', function() {
        var action = 'transferStudentFull'
        $.post('action.php', {current_code, section, stud_to_swap, id, action} , () =>{
            $('#transferConfirmationFull').modal("hide");
            location.reload();
            showToast("success", "Student Successfully Transferred");
        });

    });

    $("#stud-save-btn").click(() =>$('#student-form').submit());

    $('#student-form').submit(function(e){
        console.log("submit");
    });

    const listSearchEventBinder = (searchInputID, itemSelector) => {
        $(document).on("keyup", searchInputID, function() {
            var value = $(this).val().toLowerCase();
            console.log(value);
            $(itemSelector).filter(function() {
                if ($(this).text().toLowerCase().indexOf(value) > -1) return $(this).removeClass("d-none");
                return $(this).addClass("d-none");
            });
        });
    };

    listSearchEventBinder("#search-section", ".available-section button");
    hideSpinner();

});
import {commonTableSetup} from "./utilities.js";

var forms = document.querySelectorAll('.needs-validation');

// Array.prototype.slice.call(forms).forEach(function(form) {
//     form.addEventListener('submit', function(event) {
//         if (!form.checkValidity()) {
//             document.getElementsByClassName("invalid-ln").innerText = "Hello JavaScript!";
//             event.preventDefault()
//             event.stopPropagation();
//         }

//         form.classList.add('was-validated');
//     }, false);
// });

const tableSetup = {
    url:                'getAction.php?data=administrators',
    method:             'GET',
    uniqueId:           'admin_id',
    idField:            'admin_id',
    height:             425,
    search:             true,
    searchSelector:     '#search-input',
    ...commonTableSetup
};

var adminTable = $("#table").bootstrapTable(tableSetup);

$(function () { // document ready
    preload("#admin");
    hideSpinner();
});
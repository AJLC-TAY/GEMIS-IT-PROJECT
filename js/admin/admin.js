import {commonTableSetup} from "./utilities.js";

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

$(function () {
    preload("#admin");
    hideSpinner();
});
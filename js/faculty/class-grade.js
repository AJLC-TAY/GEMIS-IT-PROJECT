import {commonTableSetup} from "../admin/utilities.js";

let tableSetup = {
    search:             true,
    searchSelector:     "#search-input",
    method:             'GET',
    uniqueId:           'lrn',
    idField:            'lrn',
    height:             440,
    ...commonTableSetup
};


let classGradeTable = $("#table").bootstrapTable(tableSetup);

/** Changes the name of class in the card */
function changeName(name) {
    $("#class").html(name);
}

/**
 * Initializes the table given the class type and data url
 * @param {String} classType Values can either be 'advisory' or 'sub-class'.
 * @param {String} url       The url from which the data will be retrieved.
 * @returns {jQuery|*}       Bootstrap-table object.
 */
function initializeTable (classType, url) {
    tableSetup.url = url;
    // if (classType === 'advisory') {
    //     return advisoryTable = $("#advisory-table").bootstrapTable(tableSetup);
    // }
    // if (classType === 'sub-class') {
    //     return subClassTable = $("#sub-table").bootstrapTable(tableSetup);
    // }
}

/**
 *
 * @param classType
 * @param url
 * @returns {*}
 */
function setTableData (classType, url) {
    studentTable.bootstrapTable("refresh", {url});
    // if (classType === 'advisory') {
    //     try {
    //         advisoryTable.bootstrapTable('refresh', {url});
    //     } catch (e) {
    //         return advisoryTable = $("#advisory-table").bootstrapTable(tableSetup);
    //     }
    //     return;
    // }
    // if (classType === 'sub-class') {
    //     try {
    //         subClassTable.bootstrapTable('refresh', {url});
    //     } catch (e) {
    //         tableSetup.url = url;
    //         return subClassTable = $("#sub-table").bootstrapTable(tableSetup);
    //     }
    // }
}



$(function() {
    preload('#grades');

    $("#classes").select2({
        theme: "bootstrap-5",
        width: "100%"
    });

    // Display current/selected section name
    let firstClass = $("#classes option:selected");
    if (firstClass != null) {
        let classTmp = firstClass.attr("data-name") || "No class assigned yet";
        let classType = firstClass.attr("data-class-type");
        // studentTable.bootstrapTable("refresh", {url: firstClass.attr("data-url")});
        // toggleGradesColumn(classType);
        // initializeTable(classType, firstClass.attr("data-url"));
        changeName(classTmp);
    }

    $(document).on("change", "#classes", function() {
        let selected, url, classType, sectionName, displayGrades;
        selected = $("#classes option:selected");
        url = selected.attr("data-url");
        sectionName = selected.attr("data-name");
        classType = selected.attr("data-class-type");

        toggleGradesColumn(classType);
        console.log(classType);
        $("#classes").select2("close");
        changeName(sectionName);
        setTableData(classType, url);
    })

    $('#export').click(function(e){
        console.log("clicked");
        var action = 'export';
        $.post("action.php", {action}, function(data) {	
        });

    })



    hideSpinner();
});
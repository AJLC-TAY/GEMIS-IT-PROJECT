function resetSchedTable() {
    $(".subject-select").val([]).change();
}

function changeSchedTable(strand) {
    resetSchedTable();
    try {

        prepareSchedOptions(strand, 'applied');
        prepareSchedOptions(strand, 'core');
        prepareSchedOptions(strand, 'specialized');

        $(".subject-select").select2({
            theme: "bootstrap-5",
            width: null,
        });

        Object.entries(schedule[strand]).forEach(item => {
            let id = item[0];
            $(`[name='${id}']`).val(item[1]).change();
        });


    } catch (e) {}
}

function getSubjectsByProgram(list, program) {
    let temp = [];
    list.forEach(element => {
        if (element.program == program) {
            temp.push(element);
        }
    });
    return temp;
}

function renderSubOptToHtml(list, type) {
    let html = '';
    const semester = [1, 2];
    const grade = [11, 12];
    list.forEach(e => {
        html += `<option value='${e.code}'>${e.name}</option>`;
    });
    grade.forEach(e => {
        semester.forEach(s => {
            $(`[name='data[${e}][${s}][${type}][]']`).html(html);
        });
    });
}

function prepareSchedOptions(firstStrand, type) {
    try {
        // filter subject options by subject type
        let opt = schedOptions[type];
        // filter subject options by program
        let finalOpt = getSubjectsByProgram(opt, firstStrand);
        renderSubOptToHtml(finalOpt, type);
    } catch (e) {}
}

$(function () {
    preload("#student");
    $.get(`getAction.php?data=schedule&code=${strandCode}`, function (scheduleData) {
        let subData = JSON.parse(scheduleData).data;
        let template = $("#table-cell-template").html();
        let html = '';

        let elevenFirSem, elevenSecSem, twelveFirSem, twelveSecSem;
        elevenFirSem = subData[0].data.subjects.length;
        elevenSecSem = subData[1].data.subjects.length;
        twelveFirSem = subData[2].data.subjects.length;
        twelveSecSem = subData[3].data.subjects.length;

        function renderCellHTML(sub) {
            if (sub) {
                return template.replaceAll("%ID%", sub.sub_code).replace("%SUBJECTNAME%", sub.sub_name);
            }
            return "<td></td>";
        }

        let count = Math.max(elevenFirSem, elevenSecSem, twelveFirSem, twelveSecSem);
        for (let i = 0; i < count; i++) {
            html += '<tr>';
            let subjectElevenFir = subData[0].data.subjects[i] ?? "";
            let subjectElevenSec = subData[1].data.subjects[i] ?? "";
            let subjectTwelveFir = subData[2].data.subjects[i] ?? "";
            let subjectTwelveSec = subData[3].data.subjects[i] ?? "";
            html += renderCellHTML(subjectElevenFir);
            html += renderCellHTML(subjectElevenSec);
            html += renderCellHTML(subjectTwelveFir);
            html += renderCellHTML(subjectTwelveSec);
            html += '</tr>';
        }
        $("#transfer-table tbody").html(html);

    });

    $.get(`getAction.php?data=transfereesubject&transferee_id=${transfereeID}`, function(data) {
        let subCodes = JSON.parse(data);
        subCodes.forEach(e => {
            $(`#${e}`).prop("checked", true);
        });
    });

    /** Subject Schedule */
    try {
        if (strandCode.length > 0) {
            changeSchedTable(strandCode);
        }

        $(document).on("submit", "#schedule-form", function (e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            formData.push({name: "action", value: "saveSchedule" });
            formData.push({name: "program", value: progSelect.val() });
            $.post("action.php", formData, function(data) {
                $("#program-select").prop("disabled", false);
                $(".edit-sched-btn").show();
                $(".edit-opt-con").addClass("d-none");
                $(".subject-select").prop("disabled", true);
                let newData = JSON.parse(data);
                let prog = newData['program'];
                delete schedule[prog];
                schedule[prog] = newData['new'][prog];
            });
        });

        $(document).on("click", ".edit-sched-btn", function() {
            $(this).hide();
            $("#program-select").prop("disabled", true);
            $(".edit-opt-con").removeClass("d-none");
            $(".subject-select").prop("disabled", false);
        });
    } catch (e) {}
    /** Subject Schedule End */
    hideSpinner();
});
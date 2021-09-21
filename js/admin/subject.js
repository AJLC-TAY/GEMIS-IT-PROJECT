/** SUBJECT TABLE LIST */
import {commonTableSetup} from "./utilities.js";

let onPostBodyOfTable = () => {};

const tableSetup = {
  url: 'getAction.php?data=subjects',
  method: 'GET',
  uniqueId: 'sub_code',
  idField: 'sub_code',
  height: 425,
  search: true,
  searchSelector: '#search-input',
  ...commonTableSetup
};

let subjectTable = $('#table').bootstrapTable(tableSetup);
let selection = 0;
/** SUBJECT TABLE LIST END */

/** CARD PAGE METHOD */
var unarchiveMessage = 'Unarchiving this subject will also unarchive all student grades under it.'
let prepareArchiveHTML = archivedData => {
  let html = '';
  archivedData.forEach(element => {
    var code = element.sub_code;
    var name = element.sub_name;
    html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${name}
                <button data-name='${name}' class='unarchive-option btn' id='${code}'>Unarchive</button></li>`
  });
  return html;
};
/** CARD PAGE METHOD END */
preload('#curr-management', '#subject');

let addAgain = false;
$(function () {
  $('#sub-type').change(function () {
    let options = $('#app-spec-options');
    let type = $(this).val();
    if (type === 'applied' || type === 'specialized') {
      options.removeClass('d-none');
      options.find('input').each(function () {
        $(this).prop('disabled', false);
        $(this).attr('type', type == 'applied' ? 'checkbox' : 'radio');
      });
    } else if (type === 'core') {
      options.addClass('d-none');
      options.find('input').each(function () {
        $(this).prop('disabled', true);
      });
    }
  });

  $('.submit-and-again-btn').click(() => {
    addAgain = true;
    $('#add-subject-form').submit();
  });

  $('.submit-btn').click(() => {
    $('#add-subject-form').submit();
  });

  $('#add-subject-form').submit(function (e) {
    e.preventDefault();
    showSpinner();
    var form = $(this);
    var formData = form.serializeArray();

    // initialize requisites array
    var prereq = [];
    var coreq = [];

    // remove radio buttons from the formdata and store them from the respective requisite arrays
    formData = formData.filter(function (item) {
      let value = item.value;
      if (item.name.includes('radio-')) {
        if (value.includes('PRE-')) {
          prereq.push(value);
        } else if (value.includes('CO-')) {
          coreq.push(value);
        }
        return false;
      }
      return true;
    });

    /**
     * Stores all subject code under one requisite to the form data.
     * @param {String}  requisite   Requisite identifier, 'pre' or 'co'.
     * @param {Array}   codeList    Raw subject code list.
     */
    var saveRequisiteCodes = (requisite, codeList) => {
      if (codeList.length === 0) return; // return if list of codes is empty

      codeList.forEach(code => {
        code = code.substring(code.indexOf('-') + 1);
        formData.push({ name: requisite, value: code }); // store subject code value; from PRE-ABM to ABM
      });
    };

    saveRequisiteCodes('PRE[]', prereq);
    saveRequisiteCodes('CO[]', coreq);

    $.post('action.php', formData, function (data) {
      hideSpinner(500);
      if (addAgain) {
        $('#add-subject-form').trigger('reset');
        $('#app-spec-options').addClass('d-none');
        $('#sub-code').attr('autofocus');
        addAgain = false;
        return showToast('success', 'Subject successfully added!');
      }
      console.log(data);
      data = JSON.parse(data);
      window.location.href = `subject.php?${data.redirect}`;
    });
  });

  $(document).on('click', '#edit-btn', function (e) {
    let editBtn = $('#edit-btn');
    editBtn.addClass('d-none');
    let cancelBtn = $('.cancel-btn');
    let link = editBtn.attr('data-link');
    cancelBtn.attr('href', link);
    cancelBtn.removeClass('disabled');
    cancelBtn.removeClass('d-none');
  });

  // Clears all the radio buttons contained in a grade level table
  $(document).on('click', '.clear-table-btn', function (e) {
    e.preventDefault();
    let grade = $(this).attr('data-desc');
    $(`#grade${grade}-table input[name*='radio']`).prop('checked', false);
  });

  // Clears the radio buttons on the same row as the clicked clear button
  $(document).on('click', '.spec-clear-btn', function (e) {
    e.preventDefault();

    $(this)
      .closest('tr')
      .find('td')
      .slice(3, 5)
      .each(function () {
        $(this)
          .find('input')
          .prop('checked', false)
      });
  });

  // Disables radio buttons in grade 12 table when 'Grade level' is 11; otherwise, enables them
  $('#grade-level').change(function () {
    let isDisabled = $(this).val() == 11 ? true : false
    $(`#grade12-table input[name*='radio']`).prop('disabled', isDisabled);
  });

  $('#edit-btn').click(function () {
    $(this).prop('disabled', true);
    $('#save-btn').prop('disabled', false);
    $(this)
      .closest('form')
      .find('.form-input')
      .each(function () {
        $(this).prop('disabled', false)
      });
  });

  $(document).on('click', '.archive-btn', function () {
    var action = 'archiveSubject';
    var formData = [{ name: "action", value: action }];
    formData.push(
        ...selection.map(e => {
            return  { name: "code[]", value : e.sub_code }
        })
    );

    $.post('action.php', formData, function (data) {
        $('#archive-modal').modal('hide');
        let archivedSub = JSON.parse(data);
        $("ul.arch-list").html(prepareArchiveHTML(archivedSub));
        subjectTable.bootstrapTable('refresh');
    });
  });

  $(document).on('click', '.archive-option', function () {
    selection = subjectTable.bootstrapTable('getSelections');
    let length = selection.length;
    if (length === 0) return showToast('danger', 'Please select a subject first');

    let archiveModal = $('#archive-modal');
    var code, name, header, detail, archiveMessage;

    if (length === 1) {
      code = selection[0].sub_code;
      name = selection[0].sub_name;
      header = `${name} subject`;
      detail = 'this subject';
      archiveMessage = `Archiving this subject will also archive all student grades under it.`;
    } else {
      header = `${length} subjects`;
      detail = 'these subjects';
      archiveMessage = `Archiving these subjects will also archive all student grades under them.`;
    }

    archiveModal.find('#modal-identifier').html(header);
    archiveModal.find('.modal-msg').html(archiveMessage);
    archiveModal.modal('toggle');
  });

  $('#view-arch-modal').on('shown.bs.modal', function () {
    $.post('action.php', { action: 'getArchiveSubjectJSON' }, data => {
      var archiveData = JSON.parse(data);
      if (archiveData.length === 0)
        return $('ul.arch-list').html(
          `<li class='text-center my-auto'>No archived subject yet</li>`
        );
      $('.arch-list').html(prepareArchiveHTML(archiveData));
    });
  });

  $('#unarchive-modal').on('show.bs.modal', function (e) {
    $('#view-arch-modal').modal('hide');
  });

  $(document).on('click', '.unarchive-btn', function () {
    $('#view-arch-modal').modal('hide');
    var code = $(this).attr('id');
    var action = `unarchiveSubject`;
    
    $.post('action.php', {"code": code, action}, function (data) {
        $('#unarchive-modal').modal('hide');
        let archivedSub = JSON.parse(data);
        $("ul.arch-list").html(prepareArchiveHTML(archivedSub));
        subjectTable.bootstrapTable('refresh');
        subjectTable.bootstrapTable('resetSearch');
        showToast("success", "Subject successfully unarchived");
    });
  });

  $(document).on('click', '.unarchive-option', function () {
    var code = $(this).attr('id');
    let name = $(this).attr('data-name');
    let unarchiveModal = $('#unarchive-modal');
    unarchiveModal.find('#modal-identifier').html(`${name} Subject`);
    unarchiveModal.find('.modal-msg').html(unarchiveMessage);
    unarchiveModal.find('.unarchive-btn').attr('id', code);
    unarchiveModal.modal('toggle');
  });

  hideSpinner();
});
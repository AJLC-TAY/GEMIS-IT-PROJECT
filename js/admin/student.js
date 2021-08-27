let prepareSectionHTML = section => {
    let html = ''
    section.forEach(element => {
        var code = element.code
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${code}
                <button class='transfer-option btn' id='${code}'>Transfer</button></li>`
    })
    return html
}
var message = 'Are you sure you want to transfer the student?'
var stud_id;
$(function() {
    // Data Picker Initialization
    // $('#datepicker').datepicker();
    // $('.transfer-stud').click(function(){
    //     $('#select-section-modal').modal('toggle')
    // })
    // preload('#student')
    $(document).on('click','.transfer-stud', function(){
        stud_id = $(this).attr('id');
        $('#select-section-modal').modal('toggle')
    })

    $('#select-section-modal').on('shown.bs.modal', function(){
        $.post('action.php', {action:'getSectionJSON'} ,(data) => {
            var sections = JSON.parse(data)
            $('.sec-list').html(prepareSectionHTML(sections))
        })
    })

    $("#transfer-student-confirmation").on('shown.bs.modal', function (e) {
        $("#select-section-modal").modal("hide");
    });

    $(document).on('click', '.transfer-btn', function() {
        var code = $(this).attr('id')
        var action = 'archiveProgram'
        var info = {'code':code, 'stud_id': stud_id};
        console.log(info)
        
        $.post('action.php', {action:'transferStudent'} ,(data) => {
            $('#transfer-student-confirmation').modal('hide')	
        })
        
    })

    $(document).on('click', '.transfer-option', function() {
        var name = $(this).attr('name')
        var code = $(this).attr('id')
        let transferModal = $('#transfer-student-confirmation')
        transferModal.find('#modal-identifier').html(`${code}`)
        transferModal.find('.transfer-btn').attr('id', code)
        transferModal.modal('toggle')
    })

    $("#stud-save-btn").click(() =>$('#student-form').submit())

    $('#student-form').submit(function(e){
        console.log("submit")
    })
})
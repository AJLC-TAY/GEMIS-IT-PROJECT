let selectedSubjects = []
const removeFromSelectedSubject = (subjectCode) => {
    const index = selectedSubjects.indexOf(subjectCode)
    if (index > -1) {
        selectedSubjects.splice(index, 1);
    }
}

export let setSubjectSelected = list => {
    selectedSubjects = list   
}
export let getSubjectSelected = () => {return selectedSubjects};

export function addSubjectFn (e) {
    e.preventDefault()
    let id, subject, code
    id = $('#search-input').val()
    subject = subjects.filter(function (element) {
        return element.sub_code == id
    })
    
    if (subject.length == 1) {
        subject = subject[0]
        code = subject.sub_code
        if (selectedSubjects.includes(code)) return showToast('warning', 'Subject already added')
        selectedSubjects.push(code)
        $('#emptyMsg').addClass('d-none')
        $('table').find('tbody').append(`<tr class='text-center'>
            <td class='cb-con' scope='col'><input type='checkbox' value='${code}' /></td>
            <td scope='col'><input type='hidden' name='subjects[]' value='${code}'/>${code}</td>
            <td scope='col'>${subject.sub_name}</td>
            <td scope='col'>${subject.sub_type}</td>
            <td scope='col'>
                <button data-value='${code}' class='remove-btn btn btn-sm btn-danger m-auto shadow-sm' title='Delete subject'><i class='bi bi-x-square'></i></button>
                <a href='subject.php?sub_code=${code}&state=view' role='button' class='view-btn btn btn-sm btn-primary m-auto shadow-sm d-none' title='View subject'><i class='bi bi-eye'></i></a>
            </td>
        </tr>`)
    } else {
        showToast('warning', 'Code did not match')
    }
}

export function removeAllBtnFn (e) {
    e.preventDefault()
    let selected = $("tbody input[type=checkbox]:checked")
    $("#selectAll").prop('checked', false)
    if (selected.length == 0) {
        showToast('warning', 'No subject is selected')
    } else {
        selected.each(function() {
            const element = $(this)
            const id = element.val()
            element.closest("tr").remove()
            removeFromSelectedSubject(id)
        })
        if (selectedSubjects.length == 0) $('#emptyMsg').removeClass('d-none')
    }
}

export function removeSubjectBtnFn (e){
    e.preventDefault()
    let element = $(this)
    let id = element.attr('data-value')
    element.closest("tr").remove()
    removeFromSelectedSubject(id)
    if (selectedSubjects.length == 0) $('#emptyMsg').removeClass('d-none')
}

export function selectAll () {
    $(this).prop('checked', this.checked)
    var table= this.closest('table')
    $('td input:checkbox', table).prop('checked', this.checked)
}
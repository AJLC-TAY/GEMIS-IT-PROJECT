preload('#faculty')
// Deleted roles
let rolesDel = []
let rolesTmp = []

// Department
let inputData
let setup = {
    data : subjects,
    uniqueId : "sub_code",
    idField : "sub_code",
    search : true,
    searchSelector : '#search-input',
    pageSize : "10",
    paginationParts : ["pageInfoShort", "pageSize", "pageList"],
    pagination : true,
    pageList : "[10, 25, 50, All]",
    onPostBody : () => {$("#subject-table").bootstrapTable("checkBy",  
                     {field: 'sub_code', values: assigned})}
}

let subjectTable = $("#subject-table").bootstrapTable(setup)

$(function() {

    /** Tab pane initialization */
    var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
        triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)

        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
    

    /** Role Methods */
    // If rolesTmp is 0, empty message is shown, else hidden
    let checkRolesTagForMsg = () => {
        let emptyMsg = $("#role-empty-msg")
        if (rolesTmp.length == 0) emptyMsg.removeClass('d-none')
        else emptyMsg.addClass('d-none')
    }

    // Edit roles
    $("#role-edit-btn").click(function(e) {
        e.preventDefault()
        // hide edit button
        $(this).addClass('d-none') 

        rolesTmp = [...roles] // clone current roles to roles temp
        rolesDel = []         // initialize roles to delete

        // show 
        $("#role-section").addClass("border")
        $(".role-to-delete-btn button, #role-option-tag-con, #role-decide-con").removeClass('d-none')
        checkRolesTagForMsg()
    })

    // Add role tag
    $("#role-option-tag-con button").click(function() {
        let element = $(this)
        element.addClass("d-none")

        let value = element.attr('data-value')
        rolesTmp.push(value)                                                // value is pushed to temporary roles
        $(`.role-to-delete-btn[data-value=${value}]`).removeClass('d-none') // icon inside delete con is shown
        checkRolesTagForMsg()

        // console.log("*****Add clicked******")
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
    })

    // delete role tag
    $(".role-to-delete-btn button").click(function () {
        let element = $(this).closest("div")
        element.addClass('d-none')

        let value = element.attr('data-value')
        if (roles.includes(value)) rolesDel.push(value)         // if the removed role tag exist in 
                                                                // the current row, add it to roles to delete array
        rolesTmp.splice(rolesTmp.indexOf(value), 1)             // then remove it from the temporary roles
       
        checkRolesTagForMsg()
        $(`#role-option-tag-con [data-value=${value}]`).removeClass('d-none')   // show add role tag button

        // console.log("*****Delete clicked******")
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
       
    })

    // cancel role edit
    $("#role-cancel-btn").click(function(e) {
        e.preventDefault()
        // hide
        $("#role-section").removeClass("border")
        $("#role-option-tag-con, #role-decide-con").addClass('d-none')

        // show
        $("#role-edit-btn").removeClass("d-none")
        
        // for each element in the current roles, their corresponding 
        // tag will be shown but their delete icon is hidden
        roles.forEach(e => {
            let eHTML = $(`.role-to-delete-btn[data-value=${e}]`)
            eHTML.find("button").addClass('d-none')
            eHTML.removeClass('d-none')
        })

        // for each element in the roles to be delete, their add button tag are hidden
        rolesDel.forEach(e => {
            let eHTML = $(`#role-option-tag-con [data-value=${e}]`)
            eHTML.addClass('d-none')
        })

        // temporary arrays are set to empty
        rolesDel = []
        rolesTmp = []

        // console.log("*****Cancel clicked******")
        // console.log("Temp Roles:", roles)
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
    })

    $("#role-save-btn").click(() => $("#role-form").submit())

    $("#role-form").submit(function(e) {
        e.preventDefault()
        showSpinner()
        var formData = $(this).serialize()

        // add each element in the temporary roles to the formdata
        rolesTmp.forEach(role => {
            formData += encodeURI(`&access[]=${role}`)
        })

        $.post('action.php', formData, function(){
            // hide
            $("#role-section").removeClass("border")
            $("#role-option-tag-con, #role-decide-con").addClass('d-none')
            // the delete icon of tags corresponding to each of the 
            // element in the temporary roles are hidden 
            rolesTmp.forEach(e=> {
                let eHTML = $(`.role-to-delete-btn[data-value=${e}]`)
                eHTML.find("button").addClass('d-none')
            })

            // show
            $("#role-edit-btn").removeClass('d-none')
            checkRolesTagForMsg()

            // override roles by cloning the temprary roles
            roles = [...rolesTmp]
            hideSpinner()
            showToast('success', "Roles successfully updated")
        })
    })

    /** Deparment Methods */
    $("#dept-edit-btn").click(function() {
        let input = $("#dept-input")
        // Get input value and store it to the input data
        inputData = input.val()
        // empty input if no department is set
        if (!deptExist) input.val("")
        
        // show
        $("#dept-section").addClass("border")
        $("#dept-decide-con, #dept-clear-btn, .dept-ins").removeClass('d-none')
        input.removeClass('d-none')
        input.prop('readonly', false)
        
        // hide
        $(this, "#dept-empty-msg").addClass('d-none')
    })

    $("#dept-clear-btn").click(function(e) {
        e.preventDefault()
        $("#dept-input").val('')
    })

    $("#dept-cancel-btn").click(function(e) {
        e.preventDefault()
        
        let input = $("#dept-input")
        input.attr('readonly', true)
        input.val(inputData)

        // hide
        $("#dept-section").removeClass("border")
        $("#dept-decide-con, #dept-clear-btn, .dept-ins").toggleClass('d-none')
        
        // show
        $("#dept-edit-btn").removeClass("d-none")
        $("#dept-empty-msg").toggleClass('d-none')
    })


    $("#dept-save-btn").click(() => $("#dept-form").submit())
    $("#dept-form").submit(function(e) {
        e.preventDefault()
        showSpinner()

        $.post("action.php?", $(this).serialize(), function() {
            // hide
            $("#dept-section").removeClass("border")
            $("#dept-decide-con, #dept-clear-btn, .dept-ins").toggleClass('d-none')

            let input = $("#dept-input")
            input.attr('readonly', true)
            // empty input if no department is set
            if (input.val().length == 0) {
                input.val("No department set")
                deptExist = false 
            } else deptExist = true
            
            // show
            $("#dept-edit-btn, #dept-empty-msg").toggleClass('d-none')
            
            hideSpinner()
            showToast('success', "Department successfully updated")
        })
    })
    /** Department methods end */

    /** Subject methods */

    const addEmptySubjectMsg = () => $("#as-table tbody").html("<tr id='emptyMsg' class='text-center'><td colspan='5'>No subject set</td></tr>")
    const hideEditSubjectElements = () => $(`.edit-con, .finder-con, .decision-as-con, .remove-btn, .view-btn, .cb-con, #as-table thead tr th:first-child`).toggleClass("d-none")

    $(".edit-as-btn, #edit-as-btn").click(function() {
        // show
        subjectTable.bootstrapTable('checkBy', {field: 'sub_code', values: assigned})
    })

    $("#cancel-as-btn").click(() => {
        subjectTable.bootstrapTable("uncheckAll")
    })
    // $("#save-as-btn").click(() => $("#as-form").submit())
    $("#as-form").submit(function(e) {
        e.preventDefault()
        showSpinner()
        let form = $(this)
        let formData = form.serializeArray()
        let selection = subjectTable.bootstrapTable("getSelections")
        let newSubCodes = selection.map(e => {return e.sub_code})
        let newSubjects = newSubCodes.map(value => {return {name: "subjects[]", value}})
        formData = [...formData, ...newSubjects]
        $.post("action.php", formData, function() {
            assigned = newSubCodes
            console.log(assigned.length)
            let emptyMsg = $("#empty-as-msg")
            let emptySubjectCon = () => $(".assigned-sub-con a").remove()
            if (assigned.length == 0) {
                emptyMsg.removeClass("d-none")
                emptySubjectCon()
            }
            else {
                emptyMsg.addClass("d-none")
                emptySubjectCon()
                selection.forEach(e => {
                    $(".assigned-sub-con").append(`<a target='_blank' href='subject.php?sub_code=${e.sub_code}' class='list-group-item list-group-item-action' aria-current='true'>
                                            <div class='d-flex w-100 justify-content-between'>
                                                <p class='mb-1'>${e.sub_name}</p>
                                                <small>${e.sub_type}</small>
                                            </div>
                                            <small class='mb-1 text-secondary'><b>${e.for_grd_level}</b> | ${e.sub_code}</small>
                                        </a>`)
                })
            }
            form.trigger("reset")
            $("#as-modal").modal("hide")
            hideSpinner()
            showToast('success', "Handled subjects successfully updated")
        })
    })
                             
      /** Advisory Methods */

      $("#advisory-change-btn").click(() => $("#advisory-modal").modal("show"))

      // $("input[type='radio'][name='section']").click(function() {
      //     let element = $(this)
      //     let sectionCode = element.val()
      // })
  
      const reloadSectionSelection = data => {
          let html = "", container = $("#section-list")
          data.forEach(e => {
              const sectionCd = e.section_code
              const teacherID = e.adviser_id;
              const sectionNm = e.section_name;
              const sectionGr = e.section_grd;
              const sectionAd = e.adviser_name;
              const colorBadge = teacherID ? "warning" : "success";
              html += ` <li class='list-group-item'>
                      <div class='form-row row'>
                          <span class='col-1'><input class='form-check-input me-1' data-current-adviser='${teacherID ?? ""}' name='section' type='radio' value='${sectionCd}'></span>
                          <div class='section-info d-flex justify-content-between col-sm-6'>
                              <span>${sectionCd} - ${sectionNm} </span> 
                              <span class='text-secondary'>G${sectionGr}</span>
                          </div>
                          <div class='section-status d-flex justify-content-between col-sm-5'>
                              <div class='teacher-con' title='Current class adviser'>${sectionAd}</div>
                              <span class='badge available'><div class='bg-${colorBadge} rounded-circle' style='width: 10px; height: 10px;'></div></span>
                          </div>
                      </div>
                  </li>`
          })
          container.html(html)
      }
  
      $("#advisory-form").submit(function(e) {
          e.preventDefault()
          showSpinner()
          let form = $(this)
          let formData = form.serializeArray()
  
          let currentAdviser = $("#advisory-form [type='radio']:checked").attr("data-current-adviser")
          if (currentAdviser) formData.push({name : "current-adviser", value : currentAdviser})
          $.post("action.php", formData, function(data) {
              let sectionData = JSON.parse(data)
              form.trigger("reset")
            
              let currentSectValue = sectionData.section_code
              let sectionDetail = `${currentSectValue} - ${sectionData.section_name}`
              
              // toggle editable state of the unassign checkbox
              let cbEditable = false
              if (!sectionData.section_code) {
                  cbEditable = true
                  currentSectValue = ""
                  sectionDetail = sectionData.section_name
              }
              $("#current-advisory").html(`${sectionDetail}`)
              $("input[name='current-section']").val(currentSectValue)
              $("input[name='unassign']").prop("disabled", cbEditable)
  
              reloadSectionSelection(sectionData.data)
              $("#section-opt-con input, #section-filter").prop("disabled", false)
              $("#advisory-modal").modal("hide")
              hideSpinner()
              showToast("success", "Successfully updated advisory class")
          })
          // console.log($("input[type='radio']:checked"))
      })
  
      // Disable all section input when unassign checkbox is checked
      $(document).on("click", "input[name='unassign']", function() {
          let bool = false
          if ($(this).is(":checked")) {
              bool = true
              $("#section-opt-con [type='radio']").prop("checked", false)
          }
          $("#section-opt-con input, #section-filter").prop("disabled", bool)
      })
  
    /***
     *  Adds search feature to the specified input inorder to filter 
     *  the list of elements referred by the given selector. 
     * @param {String} searchInputID    Search input selector
     * @param {String} itemSelector     Item selector of the list 
     * */
    const listSearchEventBinder = (searchInputID, itemSelector) => {
        $(document).on("keyup", searchInputID, function() {
            var value = $(this).val().toLowerCase()
            $(itemSelector).filter(function() {
                if ($(this).text().toLowerCase().indexOf(value) > -1) return $(this).removeClass("d-none")
                return $(this).addClass("d-none")
                // $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            })
        })
    }

    listSearchEventBinder("#search-section", "#section-list li")
    listSearchEventBinder("#search-subject", ".assigned-sub-con a")
  
    $(document).on("click", "#all-section-btn", function() {
        $("#section-list li").removeClass("d-none")
    })
  
    const filterSection = (parameter) => {
        $("#section-list li").filter(function() {
            if ($(this).find("span").hasClass(parameter)) return $(this).removeClass("d-none")
            return $(this).addClass("d-none")
        })
    }

    $("#no-adv-btn").click(function(e) {
        e.preventDefault()
        filterSection("available")
    })

    $("#with-adv-btn").click(function(e) {
        e.preventDefault()
        filterSection("unavailable")
    })




    hideSpinner()
})
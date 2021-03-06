const REQUIRED = "<p class='text-danger'><small>This field is required</small></p>";

function validate(selector) {
  var file = $(selector)[0].files[0];
  var file_size = file.size;
  var file_name = file.name;
  var ext = file_name.split(".");
  ext = ext[ext.length-1].toLowerCase();
  var arrayExtensions = ["jpg", "jpeg", "png"];

  var message = '';
  if(file_size > 5242880) {
    message += "Image should not exceed 5MB file size. ";
  }

  if (arrayExtensions.lastIndexOf(ext) == -1) {
    message += "Allowed image types are jpg, jpeg, png.";
  }

  if (message.length > 1) {
    showToast("danger", message);
    $(selector).val('');
  }
}

var stepper, formEnroll, enrollValidator, syValidator;
/** Enrollment */
try {
  formEnroll =  $("#enrollment-form");
  stepper = new Stepper($('#stepper')[0]);
} catch (e) {}
/** School year */
try {
  form = $("#school-year-form");
  stepper = new Stepper($('#school-year-stepper')[0]);
} catch (e) {}

try {
  stepper = new Stepper($('#stepper')[0]);
} catch (e) {}


const REQUIRED_TRUE = {required: true};
const REQUIRED_AE_PARAM = {
  required: true,
  min: 60,
  max: 100
}
$(function () {
  $.validator.setDefaults({
    errorClass: 'help-block',
    highlight: function (element) {
      $(element)
        .closest('.form-group')
        .addClass('has-error');
    },
    unhighlight: function (element) {
      $(element)
        .closest('.form-group')
        .removeClass('has-error');
    },
    errorPlacement: function (error, element) {
      if (element.prop('type') === 'checkbox') {
        error.insertAfter(element.parent());
      } else {
        error.insertAfter(element);
      }
    }
  });

  $.validator.addMethod('strongPassword', function (value, element) {
    return this.optional(element)
      || value.length >= 6
      && /\d/.test(value)
      && /[a-z]/i.test(value);
  }, 'Your password must be at least 6 characters long and contain at least one number and one char\'.')
//implementing validation
  $("#admin-form").on("submit", function(event){
    event.preventDefault();
  }).validate({
    rules: {
      lastname: {
        required: true,
        lettersonly: true
      },
      firstname: {
        required: true,
        lettersonly: true
      },
      age: {
        required: true,
        max: 99
      },
      email: {
        required: true,
        email: true,
      },
    },
    messages: {
      lastname: {
        required: '<p class="text-danger user-select-none"><small>Please enter last name!</small></p>',
        lettersonly: '<p class="text-danger user-select-none"><small>Last name is letters only!</small></p>'
      },
      firstname: {
        required: '<p class="text-danger user-select-none"><small>Please enter first name!</small></p>',
        lettersonly: '<p class="text-danger user-select-none"><small>First name is letters only!</small></p>'
      },
      age: {
        required: '<p class="text-danger user-select-none"><small>Please enter age!</small></p>',
        max: '<p class="text-danger user-select-none"><small>Age is too high!</small></p>'
      },
      email: {
        required: '<p class="text-danger user-select-none"><small>Please enter an email address</small>small!</p>',
        email: '<p class="text-danger user-select-none"><small>Please enter a <em>valid</em> email address!</small></p>'
      }
    },
    submitHandler: function(form) { 
      form.submit();
      return false;  //This doesn't prevent the form from submitting.
    }
  });

  $("#school-year-form").on("submit", function(e) {
    e.preventDefault();
  }).validate({
      rules: {
        "start-year": {
          required: true,
          remote: {
            url: `getAction.php?data=school_years&type=start`,
            type: "post",
            data: {
              "start-year": function () {
                return $("[name='start-year']").val();
              }
            }
          }
        },
        "end-year": {
          required: true,
          remote: {
            url: `getAction.php?data=school_years&type=end`,
            type: "post",
            data: {
              "end-year": function () {
                return $("[name='end-year']").val();
              }
            }
          }
        },
        "start-month": {
          required: true
        },
        "end-month": {
          required: true
        }, 
        "default-days": {
          required: true
        }
      },
      messages: {
        "start-year": {
          remote: "<p>Already used start year</p>"
        },
        "end-year": {
          remote: "<p>Already used end year</p>"
        }
      },
      submitHandler: function(form) { 
        let formData = new FormData(form);
        $.ajax({
          url: "action.php", 
          data: formData,
          method: "post",
          processData: false,
          contentType: false,
          success: function(data) {
            let url = JSON.parse(data);
            let message = 'Redirecting to the initialized school year';
            console.log(url);
            showToast('dark', message, { delay: 3000 });
            location.replace(url);
          }
        });
        return false;  //This doesn't prevent the form from submitting.
      }, 
  });

  $(document).on("click", "#enroll-part-1", function(e) {
    e.preventDefault();

    enrollValidator = formEnroll.validate({
      rules: {
        lrn: { required: true },
        "last-name": { required: true },
        "first-name": { required: true },
        "cp-no": { required: true },
        sex: { required: true },
        birthdate: { required: true },
        "birth-place": { required: true },
        age: { required: true },
        group: { required: true },
        "group-name": { required: true },
        "mother-tongue": { required: true },
        "house-no": { required: true },
        street: { required: true },
        barangay: { required: true },
        "city-muni": { required: true },
        province: { required: true },
        "zip-code": { required: true }
      },
      errorPlacement: function (error, element) {
        if (element.attr("name") == "sex") {
          error.appendTo("#sex-error-con");
        } else if (element.attr("name") == "group") {
          error.appendTo("#group-error-con");
        } else {
          error.insertAfter(element)
        }
      },
      messages: {
        lrn: { required: "<p class='text-danger'><small>Please provide your LRN</small></p>" },
        "last-name": { required: "<p class='text-danger'><small>Please provide your last name</small></p>" },
        "first-name": { required: "<p class='text-danger'><small>Please provide your first name</small></p>" },
        "cp-no": {
          required: "<p class='text-danger'><small>Please provide your contact number</small></p>",
        },
        sex: { required: "<p class='text-danger'><small>This is a required field</small></p>" },
        birthdate: { required: "<p class='text-danger'><small>Please provide your birthdate</small></p>" },
        "birth-place": { required: "<p class='text-danger'><small>Please provide your birth place</small></p>" },
        age: { required: "<p class='text-danger'><small>Please provide your age</small></p>" },
        group: { required: "<p class='text-danger'><small>Please provide your indigenous group</small></p>" },
        "group-name": { required: "<p class='text-danger'><small>Please provide group name</small></p>" },
        "mother-tongue": { required: "<p class='text-danger'><small>Please provide your mother tongue</small></p>" },
        "house-no": { required:  REQUIRED },
        street: { required: REQUIRED },
        barangay: { required: REQUIRED },
        "city-muni": { required: REQUIRED },
        province: { required: REQUIRED },
        "zip-code": { required: REQUIRED }
      },
    });
    if (formEnroll.valid()) {
      stepper.next();
      enrollValidator.destroy();
    }
  });

  $(document).on("click", "#enroll-part-2", function(e) {
    e.preventDefault();
    stepper.next();
  });
  $(document).on("click", "#enroll-part-3", function(e) {
    e.preventDefault();
    formEnroll.validate({
      rules: {
         "track" : { required: true},
         "program" : { required: true},
         "grade-level" : { required: true},
         "semester" : { required: true}
      },
      messages: {
        "track": { required: REQUIRED },
        "program": { required: REQUIRED },
        "grade-level": { required: REQUIRED },
        "semester": { required: REQUIRED }
      }
    });
    if (formEnroll.valid()) {
      stepper.next();
    }
  });

  $(document).on("click", ".previous-enroll", function(e) {
    e.preventDefault();
    stepper.previous();
    enrollValidator.destroy();
  });

  $(document).on("click", ".previous-sy", function(e) {
    e.preventDefault();
    stepper.previous();
    syValidator.destroy();
  });

  //unique rule, submit done, for implementation and testing
  $("#section-form").on("submit", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      'section-name': {
        required: true,
      }
    },
    messages: {
      'section-name': {
        required: '<p class="text-danger user-select-none">Please enter program code!</p>'
      }
    },
    submitHandler: function(form) { 
      form.submit();
      return false;  //This doesn't prevent the form from submitting.
    }
  })
  $("#enroll-report-form").on("submit", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      code: {
        required: true,
      },
      desc: {
        required: true
      },
      'section-name': {
        required: true,
      }
    },
    messages: {
      code: {
        required: '<p class="text-danger user-select-none">Please enter program code!</p>'
      },
      desc: {
        required: '<p class="text-danger user-select-none">Please enter program name!</p>'
      },
      'section-name': {
        required: '<p class="text-danger user-select-none">Please enter program code!</p>'
      }
    },
    submitHandler: function(form) { 
      form.submit();
      return false;  //This doesn't prevent the form from submitting.
    }
  })
  //unique rule, submit done, for implementation
  $("#program-view-form").on("submit", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      code: {
        required: true,
      }
    },
    messages: {
      code: {
        required: '<p class="text-danger user-select-none">Please enter current code!</p>'
      }
    },
    submitHandler: function(form) { 
      form.submit();
      return false;  //This doesn't prevent the form from submitting.
    }
  })
  //submit done, for implementation
  $("#faculty-form").on("submit", function(event) {
    event.preventDefault();
  }).validate({
    rules:{
      lastname:{
        required: true,
      },
      firstname:{
        required: true,
      },
      email: {
        required: true,
        email: true
      },
      age: {
        required: true,
      },
      sex: {
        required: true
      },
    },
    errorPlacement: function (error, element) {
      if (element.attr("name") == "sex") {
        error.appendTo("#sex-error-con");
      } else {
        error.insertAfter(element)
      }
    },
    messages:{
      lastname: {
        required:'<p class="text-danger user-select-none">Please enter last name!</p>',
        lettersonly:'<p class="text-danger user-select-none">Please enter letters only!</p>'
      },
      firstname:{
        required:'<p class="text-danger user-select-none">Please enter first name!</p>',
        lettersonly:'<p class="text-danger user-select-none">Please enter letters only!</p>'
      },
      email:{
        required:'<p class="text-danger user-select-none">Please enter email!</p>',
        email:'<p class="text-danger user-select-none">Please enter a valid email!</p>'
      },
      age:{
        required: '<p class="text-danger user-select-none">Please enter age!</p>'
      },
      sex:{
        required: REQUIRED
      }
    },
    submitHandler: function(form) {
      let formData = new FormData(form);
      try {
        $("#assigned-sc-table").bootstrapTable("getData")
            .forEach(e => {
              formData.append("asgn-sub-class[]",  e.sub_class_code);
            });

        $("#subject-table").bootstrapTable("getSelections")
            .forEach(e => {
              formData.append("subjects[]", e.sub_code);
            });
      } catch (e) {}

      $.ajax({
        url: "action.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: data => {
          let response = JSON.parse(data);
          window.location.replace(`faculty.php?id=${response.teacher_id}`);
        }
      });
      return false;  //This doesn't prevent the form from submitting.
    }
  });

  try {
    $("#change-pass-form").validate({
      rules: {
        current: {
          required: true,
          remote: {
            url: `getAction.php?data=validatePassword&uid=${uid}`,
            type: "post",
            data: {
              current: function () {
                return $("[name='current']").val();
              }
            }
          }
        },
        new_password: {
          required: true,
          minlength: 8
        },
        "reenter-new-pass": {
          required: true,
          equalTo: "#new-pass"
        }
      },
      messages: {
        current: {
          required: REQUIRED,
          remote: "<p class='text-danger'><small>Incorrect password</small></p>"
        },
        new_password: {
          required: REQUIRED,
          minlength: "<p class='text-danger'><small>Please choose a password with at least 8 characters</small></p>"
        },
        "reenter-new-pass": {
          required: REQUIRED,
          equalTo: "<p class='text-danger'><small>Please enter the same value again</small></p>"
        }
      },
      submitHandler: function (form) {
        $.ajax({
          url: "action.php",
          data: new FormData(form),
          processData: false,
          contentType: false,
          type: "post",
          success: function () {
            $(form).trigger("reset");
            $("#change-pass-modal").modal("hide");
            showToast("success", "Password successfully changed");
          }
        })
      }
    });
  } catch (e) {}

  try {
    $("#reset-system-form").validate({
      rules: {
        current: {
          required: true,
          minlength: 8,
          remote: {
            url: `getAction.php?data=validatePassword&uid=${uid}`,
            type: "post",
            data: {
              current: function () {
                return $("[name='current']").val();
              }
            }
          }
        },
        "re-enter-pass": {
          required: true,
          equalTo: "#current"
        }
      },
      messages: {
        current: {
          required: REQUIRED,
          remote: "<p class='text-danger'><small>Incorrect password</small></p>"
        },
        "reenter-pass": {
          required: REQUIRED,
          equalTo: "<p class='text-danger'><small>Please enter the same value again</small></p>"
        }
      },
      submitHandler: function (form) {
        form.submit();
        return false;
      }
    });
  } catch(e) {}

  $("#acad-parameter-form").validate({
    rules: {
      semester: REQUIRED_TRUE,
      grade: REQUIRED_TRUE,
      "Highest-honor-min": REQUIRED_AE_PARAM,
      "Highest-honor-max": REQUIRED_AE_PARAM,
      "High-honor-min": REQUIRED_AE_PARAM,
      "High-honor-max": REQUIRED_AE_PARAM,
      "With-honor-min": REQUIRED_AE_PARAM,
      "With-honor-max": REQUIRED_AE_PARAM,
    },
    messages: {
      semester: {required: REQUIRED},
      grade: {required: REQUIRED},
      "Highest-honor-min": {required: REQUIRED},
      "Highest-honor-max": {required: REQUIRED},
      "High-honor-min": {required: REQUIRED},
      "High-honor-max": {required: REQUIRED},
      "With-honor-min": {required: REQUIRED},
      "With-honor-max": {required: REQUIRED},
    }
  });

  $("#other-award-form").validate({
    rules: {
      filter: REQUIRED_AE_PARAM,
    },
    messages: {
      filter:  {required: REQUIRED}
    }
  });

  $(".report-form").validate({
    rules: {
      "report-title": REQUIRED_TRUE,
      "school-year": REQUIRED_TRUE,
      date: REQUIRED_TRUE
    },
    messages: {
      "report-title": REQUIRED,
      "school-year": REQUIRED,
      date: REQUIRED
    }
  });

  /** Advisory Class */
  try {
    $("#advisory-class-form").validate();
    $("#grades").validate();
  } catch (e) {}
});



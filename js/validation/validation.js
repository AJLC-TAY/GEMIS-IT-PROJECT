const REQUIRED = "<p class='text-danger'><small>This field is required</small></p>";
try {
  var stepper, enrollValidator, form;
  form =  $("#enrollment-form");
  stepper = new Stepper($('#stepper')[0]);
} catch (e) {}

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

  // var codes = '<?php echo json_encode($currarray); ?>';
  // $.mockjax({
  //   url: "unique.action",
  //   response: function(settings) {
  //     var code = settings.data.code,
  //       this.responseText = "true";
  //     if ($.inArray(code, codes) !== -1) {
  //       this.responseText = "false";
  //     }
  //   },
  //   responseTime: 500
  // });
  $.validator.addMethod('strongPassword', function (value, element) {
    return this.optional(element)
      || value.length >= 6
      && /\d/.test(value)
      && /[a-z]/i.test(value);
  }, 'Your password must be at least 6 characters long and contain at least one number and one char\'.')
//implementing validation
  // done final, done implementation final
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

  


  //
  // $("#lrn").rules("add", {
  //   required: true,
  //   messages: {
  //     required: "<p class='text-danger'><small>Please provide your LRN</small></p>"
  //   }
  // })

  $(document).on("click", "#enroll-part-1", function(e) {
    e.preventDefault();

    enrollValidator = form.validate({
      rules: {
        lrn: { required: true },
        "last-name": { required: true },
        "first-name": { required: true },
        "cp-no": { required: true, maxlength: 11 , minlength: 11},
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
          maxlength: "<p class='text-danger'><small>Please provide a valid contact number</small></p>",
          minlength: "<p class='text-danger'><small>Please provide a valid contact number</small></p>"
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
    if (form.valid()) {
      stepper.next();
      enrollValidator.destroy();
    }
  });

  $(document).on("click", "#enroll-part-2", function(e) {
    e.preventDefault();
    form.validate({
      rules: {
        "f-lastname": { required: true  , lettersonly: true},
        "m-lastname": { required: true  , lettersonly: true},
        "g-lastname": { required: true  , lettersonly: true},
        "f-firstname": { required: true , lettersonly: true },
        "m-firstname": { required: true , lettersonly: true },
        "g-firstname": { required: true , lettersonly: true },
        "f-occupation": { required: true },
        "m-occupation": { required: true },
        "g-occupation": { required: true },
        "f-contactnumber": { required: true },
        "m-contactnumber": { required: true },
        "g-contactnumber": { required: true },
        "g-relationship": { required: true }
      },
      messages: {
        "f-lastname": { required:  REQUIRED },
        "m-lastname": { required: REQUIRED },
        "g-lastname": { required: REQUIRED },
        "f-firstname": { required: REQUIRED },
        "m-firstname": { required: REQUIRED },
        "g-firstname": { required: REQUIRED },
        "f-occupation": { required: REQUIRED },
        "m-occupation": { required: REQUIRED },
        "g-occupation": { required: REQUIRED },
        "f-contactnumber": { required: REQUIRED },
        "m-contactnumber": { required: REQUIRED },
        "g-contactnumber": { required: REQUIRED },
        "g-relationship": { required: REQUIRED }
      }
    });
    if (form.valid()) {
      stepper.next();
    }
  });
  $(document).on("click", "#enroll-part-3", function(e) {
    e.preventDefault();
    form.validate({
      rules: {
         "track" : { required: true},
         "program" : { required: true},
         "grade-level" : { required: true},
         "semester" : { required: true},
      },
      messages: {
        "track": { required: REQUIRED },
        "program": { required: REQUIRED },
        "grade-level": { required: REQUIRED },
        "semester": { required: REQUIRED },
      }
    });
    if (form.valid()) {
      stepper.next();
    }
  });

  $(document).on("click", ".previous", function(e) {
    e.preventDefault();
    stepper.previous();
    enrollValidator.destroy();
  });



  //unique rule, submit done, for implementation and testing
  $("#section-form").on("submit", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      'section-name': {
        required: true,
        // unique
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
  //submit done, for implementation
  // $("#program-form").on("submit", function(event) {
  //   event.preventDefault();
  // }).validate({
  //   rules: {
  //     'prog-code': {
  //       required: true,
  //     },
  //     'prog-name':{
  //       required: true,
  //       lettersonly: true
  //     },
  //     'prog-desc':{
  //       required: true,
  //       lettersonly: true
  //     }
  //   },
  //   messages: {
  //     'prog-code': {
  //       required: '<p class="text-danger user-select-none">Please enter code!</p>'
  //     },
  //   },
  //   submitHandler: function(form) {
  //     form.submit();
  //     return false;  //This doesn't prevent the form from submitting.
  //   }
  // })
  // unique rule, submit done, for implementation
  $("#enroll-report-form").on("submit", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      code: {
        required: true,
        // unique
      },
      desc: {
        required: true
      },
      'section-name': {
        required: true,
        // unique
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
        // unique
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
        lettersonly: true
      },
      firstname:{
        required: true,
        lettersonly: true
      },
      email: {
        required: true,
        email: true
      },
      age: {
        required: true,
      },
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
  //submit done, for implementation
  $("#student-form").on("submit", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      "g_lastname": {
        required: true,
        lettersonly: true
      },
      'g_firstname': {
        required: true,
        lettersonly: true
      },
      'g_middlename': {
        required: true,
        lettersonly: true
      },
    },
    messages: {
      "g_lastname": {
        required: '<p class="text-danger user-select-none">Please enter guardians last name!</p>',
        lettersonly: '<p class="text-danger user-select-none">Please enter letters only!</p>',
      },
      'g_firstname': {
        required: "<p class='text-danger user-select-none'>Please enter guardian's first name!</p>",
        lettersonly: '<p class="text-danger user-select-none">Please enter letters only!</p>',
      },
      'g_middlename': {
        required: "<p class='text-danger user-select-none'>Please enter guardian's middle name!</p>",
        lettersonly: '<p class="text-danger user-select-none">Please enter letters only!</p>',
      },
      submitHandler: function(form) { 
        form.submit();
        return false;  //This doesn't prevent the form from submitting.
      }
    }
  })
  //for questioning
  //program view form for improvement
  // $("#school-year-form").validate({
  //   rules: {
  //     code: {
  //       required: true,
  //       // similar validation
  //     }
  //   },
  //   messages: {
  //     code: {
  //       required: '<p class="text-danger user-select-none">Please enter current code!</p>'
  //     }
  //   }
  // });
})



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
      middlename: {
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
        required: '<p class="text-danger user-select-none">Please enter last name!</p>',
        lettersonly: '<p class="text-danger user-select-none">Last name is letters only!</p>'
      },
      firstname: {
        required: '<p class="text-danger user-select-none">Please enter first name!</p>',
        lettersonly: '<p class="text-danger user-select-none">First name is letters only!</p>'
      },
      middlename: {
        required: '<p class="text-danger user-select-none">Please enter last name!</p>',
        lettersonly: '<p class="text-danger user-select-none">Middle name is letters only!</p>'
      },
      age: {
        required: '<p class="text-danger user-select-none">Please enter age!</p>',
        max: '<p class="text-danger user-select-none">Age is too high!</p>'
      },
      email: {
        required: '<p class="text-danger user-select-none">Please enter an email address!</p>',
        email: '<p class="text-danger user-select-none">Please enter a <em>valid</em> email address!</p>'
      }
    },
    submitHandler: function(form) { 
      form.submit();
      return false;  //This doesn't prevent the form from submitting.
    }
  })

  $("#enroll-form-1").on("click", function(e) {
    e.preventDefault();
    alert("alert");
    // var stepper = new Stepper($('#stepper')[0]);
    // stepper.next();
  });

  //unique rule, submit done, for implementation 
  $(document).on("submit", "#prog-form", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      code: {
        required: true,
        // unique
      },
      desc: {
        required: true
      }
    },
    messages: {
      code: {
        required: '<p class="text-danger user-select-none">Please enter program code!</p>'
      },
      desc: {
        required: '<p class="text-danger user-select-none">Please enter program name!</p>',
        remote: $.validator.format("{0} is already associated with an account.")
      }
    },
    submitHandler: function(form) { 
      form.submit();
      return false;  //This doesn't prevent the form from submitting.
    }
  })
  //submit done, for implementation
  $("#stepper").on('click', function(){
  $("#enrollment-form").on("stepper", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      'f-lastname': {
        required: true,
        lettersonly: true
      },
      'f-firstname': {
        required: true,
        lettersonly: true
      },
      'f-middlename': {
        required: true,
        lettersonly: true
      },
      'm-lastname': {
        required: true,
        lettersonly: true
      },
      'm-firstname': {
        required: true,
        lettersonly: true
      },
      'm-middlename': {
        required: true,
        lettersonly: true
      },
      'g-lastname': {
        required: true,
        lettersonly: true
      },
      'g-firstname': {
        required: true,
        lettersonly: true
      },
      'g-middlename': {
        required: true,
        lettersonly: true
      }
    },
    messages: {
      'f-lastname': {
        required: "<p class='text-danger user-select-none'>Please enter father's last name!</p>",
        lettersonly: "<p class='text-danger user-select-none'>Please enter letters only!</p>",
      },
      'f-firstname': {
        required: "<p class='text-danger user-select-none'>Please enter father's first name!</p>",
        lettersonly: "<p class='text-danger user-select-none'>Please enter letters only!</p>",
      },
      'f-middlename': {
        required: "<p class='text-danger user-select-none'>Please enter father's middle name!</p>",
        lettersonly: "<p class='text-danger user-select-none'>Please enter letters only!</p>",
      },
      'm-lastname': {
        required: "<p class='text-danger user-select-none'>Please enter mother's last name!</p>",
        lettersonly: "<p class='text-danger user-select-none'>Please enter letters only!</p>",
      },
      'm-firstname': {
        required: "<p class='text-danger user-select-none'>Please enter mother's first name!</p>",
        lettersonly: "<p class='text-danger user-select-none'>Please enter letters only!</p>",
      },
      'm-middlename': {
        required: "<p class='text-danger user-select-none'>Please enter mother's middle name!</p>",
        lettersonly: "<p class='text-danger user-select-none'>Please enter letters only!</p>",
      },
      'g-lastname': {
        required: "<p class='text-danger user-select-none'>Please enter guardian's last name!</p>",
        lettersonly: "<p class='text-danger user-select-none'>Please enter letters only!</p>",
      },
      'g-firstname': {
        required: "<p class='text-danger user-select-none'>Please enter guardian's first name!</p>",
        lettersonly: "<p class='text-danger user-select-none'>Please enter letters only!</p>",
      },
      'g-middlename': {
        required: "<p class='text-danger user-select-none'>Please enter guardian's middle name!</p>",
        lettersonly: "<p class='text-danger user-select-none'>Please enter letters only!</p>",
      }
    },
    submitHandler: function(form) { 
      form.submit();
      return false;  //This doesn't prevent the form from submitting.
    }
  })})
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
  $("#program-form").on("submit", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      'prog-code': {
        required: true,
      },
      'prog-name':{
        required: true,
        lettersonly: true
      },
      'prog-desc':{
        required: true,
        lettersonly: true
      }
    },
    messages: {
      'prog-code': {
        required: '<p class="text-danger user-select-none">Please enter code!</p>'
      },
    },
    submitHandler: function(form) { 
      form.submit();
      return false;  //This doesn't prevent the form from submitting.
    }
  })
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
      middlename:{
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
      middlename:{
        required:'<p class="text-danger user-select-none">Please enter middle name!</p>',
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
          console.log(response)
          window.location.replace(`faculty.php?id=${response.teacher_id}`);
        }
      });
      return false;  //This doesn't prevent the form from submitting.
    }
  })
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
  //submit done, unique rule left, for implementation
  $(document).on("submit","#curriculum-form", function(event) {
    event.preventDefault();
  }).validate({
    rules: {
      code: {
        required: true,
        //remote: "unique.action"
      },
      name: {
        required: true
      }
    },
    messages: {
      code: {
        required: '<p class="text-danger user-select-none">Please enter curriculum code!</p>',
        //remote: '<p class="text-danger user-select-none">Code is already taken, please enter another code.</p>'
      },
      name: {
        required: '<p class="text-danger user-select-none">Please enter curriculum name!</p>'
      }
    },
    submitHandler: function(form) { 
      // form.submit();
      // $("#curriculum-form").submit(function(e) {
      //     e.preventDefault();
          // $.post("action.php", form.serializeArray(), (data) => {
          //     window.location.href = `curriculum.php?code=${JSON.parse(data)}`;
          // });
      // });

      // $('#curriculum-form').submit(function(e) {
        // e.preventDefault();
        showSpinner();
        // var form = $(this);
        // var formData = $(this).serialize();
        var formData = form.serialize();
        $.post("action.php", formData, function(data) {
            form.trigger('reset');
            addModal.modal('hide');
            console.log("New data: \n");
            console.log(data);
            // reload(JSON.parse(data));
            hideSpinner();
            // $(".no-result-msg").hide();
            showToast('success', 'Curriculum successfully added');
        });
        // .fail(function () {

        // });
    // });
      console.log(form);
      return false;  //This doesn't prevent the form from submitting.
    }
  });

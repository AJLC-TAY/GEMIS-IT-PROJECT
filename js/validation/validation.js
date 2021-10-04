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

  $("#admin-form").validate({
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
        numbersonly: true,
        max: 2
      },
      email: {
        required: true,
        email: true,
        remote: "http://localhost:3000/inputValidator"
      },
    },
    messages: {
      lastname: {
        required: '<p class="text-danger user-select-none">Please enter last name!</p>',
        lettersonly: '<p class="text-danger user-select-none">Last name is letters only!</p>',
        minimum: '<p class="text-danger user-select-none">Please enter more than 2 characters!</p>'
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
        numbersonly: '<p class="text-danger user-select-none">Please  enter numbers only!</p>',
        max: '<p class="text-danger user-select-none">Age is too high!</p>'
      },
      email: {
        required: '<p class="text-danger user-select-none">Please enter an email address!</p>',
        email: '<p class="text-danger user-select-none">Please enter a <em>valid</em> email address!</p>',
        remote: $.validator.format("<p class='text-danger user-select-none'>{0} is already associated with an account!</p>")
      }
    }
  })
  $("#curriculum-form").validate({
    rules: {
      code: {
        required: true
      },
      name: {
        required: true
      }
    },
    messages: {
      code: {
        required: '<p class="text-danger user-select-none">Please enter curriculum code!</p>'
      },
      name: {
        required: '<p class="text-danger user-select-none">Please enter curriculum name!</p>'
      }
    }
  })

  $("#prog-form").validate({
    rules: {
      code: {
        required: true,
        // similar validation
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
    }
  })

  $("#enrollment-form").validate({
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
    }
  })
  $("#section-form").validate({
    rules: {
      'section-name': {
        required: true,
        // similar validation
      }
    },
    messages: {
      'section-name': {
        required: '<p class="text-danger user-select-none">Please enter program code!</p>'
      }
    }
  })
  // for questions
  $("#enroll-report-form").validate({
    rules: {
      'section-name': {
        required: true,
        // similar validation
      }
    },
    messages: {
      'section-name': {
        required: '<p class="text-danger user-select-none">Please enter program code!</p>'
      }
    }
  })

  $("#program-form").validate({
    rules: {
      birthdate: {
        required: true,
      },
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
      email:{
        required: true,
        email: true
      },
      age:{
        required: true,
        max: 2
      }
    },
    messages: {
      birthdate: {
        required: '<p class="text-danger user-select-none">Please select birthdate!</p>'
      },
      lastname:{
        required:'<p class="text-danger user-select-none">Please enter last name!</p>',
        lettersonly: '<p class="text-danger user-select-none">Please enter letters only!</p>'
      },
      firstname:{
        required:'<p class="text-danger user-select-none">Please enter first name!</p>',
        lettersonly: '<p class="text-danger user-select-none">Please enter letters only!</p>'
      },
      middlename:{
        required:'<p class="text-danger user-select-none">Please enter middle name!</p>',
        lettersonly: '<p class="text-danger user-select-none">Please enter letters only!</p>'
      },
      email:{
        required:'<p class="text-danger user-select-none">Please enter email!</p>',
        email: '<p class="text-danger user-select-none">Please enter valid email!</p>'
      },
      age:{
        required:'<p class="text-danger user-select-none">Please enter age!</p>',
        max: '<p class="text-danger user-select-none">Please enter up to two digits only!</p>'
      }
    }
  })
  $("#enroll-report-form").validate({
    rules: {
      code: {
        required: true,
        // similar validation
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
        required: '<p class="text-danger user-select-none">Please enter program name!</p>'
      }
    }
  })
  //program view form for improvement
  $("#program-view-form").validate({
    rules: {
      code: {
        required: true,
        // similar validation
      }
    },
    messages: {
      code: {
        required: '<p class="text-danger user-select-none">Please enter current code!</p>'
      }
    }
  })
  $("#faculty-form").validate({
    rules:{
      lastname:{
        required: true,
        lettersonly: true
      },
      age: {
        required: true,
        maximum: 2
      },
    },
    messages:{
      lastname: {
        required:'<p class="text-danger user-select-none">Please enter last name!</p>',
        lettersonly:'<p class="text-danger user-select-none">Please enter letters only!</p>'
      },
      age:{
        required: '<p class="text-danger user-select-none">Please enter age!</p>',
        maximum: '<p class="text-danger user-select-none">Please enter up to two digits only!</p>'
      }
    }
  })
  $("#student-form").validate({
    rules: {
      'g_lastname': {
        required: true,
        lettersonly: ture
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
      'g_lastname': {
        required: "<p class='text-danger user-select-none'>Please enter guardian's last name!</p>",
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
    }
  })
  //program view form for improvement
  $("#program-view-form").validate({
    rules: {
      code: {
        required: true,
        // similar validation
      }
    },
    messages: {
      code: {
        required: '<p class="text-danger user-select-none">Please enter current code!</p>'
      }
    }
  });
})
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
      firstname:{
        required: true,
        lettersonly: true
      },
      middlename:{
        required: true,
        lettersonly: true
      },
      age:{
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
        lettersonly:'<p class="text-danger user-select-none">Last name is letters only!</p>',
        minimum: '<p class="text-danger user-select-none">Please enter more than 2 characters!</p>'
      },
      firstname:{
        required:'<p class="text-danger user-select-none">Please enter first name!</p>',
        lettersonly:'<p class="text-danger user-select-none">First name is letters only!</p>'
      },
      middlename:{
        required:'<p class="text-danger user-select-none">Please enter last name!</p>',
        lettersonly:'<p class="text-danger user-select-none">Middle name is letters only!</p>'
      },
      age:{
        required: '<p class="text-danger user-select-none">Please enter age!</p>',
        numbersonly:'<p class="text-danger user-select-none">Please  enter numbers only!</p>',
        max: '<p class="text-danger user-select-none">Age is too high!</p>'
      },
      email: {
        required: '<p class="text-danger user-select-none">Please enter an email address!</p>',
        email: '<p class="text-danger user-select-none">Please enter a <em>valid</em> email address!</p>',
        remote: $.validator.format("<p class='text-danger user-select-none'>{0} is already associated with an account!</p>")
      }
    }
  });
  $("#curriculum-form").validate({
    rules: {
      // code: {
          
      // },
      name:{
        required: true
      }
    },
    messages:{
      name:{
        required:'<p class="text-danger user-select-none">Please enter curriculum name!</p>'
      }
    }
  })
 
  $("#test-form").validate({
    rules: {
      lastname: {
        required: true
      },
      email: {
        required: true,
        email: true,
        remote: "http://localhost:3000/inputValidator"
      },
      password: {
        required: true,
        strongPassword: true
      },
      password2: {
        required: true,
        equalTo: '#password'
      },
      firstName: {
        required: true,
        nowhitespace: true,
        lettersonly: true
      },
      secondName: {
        required: true,
        nowhitespace: true,
        lettersonly: true
      },
      businessName: {
        required: true
      },
      phone: {
        required: true,
        digits: true,
        phonesUK: true
      },
      address: {
        required: true,
        minimum: 10,
      },
      town: {
        required: true,
        lettersonly: true
      },
      postcode: {
        required: true,
        postcodeUK: true
      },
      terms: {
        required: true
      }
    },
    messages: {
      lastname: {
        required: 'Please enter last name'
      },
      email: {
        required: '<br>Please enter an email address.',
        email: '<br>Please enter a <em>valid</em> email address.',
        remote: $.validator.format("{0} is already associated with an account.")
      },
      address: {
        required: "Please enter address"
      }
    }
  });
});
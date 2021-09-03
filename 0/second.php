<html>

<head>
  <title>Isaiah's Test Site</title>
  <!-- needed -->
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <h2 style="text-align: center"> Validation Form using Bootstrap</h2>
  <br>
  <form class="form-horizontal" action=" " method="post" id="reg_form">
    <!-- Text input -->
    <div class="col-md-4 form-group">
      <label class="control-label">First Name</label>
      <div class="input-group">
        <input name="first_name" placeholder="First Name" class="form-control" type="text">
      </div>
    </div>

    <div class="form-group col-md-4">
      <label for='lastname' class="control-label">Last Name</label>
      <div class="input-group">
        <input type='text' value='<?php echo $lastname; ?>' class='form-control' id='lastname' name='lastname' placeholder='Last Name'>
      </div>
    </div>

    <!-- Button -->
    <div class="form-group">
      <label class="col-md-4 control-label"></label>
      <div class="col-md-4">
        <button type="submit" class="btn btn-warning">Send <span class="glyphicon glyphicon-send"></span></button>
      </div>
    </div>
  </form>

  <!-- PrefixFree -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#reg_form').bootstrapValidator({
          // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
          feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
          },
          fields: {
            first_name: {
              validators: {
                stringLength: {
                  min: 2,
                },
                notEmpty: {
                  message: 'Please supply your first name'
                }
              }
            },
            lastname: {
              validators: {
                stringLength: {
                  min: 2,
                },
                notEmpty: {
                  message: 'Please enter your last name'
                }
              }
            },
            phone: {
              validators: {
                notEmpty: {
                  message: 'Please supply your phone number'
                },
                phone: {
                  country: 'US',
                  message: 'Please supply a vaild phone number with area code'
                }
              }
            },
            address: {
              validators: {
                stringLength: {
                  min: 8,
                },
                notEmpty: {
                  message: 'Please supply your street address'
                }
              }
            },
            city: {
              validators: {
                stringLength: {
                  min: 4,
                },
                notEmpty: {
                  message: 'Please supply your city'
                }
              }
            },
            state: {
              validators: {
                notEmpty: {
                  message: 'Please select your state'
                }
              }
            },
            zip: {
              validators: {
                notEmpty: {
                  message: 'Please supply your zip code'
                },
                zipCode: {
                  country: 'US',
                  message: 'Please supply a vaild zip code'
                }
              }
            },
            comment: {
              validators: {
                stringLength: {
                  min: 10,
                  max: 200,
                  message: 'Please enter at least 10 characters and no more than 200'
                },
                notEmpty: {
                  message: 'Please supply a description about yourself'
                }
              }
            },
            email: {
              validators: {
                notEmpty: {
                  message: 'Please supply your email address'
                },
                emailAddress: {
                  message: 'Please supply a valid email address'
                }
              }
            },
            password: {
              validators: {
                identical: {
                  field: 'confirmPassword',
                  message: 'Confirm your password below - type same password please'
                }
              }
            },
            confirmPassword: {
              validators: {
                identical: {
                  field: 'password',
                  message: 'The password and its confirm are not the same'
                }
              }
            },
          }
        })
        .on('success.form.bv', function(e) {
          $('#success_message').slideDown({
            opacity: "show"
          }, "slow") // Do something ...
          $('#reg_form').data('bootstrapValidator').resetForm();

          // Prevent form submission
          e.preventDefault();

          // Get the form instance
          var $form = $(e.target);

          // Get the BootstrapValidator instance
          var bv = $form.data('bootstrapValidator');

          // Use Ajax to submit form data
          $.post($form.attr('action'), $form.serialize(), function(result) {
            console.log(result);
          }, 'json');
        });
    });
  </script>
</body>

</html>
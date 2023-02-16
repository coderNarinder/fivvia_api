$(document).ready(function(){
   // login Form
  $('#loginForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "email": { 
          required: true,
          email: true,
      },
      "password": {
          required: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // login Submitting Form 
    $('#loginFormBtn').click(function() {
      if($('#loginForm').valid())
      {
        $('#loginFormBtn').prop('disabled', true);
        $('#loginForm').submit();
      } else {
        return false;
      }
    });
    
});

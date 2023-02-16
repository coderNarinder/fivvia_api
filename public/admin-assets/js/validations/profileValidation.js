$(document).ready(function(){
   // password Form
  $('#passwordForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "old_password": { 
          required: true,
      },
      "password": {
          required: true,
          minlength: 6,
          pwcheck: true,
          maxlength: 20,
      },
      "password_confirmation": {
      	required: true,
      	equalTo: "#password"
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // password Submitting Form 
    $('#passwordFormBtn').click(function() {
      if($('#passwordForm').valid())
      {
        $('#passwordFormBtn').prop('disabled', true);
        $('#passwordForm').submit();
      } else {
        return false;
      }
    });


     // profile Form
  $('#profileForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // profile Submitting Form 
    $('#profileFormBtn').click(function() {
      if($('#profileForm').valid())
      {
        $('#profileFormBtn').prop('disabled', true);
        $('#profileForm').submit();
      } else {
        return false;
      }
    });
    
});

$(document).ready(function(){
   // SignupPage Form
	$('#signupForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "meta_title": { 
          required: true,
      },
      "meta_description": { 
          required: true,
      },
      "meta_keyword": { 
          required: true,
      },
      "signup_title": { 
          required: true,
          maxlength: 100,
      },
      "heading": { 
          required: true,
          maxlength: 100,
      },
      "description": { 
          required: true,
          maxlength: 500,
      },
      "section1_title": {
        required: true,
        maxlength: 100,
      },
      "section1_tagline": {
        required: true,
        maxlength: 100,
      },
      "section2_title": {
        required: true,
        maxlength: 100,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // SignupPage Submitting Form 
    $('#signupFormBtn').click(function()
    {
      if($('#signupForm').valid())
      {
        $('#signupFormBtn').prop('disabled', true);
        $('#signupForm').submit();
      } else {
        window.scrollTo({top: 0, behavior: 'smooth'});
        return false;
      }
    });
    
});

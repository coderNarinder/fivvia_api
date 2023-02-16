$(document).ready(function(){
   // LoginPage Form
	$('#loginForm').validate({
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
      "login_title": { 
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
  
    // LoginPage Submitting Form 
    $('#loginFormBtn').click(function()
    {
      if($('#loginForm').valid())
      {
        $('#loginFormBtn').prop('disabled', true);
        $('#loginForm').submit();
      } else {
        window.scrollTo({top: 0, behavior: 'smooth'});
        return false;
      }
    });
    
});

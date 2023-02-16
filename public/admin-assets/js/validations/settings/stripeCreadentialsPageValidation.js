$(document).ready(function(){
   // stripeCreadentials Form
	$('#stripeCreadentialsForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "sandbox_secret": { 
          required: true,
      },
      "sandbox_public": { 
          required: true,
      },

      "live_secret": { 
          required: true,
      },
      "live_public": { 
          required: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // stripeCreadentials Submitting Form 
    $('#stripeCreadentialsFormBtn').click(function() {
      if($('#stripeCreadentialsForm').valid()) {
        $('#stripeCreadentialsFormBtn').prop('disabled', true);
        $('#stripeCreadentialsForm').submit();
      } else {
        window.scrollTo({top: 0, behavior: 'smooth'});
        return false;
      }
    });
    
});

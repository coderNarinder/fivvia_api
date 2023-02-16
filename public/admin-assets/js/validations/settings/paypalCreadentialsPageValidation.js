$(document).ready(function(){
   // paypalCreadentials Form
	$('#paypalCreadentialsForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "sandbox_username": { 
          required: true,
      },
      "sandbox_password": { 
          required: true,
      },
      "sandbox_clientId": { 
          required: true,
      },
      "sandbox_secret": { 
          required: true,
      },

      "live_username": { 
          required: true,
      },
      "live_password": { 
          required: true,
      },
      "live_clientId": { 
          required: true,
      },
      "live_secret": { 
          required: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // paypalCreadentials Submitting Form 
    $('#paypalCreadentialsFormBtn').click(function() {
      if($('#paypalCreadentialsForm').valid()) {
        $('#paypalCreadentialsFormBtn').prop('disabled', true);
        $('#paypalCreadentialsForm').submit();
      } else {
        window.scrollTo({top: 0, behavior: 'smooth'});
        return false;
      }
    });
    
});

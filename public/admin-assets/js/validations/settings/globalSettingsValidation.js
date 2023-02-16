$(document).ready(function(){
   // globalSettings Form
	$('#globalSettingsForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "google_api_key": { 
          required: true,
      },
      "weather_api_key": { 
          required: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // globalSettings Submitting Form 
    $('#globalSettingsFormBtn').click(function()
    {
      if($('#globalSettingsForm').valid())
      {
        $('#globalSettingsFormBtn').prop('disabled', true);
        $('#globalSettingsForm').submit();
      } else {
        window.scrollTo({top: 0, behavior: 'smooth'});
        return false;
      }
    });
    
});

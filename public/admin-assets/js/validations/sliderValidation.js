$(document).ready(function(){
   //  Form
	$('#sliderForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "title": { 
          required: true,
          maxlength: 50,
      },
      "slider_link": { 
          // required: true,
          url: true,
          maxlength: 100,
      },
      "image": { 
          // required: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    //  Submitting Form 
    $('#sliderFormSbt').click(function()
    {
      if($('#sliderForm').valid())
      {
        $('#sliderFormSbt').prop('disabled', true);
        $('#sliderForm').submit();
      } else {
        return false;
      }
    });  
    
});

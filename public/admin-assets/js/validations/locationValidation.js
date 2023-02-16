$(document).ready(function(){
   //  Form
	$('#locationForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "label": { 
          required: true,
          maxlength: 50,
      },
      "pincode": { 
          // required: true,
          maxlength: 10,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    //  Submitting Form 
    $('#locationFormSbt').click(function()
    {
      if($('#locationForm').valid())
      {
        $('#locationFormSbt').prop('disabled', true);
        $('#locationForm').submit();
      }else {
        return false;
      }
    });  
    
});

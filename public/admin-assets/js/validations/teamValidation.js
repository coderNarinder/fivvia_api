$(document).ready(function(){
   //  Form
	$('#teamForm').validate({
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
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    //  Submitting Form 
    $('#teamFormSbt').click(function()
    {
      if($('#teamForm').valid())
      {
        $('#teamFormSbt').prop('disabled', true);
        $('#teamForm').submit();
      } else {
        return false;
      }
    });  
    
});

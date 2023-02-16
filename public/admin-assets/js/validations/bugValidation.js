$(document).ready(function(){
   //  Form
	$('#categoryForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
     
      "title": { 
          required: true,
          
      },
      
      "description": { 
          required: true,
          maxlength: 500,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    //  Submitting Form 
    $('#categoryFormSbt').click(function()
    {
      if($('#categoryForm').valid())
      {
        $('#categoryFormSbt').prop('disabled', true);
        $('#categoryForm').submit();
      } else {
        return false;
      }
    });
    
});
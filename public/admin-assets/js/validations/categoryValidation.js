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
      "label": { 
          required: true,
          maxlength: 50,
      },
      "meta_title": { 
          required: true,
          maxlength: 50,
      },
      "meta_tag": { 
          required: true,
          maxlength: 100,
      },
      "meta_description": { 
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



//========================================================================================================================

    $('#variationForm').validate({
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
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    //  Submitting Form 
    $('#variationFormSbt').click(function()
    {
      if($('#variationForm').valid())
      {
        $('#categoryFormSbt').prop('disabled', true);
        $('#categoryForm').submit();
      } else {
        return false;
      }
    });
    
});

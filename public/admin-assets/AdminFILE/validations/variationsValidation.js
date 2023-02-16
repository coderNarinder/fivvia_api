$(document).ready(function(){
   // Add Department Form
	$('#formVariations').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "events[]": { 
          chrequired: true,
      },
      "amenities[]": { 
          chrequired: true,
      },
      "games[]": { 
          chrequired: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // Add Department Submitting Form 
    $('#btnVariations').click(function()
    {
      if($('#formVariations').valid())
      {
        $('#btnVariations').prop('disabled', true);
        $('#formVariations').submit();
      } else {
        return false;
      }
    });
    
});

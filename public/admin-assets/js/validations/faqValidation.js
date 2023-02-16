$(document).ready(function(){
   // Faq Form
  $('#faqForm').validate({
     ignore: [],
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },

    errorPlacement: function(error, element) {
      if (element.attr("name") == "answer") {
        error.insertAfter("#cke_answer");
      } else {
         error.insertAfter(element);
      }
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
      "question": { 
          required: true,
      },
      "answer": {
          ckrequired: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // faq Submitting Form 
    $('#faqFormBtn').click(function()
    {
      if($('#faqForm').valid())
      {
        $('#faqFormBtn').prop('disabled', true);
        $('#faqForm').submit();
      } else {
        return false;
      }
    });
    
});

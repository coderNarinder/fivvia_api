$(document).ready(function(){
   $('#storyForm').validate({
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
          "youtube_link": { 
              // required: true,
              url: true,
              maxlength: 100,
          },
          valueToBeTested: {
              required: true,
          }
        },
      });   
  
    //  Submitting Form 
    $('#storyFormSbt').click(function()
    {
      if($('#storyForm').valid())
      {
        $('#storyFormSbt').prop('disabled', true);
        $('#storyForm').submit();
      } else {
        return false;
      }
    });  
    
});

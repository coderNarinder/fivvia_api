$(document).ready(function() {
     // Reject Business Form
  $('#businessRejectForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },
  
    rules: {
      "comment": {
        required: true,
      },
      
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // Reject Business Submitting Form 
    $('#businessRejectFormBtn').click(function() {
      if($('#businessRejectForm').valid())
      {
        $('#businessRejectFormBtn').prop('disabled', true);
        $('#businessRejectForm').submit();
      } else {
        return false;
      }
    });
    
});

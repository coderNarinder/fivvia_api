$(document).ready(function() {
   // Email Submit Business Page Form
  $('#businessSubForm').validate({
     ignore: [],
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },

    errorPlacement: function(error, element) {
      if (element.attr("name") == "body") {
        error.insertAfter("#cke_body");
      } else {
         error.insertAfter(element);
      }
    },
  
    rules: {
      "subject": { 
          required: true,
      },
      "title": { 
          required: true,
      },
      "body": {
          ckrequired: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // Email Submit Business Page Submitting Form 
    $('#businessSubFormBtn').click(function()
    {
      if($('#businessSubForm').valid())
      {
        $('#businessSubFormBtn').prop('disabled', true);
        $('#businessSubForm').submit();
      } else {
        return false;
      }
    });


    // Email Submit Business Approved Page Form
  $('#emailAppForm').validate({
     ignore: [],
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },

    errorPlacement: function(error, element) {
      if (element.attr("name") == "body1") {
        error.insertAfter("#cke_body1");
      } else {
         error.insertAfter(element);
      }
    },
  
    rules: {
      "subject": { 
          required: true,
      },
      "title": { 
          required: true,
      },
      "body": {
          ckrequired: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // Email Submit Business Page Approved Submitting Form 
    $('#emailAppFormBtn').click(function()
    {
      if($('#emailAppForm').valid())
      {
        $('#emailAppFormBtn').prop('disabled', true);
        $('#emailAppForm').submit();
      } else {
        return false;
      }
    });

     // Email Submit Business Reject Page Form
  $('#emailRejForm').validate({
     ignore: [],
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
    },

    errorPlacement: function(error, element) {
      if (element.attr("name") == "body2") {
        error.insertAfter("#cke_body2");
      } else {
         error.insertAfter(element);
      }
    },
  
    rules: {
      "subject": { 
          required: true,
      },
      "title": { 
          required: true,
      },
      "body": {
          ckrequired: true,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // Email Submit Business Page Reject Submitting Form 
    $('#emailRejFormBtn').click(function() {
      if($('#emailRejForm').valid()) {
        $('#emailRejFormBtn').prop('disabled', true);
        $('#emailRejForm').submit();
      } else {
        return false;
      }
    });
    
});

$(document).ready(function(){
   // HomePage Form
	$('#homePageForm').validate({
    onfocusout: function (valueToBeTested) {
      $(valueToBeTested).valid();
    },
  
    highlight: function(element) {
      $('element').removeClass("error");
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
      "slider_title": { 
          required: true,
          maxlength: 100,
      },
      "slider_tagline": { 
          required: true,
          maxlength: 100,
      },
      "slider_button_title": {
          required: true,
          maxlength: 100,
      },
      "slider_button_url": { 
          required: true,
          isUrl: true,
      },
      "section1_title": {
          required: true,
          maxlength: 100,
      },
      "section1_tagline": {
          required: true,
          maxlength: 100,
      },
      "section2_title":{
          required: true,
          maxlength: 100,
      },
      "section2_tagline":{
          required: true,
          maxlength: 100,
      },
      "section2_image_tagline": {
          required: true,
          maxlength: 100,
      },
      "section3_title": {
          required: true,
          maxlength: 100,
      },
      "section3_tagline": {
          required: true,
          maxlength: 1000,
      },      
      "section4_title1": {
          required: true,
          maxlength: 100,
      },
      "section4_tagline1": {
          required: true,
          maxlength: 100,
      },
      "section4_description": {
          required: true,
          maxlength: 500,
      },
      "section4_title2": {
          required: true,
          maxlength: 100,
      },
      "section4_tagline2": {
          required: true,
          maxlength: 100,
      }, 
      "section4_button_title": {
          required: true,
          maxlength: 100,
      },      
      "section4_button_url": {
          required: true,
          isUrl: true
      },
      "section5_title": {
          required: true,       
          maxlength: 100,
      },
      valueToBeTested: {
          required: true,
      }
    },
    });   
  
    // HomePage Submitting Form 
    $('#homePageFormBtn').click(function()
    {
      if($('#homePageForm').valid())
      {
        $('#homePageFormBtn').prop('disabled', true);
        $('#homePageForm').submit();
      } else {
        window.scrollTo({top: 0, behavior: 'smooth'});
        return false;
      }
    });
    
});

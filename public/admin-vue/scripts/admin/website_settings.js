


$(document).ready(function(){

   // CmsPage Form

  $('form[name=websiteSettings]').validate({

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

      "contact_number1": { 
           number:true,
           minlength:8,
           maxlength:15,
      }, "contact_number2": { 
           number:true,
           minlength:8,
           maxlength:15,
      }, "contact_number3": { 
           number:true,
           minlength:8,
           maxlength:15,
      },

      "Developed_by":{
          
         maxlength:100
      },
      "facebook_url":{
         url:true,
         maxlength:100
      },
      "twitter_url":{
         url:true,
         maxlength:100
      },
      "instagram_url":{
         url:true,
         maxlength:100
      },
      "youtube_url":{
         url:true,
         maxlength:100
      },
      "admin_email":{
         email:true,
         maxlength:100
      },
      "admin_contact":{
         
         maxlength:100
      },
      "copyright":{
         
         maxlength:100
      },
      "play_store_url":{
         url:true,
         maxlength:100
      },
      "app_store_url":{
         url:true,
         maxlength:100
      },
 
 
      valueToBeTested: {

          required: true,

      }

    },

    });   

  

    // CmsPage Submitting Form 

    $('#websiteSettingsBtn').click(function()

    {

      if($('#websiteSettings').valid())

      {

        $('#websiteSettingsBtn').prop('disabled', true);

        $('#websiteSettings').submit();

      } else {

        return false;

      }

    });

    



function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { 
            $('#imagePreview').attr('src',e.target.result);
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});











});

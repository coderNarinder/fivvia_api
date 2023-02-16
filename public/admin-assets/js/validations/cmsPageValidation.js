$(document).ready(function(){

   // CmsPage Form

  $('#CmsPageForm').validate({

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

      // "meta_title": { 

      //     required: true,

      // },

      // "meta_keyword": { 

      //     required: true,

      // },

      // "meta_description": { 

      //     required: true,

      // },

      "page_name": { 

          required: true,

      },

      // "description": {

      //     ckrequired: true,

      // },

      valueToBeTested: {

          required: true,

      }

    },

    });   

  

    // CmsPage Submitting Form 

    $('#CmsPageFormBtn').click(function()

    {

      if($('#CmsPageForm').valid())

      {

        $('#CmsPageFormBtn').prop('disabled', true);

        $('#CmsPageForm').submit();

      } else {

        return false;

      }

    });

    

});



jQuery("form[id='userStepForm']").validate({

	ignore: "",
    rules: {

      'first_name': 
      {
        required: true,
        nowhitespace: true,
        maxlength: 35
      },
      'last_name': 
      {
        required: true,
        nowhitespace: true,
        maxlength: 35
      },
      'email': 
      {
        required: true,
        nowhitespace: true,
        email:true,
        customemail: true,
        maxlength: 35
      },
      'title': 
      {
        required: true,
        nowhitespace: true,
      },
      'device_type': 
      {
        required: true,
     
      },
      'role': 
      {
        required: true,
      
      },
      'device_limit':
      {
        required: true,
      },
      'company_name': 
      {
        required: true,
        nowhitespace: true,
      },
      'profile_picture': 
      {
        required: true
      },
      'company_description': 
      {
        required: true,
        nowhitespace: true,
      },
      'session_expire_in': 
      {
        required: true,
        nowhitespace: true,
      },
      'mobile_number':
      {
        required: true,
        digits:true,
        maxlength: 15,
        minlength:10
      },
      'address':
      {
        required: true,
        nowhitespace: true,
        maxlength: 35
      },
      'start_date':{
        required: true,
        cstmstart:true     
    },
      'end_date':{
        required: true,
        cstmend:true     
    },
 }   
});

/*jQuery("form[id='upload_form']").validate({

  ignore: "",
    rules: {

      'file': 
      {
        required: true, 
        extension : "png|jpeg|jpg|PNG|JPEG|JPG"  
      },

    },
    
       
});*/

jQuery("form[id='formGroupUpdation']").validate({

  ignore: "",
    rules: {

      'label': 
      {
        required: true, 
        nowhitespace: true,
        maxlength:35
      },
      'number_of_group':
      {
        required: true, 
        digits: true,
      }

    },
    
       
});

jQuery("form[id='userStepForm']").validate({

  ignore: "",
    rules: {

      'groups': 
      {
        required: true, 
        nowhitespace: true,
        maxlength:35
      }

    },
    
       
});


   
   

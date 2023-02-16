
jQuery("form[id='edit_profile']").validate({

  ignore: "",
    rules: {

      'profile_picture': 
      {
        required: true,
      },
      'first_name':
      {
        required: true,
        nowhitespace :true,
        maxlength:35
      },
      'last_name':
      {
        required: true,
        nowhitespace :true,
        maxlength:35
      },
      'address':
      {
        required: true,
        nowhitespace :true,
        maxlength:35
      },
      'title':
      {
        required: true,
      },
      'birthday':
      {
        required: true,
      },
      'company_address':
      {
        required: true,
        nowhitespace :true,
        maxlength:35
      },
      'company_phone':
      {
        required: true,
        digits :true,
        minlength:10,
        maxlength:15
      },      
      'occupation':
      {
        required: true,
        nowhitespace :true,
        maxlength:35
      },
      'biography':
      {
        required: true,
        nowhitespace :true
      },
      'company_description':
      {
        required: true,
        nowhitespace :true
      },
      'company_email':
      {
        required: true,
        nowhitespace :true,
        customemail:true
      },
      'email':
      {
        required: true,
        nowhitespace :true,
        customemail:true
      },
      'phone':
      {
        required: true,
        digits:true,
        minlength:8,
        maxlength:15
      },
      'instagram_link':
      {
        required: true,
        nowhitespace :true,
        url: true
      },
      'twitter_link':
      {
        required: true,
        nowhitespace :true,
        url: true
      },
      'facebook_link':
      {
        required: true,
        nowhitespace :true,
        url: true
      },
      'linkedin_link':
      {
        required: true,
        nowhitespace :true,
        url: true
      },
      'company_name':
      {
        required: true,
        nowhitespace :true,
        maxlength:35
      },
      'company_logo':
      {
        required: true,
        nowhitespace :true
      },
      

    },
    
});

jQuery("form[name='change_password']").validate({

  ignore: "",
    rules: {

      'current_password': 
      {
        required: true,
      },
      'new_password':
      {
        required: true,
        nowhitespace :true,
        minlength: 5
      },
      'confirm_password':
      {
        required: true,
        nowhitespace :true,
        minlength: 5,
        equalTo: "#new_password"
      }
      
    },
    
});

jQuery("form[name='profile_2fa']").validate({

  ignore: "",
    rules: {

      'digit1': 
      {
        required: true,
        digits :true
      },
      'digit2':
      {
        required: true,
        digits :true
      },
      'digit3':
      {
        required: true,
        digits :true
      },
      'digit4':
      {
        required: true,
        digits :true
      },
      'digit5':
      {
        required: true,
        digits :true
      },
      'digit6':
      {
        required: true,
        digits :true
      },
      
    },
    
});

jQuery("form[name='regional_setting']").validate({

  ignore: "",
    rules: {

      'regional': 
      {
        required: true,
      },
      'language':
      {
        required: true,
      },
      
      
    },
    
});



   

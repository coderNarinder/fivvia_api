jQuery("form[name='country-edit']").validate({

  ignore: "",
    rules: {

      'name': 
      {
        required: true,
        nowhitespace :true,
        maxlength:30
      },
      'code':
      {
        required: true,
        nowhitespace :true,
        maxlength: 3,
        digits:true
      },
      'image':
      {
        required: true,
        nowhitespace :true,
        maxlength: 40
      }
      
    },
    
});

jQuery("form[name='country-add']").validate({

  ignore: "",
    rules: {

      'name': 
      {
        required: true,
        nowhitespace :true,
        maxlength:30
      },
      'code':
      {
        required: true,
        nowhitespace :true,
        maxlength: 3,
        digits:true
      },
      'image':
      {
        required: true,
        nowhitespace :true,
        maxlength: 40
      }
      
    },
    
});



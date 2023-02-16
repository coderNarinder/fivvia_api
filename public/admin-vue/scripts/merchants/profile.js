const $validateUrl = $("body").attr('data-validateUrl');
 
jQuery("form#form22").validate({
    rules: {
      'email': 
      {
        required: true,
        email:true,
        remote: $validateUrl
      }, 
      
      'first_name': 
      {
        required: true,
        maxlength:50,
        lettersonly:true
      }, 
      'last_name': 
      {
        required: true,
        maxlength:50,
        lettersonly:true
      },
      'phone_number': 
      {
        required: true,
        number: true,
         maxlength:15,
         minlength:10,
         remote: $validateUrl
      },
      'gst_number': 
      {
        alphanumeric: true, 
        maxlength:15,
        minlength:10,
         
      },'pan_number': 
      {
        alphanumeric: true, 
        maxlength:15,
        minlength:8,
         
      },'business_name': 
      {
        alphanumeric: true, 
        maxlength:50,
      }, 
      
      
   
    }, 
    messages:{
       email:{
           remote:'This email already has been taken.'
       },
       phone_number:{
           remote:'This phone number already has been taken.'
       }
    }    
});
$("body").on('submit','#form22',function(e){
  e.preventDefault();
    var $this = $(this);
    if(jQuery('#form22').valid()){
         submitForm($this);
            
  }
});
const $validateUrl = $("body").attr('data-validateUrl');
 



$.validator.addMethod('lessThanEqual', function(value, element, param) {
    if (this.optional(element)) return true;
    var i = parseInt(value);
    var j = parseInt($(param).val());
    return i < j;
}, "The value {0} must be less than {1}");







jQuery("form#storyForm").validate({

    
    rules: {

      'title': 
      {
        required: true,
        maxlength:100,
      }, 
      'tagline': 
      {
        required: true,
         maxlength:100,
        
      },
      'description': 
      {
        required: true,
         maxlength:100,
      }, 
      'promo_discount': 
      {
        required: true,
        number: true,
        maxlength:4,
        minlength:1,
      },
      'min_order': 
      {
        required: true,
        number: true,
       
      },'upto_discount': 
      {
        required: true,
        number: true,
        lessThanEqual:'#min_order'
       
      },
      'start_date': 
      {
        required: true
      },
      'end_date': 
      {
        required: true
      },
      'coupon_code': 
      {
        required: true,
        
      },
      'usage_limit': 
      {
        required: true,
        number: true,

        maxlength:5,
        minlength:1,
      } 
   
    }, 
    messages:{
       email:{
           remote:'This email already has been taken.'
       },
       phone_number:{
           remote:'This phone number already has been taken.'
       },
       upto_discount:{
           lessThanEqual:"Max Discount must be less than Min Order."
       }
    }    
});
$("body").on('submit','#storyForm',function(e){
  e.preventDefault();
    var $this = $(this);
    if(jQuery('#storyForm').valid()){
         submitForm($this);
            
  }
});

 
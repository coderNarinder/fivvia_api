 
jQuery("form#form22").validate({
  rules: {
        old_password : {
            minlength : 6
        },
        password : {
            minlength : 6
        },
        password_confirm : {
            minlength : 6,
              equalTo : "#password"
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
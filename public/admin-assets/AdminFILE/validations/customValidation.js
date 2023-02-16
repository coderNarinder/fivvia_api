$.validator.addMethod("chrequired", function(value, elem, param) {
   return value > 0;
},"You must select at least one!");

 // Ck-Editor
  $.validator.addMethod('ckrequired', function (value, element, params) {
      var idname = jQuery(element).attr('id');
      var messageLength = jQuery.trim ( CKEDITOR.instances[idname].getData() );
      CKEDITOR.instances[idname].on("change", function (evt) {
          if(CKEDITOR.instances[idname].getData().length !== 0)
            $(`#${idname}`).closest('.form-group').find('label').css('display', 'none');
          else
            $(`#${idname}`).closest('.form-group').find('label').css('display', 'block');
      });
      CKEDITOR.instances[idname].updateElement();    
      return !params || messageLength.length !== 0;
  }, "This field is required.");

  //Alphanumeric-Add-Method
  $.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || /^[a-z\d\-_\s]+$/i.test(value);
  }, "Please enter alpha-numeric characters only."); 

  // letters only 
  $.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z ]+$/i.test(value);
  }, "Letters only please");
  
  // greater than equals to
  $.validator.addMethod('ge', function(value, element, param) {
    return this.optional(element) || value >= param;
  }, 'Must be greater than or equal to field 0');

   // greater than equals to
  $.validator.addMethod('res_number', function(value, element, param) {
    return this.optional(element) || !/\d/.test(value);
  }, 'Please enter valid text');

  // strong password
  $.validator.addMethod("pwcheck", function(value, element) {
    return this.optional(element) || 
    /[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~]/.test(value)  // has a special charactor
    //  /^[A-Za-z0-9\d=!\-@._*]+$/.test(value) //only allowed characters
    // /^[a-zA-Z0-9- ]*$/.test(value) // special charactor restricted
      && /[a-z]/.test(value) // has a lowercase letter
      && /[A-Z]/.test(value) // has a capital letter
      && /\d/.test(value) // has a digit      
  }, 'digit, lowercase, capital, and special characters is required');

  // validation for amount
  $.validator.addMethod('amount', function(value, element, param) {
    return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/.test(value);
  }, 'Please enter valid amount');
  
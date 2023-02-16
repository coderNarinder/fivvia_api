
/* Cstm Validations */
const $validate_Urlss = $("body").attr('data-validateUrl');

const $loader = $("body").find('.custom-loader');

jQuery.validator.addMethod("LettersOnly", function(value, element) 
{
return this.optional(element) || /^[a-z," "]+$/i.test(value);
}, "Letters and spaces only please"); 


/*  Login Form Validations  */
jQuery("form[name=userRegisterForm]").validate({
    rules: {

      'email': 
      {
        required: true,
        nowhitespace: true,
        email: true,
        remote: $validate_Urlss
      },
      'first_name': 
      {
        required: true,
        LettersOnly:true,
        maxlength:50,
      }, 
      'terms_and_conditions': 
      {
        required: true
        
      }, 
      // 'last_name': 
      // {
      //   required: true,
      //   maxlength:50,
      //   nowhitespace: true,
      // },
      'phone_number': 
      {
        required: true,
        number: true,
        minlength:10,
        maxlength:15,
        remote: $validate_Urlss
      },
      'password': 
      {
        required: true,
        minlength:8,
        maxlength:30,
        nowhitespace: true,

      },
      // 'password_confirmation': 
      // {
      //   required: true,
      //   nowhitespace: true,
      //   equalTo: "#password"

      // }
   
    }, 
    messages:{
       email:{
           remote:'Email already exists.'
       },
       phone_number:{
           remote:'Mobile number already exists.'
       }
    }     
});



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



/*  Login Form Validations  */
jQuery("form[name=vendorRegisterForm]").validate({
    rules: {
      'email': 
      {
        required: true,
        nowhitespace: true,
        email: true,
        remote: $validate_Urlss
      },
      'first_name': 
      {
        required: true,
       LettersOnly:true, 
        maxlength:50,
      }, 
      'terms_and_conditions': 
      {
        required: true
        
      }, 
      'phone_number': 
      {
        required: true,
        number: true,
        minlength:10,
        maxlength:15,
        remote: $validate_Urlss
      },
      'password': 
      {
        //pwcheck: true,
        required: true,
        minlength:8,
        maxlength:30,
        nowhitespace: true,

      },
      
    }, 
    messages:{
       email:{
           remote:'Email already exists.'
       },
       phone_number:{
           remote:'Mobile number already exists.'
       }
    }    
});




jQuery("form#resetPassword").validate({
    rules: {
      'password': 
      {
        required: true,
        nowhitespace: true,
        minlength:6,
        maxlength:15

      },
      'password_confirmation': 
      {
        required: true,
        nowhitespace: true,
        minlength:6,
        maxlength:15,
        equalTo: "#password"

      }
   
    }, 
    messages:{
       email:{
           remote:'This email is already taken.'
       },
       phone_number:{
           remote:'This Mobile number is already taken.'
       }
    }    
});





jQuery("form#forgetPassword").validate({

    
    rules: {
      'email_number': 
      {
        required: true,
        nowhitespace: true,
        remote: $validate_Urlss
      } 
   
    }, 
    messages:{
       email_number:{
           remote:'This is not found!'
       } 
    }    
});


jQuery("form[name='change-password']").validate({

    
    rules: {

      'password': 
      {
        required: true,
        nowhitespace: true,
      },
      'password_confirmation': 
      {
        required: true,
        nowhitespace: true,
        equalTo: "#password"

      }
   
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

jQuery("form[name='change-password']").validate({

    
    rules: {

      'password': 
      {
        required: true,
        nowhitespace: true,

      },
      'password_confirmation': 
      {
        required: true,
        nowhitespace: true,
        equalTo: "#password"
      }
   
    },     
});


jQuery("form[name='changePasswordForteamMember']").validate({

    
    rules: {

      'password': 
      {
        required: true,
        nowhitespace: true
         
      },
      'password_confirmation': 
      {
        required: true
        

      }
   
    },     
});




/* Cstm Validations */
jQuery("form[name='resetingPasswordForm']").validate({

    
    rules: {

      'email': 
      {
        required: true,
        nowhitespace: true,
        customemail : true
      },
      'password': 
      {
        required: true,
        nowhitespace: true

      },
      'password_confirmation': 
      {
        required: true,
        nowhitespace: true

      }
   
    },     
});

/* Cstm Validations */
jQuery("form[name='resetPasswordForm']").validate({

    
    rules: {

      'email': 
      {
        required: true,
        nowhitespace: true,
        customemail : true
      } 
    },
         
});


jQuery("form[id='otpForm2']").validate({

    
    rules: {

      'digit1': 
      {
        required: true,
        digits: true
      },
      'digit2': 
      {
        required: true,
        digits: true
      },
      'digit3': 
      {
        required: true,
        digits: true
      },
      'digit4': 
      {
        required: true,
        digits: true
      },
      'digit5': 
      {
        required: true,
        digits: true
      },
      'digit6': 
      {
        required: true,
        digits: true
      }
        
    },     
});








//-----------------------------------------------------------------------------------------------
//  password-show
//-----------------------------------------------------------------------------------------------

$("body").on('click','.password-show',function(e){
   e.preventDefault();
    var $this = $(this);
    var $change = $this.attr('data-change');

    if($change == "text"){
      $this.attr('data-change','password');
      $this.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
    }else{
      $this.attr('data-change','text');
      $this.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
    }
    $("body").find($this.data('target')).attr('type',$change);

});








//-----------------------------------------------------------------------------------------------
//  login form submit function
//-----------------------------------------------------------------------------------------------

$("body").on('submit','#resetPassword',function(e){
   e.preventDefault();
    login_func($(this));
});



//-----------------------------------------------------------------------------------------------
//  login form submit function
//-----------------------------------------------------------------------------------------------

$("body").on('submit','#forgetPassword',function(e){
   e.preventDefault();
    login_func($(this));
});





//-----------------------------------------------------------------------------------------------
//  login form submit function
//-----------------------------------------------------------------------------------------------

$("body").on('submit','#resetingPasswordForm',function(e){
   e.preventDefault();
    login_func($(this));
});






//-----------------------------------------------------------------------------------------------
//  login form submit function
//-----------------------------------------------------------------------------------------------

$("body").on('submit','form[name=vendorRegisterForm]',function(e){
    e.preventDefault();
    $this = $(this);
    var recaptchaRes = grecaptcha.getResponse();
    var message = "";   
    if(recaptchaRes.length == 0) {
     message = "Please complete the reCAPTCHA challenge!";
      notification_msg(message);
    } else {
     $this.find('.messageDiv').html('');
     
     login_func($(this));
    }
});


   

//-----------------------------------------------------------------------------------------------
//  login form submit function
//-----------------------------------------------------------------------------------------------

$("body").on('submit','form[name=userRegisterForm]',function(e){
    e.preventDefault();
    $this = $(this);
    var recaptchaRes = grecaptcha.getResponse();
    var message = "";   
    if(recaptchaRes.length == 0) {
         message = "Please complete the reCAPTCHA challenge!";
          notification_msg(message);
    } else {
         $this.find('.messageDiv').html('');
         
         login_func($(this));
    }  
});







 




jQuery('#loginWithOtp').validate({
       rules: {
             'email': 
             {
                required: true,
                nowhitespace: true,
                number: true,
                minlength:10,
                maxlength:15,
             }  
        }, 
        messages: {
              email: {
                  required: "Enter your mobile number.",
                  nowhitespace: "No white space.",
                  number:"Please enter digits only.",
                  minlength:"Please enter 10 digits.",
                  maxlength:"Please enter 10 digits."
              } 
        }     
});

jQuery('#loginWithEmail').validate({
             rules: {
                 'email': 
                 {
                    required: true,
                    nowhitespace: true,
                    email:true
                 },
                 'password': 
                 {
                    required: true,
                    nowhitespace: true,
                 }
              }, 
            messages: {
                email: {
                    required: "Enter your email.",
                    nowhitespace: "No white space."
                }
            }     
});

//-----------------------------------------------------------------------------------------------
//  login form submit function
//-----------------------------------------------------------------------------------------------
// $("body").on('click','#loginWithOtp',function(e){
//   e.preventDefault();
//   var $form = $("body").find('#login');
//       validatorLoginWithOtp();
//       if($form.valid()){
//           login_func($form);
//       }
// });


$("body").on('click','.useLoginOption',function(e){
  e.preventDefault();
  var $this = $(this);
  $($this.attr('data-target')).show();
  $($this.attr('data-target-hide-back')).hide();
  $($this.attr('data-target-hide')).hide();

  $($this.attr('data-target')).find('input:visible:first').focus()

});
 

//-----------------------------------------------------------------------------------------------
//  login form submit function
//-----------------------------------------------------------------------------------------------
 
// jQuery("form[name='login']").validate({
//      rules: {
//          'email': 
//          {
//             required: true,
//             nowhitespace: true,
//          }
//      },     
// });

// $("body").on('click','#loginWithEmailPassword',function(e){
//   e.preventDefault();
//   var $form = $("body").find('#login');
  
//   if($form.valid()){
//          login_func($form);
//   }
// });


$("body").on('submit','#loginWithEmail',function(e){
    e.preventDefault();
    login_func($(this));
});




$("body").on('submit','#loginWithOtp',function(e){
    e.preventDefault();
    login_func($(this));
});



//-----------------------------------------------------------------------------------------------
//  RESEND OPT FOR TM FORGET PASSWORD
//-----------------------------------------------------------------------------------------------

$("body").on('click','.SecondOption',function(e){
   e.preventDefault();
    var $modal = $("body").find('#myForgetPasswordModal');
    sendRequestToBS_func($(this).attr('data-action'),$modal);
});


function sendRequestToBS_func($url,$this) {
   $.ajax({
                url : $url,
                type: 'GET',  
                 
                dataTYPE:'JSON',
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                           $this.find('button').attr('disabled','true');
                },
                success: function (result) {
                     //-------------------------------------------------------------

                     statusFunctionAccordingToResponse(result,$this);
                },
                complete: function() {
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     console.log('------------------------');
                     console.log(jqXhr);
                     console.log('------------------------');
                     console.log(textStatus);
                     console.log('------------------------');
                     console.log(errorMessage);
                }

    });
}


function login_func($this){
	   
	  $.ajax({
                url : $this.attr('data-action'),
                type: 'POST',  
                data : $this.serialize(), 
                dataTYPE:'JSON',
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                           $this.find('button').attr('disabled','true');
                           $loader.show();
                },
                success: function (result) {
                     //-------------------------------------------------------------
                     $loader.hide();
                     statusFunctionAccordingToResponse(result,$this);
                },
                complete: function() {
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     console.log('------------------------');
                     console.log(jqXhr);
                     console.log('------------------------');
                     console.log(textStatus);
                     console.log('------------------------');
                     console.log(errorMessage);
                }

    });

}



//--------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------

















$("body").on('click','#optCheck',function(e){
   e.preventDefault();
   $(this).attr('disabled');
   var $this = $("body").find('#otpForm2');
    login_func($this);
});



//--------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------




$("body").on('submit','#resetPasswordForm',function(e){
   e.preventDefault();
    login_func($(this));
});





function countDownTimmer(url,$div) {
	  var counter = 5;
    var interval = setInterval(function() {
      $("body").find("#counter").attr('data-count-modal',0);
      $("body").find("#counter").attr('data-count',1);
      if(parseInt(counter) > 0){
        counter--;
       }
      
      if(parseInt(counter) == 0){
          window.location.href = url;
          return true;
      }
    }, 1000);

}







function statusFunctionAccordingToResponse(result,$this) {

      switch(result.status) {
        case 1:
               $this.find('.messageDiv').removeClass('alert alert-danger');
               $this.find('.messageDiv').addClass('alert alert-success');
               $this.find('.messageDiv').html(result.messages+' <span id="countDown">5 '+ " second's" +'</span>');
            
               var $div = $this.find('.messageDiv');
               countDownTimmer(result.url,$div.find('#countDown'));
               //window.location.href = result.url;
               $this.find("#counter").attr('data-count',1);
                $("body").find("#counter").attr('data-count',1);
                $("body").find("#counter").attr('data-count-modal',0);
          break;
        case 2:
        
              $this.find('.messageDiv').html(result.messages);
              $("body").find('#verified-OTP-modal').modal({
                        backdrop: 'static',
                        keyboard: false
              });
                $("body").find('#messageDivAlert').html(result.messages);
               var $timmerDiv = $("body").find('#timerCountDown');
                $("body").find("#counter").attr('data-count-modal',0);
                window.location.href = result.url;
               //countDownTimmer(result.url,$timmerDiv);
          break;
        case 3:
              $this.find('.messageDiv').removeClass('alert alert-danger');
              $this.find('.messageDiv').addClass('alert alert-success');
              $this.find('.messageDiv').html(result.messages);
               setInterval(function() {
                     window.location.href = result.url;
               }, 2000);
          break;
        case 4:
             $this.find('.messageDiv').html(result.messages);
             var $attemps = parseInt($this.find('#attemps').val()) + 1;
             $this.find('#attemps').val($attemps);
             $this.find('button').removeAttr('disabled');
          break;
        case 5:
               $this.find('.messageDiv').removeClass('alert alert-danger');
               $this.find('.messageDiv').addClass('alert alert-success');
               $this.find('.messageDiv').html(result.messages);
               $this[0].reset();
               $this.find('button').removeAttr('disabled');
                
          break;
          case 6:
               $this.find('.messageDiv').removeClass('alert alert-success');
               $this.find('.messageDiv').addClass('alert alert-danger');
               $this.find('.messageDiv').html(result.messages).fadeIn().fadeOut(4000);
               $this.find('button').removeAttr('disabled');
                
          break;
        case 7:
           $this.find('.messageDiv').removeClass('alert alert-success');
           $this.find('.messageDiv').addClass('alert alert-danger');
           $this.find('.messageDiv').html('');
           putTheError($this,result.errors);

           $this.find('button').removeAttr('disabled');
                
          break;
         case 8:
           $this.find('.messageDiv').removeClass('alert alert-success');
           $this.find('.messageDiv').addClass('alert alert-danger');
           $this.find('.messageDiv').html(result.messages);
           //$this.find('button').removeAttr('disabled');
           $this.find('#resendOTP').css('display','block');
                
          break;
         case 9:
            $modal = $("body").find('#loginPopup')
            $modal.find('.innder-loader').show();
            $modal.modal('show');
            $modal.find('.login-popup').html(result.list);
            loadOTPjs_for_popup();
          break;
         
        default:
           $this.find('.messageDiv').removeClass('alert alert-success');
           $this.find('.messageDiv').addClass('alert alert-danger');
           $this.find('.messageDiv').html(result.messages);
           $this.find('button').removeAttr('disabled');
           break;
      }

 
 
}

//---------------------------------------------------------------------------------------------------------------

function putTheError($this,errors) {
   $this.find('.customError').hide();
    $.each(errors, function( index, value ) {
        $this.find('#'+index+'-error').html(value).show();
        console.log(index);
          
      });

}


$("body").on('click','#resendOTP',function(e){
  e.preventDefault();
  var $this = $(this);
  var $url = $this.data('action');
  sendRequestToBS_func($url,$this);
});











//===================================================================================================================
//===================================================================================================================


$("body").on('submit','#otpFormPOP',function(e){
  e.preventDefault();
  otp_ajax_func($(this));
});



 function otp_ajax_func($this){
    $.ajax({
           url : $this.attr('data-action'),
           type: 'POST',  
           data : $this.serialize(), 
           dataTYPE:'JSON',
           headers: {
              'X-CSRF-TOKEN': $('input[name=_token]').val()
           },
           beforeSend: function() {
              $this.find('button').attr('disabled','true');
           },
           success: function (result) {
                 //-------------------------------------------------------------
                  statusFunctionAccordingToResponseForOTP(result,$this);
           },
           complete: function() {
           },
           error: function (jqXhr, textStatus, errorMessage) {
                 console.log('------------------------');
                 console.log(jqXhr);
                 console.log('------------------------');
                 console.log(textStatus);
                 console.log('------------------------');
                 console.log(errorMessage);
          }
      });
 }

















function statusFunctionAccordingToResponseForOTP(result,$this) {
     switch(result.status) {
          case 1:
              $this.find('.messageDiv').removeClass('alert alert-danger');
              $this.find('.messageDiv').addClass('alert alert-success');
              $this.find('.messageDiv').html(result.messages+' <span id="countDown">5 '+ " second's" +'</span>');
              // $this.find('.messageDiv').html(result.messages);
              var $div = $this.find('.messageDiv');
              countDownTimmerOtpPopup(result.url,$div.find('#countDown'));
              //window.location.href = result.url;
              $this.find("#counter").attr('data-count',1);
              $("body").find("#counter").attr('data-count',1);
              $("body").find("#counter").attr('data-count-modal',0);

          break;

        case 2:

             $this.find('.messageDiv').html(result.messages);
              $("body").find('#verified-OTP-modal').modal({
                      backdrop: 'static',
                      keyboard: false
              });
              $("body").find('#messageDivAlert').html(result.messages);
              var $timmerDiv = $("body").find('#timerCountDown');
              $("body").find("#counter").attr('data-count-modal',0);
              window.location.href = result.url;
        break;
        case 3:
           $this.find('.messageDiv').removeClass('alert alert-danger');
           $this.find('.messageDiv').addClass('alert alert-success');
           $this.find('.messageDiv').html(result.messages);
            setInterval(function() {
             window.location.href = result.url;
            }, 2000);

          break;

        case 4:
            $this.find('.messageDiv').html(result.messages);
            var $attemps = parseInt($this.find('#attemps').val()) + 1;
            $this.find('#attemps').val($attemps);
            $this.find('button').removeAttr('disabled');
            break;

        case 5:
            $this.find('.messageDiv').removeClass('alert alert-danger');
            $this.find('.messageDiv').addClass('alert alert-success');
            $this.find('.messageDiv').html(result.messages);
            $this[0].reset();
            $this.find('button').removeAttr('disabled');
            break;

        case 6:
            $this.find('.messageDiv').removeClass('alert alert-success');
            $this.find('.messageDiv').addClass('alert alert-danger');
            $this.find('.messageDiv').html(result.messages).fadeIn().fadeOut(4000);
            $this.find('button').removeAttr('disabled');
          break;
        default:
           $this.find('.messageDiv').removeClass('alert alert-success');
           $this.find('.messageDiv').addClass('alert alert-danger');
           $this.find('.messageDiv').html(result.messages);
           $this.find('button').removeAttr('disabled');
           break;

      }
 

}



function countDownTimmerOtpPopup(url,$div) {
   var counter = 5;
   $this = $("body").find('.login-popup');
   var interval = setInterval(function() {
        $this.find("#counter").attr('data-count-modal',0);
        $this.find("#counter").attr('data-count',1);
        if(parseInt(counter) > 0){
           counter--;
        }
        $div.html(counter+ " second's");  
        if (counter == 0) {
            $modal = $("body").find('#loginPopup');
            $modal.modal('hide');
            window.location.reload();
        }
        }, 1000);
 }










$("body").on('click','.add-to-wishlist',function(e){
  e.preventDefault();
  wishlist_ajax_func($(this));

});


function wishlist_ajax_func($this){
    $.ajax({
                url : $this.attr('data-action'),
                type: 'POST',  
                dataTYPE:'JSON',
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                    $this.find('button').attr('disabled','true');
                },
                success: function (result) {
                     //-------------------------------------------------------------
                     statusWishlistAccordingToResponse(result,$this);
                },
                 
    });

}


function statusWishlistAccordingToResponse(result,$this) {
   switch(result.response_status){
       case 0:
          notification_msg(result.message);
          $("body").find('#wishlistCount').text(result.count);
          break;
       case 1:
          notification_msg(result.message,'success');
           $("body").find('#wishlistCount').text(result.count);
          break;
       case 3:
            $modal = $("body").find('#loginPopup')
            $modal.find('.innder-loader').show();
            $modal.find('input[name=activity_type]').val(result.activity_type);
            $modal.find('input[name=custom_id]').val(result.custom_id);

            $modal.modal({
                backdrop: 'static',
                keyboard: false
            });

            
            // $modal.find('.login-popup').html(result.list);
            // loadjs_for_popup();
          break;
   }
}






//===================================================================================================================
//===================================================================================================================

function loadjs_for_popup() {
       
       jQuery('#loginWithOtp').validate({
               rules: {
                     'email': 
                     {
                        required: true,
                        nowhitespace: true,
                        number: true,
                        minlength:10,
                        maxlength:10,
                     }  
                }, 
                messages: {
                      email: {
                          required: "Enter your mobile number.",
                          nowhitespace: "No white space.",
                          number:"Please enter digits only.",
                          minlength:"Please enter 10 digits.",
                          maxlength:"Please enter 10 digits."
                      } 
                }     
        });

        jQuery('#loginWithEmail').validate({
             rules: {
                 'email': 
                 {
                    required: true,
                    nowhitespace: true,
                    email:true
                 },
                 'password': 
                 {
                    required: true,
                    nowhitespace: true,
                 }
              }, 
            messages: {
                email: {
                    required: "Enter your email.",
                    nowhitespace: "No white space."
                }
            }     
         });


}

//===================================================================================================================
//===================================================================================================================

function loadOTPjs_for_popup() {

  $('.digit-group').find('input').each(function() {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function(e) {
        var parent = $($(this).parent());
        insertOptFromAll();
        if(e.keyCode === 8 || e.keyCode === 37) {
           var prev = parent.find('input#' + $(this).data('previous'));
           if(prev.length) {
              $(prev).select();
           }
        } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
           var next = parent.find('input#' + $(this).data('next'));
           if(next.length) {
               $(next).select();
           } else {
               if(parent.data('autosubmit')) {
                parent.submit();
               }
           }
       }
   });
 });

      // OTP Fields JS ends Here    

    

        







function insertOptFromAll() {
   var $digit1 = $("body").find('#digit1').val() !== NaN ? parseInt($("body").find('#digit1').val()) : '';
   var $digit2 = $("body").find('#digit2').val() !== NaN ? parseInt($("body").find('#digit2').val()) : '';
   var $digit3 = $("body").find('#digit3').val() !== NaN ? parseInt($("body").find('#digit3').val()) : '';
   var $digit4 = $("body").find('#digit4').val() !== NaN ? parseInt($("body").find('#digit4').val()) : '';
   var $digit5 = $("body").find('#digit5').val() !== NaN ? parseInt($("body").find('#digit5').val()) : '';
   var $digit6 = $("body").find('#digit6').val() !== NaN ? parseInt($("body").find('#digit6').val()) : '';
   var $val = $digit1+''+$digit2+''+$digit3+''+$digit4+''+$digit5+''+$digit6;
   $("body").find("#otp").val($val);
}


// load Timer
  function loadTimerCount() {
         $this = $("body").find('.login-popup');
         $.ajax({
                  url:$this.data('otp'),
                  type: 'GET',   
                  dataTYPE:'JSON',
                  headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                  },
                  beforeSend: function() {
                  },
                  success: function (result) {
                        dateObj = new Date(result.timer * 1000);
                        minutes = dateObj.getUTCMinutes();
                        seconds = dateObj.getSeconds();
                        timeString = minutes.toString().padStart(2, '0') + ':' + 
                        seconds.toString().padStart(2, '0');

                        if(result.status == 1){
                           $this.find("#counter").html(timeString);
                        }else{
                             $this.find("#counter").attr('data-count',1);
                             $this.find("#counter").html(0+' second');
                            // window.location.href ="{{route('auth.logout')}}";
                        }
                 },
            });
   }

   // This is to get timming
   setInterval(function() {
         if($("body").find("#counter").attr('data-count') == 0){
            loadTimerCount();
         }
   }, 1000);

}
//===================================================================================================================
//===================================================================================================================
//===================================================================================================================
//===================================================================================================================


$("body").on('click','.i_agree',function(){
    
      $("body").find('#i_agree').modal('show');
    
});

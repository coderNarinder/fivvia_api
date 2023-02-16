



// GET PLAN DATES ACCORDING TO CHOOSED ONE

$("body").on('change',"input[name=plan]",function(e){
    getscriptDates();
});

function getPlanDetail() {
    var $this = $("body").find('input[name=plan]:checked');
    var upgradePlan = $("body").find('input[name=upgradePlan]').is(':checked') ? 1 : 0;


    	  $.ajax({
                url : $this.attr('data-action'),
                type: 'GET',  
                data : {
                	plan_id:$this.val(),
                	upgradePlan:upgradePlan
                }, 
                dataTYPE:'JSON',
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                            
                },
                success: function (result) {
                     $("body").find('#loadPlanDetails').html(result.htm);
                },
                complete: function() {
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

         });

}

function getscriptDates() {
	getPlanDetail();
	 if (/Mobi/.test(navigator.userAgent)) {
  // if mobile device, use native pickers
	  $("body").find(".date input").attr("type", "date");
	  $("body").find(".time input").attr("type", "time");
	} else {
	  
	  // $("body").find("#datepicker, #datepicker2").datetimepicker({
	  //   useCurrent: false,
	  //   format: "L",
	  //   showTodayButton: true,
	  //   icons: {
	  //     next: "fa fa-chevron-right",
	  //     previous: "fa fa-chevron-left",
	  //     today: 'todayText',
	  //   }
	  // });
	  // $("body").find("#timepicker, #timepicker2, #timepicker3").datetimepicker({
	  //   format: "LT",
	  //   icons: {
	  //     up: "fa fa-chevron-up",
	  //     down: "fa fa-chevron-down"
	  //   }
	  // });
}
}



getscriptDates();



//-------------------------------------------------------------------------------------------------------------//
//--------------------------------------                     --------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------//





$("body").on('submit','form#userStepForm',function(e){
     e.preventDefault();
     var $this = $( this );
      
     userStepFormAjaxFunc($this);
 
});


//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------


function userStepFormAjaxFunc($this){
	      
          $loader = $("body").find('.content-loader');         
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
                       userStepFormAjaxFuncReturnResponse($this,result);
                },
                complete: function(result) {
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

         });
}


//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------
function notification_msg()
{
  toastr.options = {
                      "closeButton": false,
                      "debug": false,
                      "newestOnTop": false,
                      "progressBar": false,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "2000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                      }
}

function userStepFormAjaxFuncReturnResponse($this,result){
    notification_msg();
    $loader = $("body").find('.content-loader');      
	 switch(parseInt(result.status)) {
        case 0:
               putTheError($this,result.errors);
               $this.find('button').removeAttr('disabled');
	           $loader.hide();      
          break;
        case 1:
        
                   $this.find('.customError').hide();
	               //$this.find('.messageDiv').addClass('alert alert-success');
	               //$this.find('.messageDiv').html(result.messages);
                 
	               toastr.success(result.messages, {timeOut: 100});
                 window.location.href=result.url;
                   $loader.hide();
          break;
        case 3:
              
               $this.find('button').removeAttr('disabled');
               //$this.find('.messageMSG').html(result.messages);
               $loader.hide();
               toastr.error(result.messages, {timeOut: 100});      
               /*setTimeout(function(){
                 $this.find('.messageMSG').html('');
               },5000);*/
          break;
         
        default:
            
           
           break;
      }
 
}



function putTheError($this,errors) {
	 $this.find('.customError').hide();
	  $.each(errors, function( index, value ) {
        $this.find('#'+index+'-error').html(value).show();
        console.log(index);
          
      });

}


//========================================

$("body").on('change','.allUpdatedData',function(){
    
    var $id = $(this ).attr('data-id');
    var $this = $(this).closest('.Group-field-info');

    if($(this).is(':checked')){
        $this.addClass('hidden');
        $this.next('div').removeClass('hidden');
    } 

});

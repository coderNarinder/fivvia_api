// --------------------------------------------------------------------------------------------------------------------------------------
// Product multiple Images
// --------------------------------------------------------------------------------------------------------------------------------------



// --------------------------------------------------------------------------------------------------------------------------------------
// When user clicks on product variation
// --------------------------------------------------------------------------------------------------------------------------------------


jQuery("body").on('change','input.change-product-filter',function(e){
  var $this = $( this );
	var $form = jQuery("body").find('#loadNewProduct');
	var $url = $form.data('check-action');
    if($this.attr('name') == 'v_ID'){
      $("body").find('input[name=selected_variations]').val($this.data('value'));
    }
    $form.submit();
});



 
// --------------------------------------------------------------------------------------------------------------------------------------
// Review Form
// --------------------------------------------------------------------------------------------------------------------------------------
 

$('#commentform').validate({
   rules: {
        'review' : {
                     required: true
        },
        'rating' : {
                     required: true
        },
        'title' : {
                     required: true
        },
    },
});


$("body").on('submit','#commentform22',function(e){
  e.preventDefault();
  submit_question_product($(this));

});


function submit_question_product($this){
    $.ajax({
                url : $this.attr('data-action'),
                type: 'POST',  
                dataTYPE:'JSON',
                data:$this.serialize(),
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                    $this.find('button').attr('disabled','true');
                },
                success: function (result) {
                     //-------------------------------------------------------------
                     statusWishlistAccordingToResponses(result,$this);
                },
                 
    });

}
 



function statusWishlistAccordingToResponses(result,$this) {
   switch(result.response_status){
       case 0:
          notification_msg(result.message);
          $("body").find('#wishlistCount').text(result.count);
          break;
       case 1:
          notification_msg(result.message,'success');
           window.location.reload();
          break;
       case 3:
            $modal = $("body").find('#loginPopup')
            $modal.find('.innder-loader').show();
            $modal.find('input[name=activity_type]').val(result.activity_type);
            $modal.find('input[name=custom_id]').val(result.custom_id);
            $modal.find('input[name=question]').val(result.question);

            $modal.modal({
                backdrop: 'static',
                keyboard: false
            });
          break;
   }
}



// --------------------------------------------------------------------------------------------------------------------------------------
// Review Form
// --------------------------------------------------------------------------------------------------------------------------------------
 


$("body").on('submit','#commentform',function(e){
   e.preventDefault();
   var $this = $(this);
       


       var form = $this[0]; // You need to use standard javascript object here
       var formData = new FormData(form);
       var percent = $('body').find('.percent');
       var bar = $('.bar');
       
       $.ajax({
           url:$this.data('action'),
           method:"POST",
           data:formData,
           dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function() {
              $('body').find('.progress').show();
              $('.progress').find('span.sr-only').text('0%');
              $this.find("button[type='submit']").attr('disabled','true');
              $("body").find('.custom-loading').show();
              $this.find('button').attr('disabled','true');
           },
           xhr: function () {
             var xhr = new window.XMLHttpRequest();
             xhr.upload.addEventListener("progress", function (evt) {
              if (evt.lengthComputable) {
                  var percentComplete = evt.loaded / evt.total;
                   percentComplete = parseInt(percentComplete * 100);
                   $('.progress').find('span.sr-only').text(percentComplete + '%');
                   $('.progress .progress-bar').css('width', percentComplete + '%');
               }
           }, false);
              return xhr;
          },

           success:function(result)
           {
                      $this.find('#msgBox').html('<div class="alert alert-warning">'+result.message+'</div>');
                      if(result.status == 1){
                        setTimeout(function(){
                           window.location.reload();
                        },2000);

                      }else{
                           $this.find('button').removeAttr('disabled');
                      }
           }
 });

});




// --------------------------------------------------------------------------------------------------------------------------------------
// Show More item
// --------------------------------------------------------------------------------------------------------------------------------------



$("body").on('change','.filterMoreOption',function(){
     var $this = $(this);

     hideShowOptions($this);
});



function hideShowOptions($this) {
     var $target = $("body").find($this.data('target'));
     if($this.is(':checked')){
         $target.show();
     }else{
         $target.hide();
     }
}



// --------------------------------------------------------------------------------------------------------------------------------------
// Rating Star event
// --------------------------------------------------------------------------------------------------------------------------------------
 
$("body").on('change','.my-custom-rating',function(){
     var $this = $(this);

     hideShowOptions($this);
});



function hideShowOptions($this) {
     var $val = parseInt($this.val());
     $("body").find('.my-custom-rate label').removeClass('selected');
     for (var i = 1; i <= $val; i++) {
     $("body").find('.label-star-'+i).addClass('selected');   
    }
}

 
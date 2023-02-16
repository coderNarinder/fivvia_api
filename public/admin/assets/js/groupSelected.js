//===========================================================================================
//Notification messages
//===========================================================================================


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



$("body").on('click','#GroupSelected',function(e){
  e.preventDefault();
  var $modal = $("body").find('#myselectedGroup');
  $modal.modal({
       keyboard: false,
       backdrop: 'static',
       show:true
      });
});
   


$("body").on('click','.choosedGroup',function(){
         var $this = $( this );
         
          $.ajax({
                url : $this.attr('data-action'),
                type: 'GET',  
                //data : $this.serialize(), 
                dataTYPE:'JSON',
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                           $this.find('button').attr('disabled','true');
                },
                success: function (result) {
                     //-------------------------------------------------------------
                     if(result.status == 1){
                       // alert(result.messages);
                        toastr.success(result.messages, {timeOut: 100});
                        setInterval(function(){ window.location.reload(); }, 300);
                        
                     }
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
});
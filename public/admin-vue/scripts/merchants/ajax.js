
$("body").on('click','.deleteAttribute',function(e){
      e.preventDefault();
      var $this = $(this);
   
       swal({
              title: "Are you sure?",
              text: "Once deleted, you will not be able to recover this data!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
        .then((willDelete) => {
            if (willDelete) {
                 window.location.href = $this.data('href');
               } 
              })
    });

//--------------------------------------------------------------------------------------------------
//   submit form with ajax function
//--------------------------------------------------------------------------------------------------

function submitForm($this) {
            var form = $this[0]; // You need to use standard javascript object here
            var formData = new FormData(form);
            var percent = $('body').find('.percent');
            var bar = $('.bar');
            $loader = $("body").find('.custom-loading');

            $.ajax({
                url: $this.data('action'),
                method: "POST",
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('body').find('.progress').show();
                    $('.progress').find('span.sr-only').text('0%');
                     $this.find('button').attr('disabled','true');
                     $loader.show();

                },
                xhr: function() {

                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                          
                        }
                    }, false);
                    return xhr;
                },
                success: function(result) {
                   $loader.hide();
                   if(result.status == 1){
                        notification_msg(result.messages,'success');
                         window.location.href = result.url;
                        $this.find('button').removeAttr('disabled');
                   }else if(result.status == 2){ 
                        notification_msg(result.messages);
                        $this.find('button').removeAttr('disabled');
                   }else{
                       putTheError($this,result.errors);
                        $this.find('button').removeAttr('disabled');
                   }
                }

            });
}


//--------------------------------------------------------------------------------------------------
//   submit form with ajax function
//--------------------------------------------------------------------------------------------------


function putTheError($this,errors) {
   $this.find('.customError').hide();
    $.each(errors, function( index, value ) {
    	notification_msg(value);
         $this.find('#'+index+'-error').html(value).show();
        // console.log(index);
    });
}


//--------------------------------------------------------------------------------------------------
//   submit form with ajax function
//--------------------------------------------------------------------------------------------------

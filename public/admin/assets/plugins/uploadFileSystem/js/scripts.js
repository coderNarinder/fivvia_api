


//-------------------------------------------------------------------------
// this function is used for modal opening
//-------------------------------------------------------------------------


$("body").on('click','.chooseLocalFile',function(e){

  var target = $(this).attr("data-target");
  $("body").find('#uploadFileModal').attr("data-target",target)
    e.preventDefault();
             var $modal = $("body").find('#uploadFileModal');
             $modal.modal({
                        backdrop: 'static',
                        keyboard: false
              });

             $modal.find('#fileInputUploading').val($(this).attr('data-target'));

});


//-------------------------------------------------------------------------
// upload files
//-------------------------------------------------------------------------



 $('body').on('change','#select_file', function(e){
 	   	var fileExtension = ['jpeg', 'jpg', 'png','JPEG','JPG','PNG'];
if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
//alert("Only '.jpeg','.jpg', '.png', '.gif', '.bmp' formats are allowed.");
$('html')[0].lang == 'en' ? error_message = 'Only jpeg,jpg, png are allowed.' : error_message = 'يسمح فقط jpeg ، jpg ، png.' ;
$('body').find('.type-error').text(error_message);
}
else
{
		$('body').find('.type-error').text('');
 	    var $this = $('body').find('#upload_form');
        var form = $('body').find('#upload_form')[0]; // You need to use standard javascript object here

        var formData = new FormData(form);
        var percent = $('body').find('.percent');
        var bar = $('.bar');
 
        $.ajax({
           url: $this.attr('data-action'),
           method:"POST",
           data:formData,
           dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function() {
            
               $('body').find('.progress').show();
               $('.progress').find('span.sr-only').text('0%');

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
           success:function(data)
           {
            $('#globalMessages').html('');
            console.log(data.html);
            if(data.success ==true){
                  $('body').find('.progress').hide();
                  $('.progress').find('span.sr-only').text('0%');
                   $('.progress .progress-bar').css('width','0%');
                    form.reset();

                    var $newTrigger = $("body").find('.file-panel-trigger.step-two');
                    afterFileUploading($newTrigger);

                

            }else{
                $('body').find('.progress').hide();
                  $('.progress').find('span.sr-only').text('0%');
                   $('.progress .progress-bar').css('width','0%');
                $('body').find('#globalMessages').css('display', 'block');

                 $('#globalMessages').html(data.message);

        

            }

           }

          });
 }         
    });


//########################################################################################################################


	$("body").on('click','.chooseFilePluginFromLocal',function(e){
	    e.preventDefault();
      target = $(this).parent('#uploadFileModal').attr('data-target');
	    var $this = $("body").find('#uploadFileModal');
	    var $modal = $this.find('#fileInputUploading').val();
	    var $val = $("body").find('input[name=chooseFile]:checked').val();
        var $input = $("body").find($modal);
         //$(target).next('input[name=filename]').val($val);
         //$(target).closest('.form-group').find(".upload_img_preview").attr("src",'/'+$val);
        var $url = $("body").find('input[name=myCustomUrl]').val();
        var $uploadFileParentDiv = $input.closest('.upload-box-wrap');

        //$uploadFileParentDiv.find('input[name=filename]').val($val);
        $uploadFileParentDiv.find(".upload_img_preview").attr("src",$url+'/'+$val);
        
        $input.val($val);
        $this.modal('hide');
        $uploadFileParentDiv.find(".remove_current_img").show();
        $uploadFileParentDiv.find(".chooseLocalFile").hide();
        var form_id = $input.closest('form').attr('id');
        $("#"+form_id).valid();
	});


//########################################################################################################################
 


function getList() {

     var $this = $("body").find('.file-panel-trigger.active');
     $("body").find('.item-panl').each(function(){
  	       $(this).removeClass('active');
  	 });
  	 $($this.attr('data-id')).addClass('active');
  	  getAllFileList();
}

function getAllFileList() {
	   var $this = $("body").find('.file-panel-trigger.active');
		  if($this.attr('data-id') === '#loadAllUploadedFiles'){
			 loadLocalFileListing();
		 }
}

getAllFileList();


$("body").on('click','.page-item .page-link',function(e){
   e.preventDefault();
   var $url = $( this ).attr('href');
   loadLocalFileListing($url);
});







function loadLocalFileListing($url=0){
	 var $this = $("body").find('#loadAllUploadedFiles');

   var url = $url == 0 ? $this.attr('data-action') : $url;
	 $.ajax({
                url : url,
                type: 'GET',  
                dataTYPE:'JSON',
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                        
                },
                success: function (result) {
                      $this.html(result);
                },
                complete: function() {
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

    });
}
 

$("body").on('click','.file-panel-trigger',function(e){
   e.preventDefault();

   $("body").find('.file-panel-trigger').each(function(){
      $(this).removeClass('active');
    });
    $(this).addClass('active');
     getList();

});


 
function afterFileUploading($this) {
	$("body").find('.file-panel-trigger').each(function(){
      $(this).removeClass('active');
    });
    $this.addClass('active');
     getList();
}


$("body").on('click','.delete_upload',function(e){
   e.preventDefault();
    var $this = $(this);

   var url = $this.attr('data-action');
   var id = $this.attr('data-file');
   
   $.ajax({
                url : url,
                type: 'GET',  
                dataTYPE:'JSON',
                
            /*    headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },*/
                beforeSend: function() {
                        
                },
                success: function (result) {
                      //$this.html(result);
                      getAllFileList();
                },
                complete: function() {
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

    });

});



$("body").on('click','.remove_current_img',function(e){
   if($('html')[0].lang == 'en')
   {
    var $title = "Are you sure?";
    var $text = "Want to remove the image.";
    var $button = ["NO", "Yes"];
   }
   else
   {
    var $title = "هل أنت واثق؟";
    var $text = "تريد إزالة الصورة.";
    var $button = ["لا", "نعم"];
   }
   e.preventDefault();
   //img = $(this).attr('data-img');
   //name = $(this).attr('data-name');
   swal({
  title: $title,
  text: $text,
  icon: "warning",
  buttons: true,
   buttons: $button,
  dangerMode: true,
  })
  .then((willDelete) => {
  if (willDelete) {
   $(this).closest(".upload-box-wrap").find(".upload_img_preview").attr("src",'');
   $(this).closest(".cstm-upload-file-grp").find(".logoFile").val('');
   //$(this).closest(".cstm-upload-file-grp").find('input[name="filename"]').val('');
   $(this).parent().find(".remove_current_img").hide();
   $(this).parent().find(".chooseLocalFile").show();  
   } 
});

});


if($("body").find('.upload_img_preview').attr('src')=='')
{
  $("body").find(".remove_current_img").hide();
}
else
{
 $("body").find(".chooseLocalFile").hide(); 
}


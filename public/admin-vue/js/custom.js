$(document).ready(function() {
	if($(window).width() <=1199) {
		$(".custom-toggle").click(function(){
			$("body").toggleClass("open-sidebar-mobile");
		})
		$(".logo svg").click(function(){
			$("body").removeClass("open-sidebar-mobile");
		})
	}
	if($(window).width() >=1200) {
		$(".custom-toggle").click(function(){
			$("body").toggleClass("close-sidebar");
		})
	}
	$(".change-theme-mode").click(function(){
		$("body").toggleClass("theme-dark");
	})
	
	// custom input file
	
	$(".fileInput").on('change',function (e) {
            var labelVal = $(".title").text();
            var oldfileName = $(this).val();
                fileName = e.target.value.split( '\\' ).pop();

            if(fileName ){
                if (fileName.length > 100){
                    $(".filelabel .title").text(fileName.slice(0,40)+'...'+extension);
                }
                else{
                    $(".filelabel .title").text(fileName);
                }
            }
            else{
                $(".filelabel .title").text(labelVal);
            }
        });
	
	// custom input file
	
	//settings sidebar
	
	$(".icon-settings").click(function(){
		$("body").addClass("open-settings-sidebar");
	});
	$(".s-s-heading a").click(function(){
		$("body").removeClass("open-settings-sidebar");
	});
	
	//settings sidebar
});


$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

function notification_msg($msg,$type='error')
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

    if($type == 'error'){
      toastr.error($msg, {timeOut: 1500}); 
    }else{
      toastr.success($msg, {timeOut: 1500}); 
    }


}
     




// jQuery.validator.addMethod("lettersonly", function(value, element) {
//   return this.optional(element) || /^[a-z\s]+$/i.test(value);
// }, "Letters only please"); 

//============================================================================================


// jQuery.validator.addMethod("LettersOnly", function(value, element) 
// {
// return this.optional(element) || /^[a-z," "]+$/i.test(value);
// }, "Letters and spaces only please"); 




//============================================================================================


// jQuery.validator.addMethod("alphanumeric", function(value, element) {
//   return this.optional(element) || /^[a-z0-9\s]+$/i.test(value);
// }, "Letters and digits only please"); 

//============================================================================================



function addServerErrors(errors) {

   $.each(errors,function(id,message){
         var $label = $("body").find('#'+id+'-error');
             $label.css("display", "block");
             $label.text(message);
   });
}



$("body").on('click','.confirmDelete',function(e){
 
      e.preventDefault();
   
      var $this = $(this);
    //  let _url = "http://sayaansh.com/merchant/delete/attribute";
    //  let _token   = $('meta[name="csrf-token"]').attr('content');

       swal({
              title: "Are you sure?",
              text: "Once deleted, you will not be able to recover this data!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
        .then((willDelete) => {
            if (willDelete) {
                 window.location.href = $this.attr('href');
               } 
              })
 });


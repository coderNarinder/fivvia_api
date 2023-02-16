





// edit popup for group


$("body").on('click','.popup-add-sub-group',function(e){
   e.preventDefault();
   var $this = $( this );
   var $modal = $("body").find('#mySubGroupEditModal');

   $modal.find('.modal-title').html($this.attr('data-label'));
   $modal.find('#group_id').val($this.attr('data-id'));
   $('#mySubGroupEditModal').modal({
         backdrop: 'static',
         keyboard: false
   });
  //submitAjaxSubGroup($this);

   

});



// edit popup for group


$("body").on('click','.group-popup-edit',function(e){
   e.preventDefault();
   var $this = $( this );
   var $modal = $("body").find('#myGroupEditModal');

   $modal.find('.modal-title').html($this.attr('data-label'));
   $('#myGroupEditModal').modal({
         backdrop: 'static',
         keyboard: false
   });
   gettingDataInPopup($this);

   

});


//===========================================================================================


function gettingDataInPopup($this) {
	 $loader = $("body").find('.content-loader');
	 var $modal = $("body").find('#myGroupEditModal');
 
     	  $.ajax({
                url : $this.attr('data-action'),
                type: 'GET',  
                dataTYPE:'JSON',
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                   $loader.show();
                            
                },
                success: function (result) {
                     $modal.find('.modal-body').html(result.htm);
                },
                complete: function() {
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

         });
}


//===========================================================================================


$("body").on('change','.mySubGroupToggle',function(){
  
 // if($(this).is(':checked')){
 // 	 //$("body").find('#number_of_group').removeClass('hidden');
 //     $("body").find('#number_of_member').addClass('hidden');
 // }else{
 // 	 $("body").find('#number_of_group').addClass('hidden');
 //     $("body").find('#number_of_member').removeClass('hidden');
 // }


});


//===========================================================================================




$("body").on('submit','#formGroupUpdation',function(e){
  e.preventDefault();
  var $this = $( this );
  submitAjaxSubGroup($this);

});

//===========================================================================================
//===========================================================================================




$("body").on('click','.myGroupDeleteBtn',function(e){
   if($('html')[0].lang == 'en')
   {
    var $title = "Are you sure?";
    var $text = "Want to delete the record.";
    var $button = ["NO", "Yes"];
   }
   else
   {
    var $title = "هل أنت واثق؟";
    var $text = "تريد حذف السجل.";
    var $button = ["لا", "نعم"];
   }
  e.preventDefault();
  var $this = $( this );
  //alert(1);
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
    deleteAjaxSubGroup($this);
  } 
});
  
  
});

//===========================================================================================


function deleteAjaxSubGroup($this) {
   $loader = $("body").find('.content-loader');
   $.ajax({
                url : $this.attr('data-action'),
                type: 'GET',  
                dataTYPE:'JSON',
                // data:$this.serialize(),
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                   $loader.show();
                },
                success: function (result) {
                     if(result.status == 1){
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
                      $("#myGroupEditModal").modal('hide');
                     
                      toastr.success(result.messages, {timeOut: 100});
                    
                      setInterval(function(){ window.location.reload(); }, 1000);

                      
                     }
                },
                complete: function() {
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

         });
}

function submitAjaxSubGroup($this) {
	 $loader = $("body").find('.content-loader');
	 $.ajax({
                url : $this.attr('data-action'),
                type: 'POST',  
                dataTYPE:'JSON',
                data:$this.serialize(),
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                   $loader.show();
                },
                success: function (result) {
                     if(result.status == 1){
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
                      $("#myGroupEditModal").modal('hide');
                      if($('html')[0].lang =='en'){
                      toastr.success('Updated sucessfully!', {timeOut: 100});
                    }else if($('html')[0].lang =='ar'){
                      toastr.success('تم التحديث بنجاح!', {timeOut: 100});
                    }
                      setInterval(function(){ window.location.reload(); }, 1000);

                     	
                     }
                },
                complete: function() {
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

         });
}













$(function() { 

 $loader = $("body").data('loader');

 jQuery("form[name=editCategory]").validate({
    rules: {
      'parent': 
      {
        required: true,
      },
      'label': 
      {
        required: true,
        maxlength: 150
      }, 
      'link': 
      {
        required: true
      }, 
       
       
       
    }, 
        
});
 jQuery("form[name=addCategory]").validate({
    rules: {
      'parent': 
      {
        required: true,
      },
      'label': 
      {
        required: true,
        maxlength: 150
      }, 
      'link': 
      {
        required: true
      }, 
       
       
    }, 
        
});



























   
    var dataTable = $('#example2').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        //searching:false,
        "fnDrawCallback": function(oSettings) {
                if (oSettings._iDisplayLength == -1
                    || oSettings._iDisplayLength > oSettings.fnRecordsDisplay())
                {
                    jQuery(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                } else {
                    jQuery(oSettings.nTableWrapper).find('.dataTables_paginate').show();
                }
                 $dataTables_filter = $("body").find(".dataTables_filter");
                 $search = $dataTables_filter.find("input[type='search']");
                 $search.attr("id", "searchBox");
                 $search.attr("class", "form-control");
                 $search.closest('label').addClass('f-c-with-icon').append('<span class="material-icons">search</span>');
                 
                 $dataTables_length = $("body").find('.dataTables_length');
                 $dataTables_length.addClass('entries-count');
                 $dataTables_length.find('select').addClass('form-control');
 
        },
        "language": {
            sLengthMenu: "<div class='table-options d-flex flex-wrap'>_MENU_",
            "search": "_INPUT_",            // Removes the 'Search' field label
            "searchPlaceholder": "Search",   // Placeholder for the search box
              
        },
         
        oLanguage: {sProcessing: "<div id='loader' class='table-loader'><img src='"+$loader+"'></div>"},
        ajax: $('#example2').data('action'),
        //"dom":' <"search"f><"top"l>rt<"bottom"ip><"clear">',
        columns: [
             { data: 'category', name: 'category',"bSortable":true},
             { data: 'label', name: 'label',"bSortable":true},
            
             { data: 'status', name: 'status',searchable:false,"bSortable": false, render: function ( data, type, row ){

                      $text  ='<div class="checkbox switcher">';
                      $text +='<label for="test'+data.id+'">';
                      $text +='<input type="checkbox" class="changeStatusToggle" id="test'+data.id+'" value="" '+data.checked+' data-action="'+data.url+'">';
                      $text +='<span><small></small></span>';
                      $text +='<small>'+data.label+'</small>';
                      $text +='</label>';
                      $text +='</div> ';
                      return $text;
                } 
             },
             { data: 'action', name: 'action',searchable:false,"bSortable": false },
           ]
    });

    $("body").on( 'change','.changeStatusToggle', function () {
          var $this = $( this );
          $.ajax({
               url: $this.data('action'),
               method:"GET",
               dataType:'JSON',
               success:function(data)
               {
                 if(data.status == 1){
                      notification_msg(data.message,'success');
                      dataTable.draw();
                 }else{
                   notification_msg(data.message);
                 }
                 
               }
          });
    });
});







//--------------------------------------------------------------------------------------
 // category load function
 //--------------------------------------------------------------------------------------

  $('#parent').on('change',function(){
      var val = $( this ).val();
      $("#subparent").html('<option value="0">select</option>');
      getSubCategoryByCategoryId();
  });


 //--------------------------------------------------------------------------------------
 // category load function
 //--------------------------------------------------------------------------------------


  function getSubCategoryByCategoryId() {
      var $url = $("body").find('#category_url').val();
      var val = $('select#parent option:selected').val();
       var parent = parseInt(val) == 0 ? null : val;
      $.ajax({
          url: $url,
          data:{
              'parent': parent,
              'subparent':0
          },
          dataTYPE: 'json',
          success: function(result){
               var text ='<option value="0">select</option>';
               $.each(result, function( index, key ) {
                   text +='<option value="'+key.id+'">'+key.label+'</option>';
               });
               $("#subparent").html(text);
          }
        });
  }


  $("body").on('submit','#categoryForm2',function(e){
   e.preventDefault();
   upload_form_file($(this));
 });



 function upload_form_file($this) {
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
                  
                if(parseInt(data.status) == 1){
                 
                     form.reset();
                     notification_msg(data.message,'success');
                    // $this.find("button[type='submit']").removeAttr('disabled');
                     $("body").find('.custom-loading').hide();
                       
                     window.location.href = data.url;

                     return true;
                  }else if(parseInt(data.status) == 5){
                 
                     form.reset();
                     notification_msg(data.message,'success');
                    // $this.find("button[type='submit']").removeAttr('disabled');
                     $("body").find('.custom-loading').hide();
                      window.location.reload();

                     return true;
                  }else if(parseInt(data.status) == 2){
                     
                     notification_msg(data.message);
                     $this.find('button').removeAttr('disabled');
                     $("body").find('.custom-loading').hide();
                     
                 }else{
                     putErrorsToLabel($this,data.errors);
                     $this.find('button').removeAttr('disabled');
                     $("body").find('.custom-loading').hide();
 
                 }
  
             }



          });

}


function putErrorsToLabel($this,errors) {
  $.each(errors,function(key,value){
    console.log('#'+key+'-error');
     $this.find('#'+key+'-error').text(value);
  });
}
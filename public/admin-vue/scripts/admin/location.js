$(function() { 

 $loader = $("body").data('loader');

 jQuery("form[name=editCategory]").validate({
    rules: {
      'label': 
      {
        required: true,
      } 
    }, 
        
});

 
 jQuery("form[name=addCategory]").validate({
    rules: {
      
      'label': 
      {
        required: true,
      } 
       
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
             { data: 'location', name: 'location',"bSortable": true  },
             { data: 'label', name: 'label', sortable: 'true',"bSortable": true },
             { data: 'pincode', name: 'pincode',"bSortable": true },
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
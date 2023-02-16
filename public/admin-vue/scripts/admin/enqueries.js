$(function() { 

 $loader = $("body").data('loader');
 
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
             { data: 'first_name', first_name: 'first_name',"bSortable":true},
             { data: 'last_name', last_name: 'last_name',"bSortable":true},
             { data: 'email', email: 'email',"bSortable":true},
             { data: 'phone', phone: 'phone',"bSortable":true},
             { data: 'message', message: 'message',"bSortable":true},
              
             // { data: 'action', name: 'action',searchable:false,"bSortable": false },
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
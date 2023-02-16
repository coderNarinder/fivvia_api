$(function() { 

  // **********Ck editor script*************
   // CKEDITOR.replace( 'subject' );

 $loader = $("body").data('loader');

$('#categoryForm').validate({
      
      rules: {
          "tax_code": { 
              required: true,
              maxlength: 50,
          },
          "percentage": { 
              required: true,
              number: true,
              maxlength: 10,
          },
          valueToBeTested: {
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
      
        columns: [
             { data: 'tax_code', name: 'tax_code' },   
             { data: 'percentage', name: 'percentage' },
          
             { data: 'action', name: 'action',searchable:false,"bSortable": false },

           ]
    });

 
});
$(document).ready(function(){
 
$loader = $("body").data('loader');


  var dataTable = $('#example2').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
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
      
        ajax: {
                "url":$('#example2').data('action'),
                "type": "GET",
                "data": function (d) {
                           d.document_type = $("body").find('#document_type').val();  
                           d.brand_status = $("body").find('#brand_status').val();  
                          
                         // d.category = $("body").find('#category').val();  
                },
        }, 
        columns: [
         
             { data: 'brand_logo', name: 'brand_logo',searchable:false,"bSortable": false, render: function ( data, type, row ){
                       
                      $text ='<img src="'+data+'" class="img-thumbnail" style="width:50px !important;">';
                     
                      return $text;
                } 
             },        
             { data: 'brand_name', name: 'brand_name'},            
             { data: 'document_type', name: 'document_type',"bSortable": false},            
             { data: 'brand_owner', name: 'brand_owner',"bSortable": false},            
             { data: 'vendor_info', name: 'vendor_info',"bSortable": false},            
             { data: 'status', name: 'status',searchable:false,"bSortable": false, render: function ( data, type, row ){
                       switch(parseInt(data)) {
                               case 0:
                                $text ='<span class="badge badge-primary">Pending</span>';
                                break;
                               case 1:
                                $text ='<span class="badge badge-success">Approved</span>';
                                break;
                               case 2:
                                $text ='<span class="badge badge-danger">Rejected</span>';
                                break;
                               case 3:
                                $text ='<span class="badge badge-info">Resubmitted</span>';
                                break;
                              default:
                                 $text ='<span class="badge badge-warning">Pending</span>';
                            }
                      
                     
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


 $("body").on('click','#advanceSearch',function(){
       dataTable.draw();
  });

  
$("body").on('click','.btn-block-user',function(e){
     e.preventDefault();
     var $this = $( this );
      $.ajax({
           url: $this.attr('href'),
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








 

$(document).on('change','#country',function(){
  var $region = $(this).val();
  getLocations($region,0,'#state');
});
 
$(document).on('blur','#state',function(){
  var $region = $('select[name=country]').val();
  var $state = $(this).val();
  getLocations($region,$state,'#city');
});
 

function getLocations($parent=0,$subparent=0,$id) {
   var $div = $("body").find($id);
   var $locationUrl = $("body").find('#locationUrl').val();
   $.ajax({
           url: $locationUrl,
           method:"GET",
           data:{
            parent:$parent,
            subparent:$subparent,
           },
           dataType:'JSON',
           success:function(data)
           {
             $div.html(data);
             if(parseInt($subparent) > 0){
              $div.select2();
             }
             
           }
         });
}
 

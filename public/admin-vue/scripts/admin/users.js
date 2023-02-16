$(document).ready(function(){
 
$loader = '';


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
                         d.country = $("body").find('#country').val();  
                         d.state = $("body").find('#state').val();  
                         d.city = $("body").find('#city').val();  
                         d.category = $("body").find('#category').val();  
                },
        }, 
        columns: [
              { data: 'profile_picture', name: 'profile_picture',"orderable" : false,"orderSequence": 'DESC', render: function ( data, type, row ){
                          let text ='';
                          text = '<img src="'+data+'" class="img-thumbnail" style="width:50px">';
                           
                        return text;
               } 
              },


             { data: 'name', name: 'name'},           
             { data: 'email', name: 'email'},           
             { data: 'phone_number', name: 'phone_number'},
             

             // { data: 'title', name: 'title'},            
             // { data: 'locations', name: 'locations',"bSortable": false}, 

             // { data: 'gender', name: 'gender',"bSortable": false},            
             // { data: 'dob', name: 'dob',"bSortable": false},            
             // { data: 'pan_number', name: 'pan_number',"bSortable": false},            
             // { data: 'gst_number', name: 'gst_number',"bSortable": false},            
             { data: 'created_at', name: 'created_at',"bSortable": false},            
             // { data: 'categories', name: 'categories',"bSortable": false,render: function ( data, type, row ){
             //            let text ='';
             //               $.each(data,function(index,val){
             //                    text += '<span class="badge badge-warning">'+val+'</span>';
             //               });
             //            return text;
             //   } 
             // },            
             // // { data: 'name', name: 'name'},            
             // // { data: 'email', name: 'email',"bSortable": false },            
                        
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


/* Cstm Validations */
const $validate_Urlss = $("body").attr('data-validateUrl');

 

jQuery.validator.addMethod("LettersOnly", function(value, element) 
{
return this.optional(element) || /^[a-z," "]+$/i.test(value);
}, "Letters and spaces only please"); 

 
jQuery("form[name=addCategory]").validate({
    rules: {

      'email': 
      {
        required: true,
        // nowhitespace: true,
        email: true,
        remote: $validate_Urlss
      },
      'name': 
      {
        required: true,
        // LettersOnly:true,
        maxlength:30,
      }, 
      'terms_and_conditions': 
      {
        required: true
        
      }, 
      // 'last_name': 
      // {
      //   required: true,
      //   maxlength:50,
      //   nowhitespace: true,
      // },
      'phone_number': 
      {
        required: true,
        number: true,
        minlength:10,
        maxlength:10,
        remote: $validate_Urlss
      },
      'password': 
      {
        required: true,
        minlength:8,
        maxlength:30,
        nowhitespace: true,

      },
       
   
    }, 
    messages:{
       email:{
           remote:'Email already exists.'
       },
        message:{
           required:'Please complete the reCAPTCHA challenge!'
       },
       phone_number:{
           remote:'Mobile number already exists.'
       }
    }     
});



jQuery("form[name=addCategoryEdit]").validate({
    rules: {

      'email': 
      {
        required: true,
        // nowhitespace: true,
        email: true,
        remote: $validate_Urlss
      },
      'name': 
      {
        required: true,
        // LettersOnly:true,
        maxlength:30,
      }, 
      'terms_and_conditions': 
      {
        required: true
        
      }, 
      // 'last_name': 
      // {
      //   required: true,
      //   maxlength:50,
      //   nowhitespace: true,
      // },
      'phone_number': 
      {
        required: true,
        number: true,
        minlength:10,
        maxlength:10,
        remote: $validate_Urlss
      },
      'password': 
      {
      //  required: true,
        minlength:8,
        maxlength:30,
        nowhitespace: true,

      },
       
   
    }, 
    messages:{
       email:{
           remote:'Email already exists.'
       },
        message:{
           required:'Please complete the reCAPTCHA challenge!'
       },
       phone_number:{
           remote:'Mobile number already exists.'
       }
    }     
});





//-----------------------------------------------------------------------------------------------
//  password-show
//-----------------------------------------------------------------------------------------------

$("body").on('click','.password-show',function(e){
   e.preventDefault();
    var $this = $(this);
    var $change = $this.attr('data-change');

    if($change == "text"){
      $this.attr('data-change','password');
      $this.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
    }else{
      $this.attr('data-change','text');
      $this.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
    }
    $("body").find($this.data('target')).attr('type',$change);

});

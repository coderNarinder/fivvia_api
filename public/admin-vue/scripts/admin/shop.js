





$("body").on('change','select[name=status]',function(){
   getProductListings();
});




$("body").on('change','select[name=record_limit]',function(){
   getProductListings();
});



$("body").on('keyup','input[name=search_product]',function(){
   getProductListings();
});



$("body").on('click','.move-draft-product',function(){
   $this = $(this);
   var $loader = $("body").find('.loader');
   $.ajax({
                url : $this.data('action'),
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
                    // $("body").find('#getProductListings').html(result.list);
                    alert(result.messages);
                    if(result.status == 1){
                      getProductListings();
                    }
                },
                complete: function() {
                       $loader.hide();    
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

    });
});





getProductListings();
function getProductListings() {
 
  var $this = $("body").find('#tableFilter');
      $url = $this.data('action');
      shop_product_ajax_func($this,$url);
}


$("body").on('click','#getProductListings .pagination .page-item .page-link',function(e){
e.preventDefault();
 var  $this = $("body").find('#tableFilter');
      $url = $(this).attr('href');
      shop_product_ajax_func($this,$url);
});


function shop_product_ajax_func($this,$url) {
   var $loader = $("body").find('.loader');
 $.ajax({
                url : $url,
                type: 'GET',  
                dataTYPE:'JSON',
                data:$this.serialize(),
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                       $loader.show();    
                },
                success: function (result) {
                    $("body").find('#getProductListings').html(result.list);
                },
                complete: function() {
                       $loader.hide();    
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

    });
}
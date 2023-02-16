
$("body").on('click','#buy-now',function(e){
 e.preventDefault();
  $this = $("body").find('form#addToCartForm');
  $this.find('#event_type').val(1);
 
  $this.submit();
});
$("body").on('click','#cart-item-btn',function(e){
 e.preventDefault();
  $this = $("body").find('form#addToCartForm');
  $this.find('#event_type').val(0);
   
    $this.submit();
});



$("body").on('submit','#addToCartForm',function(e){
 e.preventDefault();
  $(this).validate();
  $this = $(this);
  var $status = 0;
  $(this).find('input').each(function() {
     $alert = $("body").find($(this).data('id'));
     console.log($alert);
     if($(this).val() == ''){
       $alert.show();
       $status = 1;
     }else{
       $alert.hide();
     }
  });

  if(parseInt($status) == 0){
  	sendAjaxRequest($this);
  }

});






function disable_and_enable_cart_buttons() {
    $this = $("body").find('form#addToCartForm');
    var $status = 0;
    $this.find('input').each(function() {
       if($(this).val() == ''){
          $status = 1;
       } 
    });
    //console.log('check cart btn: '+ $this.html());
    if(parseInt($status) == 1){
        $this.find('button').attr('disabled','true');
    }
}
setTimeout(function(){
   disable_and_enable_cart_buttons();
},3000);


function sendAjaxRequest($this) {
	 $.ajax({
                url:$this.data('action'),
                method: "POST",
                data:$this.serialize(),
                dataType: 'JSON',
                beforeSend: function() {
                    $this.find('.innder-loader').show();
                    $this.find('button').attr('disabled','true');
                },
                success: function(data) {

                	 
                	if(data.status == 1){
                      notification_msg(data.message,'success');
                      setTimeout(function(){ window.location.href= data.url; }, 2000);
                       
                	}else{
                    notification_msg(data.message);
                        $this.find('button').removeAttr('disabled');
                	}
                	  
                }
    });
}




















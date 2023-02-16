
$("body").on('change','#gst-check',function(e){
 e.preventDefault();
 var $this = $( this ); 
 if($this.is(':checked')){
 
     $("body").find('#GSTMODAL').modal('show');
 }else{
 	$form = $("form[name=GSTFORM]");
 	$modal = $("body").find('.gst-block');
	 $.ajax({
	                url:$form.data('action'),
	                method: "GET",
	                data:{
	                	gst_status : 0
	                },
	                dataType: 'JSON',
	                beforeSend: function() {
	                     $modal.find('.innder-loader').show();
	                },
	                success: function(data) { 
	                      if(data.status == 1){
	                        $modal.append(data.messages);
	                        setTimeout(function(){ 
	                           window.location.reload();
	                        },1000);
	                       }
	                          $modal.find('.innder-loader').hide();
	                }
	    });
 }
    
});







 const VALIDATERULES_FOR_GSTFORM =  {
          rules: {
            'business_name':{
              required: true,
              maxlength:50,
            }, 
            'gst_number':{
              required: true,
              minlength:10,
              maxlength:15,
              alphanumeric: true,
              
            }  
              
          }      
  };


   $("body").find("form[name=GSTFORM]").validate(VALIDATERULES_FOR_GSTFORM);




   $("body").on('submit','form[name=GSTFORM]',function(e){
   e.preventDefault();
        var $form = $(this);
        var $modal = $("body").find('#GSTMODAL')
        if($form.valid()){
		   $.ajax({
		                url:$form.data('action'),
		                method: "POST",
		                data:$form.serialize(),
		                dataType: 'JSON',
		                beforeSend: function() {
		                     $modal.find('.innder-loader').show();
		                },
		                success: function(data) { 
		                      if(data.status == 1){
		                        $form.html(data.messages);
		                        setTimeout(function(){ 
		                           window.location.reload();
		                        },1000);
		                       }
		                          $modal.find('.innder-loader').hide();
		                }
		    });
		}
});


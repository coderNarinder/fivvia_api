
  

$("body").on('change','.product_qc_problems',function(){
  
   var $this = $(this);
   var $id = 'tr-'+$this.val();
   var $btn = $("body").find('#btnToGiveError');
   var $table = $("body").find('#palceTextboxes');
   var $text ='';
    
   if($this.is(':checked')){
         $text +='<tr id="'+$id+'">';
         $text +='<td>';
         $key = parseInt($this.data('variation-value')) == 0 ? $this.val() : '<img src="'+$this.data('variation-url')+'" style="width:100px">';
         $text += $key;
         $text +='</td>';
         $text +='<td>';
         $text +='<textarea class="form-control" name="'+$this.val()+'" required="">'+$this.data('value')+'</textarea>';
         $text +='</td>';
         $text +='</tr>';
         $table.append($text);
   }else{
      $table.find('#'+$id).remove();
   }
   checkboxesFun();

});
 
  
function checkboxesFun() {
   var $text ='';
   var $checked = $("body").find('.product_qc_problems:checked').length;
   var $btn = $("body").find('#btnToGiveError');
   var $btn1 = $("body").find('#product-approve');
   var $btn2 = $("body").find('#product-reject');
   // $("body").find('.product_qc_problems:checked').each(function() {
   //        $checked = 1;
   // });
   if(parseInt($checked) > 0){
        $btn.removeAttr('disabled');
        $btn2.removeAttr('disabled');
        $btn1.attr('disabled','true');
        
   }else{
        $btn.attr('disabled','true');
        $btn2.attr('disabled','true');
        $btn1.removeAttr('disabled');
        
   }
}

 

  

$("body").on('click','#product-approve',function(e){
    e.preventDefault();
    var $this = $( this );
     var $loader = $("body").find('.loader');
     $.ajax({
                url : $this.data('action'),
                type: 'GET',  
                dataTYPE:'JSON',
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                       $loader.show();    
                },
                success: function (result) {
                      if(result.status == 1){
                        alert(result.messages);
                         
                        window.location.reload();
                      }  
                },
                complete: function() {
                       $loader.hide();    
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     console.log('------------------------');
                     console.log(jqXhr);
                     console.log('------------------------');
                     console.log(textStatus);
                     console.log('------------------------');
                     console.log(errorMessage);
                }

    });
});
 
  

$("body").on('click','#product-reject',function(e){
    e.preventDefault();
    var $this = $( this );
     var $loader = $("body").find('.loader');
     $.ajax({
                url : $this.data('action'),
                type: 'GET',  
                dataTYPE:'JSON',
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                       $loader.show();    
                },
                success: function (result) {
                      if(result.status == 1){
                        alert(result.messages);
                         
                        window.location.reload();
                      }  
                },
                complete: function() {
                       $loader.hide();    
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     console.log('------------------------');
                     console.log(jqXhr);
                     console.log('------------------------');
                     console.log(textStatus);
                     console.log('------------------------');
                     console.log(errorMessage);
                }

    });
});
 
  
  

$("body").on('submit','form#productQcMessages',function(e){
    e.preventDefault();
     saveMessageForQC();
});
 
  

$("body").on('submit','form#createNewKeywords',function(e){
    e.preventDefault();
    var $loader = $("body").find('.loader');
    var $this = $(this);
    $.ajax({
                url : $this.data('action'),
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
                         loadCateFunc();
                         notification_msg(result.message,'success');
                         $this[0].reset();
                      } 
                      $loader.hide();  
                },
                complete: function() {
                       $loader.hide();    
                },
                error: function (jqXhr, textStatus, errorMessage) {
                      
                }

    });
});
 
  
function saveMessageForQC() {
  var $loader = $("body").find('.loader');
  var $this = $("body").find('#productQcMessages');
      $this.data('action');
   $.ajax({
                url : $this.data('action'),
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
                        alert(result.messages);
                        $('#exampleModal').modal('hide');
                        window.location.reload();
                      }  
                },
                complete: function() {
                       $loader.hide();    
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                      
                }

    });
}

loadCateFunc();

function loadCateFunc() {
  var $loader = $("body").find('.loader');
  var $loadAllAssignedKeywords = $("body").find('#loadAllAssignedKeywords');
  var $this = $("body").find('#createNewKeywords');
      $this.data('action');
   $.ajax({
                url : $this.data('action'),
                type: 'GET',  
                dataTYPE:'JSON',
                //data:$this.serialize(),
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                       $loader.show();    
                },
                success: function (result) {
                      if(result.status == 1){
                         $loadAllAssignedKeywords.html(result.list);
                      }  
                },
                complete: function() {
                       $loader.hide();    
                },
                error: function (jqXhr, textStatus, errorMessage) {
                      
                }

    });
}

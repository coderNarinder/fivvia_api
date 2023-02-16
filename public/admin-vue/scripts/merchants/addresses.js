  

  $("body").on('change','select[name=country_id]',function(){
     var $this = $(this);
     var $div = $("body").find('select[name=state_id]');
     getLocations($this.val(),0,$div);
  });


  $("body").on('change','select[name=state_id]',function(){
     var $this = $(this);
     var $div = $("body").find('select[name=city_id]');
     var $country_id = $("body").find('select[name=country_id] option:selected').val();
     getLocations($country_id,$this.val(),$div);
  });


  function getLocations($country_id,$state_id=0,$div) {
    var $url = $("body").find('#addressAjax').val();
    $.ajax({
           url : $url,
           data : {
                parent : $country_id,
                subparent : $state_id,
           },
           type: 'GET',  // http method
           dataTYPE:'JSON',
           beforeSend: function() {
               $("body").find('.custom-loading').show();
           },
           success:function(data)
           {
               $div.html(selectOption(data.list));    
               $("body").find('.custom-loading').hide();   
           }
      });
  }



  $("body").on('change','#city_selection',function(e){
      $this = $(this);
      // alert($this.find('option:selected').data('pincode'));
     $('#pincode').val($this.find('option:selected').data('pincode'));
  });

  function selectOption($locations) {
     var $text = '<option value="">Select</option>';
     $.each($locations,function(k,data){
      console.log(data);
       $text +='<option value="'+data.id+'" data-pincode="'+data.pincode+'">'+data.label+'</option>';
     });
     return $text;
  }



  
$("body").on('keyup keydown blur','.character_limits',function(){
   characterLengths($(this));
});



function characterLengths($this) {
   var $length = parseInt($this.val().length);
   $maxlength = parseInt($this.attr('maxlength'));
   $left = $maxlength - $length;
   console.log($left);
   $($this.data('container')).html($left);
}

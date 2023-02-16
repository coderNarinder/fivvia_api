getSidebarOptions();
function getSidebarOptions() {
	  var $url = $("body").find('#get_sidebar_url').val();
	  var $this = $("body").find('#sidebar_container');
	  $.ajax({
                url:$url,
                method: "GET",
                dataType: 'JSON',
                // contentType: false,
                // cache: false,
                // processData: false,
                beforeSend: function() {
                    $this.find('.innder-loader').show();
                },
                
                success: function(data) {
                	     $this.html(data.list);
                       $this.find('.innder-loader').hide();
                       getProducts();
                }
    });
}

$("body").on('keyup','.filter-search', function() {
  var value = this.value.toLowerCase().trim();
  var $this = $(this);
  $($this.data('target')).show().filter(function() {
    return $(this).text().toLowerCase().trim().indexOf(value) == -1;
  }).hide();

  	 var $inputContainer = $("body").find($this.data('input-container'));
  if(value == ''){
  	 var $input = $("body").find($this.data('input'));
  	 hideShowOptions($input);
  	 $inputContainer.show();
  }else{
  	  $inputContainer.hide();
  }
});



$("body").on('change','.filterMoreOption',function(){
     var $this = $(this);
     hideShowOptions($this);
});

function hideShowOptions($this) {
	 var $target = $("body").find($this.data('target'));
     if($this.is(':checked')){
     	 $target.show();
     }else{
     	 $target.hide();
     }
}





    $("body").on('change','input[type="checkbox"]', function (e) {
           setFilterTagsTourl();
           getTags();
      });
  


function getTags() {

 $text ='<ul>';
 $inputHtml = $("body").find('#extra_fields');
 $inputs ='';
 $type =0;
  $("body").find('.clear_all_groups').hide();
  $("body").find('input.input_filter_tags:checked').each(function(){

      let $this = $(this);
     $("body").find($this.attr('data-target-ID')).show();
      $text +='<li class="badge" data-id="'+($type++)+'">';
      $text +='<a href="javascript:void(0);" class="filter-tag" data-target="#'+$this.attr('id')+'">';
      $text += $this.data('filter-label');
      $text +='<i class="close fas fa-times"></i>';
      $text +='</a>';
      $text +='</li>';


      $inputs +='<input type="hidden" name="selected_all_variations[]" value="'+$this.val()+'">';
  });
  $text +='</ul>';
  $text +='<a href="javascript:void(0);" class="clear_all main-link" data-target=".input_filter_tags">Clear All</a>';
  
  $t = parseInt($type) > 0 ? $text : '';
  $("body").find('#tag-filter-container').html($t);
   $inputHtml.html($inputs);
}


$("body").on('click','a.filter-tag',function(e){
  e.preventDefault();
  var $this = $(this);
  $input = $("body").find($this.data('target'));
  console.log($this.data('target'));
  $input.prop('checked', false);
  $this.closest('li').remove();
  setFilterTagsTourl();
  getProducts();
});


$("body").on('click','a.clear_all',function(e){
  e.preventDefault();
  var $this = $(this);
  $input = $("body").find($this.data('target'));
  console.log($this.data('target'));
  $input.prop('checked', false);
  $("body").find('#tag-filter-container').html(' ');
  setFilterTagsTourl();
  getProducts();
});



function setFilterTagsTourl() {
          var data = {},
          fdata = [],
          loc = $('<a>', { href: window.location })[0];
          // get all keys.
          var fdata = $("body").find('#filterForm').serialize();
          // iterate over them and create the fdata
           if (history.pushState) {
              history.pushState(null, null, loc.pathname + '?' + fdata);
           }
}

//-------------------------------------------------------------------------------------------------------------
//  get products after filtering
//-------------------------------------------------------------------------------------------------------------

function clear_all_groups() {
  $("body").find('.clear_all_groups').hide();
}






//-------------------------------------------------------------------------------------------------------------
//  get products after filtering
//-------------------------------------------------------------------------------------------------------------

function getProducts($type = 0) {
   getTags();
    var $this = $("body").find('#filterForm');
    $sorting = $("body").find('select#sorting').val();
    $this.find('input[name=sort]').val($sorting);
    $.ajax({
                url: $this.data('action'),
                method: "GET",
                data:$this.serialize(),
                dataType: 'JSON',
                // contentType: false,
                // cache: false,
                // processData: false,
                beforeSend: function() {
                    $('#loadProducts').find('.innder-loader').show();
                },
                success: function(data) {
                       let $listing_container = $("body").find(data.listing_container_id);
                      
                       if($type == 1){
                         $listing_container.append(data.list);
                       }else{
                         $listing_container.html(data.list);
                       }
                       $('#loadProducts').find('.innder-loader').hide();

                       var numItems = $listing_container.find('.productItem').length;
                      $("body").find('#loadedProductCount').text(numItems);
                      //$("body").find('#productCounts').text($this.data('total'));
                }
    });
}




$("body").on('change','.input_filter',function(){
  getProducts();
});




$("body").on('click','.load-product-more',function(e){
  e.preventDefault();
  $this = $(this);
   var $form = $("body").find('#filterForm');
   $form.find('input[name=skip]').val($this.data('skip'));
   getProducts(1);

// $("body").find('#skipList').text($this.data('start'));

 $this.closest('.col-12').remove();
});



$("body").on('change','select#sorting',function(){
 getProducts();
});
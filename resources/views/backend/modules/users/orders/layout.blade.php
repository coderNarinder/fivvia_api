@extends('admin.layouts.layoutNew')
@section('content')


<style type="text/css">
  .pro-image {
    float: left;
    width: 60px;
    height: 60px;
    margin-right:10px;
    overflow: hidden;
}
.pro-image img{
  max-width: 100%;
}

.pro-details {
    width: calc(100% - 70px);
    float: right;
}
 

.clr {
    clear: both;
}
.o-h-t-product-details {
    max-width: 100%;
}

p.info_text span {
    color: #28a745;
    text-transform: uppercase;
    font-size: 12px;
}

p.info_text {
    font-size: 12px;
    color: #0097a7;
}

.ord-labels label.btn-warning {
    display: block;
    text-align: center;
    padding: 30px 0;
}
.ord-labels input{
  position: absolute; 
  opacity: 0;
}
.ord-labels input[type="radio"]:checked + label.btn-warning{
  background:var(--theme-color) !important;
}
</style>






<div class="m-c-heading">
  <h2>Order History</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Order History</li>
    </ol>
  </nav>
  <!--  <a href="" class="main-button m-b-rounded">
    <span class="material-icons">visibility</span>
    </a> -->
</div>
<div class="order-history-wrap">
 <div class="row">
  
<form id="tableFilter" class="col-12" data-action="{{route('admin.user.orders.history.filterajax',$user->id)}}">


<div class="row ord-labels">
       <div class="col-2">
           <input type="radio" value="all" id="all-status" class="filterOption" name="status" checked="">
         <label for="all-status" class="btn-warning">
           All
            <span>{{$user->user_order_items->count()}}</span>
         </label>
       </div>
       <div class="col-2">
           <input type="radio" value="1" id="NEW-status" class="filterOption" name="status">
           <label for="NEW-status" class="btn-warning">NEW
              <span>{{$user->user_order_items->whereIn('status',[0,1])->count()}}</span>
           </label>
       </div>
       <div class="col-2">
           <input type="radio" value="2" id="Packed-status" class="filterOption" name="status">
           <label for="Packed-status" class="btn-warning">Packed
              <span>{{$user->user_order_items->whereIn('status',[2,3])->count()}}</span>
           </label>
       </div>
       <div class="col-2">
           <input type="radio" value="4" id="Shipped-status" class="filterOption" name="status">
           <label for="Shipped-status" class="btn-warning">Shipped
              <span>{{$user->user_order_items->where('status',4)->count()}}</span>
           </label>
       </div>
       <div class="col-2">
           <input type="radio" value="5" id="Delivered-status" class="filterOption" name="status">
           <label for="Delivered-status" class="btn-warning">Delivered
              <span>{{$user->user_order_items->where('status',5)->count()}}</span>
           </label>
       </div>
       <div class="col-2">
           <input type="radio" value="6" id="Cancelled-status" class="filterOption" name="status">
           <label for="Cancelled-status" class="btn-warning">Cancelled
              <span>{{$user->user_order_items->where('status',6)->count()}}</span>
           </label>
       </div>
    </div>   


 


<div class="m-l-advance-search ">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Advance serach
       <span class="material-icons s-i-angle">expand_more</span></button>
     <div class="collapse" id="collapseExample" style="">
        <div class="card card-body">
           <div class="row">
             <div class="col-sm-12">
                  
                  <h4><i class="fas fa-search"></i>Search By:</h4>
                  <div class="row">

                     <?php 
                     $product_IDs = \App\Models\OrderItem::where('user_id',$user->id)
                       ->where('type','order')
                      ->groupBy('product_id')
                      ->pluck('product_id')
                      ->toArray();

                       $orderItemIDs = \App\Models\OrderItem::where('user_id',$user->id)
                                                             
                                                            ->where('type','order')
                                                            ->groupBy('orderItemID')
                                                            ->pluck('orderItemID')
                                                            ->toArray();
                        $productIDS = \App\Models\OrderItem::where('user_id',$user->id)
                                                          ->where('type','order')
                                                            ->groupBy('product_id')
                                                            ->pluck('product_id')
                                                            ->toArray();

                      
                      $products = \App\Models\Product::whereIn('id',$product_IDs)

                                                    ->groupBy('parent')
                                                    ->orderBy('name','ASC')
                                                    ->get();

                      ?>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Product Name</label>
                        <select class="form-control filterOption" id="product_name" 
                        data-reset="#order_IDs"
                        data-reset2="#sku"
                        name="product_name">
                           <option value="">Search....</option>
                        @foreach($products as $pro)
                            <option value="{{$pro->parent}}">{{$pro->name}}</option>
                        @endforeach
                      </select>
                      </div>
                   </div>
                 

                 <div class="col-sm-4">
                      <div class="form-group">
                        <label>Order ID</label>
                        <select class="form-control filterOption" id="order_IDs"
                        data-reset="#product_name"
                        data-reset2="#sku"
                         name="order_IDs">
                          <option value="">Search....</option>
                        @foreach($orderItemIDs as $orderItemIDss)
                            <option value="{{$orderItemIDss}}">{{$orderItemIDss}}</option>
                        @endforeach
                      </select>
                      </div>
                   </div>


                
                </div>
                  <h4 class="form-heading"><i class="fas fa-filter"></i>Filter By:</h4>
                  <div class="row">
                  
                         <!--  <div class="col-sm-4">
                          <div class="form-group">
                           <label>Status</label>
                            <select name="status" id="status" class="form-control filterOption select2">
                                 <option value="all">All</option>
                                 <option value="1">NEW</option>
                                 <option value="2">Packed</option>
                                 <option value="4">Shipped</option>
                                 <option value="5">Delivered</option>
                                 <option value="6">Cancelled</option>
                               
                            </select>
                           </div>
                         </div>   -->
                       
                 
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>From Date</label>
                    <input type="text" id="from" class="form-control" name="from">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>To Date</label>
                    <input type="text" id="to" class="form-control" name="to">
                  </div>
                </div>
                </div>
                </div>

                <div class="col-sm-12">
                   <button class="main-button" type="button" id="searchFilterFormBtn">Filter</button>
                </div>
               
           </div>
        </div>
      </div>
 </div>



</form>
 
 <div class="col-12">
 <div class="order-history-table pickup-table">
     @yield('orderContent')
 </div>
 
 </div>
</div>

 
</div>
 

 


@endsection      
@section('scripts')
<script type="text/javascript">

 //----------------------------------------------------------------------------------------------------------------
 // DATEPICKER
 //----------------------------------------------------------------------------------------------------------------


 $( function() {
    var dateFormat = "mm/dd/yy",
      from = $( "#from" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 1
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  
 //----------------------------------------------------------------------------------------------------------------
 // division
 //----------------------------------------------------------------------------------------------------------------
$("body").on('change','.allChecks',function(e){
 e.preventDefault();
 var $this = $( this ); 
 if($this.is(':checked')){
    $($this.data('class')).prop('checked', true);
 }else{
    $($this.data('class')).prop('checked', false);
 }
   checkedselectedVariation($this);
});

//----------------------------------------------------------------------------------------------------------------
// division
//----------------------------------------------------------------------------------------------------------------
$("body").on('change','.customCheck-all',function(e){
 e.preventDefault();
 var $this = $( this ); 
 checkedselectedVariation($this);
});

//----------------------------------------------------------------------------------------------------------------
// division
//----------------------------------------------------------------------------------------------------------------
function checkedselectedVariation($this) {
   var $selected = parseInt($('.customCheck-all:checked').length);
   $("body").find('#selectedVariation').text($selected);
   var $actionuttons = $("body").find('.filter-more-action-buttons');
   if($selected > 0){
        $actionuttons.show();
   }else{
        $actionuttons.hide();
   }
}

//----------------------------------------------------------------------------------------------------------------
// division
//----------------------------------------------------------------------------------------------------------------


// $("body").on('click','a.create-order-status',function(e){
//  e.preventDefault();
//  var $this = $(this);
//  filter_fun($this);
// });


//----------------------------------------------------------------------------------------------------------------
// division
//----------------------------------------------------------------------------------------------------------------


$("body").on('click','a.change-order-status',function(e){
 e.preventDefault();
 var $this = $(this);
      $.ajax({
              url:$this.data('action'),
              method: "GET",
              dataType: 'JSON',
              beforeSend: function() {
                   $("body").find('.custom-loading').show();
              },
              success: function(data) {
                   $("body").find('.custom-loading').hide();
                   alert(data.message);
                   if(data.status == 1){
                    window.location.reload();
                   }

                   if(data.status == 2){
                      window.open(
                         data.url,
                        '_blank' // <- This is what makes it open in a new window.
                      );
                       window.location.reload();
                   }
             }
      });
});


//----------------------------------------------------------------------------------------------------------------
// division
//----------------------------------------------------------------------------------------------------------------


function filter_fun($this) {
      var arr = [];
    $('input.order_item_ids:checkbox:checked').each(function () {
        arr.push($(this).val());
    });
    
       $.ajax({
                url:$this.data('action'),
                method: "GET",
                data:{
                  order_item_ids : arr
                 },
                dataType: 'JSON',
                beforeSend: function() {
                     $("body").find('.custom-loading').show();
                },
                success: function(data) {
                     $("body").find('.custom-loading').hide();
                     alert(data.message);
                     if(data.status == 1){
                      window.location.reload();
                     }
               }
        });
}



//----------------------------------------------------------------------------------------------------------------
// division
//----------------------------------------------------------------------------------------------------------------





$("body").on('click','a.page-link',function(e){
   e.preventDefault();
   var $url = $(this).attr('href');
   getCategory($url);
});

jQuery("body").on('change','#sr_status',function(){
getCategory();
});



jQuery("body").on('click','#searchFilterFormBtn',function(){
getCategory();
});


$("body").on('change','.filterOption',function(){
  $this = $(this);
  $($this.data('reset')).val('');
  $($this.data('reset2')).val('');
  getCategory();

});



getCategory();
  
function getCategory($url = null) {
  var $loader = $("body").find('.loader');
  var $this = $("body").find('#tableFilter');
  var $url2 = $url == null ? $this.data('action') : $url;
   $.ajax({
                url : $url2,
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
                    $("body").find('#getCategory').html(result.data);
                      
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
}







 


  
function formFilter($this) {
   var $loader = $("body").find('.loader');
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
                       getCategory(); 
                       $loader.hide();    
                },
                complete: function() {
                       $loader.hide();    
                         
                },
                error: function (jqXhr, textStatus, errorMessage) {
                     
                }

    });
}










 $("body").on('change','#allCheck',function(){
    var $this = $( this )  ;
    var $checkboxes = $("body").find('.checkFilter');
    if($this.is(':checked')){
       $checkboxes.prop('checked',true);
    }else{
       $checkboxes.prop('checked',false);
    }
allCheck();
    
 });




 $("body").on('change','.checkFilter',function(){
    var $this = $( this );
    var $singleDel = $($this.data('id'));
    allCheck();
    
 });





 $("body").on('click','.productStatusChange',function(){
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
                success: function (data) {
                     
                      
                       $loader.hide();
                   if (data.status == 1) {
                       $('body').find('.loader').hide();
                       notification_msg(data.messages,'success');
                       getCategory();

                    }else if (data.status == 0) {
                       $loader.hide();   
                       $('body').find('.loader').hide();
                       notification_msg(data.messages,'error');
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







function allCheck() {
  var $checkboxes = $("body").find('.checkFilter:checked');
  if(parseInt($checkboxes.length) > 0){
   changeButtons();
  }else{
    $("body").find('.btn-all-publish').attr('disabled','true');
    $("body").find('.btn-all-unpublish').attr('disabled','true');
    $("body").find('.btn-all-sendQC').attr('disabled','true');
  }

}


function changeButtons() {

  var $status = parseInt($("body").find('select[name=status]').val());
     switch ($status) {
      case 0:
          $("body").find('.btn-all-publish').attr('disabled','true');
          $("body").find('.btn-all-unpublish').attr('disabled','true');
          $("body").find('.btn-all-sendQC').removeAttr('disabled');
        break;
       case 1:
          $("body").find('.btn-all-publish').attr('disabled','true');
          $("body").find('.btn-all-unpublish').removeAttr('disabled');
          $("body").find('.btn-all-sendQC').attr('disabled','true');
        break;
       case 2:
          $("body").find('.btn-all-publish').attr('disabled','true');
          $("body").find('.btn-all-unpublish').removeAttr('disabled');
          $("body").find('.btn-all-sendQC').attr('disabled','true');
        break;
      
       case 3:
          $("body").find('.btn-all-publish').removeAttr('disabled');
          $("body").find('.btn-all-unpublish').attr('disabled','true');
          $("body").find('.btn-all-sendQC').attr('disabled','true');
        break;
      default:
        $("body").find('.btn-all-publish').attr('disabled','true');
        $("body").find('.btn-all-unpublish').attr('disabled','true');
        $("body").find('.btn-all-sendQC').attr('disabled','true');
    }
}





 





$("body").on('submit','form[name=createAwb]',function(e){
 e.preventDefault();
 var $this = $(this);
      $.ajax({
              url:$this.data('action'),
              method: "POST",
              data: $this.serialize(),
              dataType: 'JSON',
              beforeSend: function() {
                   $("body").find('.custom-loading').show();
                   $this.find('button').attr('disabled','true');
              },
              success: function(data) {
                   $("body").find('.custom-loading').hide();
                   alert(data.message);
                   if(data.status == 1){
                    window.location.href = data.url;
                   }
                   $this.find('button').removeAttr('disabled');
             }
      });
});


</script>
@endsection
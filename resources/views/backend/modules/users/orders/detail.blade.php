@extends('users.layout.layoutNew')
@section('content')


<style type="text/css">
.theme-dark .activities .col-8 {
    color: #6c757d;
}
.activities .col-8 > *:first-child {
    font-weight: bold;
	color: #000;
}
  .d_date {
    font-size: 36px;
    background: #292d32;
    margin: 0 -15px;
    color: #fff;
    line-height: 52px;
}

.d_month {
    font-size: 24px;
    margin: 0px -15px;
    background: #292d32;
    color: #7cdae4;
    line-height: 20px;
    margin-top: 7px;
    text-transform: uppercase;
}

.d_year {
    font-size: 20px;
    margin: 0px -15px;
    color: #7cdae4;
    background: #292d32;
    line-height: 24px;
}

.row.activites_date {
    background: #292d32;
	text-align: center;
	border-top-right-radius: 0;
	height: 100%;
}

.col-12.d_time {
    background: #4f5962;
    color: #fff;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
	height: 25px;
}

.row.activites_date .col-6 {
    height: calc(100% - 25px);
}
 
.row.activities h4 {
    font-size: 12px;
}

.row.activities {
    margin-bottom: 15px;
}
.activities .col-8 {
    padding: 10px 15px;
	background-color: #efefef;
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
    <a href="{{route('user.myOrders')}}" class="main-button m-b-rounded" title="click here to view all orders">
    <span class="material-icons">visibility</span>
    </a>
</div>
<div class="order-history-wrap">
  <div class="row">
    <div class="col-sm-8">

    @if(!empty($order->orderItems))
     @foreach($order->orderItems as $item)
<?php $id = encrypt_decrypt($item->product->variant_id)?>
      <div class="o-h-w-left">
        <div class="order-history-and-timeline-wrap">
          <div class="timeline-container">

            @if($item->status == 6)
              <ul>
              <li class="<?= $item->status > 0 ? 'active' : 'in-active' ?>">
                <i class="fas <?= $item->status > 0 ? 'fa-check' : 'fa-circle' ?>"></i>
                <h4>Ordered</h4>
                <p>{{$item->getStatusDate(1)}}</p>
              </li>
              
              
              <li class="<?= $item->status == 6 ? 'active' : 'in-active' ?>">
                <i class="fas <?= $item->status == 6 ? 'fa-check' : 'fa-circle' ?>"></i>
                <h4>Cancelled</h4>
                <p class="dark-est-date">{{$item->getStatusDate(6)}}</p>
              </li>
            </ul>
            @else
            <ul>
              <li class="<?= $item->status > 0 ? 'active' : 'in-active' ?>">
                <i class="fas <?= $item->status > 0 ? 'fa-check' : 'fa-circle' ?>"></i>
                <h4>Ordered</h4>
                <p>{{$item->getStatusDate(1)}}</p>
              </li>
              <li class="<?= $item->status > 2 ? 'active' : 'in-active' ?>">
                <i class="fas <?= $item->status > 2 ? 'fa-check' : 'fa-circle' ?>"></i>
                <h4>Packed</h4>
                <p>{{$item->getStatusDate(3)}}</p>
              </li>
              <li class="<?= $item->status > 3 ? 'active' : 'in-active' ?>">
                <i class="fas <?= $item->status > 3 ? 'fa-check' : 'fa-circle' ?>"></i>
                <h4>Shipped</h4>
                <p>{{$item->getStatusDate(4)}}</p>
              </li>
              <li class="<?= $item->status == 5 ? 'active' : 'in-active' ?>">
                <i class="fas <?= $item->status == 5 ? 'fa-check' : 'fa-circle' ?>"></i>
                <h4>Delivered</h4>
                <p class="dark-est-date">{{$item->getStatusDate(5)}}</p>
              </li>
            </ul>
            @endif
          </div>
          <div class="order-history-block">
            <div class="o-h-b-left">
              <ul>
                <li>
                  <h4>Order ID</h4>
                  <p>{{$item->orderID}}</p>
                </li>
                <li>
                  <h4>Order Date</h4>
                  <p>{{$order->order_date_time}}</p>
                </li>
                <li>
                  <h4>Order Status</h4>
                  <p>{{$item->userOrderItemStatus()}}</p>
                </li>
                <li>
                  <h4>Grand Total</h4>
                  <p>₹{{$item->subtotal}}</p>
                </li>
              </ul>
            </div>
            <div class="o-h-b-right">
              <div class="o-h-product-details">
                <div class="o-h-product-img">
                  <img src="{{url($item->product->getThumbnail('thumb'))}}" alt="{{$item->product->name}}" class="img-thumbnail" />
                </div>
                <div class="o-h-product-text">
                  <h4><a href="{{route('home.product.detail',[$item->product->parentProduct->slug,'v_ID' => $id])}}" title="{{$item->product->name}}" class="p-s-product-name">{{$item->product->name}}</a></h4>
                   @if($item->product->parentProduct->returnable == 1 && $item->product->parentProduct->returnable_in > 0)
                    
                      
                     
                  <p>Return eligible till {{$item->last_returnable_date()}}</p>
                  @else
                  <p>Non Returnable</p>
                    @endif
                   <p>Rs.{{$item->price}}</p>
                   <p>Qty: {{$item->qty}}</p>
                    @if($item->product->productVariation->assignedVariation != null)
                        @foreach($item->product->productVariation->assignedVariation as $attr)
                          <h6 class="color-vaiation">
                               {{$attr->variationValue->getParent->label}} :
                               <span> {{$attr->variationValue->label}} </span>
                            </h6>
                         @endforeach   
                    @endif
            				  <div class="o-h-p-t-links">
                          <a href="javascript:void(0);" data-terms="{{$item->product->parentProduct->returnable_terms}}" class="privacyModal" title="Click here to view return policy">View Return Policy</a>
                          @if($item->status == 5)
                          <a href="<?= $item->invoice_url ?>" target="_blank" title="click here to download invoice">Invoice</a>
                          @endif
            				  </div>
                </div>
              </div>





           


              <div class="o-h-product-links">
                <ul>

                  <li> 
                    <a href="javascript:void(0);" 
                     data-toggle="modal" 
                     class="<?= (!empty($item->awb_code)) && $item->status != 6 ? 'active' : 'disabled' ?>"
                     data-target="#exampleModal-track-{{$item->id}}" title="click here to track your order">Track Package</a> 
                   </li>
                   
                   <li>
                     <a href="javascript:void(0);" data-action="{{route('user.orderDetailCancel',$item->orderItemID)}}" class="cancel_order change-order-status-popup <?= ($item->status < 3) ? 'active' : 'disabled' ?>">Cancel</a>
                  </li>
                   
                   
                  <li>
                    <a href="javascript:void(0);" class="<?= ($item->status == 5) ? 'active' : 'disabled' ?>" title="click here to add return to your order">Return/Replace</a>
                  </li>
                  
                    @if(!empty($item->reviewAdded) && $item->reviewAdded->count() > 0)
                    <li>
                     <a href="javascript:void(0);" class="disabled" title="You have submitted the review">Rated Product</a>
                   </li>
                   @else
                   <li>
                    <a href="javascript:void(0);" 
                    class="<?= ($item->status == 5) ? 'active' : 'disabled' ?>"
                    <?php if(($item->status == 5)): ?>
                    data-target="#reviewModal-{{$item->id}}"
                     data-toggle="modal" 
                   <?php endif; ?>
                   
                    >Rate Product</a>
                  </li>
                    @endif
                  <li>
                    <?php $rated = !empty($item->sellerRating) && $item->sellerRating->count() > 0 ? 1 : 0;  ?>
                    <button href="javascript:void(0);" class="<?= ($item->status == 5 && $rated == 0) ? 'active' : 'disabled' ?>" 
                      <?= ($item->status == 5 && $rated == 0) ? '' : 'disabled' ?> data-toggle="modal" data-target="#rateSellerModal-{{$item->id}}" title="click here to rate seller">Rate Seller</button>
                  </li>
                  
                
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

 

<!-- Modal -->
<div class="modal fade" id="exampleModal-track-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Order Track</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12"> <?= $item->trackOrder() ?> </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="main-button m-b-red" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
@if($item->status == 5 && $rated == 0)
<!-- rateSellerModal -->
<div class="modal fade" id="rateSellerModal-{{$item->id}}" tabindex="-1" aria-labelledby="rateSellerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
       <form 
        id="commentform" 
        class="comment-form" 
        data-action="{{route('user.orderSellerRating',$item->id)}}">
        <input type="hidden" value="{{$item->id}}" name="order_id">
        @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="rateSellerModalLabel">Rate Seller</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="rating-smiley">
        <input type="radio" name="rating" value="1" id="star1" checked>
        <label for="star1">
          <img src="https://image.flaticon.com/icons/svg/1933/1933691.svg" alt="Loved it" width="120" height="120">
          <span>Loved it</span>
        </label>
        <input type="radio" name="rating" value="2" id="star2">
        <label for="star2">
          <img src="https://image.flaticon.com/icons/svg/1933/1933646.svg" alt="Liked it" width="120" height="120">
          <span>Liked it</span>
        </label>
        <input type="radio" name="rating" value="3" id="star3">
        <label for="star3">
          <img src="https://image.flaticon.com/icons/svg/1933/1933511.svg" alt="It's OK" width="120" height="120">
          <span>It's OK</span>
        </label>
        <input type="radio" name="rating" value="4" id="star4">
        <label for="star4">
          <img src="https://image.flaticon.com/icons/svg/1933/1933115.svg" alt="Disliked it" width="120" height="120">
          <span>Disliked it</span>
        </label>
        <input type="radio" name="rating" value="5" id="star5" >
        <label for="star5">
          <img src="https://image.flaticon.com/icons/svg/1933/1933127.svg" alt="Hated it" width="120" height="120">
          <span>Hated it</span>
        </label>
      </div>
      </div>
      <div class="modal-footer">
         <div id="msgBox"></div>
        <button type="button" class="main-button m-b-red" data-dismiss="modal">Close</button>
        <button type="submit" class="main-button m-b-green">Submit</button>
      </div>
    </form>
    </div>
  </div>
</div>
@endif








  <?php if(($item->status == 5) && empty($item->reviewAdded)): ?>

 <!-- reviewModal -->
    <div class="modal fade" id="reviewModal-{{$item->id}}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body">
                    <div class="heading-container h-c-inner-pages">
                  <h2>Add a review</h2>
              </div>
                    <div id="review_form_wrapper">
                        <div id="review_form">
                            <div id="respond" class="comment-respond">
                                <form 
                                id="commentform" 
                                class="comment-form" 
                                data-action="{{route('home.product.reviewAdd',$item->product->parentProduct->slug)}}">
                                <input type="hidden" value="{{$item->id}}" name="order_id">
                                    @csrf
                                    <div class="form-group">
                                        <label>Your Rating:</label>
                                        <ul class="product-rating p-r-not-filled my-custom-rate">
                                            <li><input type="radio" name="rating" value="1" class="star-1 my-custom-rating" id="star-1"><label for="star-1" class="label-star-1"><i class="fa fa-star"></i></label></li>
                                            <li><input type="radio" name="rating" value="2" class="star-2 my-custom-rating" id="star-2"><label for="star-2" class="label-star-2"><i class="fa fa-star"></i></label></li>
                                            <li><input type="radio" name="rating" value="3" class="star-3 my-custom-rating" id="star-3"><label for="star-3" class="label-star-3"><i class="fa fa-star"></i></label></li>
                                            <li><input type="radio" name="rating" value="4" class="star-4 my-custom-rating" id="star-4"><label for="star-4" class="label-star-4"><i class="fa fa-star"></i></label></li>
                                            <li><input type="radio" name="rating" value="5" class="star-5 my-custom-rating" id="star-5"><label for="star-5" class="label-star-5"><i class="fa fa-star"></i></label></li>
                                            
                                        </ul>
                                        <label for="rating" class="error"></label> 
                                    </div>
                                    <div id="add-images"></div>
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="title" >
                                    </div>
                                    <div class="form-group">
                                        <label>Your Review<span class="required">*</span></label>
                                        <textarea name="review" id="your-review" class="form-control" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Pictures</label>
                                        <input type="file" class="form-control" name="files[]" multiple="">
                                    </div>
                                    
                                   <!--  <div class="form-group custom-check">
                                        <input type="checkbox" value="" id="save-details" checked="">
                                        <label for="save-details">
                                          Save my name, email, and website in this browser for the next time I comment.
                                        </label>
                                    </div> -->
                                    <div id="msgBox"></div>
                                    <div class="form-group">
                                        <button type="submit" class="main-button m-b-bordered">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- reviewModal -->


     <?php endif; ?>
    @endforeach
   @endif
  </div>
 <div class="col-sm-4">
	<div class="o-h-w-right">
       <h4 class="form-heading">Order Details</h4>
        <ul>
          <li>Order Amount: <span>Rs.{{custom_format($order->order_product_prices,2)}}</span></li>
          <li>Discount: <span>Rs.{{custom_format($order->order_product_discount,2)}}</span></li>
          <li>Shipping Charge: <span>Rs.{{custom_format($order->shipping_charge,2)}}</span></li>
          <li>Total: <span>Rs.{{custom_format(($order->total + $order->shipping_charge),2)}}</span></li>
		    </ul>
        <h4 class="form-heading">Shipping Address</h4>
        <div class="shipping-address">
          @if(!empty($order->address))
               @php $a = $order; @endphp
               <h4 class="title-two">{{$a->name}}  </h4>
               <p>{{$a->address}}, {{$a->landmark}}</p>
               
               <p class="card-text">{{$a->country != null && $a->country->count() > 0 ? $a->country->label : 'N/A'}},
                                               {{$a->state != null && $a->state->count() > 0 ? $a->state->label : 'N/A'}},
                                               {{$a->city != null && $a->city->count() > 0 ? $a->city->label : 'N/A'}},
                                               {{$a->pincode}}</p>
                                               <p>{{$a->mobile_number}} {{$a->alterate_number != '' ? ','.$a->alterate_number : ''}}</p>
        @endif
        
        </div>


        <h4 class="form-heading">Payment Details</h4>

        <div class="shipping-address">
		  <ul>
          <li>Payment Method : <span>{{$order->payment_type}}</span></li>
          <li>Transaction Id : <span>{{$order->transaction_payment_id}}</span></li>
          <li>Payment Date : <span>{{$order->order_date_time}}</span></li>
		  </ul>
           
        </div>
		</div>
    </div>
  </div>
</div>
<!-- modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="privacyModalLabel">Returnable Policy</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>*If there is any issue with your product, you can raise a refund, replacement or exchange request within 14 days of receiving the product. Successful pick-up of the product is subject to the following conditions being met: Correct and complete product (with the original brand, article number, undetached MRP tag, product's original packaging, freebies and accessories) The product should be in unused, undamaged and original condition without any stains, scratches, tears or holes and with non-tampered quality check seals/return tags/ warranty seals (wherever applicable)</p>
      </div>
    </div>
  </div>
</div>
<!-- modal -->











<!-- modal -->
<div class="modal fade" id="cancelModalPopup" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="privacyModalLabel">Write Cancellation Reason</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form action="" id="cancellationForm"> 
          @csrf
           <div class="form-group">
              <label for="">Choose Reason</label>
              <select class="form-control" name="reason_dropdown" id="reason_dropdown">
                <?php $category = \App\Models\FAQs::where('type','userCancellationReasons')->orderBy('meta_value','ASC')->get(); ?>
                @foreach($category as $c)
                     <option value="{{$c->meta_value}}">{{$c->meta_value}}</option>
                @endforeach  
                 <option value="1">Other</option>
              </select>
           </div>
           <div class="form-group reason_dropdown" style="display: none">
              <label for="">Reason</label>
              <textarea class="form-control" name="reason"></textarea>
           </div>
            <div class="form-group">
               <button class="main-button">Submit</button>
            </div>
         </form>
      </div>
    </div>
  </div>
</div>
<!-- modal -->
@endsection
@section('scripts')
<script type="text/javascript">
  $("body").on('click','.privacyModal',function(){
       $modal = $("body").find('#privacyModal');
       $modal.find('.modal-body').html('<p>'+$(this).data('terms')+'</p>');
       $modal.modal('show');
  });



$("body").on('change','#reason_dropdown',function(){
      $box= $('.reason_dropdown');
      if(parseInt($("body").find('#reason_dropdown').val()) == 1){
         $box.show();
      }else{
         $box.hide();
      }
});


$("body").on('click','a.change-order-status-popup',function(e){
 e.preventDefault();
 $this = $(this);
 $modal = $("body").find('#cancelModalPopup');
 $modal.modal('show');
 $modal.find('#cancellationForm').attr('data-action',$this.data('action'));

});

jQuery("#cancellationForm").validate({
   rules: {
      'reason_dropdown': 
      {
        required: true
      },
      'reason': 
      {
        required: function(){
           if(parseInt($("body").find('#reason_dropdown').val()) == 1){
            return true;
           }else{
            return false;
           }
        },
      },
       
    },     
});


// 9417032711

$("body").on('submit','#cancellationForm',function(e){
 e.preventDefault();
 var $this = $(this);
      $.ajax({
              url:$this.data('action'),
              method: "POST",
              data:$this.serialize(),
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









// $("body").on('click','.cancel_order',function(e){


// });

//  $("body").on('click','.cancel_order',function(e){
//        e.preventDefault();
//        $this = $(this);
//        var $loader = $("body").find('.loader');
//         $.ajax({
//                 url : $this.data('action'),
//                 type: 'POST',  
//                 dataTYPE:'JSON',
                 
//                 headers: {
//                  'X-CSRF-TOKEN': '<?= csrf_token() ?>'
//                 },
//                 beforeSend: function() {
//                    $loader.show();    
//                 },
//                 success: function (result) {
//                     $loader.hide(); 
//                     alert(result.message);
//                     if(result.status == 1){
//                        window.location.reload();
//                     }
//                 } 
//         });
       
//   });










$('#commentform').validate({
   rules: {
        'review' : {
                     required: true
        },
        'rating' : {
                     required: true
        },
        'title' : {
                     required: true
        },
    },
});







// --------------------------------------------------------------------------------------------------------------------------------------
// Review Form
// --------------------------------------------------------------------------------------------------------------------------------------
 


$("body").on('submit','#commentform',function(e){
   e.preventDefault();
   var $this = $(this);
       


       var form = $this[0]; // You need to use standard javascript object here
       var formData = new FormData(form);
       var percent = $('body').find('.percent');
       var bar = $('.bar');
       
       $.ajax({
           url:$this.data('action'),
           method:"POST",
           data:formData,
           dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           beforeSend: function() {
              $('body').find('.progress').show();
              $('.progress').find('span.sr-only').text('0%');
              $this.find("button[type='submit']").attr('disabled','true');
              $("body").find('.custom-loading').show();
              $this.find('button').attr('disabled','true');
           },
           xhr: function () {
             var xhr = new window.XMLHttpRequest();
             xhr.upload.addEventListener("progress", function (evt) {
              if (evt.lengthComputable) {
                  var percentComplete = evt.loaded / evt.total;
                   percentComplete = parseInt(percentComplete * 100);
                   $('.progress').find('span.sr-only').text(percentComplete + '%');
                   $('.progress .progress-bar').css('width', percentComplete + '%');
               }
           }, false);
              return xhr;
          },

           success:function(result)
           {
                      $this.find('#msgBox').html('<div class="alert alert-warning">'+result.message+'</div>');
                      if(result.status == 1){
                        setTimeout(function(){
                           window.location.reload();
                        },2000);

                      }else{

                           $this.find('button').removeAttr('disabled');
                      }

                      $("body").find('.custom-loading').hide();
           }
 });

});



</script>
@endsection

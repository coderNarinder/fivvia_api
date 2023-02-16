 @php $list = 0; @endphp
      
 @foreach($orderItems as $key => $ord)
  <?php
 
   
  $id = encrypt_decrypt($ord->product->variant_id);
   ?>
 
 
<tr data-list="{{$list++}}" class="{{($key % 2 == 0) ? 'tr-odd' : 'tr-even'}}">
 <td>
  <div class="col-12">
     <p class="info_text"><span>ORDER ITEM ID : </span> 
      <a href="{{route('admin.orders.detail',$ord->orderID)}}" title="Click here to view order details"><b>{{$ord->orderItemID}}</b></a>
     </p>       
     <p class="info_text"><span>ORDER DATE : </span> {{$ord->order->order_date_time}}</p>
     <p class="info_text"><span>ORDER STATUS : </span> {!!$ord->userOrderItemStatusBtn()!!}</p>
     

      @if($ord->status == 6 && !empty($ord->OrderStatusTrack))
      <p class="info_text"><span>Cancelled Dated : </span> {{$ord->OrderStatusTrack->created_at}}</p>
      <p class="info_text"><span>Cancelled By : </span>
      @if(!empty($ord->OrderStatusTrack->user) && $ord->OrderStatusTrack->user->role == "user") User @endif
      @if(!empty($ord->OrderStatusTrack->user) && $ord->OrderStatusTrack->user->role == "vendor") Seller @endif

      </p>
      <p class="info_text"><span>Cancelled Reason : </span>
       {{$ord->OrderStatusTrack->message}}
      </p>
      @endif

      @if($ord->status == 5 && !empty($ord->OrderStatusTrack))
      <p class="info_text"><span>Delivered Dated : </span> {{$ord->OrderStatusTrack->created_at}}</p>
      @endif
   </div>
</td>

<td>
  <div class="o-h-t-product-details">
    <div class="pro-image img-thumbnail"><img src="{{url($ord->product->getThumbnail('small'))}}" alt="" 
      class=" img-responsive" /></div>
    <div class="pro-details">
        <p title="Click here to view product details">
          <a href="{{route('home.product.detail',[$ord->product->parentProduct->slug,'v_ID' => $id])}}" target="_blank" class="p-s-product-name" title="Click here to view product details">{{$ord->product->name}}</a>
        </p>
        <p class="info_text"><span>PRICE : </span> Rs.{{custom_format($ord->price,2)}}</p>
     </div>
     <div class="clr"></div>                       
 </div>
   
</td>
 <td>
     <p class="info_text"><span>QTY : </span> <b>{{$ord->qty}}</b> {{$ord->qty > 1 ? 'ITEMS' : "ITEM"}}</p>       
     <p class="info_text"><span>TOTAL : </span> Rs.{{custom_format($ord->subtotal,2)}}</p>       
            
        
 </td>
     

</tr>
 
 @endforeach
  <tr>
   <td colspan="3" class="text-center no_reviews">  {{$orderItems->links()}}</td>
  </tr>


           @if($list == 0)
             <tr>
               <td colspan="3" class="text-center no_reviews"><img src="{{url('images/orders.png')}}" alt="" style="width: 220px;">No orders yet !</td>
               
             </tr>
              
           @endif
           
           
           
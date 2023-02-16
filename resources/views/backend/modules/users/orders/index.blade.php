 @extends('admin.modules.users.orders.layout')
@section('orderContent')

 
 
 <div class="order-history-table pickup-table">
   <table>
     <thead>
     <tr>
       
       <th style="width:30%"><span>Order Details</span></th>
       <th style="width:40%"><span>Product Details</span></th>
       <th style="width:30%">Quantity And Pricing</th>
      </tr>
   </thead>
     <tbody id="getCategory">
     </tbody>
   </table>
  
@endsection
 
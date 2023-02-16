@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Package Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.product.index')}}">Package Management</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{$data->name}}</li>
    </ol>
  </nav>
  <a href="{{route('admin.product.index')}}" class="main-button m-b-rounded" title="view categories">
    <span class="material-icons">visibility</span>
  </a>
</div>


<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="editCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.product.gallery',$data->slug) }}"
  enctype="multipart/form-data">
   @csrf
     @include('admin.error_message')
    <div class="row">
           
          
          
          <div class="col-sm-6">
             <div class="form-group">
               <label>Picture</label>   
               <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                
             </div>
              <button type="submit" id="categoryFormSbt" class="main-button btn-submit btn-form-submit">Submit</button>
          </div>

            <div class="col-sm-6">
               <table class="dTable">
                   <tr>
                       <th>Image</th><th>Action</th>
                   </tr>
                   @foreach($gallery as $g)
                    <tr>
                        <td><img src="{{url($data->image)}}" id="image_src" style="width: 100px; height: 100px;margin:5px;" class="img-thumbnail"/></td>
                        <td><a href="{{route('admin.product.gallery.delete',$g->id)}}">Delete</a></td>
                    </tr>
                   @endforeach
               </table>
            </div>
 
    
    </div>

               
             
  </form>

      
</div>


 
@endsection







@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/products.js')}}"></script>
<script type="text/javascript">
  
</script>
@endsection
@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Addons Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.addons.index')}}">Addons Management</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
  </nav>
  <a href="{{route('admin.addons.index')}}" class="main-button m-b-rounded" title="view categories">
    <span class="material-icons">visibility</span>
  </a>
</div>


<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="editCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.addons.edit',$data->slug) }}"
  enctype="multipart/form-data">
   @csrf
     @include('admin.error_message')
    <div class="row">
          <div class="col-sm-6">{{textbox($errors,'Name*','name',$data->name)}}</div>
          <div class="col-sm-6 ">
            <div class="form-group"  >
                <label>Price</label>
                <input type="number" name="price" value="{{$data->price}}" class="form-control" required>
            </div>
          </div>
         
           

         
          
          <div class="col-sm-6">
             <div class="form-group">
               <label>Picture</label>   
               <input type="file" name="image" class="form-control" accept="image/*">
                @if($data->image)
                    <img src="{{url($data->image)}}" id="image_src" style="width: 100px; height: 100px;margin:5px;" class="img-thumbnail"/>
                  @endif
             </div>
          </div>

             <div class="col-12">
             <div class="form-group row">
               <label class="col-12">Categories</label>
               
                   <?php $category = \App\Models\Category::where('parent',0)->where('status',1)->orderBy('label','ASC')->get(); ?>
                   @foreach($category as $cate)
                    
 <div class="col-4">
                <div class="form-check">
  <input class="form-check-input" type="checkbox" value="{{$cate->id}}" id="cate-{{$cate->id}}" name="category_ids[]" 
  <?= \App\Models\AddonCategory::where('category_id',$cate->id)->where('addon_id',$data->id)->count() > 0 ? 'checked' : '' ?>>
  <label class="form-check-label" for="cate-{{$cate->id}}" >
   {{$cate->label}}
  </label>
</div>
</div>
                   @endforeach
               
             </div>
          </div>

           <div class="col-sm-12">
            <div class="form-group">
                <label>description</label>
                <textarea name="description" class="form-control" required>{{$data->description}}</textarea>
            </div>
          </div>
         
    </div>

              <button type="button" class="main-button btn-submit cropped_image" style="display: none;">Crop Image</button>
              <button type="submit" id="categoryFormSbt" class="main-button btn-submit btn-form-submit">Submit</button>
  </form>

      
</div>

 
@endsection







@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/products.js')}}"></script>
<script type="text/javascript">
  
</script>
@endsection


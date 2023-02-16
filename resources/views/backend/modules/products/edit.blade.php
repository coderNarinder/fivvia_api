@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Package Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.product.index')}}">Package Management</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
  </nav>
  <a href="{{route('admin.product.index')}}" class="main-button m-b-rounded" title="view categories">
    <span class="material-icons">visibility</span>
  </a>
</div>


<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="editCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.product.edit',$data->slug) }}"
  enctype="multipart/form-data">
   @csrf
     @include('admin.error_message')
    <div class="row">
          <?php
            $subparent =[];
            $parent=0;
            $parent_location=0;
            $subparent_location=0;
            $subparent_locations = [];
            $child_locations = [];

            if(!empty($data->category)  && $data->category->count() > 0){
                 $parent =!empty($data->category->parentCategory) ? $data->category->parentCategory->id : 0;
                 if(!empty($data->category->parentCategory) &&!empty($data->category->parentCategory->subparentCate) && $data->category->parentCategory->subparentCate->count() > 0){
                     $subparent = $data->category->parentCategory->subparentCate;
                 }
            } 

            if(!empty($data->category)  && $data->location->count() > 0){
                 $parent_location =!empty($data->location->parentCategory) ? $data->location->parentCategory->id : 0;
                 if(!empty($data->location->parentCategory) &&!empty($data->location->parentCategory->subparentCate) && $data->location->parentCategory->subparentCate->count() > 0){
                     $subparent_locations = $data->location->parentCategory->subparentCate;
                     $subparent_location = $data->location->subparentCategory->id;
                     $child_locations = !empty($data->location->subparentCategory->childCate) ? $data->location->subparentCategory->childCate : [];
                 }
            }

          ?>
          <div class="col-md-6"> 
             {{select3($errors,'Parent Category','parent','label','',$category,$parent)}}
           </div>
        <div class="col-md-6">
         {{select3($errors,'SubParent','subparent','label','0',$subparent,$data->category_id)}}
       </div>

        <div class="col-md-4"> 
             {{select3($errors,'country','parent_location','label','',$location,$parent_location)}}
           </div>
        <div class="col-md-4">
         {{select3($errors,'State','subparent_location','label','',$subparent_locations,$subparent_location)}}
       </div>
          <div class="col-md-4">
         {{select3($errors,'Location','child_location','label','',$child_locations,$data->location_id)}}
       </div>
           
           
          <div class="col-sm-6">{{textbox($errors,'Name*','name',$data->name)}}</div>
          <div class="col-sm-6 ">
            <div class="form-group"  >
                <label>Price for Adult <small>Per Day</small></label>
                <input type="number" name="price" value="{{$data->adult_price}}" class="form-control" required>
            </div>
          </div>
         <div class="col-sm-6 " >
            <div class="form-group">
                <label>Price for childrens <small>Per Day</small></label>
                <input type="number" name="child_price" value="{{$data->child_price}}" class="form-control" required>
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

           <div class="col-sm-12">
            <div class="form-group">
                <label>description</label>
                <textarea name="description" class="form-control" required>{{$data->description}}</textarea>
            </div>
          </div>
          <div class="col-sm-6">   {{textbox($errors,'Meta Title*','meta_title',$data->meta_title)}}</div>
          <div class="col-sm-6">  {{textbox($errors,'Meta Tags*','meta_tag',$data->meta_tag)}}</div>
          <div class="col-sm-12">  {{textarea1($errors,'Meta description*','meta_description',$data->meta_description)}}</div>
    
    </div>

              <button type="button" class="main-button btn-submit cropped_image" style="display: none;">Crop Image</button>
              <button type="submit" id="categoryFormSbt" class="main-button btn-submit btn-form-submit">Submit</button>
  </form>

      
</div>


 <input type="hidden" id="location_url" value="<?= route('admin.location.ajax') ?>">
     
<input type="hidden" id="category_url" value="<?= route('admin.category.ajax') ?>">
@endsection







@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/products.js')}}"></script>
<script type="text/javascript">
  
</script>
@endsection


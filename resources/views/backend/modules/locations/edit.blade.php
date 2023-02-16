@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Location Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.location.index')}}">Location Management</a></li>
       @if($data->parentCategory != null && $data->parentCategory->count() > 0) 
      <li class="breadcrumb-item"><a href="{{route('admin.location.index')}}">{{$data->parentCategory->label}}</a></li>
      @endif
       @if($data->subparentCategory != null && $data->subparentCategory->count() > 0) 
      <li class="breadcrumb-item"><a href="{{route('admin.location.index')}}">{{$data->subparentCategory->label}}</a></li>
      @endif
     
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
  </nav>
  <a href="{{route('admin.location.index')}}" class="main-button m-b-rounded" title="view locations">
    <span class="material-icons">visibility</span>
  </a>
</div>
 

<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="addCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.location.edit',$data->slug) }}"
  enctype="multipart/form-data">
   @csrf
     @include('admin.error_message')
   <div class="row">
        <div class="col-sm-6"> 
             {{select3($errors,'Country','parent','label','0',$location,$data->parent)}}
        </div>
        <div class="col-sm-6">
          <?php $subparent =[];
            if($data->parentCategory != null && $data->parentCategory->count() > 0){
                 if($data->parentCategory->subparentCate != null && $data->parentCategory->subparentCate->count() > 0){
                     $subparent = $data->parentCategory->subparentCate;
                 }
            }
          ?>
         {{select3($errors,'State','subparent','label','0',$subparent,$data->subparent)}}
       </div>
        <div class="col-sm-6"> 
        {{textbox($errors,'Name*','label',$data->label)}}
      </div>
       <div class="col-sm-6">{{textbox($errors,'Pin Code','pincode',$data->pincode)}}</div>
     </div>  

                    
                      <button type="submit" id="categoryFormSbt" class="main-button btn-submit btn-form-submit">Submit</button>
          </form>
 </div>


 
     
<input type="hidden" id="category_url" value="<?= route('admin.location.ajax') ?>">
@endsection







@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/location.js')}}"></script>
 
@endsection

 

 
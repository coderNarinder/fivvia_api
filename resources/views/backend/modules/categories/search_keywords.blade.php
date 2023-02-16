@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Category Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.category.index')}}">Category Management</a></li>
      @if(!empty($category->parentCategory))
      <li class="breadcrumb-item"><a href="{{route('admin.category.index')}}">{{!empty($category->parentCategory) ? $category->parentCategory->label : ''}}</a></li>
      @endif
      <li class="breadcrumb-item active" aria-current="page">{{$category->label}}</li>
    </ol>
  </nav>
  <a href="{{route('admin.category.index')}}" class="main-button m-b-rounded" title="view category">
    <span class="material-icons">visibility</span>
  </a>
</div>
<div class="wrapper-shadow-inset"> 
@include('admin.error_message')
<div class="col-md-12"> 
  
 
     <form class="form-edit-category f-e-c-variation" name="addCategory" role="form" method="post" id="categoryForm" 
           data-action="{{ route('admin.category.keyword',$category->id) }}"
           enctype="multipart/form-data">
           <input type="hidden" name="type" value="category">
           <input type="hidden" name="redirect_url" value="{{route('admin.category.keyword',$category->id)}}">
          <div class="row">

                @csrf
                @foreach($all_keywords as $v)
                <?php $checked = \App\Models\ItemAssignKeywords::where('keyword_id',$v->id)->where('model_id',$category->id)->where('type','category')->count() > 0 ? 'checked' : ''; ?>
                <div class="col-md-4">
                          <div class="form-group">
                               <div class="custom-control custom-checkbox">
                                      <input type="checkbox" name="keywords[]" 
                                             value="{{$v->id}}" 
                                             class="custom-control-input customCheck-all-{{$v->id}}" 
                                             id="customCheck-{{$v->id}}" {{$checked}}>
                                             <label class="custom-control-label" for="customCheck-{{$v->id}}">{{$v->keywords}}</label>
                                      </div>  
                          </div>
                </div>
                @endforeach
          </div>
          <button type="submit" id="categoryFormSbt" class="main-button">Assign</button>
 
</form>

 
 

@endsection
 

@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/category.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
 
@endsection


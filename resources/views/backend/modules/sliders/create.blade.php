@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Slider Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.slider.index')}}">Slider Management</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
  </nav>
  <a href="{{route('admin.slider.index')}}" class="main-button m-b-rounded" title="view slider">
    <span class="material-icons">visibility</span>
  </a>
</div>

 
<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="addCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.slider.create') }}"
  enctype="multipart/form-data">
   @csrf
     @include('admin.error_message')
    <div class="row">
            <div class="col-md-6"> 
            {{textbox($errors,'Title*','title')}}
            </div>

            <div class="col-md-6"> 
            {{textbox($errors,'Slider Image Link URL','slider_link')}}
            </div>

            <div class="col-md-12"> 
            {{textbox($errors,'Tags','tag')}}
            </div>


            <div class="col-md-12">
            <div class="form-group">
                <label class="label-file">Slider Image*</label>
               <input type="file" accept="image/*" class="form-control" name="image" required>
            </div>
            </div>
             <div class="col-md-6">
               <div class="form-group">
                   <label>Type</label>
                   <select name="type" class="form-control">
                      <option value="web">Web</option>
                      <!-- <option value="mobile-app">Mobile App</option> -->
                   </select>
               </div>
             </div>

       
     </div>
             <button type="submit" class="main-button btn-submit  ">Submit</button>
          </form>
 </div>
 

 
@endsection
 


@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/slider.js')}}"></script>
 
@endsection

  
 
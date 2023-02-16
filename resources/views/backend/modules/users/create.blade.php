@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Super Admin Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.category.index')}}">Super Admin Management</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
  </nav>
  <a href="{{route('admin.category.index')}}" class="main-button m-b-rounded" title="view categories">
    <span class="material-icons">visibility</span>
  </a>
</div>


<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="addCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.users.create') }}"
  enctype="multipart/form-data">
   @csrf
     @include('admin.error_message')
    <div class="row">
           
           <input type="hidden" name="role" value="super-admin">
          <div class="col-sm-6">{{textbox($errors,'Name*','name')}}</div>
          <div class="col-sm-6">{{textbox($errors,'Email*','email')}}</div>
          <div class="col-sm-6">{{textbox($errors,'Phone Number*','phone_number')}}</div>
          <div class="col-sm-6"><div class="form-group">
                             <label>Password*</label>
                                <input type="password" class="form-control valid" name="password" id="password" placeholder="Enter Password" aria-invalid="false">
                                <a href="javascript:void(0)" class="afterValid password-show" data-change="text" data-target="#password"><i class="fa fa-eye-slash"></i>
                                </a>
                                <label class="error" id="password-error" for="password" style="display: none;"></label>
                        </div></div>
           
          
                
				<div class="form-group f-g-input-file">
				<label class="filelabel">
                 <span class="material-icons">
                photo_camera
                </span>
                 <span class="title">
                    Add File
                 </span>
                
                             <input type="file" accept="image/*" id="images" name="images" class="fileInput form-control">
                             <input type="hidden" name="image" id="file_name" value="">
				 </label>
                             <label class='error' id='image-error' for="images"></label>
				 </div>
          </div>

    
    </div>

              <button type="button" class="main-button btn-submit cropped_image" style="display: none;">Crop Image</button>
              <button type="submit" id="categoryFormSbt" class="main-button btn-submit btn-form-submit">Submit</button>
  </form>

      <div class="col-sm-12">
          <div class="row">
             <div class="col-sm-12 text-center">
               <div id="upload-image" style="display: none;"></div>
            </div> 
          </div>
      </div>
</div>


 
     
<input type="hidden" id="category_url" value="<?= route('admin.category.ajax') ?>">
@endsection







@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/users.js')}}"></script>
 
@endsection


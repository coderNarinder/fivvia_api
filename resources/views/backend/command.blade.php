@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Command Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li> 
      <li class="breadcrumb-item active" aria-current="page">Command</li>
    </ol>
  </nav>
 
</div>

 
<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="addCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.command') }}"
  enctype="multipart/form-data">
   @csrf
     @include('admin.error_message')
    <div class="row">
      <div class="col-md-6"> 
          <div class="form-group">
             <label>Enter Command*</label>
             <input type="text" name="command_text" class="form-control" required>
          </div>
      </div>
 
     </div>
              
              <button type="submit" id="storyFormSbt" class="main-button btn-submit btn-form-submit">Submit</button>
          </form>
 </div>
 

 
@endsection
 


@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/awards.js')}}"></script>
<script type="text/javascript">
  CKEDITOR.replace( 'subject' );
</script>
@endsection

 
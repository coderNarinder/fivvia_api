@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Email Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.email.index')}}">Email Management</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
  </nav>
  <a href="{{route('admin.email.index')}}" class="main-button m-b-rounded" title="click here to view email templates">
    <span class="material-icons">visibility</span>
  </a>
</div>

 
<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="addCategory" role="form" method="post" id="storyForm" 
  data-action="{{ route('admin.email.create') }}"
  enctype="multipart/form-data">
   @csrf
     @include('admin.error_message')
    <div class="row">
        <div class="col-md-12">   
             {{textbox($errors,'Email Title*','title')}}
        </div>
        <div class="col-md-12">  
             {{textbox($errors,'Subject*','subject_line')}}
        </div>
        <div class="col-md-12">  
             <div class="form-group">
                  <label>Email Content<span class="mandatory">*</span></label>
                  <textarea class="form-control" placeholder="Enter Email Content" rows="4" name="subject" id="subject"></textarea>
                  <label id="ckedit" class="error"></label>
           </div>
        </div>
     </div>
              
              <button type="submit" id="storyFormSbt" class="main-button btn-submit btn-form-submit">Submit</button>
          </form>
 </div>
    <div class="col-md-12">
        <div class="row">
           <div class="col-md-6 text-center">
               <div id="upload-image" style="display: none;"></div>
            </div> 
       </div>
  </div>

 
@endsection
 


@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/email.js')}}"></script>
 <script type="text/javascript">
  CKEDITOR.replace( 'subject' );
</script>
@endsection
 
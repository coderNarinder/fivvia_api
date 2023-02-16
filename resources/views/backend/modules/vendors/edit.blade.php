@extends('backend.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Vendor Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('client.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('client.vendor.list')}}">Vendor Management</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
  </nav>
  <a href="{{route('client.vendor.list')}}" class="main-button m-b-rounded" title="view categories">
    <span class="material-icons">visibility</span>
  </a>
</div>


<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="addCategory" role="form" method="post" id="categoryForm" 
  data-action=" "
  enctype="multipart/form-data">
   @csrf
     @include('backend.error_message')
    <div class="row">
          <div class="col-sm-6">
          

          
           
        </div>
       
        
 
          <div class="col-sm-6 labels" style="display:none;">
            <div class="form-group"  >
                <label>Price label <small>For Exp : Rs.3,000 T0 5,000/- Per Person</small></label>
                <input type="text" name="price_label" class="form-control">
            </div>
          </div>
         <div class="col-sm-6 labels2" style="display:none;">
            <div class="form-group">
                <label>Duration label <small>For Exp : 04 Nights/ 05 Days</small></label>
                <input type="text" name="duration_label" class="form-control">
            </div>
          </div>
           <div class="col-sm-12 labels2" style="display:none;">
            <div class="form-group">
                <label>Tagline <small>ENJOY THE CAMP LIKE A PRO IN NATURE</small></label>
                <input type="text" name="tagline" class="form-control">
            </div>
          </div>

           <div class="col-sm-12">
            <div class="form-group">
                <label>description</label>
                <input type="text" name="description" class="form-control">
            </div>
          </div>
         
          <div class="col-sm-6">
             <div class="form-group">
               <label>Background | Banner picture</label>   
               <input type="file" name="banner" class="form-control" accept="image/*" required>
             </div>
          </div>
          <div class="col-sm-6">
             <div class="form-group">
               <label>Picture</label>   
               <input type="file" name="image" class="form-control" accept="image/*" required>
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


 
     
<input type="hidden" id="category_url" value="">
@endsection







@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/category.js')}}"></script>
<script type="text/javascript">
  $("body").on('change','#cates',function(){
       //$val = parseInt($(this).val());
       $labels = $("body").find('.labels');
       $labels2 = $("body").find('.labels2');
       $labels3 = $("body").find('.labels3');
       if(parseInt($(this).val()) > 0){
         $labels.show();
         $labels2.show();
         $labels3.show();
         $labels.find('input.form-control').attr('required','true');
         $labels2.find('input.form-control').attr('required','true');
       }else{
         $labels3.hide();
         $labels.hide();
         $labels2.hide();
          $labels.find('input.form-control').removeAttr('required');
         $labels2.find('input.form-control').removeAttr('required');

       }
  });
</script>
@endsection


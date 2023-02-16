@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Category Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.category.index')}}">Category Management</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
  </nav>
  <a href="{{route('admin.category.index')}}" class="main-button m-b-rounded" title="view categories">
    <span class="material-icons">visibility</span>
  </a>
</div>


<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="addCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.category.create') }}"
  enctype="multipart/form-data">
   @csrf
     @include('admin.error_message')
    <div class="row">
          <div class="col-sm-6">
          

          <div class="form-group">
            <label>Parent</label>
            <select class="form-control" name="parent" id="cates">
               <option value="0">select</option>
               @foreach($category as $c)
               <option value="{{$c->id}}">{{$c->label}}</option>

               @endforeach
            </select>
          </div>
           
        </div>
          <input type="hidden" name="subparent" value="0">
          <div class="col-sm-6">{{textbox($errors,'Name*','label')}}</div>

          <div class="col-12 labels3" style="display:none;">
            <div class="row" style="padding:20px 0;">
               <div class="col-4">
                <div class="form-check">
  <input class="form-check-input" type="checkbox" value="1" id="featured" name="featured" >
  <label class="form-check-label" for="featured">
    Tick as Featured Destination ?
  </label>
</div></div>
               <div class="col-4">
                <div class="form-check">
  <input class="form-check-input" type="checkbox" value="1" id="top_destination" name="top_destination" >
  <label class="form-check-label" for="top_destination">
    Tick as Top Destination ?
  </label>
</div></div>
               <div class="col-4">
                <div class="form-check">
  <input class="form-check-input" type="checkbox" value="1" id="economical_tours" name="economical_tours" >
  <label class="form-check-label" for="economical_tours">
    Tick as economical tours
  </label>
</div>
</div>
            </div>
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

          <div class="col-sm-6">   {{textbox($errors,'Meta Title*','meta_title')}}</div>
          <div class="col-sm-6">  {{textbox($errors,'Meta Tags*','meta_tag')}}</div>
          <div class="col-sm-12">  {{textarea1($errors,'Meta description*','meta_description')}}</div>
    
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


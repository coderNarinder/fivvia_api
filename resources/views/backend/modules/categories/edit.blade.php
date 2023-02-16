@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Category Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.category.index')}}">Category Management</a></li>
      @if($data->parentCategory != null && $data->parentCategory->count() > 0) 
      <li class="breadcrumb-item"><a href="{{route('admin.category.index')}}">{{$data->parentCategory->label}}</a></li>
      @endif
       @if($data->subparentCategory != null && $data->subparentCategory->count() > 0) 
      <li class="breadcrumb-item"><a href="{{route('admin.category.index')}}">{{$data->subparentCategory->label}}</a></li>
      @endif
      <li class="breadcrumb-item active" aria-current="page">{{$data->label}} </li>
    </ol>
  </nav>
  <a href="{{route('admin.category.index')}}" class="main-button m-b-rounded" title="view categories">
    <span class="material-icons">visibility</span>
  </a>
</div>


<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="editCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.category.edit',$data->slug) }}"
  enctype="multipart/form-data"> @csrf
     @include('admin.error_message')
    <div class="row">
       <div class="col-md-6"> 
          <div class="form-group">
            <label>Parent</label>
            <select class="form-control" name="parent" id="cates">
               <option value="0">select</option>
               @foreach($category as $c)
               <option value="{{$c->id}}" {{$c->id == $data->parent ? 'selected' : ''}}>{{$c->label}}</option>

               @endforeach
            </select>
          </div>
      </div>
       
       
       <div class="col-md-6"> {{textbox($errors,'Name*','label',$data->label)}}</div>

        <div class="col-12 labels3" style="display: <?= $data->parent > 0 ? 'block;' : 'none;' ?>;">
            <div class="row" style="padding:20px 0;">
               <div class="col-4">
                <div class="form-check">
  <input class="form-check-input" type="checkbox" value="1" id="featured" name="featured" {{$data->featured == 1 ? 'checked' : ''}}>
  <label class="form-check-label" for="featured">
    Tick as Featured Destination ?
  </label>
</div></div>
               <div class="col-4">
                <div class="form-check">
  <input class="form-check-input" type="checkbox" value="1" id="top_destination" name="top_destination" {{$data->top_destination == 1 ? 'checked' : ''}}>
  <label class="form-check-label" for="top_destination">
    Tick as Top Destination ?
  </label>
</div></div>
               <div class="col-4">
                <div class="form-check">
  <input class="form-check-input" type="checkbox" value="1" id="economical_tours" name="economical_tours" {{$data->economical_tours == 1 ? 'checked' : ''}}>
  <label class="form-check-label" for="economical_tours">
    Tick as economical tours
  </label>
</div>
</div>
            </div>
          </div>

         <div class="col-sm-6 labels2" style="display: <?= $data->parent > 0 ? 'block;' : 'none;' ?>;">
            <div class="form-group">
                <label>Price label <small>For Exp : Rs.3,000 T0 5,000/- Per Person</small></label>
                <input type="text" name="price_label" value="{{$data->price_label}}" class="form-control">
            </div>
          </div>
         <div class="col-sm-6 labels" style="display: <?= $data->parent > 0 ? 'block;' : 'none;' ?>;">
            <div class="form-group">
                <label>Duration label <small>For Exp : 04 Nights/ 05 Days</small></label>
                <input type="text" name="duration_label" value="{{$data->duration_label}}" class="form-control">
            </div>
          </div>

          <div class="col-sm-12 labels2" style="display: <?= $data->parent > 0 ? 'block;' : 'none;' ?>;">
            <div class="form-group">
                <label>Tagline <small>ENJOY THE CAMP LIKE A PRO IN NATURE</small></label>
                <input type="text" name="tagline" value="{{$data->tagline}}" class="form-control">
            </div>
          </div>

           <div class="col-sm-12">
            <div class="form-group">
                <label>description</label>
                <textarea name="description" class="form-control">{{$data->description}}</textarea>
            </div>
          </div>
       
      <div class="col-md-6">{{textbox($errors,'Meta Title*','meta_title',$data->meta_title)}}</div>
      <div class="col-md-6">  {{textbox($errors,'Meta Tags*','meta_tag',$data->meta_key)}}</div>
      <div class="col-md-12">  {{textarea1($errors,'Meta description*','meta_description',$data->meta_description)}}</div>
       

       <div class="col-sm-6">
             <div class="form-group">
               <label>Background | Banner picture</label>   
               <input type="file" name="banner" class="form-control" accept="image/*">
                @if($data->banner)
                    <img src="{{url($data->banner)}}" id="image_src" style="width: 100px; height: 100px;margin:5px;" class="img-thumbnail"/>
                  @endif
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
 
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/category.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
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
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
          <div class="col-sm-3">
              <div class="card-box text-center p-0 overflow-hidden" style="">
               <div class="background pt-3" style="background:url(https://tours.fivvia.com/pictures/business/banner/16660748173j6YUjoHdmgybaZHmkyKslide8.jpg) no-repeat center center;background-size:cover;">
                  <div class="vendor_text" style="background: #00000052;">
                            <img src="https://tours.fivvia.com/pictures/business/logo/1666074817HgbMccDdSbgEd2iNnQRepmxshw5y3iilbsqbv0rv.jpg" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                            <h4 class="mb-0 text-white">{{$vendor->name}}</h4>
                               <p class="text-white">{{$vendor->address}}</p>
                               <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light openEditModal" data-toggle="modal" data-target="#exampleModal"> Edit </button>
                               <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light" id="block_btn" data-vendor_id="21" data-status="2">Block</button>
                  </div>
                </div>
                <div class="text-left mt-0 p-3">
                    <p class="text-muted font-13 mb-0">
                        {{$vendor->desc}}
                    </p>
                </div>
              </div>

              @include('backend.modules.vendors.vendorCategoryList')
          </div>
       
        
          <div class="col-sm-9">
             <div class="card-box">
                 <div class="col-12 text-right"><a class="main-button btn-submit btn-form-submit" href="{{route('client.vendor.product.create',$vendor->slug)}}">Add Product</a></div>
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


 
     



    <div id="add-product" class="modal fade add_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Add Product') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="save_product_form" method="post" enctype="multipart/form-data"
                    action="{{ route('product.store') }}">
                    @csrf
                    <div class="modal-body pb-0">

                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="form-group" id="product_nameInput">
                                            {!! Form::label('title', __('Product Name'), ['class' => 'control-label']) !!}
                                            <span class="text-danger">*</span>
                                            {!! Form::text('product_name', null, ['class' => 'form-control', 'id' => 'product_name','placeholder' => __('Product Name'), 
                                            'autocomplete' => 'off']) !!}

                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group" id="skuInput">
                                            {!! Form::label('title', __('SKU'), ['class' => 'control-label']) !!}
                                            <span class="text-danger">*</span>
                                            {!! Form::text('sku', null, ['class' => 'form-control', 'id' => 'sku', 'onkeyup' => 'return alplaNumeric(event)', 'placeholder' =>  __('SKU')]) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                            <span class="valid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                            {!! Form::hidden('type_id', 1) !!}
                                            {!! Form::hidden('vendor_id', $vendor->id) !!}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group" id="url_slugInput">
                                            {!! Form::label('title', __('URL Slug'), ['class' => 'control-label']) !!}
                                            {!! Form::text('url_slug', null, ['class' => 'form-control', 'id' => 'url_slug', 'placeholder' =>  __('URL Slug'), 'onkeypress' => 'return slugify(event)']) !!}
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>

                                     <div class="col-6">
                                        <div class="form-group">
                                           <label>Type</label>
                                           <select class="form-control" name="business_type" required="">
                                             <option value="">Choose</option>
                                               @foreach($business_types as $t)
                                                <option value="{{$t->id}}">{{$t->title}}</option>
                                               @endforeach
                                           </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group" id="categoryInput">
                                            {!! Form::label('title', __('Category'),['class' => 'control-label']) !!}
                                        <select class="form-control selectizeInput" id="category_list" name="category">
                                           
                                            @foreach($product_categories as $product_category)
                                                <option value="{{$product_category['id']}}">{{$product_category['hierarchy']}}</option>
                                            @endforeach
                                       </select>
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-info waves-effect waves-light submitProduct">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 

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


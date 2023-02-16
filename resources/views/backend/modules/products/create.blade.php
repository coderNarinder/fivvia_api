@extends('backend.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Package Management</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('client.dashboard')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('client.vendor.view',$vendor->slug)}}">{{$vendor->name}}</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
  </nav>
  <a href="{{route('client.vendor.view',$vendor->slug)}}" class="main-button m-b-rounded" title="view categories">
    <span class="material-icons">visibility</span>
  </a>
</div>


<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="addCategory" role="form" method="post" id="categoryForm" 
  data-action="{{ route('client.vendor.product.create',$vendor->slug) }}"
  enctype="multipart/form-data">
   @csrf

     @include('backend.error_message')
     <div class="row">
          <div class="col-md-8"> 
               <div class="card-box">
                  <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">General</h5>
                          <div class="row">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                       <label>Choose Category</label>
                                       <select class="form-control" name="category">
                                        @foreach($categories as $cates)
                                           <option>{{$cates->translation_one->name}}</option>
                                        @endforeach
                                       </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                  <div class="form-group"  >
                                      <label>Tour Name</label>
                                      <input type="number" name="price" class="form-control" required>
                                  </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="form-group">
                                      <label></label>
                                       
                                  </div>
                                </div>
                                <div class="col-sm-6">
                                   <div class="form-group">
                                     <label>Picture</label>   
                                     <input type="file" name="image" class="form-control" accept="image/*" required>
                                   </div>
                                </div>

                                 <div class="col-sm-12">
                                  <div class="form-group">
                                      <label>description</label>
                                      <textarea name="description" class="form-control" required></textarea>
                                  </div>
                                </div>
                                 
                          
                          </div>
               </div>

               <div class="card-box ">
                    <div class="row mb-2 bg-light">
                        <div class="col-8" style="margin:auto; padding: 8px !important;">
                            <h5 class="text-uppercase  mt-0 mb-0">{{ __("Product Information") }}</h5>
                        </div>
                        <div class="col-4 p-2 mt-0" style="margin:auto; padding: 8px !important;">
                            <select class="selectize-select form-control" id="language_id" name="language_id">
                                @foreach($languages as $lang)
                                <option value="{{$lang->langId}}" {{ ($lang->is_primary == 1) ? 'selected' : ''}}>{{$lang->langName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Product Name'),['class' => 'control-label']) !!}
                            {!! Form::text('product_name','', ['class'=>'form-control', 'id' => 'product_name', 'placeholder' => 'Apple iMac', 'required' => 'required']) !!}
                        </div>
                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Product Description'),['class' => 'control-label']) !!}
                            {!! Form::textarea('body_html','', ['class'=>'form-control', 'id' => 'body_html', 'placeholder' => 'Description', 'rows' => '5']) !!}
                        </div>
                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Meta Title'),['class' => 'control-label']) !!}
                            {!! Form::text('meta_title','', ['class'=>'form-control', 'id' => 'meta_title', 'placeholder' => 'Meta Title']) !!}
                        </div>
                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Meta Keyword'),['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_keyword','', ['class'=>'form-control', 'id' => 'meta_keyword', 'placeholder' => 'Meta Keyword', 'rows' => '3']) !!}
                        </div>
                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Meta Description'),['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_description','', ['class'=>'form-control', 'id' => 'meta_description', 'placeholder' => 'Meta Description', 'rows' => '3']) !!}
                        </div>
                    </div>
                </div>


          </div>
      </div>
    

              
              <button type="submit" id="categoryFormSbt" class="main-button btn-submit btn-form-submit">Submit</button>
  </form>

      
</div>


  
@endsection







@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/products.js')}}"></script>
<script type="text/javascript">
  
</script>
@endsection


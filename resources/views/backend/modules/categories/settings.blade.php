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
  <a href="{{route('admin.category.index')}}" class="main-button m-b-rounded">
    <span class="material-icons">visibility</span>
  </a>
</div>
<div class="wrapper-shadow-inset"> 
@include('admin.error_message')
<div class="col-md-12"> 
  
 
     <form class="form-edit-category f-e-c-variation" name="addCategory" role="form" method="post" id="categoryForm" 
           data-action="{{ route('admin.category.settings',$category->id) }}"
           enctype="multipart/form-data">
          <div class="row">

                    @csrf
                    
                            <div class="col-md-12">
                               <h5>Choose The Variable Variations</h5>
                               <div class="form-group">
                                           <div class="custom-control custom-checkbox">
                                            <input type="checkbox"   
                                                  class="custom-control-input allChecks" 
                                                  id="customCheck-allchecks-1" 
                                                  data-class=".customCheck-all-1">
                                                  <label class="custom-control-label" for="customCheck-allchecks-1">All</label>
                                           </div> 
                                       @foreach($variations as $sv)
                                          <div class="custom-control custom-checkbox">
                                          <input type="checkbox" name="variable_variations[]" 
                                                 value="{{$sv->id}}" 
                                                 class="custom-control-input customCheck-all-1" 
                                                 id="customCheck-{{$sv->id}}" {{in_array($sv->id,$variable_variations) ? 'checked' : ''}}>
                                                 <label class="custom-control-label" for="customCheck-{{$sv->id}}">{{$sv->label}}</label>
                                          </div>  
                                       
                                        @endforeach
                              
                               </div>

                             </div>

                              <div class="col-md-12">
                               <h5>Choose The Filter Variations</h5>
                               <div class="form-group">
                                           <div class="custom-control custom-checkbox">
                                            <input type="checkbox"   
                                                  class="custom-control-input allChecks" 
                                                  id="customCheck-allchecks-2" 
                                                  data-class=".customCheck-all-2">
                                                  <label class="custom-control-label" for="customCheck-allchecks-2">All</label>
                                           </div> 
                                       @foreach($variations as $sv)
                                          <div class="custom-control custom-checkbox">
                                          <input type="checkbox" name="filter_variations[]" 
                                                 value="{{$sv->id}}" 
                                                 class="custom-control-input customCheck-all-2" 
                                                 id="customCheck-filter-variation{{$sv->id}}" 
                                                 {{in_array($sv->id,$filter_variations) ? 'checked' : ''}}>
                                                 <label class="custom-control-label" for="customCheck-filter-variation{{$sv->id}}">{{$sv->label}}</label>
                                          </div>  
                                       @endforeach
                              
                               </div>

                             </div>

                            <div class="col-md-12">
                               <h5>Choose Product Layout for This Category</h5>
                               <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                          <input type="radio" name="product_layout_type" 
                                                 value="1" 
                                                 class="custom-control-input" 
                                                 <?= $product_layout_type == 1 ? 'checked' : ''?>
                                                 id="customCheck-theme-for-product-1">
                                                 <label class="custom-control-label" for="customCheck-theme-for-product-1">
                                                     Layout One
                                                 </label>
                                        </div> 

                                        <div class="custom-control custom-checkbox">
                                          <input type="radio" name="product_layout_type" 
                                                 value="2" 
                                                 class="custom-control-input" 
                                                 <?= $product_layout_type == 2 ? 'checked' : ''?>
                                                 id="customCheck-theme-for-product-2">
                                                 <label class="custom-control-label" for="customCheck-theme-for-product-2">
                                                     Layout Two
                                                 </label>
                                        </div>

                                        <div class="custom-control custom-checkbox">
                                          <input type="radio" name="product_layout_type" 
                                                 value="3" 
                                                 class="custom-control-input" 
                                                  <?= $product_layout_type == 3 ? 'checked' : ''?>
                                                 id="customCheck-theme-for-product-3">
                                                 <label class="custom-control-label" for="customCheck-theme-for-product-3">
                                                     Layout Three
                                                 </label>
                                        </div> 
                               </div>
                            </div>



                             <div class="col-md-12">
                               <h5>Choose Product Listing By </h5>
                                <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                          <input type="radio" name="product_listing" 
                                                 value="1" 
                                                 class="custom-control-input" 
                                                  <?= $product_listing == 1 ? 'checked' : ''?>
                                                 id="customCheck-product-listing-1">
                                                 <label class="custom-control-label" for="customCheck-product-listing-1">
                                                     Parent Product listing
                                                 </label>
                                        </div> 
                                        <div class="custom-control custom-checkbox">
                                          <input type="radio" name="product_listing" 
                                                 value="2" 
                                                 class="custom-control-input" 
                                                  <?= $product_listing == 2 ? 'checked' : ''?>
                                                 id="customCheck-product-listing-2">
                                                 <label class="custom-control-label" for="customCheck-product-listing-2">
                                                     Child Product listing
                                                 </label>
                                        </div> 
                                </div>
                            </div>
                            <div class="col-md-12">
                               <h5>Comission (In Percent)</h5>
                                <div class="form-group">
                                         <input type="number" min="0" name="comission" value="{{$category->comission}}" class="form-control">
                                </div>
                            </div>


                                                 

              </div>
              <button type="submit" id="categoryFormSbt" class="main-button">Assign Variations</button>
 
</form>
 
 
@endsection
 

@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/category.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
 
@endsection


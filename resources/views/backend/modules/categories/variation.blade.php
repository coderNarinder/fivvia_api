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
  <a href="{{route('admin.category.index')}}" class="main-button m-b-rounded" title="view category">
    <span class="material-icons">visibility</span>
  </a>
</div>
<div class="wrapper-shadow-inset"> 
@include('admin.error_message')
<div class="col-md-12"> 
 @if($variations->count() > 0)
 <button type="button" class="main-button" data-toggle="modal" data-target="#exampleModal">
      Add Types
 </button>
 @endif
 @if($variations->count() > 0)
     <form class="form-edit-category f-e-c-variation" name="addCategory" role="form" method="post" id="categoryForm" 
           data-action="{{ route('admin.category.variation',$category->id) }}"
           enctype="multipart/form-data">
          <div class="row">

                    @csrf
                    @foreach($variations->get() as $v)
                         @if($v->subVariations != null && $v->subVariations->count() > 0)
                            <div class="col-md-12">
                               <h5>{{$v->label}}</h5>
                               <div class="form-group">
                                  <!-- <label>{{$v->label}}</label>
                                   <select class="form-control select2" name="{{$v->slug}}[]" multiple="true">  -->
                                        <div class="custom-control custom-checkbox">
                                          <input type="checkbox"   
                                                  class="custom-control-input allChecks" 
                                                  id="customCheck-allchecks-{{$v->id}}" 
                                                  data-class=".customCheck-all-{{$v->id}}">
                                                  <label class="custom-control-label" for="customCheck-allchecks-{{$v->id}}">All</label>
                                          </div> 
                                       @foreach($v->subVariations as $sv)
                                       <?php  //$variationArray2 = !empty($category->variations) != null && $category->variations->count() > 0 ? $category->variations->pluck('variant_id')->toArray() : [];

                                        $checked = !empty($category->variations->where('type',$v->slug)->where('variant_id',$sv->id)) && $category->variations->where('type',$v->slug)->where('variant_id',$sv->id)->count() == 1 ? 'checked' : '';
                                        ?>


                                          <div class="custom-control custom-checkbox">
                                          <input type="checkbox" name="{{$v->slug}}[]" 
                                                 value="{{$sv->id}}" 
                                                 class="custom-control-input customCheck-all-{{$v->id}}" 
                                                 id="customCheck-{{$sv->id}}" {{$checked}}>
                                                 <label class="custom-control-label" for="customCheck-{{$sv->id}}">{{$sv->label}}</label>
                                          </div>   
                                        @endforeach
                              <!-- </select> -->
                              </div>

                             </div>

                         @endif

                    @endforeach



                                                 

              </div>
              <button type="submit" id="categoryFormSbt" class="main-button">Assign Variations</button>
 
</form>

@else


<div class="alert alert-info" role="alert">
<h4 class="alert-heading">This category has not assigned Variation Type Yet!</h4>
<hr>
<p class="mb-0">
<button type="button" class="main-button" data-toggle="modal" data-target="#exampleModal">
Add Types
</button>
</p>
</div>

<!-- Button trigger modal -->


@endif


<!-- Modal -->
<div class="modal fade lgModal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
  
  <form role="form" method="post" id="categoryForm2" enctype="multipart/form-data" class="modal-content" 
  data-action="{{ route('admin.category.variation',$category->id) }}">
    <input type="hidden" name="types" value="1">
     
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Assign Variation Type</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <div class="col-12">
             <div class="row">
                <div class="col-12">
                  <div class="custom-control custom-checkbox">
                      <input type="checkbox"   
                              class="custom-control-input allChecks" 
                              id="customCheck-allchecks" 
                              data-class=".customCheck-all">
                              <label class="custom-control-label" for="customCheck-allchecks">All</label>
                  </div> 
                </div>
                  <div class="col-4">
                    @php $list = 0; @endphp
                  @foreach($VariationTypes as $k => $sv)
                      <div class="custom-control custom-checkbox" data-count="{{$list++}}">
                          <input type="checkbox" name="related_variations[]" 
                                                 value="{{$sv->id}}" 
                                                 class="custom-control-input customCheck-all" 
                                                 id="customCheck-type-{{$sv->id}}" {{in_array($sv->id,$related_variations) ? 'checked' : ''}}>
                                                 <label class="custom-control-label" for="customCheck-type-{{$sv->id}}">{{$sv->label}}</label>
                      </div>  
                      @if($list == 20)
                       @php $list = 0; @endphp
                      </div>
                      <div class="col-4">
                      @endif

                   @endforeach
                    </div>                    

              </div>
            
        </div>
      
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="submit" class="main-button">Save changes</button>
    </div>
  </form>
  
</div>
</div>
        

@endsection
 

@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/category.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
 
@endsection


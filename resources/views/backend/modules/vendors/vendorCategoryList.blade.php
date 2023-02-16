<div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mb-2"> <span class="">{{ __("Category Setup") }}</span> ({{ __("Visible For Admin") }})</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
            {!! Form::label('title', __('Can Add Category'),['class' => 'control-label']) !!}
            
             <div class="checkbox switcher">
                                <label for="test-dasas">
                                    <input type="checkbox" class="changeStatusToggle" id="test-dasas"  @if($vendor->add_category == 1) checked @endif {{$vendor->status == 1 ? '' : 'disabled'}}>
                                     <span><small></small></span>
                                      
                                </label>
                            </div>
        </div>
         
       
        <div class="col-md-12">
            {!! Form::label('title', __('Vendor Category'),['class' => 'control-label']) !!}
            <div class="custom-dd dd nestable_list_1" id="nestable_list_1">
                <ol class="category-choose-list">
                    @forelse($categories as $cate)
                     @if(!empty($cate->translation_one))
                    <li>
                        <div class="">
                            <img class="rounded-circle mr-1" src="{{$cate->icon['image_path']}}"> {{$cate->translation_one->name}}
                              <div class="checkbox switcher">
                                <label for="test-{{$cate->id}}">
                                    <input type="checkbox" class="changeStatusToggle" id="test-{{$cate->id}}" checked>
                                     <span><small></small></span>
                                      
                                </label>
                            </div>
                             
                        </div>
                     </li>
                    @endif
                   
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</div>
 
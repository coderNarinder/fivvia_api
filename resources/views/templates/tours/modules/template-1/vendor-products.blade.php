
@extends(getTemplateLayoutPath('layout'))
 @section('css')
 <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
@endsection
@section('content')



  <!-- Hero section start -->
    <div class="py-9 bg-gray-light"  @if(!empty($vendor->banner)) style="background: url({{$vendor->banner['image_path']}});" @endif>
        <div class="container">
                   <div class="grid grid-cols-12 gap-x-4">
                       <div class="row mt-n4">
                            <div class="col-12">
                                <form action="">
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <div class="file file--upload">
                                                <label>
                                                    <span class="update_pic border-0">
                                                    <img class="img-fluid blur-up lazyload" data-src="{{$vendor->logo['image_path']}}" alt="">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="name_location d-block py-0">
                                                <h4 class="mt-0 mb-1"><b>{{$vendor->name}}</b></h4>
                                            </div>
                                            @if($vendor->is_show_vendor_details == 1)
                                                <div class="">
                                                    @if($vendor->email)
                                                        <a href="{{$vendor->email}}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->email}}"><i class="fa fa-envelope"></i></a>
                                                    @endif
                                                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->address}}"><i class="fa fa-address-card mx-1"></i></a>
                                                    @if($vendor->website)
                                                        <a href="{{http_check($vendor->website) }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->website}}"><i class="fa fa-home"></i></a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        @if($vendor->desc)
                                            <div class="col-md-12 text-center">
                                                <p>{{$vendor->desc}}</p>
                                            </div>
                                        @endif
                                            @php
                                               $checkSlot = findSlot('',$vendor->id,'');
                                            @endphp
                                        <div class="col-md-12 text-center">
                                            @if($vendor->is_vendor_closed == 1 && $checkSlot == 0)
                                            <p class="text-danger">{{('Restaurant is not accepting orders right now.')}}</p>
                                            @elseif($vendor->is_vendor_closed == 1 && $vendor->closed_store_order_scheduled == 1)
                                            <p class="text-danger">{{__('We are not accepting orders right now. You can schedule this for '). $checkSlot }}.</p>
                                            @endif
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
 <!-- Hero section end -->



 
  <!-- blog grid section start -->

    <div class="py-24">
        <div class="container">
            <div class="flex flex-wrap flex-col lg:flex-row -mx-4">
                <div class="lg:w-1/4 px-4 order-last lg:order-first mt-8 lg:mt-0">
                    <div>
                    
                        @if(!empty($brands) && count($brands) > 0)
                            <div class="mb-12">
                                <h4 class="font-medium text-md lg:text-lg text-dark capitalize mb-5">{{__('Brand')}}</h4>
                                <ul>
                                    @foreach($brands as $key => $val)
                                        <li class="mb-5 flex justify-between items-center transition-all hover:text-orange">
                                              <input type="checkbox" class="checkbox opacity-0 absolute custom-control-input productFilter" 
                                              fid="{{$val->brand_id}}" 
                                              used="brands" id="brd{{$val->brand_id}}"> 
                                            @foreach($val->brand->translation as $k => $v)
                                                <label for="brd{{$val->brand_id}}" class="relative cursor-pointer before:empty before:inline-block before:w-5 before:h-5 before:bg-white before:border-2 before:border-solid before:border-gray-300 before:rounded before:mr-4 align-middle flex items-center">{{$v->title}} </label>
                                            @endforeach
                                        </li>
                                    @endforeach
                                 </ul>
                            </div>
                        @endif
                        @if(!empty($variantSets) && count($variantSets) > 0)
                        @foreach($variantSets as $key => $sets)
                        <div class="collection-collapse-block border-0 mb-2 open pt-2 pb-0 border-0">
                            @php
                            $slug = $sets->variantDetail->varcategory->cate ? $sets->variantDetail->varcategory->cate->slug.' > ' : '';
                            @endphp
                            @if($slug)
                            <h3 class="collapse-block-title"> {{$slug . $sets->title}}</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    @if($sets->type == 2)
                                        @foreach($sets->options as $ok => $opt)
                                        <div class="chiller_cb small_label d-inline-block color-selector mt-2">
                                            <?php $checkMark = ($key == 0) ? 'checked' : ''; ?>
                                            <input class="custom-control-input productFilter" type="checkbox" {{$checkMark}} id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" used="variants" optid="{{$opt->id}}">
                                            <label for="Opt{{$key.'-'.$opt->id}}"></label>
                                            @if(strtoupper($opt->hexacode) == '#FFF' || strtoupper($opt->hexacode) == '#FFFFFF')
                                            <span style="background: #FFFFFF; border-color:#000;" class="check_icon white_check"></span>
                                            @else
                                            <span class="check_icon" style="background:{{$opt->hexacode}}; border-color: {{$opt->hexacode}};"></span>
                                            @endif
                                        </div>
                                        @endforeach
                                    @else
                                        @foreach($sets->options as $ok => $opt)
                                        <div class="custom-control custom-checkbox collection-filter-checkbox">
                                            <input type="checkbox" class="custom-control-input productFilter" id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" type="variants" optid="{{$opt->id}}">
                                            <label class="custom-control-label" for="Opt{{$key.'-'.$opt->id}}">{{$opt->title}}</label>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                        @endif
                        @if($show_range == 1)
                        <div class="collection-collapse-block border-0 mb-2 pt-2 open">
                            <h3 class="collapse-block-title">{{__('Price')}}</h3>
                            <div class="collection-collapse-block-content">
                                <div class="wrapper mt-3">
                                    <div class="range-slider">
                                        <input type="text" class="js-range-slider rangeSliderPrice" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                         <div class="mb-12">
                     <h4 class="font-medium text-md lg:text-lg text-dark capitalize mb-10">{{__('New Product')}}</h4>
                        <div class="offer-slider">
                            @if(!empty($newProducts) && count($newProducts) > 0)
                                @foreach($newProducts as $newProds)
                                    <div>
                                    @foreach($newProds as $new)
                                      <a class="common-product-box scale-effect text-center border-bottom pb-2 mt-2" href="{{route('productDetail', [$new['vendor']['slug'],$new['url_slug']])}}">
                                            <div class="img-outer-box position-relative">
                                                <img class="blur-up lazyload" data-src="{{$new['image_url']}}" alt="">
                                                <div class="pref-timing">
                                                    <!--<span>5-10 min</span>-->
                                                </div>
                                                {{--<i class="fa fa-heart-o fav-heart" aria-hidden="true"></i>--}}
                                            </div>
                                            <div class="media-body align-self-center">
                                                <div class="inner_spacing px-0">
                                                    <div class="product-description">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="card_title mb-1 ellips">{{ $new['translation_title'] }}</h6>
                                                            <!--<span class="rating-number">2.0</span>-->
                                                        </div>
                                                        <!-- <h3 class="mb-0 mt-2">{{ $new['translation_title'] }}</h3> -->
                                                        <p>{{$new['vendor']['name']}}</p>
                                                        <p class="pb-1">{{__('In')}} {{$new['category_name']}}</p>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <b>
                                                                @if($new['inquiry_only'] == 0)
                                                                    <?php $multiply = $new['variant_multiplier']; ?>
                                                                    {{ Session::get('currencySymbol').' '.(number_format($new['variant_price'] * $multiply,2))}}
                                                                @endif
                                                            </b> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a> 
                                    @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>
            </div>



  <div id="shoptab" class="flex-1">
                    <div class="flex flex-wrap justify-between items-center px-4">
                     
                        <div>
                            <ul class="shop-tab-nav flex flex-wrap">
                                <li class="active"><a href="#grid" class="text-base hover:text-orange inline-block py-2 px-2"><i class="icon-grid"></i></a></li>
                                <li><a href="#list" class="text-base hover:text-orange inline-block py-2 px-2 ml-5"><i class="icon-menu"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-10 displayProducts">
                        <div id="grid" class="shop-tab-content active">
                            <div class="flex flex-wrap -mb-7 -px-4">

                               @if($listData->isNotEmpty())
                                    @foreach($listData as $key => $data) 
                                           <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-7">
                                    <div class="border border-solid border-gray-300 transition-all hover:shadow-product group relative">
                                        <div class="relative overflow-hidden">
                                            
                                            <img class="w-full h-full" 
                                            src="{{$data->image_url}}" alt="product image" loading="lazy" width="432" height="480" />
                                            <!-- actions start -->

                                            <div class="absolute left-2/4 top-2/4 transform -translate-x-2/4 -translate-y-2/4 z-10">
                                                <ul class="flex items-center justify-center bg-white shadow rounded-full h-0 transition-all group-hover:h-16 duration-500 overflow-hidden">
                                                    <li class="py-4 pl-7 md:py-5 md:pl-8">
                                                        <a href="<?= route('productDetail', [$data->vendor->slug,$data->url_slug])?>" class="text-dark flex items-center justify-center text-md hover:text-orange modal-toggle" aria-label="quick view" data-tippy-content="Quick View">
                                                            <i class="icon-magnifier"></i>
                                                        </a>
                                                    </li>
                                                    <li class="py-4 pl-7 pr-7 md:py-5 md:pl-8 md:pr-8">
                                                        <a href="<?= route('productDetail', [$data->vendor->slug,$data->url_slug])?>" class="text-dark flex items-center justify-center text-md hover:text-orange modal-toggle" aria-label="add to cart" data-tippy-content="Add to cart">
                                                            <i class="icon-bag"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            
                                        </div>

                                        <div class="py-5 px-4">
                                            <h4><a class="block text-base hover:text-orange transition-all" href="<?= route('productDetail', [$data->vendor->slug,$data->url_slug])?>">
                                                <label class="mb-0"><b>{{ $data->translation_title }}</b></label>
                                                                    @if($client_preference_detail)
                                                                        @if($client_preference_detail->rating_check == 1)
                                                                            @if($data->averageRating > 0)
                                                                                <span class="rating">{{ number_format($data->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                                                            @endif
                                                                        @endif
                                                                    @endif</a></h4>
                                             
                                            <h6 class="mt-0 mb-1"><b>{{$data->vendor->name}}</b></h6>

                                             @if($data->inquiry_only == 0)
                                                     <h5 class="font-bold text-md leading-none text-orange mt-3">{{Session::get('currencySymbol').' '.(number_format($data->variant_price * $data->variant_multiplier,2))}}</h5>
                                                @endif
                                        </div>
                                    </div>

                                </div>
                                                @endforeach
                                              @else
                                                <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{ __('No Product Found') }}</h5></div>
                                              @endif



                             

                                 
                                 
 


                            </div>




                        </div>
                        <div id="list" class="shop-tab-content">
                            <div class="flex flex-wrap -mb-7 -px-4">

                                @if($listData->isNotEmpty())
                                    @foreach($listData as $key => $data) 
                                           <div class="w-full px-4 mb-7">
                                    <div class="border border-solid border-gray-300 transition-all hover:shadow-product group relative flex flex-wrap flex-col md:flex-row">
                                        <div class="relative overflow-hidden md:w-1/3">
                                         <img class="md:absolute w-full md:h-full md:object-cover" src="{{$data->image_url}}" alt="product image" loading="lazy" width="432" height="480" />
                                            <!-- actions start -->

                                            <div class="absolute left-2/4 top-2/4 transform -translate-x-2/4 -translate-y-2/4 z-10">
                                                <ul class="flex items-center justify-center bg-white shadow opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all ease-linear transform translate-y-4 group-hover:-translate-y-0">
                                                    <li>
                                                        <a 
                                                        href="{{route('productDetail', [$data->vendor->slug,$data->url_slug])}}" 
                                                            class="product-box scale-effect mt-0 text-dark flex items-center justify-center text-md hover:text-orange modal-toggle px-4 py-4" aria-label="quick veiw" data-tippy-content="Quick View">
                                                            <i class="icon-magnifier-add"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- actions end -->


                                            <!-- variants start -->

                                            <div class="p-2 bg-gray-200 shadow absolute left-2 right-2 -bottom-40 group-hover:bottom-2 z-20 transition-all duration-500 ease-linear">
                                                <ul class="flex flex-wrap items-center justify-center mb-3">
                                                    <li class="mx-1 leading-none"><button class="text-sm">sm</button></li>
                                                    <li class="mx-1 leading-none"><button class="text-sm">l</button></li>
                                                    <li class="mx-1 leading-none"><button class="text-sm">m</button></li>
                                                    <li class="mx-1 leading-none"><button class="text-sm">xl</button></li>
                                                    <li class="mx-1 leading-none"><button class="text-sm">xxl</button></li>
                                                </ul>
                                                <ul class="flex flex-wrap items-center justify-center">
                                                    <li class="mx-1 leading-none"><button class="w-4 h-4 rounded-full bg-orange" aria-label="colors"></button></li>
                                                    <li class="mx-1 leading-none"><button class="w-4 h-4 rounded-full bg-primary" aria-label="variants"></button></li>
                                                    <li class="mx-1 leading-none"><button class="w-4 h-4 rounded-full bg-indigo-600" aria-label="variants"></button></li>
                                                    <li class="mx-1 leading-none"><button class="w-4 h-4 rounded-full bg-dark" aria-label="variants"></button></li>
                                                </ul>
                                            </div>

                                            <!-- variants end -->
                                        </div>

                                        <div class="py-5 px-4 flex-1">
                                            <h4><a class="block text-md hover:text-orange transition-all mb-2" href="{{route('productDetail', [$data->vendor->slug,$data->url_slug])}}">
                                                {{ $data->translation_title }}</a>

                                                 @if($client_preference_detail)
                                                                        @if($client_preference_detail->rating_check == 1)
                                                                            @if($data->averageRating > 0)
                                                                                <span class="rating">{{ number_format($data->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                            </h4>
                                             <h6 class="mt-0 mb-1"><b>{{$data->vendor->name}}</b></h6>
                                            <p class="text-sm"><?= strlen($data->translation_description) >= 150 ? substr($data->translation_description, 0, 150)." ..." : $data->translation_description ?></p>

 
                                                @if($data->inquiry_only == 0)
                                                     <h5 class="font-bold text-md leading-none text-orange mt-4 mb-4">{{Session::get('currencySymbol').' '.(number_format($data->variant_price * $data->variant_multiplier,2))}}</h5>
                                                @endif

                                           

                                            <ul class="flex items-center">
                                                
                                                <li class="mr-2">
                                                    <a href="<?= route('productDetail', [$data->vendor->slug,$data->url_slug])?>" class="text-dark flex items-center justify-center text-md hover:text-white border border-solid border-dark hover:bg-orange transition-all px-4 md:px-5 py-3 leading-none hover:border-orange modal-toggle" aria-label="Add to cart" data-tippy-content="Add to cart">
                                                        <i class="icon-basket-loaded"></i>
                                                        <span class="text-sm ml-2">Add to cart</span>

                                                    </a>
                                                </li>
                                               

                                            </ul>
                                        </div>
                                    </div>

                                </div>
                                                @endforeach
                                              @else
                                                <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{ __('No Product Found') }}</h5></div>
                                              @endif



                                
 

                            </div>




                        </div>
                    </div>

                    <div class="mt-12">
                     @if(!empty($listData)) {{ $listData->links() }} @endif
                    </div>
                </div>





            
    </div>
  </div>
</div>






@endsection

@section('j_s')
<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
<script>
    $('.js-range-slider').ionRangeSlider({
        type: 'double',
        grid: false,
        min: "{{floor($range_products->last() ? $range_products->last()->price * (!empty(Session::get('currencyMultiplier'))?Session::get('currencyMultiplier'):1) : 0)}}",
        max: "{{ceil($range_products->first() ? $range_products->first()->price * (!empty(Session::get('currencyMultiplier'))?Session::get('currencyMultiplier'):1) : 1000)}}",
        from: "{{floor($range_products->last() ? $range_products->last()->price * (!empty(Session::get('currencyMultiplier'))?Session::get('currencyMultiplier'):1) : 0)}}",
        to: "{{ceil($range_products->first() ? $range_products->first()->price * (!empty(Session::get('currencyMultiplier'))?Session::get('currencyMultiplier'):1) : 1000)}}",
        prefix: ""
    });

    var ajaxCall = 'ToCancelPrevReq';
    $('.js-range-slider').change(function() {
        filterProducts();
    });

    $('.productFilter').click(function() {
        filterProducts();
    });

    function filterProducts() {
        var brands = [];
        var variants = [];
        var options = [];
        $('.productFilter').each(function() {
            var that = this;
            if (this.checked == true) {
                var forCheck = $(that).attr('used');
                if (forCheck == 'brands') {
                    brands.push($(that).attr('fid'));
                } else {
                    variants.push($(that).attr('fid'));
                    options.push($(that).attr('optid'));
                }
            }
        });
        var range = $('.rangeSliderPrice').val();

        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('vendorDetail', $vendor->slug) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "brands": brands,
                "variants": variants,
                "options": options,
                "range": range
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                $('.displayProducts').html(response.html);
            },
            error: function(data) {
                //location.reload();
            },
        });
    }
</script>
@endsection

	
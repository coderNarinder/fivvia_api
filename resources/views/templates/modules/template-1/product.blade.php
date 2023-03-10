
@extends(getTemplateLayoutPath('layout'))
 @section('css')
    <link rel="stylesheet" href="https://www.jqueryscript.net/css/jquerysctipttop.css">
    <link rel="stylesheet" href="https://www.jqueryscript.net/demo/Product-Carousel-Magnifying-Effect-exzoom/jquery.exzoom.css">
    <link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .btn-disabled{
        opacity:0.5;
        pointer-events: none;
    }
    .fab {
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
    }
    #number{
        display:block;
    }
    #exzoom{
        display:none;
    }
    .exzoom .exzoom_btn a.exzoom_next_btn{
        right: -12px;
    }
    .exzoom .exzoom_nav .exzoom_nav_inner{
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        transition: all 0.5s;
    }
    @media screen and (max-width: 768px) {
        .exzoom .exzoom_zoom_outer {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div style="display: none;">
      @if(isset($set_template)  && $set_template->template_id == 1)
        @include('layouts.store/left-sidebar-template-one')
        @elseif(isset($set_template)  && $set_template->template_id == 2)
        @include('layouts.store/left-sidebar')
        @else
        @include('layouts.store/left-sidebar-template-one')
        @endif
</div>
    <!-- Hero section start -->
    <div class="py-9 bg-gray-light">
        <div class="container">
            <div class="grid grid-cols-12 gap-x-4">
                <div class="col-span-12">
                   <!--  <nav>
                        <ul class="flex flex-wrap items-center justify-center">
                            <li class="mr-5"><a href="index.html" class="text-dark font-medium text-base uppercase transition-all hover:text-orange relative before:w-5 before:h-1px before:empty before:absolute before:top-3 before:bg-dark before:transform before:rotate-115 before:-right-5">Home</a></li>
                            <li class="text-dark font-medium text-base uppercase mr-5">Airp Variable product</li>
                        </ul>
                    </nav>
 -->
                    @if(!empty($category))
                       @include('frontend.included_files.products_breadcrumb')
                    @endif
                </div>
            </div>
        </div>
    </div>


     <!-- Hero section end -->


    <div class="py-24">

        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div>
                    <div class="relative overflow-hidden">
                        <span class="font-semibold uppercase text-sm text-white inline-block py-1 px-2 leading-none absolute top-3  z-10 right-3 bg-orange">Sale</span>
                        <div class="gallery mb-6">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">

                                     @if(!empty($product->media))
                                        @foreach($product->media as $k => $image)
                                        @php
                                                        if(isset($image->pimage)){
                                                            $img = $image->pimage->image;
                                                        }else{
                                                            $img = $image->image;
                                                        }
                                                    @endphp 
                                        <div class="swiper-slide">
                                           <img src="{{$img->path['image_path']}}" alt="product image">
                                        </div>
                                        @endforeach
                                        @endif 
                                </div>
                            </div>

                        </div>

                        <div class="gallery-nav relative">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">


                                     
                                     @if(!empty($product->media))
                                        @foreach($product->media as $k => $image)
                                        @php
                                                        if(isset($image->pimage)){
                                                            $img = $image->pimage->image;
                                                        }else{
                                                            $img = $image->image;
                                                        }
                                                    @endphp 
                                        <div class="swiper-slide">
                                             <a href="javascript:void(0)">
                                           <img src="{{$img->path['image_path']}}" alt="product image">
                                       </a>
                                        </div>
                                        @endforeach
                                        @endif 
                                    
                                </div>
                            </div>
                            <!-- If we need pagination -->
                            <!-- <div class="swiper-pagination"></div> -->
                            <div class="swiper-buttons">
                                <div class="swiper-button-prev right-auto left-4  w-8 h-8 rounded-full  border border-solid border-gray-500 text-sm text-dark opacity-100 transition-all hover:text-orange hover:border-orange">
                                    <i class="ion-chevron-left"></i>
                                </div>
                                <div class="swiper-button-next left-auto right-4  w-8 h-8 rounded-full  border border-solid border-gray-500 text-sm text-dark opacity-100 transition-all hover:text-orange hover:border-orange">
                                    <i class="ion-chevron-right"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="@php if(!empty($product->media) && count($product->media) > 0){ echo 'col-lg-7'; } else { echo 'offset-lg-4 col-lg-4'; } @endphp rtl-text">
                    <h3 class="font-medium text-lg capitalize">   {{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : ''}}</h3>
                    <h5 class="font-bold text-md leading-none text-orange my-3"><del class="font-normal text-sm mr-1 inline-block">$110.00</del>$130.00</h5>
                    <div class="mb-3">Vendor: 
                        <span>
                            <img class="blur-up lazyload" data-src="{{$product->vendor->logo['image_path']}}" alt="{{$product->vendor->Name}}">  <a href="{{ route('vendorDetail', $product->vendor->slug) }}">
                             {{$product->vendor->name}}</a></span> 
                            </div>
                         @if($client_preference_detail)
                                        @if($client_preference_detail->rating_check == 1)
                                            @if($product->averageRating > 0)
                                             <div class="mb-3">Rating: <span>
                                                <span class="rating">{{ number_format($product->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                            </span>
                                        </div>
                                            @endif
                                        @endif
                                    @endif
                    <div class="mb-3">Type: <span> Type 3 </span></div>
                    <div class="mb-3"><span>Availability:</span> <span class="font-semibold">9 left in stock</span></div>
                   <!--  <p class="mb-8">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p> -->

                                                         <div class="description_txt mt-3">
                                        <p>{{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->meta_description : ''}}</p>
                                    </div>
                                    <div id="product_variant_wrapper">
                                        <input type="hidden" name="variant_id" id="prod_variant_id" value="{{$product->variant[0]->id}}">
                                        @if($product->inquiry_only == 0)
                                            <h3 id="productPriceValue" class="mb-md-3">
                                                <b class="mr-1">{{Session::get('currencySymbol')}}<span class="product_fixed_price">{{(number_format($product->variant[0]->price * $product->variant[0]->multiplier,2))}}</span></b>
                                                @if($product->variant[0]->compare_at_price > 0 )
                                                    <span class="org_price">{{Session::get('currencySymbol')}}<span class="product_original_price">{{(number_format($product->variant[0]->compare_at_price * $product->variant[0]->multiplier,2))}}</span></span>
                                                @endif
                                            </h3>
                                        @endif
                                    </div>
                                    <div id="product_variant_options_wrapper">
                                        @if(!empty($product->variantSet))
                                            @php
                                                $selectedVariant = isset($product->variant[0]) ? $product->variant[0]->id : 0;
                                                if($product->minimum_order_count > 0)
                                                $product->minimum_order_count = $product->minimum_order_count;
                                                else
                                                $product->minimum_order_count = 1;
                                            @endphp
                                            @foreach($product->variantSet as $key => $variant)
                                                @if($variant->type == 1 || $variant->type == 2)
                                                <div class="size-box">
                                                    <ul class="productVariants">
                                                        <li class="firstChild">{{$variant->title}}</li>
                                                        <li class="otherSize">
                                                            @foreach($variant->option2 as $k => $optn)
                                                            <?php $var_id = $variant->variant_type_id;
                                                            $opt_id = $optn->variant_option_id;
                                                            $checked = ($selectedVariant == $optn->product_variant_id) ? 'checked' : '';
                                                            ?>
                                                            <label class="radio d-inline-block txt-14 mr-2">{{$optn->title}}
                                                                <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" vid="{{$var_id}}" optid="{{$opt_id}}" value="{{$opt_id}}" type="radio" class="changeVariant dataVar{{$var_id}}" {{$checked}}>
                                                                <span class="checkround"></span>
                                                            </label>
                                                            @endforeach
                                                        </li>
                                                    </ul>
                                                </div>
                                                @else
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <div id="variant_response">
                                        <span class="text-danger mb-2 mt-2"></span>
                                    </div>
                                    <div id="product_variant_quantity_wrapper">
                                        @if($product->inquiry_only == 0)
                                        <div class="product-description border-product pb-0">
                                            <h6 class="product-title mt-0">{{__('Quantity')}}:
                                                @if($product->has_inventory && !$product->variant[0]->quantity > 0 && $product->sell_when_out_of_stock != 1)
                                                    <span id="outofstock" style="color: red;">{{ __('Out of Stock')}}</span>
                                                @else
                                                @php
                                                $product_quantity_in_cart = $product_in_cart->quantity??0;
                                                @endphp
                                                <input type="hidden" value="{{$product->has_inventory}}" id="hasInventory">
                                                <input type="hidden" id="instock" value="{{ ($product->variant[0]->quantity - $product_quantity_in_cart)}}">
                                                @endif
                                            </h6>
                                            @if(!$product->has_inventory || $product->variant[0]->quantity > 0 || $product->sell_when_out_of_stock == 1)
                                            @if($product->minimum_order_count > 1)
                                            {{-- <p class="mb-1 product_price">   {{__('Minimum Quantity') }} : {{ $product->minimum_order_count }} </p>
                                            <p class="mb-1 product_price">   {{__('Batch') }} : {{ $product->batch_count }} </p> --}}
                                            @endif
                                            <div class="qty-box mb-3">
                                                <div class="input-group">
                                                    <span class="input-group-prepend">
                                                        <button type="button" class="btn quantity-left-minus" data-type="minus" data-field="" data-batch_count={{$product->batch_count}} data-minimum_order_count={{$product->minimum_order_count}}><i class="ti-angle-left"></i>
                                                        </button>
                                                    </span>
                                                    <input type="text" name="quantity" id="quantity" class="form-control input-qty-number quantity_count" value="{{$product->minimum_order_count??1}}" data-minimum_order_count={{$product->minimum_order_count}}>
                                                    <span class="input-group-prepend quant-plus">
                                                        <button type="button" class="btn quantity-right-plus " data-type="plus" data-field="" data-batch_count={{$product->batch_count}} data-minimum_order_count={{$product->minimum_order_count}}>
                                                            <i class="ti-angle-right"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif

                                    </div>

                                    @if(!empty($product->addOn) && $product->addOn->count() > 0)
                                    <div class="border-product">
                                        <h6 class="product-title">{{__('Addon List')}}</h6>

                                        <div id="addon-table">
                                            @foreach($product->addOn as $row => $addon)
                                                <div class="addon-product mb-3">
                                                    <h4 addon_id="{{$addon->addon_id}}" class="header-title productAddonSet mb-2">{{$addon->title}}
                                                        @php
                                                            $min_select = '';
                                                            if($addon->min_select > 0){
                                                                $min_select = 'Minimum '.$addon->min_select;
                                                            }
                                                            $max_select = '';
                                                            if($addon->max_select > 0){
                                                                $max_select = 'Maximum '.$addon->max_select;
                                                            }
                                                            if( ($min_select != '') && ($max_select != '') ){
                                                                $min_select = $min_select.' and ';
                                                            }
                                                        @endphp
                                                        @if( ($min_select != '') || ($max_select != '') )
                                                            <small>({{$min_select.$max_select}} Selections allowed)</small>
                                                        @endif
                                                    </h4>

                                                    <div class="productAddonSetOptions" data-min="{{$addon->min_select}}" data-max="{{$addon->max_select}}" data-addonset-title="{{$addon->title}}">
                                                        @foreach($addon->setoptions as $k => $option)
                                                        <div class="checkbox checkbox-success form-check-inline mb-1">
                                                            <input type="checkbox" id="inlineCheckbox_{{$row.'_'.$k}}" class="productDetailAddonOption" name="addonData[$row][]" addonId="{{$addon->addon_id}}" addonOptId="{{$option->id}}" data-price="{{$option->price}}" data-fixed_price="{{number_format($product->variant[0]->price * $product->variant[0]->multiplier,2)}}" data-original_price="{{number_format($product->variant[0]->compare_at_price * $product->variant[0]->multiplier,2)}}">
                                                            <label class="pl-2 mb-0" for="inlineCheckbox_{{$row.'_'.$k}}" data-toggle="tooltip" data-placement="top" title="{{$option->title .' ('.Session::get('currencySymbol').$option->price.')' }}">
                                                                {{$option->title .' ('.Session::get('currencySymbol').$option->price.')' }}</label>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

 
                                    </div>
                                    @endif
                                    @php
                                    $checkSlot = findSlot('',$product->vendor->id,'');    
                                    @endphp
                                    <div class="product-buttons">
                                        @if(!$product->has_inventory || $product->variant[0]->quantity > 0  || $product->sell_when_out_of_stock == 1)
                                        @if($is_inwishlist_btn && $is_available)
                                        <button type="button" class="btn btn-solid addWishList" proSku="{{$product->sku}}">
                                            {{ (isset($product->inwishlist) && (!empty($product->inwishlist))) ? __('Remove From Wishlist') : __('Add To Wishlist') }}
                                        </button>
                                        @endif
                                        @if($product->inquiry_only == 0)
                                        @php
                                        if($product->sell_when_out_of_stock == 1 && $product->variant[0]->quantity == 0){
                                            $product_quantity_in_cart = 1;
                                            $product->variant[0]->quantity = 2;
                                        }
                                        else
                                        $product_quantity_in_cart = $product_in_cart->quantity??0;


                                        @endphp
                                        @if($is_available == 1)
                                            <a href="#" data-toggle="modal" data-target="#addtocart" class="btn btn-solid addToCart {{ (($checkSlot == 0  && $vendor_info->is_vendor_closed == 1) || ($product->variant[0]->quantity <= $product_quantity_in_cart && $product->has_inventory)) ? 'btn-disabled' : '' }}">{{__('Add To Cart')}}</a>
                                        @endif

                                            @if($vendor_info->is_vendor_closed == 1 && $checkSlot == 0)
                                            <p class="text-danger">{{__('Restaurant is not accepting orders right now.')}}</p>
                                            @elseif($vendor_info->is_vendor_closed == 1 && $vendor_info->closed_store_order_scheduled == 1)
                                            <p class="text-danger">{{ __('We are not accepting orders right now. You can schedule this for '). $checkSlot}}.</p>
                                            @endif
                                        @else
                                            <a href="#" data-toggle="modal" data-target="#inquiry_form" class="btn btn-solid inquiry_mode">{{ __('Inquire Now')}}</a>
                                        @endif
                                        @endif
                                    </div>
                                    <div class="border-product">
                                        <h6 class="product-title">{{__('Product Details')}}</h6>
                                        <p>{!!(!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}</p>
                                    </div>
                                    <div class="border-product">
                                        <h6 class="product-title">{{__('Share It')}}</h6>
                                        <div class="product-icon w-100">
                                            <!-- <ul class="product-social"> -->
                                                {!! $shareComponent !!}
                                                <!-- <li><a href="#"><i class="fa fa-twitter"></i></a></li> -->
                                                <!-- <li><a href="#"><i class="fa fa-facebook"></i></a></li> -->
                                                <!-- <li><a href="#"><i class="fa fa-google-plus"></i></a></li> -->
                                                <!-- <li><a href="#"><i class="fa fa-instagram"></i></a></li> -->
                                            <!-- </ul>   -->
                                        </div>
                                    </div>

 

                    </div>

                </div>
            </div>
        </div>

    </div>



  <section class="tab-product m-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab" href="#top-home" role="tab" aria-selected="true"><i class="icofont icofont-ui-home"></i>{{__('Description')}}</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <!-- <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-toggle="tab"
                                            href="#top-profile" role="tab" aria-selected="false"><i
                                                class="icofont icofont-man-in-glasses"></i>Details</a>
                                        <div class="material-border"></div>
                                    </li> -->
                                    @if($client_preference_detail)
                                    @if($client_preference_detail->rating_check == 1)
                                    <li class="nav-item"><a class="nav-link" id="review-top-tab" data-toggle="tab" href="#top-review" role="tab" aria-selected="false"><i class="icofont icofont-contacts"></i>{{ __('Ratings and reviews') }}</a>
                                        <div class="material-border"></div>
                                    </li>
                                    @endif
                                    @endif
                                </ul>
                                <div class="tab-content nav-material" id="top-tabContent">
                                    <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                                        <p>{!! (!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}</p>
                                    </div>
                                    <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                                        <p>{!! (!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}</p>
                                    </div>
                                    <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                                        @forelse ($rating_details as $rating)
                                        <div v-for="item in list" class="w-100 d-flex justify-content-between mb-3">
                                            <div class="review-box">

                                                <div class="review-author mb-1">
                                                    <p><strong>{{$rating->user->name??'NA'}}</strong> - <i class="fa fa-star{{ $rating->rating >= 1 ? '' : '-o' }}" aria-hidden="true"></i>
                                                        <i class="fa fa-star{{ $rating->rating >= 2 ? '' : '-o' }}" aria-hidden="true"></i>
                                                        <i class="fa fa-star{{ $rating->rating >= 3 ? '' : '-o' }}" aria-hidden="true"></i>
                                                        <i class="fa fa-star{{ $rating->rating >= 4 ? '' : '-o' }}" aria-hidden="true"></i>
                                                        <i class="fa fa-star{{ $rating->rating >= 5 ? '' : '-o' }}" aria-hidden="true"></i>
                                                    </p>
                                                </div>
                                                <div class="review-comment">
                                                    <p>{{$rating->review??''}}</p>
                                                </div>
                                                <div class="row review-wrapper">
                                                    @if(isset($rating->reviewFiles))
                                                    @foreach ($rating->reviewFiles as $files)
                                                    <a target="_blank" href="{{$files->file['image_path']}}" class="col review-photo mt-2 lightBoxGallery" data-gallery="">
                                                        <img class="blur-up lazyload" data-src="{{$files->file['image_path']}}">
                                                    </a>
                                                    @endforeach
                                                    @endif
                                                </div>
                                                <div class="review-date mt-2">
                                                    <time> {{ $rating->time_zone_created_at->diffForHumans();}} </time>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <p>{{__('No Result Found.')}}</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
<script type="text/template" id="variant_image_template">
    <% if(variant.media != '') { %>
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">
                <% _.each(variant.media, function(img, key){ %>
                    <div class="swiper-slide easyzoom easyzoom--overlay">
                        <a href="<%= img.pimage.image.path['image_path'] %>">
                        <img class="blur-up lazyload" data-src="<%= img.pimage.image.path['image_path'] %>" alt="">
                        </a>
                    </div>
                <% }); %>
            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-white"></div>
            <div class="swiper-button-prev swiper-button-white"></div>
        </div>
        <div class="swiper-container gallery-thumbs">
            <div class="swiper-wrapper">
                <% _.each(variant.media, function(img, key){ %>
                    <div class="swiper-slide">
                        <img class="blur-up lazyload" data-src="<%= img.pimage.image.path['image_path'] %>" alt="">
                    </div>
                <% }); %>
            </div>
        </div>
    <% }else{ %>
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">
                <% _.each(variant.product.media, function(img, key){ %>
                    <div class="swiper-slide easyzoom easyzoom--overlay">
                        <a href="<%= img.image.path['image_path'] %>">
                        <img class="blur-up lazyload" data-src="<%= img.image.path['image_path'] %>" alt="">
                        </a>
                    </div>
                <% }); %>
            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-white"></div>
            <div class="swiper-button-prev swiper-button-white"></div>
        </div>
        <div class="swiper-container gallery-thumbs">
            <div class="swiper-wrapper">
                <% _.each(variant.product.media, function(img, key){ %>
                    <div class="swiper-slide">
                        <img class="blur-up lazyload" data-src="<%= img.image.path['image_path'] %>" alt="">
                    </div>
                <% }); %>
            </div>
        </div>
    <% } %>
</script>
<script type="text/template" id="variant_template">
    <input type="hidden" name="variant_id" id="prod_variant_id" value="<%= variant.id %>">
    <% if(variant.product.inquiry_only == 0) { %>
        <h3 id="productPriceValue" class="mb-md-3">
            <b class="mr-1"><span class="product_fixed_price">{{Session::get('currencySymbol')}}<%= variant.productPrice %></span></b>
            <% if(variant.compare_at_price > 0 ) { %>
                <span class="org_price">{{Session::get('currencySymbol')}}<span class="product_original_price"><%= variant.compare_at_price %></span></span>
            <% } %>
        </h3>
    <% } %>
</script>
<script type="text/template" id="variant_options_template">
    <% _.each(availableSets, function(type, key){ %>
        <% if(type.variant_detail.type == 1 || type.variant_detail.type == 2) { %>
            <div class="size-box">
                <ul class="productVariants">
                    <li class="firstChild"><%= type.variant_detail.title %></li>
                    <li class="otherSize">
                        <% _.each(type.option_data, function(opt, key){ %>
                        <label class="radio d-inline-block txt-14 mr-2"><%= opt.title %>
                            <input id="lineRadio-<%= opt.id %>" name="var_<%= opt.variant_id %>" vid="<%= opt.variant_id %>" optid="<%= opt.id %>" value="<%= opt.id %>" type="radio" class="changeVariant dataVar<%= opt.variant_id %>">
                            <span class="checkround"></span>
                        </label>
                        <% }); %>
                    </li>
                </ul>
            </div>
        <% } %>
    <% }); %>
</script>
<script type="text/template" id="variant_quantity_template">
    <% if(variant.product.inquiry_only == 0) { %>
    <div class="product-description border-product">
        <h6 class="product-title mt-0">{{__('Quantity')}}:
            <% if(!variant.quantity > 0) { %>
                <span id="outofstock" style="color: red;">{{__('Out of Stock')}}</span>
            <% }else{ %>
                <input type="hidden" id="instock" value="<%= variant.quantity %>">
            <% } %>
        </h6>
        <% if(variant.quantity > 0) { %>
        <div class="qty-box mb-3">
            <div class="input-group">
                <span class="input-group-prepend">
                    <button type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i>
                    </button>
                </span>
                <input type="text" name="quantity" id="quantity" class="form-control input-qty-number quantity_count" value="1">
                <span class="input-group-prepend quant-plus">
                    <button type="button" class="btn quantity-right-plus " data-type="plus" data-field="">
                        <i class="ti-angle-right"></i>
                    </button>
                </span>
            </div>
        </div>
        <% } %>
    </div>
    <% } %>
</script>
@if($product->related_products->count() > 0)
<section class="">
    <div class="container">
        <div class="row m-0">
            <div class="col-12 ">
                <h3>{{__('Related products')}}</h3>
            </div>
        </div>
    </div>
</section>

<section class="section-b-space ratio_asos">
    <div class="container mt-3 mb-5">
        <div class="product-4 product-m no-arrow related-products">
            @forelse($product->related_products as $related_product)
            <div>
                <a class="common-product-box scale-effect text-center"
                        href="{{route('productDetail',[$related_product->vendor->slug,$related_product->url_slug])}}">
                    <div class="img-outer-box position-relative">
                        <img class="img-fluid blur-up lazyload" data-src="{{ $related_product->image_url }}" alt="">
                        <!-- <div class="pref-timing">
                            <span>5-10 min</span>
                        </div> -->
                        <!-- <i class="fa fa-heart-o fav-heart" aria-hidden="true"></i> -->
                    </div>
                    <div class="media-body align-self-center">
                        <div class="inner_spacing px-0">
                            <div class="product-description">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="card_title mb-1 ellips">{{ $related_product->translation_title }}</h6>
                                </div>
                                <p>{{ $related_product->vendor_name }}</p>
                                <p class="border-bottom pb-1">In {{$related_product->category_name}}</p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <b>
                                        @if($related_product->inquiry_only == 0)
                                        {{ Session::get('currencySymbol') . $related_product->variant_price }}
                                        @endif
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
@endif
<div class="modal fade product-rating" id="product_rating" tabindex="-1" aria-labelledby="product_ratingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="review-rating-form-modal"></div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="inquiry_form" tabindex="-1" aria-labelledby="inquiry_formLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="inquiry_formLabel">{{__('Inquiry')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                $user = Auth::user();
                @endphp
                <form id="inquiry-form">
                    <div class="row">
                        <input type="hidden" name="vendor_id" value="{{$product->vendor_id}}" />
                        <input type="hidden" name="product_id" value="{{$product->id}}" />
                        <div class="col-md-6 form-group">
                            <label>{{__('Name')}}</label>
                            <input class="form-control" name="name" id="name" value="{{$user ? $user->name : '' }}" type="text" placeholder="{{__('Name')}}">
                            <span class="text-danger error-text nameError"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{__('Email')}}</label>
                            <input class="form-control" name="email" id="email" value="{{$user ? $user->email : '' }}" type="text" placeholder="{{__('Email')}}">
                            <span class="text-danger error-text emailError"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{__('Phone Number')}}</label>
                            <input class="form-control" name="number1" id="number1" value="{{$user ? $user->phone_number : '' }}" type="text" placeholder="{{__('Phone Number')}}" style="display:inline-block;">
                            <span class="text-danger error-text numberError"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{__('Company Name')}}</label>
                            <input class="form-control" name="company_name" id="company_name" type="text" placeholder="{{__('Company Name')}}">
                        </div>
                        <div class="col-12 form-group">
                            <label>{{__('Message')}}</label>
                            <textarea class="form-control" name="message" id="message" cols="30" rows="8" placeholder="{{__('Message')}}"></textarea>
                            <span class="text-danger error-texprapt messageError"></span>
                        </div>
                        <div class="col-12 form-group checkbox-input">
                            <input type="checkbox" id="agree" name="agree" required>
                            <label for="agree">{{__('I accept the')}} <a href="{{url('page/terms-conditions')}}" target="_blank">{{__('Terms And Conditions')}}</a> {{__('and have read the')}} <a href="{{url('page/privacy-policy')}}" target="_blank"> {{__('Privacy Policy')}}</a></label>
                            <span class="d-block text-danger error-text agreeError"></span>
                        </div>
                        <div class="col-12 mt-2">
                            <button type="button" class="btn btn-solid w-100 submitInquiryForm">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('j_s')

<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>


<script>
    $(document).on('click', '.submitInquiryForm', function(e) {
        e.preventDefault();
        var formData = new FormData(document.getElementById("inquiry-form"));
        formData.append("variant_id", $('#prod_variant_id').val());
        var submit_url = "{{ route('inquiryMode.store') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: submit_url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#inquiry_form').modal('hide');
            },
            error: function(response) {
                console.log(response);
                $('.messageError').html(response.responseJSON.errors.message[0]);
                $('.agreeError').html(response.responseJSON.errors.agree[0]);
                $('.numberError').html(response.responseJSON.errors.number[0]);
                $('.emailError').html(response.responseJSON.errors.email[0]);
                $('.nameError').html(response.responseJSON.errors.name[0]);
            },
            complete: function() {}
        });
    });


    var valueHover = 0;

    function calcSliderPos(e, maxV) {
        return (e.offsetX / e.target.clientWidth) * parseInt(maxV, 10);
    }

    $(".starrate").on("click", function() {
        $(this).data('val', valueHover);
        $(this).addClass('saved')
    });

    $(".starrate").on("mouseout", function() {
        upStars($(this).data('val'));
    });


    $(".starrate span.ctrl").on("mousemove", function(e) {
        var maxV = parseInt($(this).parent("div").data('max'))
        valueHover = Math.ceil(calcSliderPos(e, maxV) * 2) / 2;
        upStars(valueHover);
    });


    function upStars(val) {
        var val = parseFloat(val);
        $("#test").html(val.toFixed(1));

        var full = Number.isInteger(val);
        val = parseInt(val);
        var stars = $("#starrate i");

        stars.slice(0, val).attr("class", "fa fa-star");
        if (!full) {
            stars.slice(val, val + 1).attr("class", "fa fa-star-half-o");
            val++
        }
        stars.slice(val, 5).attr("class", "fa fa-star-o");
    }


    $(document).ready(function() {
        $(".starrate span.ctrl").width($(".starrate span.cont").width());
        $(".starrate span.ctrl").height($(".starrate span.cont").height());
    });
</script>

<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
    var vendor_id = "{{ $product->vendor_id }}";
    var product_id = "{{ $product->id }}";
    var add_to_cart_url = "{{ route('addToCart') }}";
    $('.changeVariant').click(function() {
        updatePrice();
    });
    function updatePrice()
    {
        var variants = [];
        var options = [];
        $('.changeVariant').each(function() {
            var that = this;
            if (this.checked == true) {
                variants.push($(that).attr('vid'));
                options.push($(that).attr('optid'));
            }
        });
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('productVariant', $product->sku) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "variants": variants,
                "options": options,
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                console.log(response);
                if(response.status == 'Success'){
                    $("#variant_response span").html('');
                    if(response.variant != ''){
                        $('#product_variant_wrapper').html('');
                        let variant_template = _.template($('#variant_template').html());
                        response.variant.productPrice = (parseFloat(checkAddOnPrice()) + parseFloat(response.variant.productPrice)).toFixed(2);
                        response.variant.compare_at_price = (parseFloat(checkAddOnPrice()) + parseFloat(response.variant.compare_at_price)).toFixed(2);
                        $("#product_variant_wrapper").append(variant_template({variant:response.variant}));

                        $('#product_variant_quantity_wrapper').html('');
                        let variant_quantity_template = _.template($('#variant_quantity_template').html());
                        $("#product_variant_quantity_wrapper").append(variant_quantity_template({variant:response.variant}));
                        console.log(response.variant.quantity);
                        if(response.variant.quantity < 1){
                            $(".addToCart, #addon-table").hide();
                        }else{
                            $(".addToCart, #addon-table").show();
                        }

                        let variant_image_template = _.template($('#variant_image_template').html());
                        $(".product__carousel .gallery-parent").html('');
                        $(".product__carousel .gallery-parent").append(variant_image_template({variant:response.variant}));
                        easyZoomInitialize();
                        $('.easyzoom').easyZoom();

                        if(response.variant.media != ''){
                            $(".product-slick").slick({ slidesToShow: 1, slidesToScroll: 1, arrows: !0, fade: !0, asNavFor: ".slider-nav" });
                            $(".slider-nav").slick({ vertical: !1, slidesToShow: 3, slidesToScroll: 1, asNavFor: ".product-slick", arrows: !1, dots: !1, focusOnSelect: !0 });
                        }
                    }
                }else{
                    $("#variant_response span").html(response.message);
                    $(".addToCart, #addon-table").hide();
                }
            },
            error: function(data) {

            },
        });
    }
    function checkAddOnPrice()
    {
        price  = 0;
        $('.productDetailAddonOption').each(function(){
            if($(this).prop('checked') == true){
                var cp = $(this).data('price');
                price = price + parseFloat(cp);
            }
        });
        return price;
    }
</script>
<script>
    var addonids = [];
    var addonoptids = [];
    $(function() {
        $(".productDetailAddonOption").click(function(e) {
            var addon_elem = $(this).closest('tr');
            var addon_minlimit = addon_elem.data('min');
            var addon_maxlimit = addon_elem.data('max');
            if(addon_elem.find(".productDetailAddonOption:checked").length > addon_maxlimit) {
                this.checked = false;
            }else{
                var addonId = $(this).attr("addonId");
                var addonOptId = $(this).attr("addonOptId");
                if ($(this).is(":checked")) {
                    addonids.push(addonId);
                    addonoptids.push(addonOptId);
                } else {
                    addonids.splice(addonids.indexOf(addonId), 1);
                    addonoptids.splice(addonoptids.indexOf(addonOptId), 1);
                }
                if($('.changeVariant').length > 0)
                {
                    updatePrice();
                }else{
                    addOnPrice = parseFloat(checkAddOnPrice());
                    org_price = parseFloat($(this).data('original_price')) + addOnPrice;
                    fixed_price = parseFloat($(this).data('fixed_price')) + addOnPrice;
                    $('.product_fixed_price').html(fixed_price.toFixed(2));
                    $('.product_original_price').html(org_price.toFixed(2));
                }
            }
        });
    });
</script>

<!-----  rating product if delivered -->

<script type="text/javascript">
    $(document).ready(function(e) {
        $('.rating-star-click').click(function() {
            $('.rating_files').show();
            $('.form-row').show();
            $('#product_rating').modal('show');
        });
        $('body').on('click', '.add_edit_review', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            $.get('/rating/get-product-rating?id=' + id, function(markup) {
                $('#product_rating').modal('show');
                $('#review-rating-form-modal').html(markup);
            });
        });
    });
</script>


<script>

        (function ($, window) {
            let ele = null,
                exzoom_img_box = null,
                boxWidth = null,
                boxHeight = null,
                exzoom_img_ul_outer = null, //???????????? ul ??????,???????????????????????????
                exzoom_img_ul = null,
                exzoom_img_ul_position = 0, //???????????????????????????,???????????????????????????
                exzoom_img_ul_width = 0, //?????????????????????????????????
                exzoom_img_ul_max_margin = 0, //????????????????????????????????????,?????????????????????????????????boxWidth
                exzoom_nav = null,
                exzoom_nav_inner = null,
                navHightClass = "current", //??????????????????,
                exzoom_navSpan = null,
                navHeightWithBorder = null,
                images = null,
                exzoom_prev_btn = null, //?????????????????????
                exzoom_next_btn = null, //?????????????????????
                imgNum = 0, //???????????????
                imgIndex = 0, //?????????????????????
                imgArr = [], //?????????????????????
                exzoom_zoom = null,
                exzoom_main_img = null,
                exzoom_zoom_outer = null,
                exzoom_preview = null, //????????????
                exzoom_preview_img = null, //?????????????????????
                autoPlayInterval = null, //???????????????????????????????????????
                startX = 0, //???????????????????????????
                startY = 0, //???????????????????????????
                endX = 0, //???????????????????????????
                endY = 0, //???????????????????????????
                g = {}, //????????????
                defaults = {
                    "navWidth": 60, //??????????????????,???????????????????????????????????????
                    "navHeight": 60, //??????????????????,???????????????????????????????????????
                    "navItemNum": 5, //??????????????????
                    "navItemMargin": 7, //????????????
                    "navBorder": 1, //?????????????????????????????????0????????????css?????????
                    "autoPlay": true, //??????????????????
                    "autoPlayTimeout": 2000, //??????????????????
                };


            let methods = {
                init: function (options) {
                    let opts = $.extend({}, defaults, options);

                    ele = this;
                    exzoom_img_box = ele.find(".exzoom_img_box");
                    exzoom_img_ul = ele.find(".exzoom_img_ul");
                    exzoom_nav = ele.find(".exzoom_nav");
                    exzoom_prev_btn = ele.find(".exzoom_prev_btn"); //??????????????????????????????
                    exzoom_next_btn = ele.find(".exzoom_next_btn"); //??????????????????????????????

                    //todo ??????????????????????????????????????????
                    boxHeight = boxWidth = ele.outerWidth(); //???????????????,??? padding ????????????,????????????,?????????????????? ele ?????????

                    // console.log("boxWidth::" + boxWidth);
                    // console.log("ele.parent().width()::" + ele.parent().width());
                    // console.log("ele.parent().outerWidth()::" + ele.parent().outerWidth());
                    // console.log("ele.parent().innerWidth()::" + ele.parent().innerWidth());

                    //todo ??????????????????????????????????????????????????? ??????????????? ??? navItemNum ????????????,???????????????????????????????????????
                    g.navWidth = opts.navWidth;
                    g.navHeight = opts.navHeight;
                    g.navBorder = opts.navBorder;
                    g.navItemMargin = opts.navItemMargin;
                    g.navItemNum = opts.navItemNum;
                    g.autoPlay = opts.autoPlay;
                    g.autoPlayTimeout = opts.autoPlayTimeout;

                    images = exzoom_img_box.find("img");
                    imgNum = images.length; //???????????????
                    checkLoadedAllImages(images) //??????????????????????????????,???????????????????????????????????????
                },
                prev: function () { //???????????????
                    moveLeft()
                },
                next: function () { //???????????????
                    moveRight();
                },
                setImg: function () { //????????????
                    let url = arguments[0];

                    getImageSize(url, function (width, height) {
                        exzoom_preview_img.attr("src", url);
                        exzoom_main_img.attr("src", url);

                        //todo ?????????
                        //???????????????????????????????????????????????????,????????????????????????????????????
                        if (exzoom_img_ul.find("li").length === imgNum + 1) {
                            exzoom_img_ul.find("li:last").remove();
                        }
                        exzoom_img_ul.append('<li style="width: ' + boxWidth + 'px;">' +
                            '<img  class="img-fluid blur-up lazyload" data-src="' + url + '"></li>');

                        let image_prop = copute_image_prop(url, width, height);
                        previewImg(image_prop);
                    });
                },
            };

            $.fn.extend({
                "exzoom": function (method, options) {
                    if (arguments.length === 0 || (typeof method === 'object' && !options)) {
                        if (this.length === 0) {
                            // alert("?????? jQuery.exzomm ?????????????????????");
                            $.error('Selector is empty when call jQuery.exzomm');
                        } else {
                            return methods.init.apply(this, arguments);
                        }
                    } else if (methods[method]) {
                        return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
                    } else {
                        // alert("????????? jQuery.exzomm ?????????????????????");
                        $.error('Method ' + method + 'does not exist on jQuery.exzomm');
                    }
                }
            });

            /**
             * ?????????
             */
            function init() {
                exzoom_img_box.append("<div class='exzoom_img_ul_outer'></div>");
                exzoom_nav.append("<p class='exzoom_nav_inner'></p>");
                exzoom_img_ul_outer = exzoom_img_box.find(".exzoom_img_ul_outer");
                exzoom_nav_inner = exzoom_nav.find(".exzoom_nav_inner");

                //??? exzoom_img_ul ????????? exzoom_img_ul_outer ???
                exzoom_img_ul_outer.append(exzoom_img_ul);

                //??????????????????,????????????,?????????????????????
                for (let i = 0; i < imgNum; i++) {
                    imgArr[i] = copute_image_prop(images.eq(i)); //??????????????????????????????
                    console.log(imgArr[i]);
                    let li = exzoom_img_ul.find("li").eq(i);
                    li.css("width", boxWidth); //????????????????????? li ???????????????
                    li.find("img").css({
                        "margin-top": imgArr[i][5],
                        "width": imgArr[i][3]
                    });
                }

                //???????????????
                exzoom_navSpan = exzoom_nav.find("span");
                navHeightWithBorder = g.navBorder * 2 + g.navHeight;
                g.exzoom_navWidth = (navHeightWithBorder + g.navItemMargin) * g.navItemNum;
                g.exzoom_nav_innerWidth = (navHeightWithBorder + g.navItemMargin) * imgNum;

                exzoom_navSpan.eq(imgIndex).addClass(navHightClass);
                exzoom_nav.css({
                    "height": navHeightWithBorder + "px",
                    "width": boxWidth - exzoom_prev_btn.width() - exzoom_next_btn.width(),
                });
                exzoom_nav_inner.css({
                    "width": g.exzoom_nav_innerWidth + "px"
                });
                exzoom_navSpan.css({
                    "margin-left": g.navItemMargin + "px",
                    "width": g.navWidth + "px",
                    "height": g.navHeight + "px",
                });

                //???????????????????????????
                exzoom_img_ul_width = boxWidth * imgNum;
                exzoom_img_ul_max_margin = boxWidth * (imgNum - 1);
                exzoom_img_ul.css("width", exzoom_img_ul_width);
                //???????????????
                exzoom_img_box.append(`
                    <div class='exzoom_zoom_outer'>
                        <span class='exzoom_zoom'></span>
                    </div>
                    <p class='exzoom_preview'>
                        <img class='exzoom_preview_img blur-up lazyload' data-src='' />
                    </p>
                `);
                exzoom_zoom = exzoom_img_box.find(".exzoom_zoom");
                exzoom_main_img = exzoom_img_box.find(".exzoom_main_img");
                exzoom_zoom_outer = exzoom_img_box.find(".exzoom_zoom_outer");
                exzoom_preview = exzoom_img_box.find(".exzoom_preview");
                exzoom_preview_img = exzoom_img_box.find(".exzoom_preview_img");

                //??????????????????????????????
                exzoom_img_box.css({
                    "width": boxHeight + "px",
                    "height": boxHeight + "px",
                });

                exzoom_img_ul_outer.css({
                    "width": boxHeight + "px",
                    "height": boxHeight + "px",
                });

                exzoom_preview.css({
                    "width": boxHeight + "px",
                    "height": boxHeight + "px",
                    "left": boxHeight + 5 + "px", //???????????????
                });

                previewImg(imgArr[imgIndex]);
                autoPlay(); //????????????
                bindingEvent(); //????????????
            }

            /**
             * ??????????????????????????????
             * @param images
             */
            function checkLoadedAllImages(images) {
                let timer = setInterval(function () {
                    let loaded_images_counter = 0;
                    let all_images_num = images.length;
                    images.each(function () {
                        if (this.complete) {
                            loaded_images_counter++;
                        }
                    });
                    if (loaded_images_counter === all_images_num) {
                        clearInterval(timer);
                        jQuery('#exzoom').show();
                        init();
                    }
                }, 100)
            }

            /**
             * ??????????????????,????????? touch ??????,??????????????????
             */
            function getCursorCoords(event) {
                let e = event || window.event;
                let coords_data = e, //?????????????????????,????????? event ??????,???????????? touch ?????????
                    x, //x ???
                    y; //y ???

                if (e["touches"] !== undefined) {
                    if (e["touches"].length > 0) {
                        coords_data = e["touches"][0];
                    }
                }

                x = coords_data.clientX || coords_data.pageX;
                y = coords_data.clientY || coords_data.pageY;

                return {
                    'x': x,
                    'y': y
                }
            }

            /**
             * ????????????????????????????????????
             */
            function checkNewPositionLimit(new_position) {
                if (-new_position > exzoom_img_ul_max_margin) {
                    //?????????????????????
                    new_position = -exzoom_img_ul_max_margin;
                    imgIndex = 0; //????????????????????????????????????
                } else if (new_position > 0) {
                    //?????????????????????
                    new_position = 0;
                }
                return new_position
            }

            /**
             * ??????????????????
             */
            function bindingEvent() {
                //???????????????????????? touchend ??????
                exzoom_img_ul.on("touchstart", function (event) {
                    let coords = getCursorCoords(event);
                    startX = coords.x;
                    startY = coords.y;

                    let left = exzoom_img_ul.css("left");
                    exzoom_img_ul_position = parseInt(left);

                    window.clearInterval(autoPlayInterval); //??????????????????
                });

                //???????????????????????? touchmove ??????
                exzoom_img_ul.on("touchmove", function (event) {
                    let coords = getCursorCoords(event);
                    let new_position;
                    endX = coords.x;
                    endY = coords.y;

                    //?????????????????????
                    new_position = exzoom_img_ul_position + endX - startX;
                    new_position = checkNewPositionLimit(new_position);
                    exzoom_img_ul.css("left", new_position);

                });

                //???????????????????????? touchend ??????
                exzoom_img_ul.on("touchend", function (event) {
                    //????????????,???????????????????????????????????????
                    console.log(endX < startX);
                    if (endX < startX) {
                        //????????????
                        moveRight();
                    } else if (endX > startX) {
                        //????????????
                        moveLeft();
                    }

                    autoPlay(); //??????????????????
                });

                //??????????????????????????????,??????????????????????????????
                exzoom_zoom_outer.on("mousedown", function (event) {
                    let coords = getCursorCoords(event);
                    startX = coords.x;
                    startY = coords.y;

                    let left = exzoom_img_ul.css("left");
                    exzoom_img_ul_position = parseInt(left);
                });

                exzoom_zoom_outer.on("mouseup", function (event) {
                    let offset = ele.offset();

                    if (startX - offset.left < boxWidth / 2) {
                        //?????????????????????????????????
                        moveLeft();
                    } else if (startX - offset.left > boxWidth / 2) {
                        //?????????????????????????????????
                        moveRight();
                    }
                });

                //?????? exzoom ??????????????????
                ele.on("mouseenter", function () {
                    window.clearInterval(autoPlayInterval); //??????????????????
                });
                //?????? exzoom ??????????????????
                ele.on("mouseleave", function () {
                    autoPlay(); //??????????????????
                });

                //???????????????????????????
                exzoom_zoom_outer.on("mouseenter", function () {
                    exzoom_zoom.css("display", "block");
                    exzoom_preview.css("display", "block");
                });

                //??????????????????????????????
                exzoom_zoom_outer.on("mousemove", function (e) {
                    let width_limit = exzoom_zoom.width() / 2,
                        max_X = exzoom_zoom_outer.width() - width_limit,
                        max_Y = exzoom_zoom_outer.height() - width_limit,
                        current_X = e.pageX - exzoom_zoom_outer.offset().left,
                        current_Y = e.pageY - exzoom_zoom_outer.offset().top,
                        move_X = current_X - width_limit,
                        move_Y = current_Y - width_limit;

                    if (current_X <= width_limit) {
                        move_X = 0;
                    }
                    if (current_X >= max_X) {
                        move_X = max_X - width_limit;
                    }
                    if (current_Y <= width_limit) {
                        move_Y = 0;
                    }
                    if (current_Y >= max_Y) {
                        move_Y = max_Y - width_limit;
                    }
                    exzoom_zoom.css({
                        "left": move_X + "px",
                        "top": move_Y + "px"
                    });

                    exzoom_preview_img.css({
                        "left": -move_X * exzoom_preview.width() / exzoom_zoom.width() + "px",
                        "top": -move_Y * exzoom_preview.width() / exzoom_zoom.width() + "px"
                    });
                });

                //???????????????????????????
                exzoom_zoom_outer.on("mouseleave", function () {
                    exzoom_zoom.css("display", "none");
                    exzoom_preview.css("display", "none");
                });

                //???????????????????????????????????????
                exzoom_preview.on("mouseenter", function () {
                    exzoom_zoom.css("display", "none");
                    exzoom_preview.css("display", "none");
                });

                //???????????????
                exzoom_next_btn.on("click", function () {
                    moveRight();
                });
                exzoom_prev_btn.on("click", function () {
                    moveLeft();
                });

                exzoom_navSpan.hover(function () {
                    imgIndex = $(this).index();
                    move(imgIndex);
                });
            }

            /**
             * ????????????????????????,????????????????????????
             * @param direction: ??????,right | left,??????
             */
            function move(direction) {
                if (typeof direction === "undefined") {
                    alert("exzoom ?????? move ????????? direction ????????????");
                }
                //???????????????????????????,???????????????
                if (imgIndex > imgArr.length - 1) {
                    imgIndex = 0;
                }

                //????????????
                exzoom_navSpan.eq(imgIndex).addClass(navHightClass).siblings().removeClass(navHightClass);

                //??????????????????????????????????????????????????????
                let exzoom_nav_width = exzoom_nav.width();
                let nav_item_width = g.navItemMargin + g.navWidth + g.navBorder * 2; // ???????????????????????????
                let new_nav_offset = 0;

                //???????????????????????????????????????????????????exzoom????????????????????????????????????
                let temp = nav_item_width * (imgIndex + 1);
                if (temp > exzoom_nav_width) {
                    new_nav_offset = boxWidth - temp;
                }

                exzoom_nav_inner.css({
                    "left": new_nav_offset
                });

                //????????????
                let new_position = -boxWidth * imgIndex;
                //??? animate ?????????????????? stop() ,??????????????????
                new_position = checkNewPositionLimit(new_position);
                exzoom_img_ul.stop().animate({
                    "left": new_position
                }, 500);
                //??????????????????
                previewImg(imgArr[imgIndex]);
            }

            /**
             * ????????????
             */
            function moveRight() {
                imgIndex++; //????????? index,??????????????????
                if (imgIndex > imgNum) {
                    imgIndex = imgNum;
                }
                move("right");
            }

            /**
             * ????????????
             */
            function moveLeft() {
                imgIndex--; //????????? index,??????????????????
                if (imgIndex < 0) {
                    imgIndex = 0;
                }
                move("left");
            }

            /**
             * ????????????
             */
            function autoPlay() {
                if (g.autoPlay) {
                    autoPlayInterval = window.setInterval(function () {
                        if (imgIndex >= imgNum) {
                            imgIndex = 0;
                        }
                        imgIndex++;
                        move("right");
                    }, g.autoPlayTimeout);
                }
            }

            /**
             * ????????????
             */
            function previewImg(image_prop) {
                if (image_prop === undefined) {
                    return
                }
                exzoom_preview_img.attr("src", image_prop[0]);

                exzoom_main_img.attr("src", image_prop[0])
                    .css({
                        "width": image_prop[3] + "px",
                        "height": image_prop[4] + "px"
                    });
                exzoom_zoom_outer.css({
                    "width": image_prop[3] + "px",
                    "height": image_prop[4] + "px",
                    "top": image_prop[5] + "px",
                    "left": image_prop[6] + "px",
                    "position": "relative"
                });
                exzoom_zoom.css({
                    "width": image_prop[7] + "px",
                    "height": image_prop[7] + "px"
                });
                exzoom_preview_img.css({
                    "width": image_prop[8] + "px",
                    "height": image_prop[9] + "px"
                });
            }

            /**
             * ???????????????????????????
             * @param url
             * @param callback
             */
            function getImageSize(url, callback) {
                let img = new Image();
                img.src = url;

                // ???????????????????????????????????????????????????
                if (typeof callback !== "undefined") {
                    if (img.complete) {
                        callback(img.width, img.height);
                    } else {
                        // ???????????????????????????
                        img.onload = function () {
                            callback(img.width, img.height);
                        }
                    }
                } else {
                    return {
                        width: img.width,
                        height: img.height
                    }
                }
            }

            /**
             * ??????????????????
             * @param image : jquery ????????? ??????url??????
             * @param width : image ?????????url?????????????????????
             * @param height : image ?????????url?????????????????????
             * @returns {Array}
             */
            function copute_image_prop(image, width, height) {
                let src;
                let res = [];

                if (typeof image === "string") {
                    src = image;
                } else {
                    src = image.attr("src");
                    let size = getImageSize(src);
                    width = size.width;
                    height = size.height;
                }

                res[0] = src;
                res[1] = width;
                res[2] = height;
                let img_scale = res[1] / res[2];

                if (img_scale === 1) {
                    res[3] = boxHeight; //width
                    res[4] = boxHeight; //height
                    res[5] = 0; //top
                    res[6] = 0; //left
                    res[7] = boxHeight / 2;
                    res[8] = boxHeight * 2; //width
                    res[9] = boxHeight * 2; //height
                    exzoom_nav_inner.append(
                        `<span><img class="blur-up lazyload" data-src="${src}" width="${g.navWidth }" height="${g.navHeight }"/></span>`);
                } else if (img_scale > 1) {
                    res[3] = boxHeight; //width
                    res[4] = boxHeight / img_scale;
                    res[5] = (boxHeight - res[4]) / 2;
                    res[6] = 0; //left
                    res[7] = res[4] / 2;
                    res[8] = boxHeight * 2 * img_scale; //width
                    res[9] = boxHeight * 2; //height
                    let top = (g.navHeight - (g.navWidth / img_scale)) / 2;
                    exzoom_nav_inner.append(
                        `<span><img class="blur-up lazyload" data-src="${src}" width="${g.navWidth }" style='top:${top}px;' /></span>`);
                } else if (img_scale < 1) {
                    res[3] = boxHeight * img_scale; //width
                    res[4] = boxHeight; //height
                    res[5] = 0; //top
                    res[6] = (boxHeight - res[3]) / 2;
                    res[7] = res[3] / 2;
                    res[8] = boxHeight * 2; //width
                    res[9] = boxHeight * 2 / img_scale;
                    let top = (g.navWidth - (g.navHeight * img_scale)) / 2;
                    exzoom_nav_inner.append(
                        `<span><img class="blur-up lazyload" data-src="${src}" height="${g.navHeight}" style="left:${top}px;"/></span>`);
                }

                return res;
            }

            // ????????????
        })(jQuery, window);


        $(document).ready(function () {
            $('.container').imagesLoaded(function () {
                $("#exzoom").exzoom({
                    autoPlay: false,
                });
                $("#exzoom").removeClass('hidden')
            });

        });
    </script>

@endsection
@extends('layouts.store', ['title' => $vendor->name])
@section('css')
    <style type="text/css">
        .main-menu .brand-logo {
            display: inline-block;
            padding-top: 20px;
            padding-bottom: 20px;
        }

    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/price-range.css') }}">

@endsection
@section('content')
  <header>
        <div class="mobile-fix-option"></div>
        @if (isset($set_template) && $set_template->template_id == 1)
            @include('layouts.store/left-sidebar-template-one')
        @elseif(isset($set_template) && $set_template->template_id == 2)
            @include('layouts.store/left-sidebar')
        @else
            @include('layouts.store/left-sidebar-template-one')
        @endif
    </header>

    <style type="text/css">
        .productVariants .firstChild {
            min-width: 150px;
            text-align: left !important;
            border-radius: 0% !important;
            margin-right: 10px;
            cursor: default;
            border: none !important;
        }

        .product-right .color-variant li,
        .productVariants .otherChild {
            height: 35px;
            width: 35px;
            border-radius: 50%;
            margin-right: 10px;
            cursor: pointer;
            border: 1px solid #f7f7f7;
            text-align: center;
        }

        .productVariants .otherSize {
            height: auto !important;
            width: auto !important;
            border: none !important;
            border-radius: 0%;
        }

        .product-right .size-box ul li.active {
            background-color: inherit;
        }

        .product-box .product-detail h4,
        .product-box .product-info h4 {
            font-size: 16px;
        }

        select.changeVariant {
            color: #343a40;
            border: 1px solid #bbb;
            border-radius: 5px;
            font-size: 14px;
        }

        .counter-container {
            border: 1px solid var(--theme-deafult);
            border-radius: 5px;
            padding: 2px;
        }

        .switch {
            opacity: 0;
            position: absolute;
            z-index: 1;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .switch+.lable {
            position: relative;
            display: inline-block;
            margin: 0;
            line-height: 20px;
            min-height: 18px;
            min-width: 18px;
            font-weight: normal;
            cursor: pointer;
        }

        .switch+.lable::before {
            cursor: pointer;
            font-family: fontAwesome;
            font-weight: normal;
            font-size: 12px;
            color: #32a3ce;
            content: "\a0";
            background-color: #FAFAFA;
            border: 1px solid #c8c8c8;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            border-radius: 0;
            display: inline-block;
            text-align: center;
            height: 16px;
            line-height: 14px;
            min-width: 16px;
            margin-right: 1px;
            position: relative;
            top: -1px;
        }

        .switch:checked+.lable::before {
            display: inline-block;
            content: '\f00c';
            background-color: #F5F8FC;
            border-color: #adb8c0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05), inset 15px 10px -12px rgba(255, 255, 255, 0.1);
        }

        /* CSS3 on/off switches */
        .switch+.lable {
            margin: 0 4px;
            min-height: 24px;
        }

        .switch+.lable::before {
            font-weight: normal;
            font-size: 11px;
            line-height: 17px;
            height: 20px;
            overflow: hidden;
            border-radius: 12px;
            background-color: #F5F5F5;
            -webkit-box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.15);
            box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.15);
            border: 1px solid #CCC;
            text-align: left;
            float: left;
            padding: 0;
            width: 52px;
            text-indent: -21px;
            margin-right: 0;
            -webkit-transition: text-indent .3s ease;
            -o-transition: text-indent .3s ease;
            transition: text-indent .3s ease;
            top: auto;
        }

        .switch.switch-bootstrap+.lable::before {
            font-family: FontAwesome;
            content: "\f00d";
            box-shadow: none;
            border-width: 0;
            font-size: 16px;
            background-color: #a9a9a9;
            color: #F2F2F2;
            width: 52px;
            height: 22px;
            line-height: 21px;
            text-indent: 32px;
            -webkit-transition: background 0.1s ease;
            -o-transition: background 0.1s ease;
            transition: background 0.1s ease;
        }

        .switch.switch-bootstrap+.lable::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 3px;
            border-radius: 12px;
            box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
            width: 18px;
            height: 18px;
            text-align: center;
            background-color: #F2F2F2;
            border: 4px solid #F2F2F2;
            -webkit-transition: left 0.2s ease;
            -o-transition: left 0.2s ease;
            transition: left 0.2s ease;
        }

        .switch.switch-bootstrap:checked+.lable::before {
            content: "\f00c";
            text-indent: 6px;
            color: #FFF;
            border-color: #b7d3e5;

        }

        .switch-primary>.switch.switch-bootstrap:checked+.lable::before {
            background-color: #337ab7;
        }

        .switch-success>.switch.switch-bootstrap:checked+.lable::before {
            background-color: #5cb85c;
        }

        .switch-danger>.switch.switch-bootstrap:checked+.lable::before {
            background-color: #d9534f;
        }

        .switch-info>.switch.switch-bootstrap:checked+.lable::before {
            background-color: #5bc0de;
        }

        .switch-warning>.switch.switch-bootstrap:checked+.lable::before {
            background-color: #f0ad4e;
        }

        .switch.switch-bootstrap:checked+.lable::after {
            left: 32px;
            background-color: #FFF;
            border: 4px solid #FFF;
            text-shadow: 0 -1px 0 rgba(0, 200, 0, 0.25);
        }

        /* square */
        .switch-square {
            opacity: 0;
            position: absolute;
            z-index: 1;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .switch-square+.lable {
            position: relative;
            display: inline-block;
            margin: 0;
            line-height: 20px;
            min-height: 18px;
            min-width: 18px;
            font-weight: normal;
            cursor: pointer;
        }

        .switch-square+.lable::before {
            cursor: pointer;
            font-family: fontAwesome;
            font-weight: normal;
            font-size: 12px;
            color: #32a3ce;
            content: "\a0";
            background-color: #FAFAFA;
            border: 1px solid #c8c8c8;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            border-radius: 0;
            display: inline-block;
            text-align: center;
            height: 16px;
            line-height: 14px;
            min-width: 16px;
            margin-right: 1px;
            position: relative;
            top: -1px;
        }

        .switch-square:checked+.lable::before {
            display: inline-block;
            /* content: '\f00c'; */
            background-color: #F5F8FC;
            border-color: #adb8c0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05), inset 15px 10px -12px rgba(255, 255, 255, 0.1);
        }

        /* CSS3 on/off switches */
        .switch-square+.lable {
            margin: 0 4px;
            min-height: 24px;
        }

        .switch.switch-bootstrap:checked+.lable::before,
        .switch.switch-bootstrap+.lable::before {
            content: "";
            width: 40px;
            height: 18px;
            line-height: 21px;
        }

        .switch.switch-bootstrap+.lable::after {
            width: 14px;
            height: 14px;
        }

        .switch+.lable {
            line-height: 14px;
        }

        .switch.switch-bootstrap:checked+.lable::after {
            left: 23px;
        }

        .switch-square+.lable::before {
            font-weight: normal;
            font-size: 11px;
            line-height: 17px;
            height: 20px;
            overflow: hidden;
            border-radius: 2px;
            background-color: #F5F5F5;
            -webkit-box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.15);
            box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.15);
            border: 1px solid #CCC;
            text-align: left;
            float: left;
            padding: 0;
            width: 52px;
            text-indent: -21px;
            margin-right: 0;
            -webkit-transition: text-indent .3s ease;
            -o-transition: text-indent .3s ease;
            transition: text-indent .3s ease;
            top: auto;
        }

        .switch-square.switch-bootstrap+.lable::before {
            font-family: FontAwesome;
            /* content: "\f00d"; */
            box-shadow: none;
            border-width: 0;
            font-size: 16px;
            background-color: #a9a9a9;
            color: #F2F2F2;
            width: 52px;
            height: 22px;
            line-height: 21px;
            text-indent: 32px;
            -webkit-transition: background 0.1s ease;
            -o-transition: background 0.1s ease;
            transition: background 0.1s ease;
        }

        .switch-square.switch-bootstrap+.lable::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 3px;
            border-radius: 12px;
            box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
            width: 18px;
            height: 18px;
            text-align: center;
            background-color: #F2F2F2;
            border: 4px solid #F2F2F2;
            -webkit-transition: left 0.2s ease;
            -o-transition: left 0.2s ease;
            transition: left 0.2s ease;
        }

        .switch-square.switch-bootstrap:checked+.lable::before {
            /* content: "\f00c";*/
            text-indent: 6px;
            color: #FFF;
            border-color: #b7d3e5;

        }

        .switch-primary>.switch-square.switch-bootstrap:checked+.lable::before {
            background-color: #337ab7;
        }

        .switch-success>.switch-square.switch-bootstrap:checked+.lable::before {
            background-color: #5cb85c;
        }

        .switch-danger>.switch-square.switch-bootstrap:checked+.lable::before {
            background-color: #d9534f;
        }

        .switch-info>.switch-square.switch-bootstrap:checked+.lable::before {
            background-color: #5bc0de;
        }

        .switch-warning>.switch-square.switch-bootstrap:checked+.lable::before {
            background-color: #f0ad4e;
        }

        .switch-square.switch-bootstrap:checked+.lable::after {
            left: 32px;
            background-color: #FFF;
            border: 4px solid #FFF;
            text-shadow: 0 -1px 0 rgba(0, 200, 0, 0.25);
        }

        .switch-square.switch-bootstrap+.lable::after {
            border-radius: 2px;
        }

    </style>

    <!-- section start -->
    <section class="section-b-space ratio_asos">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="product-banner-img">
                            @if (!empty($vendor->banner))
                                <img alt=""
                                    src="{{ $vendor->banner['image_path'] }}">
                            @endif
                        </div>
                        <div class="product-bottom-bar">
                            <div class="row">
                                <div class="col-md-8 col-lg-5 order-0">
                                    <div class="card-box vendor-details-left px-2 py-3">
                                        <div class="d-sm-flex">
                                            <div class="vender-icon mr-sm-1 text-center text-sm-left mb-2 mb-sm-0">
                                                <img src="{{ $vendor->logo['image_path'] }}"
                                                    class="rounded-circle avatar-lg" alt="profile-image">
                                            </div>
                                            <div class="ml-sm-1">
                                                <h3>{{ $vendor->name }}</h3>
                                                @if (!empty($vendor->desc))
                                                    <h4 title="{{ $vendor->desc }}" style="line-height: 24px">
                                                        {{ substr($vendor->desc, 0, 80) . '...' }}</h4>
                                                @endif
                                                <ul class="vendor-info">
                                                   
                                                    @if ($vendor->is_show_vendor_details == 1)
                                                        <li class="d-block vendor-location">
                                                            <i class="icon-location"></i> {{ $vendor->address }}
                                                        </li>
                                                        @if ($vendor->email)
                                                            <li class="d-block vendor-email">
                                                                <i class="fa fa-envelope"></i> {{ $vendor->email }}
                                                            </li>
                                                        @endif
                                                        @if ($vendor->website)
                                                            <li class="d-block vendor-website">
                                                                <i class="fa fa-home"></i> {{ $vendor->website }}
                                                            </li>
                                                        @endif

                                                    @endif

                        <?php //dd(findSlot('',$vendor->id,'')); ?>
                                                    @php
                                                        $checkSlot = findSlot('',$vendor->id,'');
                                                        @endphp

                                                    <li class="d-block vendor-timing">
                                                        <i class="icon-time"></i>
                                                        @if ($vendor->is_vendor_closed == 0 && $vendor->show_slot == 0)
                                                            {{ $vendor->opening_time }} – {{ $vendor->closing_time }}
                                                            <span class="badge badge-success">{{__('Open')}}</span>
                                                        @elseif($vendor->is_vendor_closed == 0 && $vendor->show_slot == 1)
                                                            24 x 7 <span class="badge badge-success">{{__('Open')}}</span>
                                                        @elseif($vendor->closed_store_order_scheduled == 1 && $checkSlot != 0)
                                                        <span class="badge badge-danger">{{__('Closed')}}</span>
                                                        {{__('We are not accepting orders right now. You can schedule this for '). $checkSlot}}.
                                                            
                                                        @else
                                                            <span class="badge badge-danger">{{__('Closed')}}</span> 
                                                        @endif
                                                        </span>
                                                        {{-- <span data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                            <span class="tooltip-text d-none">Mon-Sun : 11am - 11pm</span> --}}
                                                        </span>

                                                    </li>
                                                    @if ($vendor->order_min_amount > 0)
                                                        <span class="badge badge-danger">{{ __('Minimum order value') }}
                                                            {{ Session::get('currencySymbol') . number_format($vendor->order_min_amount, 2, '.', '') }}</span>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-5 order-lg-1 order-2">
                                    <div class="vendor-search-bar">
                                        <div class="radius-bar w-100">
                                            <div class="search_form d-flex align-items-center justify-content-between">
                                                <button class="btn"><i class="fa fa-search"
                                                        aria-hidden="true"></i></button>
                                                <input class="form-control border-0 typeahead" type="search"
                                                    placeholder="{{ __('Search') }}" id="vendor_search_box">
                                            </div>
                                            <div class="list-box style-4" style="display:none;" id="search_box_main_div">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3 col-xl-2 order-xl-2 order-1">
                                    <div class="vendor-reviwes">
                                        <div class="row">
                                            <div class="col-12 d-flex align-items-center">
                                                @if ($vendor->vendorRating > 0)
                                                    <div class="rating-text-box ml-sm-auto">
                                                        <span>{{ $vendor->vendorRating }}</span>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    </div>
                                                @endif
                                                {{-- <div class="review-text">
                                    <div class="reviw-number">409</div>
                                    <div class="reviews-text">Delivery Reviews</div>
                                 </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="position-relative">
                    <div class="categories-product-list">

                        <a id="side_menu_toggle" class="d-md-none d-flex" href="javascript:void(0)">
                            <div class="manu-bars">
                                <span class="bar-line"></span>
                                <span class="bar-line"></span>
                                <span class="bar-line"></span>
                            </div>
                            <span>{{ _('Menu') }}</span>
                        </a>

                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-8"></div>
                            <div class="col-12">
                                <hr>
                                <div class="row vendor-products-wrapper">
                                    <div class="col-sm-4 col-lg-3 border-right">
                                        <nav class="scrollspy-menu">
                                            <ul>
                                                @forelse($listData as $key => $data)
                                                    <li><a href="#{{ $data->category->slug }}">{{ $data->category->translation_one->name }}
                                                            ({{ $data->products_count }})</a></li>
                                                @empty
                                                @endforelse
                                            </ul>
                                        </nav>
                                    </div>
                                    <div class="col-md-8 col-lg-6">
                                        <div class="row ">
                                            @if (isset($tags) && !empty($tags))
                                                @foreach ($tags as $key => $tag)
                                                    <label class="label-switch switch-primary product_tag_filter mr-2">
                                                        <input type="checkbox"
                                                            class="switch switch-bootstrap product_tag_filter status"
                                                            name="tag_id" id="product_tag_filter_{{ $key }}"
                                                            data-tag_id="{{ $tag->id }}"" value="
                                                            {{ $tag->id }}">
                                                        <span class="lable"> @if (isset($tag->icon) && !empty($tag->icon)) <img class="ml-1" src="{{$tag->icon['image_path'] }}" alt="">@endif <span
                                                                class="ml-1">{{ $tag->primary ? $tag->primary->name : '' }}</span></span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                        @forelse($listData as $key => $data)
                                            <section class="scrolling_section" id="{{ $data->category->slug }}">
                                                @if (!empty($data->products))
                                                    <h2 class="category-head mt-0 mb-3">
                                                        {{ $data->category->translation_one->name }}
                                                        ({{ $data->products_count }})</h2>
                                                    @forelse($data->products as $prod)
                                                        <div class="row cart-box-outer product_row classes_wrapper no-gutters mb-3"
                                                            data-p_sku="{{ $prod->sku }}"
                                                            data-slug="{{ $prod->url_slug }}">
                                                            <div class="col-2">
                                                                <a target="_blank"
                                                                    href="{{ route('productDetail', [$prod->vendor->slug, $prod->url_slug]) }}">
                                                                    <div class="class_img product_image">
                                                                        <img src="{{ $prod->product_image }}"
                                                                            alt="{{ $prod->translation_title }}">
                                                                    </div>
                                                                </a>

                                                            </div>
                                                            <div class="col-10">
                                                                <div class="row price_head pl-2">
                                                                    <div class="col-sm-12 pl-2">
                                                                        <div
                                                                            class="d-flex align-items-start justify-content-between">
                                                                            <h5 class="mt-0">
                                                                                {{ $prod->translation_title }}

                                                                            </h5>
                                                                            <div class="product_variant_quantity_wrapper">
                                                                                @php
                                                                                    $data = $prod;
                                                                                    $productVariantInCart = 0;
                                                                                    $productVariantIdInCart = 0;
                                                                                    $productVariantInCartWithDifferentAddons = 0;
                                                                                    $cartProductId = 0;
                                                                                    $cart_id = 0;
                                                                                    $vendor_id = 0;
                                                                                    $product_id = $data->id;
                                                                                    $variant_id = $data->variant[0] ? $data->variant[0]->id : 0;
                                                                                    $variant_price = 0;
                                                                                    $variant_quantity = $prod->variant_quantity;
                                                                                    $isAddonExist = 0;
                                                                                    $minimum_order_count = $data->minimum_order_count == 0 ? 1 : $data->minimum_order_count;
                                                                                    $batch_count = $data->batch_count;
                                                                                    if (count($data->addOn) > 0) {
                                                                                        $isAddonExist = 1;
                                                                                    }
                                                                                @endphp

                                                                                @foreach ($data->variant as $var)
                                                                                    @if (isset($var->checkIfInCart) && count($var->checkIfInCart) > 0)
                                                                                        @php
                                                                                            $productVariantInCart = 1;
                                                                                            $productVariantIdInCart = $var->checkIfInCart['0']['variant_id'];
                                                                                            $cartProductId = $var->checkIfInCart['0']['id'];
                                                                                            $cart_id = $var->checkIfInCart['0']['cart_id'];
                                                                                            // $variant_quantity = $var->checkIfInCart['0']['quantity'];
                                                                                            $variant_quantity = 0;
                                                                                            $vendor_id = $data->vendor_id;
                                                                                            $product_id = $data->id;
                                                                                            $batch_count = $data->batch_count;
                                                                                            $variant_price = $var->price * $data->variant_multiplier;
                                                                                            if (count($var->checkIfInCart) > 1) {
                                                                                                $productVariantInCartWithDifferentAddons = 1;
                                                                                            }
                                                                                            foreach ($var->checkIfInCart as $cartVar) {
                                                                                                $variant_quantity = $variant_quantity + $cartVar['quantity'];
                                                                                            }
                                                                                        @endphp
                                                                                    @break;
                                                                                @endif
                                                    @endforeach

                                                    @if ($vendor->is_vendor_closed == 0 || ($vendor->closed_store_order_scheduled != 0 && $checkSlot !=0) )
                                                        @php
                                                            $is_customizable = false;
                                                            if ($isAddonExist > 0 && ($variant_quantity > 0 || $prod->sell_when_out_of_stock == 1)) {
                                                                $is_customizable = true;
                                                            }
                                                        @endphp

                                                        @if ($productVariantInCart > 0)
                                                            {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                                                            <a class="add-cart-btn add_vendor_product" style="display:none;"
                                                                id="add_button_href{{ $cartProductId }}"
                                                                data-variant_id="{{ $productVariantIdInCart }}"
                                                                data-add_to_cart_url="{{ route('addToCart') }}"
                                                                data-vendor_id="{{ $vendor_id }}"
                                                                data-product_id="{{ $product_id }}"
                                                                data-addon="{{ $isAddonExist }}"
                                                                data-minimum_order_count="{{ $minimum_order_count }}"
                                                                data-batch_count="{{ $batch_count }}"
                                                                href="javascript:void(0)">{{ __('Add') }}
                                                                @if ($minimum_order_count > 0) ({{ $minimum_order_count }}) @endif</a>
                                                            <div class="number"
                                                                id="show_plus_minus{{ $cartProductId }}">
                                                                <span
                                                                    class="minus qty-minus-product {{ $productVariantInCartWithDifferentAddons ? 'remove-customize' : '' }}"
                                                                    data-variant_id="{{ $productVariantIdInCart }}"
                                                                    data-parent_div_id="show_plus_minus{{ $cartProductId }}"
                                                                    data-id="{{ $cartProductId }}"
                                                                    data-base_price="{{ $variant_price }}"
                                                                    data-vendor_id="{{ $vendor_id }}"
                                                                    data-product_id="{{ $product_id }}"
                                                                    data-cart="{{ $cart_id }}"
                                                                    data-addon="{{ $isAddonExist }}"
                                                                    data-minimum_order_count="{{ $minimum_order_count }}"
                                                                    data-batch_count="{{ $batch_count }}">
                                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                                </span>
                                                                <input
                                                                    style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;"
                                                                    placeholder="1" type="text"
                                                                    value="{{ $variant_quantity }}"
                                                                    class="input-number" step="0.01"
                                                                    id="quantity_ondemand_{{ $cartProductId }}" readonly>
                                                                <span
                                                                    class="plus qty-plus-product {{ $is_customizable ? 'repeat-customize' : '' }}"
                                                                    data-variant_id="{{ $productVariantIdInCart }}"
                                                                    data-id="{{ $cartProductId }}"
                                                                    data-base_price="{{ $variant_price }}"
                                                                    data-vendor_id="{{ $vendor_id }}"
                                                                    data-product_id="{{ $product_id }}"
                                                                    data-cart="{{ $cart_id }}"
                                                                    data-addon="{{ $isAddonExist }}"
                                                                    data-batch_count="{{ $batch_count }}">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                </span>
                                                            </div>
                                                        @else

                                                            @if ($prod->has_inventory == 0 || ($variant_quantity > 0 || $prod->sell_when_out_of_stock == 1))
                                                                {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                                                                <a class="add-cart-btn add_vendor_product"
                                                                    id="aadd_button_href{{ $data->id }}"
                                                                    data-variant_id="{{ $data->variant[0]->id }}"
                                                                    data-add_to_cart_url="{{ route('addToCart') }}"
                                                                    data-vendor_id="{{ $data->vendor_id }}"
                                                                    data-product_id="{{ $data->id }}"
                                                                    data-addon="{{ $isAddonExist }}"
                                                                    data-batch_count="{{ $batch_count }}"
                                                                    data-minimum_order_count="{{ $minimum_order_count }}"
                                                                    href="javascript:void(0)">{{ __('Add') }}@if ($minimum_order_count > 1) ({{ $minimum_order_count }}) @endif</a>
                                                                <div class="number" style="display:none;"
                                                                    id="ashow_plus_minus{{ $data->id }}">
                                                                    <span class="minus qty-minus-product"
                                                                        data-parent_div_id="show_plus_minus{{ $data->id }}"
                                                                        data-id="{{ $data->id }}"
                                                                        data-base_price="{{ $data->variant_price * $data->variant_multiplier }}"
                                                                        data-vendor_id="{{ $data->vendor_id }}"
                                                                        data-batch_count="{{ $batch_count }}"
                                                                        data-minimum_order_count="{{ $minimum_order_count }}">
                                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                                    </span>
                                                                    <input
                                                                        style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;"
                                                                        id="quantity_ondemand_d{{ $data->id }}"
                                                                        readonly placeholder="{{ $minimum_order_count }}"
                                                                        type="text" value="{{ $minimum_order_count }}"
                                                                        class="input-number input_qty" step="0.01">
                                                                    <span class="plus qty-plus-product" data-id=""
                                                                        data-base_price="{{ $data->variant_price * $data->variant_multiplier }}"
                                                                        data-vendor_id="{{ $data->vendor_id }}"
                                                                        data-batch_count="{{ $batch_count }}"
                                                                        data-minimum_order_count="{{ $minimum_order_count }}">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <span
                                                                    class="text-danger">{{ __('Out of stock') }}</span>
                                                            @endif
                                                        @endif
                                                        @if ($is_customizable)
                                                            <div class="customizable-text">{{ __('customizable') }}</div>
                                                        @endif
                                                    @endif
                                    </div>
                                </div>
                                @if ($prod->averageRating > 0)
                                    <div class="rating-text-box">
                                        <span>{{ number_format($prod->averageRating, 1, '.', '') }} </span>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                @endif

                                @if ($prod->minimum_order_count > 0)
                                    {{-- <p class="mb-1 product_price">   {{__('Minimum Quantity') }} : {{ $prod->minimum_order_count }} </p>
                                <p class="mb-1 product_price">   {{__('Batch') }} : {{ $prod->batch_count }} </p> --}}
                                @endif

                                <p class="mb-1 product_price">
                                    {{ Session::get('currencySymbol') . number_format($prod->variant_price * $prod->variant_multiplier, 2, '.', '') }}
                                    @if ($prod->variant[0]->compare_at_price > 0)
                                        <span
                                            class="org_price ml-1 font-14">{{ Session::get('currencySymbol') . number_format($prod->variant[0]->compare_at_price * $prod->variant_multiplier, 2, '.', '') }}</span>
                                    @endif
                                </p>
                                <div class="member_no d-block mb-0">
                                    <span>{!! $prod->translation_description !!}</span>
                                </div>
                                <div id="product_variant_options_wrapper">
                                    @if (!empty($prod->variantSet))
                                        @php
                                            $selectedVariant = $productVariantIdInCart > 0 ? $productVariantIdInCart : $prod->variant_id;
                                        @endphp
                                        @foreach ($prod->variantSet as $key => $variant)
                                            @if ($variant->type == 1 || $variant->type == 2)
                                                <?php $var_id = $variant->variant_type_id; ?>
                                                <select name="{{ 'var_' . $var_id }}" vid="{{ $var_id }}"
                                                    class="changeVariant dataVar{{ $var_id }}">
                                                    <option value="" disabled>{{ $variant->title }}</option>
                                                    @foreach ($variant->option2 as $k => $optn)
                                                        <?php
                                                        $opt_id = $optn->variant_option_id;
                                                        $selected = $selectedVariant == $optn->product_variant_id ? 'selected' : '';
                                                        ?>
                                                        <option value="{{ $opt_id }}" {{ $selected }}>
                                                            {{ $optn->title }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="variant_response">
                                    <span class="text-danger mb-2 mt-2 font-14"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    @endforelse
                @else
                    <h4 class="mt-3 mb-3 text-center">No product found</h4>
                    @endif
        </section>
    @empty
        <h4 class="mt-3 mb-3 text-center">No product found</h4>
        @endforelse
        </div>
        <div class="col-12 col-lg-3 d-lg-inline-block d-none">
            <div class="card-box p-0 cart-main-box">
                <div class="p-2 d-flex align-items-center justify-content-between border-bottom">
                    <h4 class="right-card-title">{{ __('Cart') }}</h4>
                </div>
                <div class="cart-main-box-inside d-flex align-items-center">
                    <div class="spinner-box">
                        <div class="circle-border">
                            <div class="circle-core"></div>
                        </div>
                    </div>
                    <!-- <div class="p-2 border-top">
                                                                <h5>Cottonworth Classic Cuvée 75cl</h5>
                                                                <div class="qty-box mt-3 mb-2">
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
                                                            </div>
                                                            <div class="cart-sub-total d-flex align-items-center justify-content-between">
                                                                <span>Subtotalll</span>
                                                                <span>£ 10.50</span>
                                                            </div> -->

                    <div class="show-div shopping-cart flex-fill w-100" id="header_cart_main_ul_ondemand"></div>
                </div>
            </div>
        </div>
        </div>
        <hr>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </section>
        <script type="text/template" id="header_cart_template_ondemand">
            <ul class="pl-2 pr-2 pb-2 pt-0 ">
                            <% _.each(cart_details.products, function(product, key){%>
                                <li class="p-0">
                                    <h6 class="d-flex justify-content-center badge badge-light font-14"><b><%= product.vendor.name %></b></h6>
                                </li>

                                <% if( (product.isDeliverable != undefined) && (product.isDeliverable == 0) ) { %>
                                    <li class="border_0">
                                        <th colspan="7">
                                            <div class="text-danger">
                                                {{ __('Products for this vendor are not deliverable at your area. Please change address or remove product.') }}
                                            </div>
                                        </th>
                                    </li>
                                    <% } %>
                                <% _.each(product.vendor_products, function(vendor_product, vp){%>
                                    <li class="p-0" id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                                        <div class='media-body'>
                                            <h6 class="d-flex align-items-center justify-content-between m-0">
                                                <span class="ellips"><%= vendor_product.quantity %>x <%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></span>
                                                <span>

                                                    {{ Session::get('currencySymbol') }}<%=  Helper.formatPrice(vendor_product.quantity_price) %>
                                                    <a class="action-icon remove_product_via_cart text-danger" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </a>
                                                </span>
                                            </h6>
                                        </div>
                                    </li>

                                    <% if(vendor_product.addon.length != 0) { %>
                                        <div class="row align-items-md-center">
                                            <div class="col-12">
                                                <h6 class="m-0 font-12"><b>{{ __('Add Ons') }}</b></h6>
                                            </div>
                                        </div>
                                        <% _.each(vendor_product.addon, function(addon, ad){%>
                                        <div class="row mb-1">
                                            <div class="col-md-6 col-sm-4 items-details text-left">
                                                <p class="m-0 font-14 p-0"><%= vendor_product.quantity %>x <%= addon.option.title %></p>
                                            </div>
                                            <div class="col-md-3 col-sm-4 text-center">
                                                <div class="extra-items-price font-14">{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(addon.option.price_in_cart) %></div>
                                            </div>
                                            <div class="col-md-3 col-sm-4 text-right">
                                                <div class="extra-items-price font-14 mr-3">{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(addon.option.quantity_price) %></div>
                                            </div>
                                        </div>
                                        <% }); %>
                                    <% } %>

                                <% }); %>
                                <% if(product.delivery_fee_charges > 0) { %>
                                    <div class="row justify-content-between">
                                        <div class="col-md-6 col-sm-6 text-left">
                                            <h6 class="m-0 font-14"><b>{{ __('Delivery fee') }}</b></h6>
                                        </div>
                                        <div class="col-md-6 col-sm-6 text-right">
                                            <div class="extra-items-price font-14 mr-3">{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(product.delivery_fee_charges) %></div>
                                        </div>
                                    </div>
                                <% } %>
                                <hr class="my-2">
                            <% }); %>

                            <h5 class="d-flex align-items-center justify-content-between pb-2">{{ __('PRICE DETAILS') }} </h5>
                            <li class="p-0">
                                <div class='media-body'>
                                    <h6 class="d-flex align-items-center justify-content-between">
                                        <span class="ellips">{{ __('Price') }}</span>
                                        <span >{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(cart_details.gross_amount) %></span>
                                    </h6>
                                </div>
                            </li>

                            <li class="p-0">
                                <div class='media-body'>
                                    <h6 class="d-flex align-items-center justify-content-between">
                                        <span class="ellips">{{ __('Tax') }}</span>
                                        <span>{{ Session::get('currencySymbol') }}<%= cart_details.total_taxable_amount %></span>
                                    </h6>
                                </div>
                            </li>

                            <% if(cart_details.total_subscription_discount != undefined) { %>
                             <li class="p-0">
                                <div class='media-body'>
                                    <h6 class="d-flex align-items-center justify-content-between">
                                        <span class="ellips">{{ __('Subscription Discount') }}</span>
                                        <span>{{ Session::get('currencySymbol') }}<%= cart_details.total_subscription_discount %></span>
                                    </h6>
                                </div>
                            </li>
                            <% } %>

                            <% if(cart_details.loyalty_amount > 0) { %>
                            <li class="p-0">
                                <div class='media-body'>
                                    <h6 class="d-flex align-items-center justify-content-between">
                                        <span class="ellips">{{ __('Loyalty Amount') }} </span>
                                        <span>{{ Session::get('currencySymbol') }}<%= cart_details.loyalty_amount %></span>
                                    </h6>
                                </div>
                            </li>
                            <% } %>

                            <% if(cart_details.wallet_amount_used > 0) { %>
                            <li class="p-0">
                                <div class='media-body'>
                                    <h6 class="d-flex align-items-center justify-content-between">
                                        <span class="ellips">{{ __('Wallet Amount') }} </span>
                                        <span>{{ Session::get('currencySymbol') }}<%= cart_details.wallet_amount_used %></span>
                                    </h6>
                                </div>
                            </li>
                            <% } %>
                            </ul>
                            <div class="cart-sub-total d-flex align-items-center justify-content-between">
                                <span>{{ __('Total') }}</span>
                                <span>{{ Session::get('currencySymbol') }}<%= cart_details.total_payable_amount %></span>
                            </div>
                            <a class="checkout-btn text-center d-block" href="{{ route('showCart') }}">{{ __('Checkout') }}</a>
                        </script>
        <script type="text/template" id="empty_cart_template">
            <div class="row">
                                <div class="col-12 text-center pb-3">
                                    <img class="w-50 pt-3 pb-1" src="{{ asset('front-assets/images/ic_emptycart.svg') }}" alt="">
                                    <h5>{{__('Your Cart Is Empty!')}}<br/>{{ __('Add an item to begin') }}</h5>
                                </div>
                            </div>
                        </script>
        <script type="text/template" id="variant_image_template">
            <img src="<%= media.image_path %>" alt="">
                        </script>
        <script type="text/template" id="variant_template">
            <% if(variant.product.inquiry_only == 0) { %>
                                <%= variant.productPrice %>
                                <% if(variant.compare_at_price > 0 ) { %>
                                    <span class="org_price ml-1 font-14">{{ Session::get('currencySymbol') }}<%= variant.compare_at_price %></span>
                                <% } %>
                            <% } %>
                        </script>
        <script type="text/template" id="variant_quantity_template">
            <% if(variant.quantity > 0){ %>
                                <%
                                var is_customizable = false;
                                if(variant.isAddonExist > 0){
                                    is_customizable = true;
                                }
                                %>
                                <% if(variant.check_if_in_cart != '') { %>
                                    <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a>
                                    <a class="add-cart-btn add_vendor_product" style="display:none;" id="add_button_href<%= variant.check_if_in_cart.id %>" data-variant_id="<%= variant.id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" href="javascript:void(0)">{{ __('Add') }}</a>
                                    <div class="number" id="show_plus_minus<%= variant.check_if_in_cart.id %>">
                                        <span class="minus qty-minus-product <% if(is_customizable){ %> remove-customize <% } %>"  data-parent_div_id="show_plus_minus<%= variant.check_if_in_cart.id %>" data-id="<%= variant.check_if_in_cart.id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" data-cart="<%= variant.check_if_in_cart.cart_id %>">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </span>
                                        <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="<%= variant.check_if_in_cart.quantity %>" class="input-number" step="0.01" id="quantity_ondemand_<%= variant.check_if_in_cart.id %>" readonly>
                                        <span class="plus qty-plus-product <% if(is_customizable){ %> repeat-customize <% } %>"  data-id="<%= variant.check_if_in_cart.id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" data-cart="<%= variant.check_if_in_cart.cart_id %>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                <% }else{ %>
                                    <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a>
                                    <a class="add-cart-btn add_vendor_product" id="aadd_button_href<%= variant.product_id %>" data-variant_id="<%= variant.id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= variant.product.vendor_id %>" data-product_id="<%= variant.product_id %>" data-addon="<%= variant.isAddonExist %>" href="javascript:void(0)">{{ __('Add') }}</a>
                                    <div class="number" style="display:none;" id="ashow_plus_minus<%= variant.product_id %>">
                                        <span class="minus qty-minus-product"  data-parent_div_id="show_plus_minus<%= variant.product_id %>" readonly data-id="<%= variant.product_id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.product.vendor_id %>">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </span>
                                        <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d<%= variant.product_id %>" readonly placeholder="1" type="text" value="2" class="input-number input_qty" step="0.01">
                                        <span class="plus qty-plus-product"  data-id="" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.product.vendor_id %>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                <% } %>
                                <% if(is_customizable){ %>
                                    <div class="customizable-text">customizable</div>
                                <% } %>
                            <% }else{ %>
                                <span class="text-danger">Out of stock</span>
                            <% } %>
                        </script>
        <script type="text/template" id="addon_template">
            <% if(addOnData != ''){ %>
                                <% if(addOnData.product_image){ %>
                                    <div class="d-flex" style="max-height:200px">
                                        <img class="w-100" src="<%= addOnData.product_image %>" alt=""  style="object-fit:cover">
                                    </div>
                                <% } %>
                                <div class="modal-header">
                                    <div class="d-flex flex-column">
                                        <h5 class="modal-title" id="product_addonLabel"><%= addOnData.translation_title %></h5>
                                        <% if(addOnData.averageRating > 0){ %>
                                        <div class="rating-text-box justify-content-start" style="width: max-content;">
                                            <span><%= addOnData.averageRating %></span>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                        </div>
                                        <% } %>
                                        <span><small><%= addOnData.translation_description %></small></span>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body p-0">
                                    <% _.each(addOnData.add_on, function(addon, key1){ %>
                                        <div class="border-product border-top">
                                            <div class="addon-product" style="padding: 16px;">
                                                <h4 addon_id="<%= addon.addon_id %>" class="header-title productAddonSet mb-0"><%= addon.title %></h4>
                                                <div class="addonSetMinMax mb-2">
                                                    <%
                                                        var min_select = '';
                                                        if(addon.min_select > 0){
                                                            min_select = 'Minimum ' + addon.min_select;
                                                        }
                                                        var max_select = '';
                                                        if(addon.max_select > 0){
                                                            max_select = 'Maximum ' + addon.max_select;
                                                        }
                                                        if( (min_select != '') && (max_select != '') ){
                                                            min_select = min_select + ' and ';
                                                        }
                                                    %>
                                                    <% if( (min_select != '') || (max_select != '') ) { %>
                                                        <small><%=min_select + max_select %> Selections allowed</small>
                                                    <% } %>
                                                </div>
                                                <div class="productAddonSetOptions" data-min="<%= addon.min_select %>" data-max="<%= addon.max_select %>" data-addonset-title="<%= addon.title %>">
                                                    <% _.each(addon.setoptions, function(option, key2){ %>
                                                        <% if(key2 == '5')  { %>
                                                            <div class="d-flex justify-content-end">
                                                                <a class="show_subet_addeon" data-div_id_show="subOption<%= addon.addon_id  %>_<%= key2  %>"  href="javascript:void(0)">{{ __('Show more') }}</a>
                                                            </div>
                                                            <div class="more-subset d-none" id="subOption<%= addon.addon_id %>_<%= key2 %>" >
                                                        <% } %>
                                                        <div class="checkbox-success d-flex mb-1 " <%= key2  %> >
                                                            <label class="pr-2 mb-0 flex-fill font-14" for="inlineCheckbox_<%= key1 %>_<%= key2 %>">
                                                                <%= option.title %>
                                                            </label>
                                                            <div>
                                                                <span class="addon_price mr-1 font-14">{{ Session::get('currencySymbol') }}<%= option.price %></span>
                                                                <input type="checkbox" id="inlineCheckbox_<%= key1 %>_<%= key2 %>" class="product_addon_option" name="addonData[<%= key1 %>][]" addonId="<%= addon.addon_id %>" addonOptId="<%= option.id %>" addonPrice="<%= option.price %>">
                                                            </div>
                                                        </div>
                                                        <% if(key2 == (_.size(addon.setoptions) - 1 ) ){ %>
                                                        </div>
                                                        <% } %>
                                                    <% }); %>
                                                </div>
                                            </div>
                                        </div>
                                    <% }); %>
                                    <div class="addon_response text-danger font-14 d-none" style="padding:0 16px"></div>
                                </div>
                                <div class="modal-footer flex-nowrap align-items-center">
                                    <div class="counter-container d-flex align-items-center">
                                        <span class="minus qty-action" >
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </span>
                                        <input style="text-align:center; width:60px; height:24px; padding-bottom: 3px; border:none" placeholder="1" type="text" value="1" class="addon-input-number" step="1" readonly>
                                        <span class="plus qty-action" >
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input type="hidden" id="addonVariantPriceVal" value="<%= addOnData.variant_price %>">
                                    <a class="btn btn-solid add-cart-btn flex-fill add_vendor_addon_product" id="add_vendor_addon_product" href="javascript:void(0)" data-variant_id="<%= addOnData.variant[0].id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= addOnData.vendor_id %>" data-product_id="<%= addOnData.id %>">{{ __('Add') }} {{ Session::get('currencySymbol') }}<span class="addon_variant_price"><%= addOnData.variant_price %></span></a>
                                </div>
                            <% } %>
                        </script>
        <div class="modal fade remove-item-modal" id="remove_item_modal" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="remove_itemLabel" aria-hidden="true"
            style="background-color: rgba(0,0,0,0.8); z-index: 1051">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-0">
                        <h5 class="modal-title" id="remove_itemLabel">{{ __('Remove Item') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="vendor_id" value="">
                        <input type="hidden" id="product_id" value="">
                        <input type="hidden" id="cartproduct_id" value="">
                        <h6 class="m-0">{{ __('Are You Sure You Want To Remove This Item?') }}</h6>
                    </div>
                    <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                        <button type="button" class="btn btn-solid black-btn"
                            data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-solid" id="remove_product_button">{{ __('Remove') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade product-addon-modal" id="product_addon_modal" tabindex="-1" aria-labelledby="product_addonLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                </div>
            </div>
        </div>
        <div class="modal fade repeat-item-modal" id="repeat_item_modal" data-backdrop="static" data-keyboard="false"
            tabindex="-1" aria-labelledby="repeat_itemLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-0">
                        <h5 class="modal-title" id="repeat_itemLabel">{{ __('Repeat last used customization') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="last_cart_product_id" value="">
                        <input type="hidden" class="curr_variant_id" value="">
                        <input type="hidden" class="curr_vendor_id" value="">
                        <input type="hidden" class="curr_product_id" value="">
                        <input type="hidden" class="curr_product_has_addons" value="">
                        <input type="hidden" add_to_cart_url="cart" value="{{ route('addToCart') }}">
                    </div>
                    <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                        <button type="button" class="btn btn-solid black-btn" id="repeat_item_with_new_addon_btn"
                            data-dismiss="modal">{{ __('Add new') }}</button>
                        <button type="button" class="btn btn-solid" id="repeat_item_btn">{{ __('Repeat last') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade customize-repeated-item-modal" id="customize_repeated_item_modal" data-backdrop="static"
            data-keyboard="false" tabindex="-1" aria-labelledby="customize_repeated_itemLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                </div>
            </div>
        </div>

    @endsection
    @section('script')
        <script src="{{ asset('front-assets/js/rangeSlider.min.js') }}"></script>
        <script src="{{ asset('front-assets/js/my-sliders.js') }}"></script>
        <script>
            var get_product_addon_url = "{{ route('vendorProductAddons') }}"

            jQuery(window).scroll(function() {

                var scroll = jQuery(window).scrollTop();
                if (scroll >= 900) {
                    jQuery(".categories-product-list").addClass("fixed-bar");
                } else {
                    jQuery(".categories-product-list").removeClass("fixed-bar");
                }
            });

            var addonids = [];
            var addonoptids = [];
            var showChar = 140;
            var ellipsestext = "...";
            var moretext = __("Read more");
            var lesstext = "Read less";
            $('.price_head .member_no span').each(function() {
                var content = $(this).html();
                if (content.length > showChar) {

                    var firstContent = content.substr(0, showChar);
                    var lastContent = content.substr(showChar, content.length - showChar);

                    var html = firstContent + '<span class="moreellipses">' + ellipsestext +
                        '&nbsp;</span><span class="morecontent"><span>' + lastContent +
                        '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                    $(this).html(html);
                }

            });

            $(".morelink").click(function() {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });

            $(document).delegate(".product_tag_filter", "change", function() {
                vendorProductsSearchResults();
            });
        </script>
        <script>
            var base_url = "{{ url('/') }}";
            var place_order_url = "{{ route('user.placeorder') }}";
            var payment_stripe_url = "{{ route('payment.stripe') }}";
            var user_store_address_url = "{{ route('address.store') }}";
            var promo_code_remove_url = "{{ route('remove.promocode') }}";
            var payment_paypal_url = "{{ route('payment.paypalPurchase') }}";
            var update_qty_url = "{{ url('product/updateCartQuantity') }}";
            var promocode_list_url = "{{ route('verify.promocode.list') }}";
            var payment_option_list_url = "{{ route('payment.option.list') }}";
            var apply_promocode_coupon_url = "{{ route('verify.promocode') }}";
            var payment_success_paypal_url = "{{ route('payment.paypalCompletePurchase') }}";
            var getTimeSlotsForOndemand = "{{ route('getTimeSlotsForOndemand') }}";
            var update_cart_schedule = "{{ route('cart.updateSchedule') }}";
            var showCart = "{{ route('showCart') }}";
            var update_addons_in_cart = "{{ route('addToCartAddons') }}";
            var vendor_products_page_search_url = "{{ route('vendorProductsSearchResults') }}";
            var get_last_added_product_variant_url = "{{ route('getLastAddedProductVariant') }}";
            var get_product_variant_with_different_addons_url = "{{ route('getProductVariantWithDifferentAddons') }}"
            var addonids = [];
            var addonoptids = [];
            var ajaxCall = 'ToCancelPrevReq';


            $(document).on('click', '.show_subet_addeon', function(e) {
                e.preventDefault();
                var show_class = $(this).data("div_id_show");
                $(this).addClass("d-none");
                $("#" + show_class).removeClass("d-none");
            });

            $(document).delegate('.changeVariant', 'change', function() {
                var variants = [];
                var options = [];
                var product_variant_url = "{{ route('productVariant', ':sku') }}";
                var sku = $(this).parents('.product_row').attr('data-p_sku');
                var that = this;
                $(that).parents('.product_row').find('.changeVariant').each(function() {
                    if (this.val != '') {
                        variants.push($(this).attr('vid'));
                        options.push($(this).val());
                    }
                });
                // console.log(variants);
                // console.log(options);
                // return 0;
                ajaxCall = $.ajax({
                    type: "post",
                    dataType: "json",
                    url: product_variant_url.replace(":sku", sku),
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
                        if (response.status == 'Success') {
                            $(that).parents('.product_row').find(".variant_response span").html('');
                            if (response.variant != '') {

                                $(that).parents('.product_row').find(".add-cart-btn").attr(
                                    'data-variant_id', response.variant.id);

                                $(that).parents('.product_row').find('.product_price').html('');
                                let variant_template = _.template($('#variant_template').html());
                                $(that).parents('.product_row').find('.product_price').append(
                                    variant_template({
                                        variant: response.variant
                                    }));

                                $(that).parents('.product_row').find('.product_variant_quantity_wrapper')
                                    .html('');
                                let variant_quantity_template = _.template($('#variant_quantity_template')
                                    .html());
                                $(that).parents('.product_row').find('.product_variant_quantity_wrapper')
                                    .append(variant_quantity_template({
                                        variant: response.variant
                                    }));

                                let variant_image_template = _.template($('#variant_image_template')
                                    .html());

                                $(that).parents('.product_row').find('.product_image').html('');
                                $(that).parents('.product_row').find('.product_image').append(
                                    variant_image_template({
                                        media: response.variant
                                    }));
                            }
                        } else {
                            $(that).parents('.product_row').find(".variant_response span").html(response
                                .message);
                            $(that).parents('.product_row').find(".add-cart-btn").hide();
                            $(that).parents('.product_row').find(
                                ".product_variant_quantity_wrapper .text-danger").remove();
                        }
                    },
                    error: function(data) {

                    },
                });
            });

            $(document).delegate("#vendor_search_box", "input", function() {
                let keyword = $(this).val();
                vendorProductsSearchResults();
            });

            function vendorProductsSearchResults() {
                let keyword = $("#vendor_search_box").val();
                var checkboxesChecked = [];
                $("input:checkbox[name=tag_id]:checked").each(function() {
                    checkboxesChecked.push($(this).val());
                });
                var checkedvalus = checkboxesChecked.length > 0 ? checkboxesChecked : null;
                // if (keyword.length > 2 || keyword.length == 0) {
                ajaxCall = $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: vendor_products_page_search_url,
                    data: {
                        tag_id: checkedvalus,
                        keyword: keyword,
                        vendor: "{{ $vendor->id }}",
                        vendor_category: "{{ $vendor_category ?? '' }}"
                    },
                    beforeSend: function() {
                        if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                            ajaxCall.abort();
                        }
                    },
                    success: function(response) {
                        if (response.status == 'Success') {
                            var cart_html = $('.vendor-products-wrapper #header_cart_main_ul_ondemand').html();
                            $('.vendor-products-wrapper').html(response.html);
                            $('.vendor-products-wrapper #header_cart_main_ul_ondemand').html(cart_html);
                        }
                    }
                });
                // }
            }
        </script>
@endsection
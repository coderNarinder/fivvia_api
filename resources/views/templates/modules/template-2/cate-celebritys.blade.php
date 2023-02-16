@extends('layouts.store', ['title' => $category->translation_name ])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .slick-track{
        margin-left: 0px;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
@endsection
@section('content')
 <header>
    <div class="mobile-fix-option"></div>
    @if(isset($set_template)  && $set_template->template_id == 1)
        @include('layouts.store/left-sidebar-template-one')
        @elseif(isset($set_template)  && $set_template->template_id == 2)
        @include('layouts.store/left-sidebar')
        @else
        @include('layouts.store/left-sidebar-template-one')
        @endif
</header>
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="top-banner-wrapper text-center">
                        @if(!empty($category->image))
                        <div class="common-banner"><img alt="" data-src="{{$category->image['image_path']}}" class="img-fluid blur-up lazyload"></div>
                        @endif
                        <div class="top-banner-content small-section">
                            <h4>{{ $category->translation_name }}</h4>
                            {{-- <div class="row">
                                <div class="col-12">
                                    <div class="slide-6 no-arrow">
                                        @foreach($category->childs->toArray() as $cate)
                                        <div class="category-block">
                                            <a href="{{route('categoryDetail', $cate['id'])}}">
                                                <div class="category-image"><img alt="" data-src="{{$cate['icon']['image_path']}}" ></div>
                                            </a>
                                            <div class="category-details">
                                                <a href="{{route('categoryDetail', $cate['id'])}}">
                                                    <h5>{{$cate['translation_name']}}</h5>
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="collection-filter col-lg-3">
                    <div class="theme-card">
                        <h5 class="title-border d-flex align-items-center justify-content-between">
                            <span>{{__('New Product')}}</span>
                            <span class="filter-back d-lg-none d-inline-block">
                                <i class="fa fa-angle-left" aria-hidden="true"></i> {{__('Back')}}
                            </span>
                        </h5>
                        <div class="offer-slider">
                            @if(!empty($newProducts) && count($newProducts) > 0)
                                @foreach($newProducts as $newProds)
                                    <div>
                                    @foreach($newProds as $new)
                                        <?php $imagePath = '';
                                        foreach ($new['media'] as $k => $v) {
                                            $imagePath = $v['image']['path']['image_path'];
                                        } ?>
                                        <a class="common-product-box scale-effect text-center border-bottom pb-2 mt-2" href="{{route('productDetail', [$new['vendor']['slug'],$new['url_slug']])}}">
                                            <div class="img-outer-box position-relative">
                                                <img class="blur-up lazyload" data-src="{{$imagePath}}" alt="">
                                                <div class="pref-timing">
                                                    <!--<span>5-10 min</span>-->
                                                </div>
                                                <i class="fa fa-heart-o fav-heart" aria-hidden="true"></i>
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

                                                            <!-- @if($client_preference_detail)
                                                                @if($client_preference_detail->rating_check == 1)
                                                                    @if($new['averageRating'] > 0)
                                                                        <div class="rating-box">
                                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                                            <span>{{ $new['averageRating'] }}</span>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endif   -->
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
                <div class="collection-content col-lg-9">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn"><span class="filter-btn btn btn-theme"><i class="fa fa-filter" aria-hidden="true"></i> Filter</span></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="product-filter-content">
                                                    <div class="collection-view">
                                                        <ul>
                                                            <li><i class="fa fa-th grid-layout-view"></i></li>
                                                            <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                        </ul>
                                                    </div>
                                                    {{-- <div class="collection-grid-view">
                                                        <ul>
                                                            <li><img class="blur-up lazyload" data-src="{{asset('front-assets/images/icon/2.png')}}" alt="" class="product-2-layout-view"></li>
                                                            <li><img class="blur-up lazyload" data-src="{{asset('front-assets/images/icon/3.png')}}" alt="" class="product-3-layout-view"></li>
                                                            <li><img class="blur-up lazyload" data-src="{{asset('front-assets/images/icon/4.png')}}" alt="" class="product-4-layout-view"></li>
                                                            <li><img class="blur-up lazyload" data-src="{{asset('front-assets/images/icon/6.png')}}" alt="" class="product-6-layout-view"></li>
                                                        </ul>
                                                    </div> --}}
                                                    {{-- <div class="product-page-per-view">
                                                        <?php $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 8; ?>
                                                        <select class="customerPaginate">

                                                            <option value="8" @if($pagiNate == 8) selected @endif>Show 8
                                                            </option>
                                                            <option value="12" @if($pagiNate == 12) selected @endif>Show 12
                                                            </option>
                                                            <option value="24" @if($pagiNate == 24) selected @endif>Show 24
                                                            </option>
                                                            <option value="48" @if($pagiNate == 48) selected @endif>Show 48
                                                            </option>
                                                        </select>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="displayProducts">
                                        <div class="product-wrapper-grid">
                                            <div class="row margin-res">
                                                @if($listData->isNotEmpty())
                                                    @foreach($listData as $key => $data)
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4">
                                                        <div class="product-box">
                                                            <a href="{{ route('celebrityProducts', $data->slug) }}">
                                                                <div class="celebrity-avatar text-center"><img width="100%" alt="{{$data->name}}" data-src="{{$data->avatar['image_path']}}" ></div>
                                                            </a>
                                                            <div class="celebrity-detail">
                                                                <a href="{{ route('celebrityProducts', $data->slug) }}">
                                                                    <h5 class="text-center">{{$data->name}}</h5>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{__('Details Not Available')}}</h5></div>
                                                @endif
                                            </div>
                                        </div>
                                        @if(count($listData))
                                        <div class="pagination pagination-rounded justify-content-end mb-0">
                                            {{ $listData->links() }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
@endsection

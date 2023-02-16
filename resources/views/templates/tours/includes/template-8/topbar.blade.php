@php

$applocale = 'en';
if(session()->has('applocale')){
    $applocale = session()->get('applocale');
}
$urlImg = $client_head->logo['image_path'];
@endphp
<div class="fivvia_topbar row">
 <div class="container">
  <ul class="nav justify-content-end">
                                   
 <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{session()->get('iso_code')}}
      </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
           @foreach(ClientCurrencies() as $listc)
            <a class="dropdown-item text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide {{session()->get('iso_code') ==  $listc->currency->iso_code ?  'active' : ''}}" href="#"><b>{{$listc->currency->symbol}} </b> ({{$listc->currency->iso_code}}) - {{$listc->currency->name}}</a>
            <div class="dropdown-divider"></div>
           @endforeach
      </div>
    </li>
 <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
     {{$applocale}} <i class="fa-regular fa-language"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
       @foreach(ClientLanguages() as $l)
               @if(!empty($l->language))
                <div class="dropdown-divider"></div>
                <a href="#" 
                class="text-base dropdown-item text-dark hover:text-orange transition-all font-light capitalize tracking-wide {{$applocale ==  $l->language->sort_code ?  'active' : ''}}">{{$l->language->sort_code}} - {{$l->language->name}}</a> 
                @endif
        @endforeach
    </div>
  </li>

  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{__('My Account')}}</a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
      @if(Auth::check())
          @if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1)
          <div class="dropdown-divider"></div>
          <a href="" data-lng="en" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">{{__('Control Panel')}}</a> 
          @endif
          <a href="" data-lng="en" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">{{__('Profile')}}</a> 
           <div class="dropdown-divider"></div>
          <a href="" data-lng="es" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">{{__('Logout')}}</a>
          
      @else
       <a href="" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">{{__('Login')}}</a> 
        <div class="dropdown-divider"></div>
       <a href="" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">{{__('Create Account')}}</a> 
      @endif
    </div>
  </li> 
</ul>
  
</div>
</div>




<header class="header">
    <div class="header-main">
        <div class="container">
            <nav class="navbar navbar-expand-md justify-content-end">
                <a class="navbar-brand" href="#">
                    <img src="{{$urlImg}}" alt="logo" srcset="" style="width: 100px"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                  <ul class="navbar-nav navbar-main">
                    <li class="nav-item">
                      <a class="nav-link active" href="#">home</a>
                    </li>
                    @foreach ($navCategories as $cate)
                            @if ($cate['name'])
                                <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdow-{{ $cate['id'] }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @if ($client_preference_detail->show_icons == 1 && \Request::route()->getName() == 'userHome')
                                            <div class="nav-cate-img"> <img class="blur-up lazyload"
                                                    data-src="{{ $cate['icon']['image_fit'] }}200/200{{ $cate['icon']['image_path'] }}"
                                                    alt=""> </div>
                                        @endif
                                        {{ $cate['name'] }}
                                  </a>
                                  @if (!empty($cate['children']))
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown-{{ $cate['id'] }}">
                                        <ul class="al_main_category_list">
                                            @foreach ($cate['children'] as $childs)
                                                <li  >
                                                    <a href="{{ route('categoryDetail', $childs['slug']) }}"><span
                                                            class="new-tag">{{ $childs['name'] }}</span></a>
                                                    @if (!empty($childs['children']))
                                                        <ul class="al_main_category_sub_list">
                                                            @foreach ($childs['children'] as $chld)
                                                                <li><a
                                                                        href="{{ route('categoryDetail', $chld['slug']) }}">{{ $chld['name'] }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                      </div>
                                    @endif
                               
                                </li>
                                
                            @endif
                        @endforeach
                  </ul>
                   
                </div>
              </nav>
        </div>
    </div>
</header>
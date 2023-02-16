
    <?php $client = getWebClient();?>
    <header id="sticky-header" 
    class="<?= !empty($headerClass) ? $headerClass : '' ?>">
        <div class="px-4 md:px-10 2xl:px-24 py-6 lg:py-0">
            <div class="flex items-center lg:relative">
                <div class="w-6/12 lg:w-2/12">

                    <div class="logo">
                        <a href="{{url('/')}}">
                            <img src="{{$client->logo->image_path}}" alt="logo" loading="lazy" width="125" height="45" />
                        </a>
                    </div>
                </div>
                <livewire:template.nav-menubar/>

                <div class="w-6/12 lg:w-3/12">
                    <ul class="flex items-center justify-end">
                        <li class="ml-6 hidden lg:block">
                            <button class="search-toggle text-right text-primary text-md hover:text-orange transition-all" aria-label="icon-settings">
                                <i class="icon-magnifier"></i>
                            </button>
                        </li>
                        <li class="ml-6">
                            <a href="#offcanvas-cart" class="text-primary text-md hover:text-orange transition-all relative offcanvas-toggle">
                                <span class="w-5 h-5 bg-dark text-white text-sm rounded-full font-normal flex flex-wrap items-center justify-center absolute -top-3 left-2 leading-none">0</span>
                                <i class="icon-bag"></i>
                                <span class="text-base leading-none text-dark">{{session()->get('iso_code')}} 0</span>
                            </a>

                        </li>
                        <li id="toggle-menu" class="ml-6 hidden lg:block relative">
                            <button class="text-primary text-md hover:text-orange transition-all toggle-menu" aria-label="icon-settings">
                                <i class="icon-settings"></i>
                            </button>
                            <div id="settings-card" class="bg-white absolute right-0 px-8 py-5 shadow w-80 opacity-0 invisible transition-all duration-300 ease-in-out z-20">


                                <h4 class="text-md text-dark font-normal capitalize tracking-wide pb-5 border-b border-solid border-gray-600 mb-5">Currency</h4>
                                <ul>
                                    @foreach(ClientCurrencies() as $listc)
                                     @if(!empty($listc->currency))
                                      <li class="my-4">
                                        <a href="#" 
                                        currId="{{$listc->currency_id}}" 
                                        class="customerCurr" 
                                        currSymbol="{{$listc->currency->symbol}}"
                                        class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide {{session()->get('iso_code') ==  $listc->currency->iso_code ?  'active' : ''}}"><b>{{$listc->currency->symbol}} </b> ({{$listc->currency->iso_code}}) - {{$listc->currency->name}}</a></li>

                                            <a href="javascript:void(0)" 
                                                currId="{{$listc->currency_id}}" 
                                                class="customerCurr" 
                                                currSymbol="{{$listc->currency->symbol}}">
                                                {{$listc->currency->iso_code}}
                                            </a>
                                        @endif
                                    @endforeach
                                    
                                </ul>
                                    @php
                                    $applocale = 'en';
                                    if(session()->has('applocale')){
                                        $applocale = session()->get('applocale');
                                    }
                                    @endphp

                                <h4 class="text-md text-dark font-normal capitalize tracking-wide pb-5 border-b border-solid border-gray-600 mb-5">Languages</h4>
                                <ul>
                                    @foreach(ClientLanguages() as $l)
                                    @if(!empty($l->language))
                                    <li class="my-4">
                                        <a href="#" 
                                        class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide {{$applocale ==  $l->language->sort_code ?  'active' : ''}}">{{$l->language->sort_code}} - {{$l->language->name}}</a></li>
                                        @endif
                                    @endforeach
                                    
                                </ul>
                                <h4 class="text-md text-dark font-normal capitalize tracking-wide pb-5 border-b border-solid border-gray-600 mb-5"> {{__('My Account')}}</h4>
                                <ul>
                                    @if(Auth::check())
                                        @if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1)
                                        <li>
                                            <a href="{{route('client.dashboard')}}" data-lng="en" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">{{__('Control Panel')}}</a>
                                        </li>
                                        @endif
                                        <li>
                                            <a href="{{route('user.profile')}}" data-lng="en" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">{{__('Profile')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{route('user.logout')}}" data-lng="es" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">{{__('Logout')}}</a>
                                        </li>

                                    @else
                                    <li class="my-4"><a href="{{route('customer.login')}}" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">Login</a></li>
                                    <li class="mt-4"><a href="{{route('customer.register')}}" class="text-base text-dark hover:text-orange transition-all font-light capitalize tracking-wide">Create Account</a></li>
                                    @endif
                                </ul>
                            </div>


                        </li>
                        <li class="ml-6 lg:hidden">
                            <a href="#offcanvas-mobile-menu" class="offcanvas-toggle text-primary text-md hover:text-orange transition-all"><i class="icon-menu"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </header>

    <div class="search-form fixed top-0 left-0 w-full bg-black opacity-95 min-h-screen items-center justify-center py-8 px-10 transform  transition-transform translate-x-full ease-in-out duration-500 hidden lg:flex z-50">
        <button class="search-close absolute left-1/2 text-white text-xl top-12 translate-y-1/2" aria-label="close icon"><span class="icon-close"></span></button>
        <form class="relative xl:w-1/3 lg:w-1/2" action="#" method="get">
            <input class="text-md font-normal border-b border-solid border-gray-600 bg-transparent h-14 w-full focus:outline-none pr-14 text-white" type="search" name="search" placeholder="Search our store" />
            <button class="absolute right-0 top-3 text-white text-md font-normal" type="submit" aria-label="submit button"><i class="icon-magnifier"></i></button>
        </form>
    </div>
    <!-- offcanvas-overlay start -->
    <div class="offcanvas-overlay hidden fixed inset-0 bg-black opacity-50 z-50"></div>
    <!-- offcanvas-overlay end -->
    <!-- offcanvas-mobile-menu start -->
    <div id="offcanvas-mobile-menu" class="offcanvas left-auto right-0  transform translate-x-translate-x-full-120 fixed font-normal text-sm top-0 z-50 h-screen w-72 sm:w-80 lg:w-96 transition-all ease-in-out duration-300 bg-white">

        <div class="px-8 py-12 h-5/6 overflow-y-auto">

            <!-- search form start -->

            <form class="pb-10 mb-10 border-b border-solid border-gray-600" action="#" method="get">
                <div class="relative">
                    <input class="w-full h-12 text-sm py-4 pl-4 pr-16 bg-gray-light text-dark placeholder-current focus:outline-none" type="search" name="search" placeholder="Search our store">
                    <button class="w-12 h-full absolute top-0 right-0 flex items-center justify-center text-dark text-md border-l border-solid border-gray-600" type="submit" aria-label="button"><i class="icon-magnifier"></i></button>
                </div>
            </form>

            <!-- search form end -->

            <!-- close button start -->
            <button class="offcanvas-close bg-dark group transition-all hover:text-orange text-white w-10 h-10 flex items-center justify-center absolute -left-10 top-0" aria-label="offcanvas"><i class="icon-close transition-all transform group-hover:rotate-90"></i></button>
            <!-- close button end -->

            <!-- offcanvas-menu start -->


            <nav class="offcanvas-menu pb-10 mb-10 border-b border-solid border-gray-600">
                <ul>
                    <li class="relative block">
                        <a href="#" class="block capitalize font-normal text-base my-2 py-1 font-roboto">Home</a>
                        <ul class="offcanvas-submenu static top-auto hidden w-full visible opacity-100">
                            <li><a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="index.html">Airpod</a></li>
                            <li><a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="index-2.html">Smartwatch</a></li>
                            <li><a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="index-3.html">Drone</a></li>
                            <li><a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="index-4.html">BackPack</a></li>
                        </ul>
                    </li>
                    <li class="relative block">
                        <a href="#" class="block capitalize font-normal text-base my-2 py-1 font-roboto">Shop</a>
                        <ul class="offcanvas-submenu static top-auto hidden w-full visible opacity-100">
                            <li>
                                <a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="#">Shop Grid</a>
                                <ul class="offcanvas-submenu static top-auto hidden w-full visible opacity-100">
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="shop-grid-3-column.html">Shop Grid 3 Column</a>
                                    </li>
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="shop-grid-4-column.html">Shop Grid 4 Column</a>
                                    </li>
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="shop-grid-left-sidebar.html">Shop Grid Left Sidebar</a>
                                    </li>
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="shop-grid-right-sidebar.html">shop Grid Right Sidebar</a>
                                    </li>

                                </ul>
                            </li>
                            <li>
                                <a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="#">shop list</a>
                                <ul class="offcanvas-submenu static top-auto hidden w-full visible opacity-100">
                                    <li><a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="shop-list.html">Shop List</a></li>
                                    <li><a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="shop-list-left-sidebar.html">Shop List Left Sidebar</a></li>
                                    <li><a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="shop-list-right-sidebar.html">Shop List right Sidebar</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="#">blogs</a>
                                <ul class="offcanvas-submenu static top-auto hidden w-full visible opacity-100">
                                    <li><a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-grid-3-column.html">Blog Grid 3 Column</a></li>
                                    <li><a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-grid-4-column.html">Blog Grid 4 Column</a></li>
                                    <li><a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-grid-left-sidebar.html">Blog Grid Left Sidebar</a></li>
                                    <li><a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-grid-right-sidebar.html">Blog Grid Right Sidebar</a></li>

                                </ul>
                            </li>
                            <li>
                                <a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="#">Product Types</a>
                                <ul class="offcanvas-submenu static top-auto hidden w-full visible opacity-100">
                                    <li><a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="single-product.html">Shop Single</a></li>
                                    <li><a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="single-product-configurable.html">Shop Variable</a></li>
                                    <li><a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="single-product-affiliate.html">Shop Affiliate</a></li>
                                    <li><a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="single-product-group.html">Shop Group</a></li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="relative block">
                        <a href="#" class="block capitalize font-normal text-base my-2 py-1 font-roboto">Blog</a>
                        <ul class="offcanvas-submenu static top-auto hidden w-full visible opacity-100">
                            <li>
                                <a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="#">Blog Grid</a>
                                <ul class="offcanvas-submenu static top-auto hidden w-full visible opacity-100">
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-grid-3-column.html">Blog Grid 3 column</a>
                                    </li>
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-grid-2-column.html">Blog Grid 2 column</a>
                                    </li>
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-grid-left-sidebar.html">Blog Grid Left Sidebar</a>
                                    </li>
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-grid-right-sidebar.html">Blog Grid Right Sidebar</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="text-sm py-2 px-4 text-dark font-light block font-roboto transition-all hover:text-orange" href="#">Blog List</a>
                                <ul class="offcanvas-submenu static top-auto hidden w-full visible opacity-100">
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-list-left-sidebar.html">Blog List Left Sidebar</a>
                                    </li>
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-list-right-sidebar.html">Blog List Right Sidebar</a>
                                    </li>
                                    <li>
                                        <a class="text-sm pt-3 px-10 pb-1 text-dark font-light block font-roboto transition-all hover:text-orange" href="blog-details.html">Blog details</a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </li>
                    <li class="relative block"><a href="about-us.html" class="relative block capitalize font-normal text-base my-2 py-1 font-roboto">about Us</a></li>
                    <li class="relative block"><a href="contact.html" class="relative block capitalize font-normal text-base my-2 py-1 font-roboto">Contact Us</a></li>
                </ul>
            </nav>
            <!-- offcanvas-menu end -->


            <nav>
                <ul id="settings-menu">
                    <li class="block mb-3">
                        <a class="flex flex-wrap justify-between mb-3 text-base text-dark hover:text-orange" href="javascript:void(0)">Currency <i class="icon-arrow-down"></i></a>
                        <ul class="sub-category hidden py-5 px-6 shadow">
                            <li class="my-2 block"><a class="font-light text-sm tracking-wide text-dark block hover:text-orange" href="#">EUR €</a></li>
                            <li class="my-2 block"><a class="font-light text-sm tracking-wide text-dark block hover:text-orange" href="#">USD $</a></li>
                        </ul>
                    </li>
                    <li class="block mb-3">
                        <a class="flex flex-wrap justify-between mb-3 text-base text-dark hover:text-orange" href="javascript:void(0)">Account <i class="icon-arrow-down"></i></a>
                        <ul class="sub-category hidden py-5 px-6 shadow">
                            <li class="my-2 block"><a class="font-light text-sm tracking-wide text-dark block hover:text-orange" href="#">English</a></li>
                            <li class="my-2 block"><a class="font-light text-sm tracking-wide text-dark block hover:text-orange" href="#">Français</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

        </div>
    </div>

    <!-- offcanvas-mobile-menu end -->
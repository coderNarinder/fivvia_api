<div class="hidden lg:flex flex-1 xl:relative">
 <nav class="main-menu">
    <ul class="flex flex-wrap">
    	@foreach($navCategories as $menu)
          <li class="main-menu__item group">
		    <a class="block py-10 xl:px-6 md:px-5 capitalize font-normal text-md text-primary hover:text-orange transition-all" 
		    href="{{route('categoryDetail', $menu['slug']) }}">
		    {{$menu['name']}}
		  </a>
		  @if(!empty($menu['children']) && count($menu['children']) > 0)

		    <ul class="mega-menu flex flex-wrap bg-white py-5 px-8 shadow transition-all absolute left-0 top-full opacity-0 group-hover:opacity-100 invisible group-hover:visible group-hover:-translate-y-3 transform z-10">
              @foreach($menu['children'] as $submenu)
		        <li class="flex-auto px-4">
		            <a class="font-normal text-base capitalize text-dark pb-5 border-b block border-solid border-gray-600 mb-6 tracking-wide transition-all hover:text-orange" href="{{route('categoryDetail', $submenu['slug']) }}">{{$submenu['name']}}</a>
		            @if(!empty($submenu['children']))
		            <ul class="pb-2">
		            	@foreach($submenu['children'] as $child)
		                <li class="my-3">
		                	<a class="font-normal text-base capitalize text-dark tracking-wide block hover:text-orange transition-all" 
		                	href="{{route('categoryDetail', $child['slug']) }}">{{$child['name']}}</a>
		                </li> 
		                @endforeach
		            </ul>
		            @endif
		        </li>
		        @endforeach

		    </ul>
		    @endif
        </li>


        @endforeach

        @if ($client_preference_detail->header_quick_link == 1)
                            <li class="onhover-dropdown quick-links quick-links">

                                <span class="quick-links ml-1 align-middle">{{ __('Quick Links') }}</span>
                                </a>
                                <ul class="onhover-show-div">


                                    @foreach (ClientPages() as $page)
                                        @if (isset($page->primary->type_of_form) && $page->primary->type_of_form == 2)
                                            @if (isset($last_mile_common_set) && $last_mile_common_set != false)
                                                <li>
                                                    <a href="{{ route('extrapage', ['slug' => $page->slug]) }}">
                                                        @if (isset($page->translations) && $page->translations->first()->title != null)
                                                            {{ $page->translations->first()->title ?? '' }}
                                                        @else
                                                            {{ $page->primary->title ?? '' }}
                                                        @endif
                                                    </a>
                                                </li>
                                            @endif
                                        @else
                                            <li>
                                                <a href="{{ route('extrapage', ['slug' => $page->slug]) }}"
                                                    target="_blank">
                                                    @if (isset($page->translations) && $page->translations->first()->title != null)
                                                        {{ $page->translations->first()->title ?? '' }}
                                                    @else
                                                        {{ $page->primary->title ?? '' }}
                                                    @endif
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
       </ul>
  </nav>
</div>
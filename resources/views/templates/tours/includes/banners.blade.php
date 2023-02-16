@if(count($banners)) 
<section class="hero-section relative">
        <div class="hero-slider overflow-hidden">
            <div class="swiper-container">
                <div class="swiper-wrapper">
        @foreach($banners as $banner)
        @php
        $url = '';
        if($banner->link == 'category'){
        if($banner->category != null){
        $url = route('categoryDetail', $banner->category->slug);
        $name = $banner->category->name;
        }
        }
        else if($banner->link == 'vendor'){
        if($banner->vendor != null){
        $url = route('vendorDetail', $banner->vendor->slug);
        $name = $banner->vendor->name;
        }
        }
        @endphp
        

 <!-- swiper-slide start -->
                    <div class="swiper-slide 2xl:h-screen lg:h-700px xs:h-600px flex flex-wrap items-center px-4 md:px-10 2xl:px-24 py-6 lg:py-0  bg-no-repeat bg-left-top xl:bg-right bg-cover" style="background-image: url('{{$banner->image['image_path']}}');">
                        <div class="grid grid-cols-12">
                            <div class="col-span-12">
                                <div class="slider-content md:max-w-[500px] lg:max-w-[600px] 2xl:max-w-[800px]">
                                    <span class="text-lg font-normal text-primary block mb-3">#{{$name}}.</span>
                                    <h1 class="font-playfair font-bold text-orange text-3xl sm:text-4xl lg:text-5xl 2xl:text-7xl mb-5">{{$banner->name}} </h1>
                                    <hr class="w-16 h-1 bg-orange mb-7 border-0">
                                    <p class="font-normal text-primary text-sm lg:text-md">
                                       {{$banner->description}}
                                    </p>
                                    <div class="inline-block mt-8 lg:mt-12">
                                          @if($url)
                                        <a class="flex flex-wrap items-center bg-primary transition-all hover:bg-orange hover:text-white px-3 md:px-4 xl:px-10 py-3 md:py-4 xl:py-5 rounded-full text-orange capitalize font-medium text-sm lg:text-md leading-normal" 
                                        href="{{$url}}">Explore More
                                        <i class="icon-basket-loaded ml-3 xl:ml-5"></i>
                                    </a>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- swiper-slide end-->

        @endforeach
        
        </div>
    </div>

    <!-- Add Pagination -->
    <div class="swiper-pagination"></div> 
</div>
</section>
@endif


    
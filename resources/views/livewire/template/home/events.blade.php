<div>


@foreach($banners as $k => $banner)
@php
        $url = '';
        if($banner->link == 'category'){
        if($banner->category != null){
        $url = route('categoryDetail', $banner->category->slug);
        }
        }
        else if($banner->link == 'vendor'){
        if($banner->vendor != null){
        $url = route('vendorDetail', $banner->vendor->slug);
        }
        }
        $bg_image = !empty($banner->background_image) ? url($banner->background_image) : '';
        @endphp


@if($k % 2 != 0)

<section class="pt-24"
 style="<?= $banner->type == 'color' ? 'background-color:'.$banner->background_color.';' : 'background-image:url('.$bg_image.');background-size: 100%;' ?>">
        <div class="container">
            <div class="flex items-center -mx-4 flex-wrap">
                <div class="w-full md:w-1/2 px-4  order-last md:order-first">
                    <img class="mt-8 md:mt-0 w-full h-full lg:pr-14 xl:pr-20" 
                    src="{{!empty($banner->image) ? url($banner->image) : ''}}" 
                    alt="product image" loading="lazy" width="512" height="647">
                </div>

                <div class="w-full md:w-1/2 px-4">
                    <h2 class="text-md font-normal text-primary block mb-4">{{$banner->tagline}}</h2>
                    <h3 class="font-playfair font-bold text-orange text-[30px] sm:text-[36px] xl:text-[48px] leading-tight mb-5"> {{$banner->title}}</h3>
                    <hr class="w-16 h-1 bg-orange mb-7 border-0">
                    <p class="font-normal text-primary text-base xl:text-md">
                       {{$banner->description}}
                    </p>
                    <a href="{{$url}}" class="bg-primary transition-all hover:bg-orange hover:text-white px-5 md:px-12 py-3 md:py-4 xl:py-4 rounded-full text-orange capitalize font-medium text-sm lg:text-md inline-block mt-8 leading-normal">Buy Now</a>
                </div>
            </div>
        </div>
</section>
@else

<section class="pt-24 pb-24">
        <div class="container">
            <div class="flex items-center -mx-4 flex-wrap">
                <div class="w-full md:w-1/2 px-4">
                    <h2 class="text-md font-normal text-primary block mb-4">{{$banner->tagline}}</h2>
                    <h3 class="font-playfair font-bold text-orange text-[30px] sm:text-[36px] xl:text-[48px] leading-tight mb-5">{{$banner->title}}</h3>
                    <hr class="w-16 h-1 bg-orange mb-7 border-0">
                    <p class="font-normal text-primary text-base xl:text-md">
                       {{$banner->description}}
                    </p>
                    <a href="{{$url}}" class="bg-primary transition-all hover:bg-orange hover:text-white px-5 md:px-12 py-3 md:py-4 xl:py-4 rounded-full text-orange capitalize font-medium text-sm lg:text-md inline-block mt-8 leading-normal">Buy Now</a>
                </div>
                <div class="w-full md:w-1/2 px-4">
                    <img class="mt-8 md:mt-0 w-full h-full" 
                    src="{{!empty($banner->image) ? url($banner->image) : ''}}" 
                     alt="product image" loading="lazy" width="612" height="723">
                </div>
            </div>
        </div>
    </section>
@endif
@endforeach
</div>
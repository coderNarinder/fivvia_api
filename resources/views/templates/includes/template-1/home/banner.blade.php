@if(count($banners)) 
<section class="p-0 small-slider">

    <div class="slide-1 home-slider mb-md-4 mb-4">
        @foreach($banners as $banner)
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
        @endphp
        <div>
            @if($url)
            <a href="{{$url}}">
            @endif
                <div class="home text-center">
                    <img src="{{ $banner->image['image_path']}}" class="bg-img blur-up lazyload">
                </div>
            @if($url)
            </a>
            @endif
        </div>
        @endforeach
    </div>
</section>
@endif
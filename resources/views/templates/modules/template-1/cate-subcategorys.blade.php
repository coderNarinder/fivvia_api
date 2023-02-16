
@extends(getTemplateLayoutPath('layout'))
 @section('css')
 @livewireStyles 
@endsection
@section('content')
  <!-- Hero section start -->
    <div class="py-9 bg-gray-light">
        <div class="container">
            <div class="grid grid-cols-12 gap-x-4">
                <div class="col-span-12">
                   
                    @if(!empty($category))
                   @include('templates.includes.template-1.categories_breadcrumb')
                    @endif
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
                       
                             

                        <div class="mb-12">
                            <h4 class="font-medium text-lg xl:text-3xl text-dark capitalize mb-8">{{__('New Product')}}</h4>

                            <div class="sidebar-project-wrap mt-30">
                                 @if(!empty($newProducts) && count($newProducts) > 0)
                                @foreach($newProducts as $newProds)
                                           
                                    @foreach($newProds as $new)
                                        <?php $imagePath = '';
                                        foreach ($new['media'] as $k => $v) {
                                            $imagePath =  $v['image']['path']['image_path'];
                                        } ?>
                                        <div class="flex flex-wrap pb-5 mb-5 border-b border-solid border-gray-300">
                                            <div class="w-20 mr-5 relative">
                                                <a href="{{route('productDetail', [$new['vendor']['slug'],$new['url_slug']])}}" class="block absolute top-0 left-0 h-full">
                                                    <img class="object-cover w-full h-full" 
                                                    loading="lazy" width="240" height="114" 
                                                    src="{{$imagePath}}" alt="blog image"></a>
                                            </div>
                                            <div class="flex-1">
                                                <span class="text-sm">{{ $new['translation_title'] }}</span>
                                                 <p>{{$new['vendor']['name']}}</p>
                                                        <p class="pb-1">{{__('In')}} {{$new['category_name']}}</p>
                                                <h4><a class="transition-all hover:text-orange text-sm"
                                                 href="{{route('productDetail', [$new['vendor']['slug'],$new['url_slug']])}}">
                                              @if($new['inquiry_only'] == 0)
                                                                    <?php $multiply = $new['variant_multiplier']; ?>
                                                                    {{ Session::get('currencySymbol').' '.(number_format($new['variant_price'] * $multiply,2))}}
                                                                @endif</a></h4>
                                            </div>
                                        </div>
                                    @endforeach
                                          
                                @endforeach
                            @endif
                            </div>
                        </div>

                      
                         
                    </div>

                </div>

                <div class="flex-1 px-4">

                    <div class="flex flex-wrap -my-4 -px-4">
                       
                        @if(!empty($category->childs) && count($category->childs) > 0)
                            @foreach($category->childs->toArray() as $cate)
 <div class="w-full md:w-1/2 lg:w-1/2 px-4 my-4">
                            <div class="border border-solid border-gray-300 p-[20px] md:p-[30px] group">
                                <div class="mb-6">
                                    <a href="{{route('categoryDetail', $cate['slug'])}}" class="overflow-hidden block">
                                        <img class="transform group-hover:scale-110 transition-transform duration-500 w-full" 
                                        src="{{$cate['icon']['image_path']}}" alt="blog image">
                                    </a>
                                </div>
                                <h3><a href="{{route('categoryDetail', $cate['slug'])}}" class="block text-base md:text-md hover:text-orange transition-all font-medium pb-[10px] leading-[1.3]">{{$cate['translation_name']}}</a></h3>
                                 

                                
                                <a class="bg-white transition-all hover:bg-orange hover:border-orange hover:text-white text-dark capitalize font-medium text-sm inline-block border border-solid border-gray-300 px-8 py-4 leading-none mb-[10px]" href="{{route('categoryDetail', $cate['slug'])}}">view products</a>
                            </div>
                        </div>
                            @endforeach
                        @else
                            <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{__('Details Not Available')}}</h5></div>
                        @endif
                    </div>



                </div>
            </div>
        </div>
    </div>

    <!-- blog grid section end -->
 @endsection

@section('js')
 
@endsection

	
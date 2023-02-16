  <!-- Product section start -->
    <section class="product-section pt-24 pb-24">
        <div class="container">

            <div class="grid grid-rows-1 grid-flow-col gap-4">
                <div class="text-center mb-14">
                    <h2 class="font-playfair font-bold text-orange text-4xl lg:text-xl mb-4">{{$title ?? 'New Arrivals'}}</h2>
                    <p class="font-normal text-black text-base">There are many variations of passages of Lorem</p>
                </div>
            </div>


             
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <section class="relative -m-4">
                        <div class="product-carousel overflow-hidden p-4">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                	@foreach($products as $product)
                                	<!-- swiper-slide start -->
                                       <div class="swiper-slide">
                                      @include(getTemplateLayoutPath('includes').'product')
                                     </div>
                                     <!-- swiper-slide end -->
                                    @endforeach
                                </div>
                            </div>

                            <!-- Add Pagination -->

                            <!-- <div class="swiper-pagination"></div> -->

                            <!-- swiper navigation -->

                            <div class="swiper-buttons">
                                <div class="swiper-button-prev right-auto left-4  w-12 h-12 rounded-full bg-white border border-solid border-gray-400 text-sm text-dark opacity-100 transition-all hover:text-orange hover:border-orange"></div>
                                <div class="swiper-button-next left-auto right-4  w-12 h-12 rounded-full bg-white border border-solid border-gray-400 text-sm text-dark opacity-100 transition-all hover:text-orange hover:border-orange"></div>
                            </div>
                        </div>
                    </section>




                </div>
            </div>

        </div>
    </section>
    <!-- Product section end -->

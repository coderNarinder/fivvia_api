   <!-- Blog section start -->
    <div class="blog-carousel-section pt-24 pb-24" >


        <div class="container">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
                    <div class="section-title text-center pb-14">
                        <h3 class="font-playfair font-bold text-orange text-4xl lg:text-xl mb-4">{{$vendors_title ?? 'Best Sellers'}}</h3>
                        <p class="font-normal text-black text-base">There are many variations of passages of Lorem</p>
                    </div>
                </div>
                <div class="col-span-12">
                    <section class="relative -m-4">
                        <div class="blog-carousel overflow-hidden p-4">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">

                                	@foreach($vendor_list as $vendor)
                                    <!-- swiper-slide start -->
                                    <div class="swiper-slide">
 
                        
                                        <div class="border border-solid border-gray-300 p-[20px] md:p-[30px] group">
                                            <div class="mb-6">
                                                <a href="{{route('vendorDetail')}}/{{$vendor->slug}}" 
                                                	class="overflow-hidden block">
                                                	  @if($vendor->is_vendor_closed == 1) 
							                          
							                             <img class="transform group-hover:scale-110 grayscale-image transition-transform duration-500 w-full h-full" src="<?= $vendor->logo['image_path'] ?>" alt="blog image" loading="lazy" width="600" height="400" />
							                       @else
							                           
							                            <img class="transform group-hover:scale-110 transition-transform duration-500 w-full h-full" src="<?= $vendor->logo['image_path'] ?>" alt="blog image" loading="lazy" width="600" height="400" />
							                       @endif

                                                   
                                                </a>
                                            </div>
                                            <h3><a href="{{route('vendorDetail')}}/{{$vendor->slug}}" class="block text-base md:text-md hover:text-orange transition-all font-medium pb-[10px]"><?= $vendor->name ?>

                                            </a></h3>
                                            <div class="blog-meta">
							                        @if($preferences)
							                            @if($preferences->rating_check == 1)
							                                 @if($vendor->getRating() > 0)
							                                    <span class="rating m-0">
							                                    	<?= $vendor->getRating() ?> 
							                                        <i class="fa fa-star text-white p-0"></i>
							                                    </span>
							                               @endif
							                            @endif
							                        @endif



                                                     <ul class="flex flex-wrap items-center pb-[10px]">
                                                     	 <li class="text-sm"><a href="#" class="text-sm text-dark hover:text-orange transition-all"><?= $vendor->vendorCates() ?></a>  </li>
                                                	  </ul>
                                                	  @if(($preferences) && ($preferences->is_hyperlocal == 1))
                                                	   <ul class="flex flex-wrap items-center pb-[10px]">
                                                	    <?php $distance = $vendor->getVendorDistanceWithTime($latitude,$longitude); ?>
                                                     	  <li class="text-sm"><a href="#" class="text-sm text-dark hover:text-orange transition-all">
                                                     	  	<?= $distance['lineOfSightDistance'] ?></a>  </li>
                                                     
                                                     	  <li class="text-sm"><a href="#" class="text-sm text-dark hover:text-orange transition-all"><i class="fa fa-clock"></i> <?= $distance['timeofLineOfSightDistance'] ?></a>  </li>
									                  </ul>
									                  @endif
                                                  
                                                   
                                            </div> 
                                        </div>  
                                    </div>
                                    <!-- swiper-slide end-->
                                    @endforeach
                                   

                                </div>
                            </div>

                            <!-- Add Pagination -->

                            <!-- <div class="swiper-pagination"></div> -->

                            <!-- swiper navigation -->

                            <div class="swiper-buttons">
                                <div class="swiper-button-prev right-auto left-2 md:-left-2  w-12 h-12 rounded-full bg-white border border-solid border-gray-600 text-sm text-dark opacity-100 transition-all hover:text-orange hover:border-orange"></div>
                                <div class="swiper-button-next left-auto right-2 md:-right-2  w-12 h-12 rounded-full bg-white border border-solid border-gray-600 text-sm text-dark opacity-100 transition-all hover:text-orange hover:border-orange"></div>
                            </div>
                        </div>
                    </section>




                </div>
            </div>
        </div>
    </div>
    <!-- Blog section end -->

    <div class="border border-solid border-gray-300 transition-all hover:shadow-product group">
        <div class="relative overflow-hidden">
            
            <img class="w-full h-full" src="{{$product['image_url']}}" 
            alt="product image" loading="lazy" width="432" height="480" />
            <!-- actions start -->

            <div class="absolute left-2/4 top-2/4 transform -translate-x-2/4 -translate-y-2/4 z-10">
                <ul class="flex items-center justify-center bg-white shadow rounded-full h-0 transition-all group-hover:h-16 duration-500 overflow-hidden">
                    <li class="py-4 pl-7 md:py-5 md:pl-8">
                        <a href="<?= $product['vendor']['slug'] ?>/product/<?= $product['url_slug'] ?>" 
                        class="text-dark flex items-center justify-center text-md hover:text-orange modal-toggle" data-tippy-content="Quick View" aria-label="Quick View">
                            <i class="icon-magnifier"></i>
                        </a>
                    </li>
                   
                </ul>
            </div>

            <!-- actions end -->

        </div>

        <div class="py-5 px-4">
            <ul class="mb-3 text-sm capitalize">
                <li class="flex flex-wrap items-center justify-between"><span>
                	<span>Category: </span><span class="text-orange">{{$product['category']}}</span></span> 
                	<span><span>Seller: </span> <span class="text-orange">{{$product['vendor_name']}}</span> </span>
                </li>
            </ul>
         
         
		<h3 class="mt-4">
		<a class="block text-base hover:text-orange transition-all" 
		href="<?= $product['vendor']['slug'] ?>/product/<?= $product['url_slug'] ?>">{{$product['title']}}</a></h3>
		<h4 class="font-bold text-md leading-none text-orange mt-3">{{$product['price']}}</h4>
		</div>
    </div>

 
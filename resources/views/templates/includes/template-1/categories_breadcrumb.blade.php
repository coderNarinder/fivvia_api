@php
$subParent = $category->allParentsAccount;
$breadcrumb = '<li class="breadcrumb-item align-items-center active" aria-current="page">'.$category->translation_name.'</li>';
do{
    if(!empty($subParent)){
    if(!empty($subParent->slug) && strtolower($subParent->slug) == "root"){
        $breadcrumb = '<li class="mr-5"><a href="'.route("userHome").'" 
        	class="text-dark font-medium text-base uppercase transition-all hover:text-orange relative before:w-5 before:h-1px before:empty before:absolute before:top-3 before:bg-dark before:transform before:rotate-115 before:-right-5">Home</a></li>'.$breadcrumb;
    } else{
        $translation_name = ($subParent->translation->first()) ? $subParent->translation->first()->name : $subParent->slug;
        $breadcrumb = '<li class="mr-5">
        	<a class="text-dark font-medium text-base uppercase transition-all hover:text-orange relative before:w-5 before:h-1px before:empty before:absolute before:top-3 before:bg-dark before:transform before:rotate-115 before:-right-5" href="'.route("categoryDetail",$subParent->slug).'">'.$translation_name.'</a></li>'.$breadcrumb;
    }
    $subParent = $subParent->allParentsAccount;
  }
} while(!empty($subParent));
@endphp
 
                  <nav>
                        <ul class="flex flex-wrap items-center justify-center">
                              {!! $breadcrumb !!}
                             
                        </ul>

                    </nav>
<!DOCTYPE html>
<html>
<head>
	 @include('templates.includes.meta_title')
     <meta name="_token" content="{{ csrf_token() }}">
	 @include('templates.includes.stylesheets')
     @yield('css')
</head>
<body>
<div class="col-12">
@include(getTemplateLayoutPath('includes').'.topbar')
@yield('content')


</div>

@include(getTemplateLayoutPath('includes').'.footer')
 @yield('js')
  
</body>
</html>
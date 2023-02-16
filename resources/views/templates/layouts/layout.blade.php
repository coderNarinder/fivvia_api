<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
 @include('templates.includes.meta_title')
 <meta name="_token" content="{{ csrf_token() }}">
 @include('templates.includes.stylesheets')
 @yield('css')
</head>
<body class="font-poppins text-dark text-sm leading-loose">
  @yield('mainContent')
  @yield('js')
  @include('templates.includes.scripts')
 </body>
</html>
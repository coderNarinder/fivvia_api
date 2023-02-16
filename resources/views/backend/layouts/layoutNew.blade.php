<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Admin Panel </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/himalayan_mount/images/favicon.png" type="image/x-icon" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
   <link href="/admin-vue/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
   <link href="/admin-vue/bootstrap-fileinput-master/themes/explorer-fas/theme.css" media="all" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" href="{{url('admin-vue/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{url('admin-vue/css/all.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.0.0/sweetalert.min.css">
    
    <link rel="stylesheet" href="https://www.sayaansh.com/merchant_files/plugins/fontawesome-free/css/all.min.css">

    @yield('stylesheets')
    <link rel="stylesheet" href="{{url('admin-vue/css/style.css')}}">
    <link rel="stylesheet" href="{{url('admin-vue/css/responsive.css')}}">

 <style type="text/css">
    ol.category-choose-list li {
        border-bottom: 1px solid #ddd;
        margin-bottom: 17px;
        padding: 10px 0;
    }

    ol.category-choose-list li img {
        width: 50px;
        height: 50px;
        display: inline-block;
    }

    ol.category-choose-list .switcher {
        display: inline-block;
    }

    ol.category-choose-list li {
        text-align: left;
    }
 </style>

  </head>
  <body data-loader="{{url('/loading.gif')}}" class="theme-{{Auth::user()->themeMode}}">
    <div class="custom-loading loader">
      <img src="{{url('ajax-loader.gif')}}">
    </div>
    <div class="page-wrapper d-flex flex-wrap">
      @include('backend.includes.sidebar')
      <main>
              @include('backend.includes.topbar')
          <div class="main-content">
              @yield('content')
          </div>
      <div class="settings-sidebar">
        <div class="s-s-heading">
        <h4>Settings</h4>
        <a href="javascript:void(0);" class="shadow-icon"><span class="material-icons">
close
</span></a>
      </div>
      <div class="wrapper-shadow-inset">
  <ul class="l-s-none d-flex flex-wrap">
   
 </ul>
      </div>
      </div>
      </main>
      <footer>
        <p>Copyright ©2022 </p>
      </footer>
    </div>
  <!--   <script src="{{url('admin-vue/js/jquery.min.js')}}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha512-+NqPlbbtM1QqiK8ZAo4Yrj2c4lNQoGv8P79DPtKzj++l5jnN39rHA/xsqn8zE9l0uSoxaCdrOgFs6yjyfbBxSg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="{{url('admin-vue/js/bootstrap.min.js')}}"></script>
    <script src="{{url('admin-vue/js/swiper-bundle.min.js')}}"></script>
    <script src="{{url('admin-vue/js/jquery.nicescroll.min.js')}}"></script>
    <script src="{{url('admin-vue/js/custom.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{url('/bootstrap-fileinput-master/js/fileinput.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.0.0/sweetalert.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.0/jquery.validate.js"></script>
    <script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
    <script src="{{url('/admin-assets/js/validations/customValidation.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script type="text/javascript" src="{{url('merchant_files/js/custom.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <script type="text/javascript">
    $(".select2").select2();
</script>
    @yield('scripts')
<script>
  $( function() {
    function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }
 
    
  } );
  </script>
<script type="text/javascript">
   $("body").on('click','a.change-theme-mode',function(){
        var $type = $(this).is(':checked') ? 'dark' : 'light';
        var $loader = $("body").find('.loader');
        $.ajax({
                url : "{{url('/set-theme-mode')}}",
                type: 'GET',  
                dataTYPE:'JSON',
                data:{
                  'type':"<?= Auth::user()->theme_mode == 'light' ? 'dark' : 'light' ?>"
                },
                headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                       $loader.show();    
                },
                success: function (result) {
                    $loader.hide(); 
                    window.location.reload();
                      
                } 
        });
  });
</script>

  </body>
</html>
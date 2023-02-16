<div class="tap-top top-cls">
    <div>
        <i class="fa fa-angle-double-up"></i>
    </div>
</div>
<div class="d-none" id ="nearmap">
</div>

@php
    $mapKey = env('GOOGLE_MAP_API_KEY');
    $theme = \App\Models\ClientPreference::where(['id' => 1])->first();
    if($theme && !empty($theme->map_key)){
        $mapKey = $theme->map_key;
    }
    $webColor = '#ff4c3b';
    $darkMode = '';
    if(isset(Session::get('preferences')->theme_admin) && ucwords(session('preferences')->theme_admin) == 'Dark'){
        $darkMode = 'dark';
    }

    \Session::forget('success');
@endphp

@if(isset($set_template)  && $set_template->template_id == 1)
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom-template-one.css')}}">
@endif
<script type="text/javascript" src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
    var is_hyperlocal = 0;
    var selected_address = 0;
    var vendor_type = "delivery";
    var currentRouteName = "{{Route::currentRouteName()}}";
    @if(Session::has('vendorType'))
        vendor_type = "{{Session::get('vendorType')}}";
    @endif
    var autocomplete_url = "{{ route('autocomplete') }}";
    let stripe_publishable_key = '{{ $stripe_publishable_key }}';
    let yoco_public_key = '{{ $yoco_public_key }}';
    var login_url = "{{ route('customer.login') }}";
    if(currentRouteName == 'indexTemplateOne')
    var home_page_url = "{{ route('indexTemplateOne') }}";
    else
    var home_page_url = "{{ route('userHome') }}";

    var home_page_url_template_one = "{{ route('indexTemplateOne') }}";
    let home_page_url2 = home_page_url.concat("/");
    var add_to_whishlist_url = "{{ route('addWishlist') }}";
    var show_cart_url = "{{ route('showCart') }}";
    var home_page_data_url = "{{ route('homePageData') }}";
    var home_page_data_url_category_menu = "{{ route('homePageDataCategoryMenu') }}";
    var client_preferences_url = "{{ route('getClientPreferences') }}";
    var check_isolate_single_vendor_url = "{{ route('checkIsolateSingleVendor') }}";
    let empty_cart_url = "{{route('emptyCartData')}}";
    var cart_details_url = "{{ route('cartDetails') }}";
    var delete_cart_url = "{{ route('emptyCartData') }}";
    var user_checkout_url= "{{ route('user.checkout') }}";
    var cart_product_url= "{{ route('getCartProducts') }}";
    var delete_cart_product_url= "{{ route('deleteCartProduct') }}";
    var change_primary_data_url = "{{ route('changePrimaryData') }}";
    var url1 = "{{ route('config.update') }}";
    var url2 = "{{ route('config.get') }}";
    var razorpay_complete_payment_url = "{{ route('payment.razorpayCompletePurchase') }}";
    var payment_razorpay_url = "{{route('payment.razorpayPurchase')}}";
    var featured_product_language = "{{ __('Featured Product') }}";
    var new_product_language = "{{ __('New Product') }}";
    var on_sale_product_language = "{{ __('On Sale') }}";
    var best_seller_product_language = "{{ __('Best Seller') }}";
    var vendor_language = "{{ __('Vendors') }}";
    var brand_language = "{{ __('Brands') }}";
/////GCash Payment Routes
    var gcash_before_payment = "{{route('payment.gcash.beforePayment')}}";

///////////////Simplify Payment Routes
    var simplify_before_payment = "{{route('payment.simplify.beforePayment')}}";
    var simplify_create_payment = "{{route('payment.simplify.createPayment')}}";

//////////////Square payment Routes
    var square_before_payment = "{{route('payment.square.beforePayment')}}";
    var square_create_payment = "{{route('payment.square.createPayment')}}";

//////////////Ozow payment Routes
    var ozow_before_payment = "{{route('payment.ozow.beforePayment')}}";
    var ozow_create_payment = "{{route('payment.ozow.createPayment')}}";

/////////////Pagarme Payment Routes
    var pagarme_before_payment = "{{route('payment.pagarme.beforePayment')}}";
    var pagarme_create_payment = "{{route('payment.pagarme.createPayment')}}";

// Logged In User Detail
    var logged_in_user_name = "{{Auth::user()->name??''}}";
    var logged_in_user_email = "{{Auth::user()->email??''}}";
    var logged_in_user_phone = "{{Auth::user()->phone_number??''}}";
    var logged_in_user_dial_code = "{{Auth::user()->dial_code??'91'}}";
// Payment Gateway Key Detail
    var razorpay_api_key = "{{getRazorPayApiKey()??''}}";

// Client Perference  Detail
    var client_preference_web_color = "{{getClientPreferenceDetail()->web_color}}";
    var client_preference_web_rgb_color = "{{getClientPreferenceDetail()->wb_color_rgb}}";

// Client Detail
    var client_company_name = "{{getClientDetail()->company_name}}";
    var client_logo_url = "{{getClientDetail()->logo_image_url}}";

// is restricted
    var is_age_restricted ="{{$client_preference_detail->age_restriction}}";
    //user lat long

    var userLatitude = "{{ session()->has('latitude') ? session()->get('latitude') : 0 }}";
    var userLongitude = "{{ session()->has('longitude') ? session()->get('longitude') : 0 }}";

    if(!userLatitude){
        @if(!empty($client_preference_detail->Default_latitude))
        userLatitude = "{{$client_preference_detail->Default_latitude}}";
        @endif
    }
    if(!userLatitude ){
        userLatitude = "30.7333";
    }

    if(!userLongitude){
        @if(!empty($client_preference_detail->Default_longitude))
             userLongitude = "{{$client_preference_detail->Default_longitude}}";
        @endif
    }
    if(!userLatitude ){
        userLatitude = "76.7794";
    }
    // if((home_page_url != window.location.href) && (home_page_url2 != window.location.href)){
    //     $('.vendor_mods').hide();}
    // else{
    //     $('.vendor_mods').show();}

    @if(Session::has('selectedAddress'))
        selected_address = 1;
    @endif
    // @if( Session::has('preferences') )
    //     @if( (isset(Session::get('preferences')->is_hyperlocal)) && (Session::get('preferences')->is_hyperlocal == 1) )
    //         is_hyperlocal = 1;
    //     @endif;
    // @endif;

    @if($client_preference_detail->is_hyperlocal == 1)
        is_hyperlocal = 1;
        var defaultLatitude = "{{$client_preference_detail->Default_latitude}}";
        var defaultLongitude = "{{$client_preference_detail->Default_longitude}}";
        var defaultLocationName = "{{$client_preference_detail->Default_location_name}}";
    @endif

    var NumberFormatHelper = { formatPrice: function(x){
        if(x){
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        return x;
        }
    };
    @php
        $mapurl = "https://maps.googleapis.com/maps/api/js?key=".$mapKey."&v=3.exp&libraries=places,drawing";
    @endphp
</script>


<script type="text/javascript" src="{{asset('assets/js/constants.js')}}"></script>
<script type="text/javascript" src="{{$mapurl}}"></script>

<script>
      var bindLatlng = new google.maps.LatLng(userLatitude, userLongitude);
      var bindmapProp = {
            center:bindLatlng,
            zoom:13,
            mapTypeId:google.maps.MapTypeId.ROADMAP

        };
    var bindMap=new google.maps.Map(document.getElementById("nearmap"), bindmapProp);
</script>

{{-- <script type="text/javascript" src="{{asset('front-assets/js/all-min.js')}}" defer></script> --}}
<script type="text/javascript"src="{{asset('front-assets/js/slick.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/menu.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/lazysizes.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/jquery.elevatezoom.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/underscore.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/script.js')}}"></script>
<script type="text/javascript" src="{{asset('js/custom.js')}}"></script>
<script type="text/javascript" src="{{asset('js/location.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>

<!--WaitMe Loader Script -->
<script type="text/javascript" src="{{asset('js/waitMe.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/developer.js')}}"></script>
<!--WaitMe Loader Script -->

<!-- SweetAlert Script -->
<script type="text/javascript" src="{{asset('js/sweetalert2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
@if (Auth::check())
@if(Session::has('preferences') && !empty(Session::get('preferences')['fcm_api_key']))
<script type="text/javascript" src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
<script>

    // var tag = document.createElement('script');
    // tag.src = "{{asset('front-assets/js/all-min.js')}}";
    // tag.setAttribute('defer','');
    // var firstScriptTag = document.getElementsByTagName('script')[0];
    // firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var firebaseCredentials = {!!json_encode(Session::get('preferences')) !!};
    var firebaseConfig = {
        apiKey: firebaseCredentials.fcm_api_key,
        authDomain: firebaseCredentials.fcm_auth_domain,
        projectId: firebaseCredentials.fcm_project_id,
        storageBucket: firebaseCredentials.fcm_storage_bucket,
        messagingSenderId: firebaseCredentials.fcm_messaging_sender_id,
        appId: firebaseCredentials.fcm_app_id,
        measurementId: firebaseCredentials.fcm_measurement_id
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
        messaging.requestPermission().then(function() {
            return messaging.getToken()
        }).then(function(token) {
            $.ajax({
                url: "{{ route('user.save_fcm') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    fcm_token: token,
                },
                success: function(response) {

                },
            });
            console.log(token);

        }).catch(function(err) {
            console.log(`Token Error :: ${err}`);
        });
    }
    @if(empty(Session::get('current_fcm_token')))
    initFirebaseMessagingRegistration();
    @endif
    messaging.onMessage(function(payload) {
        if (!("Notification" in window)) {
            console.log("This browser does not support system notifications.");
        } else if (Notification.permission === "granted") {
            if (payload && payload.data && payload.data.type && (payload.data.type == "order_status_change" || payload.data.type == "reminder_notification")) {
                var notificationTitle = payload.notification.title;
                var notificationOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon
                };
                var push_notification = new Notification(
                    notificationTitle,
                    notificationOptions
                );
                push_notification.onclick = function(event) {
                    event.preventDefault();
                    window.open(payload.notification.click_action, "_blank");
                    push_notification.close();
                };
            }
        }
    });
</script>
@endif
@endif

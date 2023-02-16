@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Website SMS Settings</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
     
      <li class="breadcrumb-item active" aria-current="page">Website SMS Settings</li>
    </ol>
  </nav>
  
</div>

 
<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="websiteSettings" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.settings.sms') }}"
  enctype="multipart/form-data">
  <input type="hidden" name="redirect_link" value="{{ route('admin.settings.sms') }}">
   @csrf
     @include('admin.error_message')




	 
 
 
<div class="accordion" id="accordionSettings-apikeys">
  <div class="card">
    <div class="card-header" id="headingSettings1apikeys">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-apikeys" aria-expanded="true" aria-controls="collapseSettings-apikeys">
          <h3>SMS API's KEYS & Balance</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-apikeys" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-apikeys">
      <div class="card-body">
       <div class="row">
             
             <div class="col-sm-6">
               {{textbox2($errors,'API KEY 1*','APIKey_sms','nn',$APIKey_sms)}}
               <div class="alert alert-info"> <?= get_balance_sms() ?></div>
             </div>

              <div class="col-sm-6">
               {{textbox2($errors,'API KEY 2*','APIKey_sms2','nn',$APIKey_sms2)}}
               <div class="alert alert-info"> <?= get_balance_sms('APIKey_sms2') ?></div>
             </div>
        </div>
      </div>
    </div>
  </div>
  </div>
   
    


 
<div class="accordion" id="accordionSettings-login">
  <div class="card">
    <div class="card-header" id="headingSettings1">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-login" aria-expanded="true" aria-controls="collapseSettings-login">
          <h3>OTP For Login</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-login" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-login">
      <div class="card-body">
       <div class="row">
             <div class="col-sm-12">
             {{textbox2($errors,'Text Message*','otp_login_sms','nn',$otp_login_sms)}}
             </div>
             <div class="col-sm-6">
                {{textbox2($errors,'Template Id*','template_id_login_with_otp','nn',$template_id_login_with_otp)}}
              </div>
             <div class="col-sm-6">
               {{textbox2($errors,'Sender ID*','login_with_otp_sender_ID','nn',$login_with_otp_sender_ID)}}
             </div>
             
        </div>
      </div>
    </div>
  </div>
  </div>
   
    



<div class="accordion" id="accordionSettings-register">
  <div class="card">
    <div class="card-header" id="headingSettings1">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-register" aria-expanded="true" aria-controls="collapseSettings-register">
          <h3>OTP For Account Creation</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-register" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-register">
      <div class="card-body">


    <div class="row">
       <div class="col-sm-12">{{textbox2($errors,'Text Message*','otp_register_sms','nn',$otp_register_sms)}}</div>
       <div class="col-sm-6">
          {{textbox2($errors,'Template Id*','template_id_register_with_otp','nn',$template_id_register_with_otp)}}
        </div>
       <div class="col-sm-6">
         {{textbox2($errors,'Sender ID*','register_with_otp_sender_ID','nn',$register_with_otp_sender_ID)}}
       </div>
    </div>
    
    
     </div>
    </div>
  </div>
  </div>

   
    



<div class="accordion" id="accordionSettings-reset_password">
  <div class="card">
    <div class="card-header" id="headingSettingsreset_password">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-reset_password" aria-expanded="true" aria-controls="collapseSettings-reset_password">
          <h3>OTP To Reset Password</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-reset_password" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-reset_password">
      <div class="card-body">


    <div class="row">
       <div class="col-sm-12">
          {{textbox2($errors,'Text Message*','otp_reset_password_sms','nn',$otp_reset_password_sms)}}
        </div>
       <div class="col-sm-6">
          {{textbox2($errors,'Template Id*','template_id_reset_password_with_otp','nn',$template_id_reset_password_with_otp)}}
        </div>
       <div class="col-sm-6">
         {{textbox2($errors,'Sender ID*','reset_password_with_otp_sender_ID','nn',$reset_password_with_otp_sender_ID)}}
       </div>
    </div>
    
    
     </div>
    </div>
  </div>
  </div>

   
    


<div class="accordion" id="accordionSettings-profile_update">
  <div class="card">
    <div class="card-header" id="headingSettingsprofile_update">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-profile_update" aria-expanded="true" aria-controls="collapseSettings-profile_update">
          <h3>OTP For Profile Updation</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-profile_update" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-profile_update">
      <div class="card-body">


    <div class="row">
       <div class="col-sm-12">
          {{textbox2($errors,'Text Message*','otp_profile_update_sms','nn',$otp_profile_update_sms)}}
        </div>
       <div class="col-sm-6">
          {{textbox2($errors,'Template Id*','template_id_profile_update_with_otp','nn',$template_id_profile_update_with_otp)}}
        </div>
       <div class="col-sm-6">
         {{textbox2($errors,'Sender ID*','profile_update_with_otp_sender_ID','nn',$profile_update_with_otp_sender_ID)}}
       </div>
    </div>
    
    
     </div>
    </div>
  </div>
  </div>

   
    

<div class="accordion" id="accordionSettings-password_changed">
  <div class="card">
    <div class="card-header" id="headingSettingspassword_changed">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-password_changed" aria-expanded="true" aria-controls="collapseSettings-password_changed">
          <h3>Password Changed SMS</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-password_changed" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-password_changed">
      <div class="card-body">


    <div class="row">
       <div class="col-sm-12">
          {{textbox2($errors,'Text Message*','otp_password_changed_sms','nn',$otp_password_changed_sms)}}
        </div>
       <div class="col-sm-6">
          {{textbox2($errors,'Template Id*','template_id_password_changed_with_otp','nn',$template_id_password_changed_with_otp)}}
        </div>
       <div class="col-sm-6">
         {{textbox2($errors,'Sender ID*','password_changed_with_otp_sender_ID','nn',$password_changed_with_otp_sender_ID)}}
       </div>
    </div>
    
    
     </div>
    </div>
  </div>
  </div>

   
    



<div class="accordion" id="accordionSettings-register_reward">
  <div class="card">
    <div class="card-header" id="headingSettingsregister_reward">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-register_reward" aria-expanded="true" aria-controls="collapseSettings-register_reward">
          <h3>SMS For Rewards Points on Registeration</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-register_reward" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-register_reward">
      <div class="card-body">


    <div class="row">
       <div class="col-sm-12">
          {{textbox2($errors,'Text Message*','otp_register_reward_sms','nn',$otp_register_reward_sms)}}
        </div>
       <div class="col-sm-6">
          {{textbox2($errors,'Template Id*','template_id_register_reward_with_otp','nn',$template_id_register_reward_with_otp)}}
        </div>
       <div class="col-sm-6">
         {{textbox2($errors,'Sender ID*','register_reward_with_otp_sender_ID','nn',$register_reward_with_otp_sender_ID)}}
       </div>
    </div>
    
    
     </div>
    </div>
  </div>
  </div>

   
    




<div class="accordion" id="accordionSettings-giftcard_received">
  <div class="card">
    <div class="card-header" id="headingSettingsgiftcard_received">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-giftcard_received" aria-expanded="true" aria-controls="collapseSettings-giftcard_received">
          <h3>SMS To Gift Card Recipient</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-giftcard_received" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-giftcard_received">
      <div class="card-body">


    <div class="row">
       <div class="col-sm-12">
          {{textbox2($errors,'Text Message*','otp_giftcard_received_sms','nn',$otp_giftcard_received_sms)}}
        </div>
       <div class="col-sm-6">
          {{textbox2($errors,'Template Id*','template_id_giftcard_received_with_otp','nn',$template_id_giftcard_received_with_otp)}}
        </div>
       <div class="col-sm-6">
         {{textbox2($errors,'Sender ID*','giftcard_received_with_otp_sender_ID','nn',$giftcard_received_with_otp_sender_ID)}}
       </div>
    </div>
    
    
     </div>
    </div>
  </div>
  </div>

   
    



<div class="accordion" id="accordionSettings-giftcard_sent">
  <div class="card">
    <div class="card-header" id="headingSettingsgiftcard_sent">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-giftcard_sent" aria-expanded="true" aria-controls="collapseSettings-giftcard_sent">
          <h3>SMS to Gift Card Sender</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-giftcard_sent" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-giftcard_sent">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_giftcard_sent_sms','nn',$otp_giftcard_sent_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_giftcard_sent_with_otp','nn',$template_id_giftcard_sent_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','giftcard_sent_with_otp_sender_ID','nn',$giftcard_sent_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>




<div class="accordion" id="accordionSettings-order_place">
  <div class="card">
    <div class="card-header" id="headingSettingsorder_place">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-order_place" aria-expanded="true" aria-controls="collapseSettings-order_place">
          <h3>Order Placed</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-order_place" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-order_place">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_order_place_sms','nn',$otp_order_place_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_order_place_with_otp','nn',$template_id_order_place_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','order_place_with_otp_sender_ID','nn',$order_place_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>


<div class="accordion" id="accordionSettings-order_shipped">
  <div class="card">
    <div class="card-header" id="headingSettingsorder_shipped">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-order_shipped" aria-expanded="true" aria-controls="collapseSettings-order_shipped">
          <h3>Order Shipped</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-order_shipped" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-order_shipped">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_order_shipped_sms','nn',$otp_order_shipped_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_order_shipped_with_otp','nn',$template_id_order_shipped_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','order_shipped_with_otp_sender_ID','nn',$order_shipped_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>


<div class="accordion" id="accordionSettings-order_delivered">
  <div class="card">
    <div class="card-header" id="headingSettingsorder_delivered">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-order_delivered" aria-expanded="true" aria-controls="collapseSettings-order_delivered">
          <h3>Order Delivered</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-order_delivered" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-order_delivered">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_order_delivered_sms','nn',$otp_order_delivered_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_order_delivered_with_otp','nn',$template_id_order_delivered_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','order_delivered_with_otp_sender_ID','nn',$order_delivered_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>


<div class="accordion" id="accordionSettings-order_confirmation">
  <div class="card">
    <div class="card-header" id="headingSettingsorder_confirmation">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-order_confirmation" aria-expanded="true" aria-controls="collapseSettings-order_confirmation">
          <h3>Seller Order Confirmation</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-order_confirmation" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-order_confirmation">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_order_confirmation_sms','nn',$otp_order_confirmation_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_order_confirmation_with_otp','nn',$template_id_order_confirmation_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','order_confirmation_with_otp_sender_ID','nn',$order_confirmation_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>


    
<div class="accordion" id="accordionSettings-order_approved">
  <div class="card">
    <div class="card-header" id="headingSettingsorder_approved">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-order_approved" aria-expanded="true" aria-controls="collapseSettings-order_approved">
          <h3>Seller Order approved</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-order_approved" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-order_approved">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_order_approved_sms','nn',$otp_order_approved_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_order_approved_with_otp','nn',$template_id_order_approved_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','order_approved_with_otp_sender_ID','nn',$order_approved_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>


  
<div class="accordion" id="accordionSettings-Infringement">
  <div class="card">
    <div class="card-header" id="headingSettingsInfringement">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-Infringement" aria-expanded="true" aria-controls="collapseSettings-Infringement">
          <h3>Brand Infringement Form</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-Infringement" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-Infringement">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_Infringement_sms','nn',$otp_Infringement_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_Infringement_with_otp','nn',$template_id_Infringement_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','Infringement_with_otp_sender_ID','nn',$Infringement_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>



  
<div class="accordion" id="accordionSettings-ContactUs">
  <div class="card">
    <div class="card-header" id="headingSettingsContactUs">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-ContactUs" aria-expanded="true" aria-controls="collapseSettings-ContactUs">
          <h3>Contact Us</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-ContactUs" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-ContactUs">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_ContactUs_sms','nn',$otp_ContactUs_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_ContactUs_with_otp','nn',$template_id_ContactUs_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','ContactUs_with_otp_sender_ID','nn',$ContactUs_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>


    
  
<div class="accordion" id="accordionSettings-order_cancelled">
  <div class="card">
    <div class="card-header" id="headingSettingsorder_cancelled">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-order_cancelled" aria-expanded="true" aria-controls="collapseSettings-order_cancelled">
          <h3>Order Cancelled</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-order_cancelled" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-order_cancelled">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_order_cancelled_sms','nn',$otp_order_cancelled_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_order_cancelled_with_otp','nn',$template_id_order_cancelled_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','order_cancelled_with_otp_sender_ID','nn',$order_cancelled_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>


       
  
<div class="accordion" id="accordionSettings-welcome_seller">
  <div class="card">
    <div class="card-header" id="headingSettingswelcome_seller">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-welcome_seller" aria-expanded="true" aria-controls="collapseSettings-welcome_seller">
          <h3>Welcome SMS to Seller</h3>
        </button>
      </h2>
    </div>
    <div id="collapseSettings-welcome_seller" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-welcome_seller">
      <div class="card-body">
        <div class="row">
           <div class="col-sm-12">
              {{textbox2($errors,'Text Message*','otp_welcome_seller_sms','nn',$otp_welcome_seller_sms)}}
            </div>
           <div class="col-sm-6">
              {{textbox2($errors,'Template Id*','template_id_welcome_seller_with_otp','nn',$template_id_welcome_seller_with_otp)}}
            </div>
           <div class="col-sm-6">
             {{textbox2($errors,'Sender ID*','welcome_seller_with_otp_sender_ID','nn',$welcome_seller_with_otp_sender_ID)}}
           </div>
        </div>
     </div>
    </div>
  </div>
</div>


    
    <button type="submit" id="storyFormSbt" class="main-button btn-submit btn-form-submit">Submit</button>
  </form>
 </div>
 
 
@endsection
 


@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/croppie.js')}}"></script>
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/website_settings.js')}}"></script>
 <script type="text/javascript">
  // CKEDITOR.replace( 'subject' );




function readURL3(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { 
            $('#imagePreview3').attr('src',e.target.result);
            $('#imagePreview3').hide();
            $('#imagePreview3').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#home_banner_image").change(function() {
    readURL3(this);
});
</script>
@endsection
 
 

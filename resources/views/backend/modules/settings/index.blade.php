@extends('admin.layouts.layoutNew')
@section('content')
<div class="m-c-heading">
  <h2>Website Global Settings</h2>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
     
      <li class="breadcrumb-item active" aria-current="page">Website Global Settings</li>
    </ol>
  </nav>
  
</div>

 
<div class="wrapper-shadow-inset">

  <form class="form-edit-category" name="websiteSettings" role="form" method="post" id="categoryForm" 
  data-action="{{ route('admin.settings.website') }}"
  enctype="multipart/form-data">
  <input type="hidden" name="redirect_link" value="{{ route('admin.settings.website') }}">
   @csrf
     @include('admin.error_message')
	 
	 <div class="accordion" id="accordionSettings-1">
  <div class="card">
    <div class="card-header" id="headingSettings-1">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-1" aria-expanded="true" aria-controls="collapseSettings-1">
          <h3>Website Details</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-1" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-1">
      <div class="card-body">
          
          
          
          
        <div class="row">
		  <div class="col-md-8">

        {{textbox2($errors,'Website Title*','website_title','nn',$website_title)}}
        {{textbox2($errors,'Copyright*','copyright','nn',$copyright)}}
        {{textbox2($errors,'Developed By*','Developed_by','nn',$Developed_by)}}
       
      </div>
      <div class="col-md-4">
                 <div class="form-group">
                     <label>Website Logo</label>
                 </div>

                   <div class="avatar-upload">
                    <div class="avatar-edit">
                        <input type='file' name="website_logo" id="imageUpload" accept=".png, .jpg, .jpeg" />
                        <label for="imageUpload"></label>
                    </div>
                    <div class="avatar-preview">
                        <img src="">
                        
                        <img src="<?= (!empty($website_logo)) ? url($website_logo) : ''?>" id="imagePreview">
                    </div>
                </div>
      </div>
		</div>
		
		
		
		
      </div>
    </div>
  </div>
  </div>
	 
	 

<div class="accordion" id="accordionSettings-2">
  <div class="card">
    <div class="card-header" id="headingSettings1">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-2" aria-expanded="true" aria-controls="collapseSettings-2">
          <h3>Contact Details</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-2" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-2">
      <div class="card-body">


    <div class="row">
       <div class="col-sm-6">
         {{textbox2($errors,'Contact Number1*','contact_number1','nn',$contact_number1)}}
         {{textbox2($errors,'Contact Number2*','contact_number2','nn',$contact_number2)}}
         {{textbox2($errors,'Contact Number3*','contact_number3','nn',$contact_number3)}}
         {{textbox2($errors,'General Email*','admin_email','nn',$admin_email)}}
       
       </div>
       <div class="col-sm-6">
        {{textbox2($errors,'Sales Email*','admin_contact','nn',$admin_contact)}}
        {{textbox2($errors,'Address*','address','nn',$address)}}
        {{textbox2($errors,'Contact Map*','contact_map','nn',$contact_map)}}
       </div>
    </div>
    
    
     </div>
    </div>
  </div>
</div>
	 
 <div class="accordion" id="accordionSettings-2about">
  <div class="card">
    <div class="card-header" id="headingSettings1about">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-2about" aria-expanded="true" aria-controls="collapseSettings-2about">
          <h3>About Us</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-2about" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-2about">
      <div class="card-body">


    <div class="row">
       <div class="col-sm-12">
         {{textarea2($errors,'Homepage About Us*','homepage_about_us',$homepage_about_us)}}
         {{textarea2($errors,'Footer About Us*','footer_about_us',$footer_about_us)}}
         {{textarea2($errors,'Banner About Us*','banner_about_us',$banner_about_us)}}
         {{textarea2($errors,'About Us*','about_us',$about_us)}}
          
       </div>
    </div>
    
    
     </div>
    </div>
  </div>
</div>
   
     

<div class="accordion" id="accordionSettings-payment">
  <div class="card">
    <div class="card-header" id="headingSettings1Payment">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-payment" aria-expanded="true" aria-controls="collapseSettings-payment">
          <h3>Payment Details</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-payment" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-payment">
      <div class="card-body">


    <div class="row">
       <div class="col-sm-6">
         {{textbox2($errors,'Secret Key*','razer_pay_secret_key','nn',$razer_pay_secret_key)}}
          
       
       </div>
       <div class="col-sm-6">
         {{textbox2($errors,'RZP Live Key*','rzp_live','nn',$rzp_live)}}
       
       </div>
    </div>
    
    
     </div>
    </div>
  </div>
</div>
   
    
    <div class="accordion" id="accordionSettings-3">
  <div class="card">
    <div class="card-header" id="headingSettings1-3">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-3" aria-expanded="true" aria-controls="collapseSettings-3">
          <h3>Social Media Details</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-3" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-3">
      <div class="card-body">
    
    <div class="row">
         
       <div class="col-sm-6">
         {{textbox2($errors,'Facebook Url*','facebook_url','nn',$facebook_url)}}
         {{textbox2($errors,'Twitter Url*','twitter_url','nn',$twitter_url)}}
         {{textbox2($errors,'Instagram Url*','instagram_url','nn',$instagram_url)}}
        
       </div>
        <div class="col-sm-6">
             {{textbox2($errors,'Youtube Url*','youtube_url','nn',$youtube_url)}}
          {{textbox2($errors,'Play Store Url*','play_store_url','nn',$play_store_url)}}
          {{textbox2($errors,'App Store Url*','app_store_url','nn',$app_store_url)}}
        </div>
    </div>
    
    
    </div>
    </div>
  </div>
  </div>
    
    
     <div class="accordion" id="accordionSettings-4">
    <div class="card">
    <div class="card-header" id="headingSettings4">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSettings-4" aria-expanded="true" aria-controls="collapseSettings-4">
          <h3>SEO Details</h3>
        </button>
      </h2>
    </div>

    <div id="collapseSettings-4" class="collapse " aria-labelledby="headingOne" data-parent="#accordionSettings-4">
      <div class="card-body">
    
    <div class="row">
       <div class="col-sm-6">
            {{textbox2($errors,'Image Alt*','image_alt','nn',$image_alt)}}
            {{textbox2($errors,'Achor Tags*','achor_tags','nn',$achor_tags)}}
        </div>
        <div class="col-sm-6">
            {{textbox2($errors,'Meta Description*','meta_description','nn',$meta_description)}}
            {{textbox2($errors,'Meta Keywords*','meta_keywords','nn',$meta_keywords)}}
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


function readURL33(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { 
            $(input.data('imgID')).attr('src',e.target.result);
            $(input.data('imgID')).hide();
            $(input.data('imgID')).fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("body").on('change','.uploadImages',function() {
    readURL33(this);
});



$("body").on('click','.removePicture',function(e){
   e.preventDefault();
   $this = $(this);
   $("body").find($this.data('target')).val('');
   $("body").find($this.data('action')).attr('src',"<?= url('upload.jpg') ?>");
   $this.hide();
});
</script>
@endsection
 
 

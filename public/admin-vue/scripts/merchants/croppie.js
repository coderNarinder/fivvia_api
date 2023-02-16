


$image_crop = $("body").find('#upload-image').croppie({
  enableExif: true,
  viewport: {
    // width: 400,
    // height:533,   
    width: 500,
    height:666,
    type: 'square'
  },
  boundary: {
    width: 600,
    height:800
  }
});
$("body").on('change','#images', function () { 
  var $uploadimage = $("body").find('#upload-image'); 
      $uploadimage.show();
  var reader = new FileReader();
  reader.onload = function (e) {
       //Initiate the JavaScript Image object.
        var image = new Image();
       //Set the Base64 string return from FileReader as source.
        image.src = e.target.result;

        //Validate the File Height and Width.
        image.onload = function () {
          var height = this.height;
          var width = this.width;
          if (width < 1100) {
            alert("Minimum width must be 1100px.");
            return false;
          }else{
              $("body").find('.cropped_image').show();
              $("body").find('.btn-form-submit').hide();
               $image_crop.croppie('bind', {
                 url: e.target.result
               }).then(function(){
                 console.log('jQuery bind complete');
               });
            
          }
        };
   }
  reader.readAsDataURL(this.files[0]);
});



 



$("body").on('click','.cropped_image',function(ev){
      $image_crop.croppie('result',{
        type: 'canvas',
        size: 'viewport'
      }).then(function (response) {
            $("body").find("#file_name").val(response);
            $("body").find('.cropped_image').hide();
            $("body").find('.btn-form-submit').show();
      });
});




//Checking Image Extension while uploading profile image

var _validFileExtensions = [".jpg", ".jpeg", ".gif", ".png"];
var _validFileExtension = [".mp4", ".3gp"];



function removeSelectedFile(oInput, img_id) {
  $(oInput).parent().find('img').attr('src',$(oInput).data('img'));
  $(oInput).parent().find('input').val('');
  $(oInput).css('display', 'none');
}
function ValidateSingleInput(oInput, img_id, img_count = 0, limit = 0) {
// if(limit >0 && img_count >= limit){
//   swal({
//     text: "You can upload up to "+limit+" images. Remove one to upload",
//     timer: 3000
//     });
//     return false;
// }
  // $(oInput).parent().find('label').css('display', 'none');
  // $("label[for='" + oInput.id + "']").css('display', 'none');
  if (oInput.type == "file") {
   var sFileName = oInput.value;
   if (sFileName.length > 0) {
      if (Math.round(oInput.files[0].size / (1024 * 1024)) > 10) { // make it in MB so divide by 1024*1024
        alert('Please select image size less than 10 MB');
        oInput.value = "";
        // document.getElementById(img_id).style.display = "none";
        return false;
       }

       var blnValid = false;

       for (var j = 0; j < _validFileExtensions.length; j++) 

       {

         var sCurExtension = _validFileExtensions[j];
         if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) 
         {
           blnValid = true;
           this.readURL(oInput, img_id);
           break;
         }

       }



       if (!blnValid) {
         alert("Sorry!! Allowed image extensions are .jpg, .jpeg, .gif, .png");
         oInput.value = "";
         $(`#${img_id}`).css('display', 'none');
         $(`#${img_id}`).siblings('button').css('display', 'none');

         return false;

       }

     }

   }

return true;

}



function readURL(input, img_id) {

  if (input.files && input.files[0]) {

    var reader = new FileReader();

    reader.onload = function (e) {

        $(`#${img_id}`).css('display', 'block');

				$(`#${img_id}`).attr('src', e.target.result);
        $(`#${img_id}`).siblings('button').css('display', 'block');

    }

    reader.readAsDataURL(input.files[0]);

  }

}












function ValidateSingleInput2(oInput, img_id) {

  $(oInput).parent().find('label').css('display', 'none');



  $("label[for='" + oInput.id + "']").css('display', 'none');



   if (oInput.type == "file") {

     var sFileName = oInput.value;



     if (sFileName.length > 0) {

      if (Math.round(oInput.files[0].size / (1024 * 1024)) > 2) { // make it in MB so divide by 1024*1024

        alert('Please select image size less than 2 MB');

        oInput.value = "";

        document.getElementById(img_id).style.display = "none";

        return false;

     }

       var blnValid = false;

       for (var j = 0; j < _validFileExtension.length; j++) 

       {

         var sCurExtension = _validFileExtension[j];

         // if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) 

         // {

         //   blnValid = true;

         //   this.readURL(oInput, img_id);

         //   break;

         // }

       }



       // if (!blnValid) {

       //   alert("Sorry!! Allowed image extensions are .jpg, .jpeg, .gif, .png");

       //   oInput.value = "";

       //   $(`#${img_id}`).css('display', 'none');

       //   return false;

       // }

     }

   }

return true;

}


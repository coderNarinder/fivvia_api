 @if(Session::has('flash_message'))

 <div class="row">

       <div class="col-md-12">

            



             <div class="alert alert-success" role="alert">

              <?=  Session::get('flash_message') ?>

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                <span aria-hidden="true">&times;</span>

              </button>

            </div>



         </div>

  </div>

 @endif

 
  @if(Session::has('error_flash_message'))

  <div class="row">

         <div class="col-md-12">

            <div class="alert alert-warning" role="alert">

              <?=  Session::get('error_flash_message') ?>

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                <span aria-hidden="true">&times;</span>

              </button>

            </div>

         </div>

  </div>

 @endif





  @if(Session::has('messages'))
     <div class="row">
          <div class="col-md-12">
               <div class="alert alert-warning " role="alert">
                  <?=  Session::get('messages') ?>

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                <span aria-hidden="true">&times;</span>

              </button>

            </div>

         </div>

  </div>

 @endif








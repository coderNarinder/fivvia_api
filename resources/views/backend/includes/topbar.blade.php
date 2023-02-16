 <div class="main-header">
          <a href="javascript:void(0);" class="custom-toggle shadow-icon" title="Click here to expand"><span class="material-icons">menu</span></a>
          <nav>
            <!--<ul class="l-s-none d-flex flex-wrap">-->
             
            <!--  <li><a href="javascript:void(0);">Dashboard</a></li>-->
            <!--  <li><a href="javascript:void(0);">About</a></li>-->
            <!--  <li><a href="javascript:void(0);">Help</a></li>-->
            <!--</ul>-->
            <form action="" ><input type="text" id="searchbox" name="OrderID" class="form-control" placeholder="search any order with OrderID" required="" /></form>
            
          </nav>
          <ul class="l-s-none d-flex flex-wrap ml-auto nav-right">
		  <li><a href="javascript:void(0);" class="shadow-icon change-theme-mode" title="Switch Dark/Light Mode"><span class="material-icons d-mode">dark_mode</span><span class="material-icons l-mode">light_mode</span></a></li>
		   <li><a href="{{url('/')}}" target="_blank" class="shadow-icon" title="click here to visit website"><span class="material-icons" >language</span></a></li>
            <li class="icon-settings"><a href="javascript:void(0);" class="shadow-icon" title="click here to add settings"><span class="material-icons">settings</span></a></li>
            <li><a href="#" class="shadow-icon" title="click here to view contact enquiries"><span class="material-icons">contact_mail</span></a></li>
            <li><a href=" " class="shadow-icon" title="merchant chats"><span class="material-icons">chat</span></a></li>
            <li><a href="{{ route('client.logout') }}" class="shadow-icon" title="click here to logout"><span class="material-icons">logout</span></a></li>
          </ul>
</div>
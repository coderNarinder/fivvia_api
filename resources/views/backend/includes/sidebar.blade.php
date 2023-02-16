 <aside class="sidebar">
        <div class="logo">Client Panel</div>
        <div class="user">
          <!-- <span><img src="{{url('admin-vue/images/profile.jpg')}}" alt=""></span> -->
          <a href="javascript:void(0);">{{Auth::user()->name}}</a>
        </div>
        <ul class="l-s-none d-flex flex-wrap ">
         <li class="active"><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Dashboard</a></li>
         <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Vendors</a></li>
         <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Banners</a></li>

        <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Configuration</a></li>

        <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Payment Methods</a></li>

        <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>My Profile</a></li>

        <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Vendor Management</a></li>
        <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Vendor Management</a></li>

        <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Vendor Management</a></li>

        <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Vendor Management</a></li>

        <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Vendor Management</a></li>

        <li><a href="{{route('client.vendor.list')}}"><span class="material-icons sidebar-icon">category</span>Vendor Management</a></li>
        </ul>
</aside>
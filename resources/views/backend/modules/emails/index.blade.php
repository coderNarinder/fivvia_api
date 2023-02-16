@extends('admin.layouts.layoutNew')
@section('stylesheets') 
@endsection
@section('content')
        <div class="m-c-heading">
            <h2>Email Management</h2>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Email Management</li>
              </ol>
            </nav>
            <a href="{{route('admin.email.create')}}" class="main-button m-b-rounded" title="click here to add email templates">
              <span class="material-icons">add</span>
            </a>
          </div>

          <div class="wrapper-shadow-inset">
             <div class="table-wrapper">
              <table class="dTable" id="example2" data-action="<?= route('admin.email.records.ajax') ?>">
                <thead>
                <tr>
                  <th>Email Title </th> 
                 
                  <th width="120">Action</th>
                </tr>
                  
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
     </div>
        

 
@endsection      
@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/email.js')}}"></script>
@endsection
 
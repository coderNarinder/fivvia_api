@extends('backend.layouts.layoutNew')
@section('stylesheets') 
@endsection
@section('content')


        <div class="m-c-heading">
            <h2>Vendor Management</h2>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{route('client.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Vendor Management</li>
              </ol>
            </nav>
            <a href="{{route('client.vendor.create')}}" class="main-button m-b-rounded" title="click to add vendor">
              <span class="material-icons">add</span>
            </a>
          </div>

          <div class="wrapper-shadow-inset">
             <div class="table-wrapper">
              <table class="dTable" id="example2" data-action="<?= route('client.vendor.records.ajax') ?>">
                <thead>
                  <tr>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th width="200px">Action</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>
        

 
@endsection      
@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/vendor.js')}}"></script>
 
@endsection
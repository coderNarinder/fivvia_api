@extends('admin.layouts.layoutNew')
@section('stylesheets') 
@endsection
@section('content')
        <div class="m-c-heading">
            <h2>Location Management</h2>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Location Management</li>
              </ol>
            </nav>
            <a href="{{route('admin.location.create')}}" class="main-button m-b-rounded" title="add locations">
              <span class="material-icons">add</span>
            </a>
          </div>

          <div class="wrapper-shadow-inset">
             <div class="table-wrapper">
              <table class="dTable" id="example2" data-action="<?= route('admin.location.records.ajax') ?>">
                <thead>
                <tr>
                  <th>Parent</th>
                  <th>Child</th>
                  <th>Pin Code</th>
                  <th>Status</th>
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
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/location.js')}}"></script>
@endsection






 
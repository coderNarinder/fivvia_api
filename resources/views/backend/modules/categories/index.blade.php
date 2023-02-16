@extends('admin.layouts.layoutNew')
@section('stylesheets') 
@endsection
@section('content')


        <div class="m-c-heading">
            <h2>Category Management</h2>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Category Management</li>
              </ol>
            </nav>
            <a href="{{route('admin.category.create')}}" class="main-button m-b-rounded" title="click to add category">
              <span class="material-icons">add</span>
            </a>
          </div>

          <div class="wrapper-shadow-inset">
             <div class="table-wrapper">
              <table class="dTable" id="example2" data-action="<?= route('admin.category.records.ajax') ?>">
                <thead>
                  <tr>
                    <th>Parent</th>
                    <th>Child</th>
                    <th>Featured/Un-featured</th>
                    <th>Status</th>
                    <th>menuActive</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>
        

 
@endsection      
@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/category.js')}}"></script>
 
@endsection
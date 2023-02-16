@extends('backend.layouts.layoutNew')
@section('content')
 
          <div class="m-c-heading">
            <h2>Dashboard</h2>
            <!--<nav aria-label="breadcrumb">-->
            <!--  <ol class="breadcrumb">-->
            <!--    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>-->
            <!--  </ol>-->
            <!--</nav>-->
          </div>
          <div class="row">
            <div class="col-sm-3">
              <div class="dashboard-stats">
                <h4>Total SKU (in stock)</h4>
                <div class="d-s-details"><span class="d-s-count"></span></div>
                
              </div>
            </div>
            <div class="col-sm-3">
              <div class="dashboard-stats">
                <h4>Total Orders(All)</h4>
                <div class="d-s-details"><span class="d-s-count"></span></div>
                
              </div>
            </div>
            <div class="col-sm-3">
              <div class="dashboard-stats">
                <h4>Total Sales (in INR)</h4>
                <div class="d-s-details"><span class="d-s-count"> </span></div>
                
              </div>
            </div>
            <div class="col-sm-3">
              <div class="dashboard-stats">
                <h4>Total Sales (in units)</h4>
                <div class="d-s-details"><span class="d-s-count"></span></div>
                
              </div>
            </div>
         
          </div>
          

         
@endsection
@extends('admin.layouts.layoutNew')
@section('stylesheets') 
@endsection
@section('content')
        <div class="m-c-heading">
            <h2>User Listing</h2>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Listing</li>
              </ol>
            </nav>
            <a href="{{route('admin.users.create')}}" class="main-button m-b-rounded" title="click to add user">
              <span class="material-icons">add</span>
            </a>
          </div>

          <div class="wrapper-shadow-inset">
             <div class="table-wrapper">
               <!-- /.card-header -->
			   <div class="m-l-advance-search">
                  <button class="btn btn-primary" 
                          type="button" 
                          data-toggle="collapse" 
                          data-target="#collapseExample" 
                          aria-expanded="false" 
                          aria-controls="collapseExample">
                     Advance serach
					 <span class="material-icons s-i-angle">
                  expand_more
                  </span>
                  </button>
				  
        
              <div class="collapse" id="collapseExample">
                <div class="card card-body">
                   <div class="row">
                      <div class="col-3">
                          <div class="form-group">
                              <label>Region</label>
                              <select name="country" class="form-control" id="country">
                                 <option value="">Choose</option>
                                 @foreach($locations as $l)
                                    <option value="{{$l->id}}">{{$l->label}}</option>
                                 @endforeach
                              </select>
                          </div>
                      </div>
                       <div class="col-3">
                          <div class="form-group">
                              <label>State</label>
                               <select name="state" class="form-control" id="state">
                                 <option value="">Choose</option>
                               </select>
                          </div>
                      </div>
                       <div class="col-3">
                          <div class="form-group">
                              <label>City</label>
                              <select name="city[]" class="form-control" id="city" multiple="">
                                 <option value="">Choose</option>
                               </select>
                          </div>
                      </div>
                      
                       <div class="col-3">
                        <button class="btn btn-primary" id="advanceSearch">Search</button>
                       </div>
                   </div>
                </div>
              </div>
			  </div>
              <table class="dTable table-user-listing" id="example2" data-action="<?= route('admin.users.ajax') ?>">
                <thead>
                <tr>
                  <th>Picture</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Joining Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                  
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
     </div>
        

 

<input type="hidden" id="locationUrl" value="{{ route('admin.merchant.getLocations') }}"> 
@endsection      
@section('scripts')
<script type="text/javascript" src="{{url('/admin-vue/scripts/admin/users.js')}}"></script>
@endsection

 
@extends('layouts.app')

@section('title') Edit User @endsection

@section('content')
<!-- Breadcrumbs-->
<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="#">Users</a>
  </li>
  <li class="breadcrumb-item active">Edit User</li>
</ol>
<div class="row">
  <div class="col-md-6 m-auto">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-user"></i> Enter User Details
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('update.old.subadmin' , $seller->id) }}">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Name</label>
            <input class="form-control" type="text" placeholder="Enter Name" name="name" value="{{ $seller->name }}">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input class="form-control" type="email" placeholder="Enter Email" name="email" value="{{ $seller->email }}">
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input class="form-control" type="text" placeholder="Enter Phone Number" name="phone" value="{{ $seller->phone }}">
          </div>
          <div class="form-group">
            <label>Color Code</label>
            <input class="form-control" type="text" placeholder="Enter Color Code" name="seller_color" value="{{ $seller->seller_color }}">
          </div>
          <div id="has_pin">
              @if($seller->user_of != null)
              <div class="form-group">
                <label>Pin Code</label>
                <input class="form-control" type="number" placeholder="Enter Pin" name="pin" value="{{ $seller->pincode }}">
              </div>
              @endif
          </div>
          <div class="form-group">
            <label>Password</label>
            <input class="form-control" type="password" placeholder="New Password" name="password">
          </div>   
          <!--<div class="form-group">-->
          <!--  <label>Itself Order Taker? <span style="opacity: 0.5; font-style: italic;">(Will have own customer)</span></label>-->
          <!--  <input type="checkbox" name="itself_order_taker">-->
          <!--</div>       -->
          <div class="form-group">
              @if($seller->subadmin->product_link != NULL)
            <label>Allow him to link my Products. <span style="opacity: 0.5; font-style: italic;">Branch Already linked check to unlink!</span></label>
            <input type="checkbox" name="product_link" checked/>
            @else
             <label>Allow him to link my Products. <span style="opacity: 0.5; font-style: italic;">Murge Branch</span></label>
             <input type="checkbox" name="product_link">
          
            @endif
            
          </div>  
          <div class="form-group">
            <label>Enter Shortlisted Product Ids</label>
             <input class="form-control" type="text" placeholder="Enter ids on Transfer Products" name="assign_products" value="{{ $seller->subadmin->assign_products }}">
            </div> 
            <div class="form-group">
            <label>Enter Final Transfer Product Ids</label>
             <input class="form-control" type="text" placeholder="Enter ids on Transfer Products" name="final_allowed_products" value="{{ $seller->subadmin->final_allowed_products }}">
            </div> 
       
          <button class="btn btn-primary btn-block">Update User</button>
        </form>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
<script type="text/javascript">
  $('#u-type').on('change' , function(){
    if($(this).val() == 2){
      $('#has_pin').html('<div class="form-group">\
            <label>Pin Code</label>\
            <input class="form-control" type="number" placeholder="Enter Pin" name="pin">\
          </div>');
    }
    else{
      $('#has_pin').empty();
    }
  });
</script>
@endsection
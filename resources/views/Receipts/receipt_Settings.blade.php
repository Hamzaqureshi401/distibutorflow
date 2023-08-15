@extends('layouts.app')

@section('title') Add Product @endsection

@section('content')
<!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Receipt Settings</h5>
            <p class="text-muted m-b-10 text-center">Set Your Receipt Settings</p>
         </div>
      </div>
<div class="card">
         <div class="card-header">
            <!--  <h5>Hover table</h5>
               <span>use class <code>table-hover</code> inside table element</span> -->
            <div class="card-header-right">
               <ul class="list-unstyled card-option">
                  <li><i class="fa fa-chevron-left"></i></li>
                  <li><i class="fa fa-window-maximize full-card"></i></li>
                  <li><i class="fa fa-minus minimize-card"></i></li>
                  <li><i class="fa fa-refresh reload-card"></i></li>
                  <li><i class="fa fa-times close-card"></i></li>
               </ul>
            </div>
         </div>
<div class="row card-block table-border-style">
  <div class="col-md-6 m-auto">
    <div class="card mb-3">
      <div class="card-header text-center">
        Enter Receipt Details
      </div>
      <div class="card-body">
        <form id="myForm" action="{{ route('store.Receipt') }}" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}

           @if (count($errors) > 0)
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
           <div class="form-group">
             <label style="font-size: 13px">Header Image <span style="color: red">*
             jpeg,png,jpg,pdf</span>
             </label>
             <input type="file"  name="headerImg" class="form-control">
          </div>
          <div class="form-group">
            <label>Company Name <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" type="text" placeholder="Enter Name" value="{{ $receipt->company_name ?? ''}}" name="company_name" required="">
          </div>
          <div class="form-group">
            <label>Address</label>
            <input type="text" class="form-control" placeholder="Enter address" value="{{ $receipt->address ?? ''}}" name="address">
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="number" class="form-control" placeholder="Enter phone" value="{{ $receipt->phone ?? ''}}" name="phone">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" placeholder="Enter email" value="{{ $receipt->email ?? ''}}" name="email">
          </div>
           <div class="form-group">
            <label>Website</label>
            <input type="text" class="form-control" placeholder="Enter website" value="{{ $receipt->website ?? ''}}" name="website">
          </div>
          <div class="form-group">
             <label style="font-size: 13px">Footer Image <span style="color: red">*
             jpeg,png,jpg,pdf</span>
             </label>
             <input type="file"  name="footerImg" class="form-control">
          </div>

          <span style="color: red;"> Note: if same image is repeat then upload image one by one!</span>
          
          <button id="button" type="submit" class="btn btn-primary btn-block">Set Receipt</button>
          
        </form>
      </div>
    </div>
  </div>


    <div class="col-md-6 m-auto">
    <div class="card mb-3">
      <div class="card-header text-center">
       Receipt Example
      </div>
      <div class="card-body">
         <img class="img-fluid" src="{{ asset($receipt->headerImg ?? '') }}" alt="Order Header Image" style="display: block; /* This ensures the image is centered horizontally */
        margin-left: auto;
        margin-right: auto;" max-width="500px" max-height="200px"/>

         <table class="table table-bordered table-custom-th" width="100%" cellspacing="0">
    
                  <p class="text-center" align="1">{{ $receipt->company_name ?? 'Company_name' }}</p>
                  <p class="text-center" align="1">{{ $receipt->address ?? 'Address' }}</p>
                  <p class="text-center" align="1">{{ $receipt->email ?? 'Email' }}</p>
                  <p class="text-center" align="1">{{ $receipt->phone ?? 'Phone' }}</p>
                  <p class="text-center" align="1">{{ $receipt->website ?? 'website' }}</p>
                  <p>Customer Name: Customer</p>
                  <a href="tel:{{ '1234567890' }}"><p>Phone   : {{ '1234567890' }}</p></a>
                  
                  
                  
                  <p>Address: {{ 'Customer Address' }}</p>
                  <p>Date: {{ "Order Date" }} / Bill No: {{ 01 }}</p>
                  <p>Order Taker: {{ "OrderTaker Name" }}</p>
                  
                  <p class="text-center" align="1">
                  <p class="text-center">*******************************</p>
                  
            
          
          <tr><td><p class="text-center" align="1">         --------------</p></td></tr>
          <tr><td class="text-center" align="1">{{ "Product Name"}}</td></tr>
                <tr><td><p class="text-center" align="1">         --------------</p></td></tr>
        
        
              <tr>
                  
            <td><br>{{ "Price" }} X |{{ "Unit" }}| = {{ "Amount" }} <br></td>
            
        </tr>

      
  
  </table>
            <p class="text-center">*******************************</p>
    <table>

           <p class="text-center">----------------------------</p>
                      <p size ="3">Total    : {{ 123 }}</p>
                      <p class="text-center">----------------------------</p>
                      
                  <p>Subtotal: {{ 123 ?? 0}}</p></td>
                  <img class="img-fluid" src="{{ asset($receipt->footerImg ?? '') }}" alt="Order Header Image" style="display: block; /* This ensures the image is centered horizontally */
        margin-left: auto;
        margin-right: auto;" max-width="500px" max-height="200px"/>
              <p class="text-center" align="1">--------Thank You--------</p>
          
         
      </div>
    </div>
  </div>
</div>
</div>
  
@endsection
@push('scripts')
<script>


    </script>
    @endpush
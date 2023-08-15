@extends('layouts.app')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
   .chosen-single{
   height: 40px !important;
   line-height: 36px !important;
   }
   .chosen-container-single .chosen-single div{
   top: 9px !important;
   }
   .create-invoice-section{
   display: none;
   }
</style>
@endpush
@section('title') Create Sale @endsection
@section('content')
<!-- Breadcrumbs-->
<div class="page-header card">
   <div class="card-block">
      <h5 class="m-b-10 text-center m-auto">Create Sale</h5>
      <p class="text-muted m-b-10 text-center">Sold Product On Point</p>
      <p class="text-muted m-b-10 text-center" id="sellery">Total sellary = </p>
      <ul class="breadcrumb-title b-t-default p-t-10">
         <li class="breadcrumb-item">
            <div class="d-none in">
               <button class="btn btn-sm btn-primary  check-in">Check in</button>
            </div>
            <div class="d-none out">
               <button class="btn btn-sm btn-danger  check-out">Check Out</button>
            </div>
         </li>
      </ul>
      <div>
         <a style="color: white; margin-left: 5px;" href="{{ route('Get.Attendence.Record' , Auth::id()) }}" class="btn btn-sm btn-primary pull-right">
         <i class="fa fa-edit"></i>
         Get Attandence
         </a>
         <a style="color: white;" href="{{ route('get.Employee.Sellary' , Auth::id()) }}" class="btn btn-sm btn-primary pull-right">
         <i class="fa fa-edit"></i>
         Get Sellary
         </a>
      </div>
      <p id="demo"></p>
      <div class="d-none">
         <video id="video" width=400 height=400 id="video" controls autoplay></video>
         <p>
            Screenshots : 
         <p>
            <canvas  id="myCanvas" width="400" height="350"></canvas>
      </div>
      <form method="post" id="img-form" multiparts="">
         <meta name="csrf-token" content="{{ csrf_token() }}">
         {{ csrf_field() }}
      </form>
   </div>
</div>
<div class="row">
   <div class="col-md-10 m-auto">
      <div class="card mb-3">
         <div class="card-header text-center">
            <i class="fa fa-user"></i> Enter Sale Details
         </div>
         <div class="card-body">
            <form method="post" action="{{ route('Store.Pos.Sale') }}" id="invoice-form" onsubmit='disableButton()'>
               {{ csrf_field() }}
               <div class="form-group">
                  @if(Auth::user()->role ==4 )
                  <select class="form-control sl" name="customer_id" id="customer-id"  data-placeholder="Choose a customer...">
                     <option value="">Select a customer</option>
                     <option selected id ="vlv" value="{{ Auth::user()->customer->id }}-{{ Auth::user()->customer->user->name }}-{{ Auth::user()->customer->address }}-{{ sizeof(Auth::user()->customer->invoices) }}-{{ Auth::user()->customer->phone }}-http://maps.google.com/maps?q=+{{ Auth::user()->customer->location_url }}-{{ Auth::user()->customer->visit_clear }}-{{ Auth::user()->customer->location_url }}">{{ Auth::user()->customer->user->name }}</option>
                     @else
                  <select class="form-control sl" name="customer_id" id="customer-id"  data-placeholder="Choose a customer...">
                     <option value="">Select a customer</option>
                     <option selected id ="vlv" value="{{ $users->customer->id }}-{{ $users->customer->user->name }}-{{ $users->customer->address }}-{{ sizeof($users->customer->invoices) }}-{{ $users->customer->phone }}-http://maps.google.com/maps?q=+{{ $users->customer->location_url }}-{{ $users->customer->visit_clear }}-{{ $users->customer->location_url }}">{{ $users->customer->user->name }}</option>
                     @endif
                  </select>
               </div>
               <div class="form-group c-selected" style="display: none">
                  <div class="row cus-details form-group">
                     <div class=" col-md-7 cus-details caddress">
                        <h6>Address: <a href="" target="_blank"><b></b></a></h6>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <div class="form-group pull-center">
                        <input type="text" class="search form-control" placeholder="Search product ...">
                     </div>
                     <table class="table table-bordered table-custom-th table table-hover table-bordered results" width="100%" cellspacing="0">
                        <thead>
                           <tr>
                              <th>Name</th>
                              <th>Avl Stock</th>
                              <th>Price</th>
                              <th>Sold Units</th>
                              <th>Inco Units</th>
                              <th>Units</th>
                              <th>Remaining Units</th>
                              <th>Amount</th>
                           </tr>
                        </thead>
                        <tbody id="custom-p-check">
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="row create-invoice-section" id="create-section">
                  <div class="col-md-12">
                     <label style="color: red;">Add Comments <span style="opacity: 0.5; font-style: italic; color: red;"></span></label>
                     <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments" maxlength = "200"></textarea>
                  </div>
                  <input type="hidden" name="creator_id" value="{{ Auth::id() }}">
                  <div class="form-group col-md-12">
                     <label>Total Amount</label>
                     <input class="form-control total-amount" type="number" placeholder="Total Amount" disabled="" value="0">
                  </div>
                  <div class="col-md-12 hide">
                     <button type="submit"  class="btn btn-primary btn-block hid">Create Order</button>
                     <!--<button onclick='run();' class="btn btn-primary btn-block">run</button>-->
                  </div>
               </div>
               <div class="modal fade" id="od-popup" tabindex="-1" role="dialog" aria-labelledby="od-popup" aria-hidden="true">
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('assets/js/invoiceJS.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/NoSleep.min.js') }}"></script>
<script type="text/javascript">
   var cdetails = $('#vlv').val().split('-');
    let str = document.getElementById("customer-id").value;
        var usId = str.split("-");
           
      if($('#vlv').val() != ''){
        $.get('{{ route("Get.Pos.Ajax.Prices") }}/' + cdetails[0] , function(data){
          $('#custom-p-check').html(data);
          $('.cname b').text(cdetails[1]);
          $('.caddress a').attr('href' , cdetails[5]);
          $('.caddress b').text(cdetails[2]);
          $('.cphone b').text(cdetails[4]);
          $('.invoice_no b').text("#" + cdetails[3]);
          $('.prev-record').attr("href" ,  "{{ route('customer.invoices') }}/" + cdetails[0]);
          $('.c-selected').fadeIn('slow');
          $('.t-amount').val(parseInt($('#old_balance').val()));
           $('.old_inv b').text($('#old_inv').val());
           
          if(parseInt($('#old_balance').val()) < 0)
          {
              $('.cadv b').text($('#old_balance').val());
          }
          else
          {
              $('.cbalance b').text($('#old_balance').val());
          }
          $('.cbalance b').text($('#old_balance').val());
          $('.prev-record').fadeIn();
          $('.invoice_no').fadeIn();
   
           $(document).ready(function(){      
            $('.p-units, .r-units').keyup(function(){
             var unit = "";
             var p_id = $(this).data('id');
               const old_units = $('#old-sold-'+p_id).val();
             const available = $('#available-'+p_id).val();
             const incoming = $('#incoming-'+p_id).val();
             const remain = $('#hidden-remain-'+p_id).val();
             const calculation = remain - $(this).val();
             
             if("form-control p-units" == $(this).attr('class')){
               $('#remain-'+p_id).val(calculation);
               unit = $(this).val();
             }else{
               $('#sold-'+p_id).val(calculation);
               unit = calculation;
             }
   
             var grt_price = $('#price-'+p_id).val();
             var amt = unit * grt_price;
             $('#rmty-'+p_id).attr('value', amt);
              var sum = 0;
             $('.sum-amount').each(function(){
                 sum += +$(this).val();
             });
             $('.total-amount').val(sum);
   
             if(sum != 0)
              {
                $('#create-section').removeClass('create-invoice-section');
              }else{
                $('#create-section').addClass('create-invoice-section');
              }
             
           });
          });
        });
      }
      else{
        $('.c-selected').fadeOut('slow');
        $('.prev-record').fadeOut();
          $('.invoice_no').fadeOut();
      }
     $("#customer-id").select2({
            placeholder: "Select a Name",
            allowClear: true,
            theme: "classic"
        });
        $(".js-example-basic-multiple").select2();
   
        $('.hid').click(function(){
            $('.hide').addClass('d-none');
        });
   
   
   
   
    
</script>
@include('pos.image_handeling')
@endpush
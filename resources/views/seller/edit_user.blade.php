@extends('layouts.app')
@section('title') Edit User @endsection
@section('content')
@push('styles')
<style>
   .nav-item .nav-link.active {
   background-color: #3b4650;
   color: white;
   }
</style>
@endpush
<!-- Breadcrumbs-->
<div class="page-header card">
   <div class="card-block">
      <h5 class="m-b-10 text-center">Edit Seller</h5>
      <p class="text-muted m-b-10 text-center">Update Seller Data</p>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="userSettingsTabs" role="tablist">
               <li class="nav-item">
                  <a class="nav-link active" id="userDetailsTab" data-toggle="tab" href="#userDetails" role="tab" aria-controls="userDetails" aria-selected="true"><i class="fa fa-user"></i>  Emp Personal Details</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="employeeSettingsTab" data-toggle="tab" href="#employeeSettings" role="tab" aria-controls="employeeSettings" aria-selected="false"><i class="ti-settings"></i> Set Employee Settings</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="employeeAttendenceSettingsTab" data-toggle="tab" href="#employeeAttendenceSettings" role="tab" aria-controls="employeeAttendenceSettings" aria-selected="false"><i class="ti-timer"></i> Set Employee Attendence Settings</a>
               </li>
            </ul>
         </div>
         <div class="card-body">
            <form method="post" id="myForm" action="{{ route('update.old.seller' , $seller->id) }}">
               {{ csrf_field() }}
               <div class="tab-content" id="userSettingsTabsContent">
                  <div class="tab-pane fade show active" id="userDetails" role="tabpanel" aria-labelledby="userDetailsTab">
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
                        <input class="form-control" type="number" placeholder="Enter Phone Number" name="phone" value="{{ $seller->phone }}">
                     </div>
                     <div class="form-group">
                        <label>Enter Profit for Per Delivered Order</label>
                        <input class="form-control" type="number" placeholder="Enter Phone Number" name="delivered_order_profit" value="{{ $seller_data->delivered_order_profit }}">
                     </div>
                     <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" type="password" placeholder="New Password" name="password" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');" />
                     </div>
                     <!-- Rest of the "Enter User Details" fields -->
                  </div>
                  <div class="tab-pane fade" id="employeeSettings" role="tabpanel" aria-labelledby="employeeSettingsTab">
                     <div class="form-group">
                        <label>Allow him to Assign Order</label>
                        <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $seller->id }}" name="assign-order" value="{{ $chk_assign_order_status }}"  id="yourBox" class="js-switch3 js-switch" {{ $chk_assign_order_status == 1 ? 'checked' : '' }}>
                        <label>{{ $chk_assign_order_status }} If it is on you only can give product profit and (Fixed) Delivered Profit
                        & deleviry profit will be NULL!</label>
                     </div>
                     <div class="form-group">
                        <label>Allow Him Delivered Order Per Product Profit</label>
                        <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $seller->id }}" name="assign-order" value="{{ $seller_data->deleviry_product_profit }}"  id="yourBox" class="deleviry_product_profit js-switch" {{ $seller_data->deleviry_product_profit == 1 ? 'checked' : '' }}>
                        <label>{{ $seller->deleviry_product_profit ?? "j" }} If it is on you only can give product profit and delivered profit & process profit will be NULL!</label>
                     </div>
                     <div class="form-group">
                        <label>Allow him to Add/Remove Stock</label>
                        <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $seller->id }}" name="assign-order" value="{{ $chk_add_remove_stock }}"  id="chk_add_remove_stock" class="js-switch4 js-switch" {{ $chk_add_remove_stock != 0 ? 'checked' : '' }}>
                        <label>If it is on user can Add remove stock!</label>
                     </div>
                     <div class="table-responsive thisdiv">
                        <div class="form-group">
                           <label>Enter Profit for Per Processed Order / functionlity is not defiened may be required in future</label>
                           <input class="form-control" type="number" placeholder="Enter Phone Number" name="process_order_profit" value="{{ $seller_data->process_order_profit }}">
                        </div>
                        <table class="table table-bordered table-custom-th table table-hover table-bordered results table1 table-datatable"  width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>Seller Name</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach($seller_name as $key => $seller)
                              <tr>
                                 <td class="name">{{ $seller->name }}</td>
                                 <td>
                                    <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" class="js-switch" name="checked_seller_id[]" value="{{ $seller->id }}"@if(in_array($seller->id, $allowed_seller)) checked @endif name="checked_seller_id[]">
                                 </td>
                              </tr>
                              @endforeach
                           </tbody>
                        </table>
                        <h3 class="mid">Processed Order Product Benefit</h3>
                        <div class="table-responsive">
                           <table class="table table-bordered table-custom-th table table-hover table-bordered results table2 table-datatable"  width="100%" cellspacing="0">
                              <thead>
                                 <tr>
                                    <th>Product Name</th>
                                    <th>Order Taker Benefit</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php $key = 0; ?>
                                 @if(count($custom_prices) > 0)
                                 @foreach($custom_prices as $productRow)
                                 <tr>
                                    <input type="hidden" class="form-control p-id" name="product_id[]" value="{{ $productRow->product->id ?? 'Something went wrong' }}">
                                    <td class="name">{{ $productRow->product->name ?? 'Something went wrong' }}</td>
                                    <td>
                                       <input type="text" class="form-control p-ot" name="seller_benefit[]" value="{{ $productRow->profit }}">
                                    </td>
                                    <td>
                                       <input type="hidden" value="off" class="js-switch" name="checked_products[{{ $key }}]">
                                       <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" class="js-switch {{ $productRow->id ?? 'Something went wrong' }}" @if(in_array($productRow->product->id ?? 'Something went wrong', $custom_prices->pluck('product_id')->toArray())) checked @endif name="checked_products[{{ $key }}]">
                                    </td>
                                 </tr>
                                 <?php $key++; ?>
                                 @endforeach
                                 @endif
                                 <?php $key = $key; ?>
                                 @foreach($get_product as $row)
                                 <tr>
                                    <input type="hidden" class="form-control p-id" name="product_id[]" value="{{ $row->id }}">
                                    <td class="name">{{ $row->name }}</td>
                                    <td>
                                       <input type="text" class="form-control p-ot" name="seller_benefit[]" value="{{ $row->seller_benefit }}">
                                    </td>
                                    <td>
                                       <input type="hidden" value="off"  class="js-switch" name="checked_products[{{ $key }}]">
                                       <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" class="js-switch {{ $row->id }}" @if(in_array($row->id, $custom_prices->pluck('product_id')->toArray())) checked @endif name="checked_products[{{ $key }}]">
                                    </td>
                                 </tr>
                                 <?php $key++; ?>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                        <h3 class="mid">Allow Branches To Handle Cash</h3>
                        <div class="table-responsive">
                           <table class="table table-bordered table-custom-th table table-hover table-bordered table3 results table-datatable"  width="100%" cellspacing="0">
                              <thead>
                                 <tr>
                                    <th>Customer Name</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($branches as $branch)
                                 <tr>
                                    <td class="name">{{ $branch->user->name ?? 'Something went wrong' }}</td>
                                    <td>
                                       <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" data-id='{{ $branch->id }}' class="c-allow" @if(in_array($branch->id, $allowed_branches)) checked @endif">
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="employeeAttendenceSettings" role="tabpanel" aria-labelledby="employeeAttendenceSettingsTab">
                     @include('employeeAttandence.edit_employee_attandence_settings')
                  </div>
                  <button class="btn btn-primary btn-block" id="updateButton">Update User</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
   // var a = @json($chk_assign_order_status);
   // var b = @json($seller->deleviry_product_profit);
   // if (a == null || b == 0){
   //     $('.thisdiv').addClass('d-none');         
   // }
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
     $(".selectAll").on('click',function(){
       $(".option").prop('checked',true);
       var customer=$('.customers .c-id');
       customer.each(function(key,value){
           $(this).attr('name', 'customer_id[]');
       });
     });
   
      $(".unselectAll").on('click',function(){
       $(".option").prop('checked',false);
       var customer=$('.customers .c-id');
       customer.each(function(key,value){
           $(this).attr('name', '');
       });
     });
   
     $('.option').on('change',function(){
       var customer = $(this).parent().parent().find("input[type='hidden']");
       if($(this).is(':checked')) {
         customer.attr('name','customer_id[]');
       } else { 
         customer.attr('name','');
       }
     });
     // Product Search
   
   $(document).ready(function() {
     $(".search").keyup(function () {
       var searchTerm = $(".search").val();
       var listItem = $('.results tbody').children('tr');
       var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
       
     $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
           return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
       }
     });
       
     $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
       $(this).attr('visible','false');
     });
   
     $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
       $(this).attr('visible','true');
     });
   
     var jobCount = $('.results tbody tr[visible="true"]').length;
       $('.counter').text(jobCount + ' item');
   
     if(jobCount == '0') {$('.no-result').show();}
       else {$('.no-result').hide();}
        });
   });
    
   $(document).ready(function(){
       $(document).on('change', '.js-switch3', function () {
           let assign_order = $(this).prop('checked') === true ? 1 : 0;
            if (assign_order == 0){
                   $('.thisdiv').addClass('d-none'); 
               }
               else {
                   $('.thisdiv').removeClass('d-none'); 
               }
           let userId = $(this).data('id');
           $.ajax({
               type: "GET",
               dataType: "json",
               url: '{{ route('update.assign_order') }}',
               data: {'user_id': userId , 'assign_order' : assign_order },
               success: function (data) {
               toastr.success(data.message);
              
               }
           });
       });
   });
   
   $(document).ready(function(){
       $(document).on('change', '.deleviry_product_profit', function () {
           let assign_order = $(this).prop('checked') === true ? 1 : 0;
            if (assign_order == 0){
                   $('.thisdiv').addClass('d-none'); 
               }
               else {
                   $('.thisdiv').removeClass('d-none'); 
               }
           let userId = $(this).data('id');
           $.ajax({
               type: "GET",
               dataType: "json",
               url: '{{ route('update.deleviry_product_profit') }}',
               data: {'user_id': userId , 'deleviry_product_profit' : assign_order },
               success: function (data) {
               toastr.success(data.message);
              
               }
           });
       });
   });
   
   $(document).ready(function(){
       $(document).on('change', '.js-switch4', function () {
           let chk_add_remove_stock = $(this).prop('checked') === true ? 1 : 0;
            if (chk_add_remove_stock == 0){
                   $('.thisdiv').addClass('d-none'); 
               }
               else {
                   $('.thisdiv').removeClass('d-none'); 
               }
           let userId = $(this).data('id');
           $.ajax({
               type: "GET",
               dataType: "json",
               url: '{{ route('chk.add.remove.stock') }}',
               data: {'user_id': userId , 'chk_add_remove_stock' : chk_add_remove_stock },
               success: function (data) {
               toastr.success(data.message);
              
               }
           });
       });
   });
   
   document.getElementById('yourBox').onchange = function() {
       document.getElementById('yourBox').disabled = !this.checked;
   };
   //var btn = document.getElementById("yourBox").value;
   //console.log(btn);
   //if (btn == 0){
   //    document.getElementById('yourBox').disabled = true;
   //}
   //else {
   //    document.getElementById('yourBox').disabled = false;
   //}
   
   $('.c-allow').on('change' , function(){
      var c_id = $(this).data('id');
      var userId = @json($seller_data->seller_id);
      var checked = $(this).is(':checked');
      if (checked == true){
       checked = 1;
      }else{
       checked = 0;
      }
      console.log(userId);
        $.ajax({
               type: "GET",
               dataType: "json",
               url: '{{ route('assign.Customer') }}',
               data: {'user_id': userId , 'c_id' : c_id , 'checked' : checked},
               success: function (data) {
               toastr.success(data.message);
              
               }
           });
     });
   
    $(document).ready(function() {
      $('#userSettingsTabs a').on('click', function(e) {
         e.preventDefault();
         $(this).tab('show');
      });
   });
   
      $('#updateButton').click(function() {
        // Select all checkboxes in the DataTable
        $('.table1').DataTable().page.len(-1).draw();
        $('.table2').DataTable().page.len(-1).draw();
        $('.table3').DataTable().page.len(-1).draw();
        
        // Submit the form
        $('#myForm').submit();
    });
     
</script>
@endpush
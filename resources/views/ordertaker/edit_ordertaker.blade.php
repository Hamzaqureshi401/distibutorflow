@extends('layouts.app')
@push('styles')
<style>
   .results tr[visible='false'],
   .no-result{
   display:none;
   }
   .results tr[visible='true']{
   display:table-row;
   }
   .check-all{
   background-color: #59e0c5;
   }
   .nav-item .nav-link.active {
    background-color: #3b4650;
    color: white;
}

</style>
@endpush
@section('title') Edit Order Taker @endsection
@section('content')
<!-- Breadcrumbs-->
<div class="page-header card">
   <div class="card-block">
      <h5 class="m-b-10 text-center">Edit Ordr Taker</h5>
      <p class="text-muted m-b-10 text-center">Update Order Taker Data</p>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-header">
         <ul class="nav nav-tabs card-header-tabs" id="userSettingsTabs" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" id="userDetailsTab" data-toggle="tab" href="#userDetails" role="tab" aria-controls="userDetails" aria-selected="true"><i class="fa fa-user"></i> Emp Personal Details</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" id="employeeSettingsTab" data-toggle="tab" href="#employeeSettings" role="tab" aria-controls="employeeSettings" aria-selected="false"><i class="ti-settings"></i> Set Employee Settings</a>
            </li>
           <!--  <li class="nav-item">
               <a class="nav-link" id="employeeAttendenceSettingsTab" data-toggle="tab" href="#employeeAttendenceSettings" role="tab" aria-controls="employeeAttendenceSettings" aria-selected="false"><i class="ti-timer"></i> Set Employee Attendence Settings</a>
            </li> -->
         </ul>
      </div>
         <div class="card-body">
            <form method="post" id='myForm' action="{{ route('update.ot' , $ot->id) }}" enctype="multipart/form-data">
               {{ csrf_field() }}
                <div class="tab-content" id="userSettingsTabsContent">
               <div class="tab-pane fade show active" id="userDetails" role="tabpanel" aria-labelledby="userDetailsTab">
           
               <div class="section1">
                  <div class="card-header text-center">
                     <i class="fa fa-user"></i><b> Enter Order Taker Details</b> 
                  </div>
                  <div class="form-group">
                     <label>Name</label>
                     <input class="form-control" type="text" placeholder="Enter Name" name="name" value="{{ $ot->name }}">
                  </div>
                  <div class="form-group">
                     <label>Email</label>
                     <input class="form-control" type="email" placeholder="Enter Email" name="email" value="{{ $ot->email }}">
                  </div>
                  <div class="form-group">
                     <label>Phone</label>
                     <input class="form-control" type="text" placeholder="Enter Phone Number" name="phone" value="{{ $ot->phone }}">
                  </div>
                  <div class="form-group">
                     <label>Password</label>
                     <input class="form-control" type="password" placeholder="New Password" name="password">
                  </div>
                  <div class="form-group">
                     <label>Enter Discription to show on order.</label>
                     <input class="form-control" type="text" placeholder="Detail will be printed on order" name="order_detail" max="200" value="{{ $ot->order_detail }}">
                  </div>
                  <div class="form-group">
                     <label>Order Taker Home Cordinates</label>
                     <input class="form-control" type="text" placeholder="Enter URL ( Google Location )" name="ot_hom_location" value="{{ $ot->ot_hom_location }}">
                  </div>
               </div>
             </div>
               <!--  Section 1 End -->
               <!--  Section 2 start -->
               <div class="tab-pane fade" id="employeeSettings" role="tabpanel" aria-labelledby="employeeSettingsTab">
               <div class="section2 ">
                  <!-- Add these elements to your HTML form -->
<div class="form-group">
    <label>Enable Per visit Profit</label>
    <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}"
           name="eneble_per_visit_price" value="{{ $ordertaker->eneble_per_visit_price }}" class="eneble_per_visit_price"
           {{ $ordertaker->eneble_per_visit_price == 1 ? 'checked' : '' }}>
</div>

<div class="form-group v-p">
    <div class="form-group">
        <label>Enable Total Area Profit</label>
        <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}"
               name="auto_area_price" value="{{ $ordertaker->auto_area_price }}" class="auto_area_price"
               {{ $ordertaker->auto_area_price == 1 ? 'checked' : '' }}>
    </div>

    <div class="t-p">
        <label>Enter Total Area Profit</label>
        <input class="form-control" type="number" placeholder="Enter Area Profit that will be given on visit clear on full Area"
               name="total_area_profit" value="{{ $ordertaker->total_area_profit }}">
    </div>
    <div class="o-v-p">
        <label>Enter Visit Profit</label>
        <input class="form-control" type="number" placeholder="Enter Fixed Profit that will be given on visit clear"
               name="ot_visit_profit" value="{{ $ot->ot_visit_profit }}">
    </div>
</div>


                 
                  </div>
                  <div class="form-group">
                     <label>Enter New Cstmr Profit</label>
                     <input class="form-control" type="number" placeholder="if user create new cstmer he will be given this profit" name="new_cstmr_prft" value="{{ $ot->scopeordertaker->new_cstmr_prft }}">
                  </div>
                  <div class="form-group">
                     <label>Enter Profit if new cstmr order number equal to this number </label>
                     <input class="form-control" type="number" placeholder="Enter Fixed Profit that will be given once for new cstmr after that order will approved" name="aftr_bil_nw_cst_prft" value="{{ $ot->scopeordertaker->aftr_bil_nw_cst_prft }}">
                  </div>
                  <div class="form-group">
                     <label>Enter distance to Compare with order(In meters)</label>
                     <input class="form-control" type="number" placeholder="This distance will be compared with customer location" name="compare_ot_distance" value="{{ $ot->compare_ot_distance }}">
                  </div>
                  <div class="form-group">
                     <label>Allow Him to Edit Order</label>
                     <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}" name="allow_to_edit_order" value="{{ $ot->allow_to_edit_order }}"  id="allow_to_edit_order" class="allow_to_edit_order js-switch" {{ $ot->scopeordertaker->allow_to_edit_order == 1 ? 'checked' : '' }}>
                  </div>
                  <div class="form-group">
                     <label>Allow Him to Delete Order</label>
                     <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}" name="allow_to_delete_order" value="{{ $ot->allow_to_delete_order }}"  id="allow_to_delete_order" class="allow_to_delete_order js-switch" {{ $ot->scopeordertaker->allow_to_delete_order == 1 ? 'checked' : '' }}>
                  </div>
                  <div class="form-group">
                     <label>Allow Him to Store Area and total Customer</label>
                     <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}" name="store_varae_isit_data" value="{{ $ot->store_varae_isit_data }}"  id="store_varae_isit_data" class="store_varae_isit_data js-switch" {{ $ot->scopeordertaker->store_varae_isit_data == 1 ? 'checked' : '' }}>
                  </div>
                  <div class="form-group">
                     <label>Allow To Create Customer</label>
                     <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}" name="allow_create_customer" value="{{ $ot->allow_create_customer }}"  id="allow_create_customer" class="allow_create_customer js-switch" {{ $ot->scopeordertaker->allow_create_customer == 1 ? 'checked' : '' }}>
                  </div>
                  <div class="form-group">
                     <label>Donot show pending customers in create order</label>
                     <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}" name="do_not_sho_pndng_cst" value="{{ $ot->do_not_sho_pndng_cst }}"  id="do_not_sho_pndng_cst" class="do_not_sho_pndng_cst js-switch" {{ $ot->scopeordertaker->do_not_sho_pndng_cst == 1 ? 'checked' : '' }}>
                  </div>
                  <div class="form-group">
                     <label>Show pending customers Only in create order</label>
                     <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}" name="pndng_only" value="{{ $ot->pndng_only }}"  id="pndng_only" class="pndng_only js-switch" {{ $ot->scopeordertaker->pndng_only == 1 ? 'checked' : '' }}>
                  </div>
                  <h3 class="text-center">Custom Order Taker Benefit</h3>
                  <div class="form-group">
                     <label class="text-center">Enter bill to alow maximum profit for product eg u entr 3 and cstmr nt vstd 3 tim aftr he wil be gvn blo fld prft</label>
                     <input class="form-control" type="number" placeholder="Enter Bill" name="bill_cutting_no" value="{{ $ot->bill_cutting_no }}">
                  </div>
                  <div class="form-group">
                     <label>Enter Profit if cstmr nt visited </label>
                     <input class="form-control" type="number" placeholder="Enter Fixed Profit that will be given after that invoice" name="ot_fixed_profit" value="{{ $ot->ot_fixed_profit }}">
                  </div>
                  <div class="form-group">
                     <label>Checked means order taker allowed discount by defualt.</label>
                     <!--<input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" id="js-switch" name="customer_itself" @if($ot->customer_itself == 0) checked @endif>-->
                     <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}" name="discount_on_off" value="{{ $ot->discount_on_off }}"  id="yourBox" class="js-switch3 js-switch" {{ $ot->discount_on_off == 1 ? 'checked' : '' }}>
                  </div>
                  <div class="form-group" id ="set-dn">
                     <label>Add Customer Discount In %   (Required)</label>
                     <input class="form-control" type="number" step="any" placeholder="Add Discount (0 is not allowed)" id="yourText" name="customer_discount" value="{{ $ot->customer_discount }}">
                  </div>
                 
                 
                  <div class="form-group">
                     <label>If it is checked it means user can only create given customer order.</label>
                     <!--<input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" id="js-switch" name="customer_itself" @if($ot->customer_itself == 0) checked @endif>-->
                     <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $ot->id }}" name="customer_itself" class="js-switch2 js-switch" {{ $ot->customer_itself == 1 ? 'checked' : '' }}>
                  </div>


                     <hr>
                     <div style="margin-top : 30px;">
                        <h5 class="text-center" > Customer Allow to Specific Area</h5>
                        <a class="btn btn-sm btn-out-dashed btn-round option btn-grd-primary selectAllArea" style="color: white">Check All</a>
                        <a class="btn btn-sm btn-out-dashed btn-round option btn-grd-primary unselectAllArea" style="color: white">Uncheck All</a> 
                     </div>
                     <div class="table-responsive">
                        <table class="table table-bordered table-custom-th table table-hover table-bordered results table-datatable table1" width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>Name</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach($areas as $c)
                              <tr class="customers">
                                
                                 <td>{{ $c->name }}</td>
                                 <td><input type="checkbox" data-toggle="toggle" data-onstyle="success" name="allowed_areas[]" data-size="xs" class="js-switch option checkbox" value="{{ $c->id }}" data-id="{{ $c->id }}"  @if(in_array($c->id, $allowed_areas)) checked @endif class="option">
                                 </td>
                              </tr>
                              @endforeach
                              
                           </tbody>
                        </table>
                     </div>

                  @if ($products)
                  <div class="table-responsive">
                     <div class="form-group pull-center">
                        <input type="text" class="search form-control" placeholder="Search product to Allow or Show...">
                     </div>
                     <!--ordertaker start -->
                     <h3 class="text-center">Allow customers to Ordetaker</h3>
                     <div class="table-responsive">
                        <table class="table table-bordered table-custom-th table table-hover table-bordered results allow-customer-ot table-datatable"  width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>OrderTaker Name</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach($otnames as $key => $ot)
                              <tr>
                                 <td class="name">{{ $ot->name }}</td>
                                 <td>
                                    <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" class="js-switch2" name="checked_ot_id[]" value="{{ $ot->id }}"@if(in_array($ot->id, $allowed_ot)) checked @endif name="checked_ot_id[]">
                                 </td>
                              </tr>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                     <!--ordertaker end -->
                     <h3 class="mid">Custom Order Taker Benefit</h3>
                     <div class="table-responsive">
                        <table class="table table-bordered table-custom-th table table-hover table-bordered results custom-ot-ben-table table-datatable"  width="100%" cellspacing="0">
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
                                 <input type="hidden" class="form-control p-id" name="product_id[]" value="{{ $productRow->product->id }}">
                                 <td class="name">{{ $productRow->product->name }}</td>
                                 <td>
                                    <input type="text" style="width: 50%;" class="form-control p-ot" name="ot_benefit[]" value="{{ $productRow->ot_benefit }}">
                                 </td>
                                 <td>
                                    <input type="hidden" value="off" class="js-switch" name="checked_products[{{ $key }}]">
                                    <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" class="js-switch {{ $productRow->id }}" @if(in_array($productRow->product->id, $custom_prices->pluck('product_id')->toArray())) checked @endif name="checked_products[{{ $key }}]">
                                 </td>
                              </tr>
                              <?php $key++; ?>
                              @endforeach
                              @endif
                              <?php $key = $key; ?>
                              @foreach($products as $row)
                              <tr>
                                 <input type="hidden" class="form-control p-id" name="product_id[]" value="{{ $row->id }}">
                                 <td class="name">{{ $row->name }}</td>
                                 <td>
                                    <input type="text" class="form-control p-ot" style="width: 50%;" name="ot_benefit[]" value="{{ $row->ot_benefit }}">
                                 </td>
                                 <td>
                                    <input type="hidden" value="off"  class="js-switch" name="checked_products[{{ $key }}]">
                                    <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" class="js-switch {{ $row->id }}" @if(in_array($row->id, $custom_prices->pluck('product_id')->toArray())) checked @endif name="checked_products[{{ $key }}]">
                                 </td>
                              </tr>
                              <?php $key++; ?>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                     @endif
                     <hr>
                     <div style="margin-top : 30px;">
                        <h5 class="text-center" > Customer Allow to Order Taker</h5>
                        <a class="btn btn-sm btn-out-dashed btn-round option btn-grd-primary selectAll" style="color: white">Check All</a>
                        <a class="btn btn-sm btn-out-dashed btn-round option btn-grd-primary unselectAll" style="color: white">Uncheck All</a> 
                     </div>
                     <div class="table-responsive">
                        <table class="table table-bordered table-custom-th table table-hover table-bordered results table-datatable customer-table" width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>Name</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach($customers as $c)
                              <tr class="customers">
                                
                                 <td>{{ $c->user->name }}/{{ $c->address }}</td>
                                 <td><input type="checkbox" data-toggle="toggle" data-onstyle="success" name="customer_id[]" data-size="xs" class="js-switch option checkbox" value="{{ $c->id }}" data-id="{{ $c->id }}"  @if(in_array($c->id, $ot_customers)) checked @endif class="option">
                                 </td>
                              </tr>
                              @endforeach
                              <div class="d-none">
                                 <input type="text" style="" name="ids[]" id="fg" value="">
                              </div>
                           </tbody>
                        </table>
                     </div>
                     <!--  <div class="btn-group mid" data-toggle="buttons">
                        <label class="btn btn-primary active">
                         <span>Allow Order Taker to create custom price customer</span>
                         <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="custom" id="" >
                        </label>
                        </div>  -->
                  </div>
               </div>
               
             </div>
           </div>
            <button class="btn btn-primary btn-block" id="updateButton">Update Order Taker</button>
            </form>
          </div>
         </div>
      </div>
   </div>

@endsection
@push('scripts')
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
   
    $(".selectAll").on('click',function(){
     $(".option").prop('checked',true);
     $(".selectAll").removeClass('btn-grd-primary');
     $(".selectAll").addClass('btn-grd-success');
     $(".customers").addClass('check-all');
     $(".checkbox").bootstrapToggle('on');
     $(this).html("All Customer selected!");
      var customer=$('.customers .c-id');
     customer.each(function(key,value){
        var b = $(this).attr('name', 'customer_id[]');
         console.log($('.c-id').val());
     });
   });
   
    $(".unselectAll").on('click',function(){
     $(".option").prop('checked',false);
      $(".selectAll").removeClass('btn-grd-success');
     $(".selectAll").addClass('btn-grd-primary');
      $(".selectAll").html('Check All'); 
      $(".customers").removeClass('check-all');
   $(".checkbox").bootstrapToggle('off');
     $("#custom-allow").html('');
     var customer=$('.customers .c-id');
     customer.each(function(key,value){
         $(this).attr('name', '');
     });
   });
   var arr = [];
   arr = @json($ot_customers);
   console.log(arr);
   $('#fg').val(arr);
   $('.option').on('change',function(){
      
     var customer = $(this).parent().parent().find("input[type='hidden']");
     var a = $(this).data('id');
     console.log(a);
     if($(this).is(':checked')) {
       customer.attr('name','customer_id[]');    
      checkboxselected(a)
     } else { 
       checkboxunselected(a);
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
   // swith controll
   
   $(document).ready(function(){
     $(document).on('change', '.js-switch2', function () {
         let customer_itself = $(this).prop('checked') === true ? 1 : 0;
         if ($(this).prop('checked') == 1) {
        $(this).closest('tr').addClass('visitclear');
         } else {
            $(this).closest('tr').removeClass('visitclear'); 
         }
         let userId = $(this).data('id');
         $.ajax({
             type: "GET",
             dataType: "json",
             url: '{{ route('update.customer_itself') }}',
             data: {'customer_itself': customer_itself, 'user_id': userId },
             success: function (data) {
             //console.log(data.message);
             toastr.success(data.message);
             }
         });
     });
   });
   $(document).ready(function(){
     $(document).on('change', '.js-switch3', function () {
         let discount_on_off = $(this).prop('checked') === true ? 1 : 0;
         if ($(this).prop('checked') == 1) {
        $(this).closest('tr').addClass('visitclear');
         } else {
            $(this).closest('tr').removeClass('visitclear'); 
         }
         let userId = $(this).data('id');
         $.ajax({
             type: "GET",
             dataType: "json",
             url: '{{ route('update.discount_on_off') }}',
             data: {'discount_on_off': discount_on_off, 'user_id': userId },
             success: function (data) {
             //console.log(data.message);
             toastr.success(data.message);
             }
         });
     });
   });
   $(document).ready(function(){
     $(document).on('change', '.allow_create_customer', function () {
         let allow_create_customer = $(this).prop('checked') === true ? 1 : 0;
         
         let userId = $(this).data('id');
         $.ajax({
             type: "GET",
             dataType: "json",
             url: '{{ route('allow.create.customer') }}',
             data: {'allow_create_customer': allow_create_customer, 'user_id': userId },
             success: function (data) {
             toastr.success(data.message);
             }
         });
     });
   });
   $(document).ready(function(){
     $(document).on('change', '.allow_to_edit_order', function () {
         let allow_to_edit_order = $(this).prop('checked') === true ? 1 : 0;
         
         let userId = $(this).data('id');
         $.ajax({
             type: "GET",
             dataType: "json",
             url: '{{ route('allow.to.edit.order') }}',
             data: {'allow_to_edit_order': allow_to_edit_order, 'user_id': userId },
             success: function (data) {
             toastr.success(data.message);
             }
         });
     });
   });
   $(document).ready(function(){
     $(document).on('change', '.allow_to_delete_order', function () {
         let allow_to_delete_order = $(this).prop('checked') === true ? 1 : 0;
         
         let userId = $(this).data('id');
         $.ajax({
             type: "GET",
             dataType: "json",
             url: '{{ route('allow.to.delete.order') }}',
             data: {'allow_to_delete_order': allow_to_delete_order, 'user_id': userId },
             success: function (data) {
             toastr.success(data.message);
             }
         });
     });
   });
   $(document).ready(function(){
     $(document).on('change', '.store_varae_isit_data', function () {
         let store_varae_isit_data = $(this).prop('checked') === true ? 1 : 0;
         
         let userId = $(this).data('id');
         $.ajax({
             type: "GET",
             dataType: "json",
             url: '{{ route('store.varae.isit.data') }}',
             data: {'store_varae_isit_data': store_varae_isit_data, 'user_id': userId },
             success: function (data) {
             toastr.success(data.message);
             }
         });
     });
   });
   $(document).ready(function(){
     $(document).on('change', '.do_not_sho_pndng_cst', function () {
         let do_not_sho_pndng_cst = $(this).prop('checked') === true ? 1 : 0;
         
         let userId = $(this).data('id');
         $.ajax({
             type: "GET",
             dataType: "json",
             url: '{{ route('donot.sho.pndng.cst') }}',
             data: {'do_not_sho_pndng_cst': do_not_sho_pndng_cst, 'user_id': userId },
             success: function (data) {
             toastr.success(data.message);
             }
         });
     });
   });
   $(document).ready(function(){
     $(document).on('change', '.pndng_only', function () {
         let pndng_only = $(this).prop('checked') === true ? 1 : 0;
         
         let userId = $(this).data('id');
         $.ajax({
             type: "GET",
             dataType: "json",
             url: '{{ route('pndng.only') }}',
             data: {'pndng_only': pndng_only, 'user_id': userId },
             success: function (data) {
             toastr.success(data.message);
             }
         });
     });
   });
   document.getElementById('yourBox').onchange = function() {
     document.getElementById('yourText').disabled = !this.checked;
    
     $('#set-dn').removeClass('d-none'); 
   };
   var btn = document.getElementById("yourBox").value;
   
   
   if (btn == 0){
     document.getElementById('yourText').disabled = true;
     $('#set-dn').addClass('d-none'); 
   }
   else {
     document.getElementById('yourText').disabled = false;
     $('#set-dn').removeClass('d-none'); 
   }
   
     var arr = [];
     console.log('1w');
   function checkboxselected(a){
   
    if(arr != ''){
        arr = arr +","+ a;
       }else{
         arr = a;
       }
       $('#select_box_val').val(arr);
   }
   function checkboxunselected(a){
   
    arr = (JSON.parse("[" + arr + "]")).filter(f => f !== a);
        //arr = JSON.parse("[" + arr + "]");
        $('#select_box_val').val(arr);
   }
   $('.nav-link').on('click', function() {
   $('.nav-item').removeClass('active');
   });

$(document).ready(function() {
    var vpcheck = @json($ordertaker->eneble_per_visit_price);
    var aapcheck = @json($ordertaker->auto_area_price);

    function handleToggles() {
        if (vpcheck == 1) {
            $('.v-p').show();
            if (aapcheck == 1) {
                $('.t-p').show();
                $('.o-v-p').hide();
            } else {
                $('.t-p').hide();
                $('.o-v-p').show();
            }
        } else {
            $('.v-p').hide();
            $('.t-p').hide();
            $('.o-v-p').hide();
        }
    }

    handleToggles();

    $(document).on('change', '.eneble_per_visit_price', function() {
        let eneble_per_visit_price = $(this).prop('checked') === true ? 1 : 0;
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('update.eneble_per_visit_price') }}',
            data: { 'eneble_per_visit_price': eneble_per_visit_price, 'user_id': userId },
            success: function(data) {
                toastr.success(data.message);
                vpcheck = eneble_per_visit_price;
                handleToggles();
            }
        });
    });

    $(document).on('change', '.auto_area_price', function() {
        let auto_area_price = $(this).prop('checked') === true ? 1 : 0;
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('update.auto_area_price') }}',
            data: { 'auto_area_price': auto_area_price, 'user_id': userId },
            success: function(data) {
                toastr.success(data.message);
                aapcheck = auto_area_price;
                handleToggles();
            }
        });
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
        $('.customer-table').DataTable().page.len(-1).draw();
        $('.custom-ot-ben-table').DataTable().page.len(-1).draw();
        $('.allow-customer-ot').DataTable().page.len(-1).draw();
        $('.table1').DataTable().page.len(-1).draw();
        
        // Submit the form
        $('#myForm').submit();
    });
   
</script>
@endpush
@extends('layouts.app')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
   .results tr[visible='false'],
   .no-result{
   display:none;
   }
   .results tr[visible='true']{
   display:table-row;
   }
</style>
@endpush
@section('title') Edit Customer @endsection
@section('content')
<!-- Breadcrumbs-->
<div class="page-header card">
   <div class="card-block">
      <h5 class="m-b-10 text-center">Edit Customer</h5>
      <p class="text-muted m-b-10 text-center">Update Customer Details</p>
      <!-- <ul class="breadcrumb-title b-t-default p-t-10">
         <li class="breadcrumb-item">
            <a href="index.html"> <i class="fa fa-home"></i> </a>
         </li>
         <li class="breadcrumb-item"><a href="#!">All Categories</a>
         </li>
           <li class="breadcrumb-item"><a href="#!">All Categories</a>
            </li> -->
      <!-- </ul> -->
      <!-- <div class="card-header">
         <i class="fa fa-table"></i> Categories List
         <a class="btn pull-right add-category btn-out-dashed btn-round btn-grd-primary" data-toggle="modal" data-target="#category-popup" style="color: white">Add Category</a>
         </div>
          </div> -->
   </div>
</div>
<form method="post" action="{{ route('update.customer' , $customer->id) }}" id="edit-customer" enctype="multipart/form-data" onsubmit='disableButton()'>
   {{ csrf_field() }}
   <div class="container">
      <div class="row">
         <div class="col-sm">
            <div class="card mb-3">
               <div class="card-header">
                  <i class="fa fa-user"></i> Customer Details
               </div>
               <div class="card-body">
                  <div class="form-group">
                     <label>Shop Name</label>
                     <input class="form-control" type="text" placeholder="Enter Name" name="customer_name" value="{{ $customer->customer_name }}">
                  </div>
                  <div class="form-group">
                     <label>Name</label>
                     <input class="form-control" type="text" placeholder="Enter Name" name="name" value="{{ $customer->user->name }}">
                  </div>
                  <!--Area-->
                  <div class="form-group">
                     <label>Area <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
                     {{-- <a href="{{ route('add.area') }}" class="btn pull-right"><i class="fa fa-plus"></i> Add Area</a> --}}
                     <a class="btn btn-info pull-right add-category" data-toggle="modal" data-target="#category-popup" style="color: white">+Add Area</a>
                     <select class="form-control fa-search" name="area" id="area-select">
                        <option value="" disabled>Select Area</option>
                        @foreach($areas as $a)
                        <option value="{{ $a->id }}" @if($a->id == $customer->area_id) selected @endif>{{ $a->name }}</option>
                        @endforeach
                     </select>
                     @if($errors->has('area'))
                     <div class="alert alert-danger">
                        {{ $errors->first('area') }}
                     </div>
                     @endif
                  </div>
                  <!--Phone-->
                  <div class="form-group">
                     <label>Phone</label>
                     <input class="form-control" type="number" placeholder="Enter Phone" name="phone" value="{{ $customer->phone }}">
                     <div  id = "d-n" class="d-none">
                        <a data-id="{{ $customer->user->id }}" class="btn btn-info pull-right profit-guru" id= "profit-guru" name="set_profit_guru" style="color:white;"><i class="fa fa-mail"></i> Enable Profit Guru</a>
                     </div>
                     <div  id = "d-n1" class="d-none">
                        <a class="btn btn-warning pull-right" data-toggle="modal" data-target="#send-pending-modal" style="color:white;">Profit Guru Enabled!(onclik it i,ll disabled!)</a>
                     </div>
                  </div>
                  <!--Address-->
                  <div class="form-group">
                     <label>Address</label>
                     <textarea class="form-control" rows="4" maxlength="30" placeholder="Enter Address" name="address">{{ $customer->address }}</textarea>
                  </div>
                  <div class="form-group">
                     <label>Payment Method <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
                     <select class="form-control" name="payment_method" required="">
                        <option value="" disabled selected>Select Payment Method</option>
                        <option value="Cash On Delivery">Cash On Delivery</option>
                        <option value="Bill to Bill">Bill to Bill</option>
                     </select>
                  </div>
                   <div class="form-group">
                        <label>Location URL</label>
                        <input class="form-control location_url" type="text" placeholder="Enter URL ( Google Location )" name="location_url" value="{{ $customer->location_url }}" onkeypress='validate(event)' />
                        <div>
                        <a class="btn btn-info pull-right live-cords" style="color:white;"><i class="fa fa-mail"></i> Fetch Live Cords</a>
                     </div>
                     </div>
                  
                  <hr>
                  @if(Auth::user()->role < 3 )
                  <a class="btn btn-info center" id="btn" onclick="myFunction()" style="color: white">Show All Field</a>
                  @endif
                  <div id="myDiv" style="display: none;">
                     <div class="form-group">
                        <label>Special Requirement <span style="opacity: 0.5; font-style: italic; color: green;">(Optional)</span></label>
                        <textarea class="form-control" rows="4" placeholder="Enter Requet in 20 words only" name="customer_request" maxlength = "20">{{ $customer->customer_request }}</textarea>
                     </div>
                     <div class="form-group">
                        <label>Suggestion In Email <span style="opacity: 0.5; font-style: italic; color: green;">(Optional)</span></label>
                        <textarea class="form-control" rows="4" placeholder="Enter Requet in 2000 words only" name="suggestion_in_email" maxlength = "2000">{{ $customer->suggestion_in_email }}</textarea>
                     </div>
                     <!--Email-->
                     <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" type="email" placeholder="Enter Email" name="email" value="{{ $customer->user->email }}"@scoops.com>
                     </div>
                     <div class="form-group">
                        <label>If Button is on discount by ordertaker is not allowed.</label>
                        <input type="checkbox"  data-toggle="toggle" data-onstyle="danger" data-size="xs" data-id="{{ $customer->user->id }}" name="discount_on_off" value="{{ $customer->user->discount_on_off }}"  id="yourBox" class="js-switch3 js-switch" {{ $customer->user->discount_on_off == 1 ? 'checked' : '' }}>
                     </div>
                     <div class="form-group">
                        <label>New Password</label>
                        <input class="form-control" type="password" placeholder="Enter New Password" name="password" autocomplete="on">
                     </div>
                     <div class="form-group">
                        <label>Balance Limit</label>
                        <input class="form-control" type="number" placeholder="Enter Balance Limit" name="balance_limit" value="{{ $customer->balance_limit }}">
                     </div>
                     <div class="form-group">
                        <label>CNIC No</label>
                        <input class="form-control" type="number" placeholder="Enter CNIC Number" name="cnic" value="{{ $customer->cnic }}">
                     </div>
                     <div class="form-group">
                        <label>Agreement Image*</label>
                        <img src="{{ asset($customer->image) }}" style="height: 200px;width: 200px;margin: 10px 0px;display: block" />
                        <input class="form-control" type="file" name="image">
                     </div>
                     <div class="form-group">
                        <label>Freezer Model</label>
                        <input class="form-control" type="text" placeholder="Enter Freezer Model" name="freezer_model" value="{{ $customer->freezer_model }}">
                     </div>
                     <div class="form-group">
                        <label>Other Details</label>
                        <input class="form-control" type="text" placeholder="Enter Other Details" name="other" value="{{ $customer->other }}">
                     </div>
                     @php $allowed_products = explode('|', $customer->allowed_products) @endphp
                     @php $final_allowed_products = explode('|', $customer->final_allowed_products) @endphp
                     @foreach($products as $p)
                     <input type="hidden" value="{{ $p->id }}" data-value="{{ $p->id }}" @if(in_array($p->id, $allowed_products)) name="allowed_products[]" @endif />
                     <input type="hidden" value="{{ $p->id }}" data-fvalue="{{ $p->id }}" @if(in_array($p->id, $final_allowed_products)) name="final_allowed_products[]" @endif />
                     @endforeach
                  </div>
               </div>
               <button id="button" class="btn btn-primary btn-block">Edit Customer</button>
            </div>
         </div>
      </div>
   </div>
   <div class="col-sm">
      <div class="card mb-3">
         <div class="card-header">
            <i class="fa fa-table"></i> Custom Price List <small class="pull-right">To Edit Custom Prices , Edit Values And Click Edit Button</small>
            <br>
            @if(!empty($customer->custom_prices))
            <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs"  class="js-switch" name="delete-custom-price"> <label class="btn btn-sm">Remove All Custom Price</label>
            @endif
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <div class="form-group pull-center">
                  <input type="text" class="search form-control" placeholder="Search product to Allow or Show...">
               </div>
               <table class="table table-hover table-datatable results" width="100%" id="dataTable" cellspacing="0">
                  <thead>
                     <tr>
                        <th>Name</th>
                        <th>Price</th>
                        @if(Auth::user()->role < 3 )
                        <th>Purchase Price</th>
                        <th>Sell Price</th>
                        <th>Admin Benefit</th>
                        <th>Customer Benefit</th>
                        @endif
                        <th>Order Ben</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $printed = []; ?>
                     <?php $cat_p = []; ?>
                     @foreach($customer->custom_prices as $c_price)
                     @if(!in_array($c_price->category_id , $cat_p))
                     <tr>
                        <td class="bg-info">{{ $c_price->name }}</td>
                     </tr>
                     <?php $cat_p[] = $c_price->category_id ?>
                     @endif
                     <?php $printed[] = $c_price->product_id; ?>
                     <tr class="added-to-c">
                        <input type="hidden" class="form-control this-id" name="this_id[]" value="{{ $c_price->id }}" required="">
                        <td class="text-center"><b>{{ $c_price->product->name }}</b>
                           <br>
                           <input type="hidden" class="form-control p-id" name="" value="{{ $c_price->product_id }}">
                           <label class="btn btn-sm"><input type="checkbox"  data-toggle="toggle" data-onstyle="warning" data-size="xs"   class="allowed-products js-switch" value="{{ $c_price->product_id }}"  @if(in_array($c_price->product_id, $allowed_products)) checked @endif /> Short list</label>
                           <br>
                           <label class="btn btn-sm"><input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs"  class="final-allowed-products js-switch" value="{{ $c_price->product_id }}"  @if(in_array($c_price->product_id, $final_allowed_products)) checked @endif /> Allow</label>
                           <br>
                           <button class="btn btn-sm btn-primary custom-price-btn added-to-c" type="button"><i class="fa fa-check"> Use These Prices</i></button>
                        </td>
                        <!-- Customer Trade Price -->                  
                        <td >
                           <input type="number" style="width: 70px" step="any" 
                              id="c-price-{{ $c_price->id }}" 
                              data-rowid="{{ $c_price->id }}" 
                              class="form-control p-p c-price" name="c_price[]" value="{{ $c_price->price }}" required="">
                        </td>
                        <!-- Admin Purchase Price -->
                        <td 
                           id="p-price-{{ $c_price->id }}" data-rowid="{{ $c_price->product->p_price }}">
                           {{ $c_price->product->p_price }}
                        </td>
                        <!-- Customer Sell Price -->
                        <td><input type="number" style="width: 70px" step="any" 
                           id="s-price-{{ $c_price->id }}"
                           data-rowid="{{ $c_price->id }}"                        
                           class="form-control p-a-b s-price" name="sell_price[]" value="{{ $c_price->sell_price }}"   /></td>
                        @if(Auth::user()->role < 3 )
                        <!-- Admin Benefit -->   
                        <td><input type="number" style="width: 70px" step="any" class="form-control p-a-b" id="a-ben-{{ $c_price->id }}" name="c_a_benefit[]" value="{{ $c_price->a_benefit }}" required=""></td>
                        <!-- Customer Benifit -->                 
                        <td><input type="number" style="width: 70px" step="any" class="form-control p-c-b" id="c-ben-{{ $c_price->id }}" name="c_c_benefit[]" value="{{ $c_price->c_benefit }}" required=""></td>
                        @endif
                        <td><input type="number" style="width: 70px" step="any" step="any"  class="form-control p-a-b" name="ot_benefit[]" value="{{ $c_price->ot_benefit }}" required=""></td>
                     </tr>
                     @endforeach
                     <?php $cat_p = []; ?>
                     @foreach($products as $pr)
                     @if(!in_array($pr->category_id , $cat_p))
                     <tr>
                        <td class="bg-info">{{ $pr->category->name ?? '--' }}</td>
                     </tr>
                     <?php $cat_p[] = $pr->category_id ?>
                     @endif
                     @if(!in_array($pr->id , $printed))
                     <tr>
                        <input type="hidden" class="form-control p-id" name="product_id[]" value="{{ $pr->id }}">
                        <td class="text-center"><b>{{ $pr->name }}</b>
                           <br>
                           <input type="hidden" class="form-control p-id" name="" value="{{ $pr->id }}">
                           <label class ="btn btn-sm"><input type="checkbox" data-toggle="toggle" data-onstyle="warning" data-size="xs" class="allowed-products js-switch" value="{{ $pr->id }}" @if(in_array($pr->id, $allowed_products)) checked @endif /> Short list</label>
                           <br>
                           <label class="btn btn-sm">
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" class="final-allowed-products js-switch" value="{{ $pr->id }}"  @if(in_array($pr->id, $final_allowed_products)) checked @endif /> Allow</label>
                           <br>
                           @if(Auth::user()->role < 3 )
                           <button class="btn btn-sm btn-primary custom-price-btn" type="button"><i class="fa fa-edit"> Use These Prices</i></button>
                           @endif
                        </td>
                        <td>
                           <input type="number" style="width: 70px" step="any" 
                           id="c-price-{{ $pr->id }}" 
                           data-rowid="{{ $pr->id }}" 
                           class="form-control p-p c-price" name="price[]" value="{{ $pr->price }}"  {{ Auth::user()->role < 3 ? '' : 'readonly' }} />
                        </td>
                        @if(Auth::user()->role < 3 )
                        <td id="p-price-{{ $pr->id }}" data-rowid="{{ $pr->p_price }}">{{ $pr->p_price }}</td>
                        <td><input type="number" style="width: 70px" step="any" 
                           id="s-price-{{ $pr->id }}"
                           data-rowid="{{ $pr->id }}"   
                           style="width: 70px;" class="form-control p-a-b s-price" name="sell_price[]" value="{{ $pr->sell_price }}" ></td>
                        <td><input type="number" style="width: 70px" step="any" id="a-ben-{{ $pr->id }}" class="form-control p-a-b" name="a_benefit[]" value="{{ $pr->a_benefit }}" ></td>
                        <td><input type="number" style="width: 70px" step="any" id="c-ben-{{ $pr->id }}" class="form-control p-c-b" name="c_benefit[]" value="{{ $pr->c_benefit }}" ></td>
                        @endif
                        <td><input type="number" style="width: 70px" step="any" class="form-control p-a-b" step="any" name="ot_benefit[]" value="{{ $pr->ot_benefit }}"  {{ Auth::user()->role < 3 ? '' : 'readonly' }} /></td>
                     </tr>
                     @endif
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   </div>
</form>
<div class="modal fade" id="category-popup" tabindex="-1" role="dialog" aria-labelledby="category-popup" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Add Area</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="post" action="{{ route("save.area.ajax") }}" class="category-form">
            {{ csrf_field() }}
            <div class="form-group">
               <label>Area Name</label>
               <input type="text" name="name" class="form-control old-category" required="">
            </div>
            <div class="form-group">
               <button type="button" class="btn btn-primary btn-block add-area-submit-btn">Add Area</button>
            </div>
            </form>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="send-pending-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header bg-danger">
            <h5 class="modal-title" id="delete-modal-label" style="color: white">Are You Sure ?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">Are you sure You want to Disable profit Guru?</div>
         <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">No</button>
            <a data-toggle="modal" data-target="#send-mail" class="btn btn-danger pull-right" data-dismiss="modal" style="color: white">Yes</a>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="send-mail" tabindex="-1" role="dialog" aria-labelledby="send-mail" aria-hidden="true">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header bg-danger">
            <h5 class="modal-title" id="send-mail-label" style="color: white">Want To send Mail ?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">Do you want to send mail about disable Profit Guru to User?</div>
         <div class="modal-footer">
            <button class="btn btn-default profit-guru" type="button" data-dismiss="modal" data-id="{{ $customer->user->id }}">No</button>
            <a class="btn btn-danger pull-right profit-guru" id ="yes" data-rowid="{{ 'yes' }}" data-id="{{ $customer->user->id }}" style="color: white">Yes</a>
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
   $(document).ready(function(){
     $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
     });
   
     // Save area with ajax
     $(document).on('click', '.add-area-submit-btn', function(e){
       e.preventDefault();
       var btn = $(this);
       if($('.category-form .old-category').val() != ""){
         $.ajax({
           type: 'post',
           data: $('.category-form').serialize(),
           url: $('.category-form').attr('action'),
           success: function(res){
             $("#area-select").html(res);
             $("#category-popup").modal('hide');
           }
         });
       }
       
     });
   
   });
   function disableButton() {
           var btn = document.getElementById('button');
           btn.disabled = true;
           btn.innerText = 'Customer Updating Wait'
   
           var originalText = $("#button").text(),
       i  = 0;
   setInterval(function() {
   
       $("#button").append(".");
       i++;
   
       if(i == 4)
       {
           $("#button").html(originalText);
           i = 0;
       }
   
   }, 500);
       }
   
     $('.custom-price-btn').click(function(){
         var cTR = $(this).closest('tr');
       if(!cTR.hasClass('added-to-c')){
           cTR.addClass('added-to-c');
           $(this).html('<i class="fa fa-check"></i>');
       }
       else{
         $(this).html('<i class="fa fa-edit"></i>');
           cTR.removeClass('added-to-c');
       }
     });
     $('#edit-customer').one('submit' , function(event){
         event.preventDefault();
         $('tbody tr').each(function(){
            if(!$(this).hasClass('added-to-c')){
                $(this).find('input').each(function(){
                   $(this).removeAttr('name');
                });
            }
         });
         $(this).submit();
     });
     
     // Allowed Products
     
        $(".selectAll").on('click',function(){
       $(".option").prop('checked',true);
       var product=$('.products .p-id').clone();
       product.each(function(key,value){
           $('#custom-allow').append(value);
       });
     });
   
      $(".unselectAll").on('click',function(){
       $(".option").prop('checked',false);
       $("#custom-allow").html('');
     });
   
     $('.option').on('change',function(){
       var cTR = $(this).parent().parent().find("input[type='hidden']").clone();
       var product=cTR;
       var id=cTR.val();
   
       if($(this).is(':checked')) {
         product.attr('name','product_id[]');
         $('#custom-allow').append(product);
   
       } else { 
         $('#custom-allow').find('input[type=hidden][value='+id+']').remove();
       }
     });
     
     // Allowed P
     $('.option').on('change',function(){
       var cTR = $(this).parent().parent().find("input[type='hidden']").clone();
       var product=cTR;
       var id=cTR.val();
   
       if($(this).is(':checked')) {
         product.attr('name','product_id[]');
         $('#custom-allow').append(product);
   
       } else { 
         $('#custom-allow').find('input[type=hidden][value='+id+']').remove();
       }
     });
     $('.allowed-products').change(function(){
         var val = $(this).val();
         if($(this).is(':checked')){
             $('#edit-customer [data-value="'+val+'"]').attr('name', 'allowed_products[]');
         }
         else{
             $('#edit-customer [data-value="'+val+'"]').removeAttr('name');
         }
     });
     $('.final-allowed-products').change(function(){
         var val = $(this).val();
         if($(this).is(':checked')){
             $('#edit-customer [data-fvalue="'+val+'"]').attr('name', 'final_allowed_products[]');
         }
         else{
             $('#edit-customer [data-fvalue="'+val+'"]').removeAttr('name');
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
   
   
   // show or hide input field
    $(document).ready(function(){
     $("#btn").click(function(){
       $("#myDiv").toggle();
     });
   });
   
   // area search
    $("#area-select").select2({
               placeholder: "Select Area",
               allowClear: true,
               theme: "classic"
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
   // document.getElementById('yourBox').onchange = function() {
   //     document.getElementById('yourText').disabled = !this.checked;
   // };
   // var btn = document.getElementById("yourBox").value;
   // console.log(btn);
   // if (btn == 0){
   //     document.getElementById('yourText').disabled = true;
   // }
   // else {
   //     document.getElementById('yourText').disabled = false;
   // }
    
       
       $('.profit-guru').click(function(e){
           //$("#send-mail .close").click();
           $('#send-mail').modal('hide');
           $('#d-n1').addClass('d-none');
   
            e.preventDefault();
            e.stopImmediatePropagation();
           let profit_guru = $(this).prop('checked') === true ? 1 : 0;
           let userId = $(this).data('id');
           var status = $(this).data('rowid');
           
           console.log(profit_guru , userId , status);
           let name = "hamze";
           let message = "m1";
           $.ajax({
               type: "GET",
               dataType: "json",
               url: '{{ route('update.profit.guru') }}',
               data: {'set_profit_guru': profit_guru, 'user_id': userId, 'name': name, 'message': message , 'status' : status},
               success: function( data ) {
                   console.log( data );
                    toastr.success(data.message);
                   document.getElementById('profit-guru').style.pointerEvents = 'none';
                   $('#profit-guru').addClass('btn-success'); 
                   var btn = document.getElementById('profit-guru');
                   btn.innerText = 'Profit Guru Enabled Successfully!';
                   
               }
           });
   
           return false;
       });
       var data = @json( $customer->user->email_subscription);
       if (data == 1){
           $('#d-n1').removeClass('d-none');
           
       }
       else{
           var btn = document.getElementById('profit-guru');
                   btn.innerText = 'Enable Profit Guru';
                   //  var a = "<button class='btn btn-danger'>Delete seller report</button>";
                   // document.getElementById("profit-guru").innerHTML = a;
                   $('#d-n').removeClass('d-none');
       }
   
   $('.c-price , .s-price').keyup(function(){
   
       var id = $(this).data('rowid');
       
       var s_price = $('#s-price-'+id).val();
       var c_price = $('#c-price-'+id).val();
       var p_price = $('#p-price-'+id).data('rowid');
       
       console.log(id , s_price , c_price , p_price);
   
       var result = c_price - p_price;
       var p_price = $('#a-ben-'+id).val(result);
       var result = s_price - c_price;
       var c_ben = $('#c-ben-'+id).val(result);
      
     });
    
     function validate(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[0-9,]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}


 $(document).on('click', '.live-cords', function(e){

   getLocation();
   getLocation();
   getLocation();


 });

 function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    alert("Geolocation is not supported by this browser.") ;
  }
}

function showPosition(position) {
  $('.location_url').val(position.coords.latitude + ',' + position.coords.longitude);
}
</script>
@endpush
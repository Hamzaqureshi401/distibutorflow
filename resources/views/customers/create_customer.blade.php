@extends('layouts.app')
@section('title') Add Customer @endsection
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
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
@section('content')
<!-- Breadcrumbs-->
<div class="page-header card">
   <div class="card-block">
      <h5 class="m-b-10 text-center">Add Customer</h5>
      <p class="text-muted m-b-10 text-center">Store Customer and Allow Products</p>
      <!-- <ul class="breadcrumb-title b-t-default p-t-10">
         <li class="breadcrumb-item">
            <a href="index.html"> <i class="fa fa-home"></i> </a>
         </li>
         <li class="breadcrumb-item"><a href="#!">All Categories</a>
         </li>
          <li class="breadcrumb-item"><a href="#!">All Categories</a>
            </li>
         </ul>
         <div class="card-header">
         <i class="fa fa-table"></i> Categories List
         <a class="btn pull-right add-category btn-out-dashed btn-round btn-grd-primary" data-toggle="modal" data-target="#category-popup" style="color: white">Add Category</a> -->
      <!--  </div> -->
   </div>
</div>
<div class="row">
   <div class="col-md-6">
      <div class="card mb-3">
         <div class="card-header">
            <i class="fa fa-user"></i> Enter Customer Details
         </div>
         <div class="card-body">
            <form id="customer-form">
               {{ csrf_field() }}
               <div class="form-group">
                  <label>Shop Name <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
                  <input class="form-control" maxlength="30" type="text" placeholder="Enter Shop Name" name="shop_name" required="" id="message">
               </div>
               <div class="form-group">
                  <label>Customer Name <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
                  <input class="form-control" maxlength="30" type="text" placeholder="Enter Name" name="customer_name" required="" id="customer-name">
               </div>
               <div class="form-group d-none">
                  <label>Password <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
                  <input class="form-control" type="password" placeholder="Enter Password" name="password" required="">
               </div>
               <hr>
               <div class="form-group">
                  <label>Phone <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
                  <input class="form-control" type="number" placeholder="Enter Phone" name="phone" required="">
               </div>
               <div class="form-group d-none">
                  <label>Location Cordinates <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
                  <div id="map-layer"></div>
                  <input autocomplete="off" class="form-control" type="text"  maxlength="19" placeholder="Enter Cordinates Only" name="location_url">
               </div>
               <div class="form-group">
                  <label>Address <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
                  <textarea class="form-control" rows="4"maxlength="30" placeholder="Enter Address" name="address" required=""></textarea>
               </div>
               <div class="form-group">
                  <label>Area <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
                  <div>
                     <a onclick='getLocationn();' style="color:white;" class="btn btn-sm btn-info area-btn area-find">Find Nearest Area</a>
                     <div><b style="color: green;" class="success-data"></b></div>
                     <!--<input class="form-control" type="hidden" placeholder="Find Area On click" name="findarea"  id="area-find" disabled = "">-->
                     <input autocomplete="off" class="form-control" type="hidden" id="area-find" maxlength="19" placeholder="Enter Cordinates Only" name="findarea">
                     <textarea class="form-control" rows="5" placeholder="Find Area On click" name="area-name"  readonly=""></textarea>
                  </div>
                  <div class="remove-d-n d-none">
                     <a class="btn btn-info pull-right add-category" data-toggle="modal" data-target="#category-popup" style="color: white">+Add Area</a>
                     <br>
                     <select class="fa fa-search form-control " name="area"  id="area-select" >
                        <option value="" disabled selected>Select Area</option>
                        @foreach($areas as $a)
                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                     </select>
                     @if($errors->has('area'))
                     <div class="alert alert-danger">
                        {{ $errors->first('area') }}
                     </div>
                     @endif
                  </div>
               </div>
               <div id="myDiv" style="display: none;">
                  <div class="form-group">
                     <label>Payment Method <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
                     <select class="form-control" name="payment_method" required="" >
                        <option value="Cash On Delivery" selected>Cash On Delivery</option>
                        <option value="Bill to Bill">Bill to Bill</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Special Requirement <span style="opacity: 0.5; font-style: italic; color: green;">(Optional)</span></label>
                     <textarea class="form-control" rows="4" placeholder="Enter Requet in 20 words only" name="customer_request" maxlength = "20"></textarea>
                  </div>

                   <div class="form-group">
                     <label>Create Defualt Order <span style="opacity: 0.5; font-style: italic; color: green;">(Optional)</span></label>
                      <input  
                      data-toggle="toggle" 
                      data-onstyle="warning" 
                      data-size="xs" 
                      type="checkbox"
                      name="defualtOrder" 
                      checked/>
                  </div>

                 



                  <div class="form-group table table-hover table-bordered results" >
                     <label>CNIC No <span style="opacity: 0.5; font-style: italic; color: green;">(Optional)</span></label>
                     <input class="form-control" type="text" id="cnic" maxlength="15" placeholder="Enter CNIC Number" name="cnic">
                  </div>
                  <div class="form-group">
                  <label>Custom Cords <span style="opacity: 0.5; font-style: italic; color: red;">(Optional)</span></label>
                  
                 <input autocomplete="off" class="form-control customCords" type="text"  maxlength="19" placeholder="Enter Cordinates Only" name="customCords">

               </div>
               
                  <div class="form-group">
                     <label>Balance Limit <span style="opacity: 0.5; font-style: italic; color: green;">(Optional)</span></label>
                     <input class="form-control" type="text" placeholder="Enter Balance Limit" name="balance_limit">
                  </div>
                  <div class="form-group">
                     <label>Freezer Model <span style="opacity: 0.5; font-style: italic; color: green;">(Optional)</span></label>
                     <input class="form-control" type="text" placeholder="Enter Freezer Model" name="freezer_model">
                  </div>
                  <div class="form-group">
                     <label>Other Details <span style="opacity: 0.5; font-style: italic; color: green;">(Optional)</span></label>
                     <input class="form-control" type="text" placeholder="Enter Other Details" name="other">
                  </div>
                  <div class="form-group">
                     <label>Agreement Image* <span style="opacity: 0.5; font-style: italic; color: green;">(Optional)</span></label>
                     <input class="form-control" type="file" name="image">
                     @if($errors->has('image'))
                     <div class="alert alert-danger">
                        {{ $errors->first('image') }}
                     </div>
                     @endif
                  </div>
               </div>
               <div id="custom-prices" style="display: none;"></div>
               @foreach($products as $p)
               <input type="hidden" value="{{ $p->id }}" data-value="{{ $p->id }}"  {!! ($p->allow_status == 1) ? "name='allowed_products[]'": null !!} />
               <input type="hidden" value="{{ $p->id }}" data-fvalue="{{ $p->id }}" {!! ($p->show_status == 1) ? "name='final_allowed_products[]'": null !!} />
               @endforeach
               <a class="btn btn-info center btn-block" id="btn" onclick="myFunction()" style="color: white">Show All Field</a>
               <button id="button" type="button" onclick='getLocation();' class="btn btn-primary btn-block">Create Customer</button>
            </form>
         </div>
         {{ csrf_field() }}
      </div>
   </div>
   @if ($products)
   <div class="col-md-6">
      <div class="card mb-3">
         <div class="card-header">
            <i class="fa fa-table"></i> Product List <small class="pull-right">For Custom Prices , Edit Values And Click Edit Button</small>
         </div>
         <!--Custom Order Ben-->
         <!--For search functionality-->
         <!--<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">-->
         <div class="card-body">
            <div class="table-responsive">
               <div class="form-group pull-center">
                  <input type="text" class="search form-control" placeholder="Search product to Allow or Show...">
               </div>
               <span class="counter pull-right"></span>
               <table class="table table-bordered table-custom-th table table-hover table-bordered results" width="100%" cellspacing="0">
                  <thead>
                     <tr>
                        <th>Name</th>
                        @if(Auth::user()->role < 3 )
                        <th style="width: 200px;">Trade Price</th>
                        <th style="width: 20px;">P.Price</th>
                        <th style="width: 200px;">Sell Price</th>
                        <th>Customer Benefit</th>
                        <th>Admin Benefit</th>
                        @endif
                        <th>Order Benefit</th>
                        <th>Custom Price / Allow / Short List</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $cat_p = []; ?>
                     @foreach($products as $p)
                     @if(!in_array($p->category_id , $cat_p))
                     <tr>
                        <td class="bg-info">{{ $p->category->name }}</td>
                     </tr>
                     <?php $cat_p[] = $p->category_id ?>
                     @endif
                     <tr>
                        <input type="hidden" class="form-control p-id" name="product_id[]" value="{{ $p->id }}">
                        <td>{{ $p->name }}</td>
                        @if(Auth::user()->role < 3 && Auth::user()->role < 5)
                        <!-- Customer Trade Price -->
                        <td>
                           <input style="width: 100px;" type="number"
                              id="c-price-{{ $p->id }}" 
                              data-rowid="{{ $p->id }}" 
                              class="form-control p-p c-price" name="price[]" value="{{ $p->price }}" onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp'>
                        </td>
                        <!-- Admin Purchase Price -->
                        <td
                           id="p-price-{{ $p->id }}" data-rowid="{{ $p->p_price }}">
                           {{ $p->p_price }}
                        </td>
                        <!-- Customer Sell Price -->
                        <td><input type="number"
                           id="s-price-{{ $p->id }}"
                           data-rowid="{{ $p->id }}" 
                           class="form-control p-c-b s-price" name="sell_price[]" value="{{ $p->sell_price }}" onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp'></td>
                        <!-- Customer Benifit -->
                        <td><input type="number" class="form-control p-c-b" name="c_benefit[]"
                           id="c-ben-{{ $p->id }}"
                           value="{{ $p->c_benefit }}" onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp'></td>
                        <!-- Admin Benefit -->   
                        <td><input type="number" class="form-control p-c-b" name="a_benefit[]" 
                           id="a-ben-{{ $p->id }}"
                           value="{{ $p->a_benefit }}" onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp'></td>
                        <td><input type="number" class="form-control p-a-b" name="ot_benefit[]" value="{{ $p->ot_benefit }}" onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp'></td>
                        @endif
                        @if(Auth::user()->role == 5)
                        <td><input type="number" readonly class="form-control p-a-b" name="a_benefit[]" value="{{ $p->ot_benefit }}" onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp'></td>
                        @endif
                        @if(Auth::user()->role < 3 )
                        <td><button class ="btn btn-sm btn-primary custom-price-btn"><i class="fa fa-edit"></i>Use these values</button>
                           <label class ="btn btn-sm center"><input type="hidden" class="form-control p-id" name="" value="{{ $p->id }}"></label>
                           <label class ="btn btn-sm"><input id = "js-switch" data-toggle="toggle" data-onstyle="warning" data-size="xs" type="checkbox" class="allowed-products" value="{{ $p->id }}" {!! ($p->allow_status == 1) ? 'checked="true"': '' !!} /> Short</label>
                           <label class="btn btn-sm"><input id = "js-switch" data-toggle="toggle" data-onstyle="success" data-size="xs" type="checkbox" class="final-allowed-products" value="{{ $p->id }}" {!! ($p->show_status == 1)? 'checked="true"': '' !!} /> Allow</label>
                        </td>
                        @endif
                        @if(Auth::user()->role == 5 )
                        <td>
                           <label class ="btn btn-sm center"><input type="hidden" class="form-control p-id" name="" value="{{ $p->id }}"></label>
                           <label class ="btn btn-sm"><input id = "js-switch" data-toggle="toggle" data-onstyle="warning" data-size="xs" type="checkbox" class="allowed-products" value="{{ $p->id }}" {!! ($p->allow_status == 1) ? 'checked="true"': '' !!} /> Short</label>
                           <label class="btn btn-sm"><input id = "js-switch" data-toggle="toggle" data-onstyle="success" data-size="xs" type="checkbox" class="final-allowed-products" value="{{ $p->id }}" {!! ($p->show_status == 1)? 'checked="true"': '' !!} /> Allow</label>
                        </td>
                        @endif
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   @endif
</div>
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
               <button id = "areabutton"type="button" class="btn btn-primary btn-block add-area-submit-btn">Add Area</button>
            </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
   $('#button').attr('disabled', 'disabled');
   
   $('#cnic').keydown(function(){
   
   //allow  backspace, tab, ctrl+A, escape, carriage return
   if (event.keyCode == 8 || event.keyCode == 9 
                 || event.keyCode == 27 || event.keyCode == 13 
                 || (event.keyCode == 65 && event.ctrlKey === true) )
                     return;
   if((event.keyCode < 48 || event.keyCode > 57))
   event.preventDefault();
   
   var length = $(this).val().length; 
           
   if(length == 5 || length == 13)
   $(this).val($(this).val()+'-');
   
   });
   
   //cnic Finish
   $('.custom-price-btn').click(function(){
   if(!$(this).hasClass('added-to-c')){
   $(this).addClass('added-to-c');
   $(this).html('<i class="fa fa-check"></i>');
   var cTR = $(this).closest('tr');
   $(cTR).find('.p-p').attr('value' , $(cTR).find('.p-p').val());
   $(cTR).find('.p-a-b').attr('value' , $(cTR).find('.p-a-b').val());
   $(cTR).find('.p-c-b').attr('value' , $(cTR).find('.p-c-b').val());
   $('#custom-prices').append('<tr>' + cTR.html() + '</tr>');
   }
   else{
   $(this).removeClass('added-to-c');
   $(this).html('<i class="fa fa-edit"></i>');
   var cTR = $(this).closest('tr');
   var id=cTR.children("input[type='hidden']").val();
   $('#custom-prices tr').find('input[type=hidden][value='+id+']').parent().remove();
   }
   });
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
   $('.allowed-products').change(function(){
   var val = $(this).val();
   if($(this).is(':checked')){
       $('#customer-form [data-value="'+val+'"]').attr('name', 'allowed_products[]');
   }
   else{
       $('#customer-form [data-value="'+val+'"]').removeAttr('name');
   }
   });
   $('.final-allowed-products').change(function(){
   var val = $(this).val();
   if($(this).is(':checked')){
       $('#customer-form [data-fvalue="'+val+'"]').attr('name', 'final_allowed_products[]');
   }
   else{
       $('#customer-form [data-fvalue="'+val+'"]').removeAttr('name');
   }
   });
   
   function getLocation() {
   if (navigator.geolocation) {
     return navigator.geolocation.getCurrentPosition(showPosition, showError);
   } else {
     alert("Try any other browser");
   }
   }
   
   function showPosition(position) {
   $('[name="location_url"]').val(position.coords.latitude+','+position.coords.longitude);
   if($('[name="location_url"]').val() != ""){
     //disableButton();
     $("#customer-form").submit();
   }else{
     alert('Location information is unavailable.');
   }
   }
   
   function getLocationn() {
   $('.area-find').addClass('btn-info');
   $('.remove-d-n').addClass('d-none');
   $('.area-find').html("Finding Area Wait!....");
   $('.success-data').html('');
   $('.area-find').attr("disabled", "disabled");
   if (navigator.geolocation) {
     return navigator.geolocation.getCurrentPosition(showPositionn, showError);
   } else {
     alert("Try any other browser");
   }
   }
   
   function showPositionn(position) {
   
   var a = position.coords.latitude+','+position.coords.longitude;
    var b = $('.customCords').val();
    if(b != ''){
      a = b;
    }
   if(a != ""){
       $.ajax({
         type: "GET",
         dataType: "json",
         url: '{{ route('find.nearest.customer') }}',
         data: {'cords': a },
                   success: function (data) {
                     
                        
                      if(data.success == true){
                          console.log(data.data1[0]);
                     document.getElementById('area-find').value = data.data1[0];
                     $('[name="area-name"]').val("Area Found: " + data.data1[1] 
                        + "\n" + "Customer Name:"+ data.data1[3] 
                        + "\n" + "Phone:" + data.data1[4]
                        + "\n" + "Address:" + data.data1[5]
                        + "\n" + "Distance In Meters:" + data.data1[2]
                        );

                     $('#button').attr('disabled', false);
                     $('.area-find').html("Find Area Again if wrong!");
                     $('.area-find').addClass('btn-success');
                     $('.area-find').removeClass('btn-info');
                     $('.success-data').html('Area Found Sucessfully!');
                      toastr.success(data.message);
              } else {
                       $('.remove-d-n').removeClass('d-none');
                      toastr.error(data.message);
                      $('.area-find').html("Find Area");
                      $('.area-find').addClass('btn-danger');
                      $('.area-find').removeClass('btn-info');
                      $('.fail-data').html('Area Not Found Try Again!');
                      
              }
         },
         // error: function(err){
         // toastr.error(data.message);
         // }
     });
   }
   
   }
   
   function showError(error) {
   switch(error.code) {
     case error.PERMISSION_DENIED:
       alert("User denied the request for Geolocation.");
       break;
     case error.POSITION_UNAVAILABLE:
       alert("Location information is unavailable.");
       break;
     case error.TIMEOUT:
       alert("The request to get user location timed out.");
       break;
     case error.UNKNOWN_ERROR:
       alert("An unknown error occurred.");
       break;
   }
   }
   // $(document).ready(getLocation);
   
   // email check
   
   $(document).ready(function(){
   
   $.ajaxSetup({
   headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
   });
   
   $(document).on('keydown', "#email", function(){
   var btn = $(this);
   var email = btn.val();
   var _token = $('input[name="_token"]').val();
   $.ajax({
   type: 'post',
   url: "{{ route('user.suggest.email') }}",
   data: { email: email },
   success: function(res){
     $('#suggested-emails').hide();
     if(res != "false"){
       $('#suggested-emails').html(res).show();
     }
   }
   });
   });
   
   $(document).on('click', "#suggested-emails li", function(){
   var $this = $(this);
   var text = $this.text();
   $("#email").val(text);
   $("#suggested-emails").hide();
   });
   
   $('#email').blur(function(){
   var error_email = '';
   var email = $('#email').val();
   var _token = $('input[name="_token"]').val();
   var filter = /^([a-zA-Z0-9_\.\-])+\@scoops.com+$/;
   //console.log(filter);
   if(!filter.test(email))
   {  
   $.ajax({
   url:"{{ route('email_available.check') }}",
   method:"POST",
   data:{email:email, _token:_token},
   success:function(result)
   {
     console.log(result);
   if(result == 'not_unique')
   {
   $('#error_email').html('<label class="text-danger">Email not Available</label>');
   $('#email').addClass('has-error');
   $('#button').attr('disabled', 'disabled');
   }
   else if(result == 'unique')
   {
   $('#error_email').html('<label class="text-success">Email Available</label>');
   $('#email').removeClass('has-error');
    var findarea = $('#area-find').val();
    var areaselect = $('#area-select').val();
    if (findarea == ''){
    $('#button').attr('disabled', 'disabled');
     
   }
   else{
   $('#button').attr('disabled', false);
   }
   }
   else
   {
   $('#error_email').html('<label class="text-danger">Invalid Email</label>');
   $('#email').addClass('has-error');
   $('#button').attr('disabled', 'disabled');
   
   }
   
   $("#suggested-emails").hide();
   }
   })
   
   // pick  
   
   }
   else
   {
   $('#error_email').html('<label class="text-danger">Invalid Email</label>');
   $('#email').addClass('has-error');
   $('#button').attr('disabled', 'disabled');
   
   }
   });
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
     
   // empty input dissable submit button
   // $(document).ready(function(){
   //     $('#button').attr('disabled',true);
   //     $('#message').keyup(function(){
   //         if($(this).val().length !=0)
   
   //             $('#button').attr('disabled', false);            
   //         else
   //             $('#button').attr('disabled',true);
   //     })
   // });
</script>
<script type="text/javascript">
   // add area
   
   
   //   $('.add-category').click(function(event){
   //     event.preventDefault();
   //     $('.old-category').val('')
   //     $('.category-form').attr('action' , '{{ route("save.area.ajax") }}');
   //     $('#category-popup .modal-title').html('Add Area');
   //     $('#category-popup .modal-footer button').text('Add Area');
   //   });
   
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
           var btn = document.getElementById('areabutton');
           btn.disabled = true;
           btn.innerText = 'Area saving Wait'
           $("#category-popup .close").click(); 
           
           var originalText = $(".add-area-submit-btn").text(),
               i  = 0;
               setInterval(function() {
           
               $(".add-area-submit-btn").append(".");
               i++;
           
               if(i == 4)
               {
                   $(".add-area-submit-btn").html(originalText);
                   i = 0;
               }
           
           }, 500);
            $('#button').attr('disabled', false);
    
                   }
                 });
               }
               
             });
     $("#area-select").change(function(e) {
     e.preventDefault();
     e.stopPropagation();
     if (this.value != "NULL") {
     $('#button').attr('disabled', false)
                          
     };
   });
     $('#customer-name').html('');
   
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
   
   var total = 0;
      $(function () {
           $('form').on('submit', function (e) {
             e.preventDefault();
             var btn = document.getElementById('button');
             btn.disabled = true;
             btn.innerText = 'Customer Saving Wait..';
             $('#button').addClass('btn-success');
             $.ajax({
               type: 'post',
               url: '{{ route('create.customer') }}',
               data: $('form').serialize(),
               success: function (data) {
                 var nType = "success";
                 var title = "Success ";
                 var msg = data.message;
                 notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
                 $('#customer-form')[0].reset();
                  var btn = document.getElementById('button');
                   btn.disabled = false;
                   total = total + 1;
                   btn.innerText = 'Again Save New Customer (Totoal Saved '+total+')';
                   $('.success-data').html('');
                   $('.fail-data').html('');
                   $('.area-find').html("Find Area");
                   $('.area-find').addClass('btn-info');
                   $('.area-find').removeClass('btn-success');
                   $('.area-find').removeClass('btn-danger');
                   $('.remove-d-n').addClass('d-none');
                   //$('#button').addClass('btn-primary');
                 }
             });
   
           });
   
         });
   
     
</script>
@endpush
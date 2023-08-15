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

</style>
@endpush
@section('title') Add User @endsection

@section('content')
<!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Add User</h5>
            <p class="text-muted m-b-10 text-center">Select User Type</p>
           <!--  <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <a href="index.html"> <i class="fa fa-home"></i> </a>
               </li>
               <li class="breadcrumb-item"><a href="#!">All Products</a>
               </li>
                <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li>
            </ul>
            <div class="card-header">
               <i class="fa fa-table"></i> Products List
               <a class="btn pull-right add-category btn-out-dashed btn-round btn-grd-primary" data-toggle="modal" data-target="#category-popup" style="color: white">Add Product</a>
            </div> -->
         </div>
      </div>
<div class="row">
  <div class="col-md-6 m-auto">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-user"></i> Enter User Details
      </div>
      <div class="card-body">
        <form id="myform">
          {{ csrf_field() }}
          <div class="form-group">
            <label>User Role</label>
            <select class="form-control" name="type" id="u-type">
              <option value="1">Seller</option>
              @if(Auth::user()->role == 1)
              <option value="2">Sub Admin</option>
              @endif
              <option value="3">Order Taker</option>
            </select>
          </div>
          <div class="form-group">
            <label>Name <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" type="text" placeholder="Enter Name" name="name" required>
          </div>
          <div class="form-group">
            <label>Email <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" id="email" type="email" placeholder="Enter Email" name="email" required>
            
            <div><b style="color: red;" id="user-not-unique"></b></div>
             @if($errors->has('email'))
            <div class="alert alert-danger">
                {{ $errors->first('email') }}
            </div>
            @endif
          </div>
          <div class="form-group">
            <label>Password <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" type="password" placeholder="Enter Password" name="password" required>
          </div>
          <div class="form-group">
            <label>Phone <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" type="number" placeholder="Enter Phone Number" name="phone" required>
          </div>
         
          <div id="has_pin"></div>
          <div id="custom-allow" style="display: none;">
           {{-- @foreach($customers as $c)
              <input type="hidden" class="form-control c-id" value="{{ $c->id }}">
            @endforeach --}}
          </div>
          
          <div class="form-group d-none r-d-n">
            <label>Allow him to create order <span style="opacity: 0.5; font-style: italic;">(Will have own customer)</span></label>
            <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="itself_order_taker">
          </div>

        <div style="display:none;" class="ot_options">
          <h3 class="mid">Custom Order Taker Benefit</h3>
          <div class="table-responsive">
              <div class="form-group pull-center">
            <input type="text" class="search form-control" placeholder="Search product to Allow or Show...">
        </div>
            <table class="table table-bordered table-custom-th table table-hover table-bordered results"  width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>Order Taker Benefit</th>
                  <th>Allow</th>
                </tr>
              </thead>
              <tbody>
                @if ($products)
                @foreach($products as $product)
                <tr>
                  <input type="hidden" class="form-control p-id" name="product_id[]" value="{{ $product->id }}" disabled>
                  <td class="name">{{ $product->name }}</td>
                  <td>
                   <input type="text" class="form-control p-ot" name="ot_benefit[]" value="{{ $product->ot_benefit }}" disabled>
                  </td>
                  <td>
                   <input type="checkbox" class="form-control p-check" data-toggle="toggle" data-onstyle="success" data-size="xs" value="{{ $product->id }}">
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>
          </div>
        <hr>
          <div style="margin-top : 30px;" class="row">
            <div class="card-header">
              <b>Customer Allow to Order Taker</b>
            </div>
          
          <br>
          <a class="btn btn-sm btn-out-dashed btn-round option btn-grd-primary selectAll" style="color: white">Check All</a>
          <a class="btn btn-sm btn-out-dashed btn-round option btn-grd-primary unselectAll" style="color: white">Uncheck All</a>
          </div>
      
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th table table-hover table-bordered results" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th style="width: 20%;">Name</th>
                <th style="width: 80%;">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($customers as $c)
              <tr class="customers">
                <input type="hidden" class="form-control c-id" name="" value="{{ $c->id }}">
                <td>{{ $c->user->name ?? '--' }}</td>
                <td><input type="checkbox" unchecked class="option checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs"></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
       <!--  <div class="btn-group mid" data-toggle="buttons">
          <label class="btn btn-primary active">
           <span>Allow Order Taker to create custom price customer</span>
           <input type="checkbox" name="custom" id="" >
          </label>
        </div> -->
        </div>
         <button type="submit" id="button" class="btn btn-primary btn-block">Create User</button>
        </form>
      </div>
  </div>
</div>
</div>

@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
  {{--  $('table').dataTable();  --}}
} );
  $('#u-type').on('change' , function(){
    if($(this).val() == 2){
      $('.r-d-n').removeClass('d-none');
      $('#has_pin').html('<div class="form-group">\
            <label>Pin Code <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>\
            <input class="form-control" type="number" placeholder="Enter Pin" name="pin">\
          </div>');
      
      $('.ot_options').css('display','none');
    } else if($(this).val() == 3){
       $('.ot_options').css('display','block');
      $('#has_pin').empty();
    } else{
      $('.r-d-n').addClass('d-none');
      $('#has_pin').empty();
      $('.ot_options').css('display','none');
    }
  });

  $(".selectAll").on('click',function(){
    $(".option").prop('checked',true);
    $(".selectAll").removeClass('btn-grd-primary');
    $(".selectAll").addClass('btn-grd-success');
    $(".customers").addClass('check-all');
    $(".checkbox").bootstrapToggle('on');
    $(this).html("All Customer selected!");
    var customer=$('.customers .c-id').clone();
    customer.each(function(key,value){
        $('#custom-allow').append(value);
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
  });

  $('.option').on('change',function(){
    var cTR = $(this).parents('.customers').find("input[type='hidden']").clone();
    var customer=cTR;
    var id=cTR.val();

    if($(this).is(':checked')) {
      customer.attr('name','customer_id[]');
      $('#custom-allow').append(customer);

    } else { 
      $('#custom-allow').find('input[type=hidden][value='+id+']').remove();
    }
  });
  $('.p-check').change(function(){
      if(!$(this).is(':checked')){
          $(this).closest('tr').find('input:not(.p-check)').attr('disabled', 'disabled');
          $(this).removeAttr('name');
      }else{
          $(this).closest('tr').find('input:not(.p-check)').removeAttr('disabled');
          $(this).attr('name', 'allowed_products[]');
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

var total = 0;
    $(function () {
        $('form').on('submit', function (e) {
          e.preventDefault();
          $('#user-not-unique').html('');
          var btn = document.getElementById('button');
          btn.disabled = true;
          btn.innerText = 'User Saving Wait..';
          var mail = $('#email').val();
          var data = finduser(mail);
        });

      });

    function finduser(argument) {
      
      $.ajax({
            type: 'post',
            url: '{{ route('find.user') }}',
            data:{
              _token: "{{ csrf_token() }}", 'email': argument},
            success: function (data) {
             
              if (data == 0){
                submitdata();
              }else{
                $('#user-not-unique').html('This User Is Already Taken!');
                var btn = document.getElementById('button');
                btn.disabled = false;
                  setTimeout(function(){
                   btn.innerText = 'Try Again to Create User';
               }, 5000);
                btn.innerText = 'Please resolve error!';
              }
              
              }
          });

      }

      function submitdata(){

         $('#button').addClass('btn-success');
          $.ajax({
            type: 'post',
            url: '{{ route('create.user') }}',
            data: $('form').serialize(),
            success: function (data) {
              console.log(data);
              var nType = "success";
              var title = "Success ";
              var msg = data.message;
              notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
              $('#myform')[0].reset();
               var btn = document.getElementById('button');
                btn.disabled = false;
                total = total + 1;
                btn.innerText = 'Again Save New User (Totoal Saved '+total+')';
                $('.ot_options').css('display','none');
                //$('#button').addClass('btn-primary');
              }
          });
      }
</script>
@endpush
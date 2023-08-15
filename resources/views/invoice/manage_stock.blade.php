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
    .not-in-sl{display: none;}r
    .results tr[visible='false'],
    .no-result{
      display:none;
    }
    
    .results tr[visible='true']{
      display:table-row;
    }

</style>
@endpush

@section('title') Manage Stock @endsection

@section('content')
<!-- Breadcrumbs-->
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Create Stock Invoice</h5>
            <p class="text-muted m-b-10 text-center">Add or Remove Product Stock</p>
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
<div class="row">
  <div class="col-md-6 m-auto">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-user"></i> Enter Invoice Details
        <button class="btn btn-sm btn-info pull-right print-invoice"><i class="fa fa-print"></i></button>
        <a href="javascript:;" target="_blank" class="btn btn-sm prev-record btn-primary pull-right" style="margin-right: 10px;display: none"><i class="fa fa-reply"></i></a>
        <span class="pull-right invoice_no" style="margin: 3px 15px;display: none"><b></b></span>
        <span class="pull-right" style="margin: 3px 15px"><b>{{ date('d/m/Y') }}</b></span>
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('store.invoice') }}" id="invoice-form" onsubmit='disableButton()'>
          {{ csrf_field() }}
          <div class="form-group">
              <center>
            <select class="fa fa-search" name="customer_id" style="width: 300px" id="customer-id" required="" data-placeholder="Choose a customer...">
                <option value="">Select a customer</option>
              @foreach($users as $user)
              <option value="{{ $user->id }}-{{ $user->name }}-{{ $user->address }}-{{ $user->phone }}-http://maps.google.com/maps?q=+{{ $user->location_url }}">{{ $user->name }}</option>
              @endforeach
            </select>
            </center>
          </div>
          <div class="form-group c-selected" style="display: none">
            <div class="row cus-details form-group">
              <div class="col-md-4 cname"><h6>Name: <b></b></h6></div>
              <div class="col-md-4 cphone"><h6>Phone: <b></b></h6></div>
              <div class="col-md-4 cbalance"><h6>Balance: <b></b></h6></div>
              <div class="col-md-4 cadv"><h6>Advance: <b></b></h6></div>
              <button class="btn btn-info sl-toggler" type="button">Show All Products</button>
            </div>
            <div class="row cus-details form-group">
                <div class="col-md-12 caddress"><h6>Address: <a href="" target="_blank"><b></b></a></h6></div>
            </div>
            <div class="table-responsive">
                <div class="form-group pull-center">
            <input type="text" class="search form-control" placeholder="Search product ...">
        </div>
              <table class="table table-bordered table-custom-th table table-hover table-bordered results" width="100%" cellspacing="0">
                <thead>
                  <tr>                    
                    <th>Name</th>
                    <!--<th>Price</th>-->
                    <th>Avl Stock</th>
                    <th>Units Add/Reomove</th>
                    <!--<th>Benefit</th>-->
                  </tr>
                </thead>
                <tbody id="custom-p-check">
                </tbody>
              </table>
            </div>
          </div>
          <div class="row create-invoice-section" id="create-section">
              <input type="hidden" class="form-control" type="number" name="stock_type" value="{{ $stock_type }}">
           <input type="hidden" class="form-control" type="number" name="user_id" value="">
           
               <div class="col-md-12">
            <label style="color: red;">Add Comments <span style="opacity: 0.5; font-style: italic; color: red;"></span></label>
            <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments"></textarea>
          </div>
          
          <div class="d-none">
          
          
            <div class="form-group col-md-6">
              <label>Received Amount</label>
              <input class="form-control r-amount" type="number" name="received_amount" value="0">
            </div>
            @if(Auth::user()->role < 3)
            <div class="form-group col-md-6">
              <label>Discount</label>
              <input class="form-control r-amount" type="number" name="discount" value="0">
            </div>
            @endif
            <div class="form-group col-md-6">
              <label>Total Amount</label>
              <input class="form-control " type="number" placeholder="Total Amount" disabled="" value="0">
            </div>            
            <div class="form-group col-md-6">
              <label>Advance</label>
              <input class="form-control advance-amount" type="number" name="advance" placeholder="Advance" disabled="" value="0">
            </div>
            <div class="form-group col-md-6">
              <label>Amount Left</label>
              <input class="form-control " type="number" placeholder="Amount Left" disabled="" value="0">
            </div>
            <div class="form-group col-md-6">
              <label>Customer Benefit</label>
              <input class="form-control c-benefit" type="number" placeholder="Customer Benefit" disabled="" value="0">
            </div>
            <div class="form-group col-md-6">
              <label>Sub Total</label>
              <input class="form-control " type="number" placeholder="Sub Total" disabled="" value="0">
            </div>
            </div>
            <div class="col-md-12">
                <button id="button" type="submit" class="btn btn-primary btn-block">Create Invoice</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript" src="{{ asset('assets/js/invoiceJS.js') }}"></script>

<script type="text/javascript">
 function disableButton() {
        var btn = document.getElementById('button');
        btn.disabled = true;
        btn.innerText = 'Invoice Saving Wait'

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
    
   $('#customer-id').on('change' , function(){
    var cdetails = $(this).val().split('-');
    console.log(cdetails[0]);
      if($(this).val() != ''){
           $('[name="user_id"]').val(cdetails[0]);
        $.get('{{ route("find.stock.product") }}/' + cdetails[0] , function(data){
          $('#custom-p-check').html(data);
          $('.cname b').text(cdetails[1]);
          $('.caddress a').attr('href' , cdetails[5]);
          $('.caddress b').text(cdetails[2]);
          $('.cphone b').text(cdetails[4]);
          $('.invoice_no b').text("#" + cdetails[3]);
          $('.prev-record').attr("href" ,  "{{ route('unApproved.Stock.Invoices') }}");
          $('.c-selected').fadeIn('slow');
          $('.t-amount').val(parseInt($('#old_balance').val()));
          
          if(parseInt($('#old_balance').val()) < 0)
          {
              $('.cadv b').text($('#old_balance').val());
          }
          else
          {
              $('.cbalance b').text($('#old_balance').val());
          }
          
          $('.prev-record').fadeIn();
          $('.invoice_no').fadeIn();
        });
      }
      else{
        $('.c-selected').fadeOut('slow');
        $('.prev-record').fadeOut();
          $('.invoice_no').fadeOut();
      }
  });
  $('.print-invoice').click(function(){
      window.print();
  });
  $(document).on('click', '.sl-toggler', function(){
        $('.not-in-sl').toggle();
    });
    
    $("#customer-id").select2({
            placeholder: "Select a Name",
            allowClear: true,
            theme: "classic"
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
$('.p-units').each(function(){
          if($(this).val() != '')
          var a =  b + parseFloat($(this).val());
          if(a != 0)
            {
                $('#create-section').removeClass('create-invoice-section');
            }
        });
</script>
@endpush
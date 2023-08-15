@extends('layouts.app')
@push('styles')
<style>
    .create-invoice-section{
        display: none;
    }
    .chosen-single{
        height: 40px !important;
        line-height: 36px !important;
    }
    .chosen-container-single .chosen-single div{
        top: 9px !important;
    }
    .create-invoice-section{
        display: none;
    /*}*/
    /*.not-in-sl{display: none;}*/
</style>
@endpush

@section('title') Edit Order @endsection

@section('content')
<!-- Breadcrumbs-->
<div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Edit Order </h5>
            <p class="text-muted m-b-10 text-center">Update Order detail</p>
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
        <i class="fa fa-user"></i> Order Details
      </div>
      <div class="card-body">        
        <div class="form-group update-type">
          <div class="row">
            <!-- <div class="col-md-6">
              <label class="btn btn-block btn-light">
                <input type="radio" name="radio-left" class="full-edit" checked="">
                Full Update
              </label>
            </div> -->
           <!--  <div class="col-md-6">
              <label class="btn btn-block btn-light">
                <input type="radio" name="radio-left" class="amount-left-r">
                Balance Update
              </label>
            </div> -->
          </div>
        </div>
        <form method="post" action="{{ route('update.order' , $order->id) }}" id="invoice-form" onsubmit='disableButton()'>
          {{ csrf_field() }}
          
          <div class="form-group">
              <h6>Customer Name: <b>{{ strtoupper($order->customer->user->name) }}</h6>
              
              <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
              <input type="hidden" name="old_balance" id="old_balance" value="{{ $amount_left }}">
          </div>
        <!--   <div class="col-md-12 text-center">
                  <button class="btn btn-info sl-toggler" type="button">Show All Products</button>
              </div> -->
          <div class="form-group">
            <div class="table-responsive">
              <table class="table table-bordered table-custom-th" width="100%" cellspacing="0">
                <thead>
                  <tr>                    
                    <th>Name</th>
                    <th>Price</th>
                    <th>Units</th>
                    <th>Amount</th>
                    <th>c Ben</th>
                  </tr>
                </thead>
                <tbody id="custom-p-check">
                  <?php $cat_p = []; ?>
                  @foreach($order->orderdetail as $odetail)
                  @if(!in_array($odetail->product->category_id , $cat_p))
                    <tr style="width: 100%">
                        <td class="bg-info">
                          {{ $odetail->product->category->name }}
                          <br>
                          <p>Old Order!</p>
                        </td>
                    </tr>
                    <?php $cat_p[] = $odetail->product->category_id ?>
                    @endif
                  <tr >
                    <input type="hidden" class="form-control p-id" name="product_id[]" value="{{ $odetail->product_id }}">                  
                    <input type="hidden" class="c-ben" name="" value="{{ $odetail->product->c_benefit }}">  
                    <td style="color: red;">{{ $odetail->product->name }}</td>
                    <td class="p-price">
                      <input type="text" value="{{ $odetail->product->price }}" class="form-control p-price-{{ $odetail->product->id }}" {{ Auth::user()->role < 3 ? '' : 'readonly' }} /></td>
                    <td>
                      <input type="number"  onchange="myFunction()" data-id="{{ $odetail->product->id }}" style="width: 70px;" class="form-control p-units p-units-{{ $odetail->product->id }}" step="any" value="{{ $odetail->unit }}" name="unit[]">
                    </td>
                    <td><input type="number" name="amount[]" value="{{ $odetail->amount }}" class="row-amount p-amount-{{ $odetail->product->id }}" readonly></td>
                    <td class="show-row-ben">{{ $odetail->product->c_benefit*$odetail->unit }}</td>
                  </tr>
                  @endforeach
                  <?php $cat_p = []; ?>
                  @foreach($products as $key => $p)
                  @if(!in_array($p->category_id , $cat_p))
                    <tr>
                        <td class="bg-primary">{{ $p->category->name }}</td>
                    </tr>
                    <?php $cat_p[] = $p->category_id ?>
                    @endif
                  <tr>
                    <input type="hidden" class="form-control p-id" name="product_id[]" value="{{ $p->id }}">  
                    <input type="hidden" class="c-ben" name="" value="{{ $p->c_benefit }}">  
                    <td>{{ $p->name }}</td>
                    <td class="p-price"><input style="width: 70px;" type="text" value="{{ $p->price }}" class="form-control  p-price-{{ $p->id }}" {{ Auth::user()->role < 3 ? '' : 'readonly' }} /></td>
                    <td>
                      <input type="number" style="width: 70px;" data-id="{{ $p->id }}" class="form-control p-units p-units-{{ $p->id }}" step="any" value="{{ $p->unit }}" name="unit[]">
                    </td>
                    <td><input type="number" class="row-amount p-amount-{{ $p->id }}" name="amount[]" readonly></td>
                    <td class="show-row-ben">0</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="row" id="create-section">
              <div class="form-group d-none">
            <label>Location Cordinates <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
            <div id="map-layer"></div>
            <input autocomplete="off" class="form-control" type="text" id="myMessage"  maxlength="19" placeholder="Enter Cordinates Only" name="location_url_ot" value="{{ $order->location_url_ot }}">
          </div>
          <div class="form-group d-none">
            <label>Ot id <span style="opacity: 0.5; font-style: italic; color: red;">(Required)</span></label>
            <div id="map-layer"></div>
            <input autocomplete="off" class="form-control" type="text" id="myMessage"  maxlength="19" placeholder="Enter Cordinates Only" name="ot_id" value="{{ $order->ot_id }}">
          </div>
              <div class="col-md-12">
            <label style="color: red;">Add Comments <span style="opacity: 0.5; font-style: italic; color: red;"></span></label>
            <textarea class="form-control" maxlength = "40" rows="4"  name="order_comments">{{ $order->order_comments }}</textarea>
                
             <label class="btn btn-primary active">
                  <span>Mark As Urgent Delivery</span>
                  <input class="js-switch" type="checkbox" name="urgent" value=" {{ $order->urgent}} " @if( $order->urgent == 'urgent') checked @endif >
                 
                  <div>
                  <span>Set Order Date :</span><input type="date" style="margin-left:30px;" min="{{ date('Y-m-d', strtotime($order->order_date)) }}" name="order_date" value="{{ date('Y-m-d', strtotime($order->order_date)) }}" />
                  </div>
                </label>
                @if(Auth::user()->role < 3 )
                 <label class="btn btn-primary active">
                  <span>Do not Use Stok</span>
                  <input class="js-switch" onclick="UseStock();" type="checkbox" name="stock_useable" data-id="{{ $order->id }}" value ="{{ $order->use_stock }}"{{ ($order->use_stock === 0  ? 'checked' : '') }} />
                </label>
                 @endif
                <!--<label class="btn btn-primary active">-->
                <!--  <span>Mark As Today Delivery</span>-->
                <!--  <input class="js-switch class1" type="checkbox" name="today"   value="{{ $order->today }}" @if( $order->today == 'today') checked @endif>-->
                <!--</label>-->
                <!--<label class="btn btn-primary active">-->
                <!--  <span>Mark As Yesterday Delivery</span>-->
              
                <!--</label>-->
          </div>
          
            <div class="form-group col-md-6">
                
              <label>Received Amount</label>
              @if ( Auth::user()->role == 5 )
              <input class="form-control r-amount" type="number" name="received_amount" value="0" {{ Auth::user()->role <= 3 ? '' : 'readonly' }}>
              @else ( Auth::user()->role < 3  )
              <input class="form-control r-amount" type="number" step="any" name="received_amount" required = "" value="" {{ Auth::user()->role <= 3 ? '' : 'readonly' }}>
              @endif
            </div>
            <div class="form-group col-md-6">
              <label>Discount</label>
              <input class="form-control r-amount" type="number" name="discount" value="{{ $order->discount }}" {{ Auth::user()->role <= 3 ? '' : 'readonly' }}>
            </div>
            <div class="form-group col-md-6">
              <label>Total Amount</label>
              <input class="form-control t-amount" id="mySelect"  type="number" name = "amount" placeholder="Total Amount" disabled="" value="{{ $order->amount }}">

            </div>
            <!--<div class="form-group col-md-6 d-none">-->
            <!--  <label>Number of vsist</label>-->
            <!--  <input class="form-control"  type="number" name = "chk_ord_vst" placeholder="Total Amount" value="{{ $order->chk_ord_vst  }}">-->

            <!--</div>-->
            <!--<input style="display: none" type="number"  id="demo" value="">-->
            
            <div class="form-group col-md-6">
              <label>Advance</label>
              <input class="form-control advance-amount" type="number" name="advance" placeholder="Advance" disabled="" value="{{ $order->advance }}">
            </div>
            <div class="form-group col-md-6">
              <label>Amount Left</label>
              <input class="form-control amount-left" type="number" placeholder="Amount Left" disabled="" value="{{ $amount_left }}">
            </div>
            <div class="form-group col-md-6">
              <label>Customer Benefit</label>
              <input class="form-control c-benefit" type="number" name = "c_benefit" placeholder="Customer Benefit" disabled="" value="{{ $order->c_benefit }}">
            </div>
            <div class="form-group col-md-6">
              <label>Sub Total</label>
              <input class="form-control sub-total" type="number"  placeholder="Sub Total" disabled="" value="{{ $order->subtotal }}">
            </div>
            <div class="col-md-12">
                <button id="button" type="submit" class="btn btn-primary btn-block">Update Order</button>
            </div>
          </div>
        </form>
        <form method="post" id="amount-left-r-form" style="display: none">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Enter Received Amount ( RS-/ <b>{{ $amount_left }}</b> Left )</label>
            <input type="number" name="amount_left_input" class="form-control">
          </div>
          <button class="btn btn-primary btn-block">Update Order</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('assets/js/invoiceJS.js') }}"></script>

<script type="text/javascript">


function myFunction() {
  var x = document.getElementById("mySelect").value;
  document.getElementById("demo").value = x;
}

function disableButton() {

        var btn = document.getElementById('button');
        btn.disabled = true;
        btn.innerText = 'Order Updating Wait'
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

//   $('.update-type input:radio').on('change' , function(){

//     if(this.checked){
//       if($(this).hasClass('full-edit')){
//         $('#invoice-form').attr('action' , $('#amount-left-r-form').attr('action')).show();
//         $('#amount-left-r-form').hide();
//       }
//       else{
//         $('#amount-left-r-form').attr('action' , $('#invoice-form').attr('action')).show();
//         $('#invoice-form').hide(); 
//       }
//     }
//   });
  
  //stats swith controll


$(document).ready(function(){
    $('.class1').on('change', function(){        
        if($('.class1:checked').length){
            $('.class3').prop('disabled', true);
            $('.class3').prop('checked', false);
            return;
        }
        
        $('.class3').prop('disabled', false);
    });
    
    $('.class2').on('change', function(){
        if(!$(this).prop('checked')){
            $('.class2').prop('disabled', false);
            return;
        }
        $('.class2').prop('disabled', true);
        $(this).prop('disabled', false);
        
        !$('.class1:checked').length ? $('.class1').click() : '';
    });
})
// show all product
$(document).on('click', '.sl-toggler', function(){
        $('.not-in-sl').toggle();
    });
    function UseStock (){
        
    var order_id = @json($order->id); 
     $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('set.stock') }}',
            data: {order_id , order_id}, 
            success: function (data) {
            toastr.success(data.message);
            }
        });
    }

    $('.p-units').keyup(function(){

    var id = $(this).data('id');
  
    var units = $('.p-units-'+id).val();
    var p_price = $('.p-price-'+id).val();
    $('.p-amount-'+id).val(units * p_price);
    console.log(id , units , p_price);
   
  });
    

</script>
@endpush
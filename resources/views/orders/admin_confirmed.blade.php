@extends('layouts.app')
@section('title') Admin Confirmed Orders @endsection
@section('content')
@push('styles')
<style>
    .highlighted td {
    background: green;
}
.cancel td {
    background: red;
}
.delivery-failed{
  background: yellow;

}
.today-delivery{
    background: #00FBF7;
}
.delivery-missed{
 
    background: pink;
       border-style: solid;
}
.delivery-missed-2day{
    background: red;
}
.new-customer{
    background: #00FB63;
}
.clear td {
    background: #009868;
}
.brdr{
			border: 1px solid gray;
			padding: 1px;
			font-weight: 600;
			background: red;
		}
</style>
@endpush
<?php error_reporting(0) ?>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Orders</a></li>
  <li class="breadcrumb-item active">Admin Confirmed Orders</li>
</ol>
<div class="row">
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Orders List
        <div class="col-md-10 pull-right">
          @if(Auth::user()->role < 3)
            <form method="post" >
              {{ csrf_field() }}
              <div style="padding: 20px;" class="row">
                <div class="col-md-3">
                  <label>Filter By Order Taker</label>
                </div>
                <div class="col-md-3">
                  <select class="form-control ot_filter" name="" >
                  <option value="yes">Show All</option>
                  @foreach ($ordertakers as $ot)
                    <option value="{{ $ot->id }}">{{$ot->name}}</option>
                  @endforeach
                  </select>
                </div>
              </div>
              <div style="padding: 20px;" class="row">
                <div class="col-md-3">
                  <label>Filter By Area</label>
                </div>
                <div class="col-md-3">
                  <select class="form-control ot_area" name="" >
                  <option value="yes">Show All</option>
                  @foreach ($areas as $ot)
                    <option value="{{ $ot->id }}">{{$ot->name}}</option>
                  @endforeach
                  </select>
                </div>
              </div>
            </form>
          @endif
          @if(Auth::user()->role <=3)
                      <button type="button" class="btn btn-success btn-sm check-all">check all</button>
                      <button type="button" @if(Auth::user()->role < 3) data-toggle="modal" data-target="#order-unapprove-popup" @else onclick="document.getElementById('multiple-approve').submit()" @endif class="btn btn-success btn-sm app-mult">Confirm</button>
                      <button type="button" id="btnGet" class="btn btn-success btn-sm">Show Invoice</button>
                       <div class="btn p-0 text-white table-responsive" style="cursor: default">
                      <span class="px-3 py-1 my-auto mr-1 d-inline-block bg-danger">
                        Phone Order <br> Tota ord: {{ $distance_data['red_total_distance']." Today ord:  ".$distance_data['red_today_distance'] }}
                      </span>
                      <span class="px-3 py-1 my-auto mr-1 d-inline-block bg-success">
                        Visit Order <br> Tota ord: {{ $distance_data['green_total_distance']." Today ord: ".$distance_data['green_today_distance'] }}
                      </span>
                    </div>
                    @endif
          <form method="post" action="{{ route('confirmed.orders.admin' ) }}">
                {{ csrf_field() }}
                
                <div class="row">
                 
                    <div class="col-md-1 text-right">
                        <b>From</b>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="from" value="{{ date('d-m-Y') }}" class="form-control" />
                    </div>
                    <div class="col-md-1 text-right">
                        <b>To</b>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="to" value="{{ date('d/m/Y') }}" class="form-control" />
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-success btn-sm">Search</button>
                    </div>
                </div>
            </form>
    </div>
</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th style="width: 20px">#</th>
                <th>Customer</th>
                <th>Comments</th>
                <th>Location</th>
                <th>Order Taker</th>
                <th>Units</th>
                <th>Total</th>
                <th>Sub Total</th>
                <th>Recieved</th>
                <th>Balance</th>
                <th>Advance</th>
                <th>OT Benefit</th>
                @if(Auth::user()->role !=3)
                  <th>Confirmed By</th>
                  <th>Selected Seller</th>
                @endif
                <th>C Benefit</th>
                <th>Date</th>
                <th>Action</th>
               </tr>
            </thead>
            <tbody>
              @foreach($orders as $key => $order)
              <tr>
                <input type="hidden" class="ot_id" name="ot_id" value="{{ $order->ordertaker->id }}">
                <input type="hidden" class="area_id" name="area_id" value="{{ $order->customer->area->id }}">
                <td>{{ $order->id }}</td>
                <td>@if(Auth::user()->role < 3)
                    <a href="{{ route('update.receiving' , $order->id) }}" style="width : 80px;" class="btn btn-sm btn-warning edit-receiving" 
                data-toggle="modal" data-target="#receiving-popup"><i class="fa fa-money"></i></a>
                @endif
                {{ $order->customers->user->name }}
                <!--un hide for order taker-->
                 <br>@if(Auth::user()->role < 3 &&  $order->ot_customer_distance * 1000 > 300 )
                    
                     <P class="brdr text-center">Phone Order/{{ $order->chk_ord_vst }}<br>
                     {{ $order->ot_customer_distance * 1000 }}</P>
                     @endif
                 <br>
                </td>
                @if ($order->cancel_status == 1)
                <td>{{ $order->cancel_reason  }}</a>
               <br>----------
               <br>{{ $order->customers->customer_request }}
               <br>----------
               <br>{{ $order->order_comments }}</td>
               @elseif ($order->cancel_status == 0)
               <td style="width: 20px ; background: #007bff;">
               <a href="{{ route('update.canceltext' , $order->id) }}" class="edit-category" style="color : white;" data-toggle="modal" data-target="#category-popup"> (Order Canelled) <br> {{ $order->cancel_reason  }}</a>
               <br>----------
               <br>{{ $order->customers->customer_request }}
               <br>----------
               <br>{{ $order->order_comments }}</td>
               @else ($order->cancel_status == 2)
               <td style="width: 20px ; background: #007bff;">
               <a href="{{ route('update.canceltext' , $order->id) }}" class="edit-category" style="color : white;" data-toggle="modal" data-target="#category-popup"> (Delivery Failed) <br> {{ $order->cancel_reason  }}</a>
               <br>----------
               <br>{{ $order->customers->customer_request }}
               <br>----------
               <br>{{ $order->order_comments }}
               @endif</td>
                <!--<td><a href="http://maps.google.com/maps?q=+{{ $order->customers->location_url }}">-->
                <!--{{ $order->customers->address }}</a><br>{{ $order->customers->location_url }}-->
                <!--<br><a href="http://maps.google.com/maps?q=+{{ $order->location_url_ot }}" target="_blank">-->
                <!-- {{ $order->location_url_ot }}</a>/-->
                <!-- @if(Auth::user()->role != 5)-->
                <!-- @if ($order->ot_customer_distance * 1000 > 1000)-->
                <!-- {{ $order->ot_customer_distance }}Km-->
                <!-- @else -->
                <!-- {{ $order->ot_customer_distance * 1000 }}M-->
                <!-- @endif-->
                <!-- @endif-->
                <!-- / {{  $order->chk_ord_vst }}</td>-->
                @if(Auth::user()->role <= 3)<td><a href="http://maps.google.com/maps?q=+{{ $order->customers->location_url }}">{{ $order->customers->area->name }} /
                {{ $order->customers->address }}</a><br>{{ $order->customers->location_url }}<br>
                 @if( $order->clear == NULL )
                 <a href="{{ route('clear.order' , $order->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-check-square">Clear</i></a>
                 @elseif( $order->clear == clear )
                 <a href="{{ route('unclear.order' , $order->id) }}" class="btn btn-sm btn-w)arning"><i class="fa fa-times-circle">Unclear</i></a>
                @endif
                 <a href="http://maps.google.com/maps?q=+{{ $order->location_url_ot }}" target="_blank">
                 {{ $order->location_url_ot }}</a>/
                 @if(Auth::user()->role != 5)
                 @if ($order->ot_customer_distance * 1000 > 1000)
                 {{ $order->ot_customer_distance }}Km
                 @else 
                 {{ $order->ot_customer_distance * 1000 }}M
                 @endif
                 @endif
                 / {{  $order->chk_ord_vst }}
                </td>
                @else
                <td>
                    {{ $order->customers->address }}
                </td>
                @endif
                <td>{{ $order->ordertaker->name }}</td>
                <td>{{ $order->unit }}</td>
                <td>{{ $order->subtotal + $order->amount_left - $order->advance }}</td>
                <td>{{ $order->subtotal }}</td>
                <td>{{ $order->received_amount }}</td>
                <td>{{ $order->amount - $order->received_amount  }}</td>
                @if($order->received_amount > ($order->subtotal + $order->amount_left - $order->advance))
                  <td>$order->amount_left</td>
                @else
                  <td>{{ $order->advance }}</td>
                @endif
                <td>{{ $order->ot_benefit }}</td>
                @if(Auth::user()->role !=3)
                  <td>{{ $order->seller->name }}</td>
                  <td>{{ $order->selected_seller }}</td>
                @endif
                <td>{{ $order->c_benefit }}</td>
                <td>{{ $order->created_at }}</td>
                <td>
                @if(Auth::user()->role <3)
                  <label class="btn btn-default btn-sm">
                    <input type="checkbox" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                  </label>
                @endif
                @if(Auth::user()->role < 3)
                
                 @if ( $order->received_amount == 0)
                   <div >
                   <label class="btn btn-sm btn-warning">
                  <span>Received Subtotal</span> <input type="checkbox" id="btn" onclick="myFunction()" data-id="{{ $order->id }}" name="equal_order" value="red" class="js-switch1" {{ $order->received_amount == 0 ? 'unchecked' : '' }}></label>
                  </div>
                           <a href="{{ route('edit.order' , $order->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                  <a href="{{ route('delete.order' , $order->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></a>
       
                  @endif
                  @endif
                <a href="javascript:;" data-toggle="modal" data-target="#order-detail-popup" class="btn btn-sm btn-success view-details" id="{{ $order->id }}"><i class="fa fa-eye"></i></a>
                @if(Auth::user()->role < 3)
                
                @if(in_array($key, $keys_generated))
                <span>Reduce Order visit</span> <input type="checkbox" id="btn" onclick="myFunction()" data-id="{{ $order->id }}" name="chk_ord_vst" value="red" class="js-switch2" {{ $order->chk_ord_vst == 0 ? 'unchecked' : '' }}></label>
                 @endif
                 
                 
                 @endif 
                 
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted">Total: <b>{{ $orders->sum('amount') }}</b> | Sub Total: <b>{{ $orders->sum('subtotal') }}</b>  | Total Order Taker Benefit: <b>{{ $orders->sum('ot_benefit') }}</b> | Balance: <b>{{ $orders->sum('amount_left') }}</b> | Rec Amount: <b>{{ $orders->sum('received_amount') }}</b> @if(Auth::user()->role < 3) | A Ben: <b>{{ $orders->sum('subtotal') - $orders->sum('p_amount') - $orders->sum('discount') - $orders->sum('ot_benefit') }}</b> @endif | C Ben: <b>{{ $orders->sum('c_benefit') }}</b> | Advance: <b>{{ $orders->sum('advance') }}</b> | Units: <b>{{ $orders->sum('unit') }}</b>| Discount: <b>{{ $orders->sum('discount') }}</b> 
      <button class="pull-right btn btn-info btn-sm" data-toggle="modal" data-target="#product-report-popup"><i class="fa fa-eye"></i></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="product-report-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="width: 100%" id="exampleModalLabel">Product Sell Report <small></small></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>P.ID</th>
                <th>P.Name</th>
                <th>Units</th>
                @if(Auth::user()->role < 3)
                <th>Amount</th>
                @endif
              </tr>
            </thead>
            <tbody>
                <?php $show_in_modal = 0; ?>
              @foreach($product_report as $preport)
                @if($preport['amount'] != 0 || $preport['unit'] != 0)
                  <tr>
                    <td>{{ $preport['id'] }}</td>
                    <td>{{ $preport['name'] }}</td>
                    <td>{{ $preport['unit'] }}</td>
                    @if(Auth::user()->role < 3)
                    <td>{{ $preport['amount'] }}</td>
                    @endif
                    <?php $show_in_modal += $preport['amount']; ?>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="alert alert-info text-left pull-left">
          @if(Auth::user()->role < 3)
          <b>P.Total: </b>{{ $show_in_modal }}
          @endif
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="order-detail-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Invoice Detail <small></small></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
        @if(Auth::user()->role < 3)
        <a href="" class="btn btn-primary approve-btn">Approve</a>
        <button onclick="window.location='printerplus://send?text='+document.getElementById('p').innerHTML;">
      Send to Printer+
    </button>
        @endif
      </div>
    </div>
  </div>
</div>
<!--Receiving Modal-->

<div class="modal fade" id="receiving-popup" tabindex="-1" role="dialog" aria-labelledby="receiving-popup" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Order Status</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" class="receiving-form">
          {{ csrf_field() }}
          <div class="form-group">
            <!--<label>What Happene to this Order?</label>-->
            <tr>
                  <td>Subtotal : {{ $order->subtotal}}</td>
              </tr>
              ||
              <tr>
                  <td>Balance : {{ $order->amount_left}}</td>
              </tr>
              ||
              <tr>
                  <td>Total : {{ $order->amount}}</td>
              </tr>
              <!--<br>-->
            <!--<p styel = "text-align : center; ">Add Receiving</p>-->
            <label>Enter Receiving</label>
            <input type="number" name="received_amount" value="" class="form-control old-receiving" >
            @if(Auth::user()->role <= 2)
            <label>Set Balance</label>
            <input type="number" name="amount_left" value="" class="form-control old-receiving">
            <label>Enter Comments</label>
             <textarea class="form-control" rows="4" placeholder="Enter Comments" name="order_comments" maxlength = "200"></textarea>
            @endif
          </div>
          <div class="form-group">
            <button class="btn btn-primary btn-block">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<form style="display: none" method="post" action="{{ route('approve.order.multiple') }}" id="multiple-approve">
    {{ csrf_field() }}
</form>
@endsection
@section('scripts')
<script type="text/javascript">
  $('.view-details').click(function(){
    console.log('es')
    var param = $(this).attr('id');
    console.log(param);
    $('.approve-btn').attr('href' , "{{ route('approve.invoice') }}/" + param);
    $('#order-detail-popup .modal-title small').text('(' + $(this).closest('tr').find('input').val() + ')');
    $('#order-detail-popup .modal-body').html('<h6 class="text-center">Loading ..</h6>');
    $.get('{{ route("order.detail") }}/' + param , function(success){
      $('#order-detail-popup .modal-body').html(success);
    });
  });
  $('.check-all').click(function(){
      if($(this).hasClass('revert')){
          var is_rev = true;
          $(this).removeClass('revert');
          $(this).text('check all');
      }
      else{
          var is_rev = false;
          $(this).addClass('revert');
          $(this).text('uncheck all');
      }
      $('tbody .approve-to').each(function(confirm){
          if(!is_rev){
              $(this).prop('checked' , 'checked');
              $('#multiple-approve').append($(this).closest('label').html());
              $('#multiple-approve .approve-to').last().attr('checked' , 'checked');
          }
          else{
              $(this).prop('checked' , false);
              $('#multiple-approve .approve-to').each(function(){
                  $(this).remove();
              });
          }
      });
  });
  
  $('#multiple-approve .approve-to').on('change' , function(){
      if(!this.checked){
          $(this).remove();
      }
  });
  $('tbody .approve-to').on('change' , function(){
   
      if(this.checked){
         console.log('yes');
          $('#multiple-approve').append($(this).closest('label').html());
          $('#multiple-approve .approve-to').last().attr('checked' , 'checked');
      }
      else{
          var this_val = $(this).val();
          $('#multiple-approve .approve-to').each(function(){
              if($(this).val() == this_val){
                  $(this).remove();
              }
          });
      }
  });
  var ot_filter_val = 'yes',ot_area_val = 'yes';
  function runtimeFilter()
  {
    if(ot_filter_val == 'yes' && ot_area_val == 'yes'){
        $('.ot_id').each(function(){
          $(this).parent().show();
        });
        $('#dataTable_info').show();
    }
    else{
      $('.ot_id').each(function(){
        var flag = false;
        if(ot_filter_val != 'yes' && ot_area_val != 'yes')
        {
            if(ot_filter_val == $(this).val() && ot_area_val == $(this).closest('tr').find('.area_id').val())
                flag = true;
        }
        else if(ot_filter_val == $(this).val())
            flag = true;
        else if(ot_area_val == $(this).closest('tr').find('.area_id').val())
            flag = true;
        if(!flag){
          $(this).parent().hide();
          $('#dataTable_info').hide();
        }
        else{
          $(this).parent().show();
        }
      });
    }
  }
  $('.ot_filter').on('change' , function(){
      ot_filter_val = $(this).val()
      runtimeFilter();
});
$('.ot_area').on('change' , function(){
      ot_area_val = $(this).val()
      runtimeFilter();
});
//received subtotal
  
  $(document).on('click', '.edit-receiving', function(event){
    event.preventDefault();
    var cTR = $(this).closest('tr');
    var c_name = cTR.find('td').eq(1).text();
    $('.old-category').val(c_name)
    $('.receiving-form').attr('action' , $(this).attr('href'));
    $('#receiving-popup .modal-title').html('Edit <b>' + c_name + '</b>');
    $('#receiving-popup .modal-footer button').text('Update Category');
});

//equal order by ajax

$(document).ready(function(){
    $(document).on('change', '.js-switch1', function () {
        let received_amount = $(this).prop('checked') === true ? 1 : 0;
         if ($(this).prop('checked') == 0) {
       $(this).closest('tr').addClass('cancel'); 
       $(this).attr("disabled", true);
    } else {
       $(this).closest('tr').removeClass('cancel'); 
       $(this).attr("disabled", true);
    }
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('equal.order') }}',
            data: {'received_amount': received_amount, 'user_id': userId},
            success: function (data) {
            console.log(data.message);
            }
        });
});
});

$(document).ready(function(){
    $(document).on('change', '.js-switch2', function () {
        let chk_ord_vst = $(this).prop('checked') === true ? 1 : 0;
         if ($(this).prop('checked') == 0) {
       $(this).closest('tr').addClass('cancel'); 
       $(this).attr("disabled", true);
    } else {
       $(this).closest('tr').removeClass('cancel'); 
       $(this).attr("disabled", true);
    }
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('update.updateotvisit') }}',
            data: {'chk_ord_vst': chk_ord_vst, 'user_id': userId},
            success: function (data) {
            console.log(data.message);
            }
        });
});
});

</script>
@endsection
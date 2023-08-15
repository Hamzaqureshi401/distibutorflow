@extends('layouts.app')
@section('title') Customer Orders @endsection
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
<div class="page-header card">
        <div class="card-block">
            <h5 class="m-b-10 text-center">{{ $title }}</h5>
            <p class="text-muted m-b-10 text-center">{{ $details }}</p>
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

                     <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#product-report-popup"><i class="fa fa-eye"></i>Show Product Details</button>
                  
                   
                     @php
                  $now = \Carbon\Carbon::now()->toDateString();
                  $a = strtotime($orders->pluck('created_at')->first());
                  $b = strtotime($now);
                  $days_between = ceil(($b - $a) / 86400);
                  @endphp
                    
                     
      </div>
    </div>

<div class="row">
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header">
        <!-- <i class="fa fa-table"></i> <b>Ot</b> Orders List -->
        <div class="col-md-10 pull-right">
           <form method="get" action="{{ route('ot.orders', ['ot_id' => $ot_id]) }}">
    <div class="row">
        <div class="col-md-1 text-right">
            <b>From</b>
        </div>
        <div class="col-md-3">
            <input type="date" name="from" value="{{ date('Y-m-d') }}" class="form-control" />
        </div>
        <div class="col-md-1 text-right">
            <b>To</b>
        </div>
        <div class="col-md-3">
            <input type="date" name="to" value="{{ date('Y-m-d') }}" class="form-control" />
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-success btn-sm">Search</button>
        </div>
    </div>
</form>


        </div>
        </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th table-datatable table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr class="header" id="myHeader">
                <th>#</th>
                <td>Customer</td>
                <th>Comments</th>
                <th>Units</th>
                <th>Order Status</th>
                <th>Total</th>
                <th>Sub Total</th>
                <th>Sell Price</th>
                <th>Recieved</th>
                <th>Discount</th>
                <th>Balance</th>
                <th>Advance</th>
                @if(Auth::user()->role < 3)
                <th>A Benefit</th>
                @endif
                <th>C Benefit</th>
                <th>Comments</th>
                <th>Approve Date</th>
                <th>Date</th>
                <th>Action/Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $order)              
              <tr>
                <input type="hidden" name="" value="{{ $order->created_at->diffForHumans() }}">
                <td>{{ $order->id }}<br> {{ $order->selected_seller }} <br> {{$order->seller->name}}</td>
                <td>{{ $order->customers->user->name ?? '--' }} / {{ $order->customers->customer_name ?? '--' }}</td>
                 @if ($order->cancel_status == 1)
                <td>
                    <a href="{{ route('update.canceltext' , $order->id) }}" style="color : black;" class="edit-category" data-toggle="modal" data-target="#category-popup">(Order In Pending)  <br> {{ $order->cancel_reason  }}</a>
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
               <br>{{ $order->order_comments }}</td>
               @endif
                </td>
                
                <!--Customer Name-->
                
            <!--Unit-->
                
                    @if ( $order->received_amount < $order->subtotal  )
                <td style="color: red" data-changein="subtotal">{{ $order->unit }}</td>
                @elseif ( $order->received_amount > $order->subtotal  )
                <td style="color: #2ECC71" data-changein="subtotal">{{ $order->unit }}</td>
                @elseif ( $order->received_amount == $order->subtotal && $order->amount_left <= 0 )
                <td style="color: #2ECC71" data-changein="subtotal">{{ $order->unit }}</td>
                @elseif ( $order->received_amount == $order->subtotal && $order->amount_left > 0 )
                <td style="color: #CC9A2E" data-changein="subtotal">{{ $order->unit }}</td>
                @elseif ( $order->received_amount == 0  )
                <td data-changein="subtotal">{{ $order->unit }}</td>
                @endif
                
                
                @if($order->i_status == 0)
                  <td><p style="color : red"> Not updated</p></td>
                  @elseif($order->i_status == 1)
                  <td><p style="color : #2ECC71">Updated</p></td>
                  @endif
                
                
                <!--Total-->
                
                       
                @if ( $order->received_amount < $order->subtotal  )
                <td style="color: red" data-changein="subtotal">{{ $order->amount }}</td>
                @elseif ( $order->received_amount > $order->subtotal && $order->amount_left > 0  )
                <td style="color: #CC9A2E" data-changein="subtotal">{{ $order->amount }}</td>
                @elseif ( $order->received_amount > $order->subtotal && $order->amount_left <= 0  )
                <td style="color: #28B463" data-changein="subtotal">{{ $order->amount }}</td>
                @elseif ( $order->received_amount == $order->subtotal && $order->amount_left <= 0 )
                <td style="color: #2ECC71" data-changein="subtotal">{{ $order->amount }}</td>
                @elseif ( $order->received_amount == $order->subtotal && $order->amount_left > 0 )
                <td style="color: #CC9A2E" data-changein="subtotal">{{ $order->amount }}</td>
                @elseif ( $order->received_amount == 0  )
                <td data-changein="subtotal">{{ $order->amount }}</td>
                @endif
                
                
                <!--Subtotal-->
               
                @if ( $order->received_amount < $order->subtotal  )
                <td style="color: red" data-changein="subtotal">{{ $order->subtotal }}</td>
                @elseif ( $order->received_amount > $order->subtotal  )
                <td style="color: #2ECC71" data-changein="subtotal">{{ $order->subtotal }}</td>
                @elseif ( $order->received_amount == $order->subtotal && $order->amount_left <= 0 )
                <td style="color: #2ECC71" data-changein="subtotal">{{ $order->subtotal }}</td>
                @elseif ( $order->received_amount == $order->subtotal && $order->amount_left > 0 )
                <td style="color: #CC9A2E" data-changein="subtotal">{{ $order->subtotal }}</td>
                @elseif ( $order->received_amount == 0  )
                <td data-changein="subtotal">{{ $order->subtotal }}</td>
                @endif
                
                <td style="color: red" data-changein="subtotal">{{ $order->sell_price }}</td>
                
                <!--Received Amount-->
                
                @if ( $order->amount_left <= 0  )
                <td style="color: #2ECC71" data-changein="received_amount">{{ $order->received_amount }}</td>
                @elseif ( $order->received_amount == 0 && $order->subtotal != $order->received_amount )
                <td style="color: red" data-changein="received_amount">{{ $order->received_amount }}</td>
                @elseif ( $order->received_amount < $order->subtotal && $order->amount_left > 0 )
                <td style="color: #CC9A2E" data-changein="received_amount">{{ $order->received_amount }}</td>
                @elseif ( $order->received_amount > $order->subtotal && $order->amount_left > 0 )
                <td style="color: #28B463" data-changein="received_amount">{{ $order->received_amount }}</td>
                @elseif ( $order->received_amount == $order->subtotal && $order->amount_left > 0 )
                <td style="color: #CC9A2E" data-changein="received_amount">{{ $order->received_amount }}</td>
                @elseif ( $order->received_amount > 0 && $order->subtotal < 0 && $order->amount_left > 0 )
                <td style="color: #2ECC71" data-changein="received_amount">{{ $order->received_amount }}</td>
               
                @endif
                
                <td>{{ $order->discount }}</td>
                
                    <!--Balance-->
                
                @if ($order->amount_left > 0)
                <td style="color: red">{{ $order->amount_left }}</td>
                @endif
                @if ($order->amount_left <= 0)
                <td style="color: #2ECC71">{{ $order->amount_left }}</td>
                @endif
                <td>{{ $order->advance }}</td>
                
                <!--Benefit-->
                     
                @if(Auth::user()->role < 3)
                @if( $order->received_amount >= $order->subtotal && $order->amount_left <= 0 )
                <td style= "color: #2ECC71">{{ $order->a_benefit }}</td>
                @elseif( $order->received_amount >= $order->subtotal && $order->amount_left > 0 )
                <td style= "color: #2ECC71">{{ $order->a_benefit }}</td>
                @elseif( $order->subtotal == $order->received_amount && $order->amount_left > 0 )
                <td style= "color: #CC9A2E">{{ $order->a_benefit }}</td>
                @elseif( $order->subtotal >= $order->received_amount && $order->amount_left > 0 )
                <td style= "color: #CC9A2E">{{ $order->a_benefit }}</td>
                @elseif( $order->subtotal <0 )
                <td style= "color: red">{{ $order->a_benefit }}</td>
                @elseif( $order->amount == $order->received_amount && $order->amount_left <= 0 )
                <td style= "color: #2ECC71">{{ $order->a_benefit }}</td>
                 @elseif( $order->amount =! $order->received_amount && $order->amount_left > 0 )
                <td style= "color: #2ECC71">{{ $order->a_benefit }}</td>
                 @elseif( $order->amount != $order->received_amount && $order->amount_left <= 0 )
                <td style= "color: #2ECC71">{{ $order->a_benefit }}</td>
                
                @elseif( $order->received_amount <= 0 )
                <td style= "color: red">{{ $order->a_benefit }}</td>
                @endif
                @endif
                <td>{{ $order->c_benefit }}</td>
                
                <!--Order Status-->
                 <td>{{ $order->comments }}</td>
                 <td> {{ $order->approve_date }}</td>
                <td>{{ $order->created_at }}</td>
                <td>
                  <span style="display: none;" class="is-approved" id="{{ $order->is_approved }}"></span>
                  <a href="javascript:;" data-toggle="modal" data-target="#order-detail-popup" class="btn btn-sm btn-success view-details" id="{{ $order->id }}"><i class="fa fa-eye"></i> Order Detail</a>
                <!--   @if(Auth::user()->role < 3)
                  <input type="checkbox" data-id="{{ $order->id }}" name="status" class="js-switch" {{ $order->i_status == 1 ? 'checked' : '' }}>
                  @endif -->
                  
                 
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted">Total: <b>{{ $orders->sum('amount') }}</b> | Sub Total: <b>{{ $orders->sum('subtotal') }}</b> | Balance: <b>{{ $orders->sum('amount_left') }}</b> | Rec Amount: <b>{{ $orders->sum('received_amount') }}</b>@if(Auth::user()->role < 3) | A Ben: <b>{{ $orders->sum('a_benefit') }}</b>@endif | C Ben: <b>{{ $orders->sum('c_benefit') }}</b> | Advance: <b>{{ $orders->sum('advance') }}</b> | Units: <b>{{ $orders->sum('unit') }}</b>
      </div>
      {{ $orders->links()}}
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
                <th>Amount</th>
                <th>T.Amount</th>
                
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
                    <td>{{ $preport['tamount'] }}</td>
                    <?php $show_in_modal += $preport['amount']; ?>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @if(Auth::user()->role < 3)
      <div class="alert alert-info text-left pull-left">
          <b>P.Total: </b>{{ $show_in_modal }}
      </div>
      @endif
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
        <h5 class="modal-title" style="width: 100%" id="exampleModalLabel">Order Detail <small></small></h5>
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
@endsection
@push('scripts')
<script type="text/javascript">
  $('.view-details').click(function(){
    var param = $(this).attr('id');
    console.log(param);
    $('#order-detail-popup .modal-title small').text('(' + $(this).closest('tr').find('input').val() + ')');
    $('#order-detail-popup .modal-body').html('<h6 class="text-center">Loading ..</h6>');
    $.get('{{ route("order.detail") }}/' + param , function(success){
      $('#order-detail-popup .modal-body').html(success);
    });
  });
  // swith controll

  
</script>
@endpush
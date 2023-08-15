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
<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="#">Orders</a>
  </li>
  <li class="breadcrumb-item active">Customer Orders</li>
</ol>
<div class="row">
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> <b>{{ $customer->user->name }}</b> Orders List
        <div class="col-md-10 pull-right">
            <form method="post" action="{{ route('date.filter') }}">
                {{ csrf_field() }}
                <input type="hidden" name="customer_id" value="{{ $customer->id }}" class="form-control" />
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
                        <button class="btn btn-success btn-sm">Search</button></div>
                   
                </div>
            </form>
        </div>
        </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr class="header" id="myHeader">
                 <th>Total Orders</th>
                <th>Total</th>
                <th>Sub Total</th>
                <th>Recieved</th>
                <th>Balance</th>
                <th>Order Approve Date</th>
                
              </tr>
            </thead>
            <tbody>
                @foreach ($orde as $key => $d) 
              <tr>
                  
                  <td>
                  <span class="btn btn-success pull-right"  onclick="findOrder('all' , '{{ $unique_date[$key] }}');">{{ $d->count('id') }} Total Orders </span>
                  <span class="btn btn-primary pull-right" onclick="findOrder('unstock' , '{{ $unique_date[$key] }}');">    {{ $get_stock_ord[$key] }} Unstock Orders </span>
                  @if( $get_verified_ord[$key] != 0 )
                  <a class="btn btn-warning pull-right" onclick="findOrder('un_verified' , '{{ $unique_date[$key] }}');" target="_blank" >    {{ $get_verified_ord[$key] }}<i class="fa fa-warning"></i> Un verfied Orders </a>
                  <span class="btn btn-danger pull-right" onclick="verifyOrder('verify' , '{{ $unique_date[$key] }}');"><i class="fa fa-danger"></i> Click Verify These {{ $get_verified_ord[$key] }} Orders </span>
                 
                  @endif
                  </td>
                  <td>{{ $d->sum('amount') }}</td>
                  <td>{{ $d->sum('subtotal') }}</td>
                  <td>{{ $d->sum('received_amount') }}</td>
                  <td>{{ $d->sum('subtotal')  - $d->sum('received_amount') }}</td>
                  <td>{{ $unique_date[$key] }}</td>
                  
              </tr>
              @endforeach 
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted">
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
      <div class="alert alert-info text-left pull-left">
          <b>P.Total: </b>{{ $show_in_modal }}
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
@section('scripts')
<script type="text/javascript">
  $('.view-details').click(function(){
    var param = $(this).attr('id');
    @if(Auth::user()->role < 3){
        if($(this).closest('tr').find('.is-approved') != 1){
        $('.approve-btn').hide();
      }
      else{
        $('.approve-btn').show();
        $('.approve-btn').attr('href' , "{{ route('approve.invoice') }}/" + param);
      }
    }
    @endif
    $('#order-detail-popup .modal-title small').text('(' + $(this).closest('tr').find('input').val() + ')');
    $('#order-detail-popup .modal-body').html('<h6 class="text-center">Loading ..</h6>');
    $.get('{{ route("order.detail") }}/' + param , function(success){
      $('#order-detail-popup .modal-body').html(success);
    });
  });
  // swith controll
  let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

elems.forEach(function(html) {
    let switchery = new Switchery(html,  { size: 'small' });
});
    function findOrder(type , date){
        console.log(type , date);
        var url = "{{ route('get.confirmed.order') }}/"  + type + "/" + date;
        console.log(url);
        document.location.href=url;
       
 
    }
     function verifyOrder(type , date){

       $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('verify.order') }}',
            data: {'date': date},
            success: function (data) {
            console.log(data.message);
            }
        });
    
 
    }
  
</script>
@endsection
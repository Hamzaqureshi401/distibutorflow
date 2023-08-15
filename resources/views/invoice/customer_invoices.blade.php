@extends('layouts.app')
@section('title') Customer Invoices @endsection
@section('content')
<?php error_reporting(0) ?>
<div class="page-header card">
   <div class="card-block">
      <h5 class="m-b-10 text-center">Customer Invoices</h5>
      <p class="text-muted m-b-10 text-center">Customer Invoices Details</p>
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
      @if(Auth::user()->role < 3 || Auth::user()->role == 5)
      <a href="{{ route('edit.customer' , $customer->id) }}" class="btn btn-sm btn-primary "><i class="fa fa-edit"> Edit Customer</i></a>
      <a href="{{ route('customer.orders' , $customer->id) }}" class="btn btn-sm btn-primary "><i class="fa fa-edit"> Customer Orders</i></a>
      @endif
      @php
      $now = \Carbon\Carbon::now()->toDateString();
      $a = strtotime($invoices->pluck('created_at')->first());
      $b = strtotime($now);
      $days_between = ceil(($b - $a) / 86400);
      @endphp
      <button class="btn btn-sm btn-info "><i class="ti-shopping-cart-full">
      Last order
      {{ $days_between }} Days Before At Date
      {{ $invoices->pluck('created_at')->first() }}</i></button>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card mb-3">
         <div class="card-header">
            <i class="fa fa-table"></i> <b>{{ $customer->user->name }}</b> Invoices List
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
                        <button class="btn btn-success btn-sm">Search</button>
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
                        <th>Total</th>
                        <th>Sub Total</th>
                        <th>Recieved</th>
                        <th>Balance</th>
                        <th>Discount</th>
                        @if(Auth::user()->role < 3)
                        <th>A Benefit</th>
                        @endif
                        <th>Comments</th>
                        <th>Approve Date</th>
                        <th>Created At</th>
                        <th>Action/Status</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($invoices as $invoice)              
                     <tr>
                        <input type="hidden" name="" value="{{ $invoice->created_at->diffForHumans() }}">
                        <td>{{ $loop->index + 1 }}</td>
                        <!--Customer Name-->
                        <!--Total-->
                        @if ( $invoice->received_amount < $invoice->subtotal  )
                        <td style="color: red" data-changein="subtotal">{{ $invoice->amount }}</td>
                        @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left > 0  )
                        <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->amount }}</td>
                        @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left <= 0  )
                        <td style="color: #28B463" data-changein="subtotal">{{ $invoice->amount }}</td>
                        @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left <= 0 )
                        <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->amount }}</td>
                        @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                        <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->amount }}</td>
                        @elseif ( $invoice->received_amount == 0  )
                        <td data-changein="subtotal">{{ $invoice->amount }}</td>
                        @endif
                        <!--Subtotal-->
                        @if ( $invoice->received_amount < $invoice->subtotal  )
                        <td style="color: red" data-changein="subtotal">{{ $invoice->subtotal }}</td>
                        @elseif ( $invoice->received_amount > $invoice->subtotal  )
                        <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->subtotal }}</td>
                        @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left <= 0 )
                        <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->subtotal }}</td>
                        @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                        <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->subtotal }}</td>
                        @elseif ( $invoice->received_amount == 0  )
                        <td data-changein="subtotal">{{ $invoice->subtotal }}</td>
                        @endif
                        <!--Received Amount-->
                        @if ( $invoice->amount_left <= 0  )
                        <td style="color: #2ECC71" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                        @elseif ( $invoice->received_amount == 0 && $invoice->subtotal != $invoice->received_amount )
                        <td style="color: red" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                        @elseif ( $invoice->received_amount < $invoice->subtotal && $invoice->amount_left > 0 )
                        <td style="color: #CC9A2E" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                        @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left > 0 )
                        <td style="color: #28B463" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                        @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                        <td style="color: #CC9A2E" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                        @elseif ( $invoice->received_amount > 0 && $invoice->subtotal < 0 && $invoice->amount_left > 0 )
                        <td style="color: #2ECC71" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                        @endif
                        <!--Balance-->
                        @if ($invoice->amount_left > 0)
                        <td style="color: red">{{ $invoice->amount_left }}</td>
                        @endif
                        @if ($invoice->amount_left <= 0)
                        <td style="color: #2ECC71">{{ $invoice->amount_left }}</td>
                        @endif
                        <td>{{ $invoice->discount }}</td>
                        <!--Benefit-->
                        @if(Auth::user()->role < 3)
                        @if( $invoice->received_amount >= $invoice->subtotal && $invoice->amount_left <= 0 )
                        <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                        @elseif( $invoice->received_amount >= $invoice->subtotal && $invoice->amount_left > 0 )
                        <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                        @elseif( $invoice->subtotal == $invoice->received_amount && $invoice->amount_left > 0 )
                        <td style= "color: #CC9A2E">{{ $invoice->a_benefit }}</td>
                        @elseif( $invoice->subtotal >= $invoice->received_amount && $invoice->amount_left > 0 )
                        <td style= "color: #CC9A2E">{{ $invoice->a_benefit }}</td>
                        @elseif( $invoice->subtotal <0 )
                        <td style= "color: red">{{ $invoice->a_benefit }}</td>
                        @elseif( $invoice->amount == $invoice->received_amount && $invoice->amount_left <= 0 )
                        <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                        @elseif( $invoice->amount =! $invoice->received_amount && $invoice->amount_left > 0 )
                        <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                        @elseif( $invoice->amount != $invoice->received_amount && $invoice->amount_left <= 0 )
                        <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                        @elseif( $invoice->received_amount <= 0 )
                        <td style= "color: red">{{ $invoice->a_benefit }}</td>
                        @endif
                        @endif
                        <!--Invoice Status-->
                        <td>{{ $invoice->comments ?? '--' }}</td>
                        <td>{{ $invoice->approve_date ?? '--' }}</td>
                        <td>{{ $invoice->created_at }}</td>
                        <td>
                           <span style="display: none;" class="is-approved" id="{{ $invoice->is_approved }}"></span>
                           <a href="javascript:;" data-toggle="modal" data-target="#invoice-detail-popup" class="btn btn-sm btn-success view-details" id="{{ $invoice->id }}"><i class="fa fa-eye"></i>Invoice Details</a>
                           <!--   @if(Auth::user()->role < 3)
                              <input type="checkbox" data-id="{{ $invoice->id }}" name="status" class="js-switch" {{ $invoice->i_status == 1 ? 'checked' : '' }}>
                              @endif -->
                           @if(Auth::user()->role < 3)
                           @if($loop->first)
                           <a href="{{ route('edit.invoice' , $invoice->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit Invoice</a>
                           <a href="{{ route('delete.invoice' , $invoice->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i> Delete Invoice</a>
                           <a href="{{ route('set.invoice.zero' , $invoice->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-warning">Set 0 Balance</i></a>
                           @endif
                           @elseif(Auth::user()->role == 3 && $invoice->is_approved == null)
                           @if($loop->first)
                           <a href="{{ route('edit.invoice' , $invoice->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit Invoice</a>
                           <a href="{{ route('delete.invoice' , $invoice->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i> Delete Invoice</a>
                           @endif
                           @endif
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
         <div class="card-footer small text-muted">Total: <b>{{ $invoices->sum('amount') }}</b> | Sub Total: <b>{{ $invoices->sum('subtotal') }}</b> | Balance: <b>{{ $invoices->sum('amount_left') }}</b> | Rec Amount: <b>{{ $invoices->sum('received_amount') }}</b>@if(Auth::user()->role < 3) | A Ben: <b>{{ $invoices->sum('a_benefit') }}</b>@endif | C Ben: <b>{{ $invoices->sum('c_benefit') }}</b> | Advance: <b>{{ $invoices->sum('advance') }}</b> | Units: <b>{{ $invoices->sum('unit') }}</b>
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
<div class="modal fade" id="invoice-detail-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" style="width: 100%" id="exampleModalLabel">Invoice Detail <small></small></h5>
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
     $('#invoice-detail-popup .modal-title small').text('(' + $(this).closest('tr').find('input').val() + ')');
     $('#invoice-detail-popup .modal-body').html('<h6 class="text-center">Loading ..</h6>');
     $.get('{{ route("invoice.detail") }}/' + param , function(success){
       $('#invoice-detail-popup .modal-body').html(success);
     });
   });
   // swith controll
   let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
   
   elems.forEach(function(html) {
     let switchery = new Switchery(html,  { size: 'small' });
   });
   $(document).ready(function(){
     $(document).on('change', '.js-switch', function () {
         let i_status = $(this).prop('checked') === true ? 1 : 0;
         console.log(i_status);
         let userId = $(this).data('id');
         $.ajax({
             type: "GET",
             dataType: "json",
             url: '{{ route('invoice.update.status') }}',
             data: {'i_status': i_status, 'user_id': userId},
             success: function (data) {
                 console.log(data.message);
             }
         });
     });
   });
   
</script>
@endpush
@extends('layouts.app')
@section('title') Dated Invoices @endsection
@section('content')
<?php error_reporting(0) ?>
<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="#">Invoices</a>
  </li>
  <li class="breadcrumb-item active">Approved Invoices</li>
</ol>
<div class="row">
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Invoices List
        <div>
                   <select class="form-control" id="aioConceptName" data-placeholder="sorted list...">
                     <option value="">Sorted List</option>
                        @foreach ($available_dates as  $ar)
                          <option>{{ $ar }}</option>
                        @endforeach
                        </select>
                        <a onclick="selecteddate();" class="btn btn-sm btn-primary">Show Invoices<br></a>
                   
                    </div>
        <div class="col-md-8 pull-right">
            <form method="post" action="{{ route('date.filter') }}">
                {{ csrf_field() }}
                <div class="row">
                
                    <div class="col-md-2 text-right">
                        <b>From</b>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="from" value="{{ date('d-m-Y') }}" class="form-control" />
                    </div>
                    <div class="col-md-2 text-right">
                        <b>To</b>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="to" value="{{ date('d-m-Y') }}" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success btn-sm">Search</button>
                    </div>
                    
                </div>
                
            </form>
        </div>
        </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th"  width="100%" cellspacing="0">
            <thead>
              <tr class="header" id="myHeader">
                <th>#</th>
                <th>Customer</th>
                <th>Approve Date</th>
                <th>P.Amount</th>
                <th>Units</th>
                <th>Total</th>
                <th>Subtotal</th>
                <th>Recieved</th>
                <th>Balance</th>
                <th>Advance</th>
                @if(Auth::user()->role < 3)
                <th>A Benefit</th>
                @endif
                <th>Discount</th>
                <th>C Benefit</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($invoices as $invoice)              
              <tr>
                <input type="hidden" name="" value="{{ $invoice->created_at->diffForHumans() }}">
                <td>{{ $invoice->id }}</td>
                 @if ( $invoice->received_amount < $invoice->subtotal  )
                <td style="color: red" data-changein="subtotal">{{ $invoice->customer->user->name }}</td>
                @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left > 0  )
                <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->customer->user->name }}</td>
                @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left <= 0  )
                <td style="color: #28B463" data-changein="subtotal">{{ $invoice->customer->user->name }}</td>
                @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left <= 0 )
                <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->customer->user->name }}</td>
                @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->customer->user->name }}</td>
                @elseif ( $invoice->received_amount == 0  )
                <td data-changein="subtotal">{{ $invoice->customer->user->name }}</td>
                @endif
                
                <td data-changein="">{{ $invoice->approve_date }}</td>
                
                
                <td data-changein="subtotal">{{ $invoice->p_amount }}
                @if($invoice->p_amount  != $invoice->totalProductPrice())
                |{{ $invoice->totalProductPrice() }}
                @endif
                </td>
                <!--Unit-->
                
                    @if ( $invoice->received_amount < $invoice->subtotal  )
                <td style="color: red" data-changein="subtotal">{{ $invoice->unit }}</td>
                @elseif ( $invoice->received_amount > $invoice->subtotal  )
                <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->unit }}</td>
                @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left <= 0 )
                <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->unit }}</td>
                @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->unit }}</td>
                @elseif ( $invoice->received_amount == 0  )
                <td data-changein="subtotal">{{ $invoice->unit }}</td>
                @endif
                
                
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
                <td>{{ $invoice->advance }}</td>
                
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
                <td>{{ $invoice->discount }}</td>
                <td>{{ $invoice->c_benefit }}</td>
                <td>{{ $invoice->created_at }}</td>
                <td>
                  <a href="javascript:;" data-toggle="modal" data-target="#invoice-detail-popup" class="btn btn-sm btn-success view-details" id="{{ $invoice->id }}"><i class="fa fa-eye"></i></a>
                  @if(Auth::user()->role < 3)
                  <a href="{{ route('print.invoice' , $invoice->id) }}" class="btn btn-sm btn-info"><i class="fa fa-print"></i></a>
                  @elseif(Auth::user()->role == 3 && $invoice->is_approved == null)
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      {{ $invoices->links("pagination::bootstrap-4") }}
      <div class="header" id="myHeader" class="card-footer small text-muted">Total: <b>{{ $invoices->sum('amount') }}</b> | P amount: <b>{{ $invoices->sum('p_amount') }}</b> | Sub Total: <b>{{ $invoices->sum('subtotal') }}</b> | Discount: <b>{{ $invoices->sum('discount') }}</b> | Balance: <b>{{ $invoices->sum('amount_left') }}</b> | Rec Amount: <b>{{ $invoices->sum('received_amount') }}</b> @if(Auth::user()->role < 3) | A Ben: <b>{{ $invoices->sum('a_benefit') }}</b> @endif| C Ben: <b>{{ $invoices->sum('c_benefit') }}</b> | Advance: <b>{{ $invoices->sum('advance') }}</b> | Units: <b>{{ $invoices->sum('unit') }}</b>
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
                    <td>{{ $preport['amount'] }}</td>
                     <td>{{ $preport['invoices_sum_p_amount'] }}</td>
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
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
  $('.view-details').click(function(){
    var param = $(this).attr('id');
    $('#invoice-detail-popup .modal-title small').text('(' + $(this).closest('tr').find('input').val() + ')');
    $('#invoice-detail-popup .modal-body').html('<h6 class="text-center">Loading ..</h6>');
    $.get('{{ route("invoice.detail") }}/' + param , function(success){
      $('#invoice-detail-popup .modal-body').html(success);
    });
  });
   function selecteddate(){
       var conceptName = $('#aioConceptName').find(":selected").text();
        var url = "{{ route('dated.invoice') }}/"  + conceptName ;
        document.location.href=url;
    }
</script>
@endpush
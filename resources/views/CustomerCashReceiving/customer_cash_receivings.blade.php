@extends('layouts.app')
@section('content')
<!-- Main-body start -->
      <!-- Page-header start -->
      @if($exppayment->sum('id') > 0)
@include('CustomerCashReceiving.unconfirmed_expences')
@endif
      <!-- Page-header end -->
         <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Cash Receiving Invoices</h5>
            <p class="text-muted m-b-10 text-center">Cash Add History</p>
            <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <button class="btn btn-sm btn-primary cash-receivings" data-toggle="modal" data-target="#cash-receivings">Add Payment</button>
               </li>
               @if (Auth::user()->role != 4)
                <li class="breadcrumb-item">
                  <button class="btn btn-sm btn-primary add-admin-payment" data-toggle="modal" data-target="#add-admin-payment">Add Payment To Admin</button>
               </li>
               @endif

            </ul>
         </div>
      </div>
      <div class="card">
         <div class="card-header">
            <!--  <h5>Hover table</h5>
               <span>use class <code>table-hover</code> inside table element</span> -->
            <div class="card-header-right">
               <ul class="list-unstyled card-option">
                  <li><i class="fa fa-chevron-left"></i></li>
                  <li><i class="fa fa-window-maximize full-card"></i></li>
                  <li><i class="fa fa-minus minimize-card"></i></li>
                  <li><i class="fa fa-refresh reload-card"></i></li>
                  <li><i class="fa fa-times close-card"></i></li>
               </ul>
            </div>
         </div>
         <div class="card-block table-border-style">
            <div class="table-responsive">
               <table class="table table-hover table-datatable">
                  <thead>
                     <tr>
                     <th class="text-center">Old Cash Remaining</th> 
                     <th class="text-center">Cash Paid Or Added</th> 
                     <th class="text-center">Current Cash Remaining</th> 
                     <th class="text-center">Out Side Payments</th> 
                     <th class="text-center">Expenses</th> 
                     <th class="text-center">Discounts</th> 
                     <th class="text-center">Comments</th> 
                     <th class="text-center">Added By</th> 
                     <th class="text-center">Created At/Action</th> 
                     </tr>
                  </thead>
                  <tbody>
                    @if (!empty($receivings))
                     @foreach($receivings as $r)
                    @if ($r->processor_id == Auth::id())
                    <tr class="text-center" style="background-color: red;">
                      @else
                     <tr class="text-center">
                      @endif
                       <td>
                        {{ $r->old_cash_remaining ?? '' }}
                       </td>
                       <td>
                        {{ $r->cash_paid_added }}
                       </td>
                       <td>
                        {{ $r->current_cash_remaining }}
                       </td>
                       <td>
                        {{ $r->outside_payments ?? '--' }}
                       </td>
                       <td>
                        {{ $r->expenses ?? '--' }}
                       </td>
                       <td>
                        {{ $r->discouns ?? '--' }}
                       </td>
                       <td>
                        {{ $r->comments ?? '--' }}
                       </td>
                       <td>
                        @php
                        $id = $r->processor_id ?? Auth::id();
                        @endphp
                        {{ $UserModel->where('id' , $id)->first()->name }}
                       </td>
                       <td>
                        {{ $r->created_at}}
                        <br>
                         @if($r->pos_sell_ids != NULL)
                        <a href="{{ route('Get.Ids.Order' , $r->id) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"> Show Order{{ $r->id }}</i></a>
                        @endif
                       </td>
         

                     </tr>
                     @endforeach
                     @endif
                  </tbody>
               </table>
               @if (!empty($receivings->first()->created_at))
                @php
                  $now = \Carbon\Carbon::now()->toDateString();
                  $a = strtotime($receivings->first()->created_at);
                  $b = strtotime($now);
                  $days_between = ceil(($b - $a) / 86400);
                  @endphp

                  
               <div class="card-footer small text-muted">
                        <b>Total Remain : {{ $receivings->first()->current_cash_remaining }}  
                          | Total Stock Remaing : {{ $purchase + $receivings->whereNull('pos_sell_ids')->sum('cash_paid_added') }}
                        </b>
                        <a href="{{ route('customer.invoices' , $customer_id) }}" class="btn btn-sm btn-success">
                        <b>Last Purchsing : {{ $last_invoice->subtotal }}</b> 
                        <b>Last Paid : {{ $last_invoice->received_amount ?? 0 }}</b> 
                        <b>Amount To Be Paid : {{ $last_invoice->amount_left }}</b> 
                        <b>Last Order : {{ $days_between }} Days Before</b>
                        </a>
                        
                        </div>
            </div>
         </div>
      </div>
      @endif
      <!-- Hover table card end -->
      
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
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="cash-receivings" tabindex="-1" role="dialog" aria-labelledby="pay-ot-popup-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pay Amount Or Add Profit</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" class="pay_form" action="{{ route('Add.Manual.Cash') }}">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Cash Received </label><input class="form-control r-amount" type="number" name="amount" class="form-control" value="0" required="">
            <label style="color: red;">Add Comments <span style=" font-style: italic; color: red;"></span></label>
            <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments" maxlength = "200"></textarea>
          </div>
          <button class="btn btn-secondary btn-block cl">Continue</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add-admin-payment" tabindex="-1" role="dialog" aria-labelledby="pay-ot-popup-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pay Amount Or Add Profit</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" class="pay_form" action="{{ route('Add.Payment.To.Admin') }}">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Payment </label><input class="form-control r-amount" type="number" name="payment" class="form-control" value="0" required="">
            <label>Expenses </label><input class="form-control r-amount" type="number" name="expenses" class="form-control" value="0" required="">
            <label style="color: red;">Add Comments <span style=" font-style: italic; color: red;"></span></label>
            <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments" maxlength = "200"></textarea>
          </div>
          <button class="btn btn-secondary btn-block cl">Continue</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
@include('CheckBoxHandling.check_box')
@endpush
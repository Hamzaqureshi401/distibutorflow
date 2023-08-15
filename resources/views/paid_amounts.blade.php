@extends('layouts.app')
@section('title') Paid History @endsection
@section('content')
<!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Paid History</h5>
            <p class="text-muted m-b-10 text-center">Over All Data</p>
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
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> <b><?php echo empty($subadmin_name) ? '' : $subadmin_name ?></b> Paid History
        <button class="btn btn-primary pull-right" data-target="#pay-popup" data-toggle="modal">Pay Amount</button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th table-datatable table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Previous Remaining</th>
                <th>Paid</th>
                <th>Purchases Added</th>
                <th>Remaining</th>
                <th>Difference</th>
                <th>Profit</th>
                <th>T.invoices</th>
                <th>Comments</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($paid_amounts as $key => $pay_amount)
              @php
               if($loop->index - 1 == -1)
                    $diff = 0;
                    else
                    $diff = $pay_amount->remaining - $paid_amounts[$loop->index - 1]->c_remaining;
                    
              @endphp
              <tr  style="background-color: {{ $diff == 0 ? '' : 'red' }}">
              
                <td>{{ $pay_amount->id }}</td>
                <td>{{ $pay_amount->total_is }}</td>
                <td><p style="font-size:10px; color: #007eff">Previous Remaining</p> {{ $pay_amount->remaining }}</td>
                <td><p style="font-size:10px; color: green">Paid</p>{{ $pay_amount->paid }}</td>
                <td><p style="font-size:10px; color: #59e0c5">Purchase</p>{{ $pay_amount->purchases }}</td>
                <td>{{ $pay_amount->c_remaining}}</td>
                
                <td>
                    {{ $diff }}
                    
                    </td>
                
                <td>{{ $pay_amount->profit }}</td>
                <td>{{ $pay_amount->t_invoices }}</td>
                <td>{{ $pay_amount->comments }}</td>
                <td>
                    <a href="{{ route('dated.invoice' , $pay_amount->created_at) }}" class="btn btn-sm btn-primary">Show Invoices<br>{{ $pay_amount->created_at->format('d, M Y') }}/{{ $pay_amount->created_at }}</a>
                    </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="pay-popup" tabindex="-1" role="dialog" aria-labelledby="pay-popup-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pay Amount</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('pay.amount') }}" onsubmit='disableButton()'>
          {{ csrf_field() }}
          <div class="form-group">
            <input type="number" name="amount" class="form-control" placeholder="Enter Amount">
            <label style="color: red;">Add Comments <span style="opacity: 0.5; font-style: italic; color: red;"></span></label>
            <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments" maxlength = "200"></textarea>
          </div>
          <button id="button" type="submit" class="btn btn-secondary btn-block">Continue</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
  function disableButton() {
        var btn = document.getElementById('button');
        btn.disabled = true;
        btn.innerText = 'Transection Processing Wait'

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

    </script>
@endpush
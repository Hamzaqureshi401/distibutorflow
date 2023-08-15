@extends('layouts.app')
@section('title') Sell Records @endsection
@section('content')
<?php error_reporting(0) ?>
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Sell Purchase & Paid History</h5>
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
        <i class="fa fa-table"></i> <b><?php echo empty($subadmin_name) ? '' : $subadmin_name ?></b> Sell Records
        <button class="btn btn-primary pull-right" data-target="#pay-popup" data-toggle="modal">Pay Amount</button>
        <!-- <a class="btn btn-danger pull-right approve-btn notify-first" href="{{ route('clear.all') }}">Clear All</a> -->
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th table-datatable table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Units / Today</th>
                <th>T.Amount / Today</th>
                <th>P.Amount / Today</th>
                <th>A Ben / Today</th>
                <th>C Ben / Today</th>
              </tr>
            </thead>
            <tbody>
              @foreach($sell_records as $sell_record)
              <?php $indet = App\Models\InvoiceDetail::with(array('invoice' => function($query){
                         $query->where('is_approved', 1);
                        }))->where([['product_id' , '=' , $sell_record->product_id] , ['created_at' , '>=' ,  date('Y-m-d').'00:00:00']])->get(); ?>
              <tr>
                <td>{{ $sell_record->id }}</td>
                <td>{{ $sell_record->product->name }}</td>
                <td>{{ $sell_record->unit }} / {{ $indet->sum('unit') }}</td>
                <td>{{ $sell_record->amount }} / {{ $indet->sum('amount') }}</td>
                <td>{{ $sell_record->p_amount }} / {{ $indet->sum('p_amount') }}</td>
                <td>{{ $sell_record->a_benefit }} / {{ $indet->sum('a_benefit') }}</td>
                <td>{{ $sell_record->c_benefit }} / {{ $indet->sum('c_benefit') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted">
        <div class="row">
          <div class="col-md-12">Total Trade Amount: <b>{{ $sell_records->sum('amount') }}</b> | Total Purchased Amount: <b>{{ $sell_records->sum('p_amount') }}</b> | Paid: <b>{{ Auth::user()->paid->sum('paid') }}</b> | Remaining: <b>{{$sell_records->sum('p_amount') - Auth::user()->paid->sum('paid') }}</b></div>
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
            <input type="number" name="amount" min="0" class="form-control" placeholder="Enter Amount" required>
            <label style="color: red;">Add Comments <span style=" font-style: italic; color: red;"></span></label>
            <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments" maxlength = "200"></textarea>
          </div>
          <button id="button" type="submit" class="btn btn-secondary btn-block">Continue</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
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

    $('.notify-first').click(function(){
        toastr.warning('This Action Clears All Sell & Paid Record');
    });
</script>
@endsection
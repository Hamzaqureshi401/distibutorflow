@extends('layouts.app')
@section('title') OT benefit Paid History @endsection
@section('content')
<!-- Breadcrumbs-->
<div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Payment History</h5>
            <p class="text-muted m-b-10 text-center">Cash Flow Details</p>
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
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Paid History
        <!--<button class="btn btn-primary pull-right" data-target="#pay-popup" data-toggle="modal"></button>-->
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Paid</th>
                @if(Auth::user()->role < 3)
                <th>Remaining</th>
                @endif
                <th>Comments</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($paid_benefits as $pay_amount)
              <tr>
                <td>{{ $pay_amount->id }}</td>
                <td>{{ ($pay_amount->total_is - $pay_amount->paid) }}</td>
                <td>{{ $pay_amount->paid_amount }}</td>
                @if(Auth::user()->role < 3)
                <td>{{ ($pay_amount->total_is - $pay_amount->paid) - $pay_amount->paid_amount }}</td>
                @endif
                <td>{{ $pay_amount->comments }}</td>
                <td>{{ $pay_amount->created_at->format('d, M Y') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!--<div class="modal fade" id="pay-popup" tabindex="-1" role="dialog" aria-labelledby="pay-popup-label" aria-hidden="true">-->
<!--  <div class="modal-dialog" role="document">-->
<!--    <div class="modal-content">-->
<!--      <div class="modal-header">-->
<!--        <h5 class="modal-title">Pay Amount</h5>-->
<!--        <button class="close" type="button" data-dismiss="modal" aria-label="Close">-->
<!--          <span aria-hidden="true">×</span>-->
<!--        </button>-->
<!--      </div>-->
<!--      <div class="modal-body">-->
<!--        <form method="post" action="{{ route('pay.amount') }}">-->
<!--          {{ csrf_field() }}-->
<!--          <div class="form-group">-->
<!--            <input type="number" name="amount" min="0" class="form-control">-->
<!--          </div>-->
<!--          <button class="btn btn-secondary btn-block">Continue</button>-->
<!--        </form>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
@endsection
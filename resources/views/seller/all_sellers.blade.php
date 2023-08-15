@extends('layouts.app')
@section('title') All Sellers @endsection
@section('content')
<!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">All Seller</h5>
            <p class="text-muted m-b-10 text-center">Sellers List</p>
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
        <i class="fa fa-table"></i> <b><?php echo empty($subadmin_name) ? '' : $subadmin_name ?></b> Sellers List</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th table-datatable" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Name</th>
                <th>email</th>
                <th>phone</th>
                <th>Allow.P.Prft</th>
                <th>Allow.D.prft</th>
                
                <th>T.Inv/Ord</th>
                <th>T.Sells</th>
                <th>T.P.Benefit</th>
                <th>T.D.Benefit</th>
                <th>T.Paid</th>
                <th>T.Remain</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($sellers as $key => $seller)
              <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $seller->email }}</td>
                <td>{{ $seller->phone }}</td>
                <td>{{ $seller->seller_data->process_order_profit ?? '0' }}</td>
                <td>{{ $seller->seller_data->delivered_order_profit ?? '0' }}</td>
                <td>{{ sizeof($seller->invoices) }}/{{ $orders[$key ] }}</td>
                <td>{{ $seller->invoices->sum('amount') }}</td>
                <td>{{ $seller->seller_data->total_prosess_benefit ?? '0' }}</td>
                <td>{{ $seller->seller_data->total_delivered_benefit ?? '0'}}</td>
                <td>{{ $seller->sellerPaiddata->sum('paid') }}</td>
                @if (!empty($seller->seller_data->total_delivered_benefit))
                <td>{{ ($seller->seller_data->total_prosess_benefit + $seller->seller_data->total_delivered_benefit) - $seller->sellerPaiddata->sum('paid')  }}</td>
                @else
                <td>0</td>
                @endif
                @if(Auth::user()->role < 3)
                <td>
                  <a href="{{ route('sellerpaid.history', $seller->id) }}" class="btn btn-dark btn-sm">History</a>
                  <a href="{{ route('view.seller.orders.processing' , $seller->id) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Order Processign History</a>
                  <button class="btn btn-sm btn-primary pay_button" data-route="{{ route('pay.seller.amount',$seller->id) }}" >Pay</button>
                <!--  <button class="btn btn-sm btn-primary cash_processing" data-route="{{ route('set.cash.processing',$seller->id) }}" data-id="{{ $seller->id }}">Add Cash Processing</button> 
                  <a href="{{ route('view.seller.sells' , $seller->id) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> view sells</a>-->
                  <a href="{{ route('view.seller.orders' , $seller->id) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View Orders</a>
                  <a href="{{ route('edit.seller' , $seller->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"> Edit Seller</i></a>
                  <a href="{{ route('get.Employee.Sellary' , $seller->id) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Sellary Record</a>
                    <a href="{{ route('Get.Attendence.Record' , $seller->id) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Attandence Record</a>
                    
                  <a href="{{ route('delete.seller' , $seller->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"> Delete Seller</i></a>                  
                </td>
                @else 
                <td>
                    <a href="{{ route('view.seller.orders.processing' , $seller->id) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Order Processign History</a>
                    <button class="btn btn-sm btn-primary cash_processing" data-route="{{ route('set.cash.processing',$seller->id) }}">Add Cash Processing</button>
                  
                </td>
                @endif
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="pay-ot-popup" tabindex="-1" role="dialog" aria-labelledby="pay-ot-popup-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pay Amount Or Add Profit</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" class="pay_form" action="">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Pay Amount </label><input class="form-control r-amount" type="number" name="amount" class="form-control" value="0" required="">
            <label style="color: red;">Add Comments <span style=" font-style: italic; color: red;"></span></label>
            <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments" maxlength = "200"></textarea>
          </div>
          <input type="hidden" name="seller" id="seller-id">
          <button class="btn btn-secondary btn-block cl">Continue</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- <div class="modal fade" id="cash_processing" tabindex="-1" role="dialog" aria-labelledby="cash_processing-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Cash Processing</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" class="submit_cash_processing" action="">
          {{ csrf_field() }}
          <div class="form-group">
            @if(Auth::user()->role < 3)
            <label>Pay Amount </label><input class="form-control r-amount" type="number" name="cash_processing" class="form-control" value="0" required="">
            @endif
            <label>Add Expense</label><input class="form-control r-amount" type="number" name="expenses" class="form-control" value="0" required="">
            <label style="color: red;">Add Comments <span style=" font-style: italic; color: red;"></span></label>
            <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments" maxlength = "200"></textarea>
          </div>
          <button class="btn btn-secondary btn-block">Continue</button>
        </form>
      </div>
    </div>
  </div>
</div> -->
@endsection
@push('scripts')
<script type="text/javascript">
  $('.pay_button').on('click',function(e){
    e.preventDefault();
    //console.log('yes');
    $('#pay-ot-popup').modal('show');
    $('.pay_form').attr('action', $(this).data('route'));
    
  });
  $('.cl').on('click',function(){

    $('#pay-ot-popup .close').click();
    });
</script>
@endpush
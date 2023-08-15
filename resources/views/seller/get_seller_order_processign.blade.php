@extends('layouts.app')
@section('title') Seller Order Processing @endsection
@section('content')
<!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Seller Processed Orders</h5>
            <p class="text-muted m-b-10 text-center">Seller Work History</p>
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
                <button class="btn btn-sm btn-primary cash_processing pull-left" id = "seller" value="{{ $id }}">Add Expence</button>
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
          <table class="table table-bordered table-custom-th table-datatable table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Confirm By</th>
                <th>Old Cash</th>
                <th>Subtotal</th>
                <th>Discount</th>
                <th>Cash Paid / Add</th>
                <th>Cash Remaining</th>
                <th>Expence</th>
                <th>Total Orders</th>
                <th>Comments</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders_processign_history as $processing)
              <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $processing->admin->name }}</td>
                <td>{{ $processing->old_cash_remaining }}</td>
                <td>{{ $processing->subtotal }}</td>
                <td>{{ $processing->discount }}</td>
                <td>{{ $processing->cash_paid_or_added }}</td>
                <td>{{ $processing->current_cash_remaining }}</td>
                <td>{{ $processing->expenses }}</td>
                <td>{{ $processing->no_of_orders }}</td>
                <td>{{ $processing->comments }}</td>
                <td>{{ $processing->created_at->format('d, M Y') }}
                <br>
                <div class = "hid">
                  @if (Auth::user()->role < 3 && $processing->unconfirmed_expences == 0)
                    <a onclick="SellerExpnceProcessing({{ $processing->id }}); remvebtn(this);" class="btn btn-sm btn-warning"><i class="fa fa-warning"></i> Confirm Expence</a>
                    <a onclick="DeleteExpnce({{ $processing->id }}); remvebtn(this);" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete Expence</a>
                  @elseif (Auth::user()->role == 3 && $processing->unconfirmed_expences == 0)
                    <a class="btn btn-sm btn-warning"><i class="fa fa-confirm"></i> Expence Not Confirmed Yet</a>
                    <a onclick="DeleteExpnce({{ $processing->id }}); remvebtn(this);" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete Expence</a>
                  @elseif (Auth::user()->role <= 3 && $processing->order_ids != 0)
                    <a onclick="ShowProcessedorders({{ $processing->id }})" class="btn btn-sm btn-success"><i></i> Show Orders</a>
                @endif
                </div>
                </td>
              
              </tr>
              @endforeach
            </tbody>

          </table>
          <div class="pagination">
                      {{ $orders_processign_history->links() }}

          </div>
        </div>
        <br>
        <br>
         <div class="header" id="myHeader" class="card-footer small text-muted">Total Expence: {{ $sum_expences }}
       </b>
      |Week Expence: <b>{{ $week_expence }}
       </b>
      |Month Expence: <b>{{ $month_expence }}
       </b>
      |Tody Expence: <b>{{ $today_expences }}
      </b> 
      |Discount: <b>{{ $sum_discount }}
      </b> 
      | Balance: <b class="amount_left">{{ $balance }}
      </b> 
      @if(Auth::user()->role < 3) 
      | A Ben: <b>{{ $sum_total_profit }}
      </b>  
      | Week Ben: <b>{{ $sum_week_profit }}
      </b>
       | Month Ben: <b>{{ $sum_month_profit }}
      </b> 
      
      | Sub Total: <b class="subtotal">{{ $sum_subtotal }}
      </b> 
      | Rec Amount: <b class="received_amount">{{ $sum_receiving }}
      </b> 
 
      @endif
     </div>
      </div>
     
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
 
function SellerExpnceProcessing (order_id){
     
     $('.hid').addClass('d-none');
     
     $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('confirm.expnce') }}',
            data: {order_id , order_id}, 
            success: function (data) {
                 $('.hid').removeClass('d-none');
            toastr.success(data.message);
            }
        });
    }
function DeleteExpnce (order_id){
    $('.hid').addClass('d-none');
     
     $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('delete.expnce') }}',
            data: {order_id , order_id}, 
            success: function (data) {
                $('.hid').removeClass('d-none');
            toastr.success(data.message);
            }
        });
    }
    function ShowProcessedorders (order_id){
 
        var order_id= order_id;
        console.log(order_id);
        var url =  "{{ route('show.processed.orders') }}/"  + order_id ;
        
        document.location.href=url;

    }
    console.log(@json($orders_processign_history->where('current_cash_remaining' , "!=" , 0)->pluck('current_cash_remaining')->first()));
    
var old_cash = @json($orders_processign_history->where('current_cash_remaining' , "!=" , 0)->pluck('current_cash_remaining')->first());
    
</script>
@endpush
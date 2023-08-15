@extends('layouts.app')
@section('title') All OrderTaker @endsection
@section('content')
<!-- Breadcrumbs-->

<div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">@if(Auth::user()->role != 5)All Order Takers @else My Details @endif</h5>
            <p class="text-muted m-b-10 text-center">@if(Auth::user()->role != 5)Order Takers Details @else Work History @endif</p>
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
        <i class="fa fa-table"></i> Order Takers @if(Auth::user()->role != 5)List @endif</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th table-hover table-datatable" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                <th>ID</th>
                <th class="text-center">Name</th>
                <th>email</th>
                <th>phone</th>
                <th>T.Orders</th>
                <th>Tdy.Ord.Prft</th>
                <th>Tdy.Vst.Prft</th>
                <th class="text-center">Total Vst</th>
                <th>T.Sell</th>
                <th>Discount Allowed</th>
               @if(Auth::user()->role != 5)
                <th>A.earned</th>
                @endif
                <th>Ben.earned</th>
                <th>Ben.Paid</th>
                <th>Ben.Remain</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
                
              @foreach($ordertaker as $key => $ot )
                            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-center"><b>Name: {{ $ot->name }}</b><br>
                <b>Distance.Allowed: {{ $ot->compare_ot_distance }}</b><br>
                </td>
                <td><b>Email</b><br>{{ $ot->email }}</td>
                <td><b>Phone</b><br>{{ $ot->phone }}</td>
                <td><b>T.ord</b><br>{{ sizeof($ot->orders) }}</td>
                <td><b>Tdy.Ord.Prft</b><br>{{ $ot->ordertaker->today_ord_profit ?? '0'  }}</td>
                <td><b>Tdy.Vst.Prft</b><br>{{ $ot->ordertaker->today_visit_profit ?? '0' }}</td>
                <td class="text-center"><b>Total.Vst: {{ isset($sum[$key]) ? ($sum[$key] + $sumbig[$key]) : '0'  }}</b><br>
                <b>Vst.Ord: {{ isset($sum[$key]) ? ($sum[$key]) : '0' }}</b><br>
                <b>Not.Vst.Ord: {{ isset($sumbig[$key]) ? ($sumbig[$key]) : '0' }}</b><br>
                <b>Tdy.Not.Vst Ord: {{ isset($sumtdybig[$key]) ? ($sumtdybig[$key]) : '0' }}</b><br>
                <b>Tdy.Vst.Ord: {{ isset($sumtdyless[$key]) ? ($sumtdyless[$key]) : '0' }}</b><br></td>
                <td><b>T.sell</b><br>{{ $ot->ordertaker->total_subtotal ?? '0' }}</td>
                @if ( $ot->discount_on_off == 0 )
                <td><b>Discount Alloed</b><br>0%</td>
                @elseif ( $ot->discount_on_off == 1 )
                @if( $ot->customer_discount > 1 )
                <td style="color: red;"><b>Discount Alloed</b><br>{{ $ot->customer_discount }}%</td>
                @else
                <td><b>Discount Alloed</b><br>{{ $ot->customer_discount }}%</td>
                @endif
                @endif
                 @if(Auth::user()->role != 5)
                <td><b>A.bem</b><br>{{ $ot->ordertaker->a_ben }}</td>
                @endif
                <td><b>Ot Ben</b><br>{{ $ot->ordertaker->ben_earned }}</td>
                <td><b>Paid</b><br>{{ $ot->ordertaker->ben_paid}}</td>
                <td><b>Remain</b><br>{{ $ot->ordertaker->ben_earned - $ot->ordertaker->ben_paid }}</td>
                <td>
                    <a href="{{ route('ot.paid.history', $ot->id) }}" class="btn btn-dark btn-sm">History</a>
                  @if(Auth::user()->role != 5)
                  <button class="btn btn-sm btn-primary pay_button" data-route="{{ route('pay.ot.amount',$ot->id) }}">Pay</button>
                  <a href="{{ route('edit.ot' , $ot->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>Edit Order Taker</a>
                  <a href="{{ route('ot.orders' , $ot->id) }}" class="btn btn-sm btn-success"><i class="fa fa-edit"></i>Processed Orders List</a>
                  <!-- <a href="{{ route('delete.ot' , $ot->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i>Delete Order Taker</a>  -->
                   
                  @if($ot->is_blocked == 1)
                  <a href="{{ route('unblock.ot' , [$ot->id , 'unblock']) }}" class="btn btn-sm btn-success approve-btn">Unblock</a>
                  @else
                  <a href="{{ route('unblock.ot' , [$ot->id , 'block']) }}" class="btn btn-sm btn-warning approve-btn">block</a>
                  @endif
                 @endif
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
        <form method="post" class="pay_form" action="" onsubmit="disableButton();">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Pay Amount </label><input class="form-control r-amount" type="number" name="amount" class="form-control" value="0" required="">
            <label style="color: red;">Add Comments <span style=" font-style: italic; color: red;"></span></label>
            <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments" maxlength = "200"></textarea>
          </div>
          <button class="btn btn-secondary btn-block call-processing">Continue</button>
        </form>
      </div>
    </div>
  </div>
</div>

  @if(Auth::user()->role < 3)
      <div class="modal fade" id="pin-modal" tabindex="-1" role="dialog" aria-labelledby="pin-modal" aria-hidden="true" style="background: rgba(0,0,0,.9);">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <h5 class="modal-title" id="pin-modal-label" style="color: white">Enter Pin Code</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" action="{{ route('validate.pin') }}" id="validate-pin" onsubmit='disableButton();'>
                <div class="form-group">
                  <input type="number" class="form-control pin">
                </div>
                <button type="submit" class="btn btn-primary btn-block call-processing" id= "apr-btn" style="color: white">Continue</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <button id="p-m-open" style="display: none" data-target="#pin-modal" data-toggle="modal"></button>
      @endif

@endsection
@push('scripts')
<script type="text/javascript">
var a = @json($sum);
console.log(a);
  $('.pay_button').on('click',function(e){
    e.preventDefault();
    console.log('yes');
    $('#pay-ot-popup').modal('show');
    $('.pay_form').attr('action', $(this).data('route'));
  });
  $('.approve-btn').click(function (event) {
           event.preventDefault();
            $('#p-m-open').click();
            path_param = $(this).attr('href');
          });
  $('#validate-pin').on('submit', function (event) {
            event.preventDefault();
            $.post($(this).attr('action'), {
              _token: "{{ csrf_token() }}",
              pin: $('input.pin').val()
            }, function (data) {
              if (data == 1) {
                if (path_param != "") {
                  console.log(path_param);
                  window.location.href = path_param;
                } 
              } else {
                toastr.error(data);
                 var btn = document.getElementById('apr-btn');
                 btn.disabled = false;
                 btn.innerText = '! Wrong Pin Try Again'

              }
            });
          });
</script>
@endpush

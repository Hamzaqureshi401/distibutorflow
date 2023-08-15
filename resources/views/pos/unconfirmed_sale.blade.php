@extends('layouts.app')
@section('content')
<!-- Main-body start -->
      <!-- Page-header start -->
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Stock Sell Invoices</h5>
            <p class="text-muted m-b-10 text-center">Sell History</p>
            @if(Auth::user()->role == 4 || Auth::user()->role == 7)
            <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <button class="btn btn-sm btn-primary check-all">Check All</button>
               </li>

                <button type="button" 
                data-toggle="modal" data-target="#confirm-sale"
                class="btn btn-success btn-sm d-none confirm-btn">Confirm</button>
               <!--  <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li> -->
            </ul>
            @endif
             <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#product-report-popup"><i class="fa fa-eye"> Show Product Report</i></button>
              <div class="card-header">
       
      </div>
         </div>
      </div>
      <!-- Page-header end -->
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
                        <th class="text-center">Records</th> 
                     </tr>
                  </thead>
                  <tbody>

                     @foreach($sales as $pr)
                    
                     <tr class="text-center">
                       <td>
                        <a class="btn btn-sm btn-success show-details" style="color: white;" data-id="{{ $pr->id }}" data-toggle="modal" data-target="#order-detail-popup"><li class="fa fa-eye"> {{ $loop->index + 1 }} Show Details</li></a>
                         @if(Auth::user()->role == 4 || Auth::user()->role == 7)
                         <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" value="{{ $pr->id }}" class="select-check-box" name="select-check-box[]" form="multiple-approve" unchecked="">
                         @endif
             
                        <br>
                        <span><b>Created By</b><br>
                           {{ $pr->User->name }}
                        </span><br>

                        <br>
                        <span><b>Subtotal</b><br>
                           {{ $pr->subtotal }}
                        </span><br>

                        <br>
                        <span><b>Received Amount</b><br>
                           {{ $pr->received_amount }}
                        </span><br>
                        @if($pr->amount_left != 0)
                        <br>
                        <span><b>Balance</b><br>
                           {{ $pr->amount_left }}
                        </span><br>
                        @endif
                        @if($pr->discount != 0)
                        <br>
                        <span><b>Discount</b><br>
                           {{ $pr->discount }}
                        </span><br>
                        @endif
                        <br>
                        <span><b>Comments</b><br>
                           {{ $pr->comments }}
                        </span><br>
                         <span><b>Created At</b><br>
                           {{ $pr->created_at }}
                        </span><br>
                       </td>
                       

                     </tr>
                     @endforeach
                  </tbody>
               </table>
               <div class="card-footer small text-muted">
                        <b>Total Sale : {{ $sales->sum('subtotal')}} </b> 
                        <b>Received Sale : {{ $sales->sum('received_amount')}} </b> 
                        <b>Discounts : {{ $sales->sum('discount')}} </b></div>
            </div>
         </div>
      </div>
      <!-- Hover table card end -->
      <div id="styleSelector">
      </div>
   </div>
</div>
@include('pos.product_report_details')
<div class="modal fade" id="confirm-sale" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
               <div class="modal-content">
                  <div class="modal-header bg-danger">
                     <h5 class="modal-title" id="delete-modal-label" style="color: white">Are You Sure ?</h5>
                     <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">×</span>
                     </button>
                     </div>
                     <div class="modal-body">Are you sure to Confirm these Orders!
                        <br>
                     <form method="post" action="{{ route('Approve.Pos.Sale') }}" id="multiple-approve">
                         {{ csrf_field() }}
                         <div class="modal-footer">
                         <button class="btn btn-default" type="button" data-dismiss="modal">No</button>
                     <a class="btn btn-danger confirm-btn send-to-unapprove dis" onclick="document.getElementById('multiple-approve').submit()" style="color: white" data-dismiss="modal">Yes</a>
                     </div>
                     </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>

@endsection
@push('scripts')
<script type="text/javascript">
   $('.show-details').click(function(){
    var param = $(this).data('id');
    console.log(param);
    $.get('{{ route("Get.Pos.Sale.Deatils") }}/' + param , function(success){
      $('#order-detail-popup .modal-body').html(success);
    });
  });
 
</script>
@include('CheckBoxHandling.check_box')
@endpush
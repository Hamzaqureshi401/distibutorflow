@extends('layouts.app')
@section('content')
<!-- Main-body start -->
      <!-- Page-header start -->
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Stock Invoices</h5>
            <p class="text-muted m-b-10 text-center">Add Remove Stock Record</p>
            <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <a href="index.html"> <i class="fa fa-home"></i> </a>
               </li>
               <li class="breadcrumb-item"><a href="#!">All Stock History</a>
               </li>
               <!--  <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li> -->
            </ul>
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
                        <th class="text-center">ID</th> 
                        <th class="text-center">Old Stock</th>
                        <th class="text-center">Current Stock</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Stock Added / Sell Added</th>
                        <th class="text-center">Comments</th>
                        <th class="text-center">Added By</th>
                        <th class="text-center">Date</th>
                     </tr>
                  </thead>
                  <tbody>

                     @foreach($products as $pr)
                     @if ($model->GetTransectionRecord($pr)->first()->stock_adder_user_id != NULL)
                     <tr class="text-center" style="background-color: #ffe0ee;">
                     @else
                     <tr class="text-center">
                     @endif
                       <td><a class="btn btn-sm btn-success show-details" style="color: white;" data-id="{{ $pr }}" data-toggle="modal" data-target="#order-detail-popup"><li class="fa fa-eye"> {{ $loop->index + 1 }} Show Details</li></a></td>
                       <td>{{ $model->GetTransectionRecord($pr)->sum('old_stock') }}</td>
                       <td>{{ $model->GetTransectionRecord($pr)->sum('remaining_stock') }}</td>
                       <td>{{ $model->GetTransectionRecord($pr)->sum('remaining_stock') - $model->GetTransectionRecord($pr)->sum('old_stock') }}</td>
                       <td class="text-center">{{ $model->GetTransectionRecord($pr)->first()->stock_added == 1 ? 'Stock Added' : 'Sell Added' }}</td>
                       <td>{{ $model->GetTransectionRecord($pr)->first()->comments ?? "--" }}</td>
                       <td>{{ $model->get_user_name($model->GetTransectionRecord($pr)->first()->stock_adder_user_id)->name ?? Auth::user()->name }}</td>
                       <td>{{ $model->GetTransectionRecord($pr)->first()->created_at }}</td>
                       

                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <!-- Hover table card end -->
      <div id="styleSelector">
      </div>
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


@endsection
@push('scripts')
<script type="text/javascript">
   $('.show-details').click(function(){
    var param = $(this).data('id');
    console.log(param);
    $.get('{{ route("Get.Transection.Record") }}/' + param , function(success){
      $('#order-detail-popup .modal-body').html(success);
    });
  });
 
</script>
@endpush
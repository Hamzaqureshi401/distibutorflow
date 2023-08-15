@extends('layouts.app')
@section('content')
<!-- Main-body start -->
      <!-- Page-header start -->
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Employee Attandence</h5>
            <p class="text-muted m-b-10 text-center">Attandence Record</p>
            <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <a href="index.html"> <i class="fa fa-home"></i> </a>
               </li>
               <!--  <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li> -->
            </ul>
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
                     <tr class="text-center">
                        <th>Record</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Distance</th>
                        <th>Minutes Served</th>
                        <th>Over Time Served</th>
                        <th>Per_Minute_Sellary</th>
                        <th>Over Time Sellary</th>
                        <th>Total Sellary</th>
                        <th>Created At</th>
                        @if(Auth::user()->role ==4 || Auth::user()->role < 3)
                        <th>Action</th>
                        @endif
                     </tr>
                  </thead>
                  <tbody>
                   @foreach($query as $record)
                     <tr>
                     <td>{{ $loop->index + 1 }}</td>
                     <td>{{ date('h:i:s a m/d/Y', strtotime($record->start_time))}}</td>
                     <td>{{ date('h:i:s a m/d/Y', strtotime($record->end_time))}}</td>
                     <td>
                         <a href="http://maps.google.com/maps?q=+{{ $record->cords }}" target="_blank">
                 {{ $record->distance_measure}}</a></td>
                     <td>{{ $record->minutes_served}}</td>
                     <td>{{ $record->over_time_served}}</td>
                     <td>
                        {{ $record->minutes_served - $record->over_time_served }} 
                        * 
                        {{ $record->per_minute_sellary }} 
                        = {{ ($record->minutes_served - $record->over_time_served) * $record->per_minute_sellary }}
                     </td>
                     <td>
                        {{ $record->over_time_served}} 
                        * 
                        {{ $record->over_time_per_minute_sellary }} 
                        = {{ $record->over_time_served * $record->over_time_per_minute_sellary}}
                     </td>
                     <td>{{ (($record->minutes_served - $record->over_time_served) * $record->per_minute_sellary) + ($record->over_time_served * $record->over_time_per_minute_sellary)}}</td>
                     <td>{{ $record->created_at}} / {{ $record->updated_at }}</td>
                     @if(Auth::user()->role == 4 || Auth::user()->role < 3)
                     <td><a href="javascript:;" data-toggle="modal" data-target="#Attandence-detail-popup" class="btn btn-sm btn-success view-attandence" id="{{ $record->id }}"><i class="fa fa-eye"> Image View</i></a>
                        @if((strtotime($record->end_time) - strtotime($record->start_time)) == 0 && $record->minutes_served == 0 && empty($record->failed_attemptes) && $record->user_active != 1)
                          <a href="{{ route('again.Checkout' , $record->id) }}" class="btn btn-sm prev-record btn-primary"><i class="fa fa-reply"></i>Again Checkout</a>
                          @endif

                     </td>
                     @endif
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

<div class="modal fade" id="Attandence-detail-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  $(document).on('click', '.view-attandence', function(){
    var param = $(this).attr('id');        
$('#Attandence-detail-popup .modal-body').html('<h6 class="text-center">Loading ..</h6>');
    $.get('{{ route("attandence.detail") }}/' + param , function(success){
      $('#Attandence-detail-popup .modal-body').html(success);
    });
  });
</script>
@endpush
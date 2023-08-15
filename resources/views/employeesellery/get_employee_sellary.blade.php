@extends('layouts.app')
@section('content')
<!-- Main-body start -->
      <!-- Page-header start -->
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">My Sellary</h5>
            <p class="text-muted m-b-10 text-center">Sellary History</p>
         </div>
         @if(Auth::user()->role ==4 || Auth::user()->role < 3)
         <button class="btn btn-sm btn-primary cash-receivings" data-toggle="modal" data-target="#cash-receivings">Pay Sellary</button>
         @endif
         
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
                        <th>ID</th>
                        <th>Name</th>
                        <th>Old Cash Remaining</th>
                        <th>Cash Paid Added</th>
                        <th>Current Cash Remaining</th>
                        <th>Comments</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($query as $user)
                     <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $user->user_id }}</td>
                        <td>{{ $user->old_cash_remaining }}</td>
                        <td>{{ $user->cash_paid_added }}</td>
                        <td>{{ $user->current_cash_remaining }}</td>
                        <td>{{ $user->comments ?? '--' }}</td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <!-- Hover table card end -->
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
        <form method="post" class="pay_form" action="{{ route('pay.seller.sellery') }}">
          {{ csrf_field() }}
          <div class="form-group">
            <input type="hidden" name="id" value="{{ $id }}">
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


@endsection
@push('scripts')
<script type="text/javascript">

      $('.confirm').click(function(){
    var param = $(this).data('id');
      $.ajax({
            type: 'get',
            url: '{{ route('pay.seller.sellery') }}',
             data:{
              _token: "{{ csrf_token() }}", 'id': param},
            success: function (data) {
              console.log(data);
              var nType = "success";
              var title = "Success ";
              var msg = data.message;
              notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
              $(this).closest("tr").remove();
              $(this).parent().remove();
              }
          });
  });
</script>
@endpush
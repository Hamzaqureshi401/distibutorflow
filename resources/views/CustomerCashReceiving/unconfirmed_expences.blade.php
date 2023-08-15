      <div class="col-sm-12">
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Manager Transections</h5>
            <p class="text-muted m-b-10 text-center">Expnse / Payments</p>
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
                     <th class="text-center">Out Side Payments</th> 
                     <th class="text-center">Expenses</th> 
                     <th class="text-center">Comments</th> 
                     <th class="text-center">Added By</th> 
                     <th class="text-center">Created At/Action</th> 
                     </tr>
                  </thead>
                  <tbody>

                     @foreach($exppayment as $r)
                    @if ($r->processor_id == Auth::id())
                    <tr class="text-center" style="background-color: red;">
                      @else
                     <tr class="text-center">
                      @endif
                      
                       <td>
                        {{ $r->outside_payments ?? '--' }}
                       </td>
                       <td>
                        {{ $r->expenses ?? '--' }}
                       </td>
                       <td>
                        {{ $r->comments ?? '--' }}
                       </td>
                       <td>
                        @php
                        $id = $r->processor_id ?? Auth::id();
                        @endphp
                        {{ $UserModel->where('id' , $id)->first()->name }}
                       </td>
                       <td>
                        {{ $r->created_at}}
                        <br>
                         @if(Auth::user()->role == 4)
                        <button class="btn btn-sm btn-warning confirm" data-id="{{ $r->id }}"><i class="fa fa-warning"> Confirm Transection</i></button>
                        @endif
                        <button class="btn btn-sm btn-danger delete" data-id="{{ $r->id }}"><i class="fa fa-trash"> Delete Transection</i></button>
                       </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
               <div class="card-footer small text-muted">
                         Out Side Payments: <b>{{ $exppayment->sum('outside_payments') }}</b> 
                         | 
                         Expence: <b>{{ $exppayment->sum('expenses') }}</b> 
                     </div>
                  
        
            </div>
         </div>
      </div>
   </div>
@push('scripts')
   <script type="text/javascript">
   $('.confirm').click(function(){
    var param = $(this).data('id');
      $.ajax({
            type: 'get',
            url: '{{ route('Confirm.Customer.Expnse') }}',
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

    $('.delete').click(function(){
    var param = $(this).data('id');
      $.ajax({
            type: 'get',
            url: '{{ route('delete.Customer.Transection') }}',
             data:{
              _token: "{{ csrf_token() }}", 'id': param},
            success: function (data) {
              console.log(data);
              var nType = "danger";
              var title = "Please Refresh! ";
              var msg = data.message;
              notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
              $(this).closest("tr").remove();
              $(this).parent().remove();
              }
          });
  });
 
</script>
@endpush


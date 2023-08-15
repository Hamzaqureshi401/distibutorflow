            <!-- statustic and process end -->
            <!-- tabs card start -->

            <div class="col-sm-12">
               <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Branches Transection History</h5>
            <p class="text-muted m-b-10 text-center">Branch List</p>
            <!-- <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <a href="index.html"> <i class="fa fa-home"></i> </a>
               </li>
               <li class="breadcrumb-item"><a href="#!">All Categories</a>
               </li>
                <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li>
            </ul>
              <div class="card-header">
        <i class="fa fa-table"></i> Categories List
        <a class="btn pull-right add-category btn-out-dashed btn-round btn-grd-primary" data-toggle="modal" data-target="#category-popup" style="color: white">Add Category</a> -->
     <!--  </div> -->
         </div>
      </div>
               <div class="card tabs-card">
                  <div class="card-block p-0">
                     <!-- Nav tabs -->
                     <ul class="nav md-tabs" role="">
                        <li class="nav-item text-center">
                           <a class="nav-link active" data-toggle="tab" href="#home3" role="tab"><i class="fa fa-"></i></a>
                           <div class="slide"></div>
                        </li>
                     </ul>
                     <div class="tab-content card-block">
                        <div class="tab-pane active" id="home3" role="tabpanel">
                           <div class="table-responsive">
                              <table class="table table-hover table-datatable">
                                 <thead>
                                    <tr class="header" id="myHeader">
                                       <th>Branches</th>
                                       <th>Old Cash</th>
                                       <th>Stock Added</th>
                                       <th>Remaining Cash</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($result['branches']['branches'] as $record)
                                    <tr>
                                       <td>{{ $record->user->name }}</td>
                                       <td>{{ $result['branches']['customerModel']->getOrders($record->id)->sum('subtotal') + $result['branches']['customerModel']->PosSaleCashReceiving($record->id)->whereNull('pos_sell_ids')->sum('cash_paid_added') - $result['branches']['customerModel']->getOrders($record->id)->last()->subtotal}}</td>
                                       <td>{{ $result['branches']['customerModel']->getOrders($record->id)->last()->subtotal }}</td>
                                       <td>{{ $result['branches']['customerModel']->getOrders($record->id)->sum('subtotal') + $result['branches']['customerModel']->PosSaleCashReceiving($record->id)->whereNull('pos_sell_ids')->sum('cash_paid_added')}}</td>
                                       <td> 
                                         @if (Auth::user()->role != 4)
                                        <li class="breadcrumb-item">
                                          <a class="btn btn-sm btn-primary add-admin-payment" data-id="{{ $record->id }}" row-id="{{ $record->user_id }}" data-toggle="modal" data-target="#add-admin-payment">Add Payment To Admin</a>
                                       </li>
                                       @endif
                                       </td>
                                       
                                    </tr>
                                    @endforeach
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <!--  <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile3" role="tab"><i class="fa fa-key"></i>Security</a>
                        <div class="slide"></div>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#messages3" role="tab"><i class="fa fa-play-circle"></i>Entertainment</a>
                        <div class="slide"></div>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#settings3" role="tab"><i class="fa fa-database"></i>Big Data</a>
                        <div class="slide"></div>
                        </li> -->
                     <!-- Tab panes -->
                  </div>
               </div>
            </div>
            <!-- social statustic start -->

      <div class="modal fade" id="add-admin-payment" tabindex="-1" role="dialog" aria-labelledby="pay-ot-popup-label" aria-hidden="true">
           <div class="modal-dialog" role="document">
             <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title">Pay Amount Or Add Profit</h5>
                 <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
                 </button>
               </div>
               <div class="modal-body">
                 <form method="post" class="pay_form" action="{{ route('Add.Payment.To.Admin') }}">
                   {{ csrf_field() }}
                   <div class="form-group">
                     <input type="hidden" name="customer_id" id="customer_id">
                     <input type="hidden" name="user_id" id="user_id">
                     <label>Payment </label><input class="form-control r-amount" type="number" name="payment" class="form-control" value="0" required="">
                     <label>Expenses </label><input class="form-control r-amount" type="number" name="expenses" class="form-control" value="0" required="">
                     <label style="color: red;">Add Comments <span style=" font-style: italic; color: red;"></span></label>
                     <textarea class="form-control" rows="4" placeholder="Enter Comments" name="comments" maxlength = "200"></textarea>
                   </div>
                   <button class="btn btn-secondary btn-block cl">Continue</button>
                 </form>
               </div>
             </div>
           </div>
         </div>
         @push('scripts')
         <script type="text/javascript">
              $(document).on('click', '.add-admin-payment', function(){
               $('#customer_id').val($(this).data('id'));
               $('#user_id').val($(this).row('id'));
             });
                $(document).on('click', '.cl', function(){
               $("#add-admin-payment .close").click();
             });
         </script>
         @endpush

@extends('layouts.app')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('otikaassests/css/app.min.css') }}">
<!-- Template CSS -->
<!-- <link rel="stylesheet" href="{{ asset('otikaassests/css/style.css') }}"> -->
<link rel="stylesheet" href="{{ asset('otikaassests/css/components.css') }}">
<!-- Custom style CSS -->
<link rel="stylesheet" href="{{ asset('otikaassests/css/custom.css') }}">
<style>
   #echart_graph_line {
   width: 800px;
   height: 400px;
   margin: 0 auto;
   }
</style>
@endpush
<div class="main-body">
   <div class="page-wrapper">
      <div class="page-body">
         <div class="row">
            <!-- order-card start -->
            <div class="col-md-6 col-xl-3">
               <div class="card bg-c-blue order-card">
                  <div class="card-block">
                     <h6 class="m-b-20">Total Sale</h6>
                     <h3 class="text-right"><i class="ti-shopping-cart f-left"></i>
                        <span><small><b>{{ $result['month_amount'] }}</b></small></span>
                     </h3>
                     <p class="m-b-0">Completed Orders<span class="f-right">{{ $result['total_invoices'] }}</span></p>
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-xl-3">
               <div class="card bg-c-green order-card">
                  <div class="card-block">
                     <h7 class="m-b-20">Total Profit This Month</h7>
                     <p> </p>
                     <h3 class="text-right"><i class="ti-tag f-left"></i><span>{{$result['total_profit']}}</span></h3>
                     <p class="m-b-0">Actual Profit<span class="f-right">{{ $result['profit_in_hand']}}</span></p>
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-xl-3">
               <div class="card bg-c-yellow order-card">
                  <div class="card-block">
                     <h6 class="m-b-20">Revenue</h6>
                     <h3 class="text-right"><i class="ti-reload f-left"></i><span>{{ $result['over_all_profit']}} </span></h3>
                    <p class="m-b-0">In Hand<span class="f-right">{{ $result['over_all_profit_in_hand'] }}</span></p>
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-xl-3">
               <div class="card bg-c-pink order-card">
                  <div class="card-block">
                     <h6 class="m-b-20">Total Balance</h6>
                     <h3 class="text-right"><i class="ti-wallet f-left"></i><span>{{$result['total_balance']}}</span></h3>
                     <p class="m-b-0">This Month<span class="f-right">{{$result['month_balance']}}</span></p>
                  </div>
               </div>
            </div>
            <!-- order-card end -->
            <!-- statustic and process start -->
            <div class="col-lg-8 col-md-12">
               <div class="card">
                  <div class="card-header">
                     <h5>Profits Statistics</h5>
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
                  <div class="card-block">
                     <canvas id="Statistics-chart" height="400"></canvas>
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-md-12">
               <div class="card">
                  <div class="card-header text-center">
                     <h5>Customer Order Ratio of last 15 Days</h5>
                  </div>
                  <div class="card-block">
                     <span class="d-block text-c-blue f-24 f-w-600 text-center">{{ $result['total_customer'] ?? 0}}-<small>Customers</small></span>
                     <canvas id="feedback-chart" height=""></canvas>
                     <div class="row justify-content-center m-t-15">
                        <div class="col-auto b-r-default m-t-5 m-b-5">
                           <h4>{{$result['percentage']}} %</h4>
                           <p class="text-success m-b-0"><i class="ti-hand-point-up m-r-5"></i>Positive</p>
                        </div>
                        <div class="col-auto m-t-5 m-b-5">
                           <h4>{{ 100 - $result['percentage'] }} %</h4>
                           <p class="text-danger m-b-0"><i class="ti-hand-point-down m-r-5"></i>Negative</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-md-12">
               <div class="card" style="margin-left :12px;">
                  <div class="card-header">
                     <h4>Area Visit Chart</h4>
                  </div>
                  <div class="card-block">
                     <div class="recent-report__chart">
                        <div id="gaugeChart"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-8 col-md-12">
               <div class="card">
                  <div class="card-header">
                     <h4>6 Month Cash Flow History</h4>
                  </div>
                  <div class="card-body">
                     <canvas id="myChart1"  width="800" height="400"></canvas>
                  </div>
               </div>
            </div>
            <div class="col-lg-12 col-md-12">
               <div class="card">
                  <div class="card-header">
                     <h4>Area Order Chart</h4>
                  </div>
                  <div class="card-body">
                     <canvas id="myChart"  width="800" height="400"></canvas>
                  </div>
               </div>
            </div>
            <!-- statustic and process end -->
            <!-- tabs card start -->
            <div class="col-sm-12">
               <div class="card tabs-card">
                  <div class="card-block p-0">
                     <!-- Nav tabs -->
                     <ul class="nav md-tabs" role="">
                        <li class="nav-item text-center">
                           <a class="nav-link active" data-toggle="tab" href="#home3" role="tab"><i class="fa fa-home"></i>Home</a>
                           <div class="slide"></div>
                        </li>
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
                     </ul>
                     <!-- Tab panes -->
                     <?php $invoices = $result['today_in']; ?>
                     <div class="tab-content card-block">
                        <div class="tab-pane active" id="home3" role="tabpanel">
                           <div class="table-responsive">
                              <table class="table table-hover table-datatable">
                                 <thead>
                                    <tr class="header" id="myHeader">
                                       <th style="width: 20px">#</th>
                                       <th>Customer</th>
                                       <th>Units</th>
                                       <th>Total</th>
                                       <th>Sub Total</th>
                                       <th>Recieved</th>
                                       <th>Balance</th>
                                       <th>Advance</th>
                                       @if(Auth::user()->role < 3)
                                       <th>A Benefit</th>
                                       @endif
                                       <th>C Benefit</th>
                                       <th>Date</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <input type="hidden" value="{{$counter=0}}">
                                    @foreach($invoices as $invoice)
                                    <tr>
                                       <input type="hidden" name="" value="{{ $invoice->created_at->diffForHumans() }}">
                                       <input type="hidden" name="" class="apprbtn" value="{{ $invoice->is_approved }}">
                                       <td style="width: 20px">{{ $invoice->id }}</td>
                                       @if ( $invoice->received_amount < $invoice->subtotal  )
                                       <td style="color: red" data-changein="subtotal">{{ $invoice->customer->user->name ?? 'Not Defined' }}<br><b>Creater</b><br>{{ $invoice->customer->otordertakername->name ?? 'Not Defined' }} </td>
                                       @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left > 0  )
                                       <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->customer->user->name }}<br><b>Creater</b><br>{{ $invoice->customer->otordertakername->name }}</td>
                                       @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left <= 0  )
                                       <td style="color: #28B463" data-changein="subtotal">{{ $invoice->customer->user->name }}<br><b>Creater</b><br>{{ $invoice->customer->otordertakername->name }}</td>
                                       @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left <= 0 )
                                       <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->customer->user->name ?? 'Not Defined' }}<br><b>Creater</b><br>{{ $invoice->customer->otordertakername->name ?? 'Not Defined' }}</td>
                                       @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                                       <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->customer->user->name }}<br><b>Creater</b><br>{{ $invoice->customer->otordertakername->name }}</td>
                                       @elseif ( $invoice->received_amount == 0  )
                                       <td data-changein="subtotal">{{ $invoice->customer->user->name }}<br><b>Creater</b><br>{{ $invoice->customer->otordertakername->name }}</td>
                                       @endif
                                       <!--Unit-->
                                       @if ( $invoice->received_amount < $invoice->subtotal  )
                                       <td style="color: red" data-changein="subtotal">{{ $invoice->unit }}</td>
                                       @elseif ( $invoice->received_amount > $invoice->subtotal  )
                                       <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->unit }}</td>
                                       @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left <= 0 )
                                       <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->unit }}</td>
                                       @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                                       <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->unit }}</td>
                                       @elseif ( $invoice->received_amount == 0  )
                                       <td data-changein="subtotal">{{ $invoice->unit }}</td>
                                       @endif
                                       <!--Total-->
                                       @if ( $invoice->received_amount < $invoice->subtotal  )
                                       <td style="color: red" data-changein="subtotal">{{ $invoice->amount }}</td>
                                       @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left > 0  )
                                       <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->amount }}</td>
                                       @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left <= 0  )
                                       <td style="color: #28B463" data-changein="subtotal">{{ $invoice->amount }}</td>
                                       @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left <= 0 )
                                       <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->amount }}</td>
                                       @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                                       <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->amount }}</td>
                                       @elseif ( $invoice->received_amount == 0  )
                                       <td data-changein="subtotal">{{ $invoice->amount }}</td>
                                       @endif
                                       <!--Subtotal-->
                                       @if ( $invoice->received_amount < $invoice->subtotal  )
                                       <td style="color: red" data-changein="subtotal">{{ $invoice->subtotal }}</td>
                                       @elseif ( $invoice->received_amount > $invoice->subtotal  )
                                       <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->subtotal }}</td>
                                       @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left <= 0 )
                                       <td style="color: #2ECC71" data-changein="subtotal">{{ $invoice->subtotal }}</td>
                                       @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                                       <td style="color: #CC9A2E" data-changein="subtotal">{{ $invoice->subtotal }}</td>
                                       @elseif ( $invoice->received_amount == 0  )
                                       <td data-changein="subtotal">{{ $invoice->subtotal }}</td>
                                       @endif
                                       <!--Received Amount-->
                                       @if ( $invoice->amount_left <= 0  )
                                       <td style="color: #2ECC71" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                                       @elseif ( $invoice->received_amount == 0 && $invoice->subtotal != $invoice->received_amount )
                                       <td style="color: red" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                                       @elseif ( $invoice->received_amount < $invoice->subtotal && $invoice->amount_left > 0 )
                                       <td style="color: #CC9A2E" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                                       @elseif ( $invoice->received_amount > $invoice->subtotal && $invoice->amount_left > 0 )
                                       <td style="color: #28B463" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                                       @elseif ( $invoice->received_amount == $invoice->subtotal && $invoice->amount_left > 0 )
                                       <td style="color: #CC9A2E" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                                       @elseif ( $invoice->received_amount > 0 && $invoice->subtotal < 0 && $invoice->amount_left > 0 )
                                       <td style="color: #2ECC71" data-changein="received_amount">{{ $invoice->received_amount }}</td>
                                       @endif
                                       <!--Balance-->
                                       @if ($invoice->amount_left > 0)
                                       <td style="color: red">{{ $invoice->amount_left }}</td>
                                       @endif
                                       @if ($invoice->amount_left <= 0)
                                       <td style="color: #2ECC71">{{ $invoice->amount_left }}</td>
                                       @endif
                                       <td>{{ $invoice->advance }}</td>
                                       <!--Benefit-->
                                       @if(Auth::user()->role < 3)
                                       @if( $invoice->received_amount >= $invoice->subtotal && $invoice->amount_left <= 0 )
                                       <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                                       @elseif( $invoice->received_amount >= $invoice->subtotal && $invoice->amount_left > 0 )
                                       <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                                       @elseif( $invoice->subtotal == $invoice->received_amount && $invoice->amount_left > 0 )
                                       <td style= "color: #CC9A2E">{{ $invoice->a_benefit }}</td>
                                       @elseif( $invoice->subtotal >= $invoice->received_amount && $invoice->amount_left > 0 )
                                       <td style= "color: #CC9A2E">{{ $invoice->a_benefit }}</td>
                                       @elseif( $invoice->subtotal <0 )
                                       <td style= "color: red">{{ $invoice->a_benefit }}</td>
                                       @elseif( $invoice->amount == $invoice->received_amount && $invoice->amount_left <= 0 )
                                       <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                                       @elseif( $invoice->amount =! $invoice->received_amount && $invoice->amount_left > 0 )
                                       <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                                       @elseif( $invoice->amount != $invoice->received_amount && $invoice->amount_left <= 0 )
                                       <td style= "color: #2ECC71">{{ $invoice->a_benefit }}</td>
                                       @elseif( $invoice->received_amount <= 0 )
                                       <td style= "color: red">{{ $invoice->a_benefit }}</td>
                                       @endif
                                       @endif
                                       <td>{{ $invoice->c_benefit }}</td>
                                       <td>{{ $invoice->created_at }}</td>
                                       <td>
                                          <a href="javascript:;" data-toggle="modal" data-target="#invoice-detail-popup" class="btn btn-sm btn-success view-details" id="{{ $invoice->id }}" is_app = "{{ $invoice->is_approved }}"><i class="fa fa-eye"></i></a>
                                          <input type="hidden" value="{{$counter++}}">
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
            </div>
            <!-- tabs card end -->
            <!-- tabs card start -->
            <?php $Attandence = $result['emp_user']; ?>
            @if(!empty($Attandence))
            <div class="col-sm-12">
               <div class="card tabs-card">
                  <div class="card-block p-0">
                     <!-- Nav tabs -->
                     <ul class="nav md-tabs" role="">
                        <li class="nav-item text-center">
                           <a class="nav-link active" data-toggle="tab" href="#home3" role="tab"><i class="fa fa-home"></i>Home</a>
                           <div class="slide"></div>
                        </li>
                     </ul>
                     <!-- Tab panes -->
                     <div class="tab-content card-block">
                        <div class="tab-pane active" id="home3" role="tabpanel">
                           <div class="table-responsive">
                              <table class="table table-hover table-datatable">
                                 <thead>
                                    <tr class="header" id="myHeader">
                                       <th>Active User</th>
                                       <th>Shift Started</th>
                                       <th>Shift Ended</th>
                                       <th>Ditance</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($Attandence as $Attandence)
                                    @if($result['Attandence']->getAttanceRecord($Attandence) && $result['Attandence']->getAttanceRecord($Attandence)->user_active == 1)
                                    <tr class="bg-primary">
                                       @else
                                    <tr class="bg-danger">
                                       @endif
                                       <td>{{ $result['Attandence']->getUserName($Attandence)->name }}</td>
                                       <td>{{ $result['Attandence']->getAttanceRecord($Attandence) ? date('h:i:s a m/d/Y', strtotime($result['Attandence']->getAttanceRecord($Attandence)->start_time)) : '--' }}</td>
                                       <td>{{ $result['Attandence']->getAttanceRecord($Attandence) ? date('h:i:s a m/d/Y', strtotime($result['Attandence']->getAttanceRecord($Attandence)->end_time)) : '--' }}</td>
                                       <td><a href="http://maps.google.com/maps?q=+{{ $result['Attandence']->getAttanceRecord($Attandence)->cords ?? '' }}" target="_blank">
                                          {{ $result['Attandence']->getAttanceRecord($Attandence)->distance_measure ?? '' }}</a>
                                       </td>
                                       <td>
                                          @if ($result['Attandence']->getAttanceRecord($Attandence))
                                          <a href="javascript:;" data-toggle="modal" data-target="#Attandence-detail-popup" class="btn btn-sm btn-success view-attandence" id="{{ $result['Attandence']->getAttanceRecord($Attandence)->id }}"><i class="fa fa-eye"></i> Image View</a>
                                          <a href="{{ route('Get.Attendence.Record', $Attandence) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Get Attandence Record</a>
                                          <a href="{{ route('get.Employee.Sellary', $Attandence) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Get Employee Sellary</a>
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
            </div>
            @endif
            <!-- tabs card end -->
            <!-- social statustic start -->
            <div class="col-md-12 col-lg-4">
               <div class="card bg-c-blue">
                  <div class="card-block text-center"  style="color:white;">
                     <i class="fa fa-envelope-open text-c-yellow d-block f-40"></i>
                     <h4 class="m-t-20">Week Profit</h4>
                     <p class="m-b-20">Total : {{ $result['week_total_profit']}}
                        <br>In Hand : {{ $result['week_profit_in_hand']}}
                     </p>
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-lg-4">
               <div class="card">
                  <div class="card-block text-center">
                     <i class="fa fa-twitter text-c-green d-block f-40"></i>
                     <h4 class="m-t-20"><span class="text-c-blgreenue"></span> Todays Activity</h4>
                     <p class="m-b-20">Area Selected: {{ $result['selected_area'] }}
                        <br>Total Visit :{{ $result['total_visit'] }}
                        <br>Visit Completed : {{ $result['get_visit'] }}
                        <br>Calls : {{ $result['get_call_customer'] }}
                     </p>
                     <!--  <button class="btn btn-success btn-sm btn-round">Check them out</button> -->
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-lg-4">
               <div class="card bg-c-pink">
                  <div class="card-block text-center" style="color:white;">
                     <i class="fa fa-puzzle-piece text-c-blue d-block f-40"></i>
                     <h4 class="m-t-20">Week Balance</h4>
                     <p class="m-b-20">Total : {{ $result['week_balance']}}
                        <br>In Hand : {{ $result['week_profit_in_hand']}}
                     </p>
                  </div>
               </div>
            </div>
            <!-- social statustic end -->
            <!-- users visite and profile start -->
            <a href="{{ route('get.profile') }}">
               <div class="col-md-4">
                  <div class="card user-card">
                     <div class="card-header">
                        <h5>Profile</h5>
                     </div>
                     <div class="card-block">
                        <div class="usre-image">
                           @if (empty($profile['profile']->img))
                           <img src="{{ asset('assets/images/2b1ba07a56167735eb206ef0088fa358f90aeb69.png') }}" class="img-radius" alt="user avatar">
                           @else
                           <img src="{{ asset($profile['profile']->img) }}" class="img-radius" alt="user avatar" style="width: 100px; height: 100px;">
                           @endif
                        </div>
                        <h6 class="f-w-600 m-t-25 m-b-10">{{ $profile['user']->name }}</h6>
                        <p class="text-muted">{{ $profile['role'] }} | {{ $profile['profile']->gender ?? 'Others'}} | Born {{ $profile['profile']->date_of_birth ?? 'Not Born Yet!'}}</p>
                        <hr/>
                        <!-- <p class="text-muted m-t-15">Profile Completed </p>
                        <ul class="list-unstyled activity-leval">
                           <li class="active"></li>
                           <li class="active"></li>
                           <li class="active"></li>
                           <li></li>
                           <li></li>
                        </ul> -->
                        <!-- <div class="bg-c-blue counter-block m-t-10 p-20">
                           <div class="row">
                              <div class="col-4">
                                 <i class="ti-comments"></i>
                                 <p>0</p>
                              </div>
                              <div class="col-4">
                                 <i class="ti-user"></i>
                                 <p>0</p>
                              </div>
                              <div class="col-4">
                                 <i class="ti-bag"></i>
                                 <p>0</p>
                              </div>
                           </div>
                        </div> -->
                        <p class="m-t-15 text-muted">{{ $profile['profile']->about ?? 'Not Updated!' }}</p>
                        <hr/>
                        <div class="row justify-content-center user-social-link">
                           <div class="col-auto">
            <a href="#!"><i class="fa fa-facebook text-facebook"></i></a></div>
            <div class="col-auto"><a href="#!"><i class="fa fa-twitter text-twitter"></i></a></div>
            <div class="col-auto"><a href="#!"><i class="fa fa-dribbble text-dribbble"></i></a></div>
            </div>
            </div>
            </div>
            </div>
            </a>
            <!-- users visite and profile end -->
         </div>
      </div>
      <div id="styleSelector">
      </div>
   </div>
</div>
<div class="modal fade" id="invoice-detail-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            @if(Auth::user()->role <= 3)
            @if(Auth::user()->role < 3)
            <a href="" class="btn btn-primary approve-btn">Approve</a>
            @endif
            <button id="btnStatus2" onclick="window.location='printerplus://send?text='+document.getElementById('p').innerHTML;" onclick="btnStatus2_Click">
            Send to Printer+
            </button>
            @endif
         </div>
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
<!-- <script src="{{ asset('otikaassests/js/app.min.js') }}"></script> -->
@include('dashboard.areaGuageChart')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('dashboard.charts.admin_chart-dashboard')
<script type="text/javascript">
   $(document).on('click', '.view-details', function(){
   var param = $(this).attr('id');
   $('.approve-btn').attr('href' , "{{ route('approve.invoice') }}/" + param);
   $('#invoice-detail-popup .modal-title small').text('(' + $(this).closest('tr').find('input').val() + ')');
      var invoice_type = $(this).data('rowid');
      
   $('#invoice-detail-popup .modal-body').html('<h6 class="text-center">Loading ..</h6>');
   if (invoice_type == 0 ){
   $.get('{{ route("invoice.detail") }}/' + param , function(success){
    $('#invoice-detail-popup .modal-body').html(success);
   });
      }
      else {
   $.get('{{ route("stock.detail") }}/' + param , function(success){
    $('#invoice-detail-popup .modal-body').html(success);
   });
      }
   });
   
   $(document).on('click', '.view-attandence', function(){
   var param = $(this).attr('id');        
   $('#Attandence-detail-popup .modal-body').html('<h6 class="text-center">Loading ..</h6>');
   $.get('{{ route("attandence.detail") }}/' + param , function(success){
    $('#Attandence-detail-popup .modal-body').html(success);
   });
   });
   
   
   //console.log(allAreas);
   
   const data = []; // create an empty array for chart data
   const areas = Object.keys(areHistory); // get the area names from the array keys
   
   console.log(areas);
   
   
   // iterate through the areas and populate the chart data array
   areas.forEach(area => {
   allAreas.push(area);
   const values = areHistory[area];
   allOrders.push(values.areaOrder[0]);
   });
   
   console.log(allOrders);
   
   
   const ctx = document.getElementById('myChart');
   
   new Chart(ctx, {
   type: 'bar',
   data: {
   labels: allAreas,
   datasets: [{
    label: '# of Orders',
    data: allOrders,
    borderWidth: 1
   }]
   },
   options: {
   plugins: {
    tooltip: {
      enabled: true
    }
   },
   maintainAspectRatio: false,
   width: 800,
   height: 400,
   scales: {
    y: {
      beginAtZero: true
    }
   }
   }
   });
   
   //const data = []; // create an empty array for chart data
   //const areas = Object.keys(areHistory); // get the area names from the array keys
   
   // console.log(areas);
   
   
   // // iterate through the areas and populate the chart data array
   // areas.forEach(area => {
   //   allAreas.push(area);
   //   const values = areHistory[area];
   //   allOrders.push(values.areaOrder[0]);
   // });
   
   console.log();
   var subtotal = 0;
   var received_amount = 0;
   var discounts = 0;
   $.each(@json($result['6_month_subtotal']), function(index, value) {
   subtotal += value;
   });
   $.each(@json($result['6_month_received_amount']), function(index, value) {
   received_amount += value;
   });
   $.each(@json($result['6_month_discount']), function(index, value) {
   discounts += value;
   });
   var balance = subtotal -received_amount;
   
   
   const ctx1 = document.getElementById('myChart1');
   
   new Chart(ctx1, {
   type: 'bar',
   data: {
   labels: ['Subtotal' , 'Received Amount' , 'Discounts' , 'Balances'],
   datasets: [{
    label: '# value',
    data: [subtotal , received_amount , discounts , balance],
    borderWidth: 1
   }]
   },
   options: {
   plugins: {
    tooltip: {
      enabled: true
    }
   },
   maintainAspectRatio: false,
   width: 800,
   height: 400,
   scales: {
    y: {
      beginAtZero: true
    }
   }
   }
   });
   
</script>

<script>
window.addEventListener('resize', resizeText);

function resizeText() {
  const heading = document.getElementById('profit-heading');
  const headingContainer = heading.parentElement;
  const maxWidth = headingContainer.offsetWidth;

  // Reset the font size to the original size
  heading.style.fontSize = '';

  // Reduce the font size until the text fits within the container
  while (heading.offsetWidth > maxWidth) {
    const currentFontSize = parseFloat(window.getComputedStyle(heading).fontSize);
    heading.style.fontSize = (currentFontSize - 1) + 'px';
  }
}

// Call the resizeText function initially to set the correct font size
resizeText();
</script>

@endpush
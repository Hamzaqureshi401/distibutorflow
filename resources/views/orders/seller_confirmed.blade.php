@extends('layouts.app')
@push('styles')
<style>
   .highlighted td {
   background: yellow;
   }
   .clear td {
   background: #009868;
   }
   .brdr{
   border: 1px solid gray;
   padding: 1px;
   font-weight: 600;
   background: yellow;
   }
   .highlighted td {
   background: green;
   }
   .cancel td {
   background: red;
   }
   .delivery-failed{
   background: yellow;
   }
   .today-delivery{
   background: #00FBF7;
   }
   .dont_use_stock{
   background: #fc5e03;
   }
   .delivery-missed{
   background: pink;
   border-style: solid;
   }
   .delivery-missed-2day{
   background: #f542e0;
   }
   .new-customer{
   background: #00FB63;
   }
   .clear td {
   background: #009868;
   }
   .brdr{
   border: 1px solid gray;
   padding: 1px;
   font-weight: 600;
   background: red;
   }
   .check-all-color{
   background-color: #59e0c5;
   }
</style>
@endpush
@section('title') Seller Confirmed Orders @endsection
@section('content')
<?php error_reporting(0) ?>
<div class="page-header card">
   <div class="card-block">
      <h5 class="m-b-10 text-center">Seller Cofirmed Orders </h5>
      <p class="text-muted m-b-10 text-center">Orders Assigned TO Seller</p>
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
      <a href="{{ route('unconfirmed.orders') }}" class="btn btn-sm btn-primary">Go To Unconfirmed Orders</a>
        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#product-report-popup"><i class="fa fa-eye"> Show Product Report</i></button>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card mb-3">
         <div class="card-header">
            <h5><i class="fa fa-table"></i> Orders List</h5>
            <div class="col-md-12 p-0">
               @if(Auth::user()->role <= 3)
               <form method="post" >
                  {{ csrf_field() }}
                  <div class="row">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label>Filter By Seller</label>
                           <select id="seller_filter" class="form-control">
                              <option value="yes" selected>Show All</option>
                              @if(!empty($selected_seller))
                              @foreach($selected_seller as $item)
                              <option value="{{$item->id}}">{{ $item->name }}</option>
                              @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label>Filter By Order Taker</label>
                           <select class="form-control ot_filter" name="" >
                              <option value="yes">Show All</option>
                              @foreach ($ordertakers as $ot)
                              <option value="{{ $ot->id ?? '--' }}">{{$ot->name ?? '--'}}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label>Filter By Area</label>
                           <select class="form-control ot_area" name="" >
                              <option value="yes">Show All</option>
                              @foreach ($areas as $ot)
                              <option value="{{ $ot->id ?? '--' }}">{{$ot->name ?? '--'}}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                  </div>
               </form>
               @endif
               <form method="post" action="{{ route('confirmed.orders.seller' ) }}">
                  {{ csrf_field() }}
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">From</label>
                           <input type="date" name="from" value="{{ date('Y-m-d') }}" class="form-control" />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="">To</label>
                           <div class="d-flex">
                              <input type="date" name="to" value="{{ date('Y-m-d') }}" class="form-control" />
                              <button class="btn btn-success btn-sm ml-3">Search</button>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <button class="btn btn-info btn-sm show-map" type="button" data-toggle="modal" data-target="#map-modal">Show Map</button>  
                        <button class="btn btn-info btn-sm check-all-map-point" type="button" data-target="#map-modal">Check All Map Point</button>
                        <!-- <button type="button" class="btn btn-success btn-sm check-all-unvisited">Check All Unvisited Customer</button> -->
                    <!--     <button type="button" id="btnGet" class="btn btn-success btn-sm">Show Invoice</button>
                     -->    
                     @if(Auth::user()->role <3) 
                        <button type="button" class="btn btn-success btn-sm check-all-customers">Check All Customers</button>
                        <button type="button" class="btn btn-primary btn-sm confirm-btn send-to-unapprove" id="dis"> Send to Pending</button>
                        <button type="button" @if(Auth::user()->role < 3) data-toggle="modal" data-target="#order-unapprove-popup" @else onclick="document.getElementById('multiple-approve').submit()" @endif class="btn btn-success btn-sm app-mult">Change Seller Of These Orders</button>
                        @elseif (Auth::user()->role == 3 && ($seller_data->assign_order) == 1)
                        <button type="button" class="btn btn-primary btn-sm confirm-btn send-to-unapprove">Confirm</button>
                        <button type="button" class="btn btn-success btn-sm check-all-customers">check all</button>
                        <button type="button" data-toggle="modal" data-target="#order-unapprove-popup" class="btn btn-success btn-sm">Change Seller</button>
                        @endif
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div class="card-body filtered-by-seller">
            <div class="table-responsive">
               <table class="table table-bordered table-custom-th table-datatable table-hover" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                     <tr>
                        
                        <th>Action</th>
                        <th>Customer</th>
                        <th>Comments</th>
                        <th>Location</th>
                        <th>Total</th>
                        <th>Sub Total</th>
                        <th>Recieved</th>
                        <th>Discount</th>
                        <th>Balance</th>
                        <th>Advance</th>
                        <th>Unit</th>
                        <th>Selected Seller</th>
                        @if(Auth::user()->role < 3)
                        <th>A Ben</th>
                        <th>OT Benefit</th>
                        <th>Loc</th>
                        @endif
                        @if(Auth::user()->role !=3)
                        <th>Confirmed By</th>
                        @endif
                        <th>C Benefit</th>
                        <th>Order Taker</th>
                        <th style="width: 20px">#</th>
                        <th>Date</th>
                     </tr>
                  </thead>
                  <tbody>
                     {{$condition[$counter]->id}}
                     <input type="hidden" value="{{$counter=0}}">
                     @foreach($orders->all() as $key => $order)
                     @php
                     $now = \Carbon\Carbon::now()->toDateString();
                     $a = strtotime($order->order_date);
                     $b = strtotime($now);
                     $days_between = ceil(($a - $b) / 86400);
                     @endphp
                     @if ($order->use_stock == 0 )          
                     <tr class="dont_use_stock set-background">    
                        @elseif ( $order->cancel_status == 0 )     
                     <tr class="cancel set-background">
                        @elseif ( $order->cancel_status == 2 ) 
                     <tr class="delivery-failed set-background">
                        @elseif($order->clear == 'clear')        
                     <tr class="clear set-background">
                        @elseif( $days_between == 0 )          
                     <tr class="today-delivery set-background">
                        @elseif($days_between == -1)           
                     <tr class="delivery-missed set-background">
                        @elseif($days_between < 1)             
                     <tr class="delivery-missed-2day set-background">
                        @elseif( $order['bill_no'] == 0 )      
                     <tr class="new-customer set-background">
                        @elseif($days_between > 0)             
                     <tr class="set-background">
                        @endif
                        <input type="hidden" class="ot_id" name="ot_id" value="{{ $order->ordertaker->id }}">
                        <input type="hidden" class="area_id" name="area_id" value="{{ $order->customer->area_id }}">
                        <input type="hidden" class="seller_filter_id" name="seller_filter_id" value="{{ $order->seller->id }}">
                        <!--Buttons-->
                        
                        <td class="text-center">
                           @if(in_array($key, $keys_generated))
                           @if(Auth::user()->role <= 3)
                           @if(Auth::user()->role == 3 && ($seller_data->assign_order) == 1)
                          <!--  <a href="{{ route('edit.order' , $order->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>Edit Order</a> -->
                            <a href="javascript:;" class="btn btn-sm btn-primary view-order-details" data-toggle="modal" data-target="#edit-order-detail-popup" id="{{ $order->id }}"><i class="fa fa-edit"></i>Edit Order</a>
                           <a href="{{ route('delete.order' , $order->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i>Delete Order</a>
                           @endif    
                           @if(Auth::user()->role < 3)
                           <button onclick="UseStock({{ $order->id }})" class="btn btn-sm btn-primary " {{ $order->use_stock == 0 ? 'unchecked' : '' }}><span class="use-stock">Use Stock</span></button>
                           <a href="{{ route('edit.customer' , $order->customer_id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>Edit Customer</a>
                         <!--   <a href="{{ route('edit.order' , $order->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>Edit Order</a> -->
                         <a href="javascript:;" class="btn btn-sm btn-primary view-order-details" data-toggle="modal" data-target="#edit-order-detail-popup" id="{{ $order->id }}"><i class="fa fa-edit"></i>Edit Order</a>
                          <a href="{{ route('delete.order' , $order->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i>Delete Order</a>
                           @endif
                           <a href="{{ route('update.canceltext' , $order->id) }}" class="btn btn-sm btn-warning edit-category" data-toggle="modal" data-target="#category-popup"><i class="fa fa-reply"></i>Send to Unconfirm</a>
                           <br><a href="{{ route('customer.invoices' , $order->customer_id) }}" class="btn btn-sm prev-record btn-primary"><i class="fa fa-reply"></i>Customer Invoices</a>
                           <a href="{{ route('customer.orders' , $order->customer_id) }}" class="btn btn-sm prev-record btn-primary"><i class="fa fa-reply"></i>Customer Order</a>
                           @if ( $order->received_amount == 0)
                           <div >
                              <label class="btn btn-sm btn-warning">
                              <span>Received Subtotal</span> <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" id="btn" onclick="myFunction()" data-id="{{ $order->id }}" name="equal_order" value="red" class="js-switch1" {{ $order->received_amount == 0 ? 'unchecked' : '' }}></label>
                           </div>
                           @endif
                           @endif
                           <input type="hidden" value="{{$counter++}}">
                           @endif
                           <br>
                           <a href="javascript:;" data-toggle="modal" data-target="#order-detail-popup" class="btn btn-sm btn-success view-details" id="{{ $order->id }}"><i class="fa fa-eye"></i>Invoice Detail</a>
                           <a href="tel:{{ $order->customers->phone }}" class="btn btn-sm btn-danger phone-btn"><i class="fa fa-phone"></i>Call</a>
                           <a href="https://wa.me/92{{ $order->customers->phone }}" class="btn btn-sm btn-danger phone-btn"><i class="fa fa-phone"></i>Whtsapp</a>
                    
                           <button type="button" value="{{ $order->id }}" data-id="{{ $order->id }}"  class="btn btn-info btn-sm row-report-clicked"><i class="fa fa-print"></i> Print</button>
                           <br>
                           <label class="btn btn-default btn-sm">
                           <span>Map view</span><br><input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" value="{{ $order->customers->location_url }}" data-rowid="{{ $order->id }}" data-rowname="{{ $order->customer->user->name }}" class="add-coords js-switch" />
                           </label>
                          
                           <br>{{ $order->order_date }}
                           <br>Bill no: {{ $order['bill_no']  }}
                           <!--un hide for order taker-->
                           <br>@if(Auth::user()->role < 3 &&  $order->ot_customer_distance * 1000 > 300 )
                           <P class="brdr text-center">Phone Order/{{ $order->chk_ord_vst }}</P>
                           @endif
                           @if($order->payment_method == 'Bill to Bill')
                           <br><span class="brdr">{{ $order->payment_method }}</span>
                           @endif
                           @if ( $order['bill_no'] == 0 )
                           New
                           @endif
                           @if ($order->urgent == 'urgent')
                           <br><span style ="color: red; ">(Urgent!)</span>
                           @endif
                        </td>
                        <!--Customer Name-->
                        @if ( $order->received_amount < $order->subtotal  )
                        <td class="text-center" style="color: red" data-changein="subtotal">
                          <a href="{{ route('update.receiving' , $order->id) }}"
                           data-amount="{{ $order->amount }}" 
                           data-receivedamount="{{ $order->received_amount }}" 
                           data-subtotal="{{ $order->subtotal }}" 
                           data-amountleft="{{ $order->amount_left }}"
                           style="" class="btn btn-sm btn-warning edit-receiving" 
                           data-toggle="modal" data-target="#receiving-popup">
                           <i class="fa fa-money"> Partial Cash</i>
                         </a>
                         <br>{{ $order->customer->user->name }}/{{ $order->customers->customer_name}}<br>
                           @if( $order->clear == NULL )
                           <a  onclick = "clearOrder({{ $order->id }})" data-id="{{ $order->id }}" class="btn btn-sm btn-warning clear"><i class="fa fa-check-square">Clear</i></a>
                           @elseif( $order->clear == 'clear' )
                           <a href="{{ route('unclear.order' , $order->id) }}" class="btn btn-sm btn-warning"><i class="fa BTN-WARNING fa-times-circle">Unclear</i></a>
                           @endif
<br>
                            @if(Auth::user()->role < 3)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <span>Select Customer</span><br>
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @elseif (Auth::user()->role == 3 && $seller_data->assign_order == 1)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @endif
                        </td>
                        @elseif ( $order->received_amount > $order->subtotal && $order->amount_left > 0  )
                        <td class="text-center" style="color: #CC9A2E" data-changein="subtotal">
                          <a href="{{ route('update.receiving' , $order->id) }}"
                           data-amount="{{ $order->amount }}" 
                           data-receivedamount="{{ $order->received_amount }}" 
                           data-subtotal="{{ $order->subtotal }}" 
                           data-amountleft="{{ $order->amount_left }}"
                           style="" class="btn btn-sm btn-warning edit-receiving" 
                           data-toggle="modal" data-target="#receiving-popup">
                           <i class="fa fa-money"> Partial Cash</i>
                         </a>
                         <br>{{ $order->customer->user->name }}/{{ $order->customers->customer_name}}<br>
                           @if( $order->clear == NULL )
                           <a  onclick = "clearOrder({{ $order->id }})" data-id="{{ $order->id }}" class="btn btn-sm btn-warning clear"><i class="fa fa-check-square">Clear</i></a>
                           @elseif( $order->clear == 'clear' )
                           <a href="{{ route('unclear.order' , $order->id) }}" class="btn btn-sm btn-warning"><i class="fa BTN-WARNING fa-times-circle">Unclear</i></a>
                           @endif
<br>
                            @if(Auth::user()->role < 3)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <span>Select Customer</span><br>
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @elseif (Auth::user()->role == 3 && $seller_data->assign_order == 1)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @endif
                        </td>
                        @elseif ( $order->received_amount > $order->subtotal && $order->amount_left <= 0  )
                        <td class="text-center" style="color: #28B463" data-changein="subtotal">
                          <a href="{{ route('update.receiving' , $order->id) }}"
                           data-amount="{{ $order->amount }}" 
                           data-receivedamount="{{ $order->received_amount }}" 
                           data-subtotal="{{ $order->subtotal }}" 
                           data-amountleft="{{ $order->amount_left }}"
                           style="" class="btn btn-sm btn-warning edit-receiving" 
                           data-toggle="modal" data-target="#receiving-popup">
                           <i class="fa fa-money"> Partial Cash</i>
                         </a>
                         <br>{{ $order->customer->user->name }}/{{ $order->customers->customer_name}}<br>
                           @if( $order->clear == NULL )
                           <a  onclick = "clearOrder({{ $order->id }})" data-id="{{ $order->id }}" class="btn btn-sm btn-warning clear"><i class="fa fa-check-square">Clear</i></a>
                           @elseif( $order->clear == 'clear' )
                           <a href="{{ route('unclear.order' , $order->id) }}" class="btn btn-sm btn-warning"><i class="fa BTN-WARNING fa-times-circle">Unclear</i></a>
                           @endif
<br>
                            @if(Auth::user()->role < 3)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <span>Select Customer</span><br>
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @elseif (Auth::user()->role == 3 && $seller_data->assign_order == 1)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @endif
                        </td>
                        @elseif ( $order->received_amount == $order->subtotal && $order->amount_left <= 0 )
                        <td class="text-center" style="color: #2ECC71" data-changein="subtotal">
                          <a href="{{ route('update.receiving' , $order->id) }}"
                           data-amount="{{ $order->amount }}" 
                           data-receivedamount="{{ $order->received_amount }}" 
                           data-subtotal="{{ $order->subtotal }}" 
                           data-amountleft="{{ $order->amount_left }}"
                           style="" class="btn btn-sm btn-warning edit-receiving" 
                           data-toggle="modal" data-target="#receiving-popup">
                           <i class="fa fa-money"> Partial Cash</i>
                         </a>
                         <br>{{ $order->customer->user->name }}/{{ $order->customers->customer_name}}<br>
                           @if( $order->clear == NULL )
                           <a  onclick = "clearOrder({{ $order->id }})" data-id="{{ $order->id }}" class="btn btn-sm btn-warning clear"><i class="fa fa-check-square">Clear</i></a>
                           @elseif( $order->clear == 'clear' )
                           <a href="{{ route('unclear.order' , $order->id) }}" class="btn btn-sm btn-warning"><i class="fa BTN-WARNING fa-times-circle">Unclear</i></a>
                           @endif
<br>
                            @if(Auth::user()->role < 3)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <span>Select Customer</span><br>
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @elseif (Auth::user()->role == 3 && $seller_data->assign_order == 1)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @endif
                        </td>
                        @elseif ( $order->received_amount == $order->subtotal && $order->amount_left > 0 )
                        <td class="text-center" style="color: #CC9A2E" data-changein="subtotal">
                          <a href="{{ route('update.receiving' , $order->id) }}"
                           data-amount="{{ $order->amount }}" 
                           data-receivedamount="{{ $order->received_amount }}" 
                           data-subtotal="{{ $order->subtotal }}" 
                           data-amountleft="{{ $order->amount_left }}"
                           style="" class="btn btn-sm btn-warning edit-receiving" 
                           data-toggle="modal" data-target="#receiving-popup">
                           <i class="fa fa-money"> Partial Cash</i>
                         </a>
                         <br>{{ $order->customer->user->name }}/{{ $order->customers->customer_name}}<br>
                           @if( $order->clear == NULL )
                           <a  onclick = "clearOrder({{ $order->id }})" data-id="{{ $order->id }}" class="btn btn-sm btn-warning clear"><i class="fa fa-check-square">Clear</i></a>
                           @elseif( $order->clear == clear )
                           <a href="{{ route('unclear.order' , $order->id) }}" class="btn btn-sm btn-warning"><i class="fa BTN-WARNING fa-times-circle">Unclear</i></a>
                           @endif
<br>
                            @if(Auth::user()->role < 3)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <span>Select Customer</span><br>
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @elseif (Auth::user()->role == 3 && $seller_data->assign_order == 1)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @endif
                        </td>
                        @elseif ( $order->received_amount == 0  )
                        <td class="text-center" data-changein="subtotal">
                          <a href="{{ route('update.receiving' , $order->id) }}" 
                           data-amount="{{ $order->amount }}" 
                           data-receivedamount="{{ $order->received_amount }}" 
                           data-subtotal="{{ $order->subtotal }}" 
                           data-amountleft="{{ $order->amount_left }}"
                           style="" class="btn btn-sm btn-warning edit-receiving" 
                           data-toggle="modal" data-target="#receiving-popup">
                           <i class="fa fa-money"> Partial Cash</i>
                         </a>
                         <br>{{ $order->customer->user->name }}/{{ $order->customers->customer_name}}
                           <br>
                           @if( $order->clear == NULL )
                           <a  onclick = "clearOrder({{ $order->id }})" data-id="{{ $order->id }}" class="btn btn-sm btn-warning clear"><i class="fa fa-check-square">Clear</i></a>
                           @elseif( $order->clear == clear )
                           <a href="{{ route('unclear.order' , $order->id) }}" class="btn btn-sm btn-warning"><i class="fa BTN-WARNING fa-times-circle">Unclear</i></a>
                           @endif
<br>
                            @if(Auth::user()->role < 3)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <span>Select Customer</span><br>
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @elseif (Auth::user()->role == 3 && $seller_data->assign_order == 1)
                           <label class="btn btn-default btn-sm check-show-all-orders">
                           <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="confirm-to[]" value="{{ $order->id }}" class="approve-to" />
                           </label>
                           @endif

                        </td>
                        @endif

                        <!-- Comments Start -->

                         @if ($order->cancel_status == 1)
                        <td>
                           <a href="{{ route('update.canceltext' , $order->id) }}" style="color : black;" class="edit-category" data-toggle="modal" data-target="#category-popup">(Order In Pending)  <br> {{ $order->cancel_reason  }}</a><br>----------
                           <br>{{ $order->order_comments }}
                        </td>
                        @elseif ($order->cancel_status == 0)
                        <td style="width: 20px ; background: #007bff;">
                           <a href="{{ route('update.canceltext' , $order->id) }}" class="edit-category" style="color : white;" data-toggle="modal" data-target="#category-popup"> (Order Canelled) <br> {{ $order->cancel_reason  }}</a><br>----------
                           <br>{{ $order->order_comments }}
                        </td>
                        @else ($order->cancel_status == 2)
                        <td style="width: 20px ; background: #007bff;">
                           <a href="{{ route('update.canceltext' , $order->id) }}" class="edit-category" style="color : white;" data-toggle="modal" data-target="#category-popup"> (Delivery Failed) <br> {{ $order->cancel_reason  }}</a><br>----------
                           <br>{{ $order->order_comments }}
                        </td>
                        @endif

                        <!-- Comments end -->
                        <!-- Location Start -->
                        <td><a href="http://maps.google.com/maps?q=+{{ $order->customers->location_url }}">{{ $order->customers->area->name }} /
                           {{ $order->customers->address }}</a><br>{{ $order->customers->location_url }}<br>
                           <a href="http://maps.google.com/maps?q=+{{ $order->location_url_ot }}" target="_blank">
                           {{ $order->location_url_ot }}</a>/
                           @if(Auth::user()->role != 5)
                           @if ($order->ot_customer_distance * 1000 > 1000)
                           {{ $order->ot_customer_distance }}Km
                           @else 
                           {{ $order->ot_customer_distance * 1000 }}M
                           @endif
                           @endif
                           / {{  $order->chk_ord_vst }}
                           <br>
                           <a href="{{ route('update.area.customer' , $order->id) }}" class="change-area" data-toggle="modal" data-target="#change-area-popup"><i class="fa fa-check-square">Change Area</i></a>
                        </td>
                        <!-- Location End -->
                        <!--Total-->
                        @if ( $order->received_amount < $order->subtotal  )
                        <td style="color: red" data-changein="subtotal"><span id="order-amount-{{ $order->id }}">{{ $order->amount }}</span></td>
                        @elseif ( $order->received_amount > $order->subtotal && $order->amount_left > 0  )
                        <td style="color: #CC9A2E" data-changein="subtotal"><span id="order-amount-{{ $order->id }}">{{ $order->amount }}</span></td>
                        @elseif ( $order->received_amount > $order->subtotal && $order->amount_left <= 0  )
                        <td style="color: #28B463" data-changein="subtotal"><span id="order-amount-{{ $order->id }}">{{ $order->amount }}</span></td>
                        @elseif ( $order->received_amount == $order->subtotal && $order->amount_left <= 0 )
                        <td style="color: #2ECC71" data-changein="subtotal"><span id="order-amount-{{ $order->id }}">{{ $order->amount }}</span></td>
                        @elseif ( $order->received_amount == $order->subtotal && $order->amount_left > 0 )
                        <td style="color: #CC9A2E" data-changein="subtotal"><span id="order-amount-{{ $order->id }}">{{ $order->amount }}</span></td>
                        @elseif ( $order->received_amount == 0  )
                        <td data-changein="subtotal"><span id="order-amount-{{ $order->id }}">{{ $order->amount }}</span></td>
                        @endif
                        <!--Subtotal-->
                        @if ( $order->received_amount < $order->subtotal  )
                        <td style="color: red" data-changein="subtotal"><span id="order-subtotal-{{ $order->id }}">{{ $order->subtotal }}</span> <br> Discount: {{$order->ordertaker->customer_discount }}% <br>RS: {{ $order->discounted_subtotal }}</td>
                        @elseif ( $order->received_amount > $order->subtotal  )
                        <td style="color: #2ECC71" data-changein="subtotal"><span id="order-subtotal-{{ $order->id }}">{{ $order->subtotal }}</span> <br> Discount: {{$order->ordertaker->customer_discount }}% <br>RS: {{ $order->discounted_subtotal }}</td>
                        @elseif ( $order->received_amount == $order->subtotal && $order->amount_left <= 0 )
                        <td style="color: #2ECC71" data-changein="subtotal"><span id="order-subtotal-{{ $order->id }}">{{ $order->subtotal }}</span> <br> Discount: {{$order->ordertaker->customer_discount }}% <br>RS: {{ $order->discounted_subtotal }}</td>
                        @elseif ( $order->received_amount == $order->subtotal && $order->amount_left > 0 )
                        <td style="color: #CC9A2E" data-changein="subtotal"><span id="order-subtotal-{{ $order->id }}">{{ $order->subtotal }}</span> <br> Discount: {{$order->ordertaker->customer_discount }}% <br>RS: {{ $order->discounted_subtotal }}</td>
                        @elseif ( $order->received_amount == 0  )
                        <td data-changein="subtotal"><span id="order-subtotal-{{ $order->id }}">{{ $order->subtotal }}</span> <br> Discount: {{$order->ordertaker->customer_discount }}% <br>RS: {{ $order->discounted_subtotal }}</td>
                        @endif
                        <!--Received Amount-->
                        @if ( $order->amount_left <= 0  )
                        <td style="color: #2ECC71" data-changein="received_amount" id = "newvalue"><span id="order-received_amount-{{ $order->id }}">{{ $order->received_amount }}</span></td>
                        @elseif ( $order->received_amount == 0 && $order->subtotal != $order->received_amount )
                        <td style="color: red" data-changein="received_amount" id = "newvalue"><span id="order-received_amount-{{ $order->id }}">{{ $order->received_amount }}</span></td>
                        @elseif ( $order->received_amount < $order->subtotal && $order->amount_left > 0 )
                        <td style="color: #CC9A2E" data-changein="received_amount" id = "newvalue"><span id="order-received_amount-{{ $order->id }}">{{ $order->received_amount }}</span></td>
                        @elseif ( $order->received_amount > $order->subtotal && $order->amount_left > 0 )
                        <td style="color: #28B463" data-changein="received_amount" id = "newvalue"><span id="order-received_amount-{{ $order->id }}">{{ $order->received_amount }}</span></td>
                        @elseif ( $order->received_amount == $order->subtotal && $order->amount_left > 0 )
                        <td style="color: #CC9A2E" data-changein="received_amount" id = "newvalue"><span id="order-received_amount-{{ $order->id }}">{{ $order->received_amount }}</span></td>
                        @elseif ( $order->received_amount > 0 && $order->subtotal < 0 && $order->amount_left > 0 )
                        <td style="color: #2ECC71" data-changein="received_amount" id = "newvalue"><span id="order-received_amount-{{ $order->id }}">{{ $order->received_amount }}</span></td>
                        @endif
                        <td>{{ $order->discount }}</td>
                        <!--Balance-->
                        @if ($order->amount_left > 0)
                        <td style="color: red"><span id="order-amount_left-{{ $order->id }}">{{ $order->amount_left }}</span></td>
                        @endif
                        @if ($order->amount_left <= 0)
                        <td style="color: #2ECC71"><span id="order-amount_left-{{ $order->id }}">{{ $order->amount_left }}</span></td>
                        @endif
                        <td>{{ $order->advance }}</td>
                        <!--@if($order->received_amount > ($order->subtotal + $order->amount_left - $order->advance))-->
                        <!--  <td>$order->amount_left</td>-->
                        <!--@else-->
                        <!--  <td>{{ $order->advance }}</td>-->
                        <!--@endif-->
                        <!--Unit-->
                        @if ( $order->received_amount < $order->subtotal  )
                        <td style="color: red" data-changein="subtotal"><span id="order-unit-{{ $order->id }}">{{ $order->unit }}</span></td>
                        @elseif ( $order->received_amount > $order->subtotal  )
                        <td style="color: #2ECC71" data-changein="subtotal"><span id="order-unit-{{ $order->id }}">{{ $order->unit }}</span></td>
                        @elseif ( $order->received_amount == $order->subtotal && $order->amount_left <= 0 )
                        <td style="color: #2ECC71" data-changein="subtotal"><span id="order-unit-{{ $order->id }}">{{ $order->unit }}</span></td>
                        @elseif ( $order->received_amount == $order->subtotal && $order->amount_left > 0 )
                        <td style="color: #CC9A2E" data-changein="subtotal"><span id="order-unit-{{ $order->id }}">{{ $order->unit }}</span></td>
                        @elseif ( $order->received_amount == 0  )
                        <td data-changein="subtotal"><span id="order-unit-{{ $order->id }}">{{ $order->unit }}</span></td>
                        @endif
                        <td>{{  $order->assignedseller->name }}</td>
                        @if(Auth::user()->role < 3)
                        <td>{{ $order->subtotal - $order->ot_benefit - $order->p_amount - $order->discount}}</td>
                        <td>{{ $order->ot_benefit }}</td>
                        <td>{{ $order->location_url_ot }}</td>
                        @endif
                        @if(Auth::user()->role !=3)
                        <td>{{ $order->seller->name }}</td>
                        @endif
                        <td>{{ $order->c_benefit }}</td>
                        <td>{{ $order->ordertaker->name }}</td>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->created_at }}</td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
         <div class="card-footer small text-muted filtered-by-seller">
            Total: <b>{{ $orders->sum('amount') }}</b> 
            | 
            @if ($order->ordertaker->discount_on_off == 1)
            Sub Total: <b>{{ $orders->sum('subtotal') - ( $orders->sum('subtotal') * ($order->ordertaker->customer_discount/100)) }}</b> 
            @else
            Sub Total: <b>{{ $orders->sum('subtotal') }}</b>
            @endif
            | 
            Balance: <b>{{ $orders->sum('amount_left') }}</b> 
            | 
            Rec Amount: <b>{{ $orders->sum('received_amount') }}</b> 
            | 
            Order Ben: <b>{{ $orders->sum('ot_benefit') }}</b> 
            |
            @if(Auth::user()->role < 3)
            | 
            A ben Ben: <b>{{ $orders->sum('subtotal') - $orders->sum('ot_benefit') - $orders->sum('p_amount') - $orders->sum('discount') }}</b> 
            |
            @endif
            C Ben: <b>{{ $orders->sum('c_benefit') }}</b> 
            | 
            Advance: <b>{{ $orders->sum('advance') }}</b> 
            | 
            Units: <b>{{ $orders->sum('unit') }}</b>
         </div>
         <div class="show-filtered-by-seller">
            <span class="sr-only">Here it will show/append the seller filter table record</span>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="product-report-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" style="width: 100%" id="exampleModalLabel">Product Sell Report <small></small></h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="table-responsive">
               <table class="table table-bordered table-custom-th" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                     <tr>
                        <th>P.ID</th>
                        <th>P.Name</th>
                        <th>Units</th>
                        @if(Auth::user()->role < 3)
                        <th>Amount</th>
                        @endif
                     </tr>
                  </thead>
                  <tbody>
                     <?php $show_in_modal = 0; ?>
                     @foreach($product_report as $preport)
                     @if($preport['amount'] != 0 || $preport['unit'] != 0)
                     <tr>
                        <td>{{ $preport['id'] }}</td>
                        <td>{{ $preport['name'] }}</td>
                        <td>{{ $preport['unit'] }}</td>
                        @if(Auth::user()->role < 3)
                        <td>{{ $preport['amount'] }}</td>
                        @endif
                        <?php $show_in_modal += $preport['amount']; ?>
                     </tr>
                     @endif
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
         <div class="alert alert-info text-left pull-left">
            @if(Auth::user()->role < 3)
            <b>P.Total: </b>{{ $show_in_modal }}
            @endif
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
         </div>
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
            @if(Auth::user()->role <= 3)
            <button onclick="window.location='printerplus://send?text='+document.getElementById('p').innerHTML;">
            Send to Printer+
            </button>
            @endif
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="category-popup" tabindex="-1" role="dialog" aria-labelledby="category-popup" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5>Order Status</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="post" action="" class="category-form">
               {{ csrf_field() }}
               <div class="form-group">
                  <label>What Happene to this Order?</label>
                  <input type="text" name="cancel_reason" required="" class="form-control old-category" value="{{ $order->cancel_reason }}" onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp'>
                  <br>
                  <h5>Select Order State</h5>
                  <br>
                  <select class="form-control" name="cancel_status" required="" >
                     <option value="" disabled selected>Select State</option>
                     <option name="cancel_status" value="{{ 1 }}" >Pending</option>
                     <option name="cancel_status" value="{{ 2 }}" >Delivery Failed</option>
                     <option name="cancel_status" value="{{ 0 }}" >Cancel</option>
                  </select>
                  <h5>Send Urgent</h5>
                  <input class="js-switch" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="urgent"  >
                  <br>
                  <h5>Set Delivery Date</h5>
                  <input type="date" name="order_date" value="{{  $order->order_date  }}" required="">
               </div>
               <div class="form-group">
                  <button class="btn btn-primary btn-block">Save</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!--Receiving Modal-->
<div class="modal fade" id="receiving-popup" tabindex="-1" role="dialog" aria-labelledby="receiving-popup" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5>Order Status</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="post" action="" class="receiving-form">
               {{ csrf_field() }}
               <div class="form-group text-center">
                  <div  class="btn-grd-info">
                     <label>
                        <h5>Old Order Data</h5>
                     </label>
                  </div>
                  <div class="">
                     <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="sorting">
                           <br>
                           <tr class="text-center" style="color: red;">
                              <td> <b>Subtotal :</b>
                                 <span class="show-subtotal"></span> 
                              </td>
                           </tr>
                           <tr class="text-center" style="color: red;">
                              <td><b>Balance :</b>
                                 <span class="show-amount_left"></span> 
                              </td>
                           </tr>
                           <tr class="text-center" style="color: red;">
                              <td><b>Total :</b>
                                 <span class="show-amount"></span> 
                              </td>
                           </tr>
                        </thead>
                     </table>
                  </div>
                  <!--<br>-->
                  <!--<p styel = "text-align : center; ">Add Receiving</p>-->
                  <br>
                  <div class="btn-grd-primary">
                     <h5>Update Data</h5>
                  </div>
                  <br>
                  <label>Enter Receiving</label>
                  <input type="number" name="received_amount" value="" class="form-control old-receiving" >
                  @if(Auth::user()->role <= 2)
                  <label>Set Balance</label>
                  <input type="number" name="amount_left" value="" class="form-control old-receiving">
                  <label>Enter Comments</label>
                  <textarea class="form-control" rows="4" placeholder="Enter Comments" name="order_comments" maxlength = "200"></textarea>
                  @endif
               </div>
               <div class="form-group">
                  <button class="btn btn-primary btn-block">Save</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!--Google Map modal-->
<div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Shop Navigator</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <div id="map-total-distance"></div>
          <div id="map-total-duration"></div>
            <div id="region-map" style="height: 400px"></div>
         </div>
         <div class="modal-footer">
            <a class="close btn btn-sm btn-grd-primary" type="button" data-dismiss="modal" aria-label="Close">
               <span style="color: white;">Close</span>
            </a>
         </div>
      </div>
   </div>
</div>
<!--Assign order to seller -->
<div class="modal fade" id="order-unapprove-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Directly Send To Pending Invoice ? <small></small></h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <p>
               Do you want to send selected orders to pending invoices ?
            </p>
            <div class="form-group">
               <select name="seller_id" required class="form-control" id="seller_id_popup_confirm">
                  <option value="" disabled value="" selected>Please Select Seller</option>
                  @if(!empty($sellerUser))
                  @foreach($sellerUser as $item)
                  <option value="{{$item->id}}">{{ $item->name }}</option>
                  @endforeach
                  @endif
               </select>
            </div>
         </div>
         <div class="modal-footer">
            <a  onclick="ChangeSeller();"class="btn btn-secondary">Seller Confirm</a>
            @if(Auth::user()->role <= 2)
            <button class="btn btn-primary" data-toggle="modal" data-target="#send-pending-modal" >Pending Invoices</button>
            @endif
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="send-pending-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-header bg-danger">
            <h5 class="modal-title" id="delete-modal-label" style="color: white">Are You Sure ?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">Are you sure to Confirm these Orders!</div>
         <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">No</button>
            <a class="btn btn-danger confirm-btn send-to-unapprove" style="color: white">Yes</a>
         </div>
      </div>
   </div>
</div>
<!--Change area-->
<div class="modal fade" id="change-area-popup" tabindex="-1" role="dialog" aria-labelledby="change-area-popup" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5>Want to Change Area?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="post" action="" class="category-form">
               {{ csrf_field() }}
               <div class="form-group">
                  <label>Area Name</label>
                  <!--<input type="text" name="area" value="{{ $order->area->name }}" required="">-->
                  <select class="form-control" name="area" id="area-select">
                     <option value="" disabled>Select Area</option>
                     @foreach($allareas as $a)
                     <option value="{{ $a->id }}" @if($a->id == $order->customers->area_id) selected @endif>{{ $a->name }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <button class="btn btn-primary btn-block">Change Area</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!--change area end-->
<form style="display: none" method="post" action="{{ route('approve.order.multiple') }}" id="multiple-approve">
   {{ csrf_field() }}
   <!--Question does this cause issue?-->
   @if(Auth::user()->role < 3)
   <input type="hidden" name="send_to_unapprove" value="" />
   @else(Auth::user()->role == 3)
   <input type="hidden" name="processed_order" value="{{ Auth::id()}}" />
   @endif
   <input type="hidden" name="selected_seller" value="{{ Auth::id() }}" id="input-seller">
</form>
<form style="display: none" class="my-form" method="post" action="{{ route('approve.order.multiple') }}" id="multiple-approve-form">
   {{ csrf_field() }}
   <!--Question does this cause issue?-->
   @if(Auth::user()->role < 3)
   <input type="hidden" name="send_to_unapprove" value="" />
   @endif
   <input type="hidden" name="selected_seller" value="{{ Auth::id() }}" id="input-seller">
</form>
<div class="modal fade" id="edit-order-detail-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Old Order Details <small></small></h5>
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
<script src="{{ asset('assets/js/googleapi.js') }}"></script>
<script type="text/javascript">
   $(document).on('click', '.view-details', function(){
       var param = $(this).attr('id');
       console.log(param);
       $('#order-detail-popup .modal-title small').text('(' + $(this).closest('tr').find('input').val() + ')');
       $('#order-detail-popup .modal-body').html('<h6 class="text-center">Loading ..</h6>');
       $.get('{{ route("order.detail") }}/' + param , function(success){
         $('#order-detail-popup .modal-body').html(success);
       });
     });
   
  
     $(document).on('click', '.check-all-customers', function(){
         if($(this).hasClass('revert')){
             var is_rev = true;
             $(this).removeClass('revert');
             $(this).text('check all');
         }
         else{
             var is_rev = false;
             $(this).addClass('revert');
             $(this).text('uncheck all');
         }
         var mainClassName;
         if($(this).hasClass('showFilteredBySeller')){
           mainClassName = ".show-filtered-by-seller";
         }else{
           mainClassName = ".filtered-by-seller";
         }
         $(mainClassName+' tbody .approve-to').each(function(confirm){
             if(!is_rev){
                 $(this).prop('checked' , 'checked');
                  $(this).bootstrapToggle('on'); 
                  $(".set-background").addClass('check-all-color');
                 $('#multiple-approve').append($(this).closest('label').html());
                 $('#multiple-approve .approve-to').last().attr('checked' , 'checked');
                 $('#multiple-approve-form').append($(this).closest('label').html());
                 $('#multiple-approve-form .approve-to').last().attr('checked' , 'checked');
             }
             else{
                 $(this).prop('checked' , false);
                  $(this).bootstrapToggle('off');
                 $(".set-background").removeClass('check-all-color');
                 $('#multiple-approve .approve-to').each(function(){
                     $(this).remove();
                 });
                  $('#multiple-approve-form .approve-to').each(function(){
                     $(this).remove();
                 });
             }
         });
     });
     
     $(document).on('change', '#multiple-approve .approve-to', function(){
         if(!this.checked){
             $(this).remove();
         }
     });
      $(document).on('change', '#multiple-approve-form .approve-to', function(){
         if(!this.checked){
             $(this).remove();
         }
     });
     
     $(document).on('change', "select#seller_id_popup_confirm", function(e){
       $("#multiple-approve #input-seller").val($(this).val());
     });
     $(document).on('change', "select#seller_id_popup_confirm", function(e){
       $("#multiple-approve-form #input-seller").val($(this).val());
     });
     
     $(document).on('change', 'tbody .approve-to', function(){
      
         if(this.checked){
              $('#multiple-approve').append($(this).closest('label').html());
             $('#multiple-approve .approve-to').last().attr('checked' , 'checked');
            $('#multiple-approve-form').append($(this).closest('label').html());
             $('#multiple-approve-form .approve-to').last().attr('checked' , 'checked');
         }
         else{
             var this_val = $(this).val();
             $('#multiple-approve .approve-to').each(function(){
                 if($(this).val() == this_val){
                     $(this).remove();
                 }
             });
              $('#multiple-approve-form .approve-to').each(function(){
                 if($(this).val() == this_val){
                     $(this).remove();
                 }
             });
         }
     });
     var ot_filter_val = 'yes',ot_area_val = 'yes', seller_filter_val = 'yes';
     function runtimeFilter()
     {
       if(ot_filter_val == 'yes' && ot_area_val == 'yes'){
           $('.ot_id').each(function(){
             $(this).parent().show();
           });
           $('#dataTable_info').show();
       }
       else{
         $('.ot_id').each(function(){
           var flag = false;
           if((ot_filter_val != 'yes' && ot_area_val != 'yes'))
           {
               if(ot_filter_val == $(this).val() && ot_area_val == $(this).closest('tr').find('.area_id').val())
                   flag = true;
           }
           else if(ot_filter_val == $(this).val())
               flag = true;
           else if(ot_area_val == $(this).closest('tr').find('.area_id').val())
               flag = true;
   
           if(!flag){
             $(this).parent().hide();
             $('#dataTable_info').hide();
           }
           else{
             $(this).parent().show();
           }
         });
       }
     }
     $(document).on('change', '.ot_filter', function(){
         ot_filter_val = $(this).val()
         runtimeFilter();
   });
   $(document).on('change', '.ot_area', function(){
         ot_area_val = $(this).val()
         runtimeFilter();
   });
   $(document).on('change', '#seller_filter', function(){
     seller_filter_val = $(this).val();
     $(".filtered-by-seller").hide();
     $(".show-filtered-by-seller").html("<div class='spinner-border text-primary'></div>");
     $("button.check-all-customers").addClass('showFilteredBySeller');
     if(seller_filter_val != 'yes' && seller_filter_val > 0){
       $.ajax({
         type: 'get',
         url: "{{ route('filter-by-seller') }}",
         data: {
           id: seller_filter_val
         },
         success: function(res){
           if(res.success){
             $(".show-filtered-by-seller").show();
             $(".show-filtered-by-seller").html(res.html);          
           }else{
             $(".filtered-by-seller").show();
             $(".show-filtered-by-seller").hide();
             $("button.check-all-customers").removeClass('showFilteredBySeller');
           }
         },
         error: function(err){
           alert('Something went wrong!');
           $("button.check-all-customers").removeClass('showFilteredBySeller');
         }
       });
     }else{
       $(".filtered-by-seller").show();
       $(".show-filtered-by-seller").hide();
       $("button.check-all-customers").removeClass('showFilteredBySeller');
     }
   });
   // selected row
   $('tr').find('input').on('click', function() {
       if ($(this).prop('checked') === true) {
          $(this).closest('tr').addClass('highlighted'); 
       } else {
          $(this).closest('tr').removeClass('highlighted'); 
       }
   });
   // cancel reason modal
   $(document).on('click', '.edit-category', function(event){
       event.preventDefault();
       var cTR = $(this).closest('tr');
       var c_name = cTR.find('td').eq(1).text();
       $('.old-category').val(c_name)
       $('.category-form').attr('action' , $(this).attr('href'));
       $('#category-popup .modal-title').html('Edit <b>' + c_name + '</b>');
       $('#category-popup .modal-footer button').text('Update Category');
   });
   
   // show or hide input field
   //  $(document).ready(function(){
   //   $("#btn").click(function(){
   //     $("#myDiv").toggle();
   //   });
   // });
   
   // if ( window.history.replaceState ) { 
   //         window.history.replaceState( null, null, window.location.href ); 
   //     }
      
      // print report and check check box
       $("body").delegate('.map-checked-box', "click", function(){
       //  console.log("this click =" , $(this).data('id'));
     var dataID = $(this).data('id');
     var status = true;
     if($(this).prop('checked') == false){
       status = false;
       $(this).bootstrapToggle('off'); 
     }
     console.log("this click =" , status , dataID);
     $(".approve-to[value='"+dataID+"']").prop('checked', status);
      if(status == true){
         var val = "on";
      }else{
         var val = "off";
      }
     $(".approve-to[value='"+dataID+"']").bootstrapToggle(val);
   });
   
   $("body").delegate('.map-report-clicked, .row-report-clicked', 'click', function(){
         var param = $(this).data('id');
         $.get('{{ route("order.detail") }}/' + param , function(success){
           $('#order-detail-popup .modal-body').html(success);
           window.location='printerplus://send?text='+document.getElementById('p').innerHTML;
         });
       });
   
</script>
<script>
   var start_point = null;
   var end_point = null;
   var map;
   var directionsDisplay;
   function getLocation() {
     if (navigator.geolocation) {
       return navigator.geolocation.getCurrentPosition(showPosition, showError);
     } else {
       alert("Try any other browser");
     }
   }
   function showPosition(position) {
       start_point = position.coords.latitude+','+position.coords.longitude;
   }
   function showError(error) {
     switch(error.code) {
       case error.PERMISSION_DENIED:
         alert("User denied the request for Geolocation.");
         break;
       case error.POSITION_UNAVAILABLE:
         alert("Location information is unavailable.");
         break;
       case error.TIMEOUT:
         alert("The request to get user location timed out.");
         break;
       case error.UNKNOWN_ERROR:
         alert("An unknown error occurred.");
         break;
     }
   }
   getLocation();
   var locations = [];
   var coords = [];
   $('.show-map').click(function(){
       coords = [];
       var current_coords = end_point != null ? end_point : start_point;
       $('.add-coords:checked').each(function() {
           coords.push({
               "points": $(this).val(),
               "info": $(this).data('rowname'),
               "row_check": $(this).data('rowid')
           });
       });
         console.log("this cords =" , coords);
       locations = [];
       s_points = start_point.split(',');
       locations.push([
           'Your Location', s_points[0], s_points[1]
       ]);
       $.ajax({
           url: "{{ route('get.nearest.shop') }}",
           type: "POST",
           data: {
               _token: "{{ csrf_token() }}",
               coords: coords,
               current_coords: current_coords
           },
           success: function(response){
               for(var i=0;i<24;i++)
               {
                   if(typeof response[i] !== 'undefined')
                   {
                       var points = response[i][0].split(',');
                       locations.push([response[i][1], points[0], points[1], response[i][2] ]);   
                   }
               }
               initMap();     
           }
       });
   });
   
   var geocoder;
   var map;
   var directionsDisplay;
   function initMap() {
     var directionsService = new google.maps.DirectionsService();
     var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});
     var map = new google.maps.Map(document.getElementById('region-map'), {
       zoom: 10,
       center: new google.maps.LatLng(-33.92, 151.25),
       mapTypeId: google.maps.MapTypeId.ROADMAP
     });
     directionsDisplay.setMap(map);
   
     var request = {
       travelMode: google.maps.TravelMode.DRIVING,
       optimizeWaypoints: true
     };
     for (var i = 0; i < locations.length; i++) {
       var marker = new google.maps.Marker({
         position: new google.maps.LatLng(locations[i][1], locations[i][2]),
         title: locations[i][0]
       });
       var infowindow = new google.maps.InfoWindow({
               content: locations[i][0]
           });
       
       marker.addListener('click', function() {
           infowindow.open(map, marker);
       });
   
       if (i == 0) {
         request.origin = marker.getPosition();
         request.destination = marker.getPosition();
       }
       // else if (i == locations.length - 1) request.destination = marker.getPosition();
       else {
         if (!request.waypoints) request.waypoints = [];
         request.waypoints.push({
           location: marker.getPosition(),
           stopover: true
         });
       }
   
     }
     directionsService.route(request, function(result, status) {
       if (status == google.maps.DirectionsStatus.OK) {
         directionsDisplay.setDirections(result);
         var waypointOrder = result.routes[0].waypoint_order;
         // console.log(JSON.stringify(waypointOrder));
         
           var totalDistance = 0; // Added by Izaz
           var totalDuration = 0; // Added by Izaz
   
         var legs = result.routes[0].legs;
         
         for (var i = 0; i < legs.length; i++) {
           var marker = new google.maps.Marker({
             position: legs[i].start_location,
             map: map,
             label: {
               text: (i + 1).toString(),
               color: 'white'
             }
           });
           var title = '';
           console.log("location =" , locations[i][3]);
           var otherData = 
           "<button class='btn btn-primary btn-sm map-report-clicked mr-2' data-id='"+ locations[i][3] +"'>Print</button> <button class='btn btn-primary btn-sm open-map mr-1' data-id='"+ locations[i][2] +"'>Direction</button> <input type='checkbox'  class='map-checked-box mr-1' data-id='"+ locations[i][3] +"' >";
           if(i == 0) {
             title = locations[i][0] + otherData;
           } else {
             var order = waypointOrder[i - 1];
             title = locations[order + 1][0] + " <button class='btn btn-primary btn-sm map-report-clicked mr-2' data-id='"+ locations[order + 1][3] +"'>Print</button> <button class='btn btn-primary btn-sm open-map mr-1' data-id='"+ locations[order + 1][3] +"'>Direction</button> <input type='checkbox' class='map-checked-box mr-1' data-id='"+ locations[order + 1][3] +"' >";
           }
           addInfoWindow(marker, title);
           
           // Edited by Izaz
           totalDistance += legs[i].distance.value;
           totalDuration += legs[i].duration.value;
         }
         
           $("#map-total-distance").text('Total distance: '+ Math.round( totalDistance / 1000)+' kilometers' );
           $("#map-total-duration").text('Total duration: '+ Math.round(totalDuration / 60)+' minutes' );
       }
     });
   }
   function addInfoWindow(marker, title) {
     var infowindow = new google.maps.InfoWindow({
         content: title
     });
     marker.addListener('click', function() {
         infowindow.open(map, marker);
          marker.setIcon('http://maps.google.com/mapfiles/ms/micons/orange.png');
     });
   }
   google.load("maps", "3.exp", {callback: initMap, other_params:'key=AIzaSyD4DT5qvOftiCuda7_7Bb6Uj19x_eo_pyk&callback=initMap&libraries=&v=weekly&channel=2'});
</script>
<!--<script async defer-->
<!--        src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap">-->
<!--    </script>-->
<script type="text/javascript">
   $('.change-area').click(function(event){
     event.preventDefault();
     var cTR = $(this).closest('tr');
     var c_name = cTR.find('td').eq(1).text();
     $('.old-category').val(c_name)
     $('.category-form').attr('action' , $(this).attr('href'));
    // $('#change-area-popup .modal-title').html('Edit <b>' + c_name + '</b>');
     $('#change-area-popup .modal-footer button').text('Update Category');
   });
   
</script>
<script type="text/javascript">
   $(function () {
       $(document).on('click', "#btnGet" ,function () {
           //Create an Array.
           var selected = new Array();
   
           //Reference the CheckBoxes and insert the checked CheckBox value in Array.
           $(".check-show-all-orders input[type=checkbox]:checked").each(function () {
               selected.push(this.value);
           });
           
           var new_array = selected.join(":");
           //Display the selected CheckBox values.
           if (selected.length > 0) {
               window.location.href = "{{ route('order.detail.multiple') }}?ids="+new_array;
           }
       });
   });
   
</script>
<script type="text/javascript">
   function storesortedlist(){
       var values = $('#area-id').val();
     
        $.ajax({
          type: "GET",
          dataType: "json",
          url: '{{ route('store.seller.report') }}',
          data: {seller_id : values}, 
          success: function (data) {
               if(data.success == true){
              toastr.success(data.message);
              var getsellerreport = data.getsellerreport;
              $("#fin").addClass("d-none");
              $("#menu").removeClass("d-none");        
               var output = `<tbody>
              ${getsellerreport.map(function(preport) {
                return `<tr><td>${preport.product_id}</td>`+
                       `<td>${preport.unit}</td>'
                       '<td>${preport.amount}</td><tr>`;
              }).join('\n')}
            </tbody>`;
            var a = "<button  onclick='deletestoresortedlist();' class='btn btn-danger'>Delete seller report</button>";
            document.getElementById("demo").innerHTML = output;
            document.getElementById("sho-btn").innerHTML = a;
            document.getElementById("del-btn").style.visibility = 'hidden';
         
               } else {
                       toastr.error(data.message);
               }
          },
      });
      
   
   }
   function deletestoresortedlist(){
      var values = $('#area-id').val();
     
        $.ajax({
          type: "GET",
          dataType: "json",
          url: '{{ route('delete.seller.report') }}',
          data: {seller_id : values}, 
          success: function (data) {
               if(data.success == true){
                   
                       toastr.success(data.message);
                       var d = document.getElementById("div1");
                          d.className += "d-none";
                          var a = "<button  onclick='storesortedlist();' class='btn btn-primary'>Store seller report</button>";
            
                          document.getElementById("del-btn").style.visibility = 'hidden';
                          document.getElementById("sho-btn").innerHTML = a;
            
               } else {
                       toastr.error(data.message);
               }
          },
      });
   
   }
   
   function ChangeSeller(){
   $(".my-form").submit();
   }
   
</script>
@include('orders.order_blade_js')
@endpush
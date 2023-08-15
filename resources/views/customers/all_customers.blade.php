@extends('layouts.app')
@section('title') All @endsection
@section('content')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
  .profit-paid{
  background: #00FB63;
  }
  .visitclear{
  background:green;
  }
  .callcustomer{
  background:yellow;
  }
  .customer-pending {
  background:pink;
  }
  .deletecustomer{
  background:light-red;
  }
  .productShowcaseTitle {
  height: 40px;
  width: 99.99%;
  background-color: #f1c40f;
  vertical-align: middle;
  line-height: 40px;
  border-radius: 20px 20px 0 0;
  }
  .inside-check-box {
  height: 40px;
  width: 60%;
  background-color: #f1c40f;
  vertical-align: middle;
  text-align: center;
  line-height: 40px;
  border-radius: 20px 20px 0 0;
  }
</style>
@endpush
<!-- Breadcrumbs-->
<!-- <ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="#">Customers</a>
  </li>
  <li class="breadcrumb-item active">All Customers Balance {{ $total_credit ?? '' }}</li>
  </ol> -->
<div class="page-header card">
  <div class="card-block">
    <h5 class="m-b-10 text-center">All Customers</h5>
    <p class="text-muted m-b-10 text-center">Customers And Details</p>
    <!--  <ul class="breadcrumb-title b-t-default p-t-10">
      <li class="breadcrumb-item">
         <a href="index.html"> <i class="fa fa-home"></i> </a>
      </li>
      <li class="breadcrumb-item"><a href="#!">All Customers</a>
      </li>
      <li class="breadcrumb-item"><a href="#!">All Categories</a>
         </li> -->
    <!--       </ul>  -->
    <div class="card-header">
      <!-- <i class="fa fa-table"></i> Customers List -->
      <button class="btn btn-info btn-sm show-map" type="button" data-toggle="modal" data-target="#map-modal">Show Map</button>
      <button class="btn btn-info btn-sm show_all_point" type="button" data-toggle="modal" data-target="#map-modal-points">Show All Points</button> 
      <button type="button" class="btn btn-success btn-sm check-all">Check All</button>
      <button type="button" class="btn btn-success btn-sm check-all-unvisited">Check All Unvisited Customer</button>
      <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#change_area_multiple-popup">Change Selected Customer Area</button>
      <!--   <button onclick='getAssignedCustomer();' class="btn btn-sm btn-info"> Show New Assigned Customer</button>
        <button data-toggle="modal" data-target="#now_of_days" class="btn btn-sm btn-info"> Show New 30 Days Customer</button> -->
      <div>
        <select class="form-control js-example-basic-multiple" multiple="multiple" name="area_id[]" id="are-id" data-placeholder="Choose a Area...">
          <option value="">Load Area</option>
          @foreach ($areas as $ar)
          <option value="{{ $ar->id }}">{{$ar->name}}</option>
          @endforeach
        </select>
        <button onclick="getdata();" class="ml-3 mt-0 mb-0 btn btn-sm btn-info pull-right form-header btn-block " >Use Current Location</button>
        
        <div class="d-none d-n">
        <button type="area-btn" onclick="selectedarea();" class="ml-3 mt-0 mb-0 btn btn-sm btn-info pull-right form-header btn-block form_submit" disabled="">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--           <div style="padding: 20px;" class="row">
  <div class="col-md-3">
    <label>Filter By Area</label>
  </div>
  <div class="col-md-3"> -->
<!--                     <select class="form-control" data-placeholder="sorted list...">
  <option value="">Sorted List</option>
  @foreach ($customers as $ar)
            <option>{{$ar->user->name ?? '-'}}</option>
          @endforeach
          </select>
  <button onclick="storesortedlist();" class="ml-3 mt-0 mb-0 btn btn-sm btn-info pull-right form-header btn-block " >Store sorted list</button>        
  -->            
<!--    </div>
  </div> -->
<!--              
  @if( Auth::user()->role < 3 )
  <div class="form-group">
  <label>Change Area of Multiple Customers</label>
  
  @if( Auth::user()->role < 3 )
  
  <button class="btn btn-info btn-sm show-map" type="button" data-toggle="modal" id="change_area_multiple" data-target="">Change Multiple Area</button>
   <button class="btn btn-info btn-sm show-map" type="button" data-toggle="modal" id="un-visit-customer" data-target="">Un Visit Customer</button>
  @endif
  </div>
  <div class="form-group">
  <label>Change Admin Of Multiple Customers</label>
  <select class="form-control" id="admin-select">
  <option value="" disabled>Select Admin</option>
  @foreach($subadmins as $a)
  <option value="{{ $a->id }}"> {{ $a->name }}</option>
  @endforeach
  </select>
  @if( Auth::user()->role < 3 )
  <button class="btn btn-info btn-sm show-map" type="button" data-toggle="modal" id="change_creaetd_by" data-target="">Change Multiple Admin</button>
  @endif
  </div>
  @endif -->
<!--              
  <a onclick='getLocationn(); myStopFunction();' class="btn btn-sm btn-info remove-d-none visitv">Use Current Location</a>
  -->
  <input type="hidden" autocomplete="off" class="form-control" type="text" id="ot-value-visit" placeholder="Enter Cordinates Only" name="location_url_ot_visit">
  <input type="hidden" class="form-control" type="text" id="area_location"   placeholder="Enter Cordinates Only" name="area_location"> 
<div id="map-layer"></div>
<div class="row">
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> <b><?php echo empty($subadmin_name) ? '' : $subadmin_name ?></b> Customers List
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="example" class="table table-bordered table-custom-th" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Action</th>
                <th>Customers Details</th>
              </tr>
            </thead>
            <form method="post" action="route('change.area.multiple')">
            <tbody>
              @foreach($customers as $key => $customer)
              @if ($customer->ot_del_customer == 1)
              <tr class="deletecustomer">
                @elseif ($customer->call_customer == 1)
              <tr class="callcustomer">
                @elseif ($customer->status == 10)
              <tr class="profit-paid">
                @elseif ($customer->visit_clear == 1)
              <tr class="visitclear">
                @elseif ($customer->customer_pending == 1)
              <tr class="customer-pending">
                @else 
              <tr>
                @endif
                <td class="text-center">
                  <!--                     @if($customer->customer_pending == 0)
                    <div class="btn-primary btn-group mid " >
                     <label>
                       <button onclick='getLocationn(); myStopFunction();' class="btn btn-sm btn-info remove-d-none visitv">Use Current Location</button>
                        <div class="menu" style="display: none;">
                          <span>Visit Clear</span><br><input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $customer->id }}" name="visit_clear" class="js-switch2" {{ $customer->visit_clear == 1 ? 'checked' : '' }}>
                     </label>
                     </div>
                    </div>
                             
                    @endif -->
                  <br>
                  @if(Auth::user()->role == 1 || $customer->created_by == Auth::id())
                  <div style="color:white;">
                    <button class="btn btn-sm btn-warning" data-rowid="{{ $customer->id }}" disabled="">
                      <span>
                        <li class="fa fa-phone"> Call to Customer</li>
                      </span>
                      <input type="checkbox" id ="th-{{ $customer->id }}"  data-toggle="toggle" data-onstyle="success" data-size="xs" data-id="{{ $customer->id }}"  name="call" id="call" class="js-switch call" {{ $customer->call_customer == 1 ? 'checked' : '' }}>
                    </button>
                  </div>
                  <div style="color:white;">
                    <button class="btn btn-sm btn-danger th" data-rowid="{{ $customer->id }}" disabled="">
                      <span>
                        <li class="fa fa-warning"> In Pending</li>
                      </span>
                      <input type="checkbox"  data-toggle="toggle" data-onstyle="danger" data-size="xs" data-id="{{ $customer->id }}" name="customer_pending" class="js-switch js-switch1" {{ $customer->customer_pending == 1 ? 'checked' : '' }}>
                    </button>
                  </div>
                  <!--<div style="color:white;">-->
                  <!--  <button class="btn btn-sm btn-primary" data-rowid="{{ $customer->id }}" disabled="">-->
                  <!--    <span>-->
                  <!--      <li class="fa fa-map-marker"> Map View</li>-->
                  <!--    </span>-->
                  <!--    <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs"  class="parent1 js-switch" value="{{ $customer->id }}" />-->
                  <!--    <br><input type="checkbox" style="opacity: 0" name="checkbox[]" value="{{ $customer->location_url }}" data-rowid="{{ $customer->id }}" class="add-coords child approve-to" />-->
                  <!--  </button>-->
                  <!--  <input type="checkbox" style="display: none;"   name="checkbox1[]" value="{{ $customer->user->name ?? '-' }}~{{ $customer->location_url }}~{{ $customer->id }}" data-rowid="{{ $customer->id }}" data-loc="{{ $customer->location_url }}" class=" child approve-to" />-->
                  <!--  <input class="form-control customer-loc" style="display: none;" type="text" data-rowid="{{ $customer->id }}" placeholder="Enter Cordinates Only" value="{{ $customer->location_url }}">-->
                  <!--</div>-->
                  <br>
                  @if(!empty(asset($customer->image)))
                  <a href="javascript:;" data-toggle="modal" data-target="#agreement-popup" class="btn btn-info btn-sm see-ag">
                  <span id="ag-img" style="display: none;">{{ asset($customer->image) }}</span>
                  <i class="fa fa-image"></i>
                  Customer Agreement
                  </a><br>
                  @endif
                  <a href="{{ route('customer.invoices' , $customer->id) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"> Customer Invoices</i></a><br>
                  @endif
                  @if(Auth::user()->role < 3)
                  <a href="{{ route('customer.orders' , $customer->id) }}"  class="btn btn-sm btn-success"><i class="fa fa-eye"></i>Customer Orders</a><br>
                  <a class="btn btn-sm btn-danger" data-id="{{ $customer->id }}" data-toggle="modal" data-target="#delete-customer-modal" style="color: white;"><i class="fa fa-trash"></i>Delete Customer</a><br>
                  <a href="{{ route('edit.customer' , $customer->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>Edit Customer</a><br>
                  @if( $customer->ot_del_customer == 1 )
                  <a href="{{ route('restore.customer' , $customer->id) }}" class="btn btn-sm btn-danger "><i class="fas fa-reply"></i>Restore Customer</a><br>
                  @endif
                  @endif
                  @if( Auth::user()->role == 5 )
                  <a href="{{ route('delete.customer.by.ot' , $customer->id) }}" style="color: white;" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i>Delete Customer</a><br>
                  <a href="{{ route('edit.customer' , $customer->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>Edit Customer</a><br>
                  @endif
                  @if ( $customer->getlastorder($customer->id) === null )
                  <a href="tel:{{ $customer->phone }}" class="btn btn-sm btn-danger phone-btn call"><i class="fa fa-phone"></i>Call / Order exist</a><br>
                  @else
                  <a href="tel:{{ $customer->phone }}" class="btn btn-sm btn-danger phone-btn call"><i class="fa fa-phone"></i>Call</a><br>
                  @endif
                  
                  <div style="color:white;">
                    <button class="btn btn-sm btn-primary" data-rowid="{{ $customer->id }}" disabled="">
                      <span>
                        <li class="fa fa-map-marker"> Map View</li>
                      </span>
                      <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs"  class="parent1 js-switch" value="{{ $customer->id }}" />
                      <br><input type="checkbox" style="opacity: 0"  name="checkbox[]" value="{{ $customer->location_url }}" data-rowcname="{{ $customer->user->name }}-{{ $customer->customer_name }}" data-rowid="{{ $customer->id }}" class="add-coords child approve-to" />
                    </button>
                    <input type="checkbox" style="display: none;"   name="checkbox1[]" value="{{ $customer->user->name ?? '-' }}~{{ $customer->location_url }}~{{ $customer->id }}" data-rowid="{{ $customer->id }}" data-loc="{{ $customer->location_url }}" class=" child approve-to" />
                    <input class="form-control customer-loc" style="display: none;" type="text" data-rowid="{{ $customer->id }}" placeholder="Enter Cordinates Only" value="{{ $customer->location_url }}">
                  </div>

                  <!-- <input type="checkbox" style="opacity: 0" name="checkbox[]"  data-toggle="toggle" data-onstyle="success" data-size="xs" value="{{ $customer->location_url }}" data-rowid="{{ $customer->id }}" class="add-coords child approve-to" />
                    <input type="checkbox" style="opacity: 0"  name="checkbox1[]"  data-toggle="toggle" data-onstyle="success" data-size="xs" value="{{ $customer->user->name ?? '-' }}~{{ $customer->location_url }}~{{ $customer->id }}" data-rowid="{{ $customer->id }}" data-loc="{{ $customer->location_url }}" class=" child approve-to" />
                    <input class="form-control customer-loc" style="display: none;" type="text" data-rowid="{{ $customer->id }}" placeholder="Enter Cordinates Only" value="{{ $customer->location_url }}"> -->
                  <div style="display: none;">
                    @if ($customer->visit_clear == 0 && $customer->customer_pending == 0)
                    <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" style="display:none" name="checkbox[]" value="{{ $customer->location_url }}" data-rowid="{{ $customer->id }}" data-rowcname="{{ $customer->user->name }}-{{ $customer->customer_name }}" class="add-coords check-unvisited" />
                    <input type="checkbox"  data-toggle="toggle" data-onstyle="success" data-size="xs" style="display:none"  name="checkbox1[]" value="{{ $customer->user->name ?? '-' }}~{{ $customer->location_url }}~{{ $customer->id }}" data-rowid="{{ $customer->id }}" class="check-unvisited " />
                    @endif
                  </div>
                </td>
                <td class="text-center">
                  @if( $customer->customer_pending == 1)
                  <div class="productShowcaseTitle fa fa-warning inactive-click" data-rowid="{{ $customer->id }}" style="color:black;">Inactive Customer
                    @if( $customer->ot_del_customer == 1 )
                    <b id="customer-name-{{ $customer->id }}" value="{{ $customer->user->name ?? '-' }}" style="text-transform: uppercase; color: red;">
                    Deleted /( {{ $customer->user->name ?? '-' }} /{{ $customer->customer_name }})
                    </b>
                    @else
                    <b id="customer-name-{{ $customer->id }}" value="{{ $customer->user->name ?? '-' }}" style="text-transform: uppercase; color: red;">
                    ({{ $customer->user->name ?? '-' }} / {{ $customer->customer_name }})
                    </b>
                    @endif
                  </div>
                  <br>
                  @else
                  <div class="productShowcaseTitle ti-pulse" data-rowid="{{ $customer->id }}" style="color:black; background-color: #2ed8b6;">Active Customer
                    @if( $customer->ot_del_customer == 1 )
                    <b id="customer-name-{{ $customer->id }}" value="{{ $customer->user->name ?? '-' }}" style="text-transform: uppercase; color: red;">
                    Deleted / ({{ $customer->user->name ?? '-' }} /{{ $customer->customer_name }})
                    </b>
                    @else
                    <b id="customer-name-{{ $customer->id }}" value="{{ $customer->user->name ?? '-' }}" style="text-transform: uppercase; color: red;">
                    ({{ $customer->user->name ?? '-' }} /{{ $customer->customer_name }})
                    </b>
                    @endif
                  </div>
                  @endif 
                  <b>Address</b><br>
                  <a class="btn btn-sm btn-primary ti-location-pin" style="color: black;"href="https://www.google.com/maps?q=+{{$customer->address }}" target="_blank"> {{ $customer->address }}</a>
                  <br>
                  <b>Balance</b><br>
                  @if ($customer->getbalance($customer->id) != 0)
                  <button class="btn btn-sm btn-danger fa fa-warning" style="color:black;">
                  Balance: {{ $customer->getbalance($customer->id) }}
                  </button> 
                  @else
                  <button class="btn btn-sm btn-success" style="color:black;">
                  Balance: {{ $customer->getbalance($customer->id) }}
                  </button> 
                  @endif
                  <br><b>Last Order</b>
                  <br>
                  @php
                  $now = \Carbon\Carbon::now()->toDateString();
                  $a = strtotime($customer->getlastorderdate($customer->id));
                  $b = strtotime($now);
                  $days_between = ceil(($b - $a) / 86400);
                  @endphp
                  <b style="  border: 1px solid gray;
                    padding: 1px;
                    font-weight: 600;
                    background: yellow;">{{ $days_between }} Days Before
                  <br>
                  At {{ $customer->getlastorderdate($customer->id) }}
                  </b>
                  <br>
                  @php
                  $now = \Carbon\Carbon::now()->toDateString();
                  $a = strtotime($customer->visit_date);
                  $b = strtotime($now);
                  $days_between = ceil(($b - $a) / 86400);
                  @endphp
                  <b>Last Visit</b><br>
                  <b style="  border: 1px solid gray;
                    padding: 1px;
                    font-weight: 600;
                    background: yellow;">{{ $days_between }} Days Before
                  <br>
                  At {{ $customer->visit_date }}
                  </b>
                  <br>
                  <b>Area Name</b><br>{{ $customer->area->name ?? 'No Area Is Assigned!' }}
                  <br>
                  <b>Created By</b><br>{{ $customer->otordertakername->name }}
                  <br>
                  <b>Phone</b><br>{{ $customer->phone }}
                  <br>
                  <b>Location Cords</b><br>{{ $customer->location_url }}
                  <br>
                  <b>Current Order Exist</b><br>{{ $customer->order_exist }}
                  <br>
                  <b>Total Invoices</b><br>{{ sizeof($customer->invoices) }}
                  <br>
                  @if(!empty($customer->cnic))
                  <b>Cnic</b><br>{{ $customer->cnic }}
                  <br>
                  @else
                  <b>Cnic</b><br>{{ "No Data Found!" }}
                  <br>
                  @endif
                  @if(!empty($customer->freezer_model))
                  <b>Freezer Model</b><br>{{ $customer->freezer_model }}
                  <br>
                  @else
                  <b>Freezer Model</b><br>{{ "No Data Found!" }}
                  <br>
                  @endif
                  @if(!empty($customer->customer_request))
                  <b>Request</b><br>{{ $customer->customer_request }}
                  <br>
                  @else
                  <b>Request</b><br>{{ "No Data Found!" }}
                  <br>
                  @endif
                  <b>Payment Method</b><br>{{ $customer->payment_method }}
                  <br>
                  <b>Craeted</b><br>{{ $customer->created_at }}
                  <br>
                  @if(!empty($customer->ot_location))
                  <b>Last Visit Location</b><br>{{ $customer->ot_location }}
                  <br>
                  @else
                  <b>Last Visit Location</b><br>{{ "No Data Found!" }}
                  <br>
                  @endif
                  <br>
                  <input  type="checkbox" id="select-{{ $customer->id }}"  name="selected[]" class="selectedCustomer" value="{{ $customer->id }}">
                </td>
              </tr>
              @endforeach
            </tbody>
            </form>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
{{ $customers->links("pagination::bootstrap-4") }}
<div class="modal fade" id="agreement-popup" tabindex="-1" role="dialog" aria-labelledby="agreement-popup" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agreement Image</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" id="append-ag-img" style="height: 500px;width: 100%">
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="now_of_days" tabindex="-1" role="dialog" aria-labelledby="now_of_days" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Find Older then Days</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" class="category-form">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Enter Number of Days</label>
            <input type="text" class="form-control" name="now_of_days" id="set-days" value="" required="">
          </div>
          <div class="form-group">
            <a onclick='getNewCustomer();' class="btn btn-primary btn-block">Show Customers</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Shop Navigator</h5>
        <a onclick='getLocationn(); myStopFunction();' class="btn btn-sm btn-info remove-d-none visitv">Use Current Location</a>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="map-total-distance"></div>
        <div id="map-total-duration"></div>
        <div id="region-map" style="height: 400px"></div>
        <div id="sortedlist"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary close" data-dismiss="modal" aria-label="Close">Close</button>
      </div>
    </div>
  </div>
</div>
<!--//show all point-->
<div class="modal fade" id="map-modal-points" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">All Shops</h5>
        <a onclick='getLocationn(); myStopFunction();' class="btn btn-sm btn-info remove-d-none visitv">Use Current Location</a>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="map_canvas" style="height: 400px">
           
        </div>
         
      </div>
      
      <div class="modal-footer">
        <button class="btn btn-secondary">Close</button>
      </div>
    </div>
  </div>
</div>
<!--Change Area-->
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
            <select class="form-control" name="area" id="area-select">
              <option value="" disabled>Select Area</option>
              @if(!empty($customer))
              @foreach($areas as $a)
              <option value="{{ $a->id }}" @if($a->id == $customer->area_id ?? '') selected @endif>{{ $a->name }}</option>
              @endforeach
              @endif
            </select>
          </div>
          <div class="form-group">
            <a id="change_area_multiple"  class="btn btn-sm btn-primary">console check</a>
            <button class="btn btn-primary btn-block">Change Area</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="delete-customer-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="delete-modal-label" style="color: white">Are You Sure ?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Are you sure to delete it . Data will be deleted permanently from system.</div>
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">No</button>
        <a onclick="deletecustomer(this)" class="btn btn-danger f-d" style="color: white">Yes</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="Customer-status-model" tabindex="-1" role="dialog" aria-labelledby="change-area-popup" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Customer Deatils</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          {{ csrf_field() }}
          <div class="form-group">
            <label><b class="text-center">About This Customer</b></label>
            <br>
            <p>Some of You Made This Customer <b id = "c-name"></b>"Inactive" which means you can not see this Customer in New Order if you apply "Filters"! 
              If you want to show Customer in New Order then you Need to Active Customer click to switch "Customer In Pending" and refresh.
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="change_area_multiple-popup" tabindex="-1" role="dialog" aria-labelledby="change_area_multiple-popup" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Area</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="form-group">
        <select class="form-control areaid" data-placeholder="Choose a Area...">
          <option value="">Select Area</option>
          @foreach ($areas as $ar)
          <option value="{{ $ar->id }}">{{$ar->name}}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <button class=" form-control btn btn-sm btn-block btn-danger" id="getSelectedCustomer">Change Area Of Selected Customer</button>
      </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
  $('.see-ag').click(function(){
    var src = $(this).find('span').text();
    $('#append-ag-img').attr('src' , src);
  });
  // admin swicth 
  $(document).ready(function(){
    $(document).on('change', '.js-switch3', function () {
        let status = $(this).prop('checked') === true ? 1 : 0;
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('users.update.status') }}',
            data: {'status': status, 'user_id': userId},
            success: function (data) {
            console.log(data.message);
            }
        });
    });
  });
  $(document).ready(function(){
    $(document).on('change', '.js-switch1', function () {
        let customer_pending = $(this).prop('checked') === true ? 1 : 0;
        if ($(this).prop('checked') == 1) {
       $(this).closest('tr').addClass('customer-pending'); 
        } else {
           $(this).closest('tr').removeClass('customer-pending'); 
        }
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('uptodate.customer_pending') }}',
            data: {'customer_pending': customer_pending, 'user_id': userId},
            success: function (data) {
            console.log(data.message);
            }
        });
    });
  });
  var ot_filter_val = 'yes',ot_area_val = 'yes';
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
        if(ot_filter_val != 'yes' && ot_area_val != 'yes')
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
  $('.ot_filter').on('change' , function(){
      ot_filter_val = $(this).val()
      runtimeFilter();
  });
  $('.ot_area').on('change' , function(){
      ot_area_val = $(this).val()
    //   runtimeFilter();
    window.location.href = "{{ route('all.customers') }}?area="+ot_area_val;
  });
  
  $(document).ready(function() {
    $('#example').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
  } );
  
  
  function mapcheck(ids){
    var va = ids;
    console.log(va);
    if($this.data('clicked')){
        $('.child[data-rowid |="'+id+'"]').attr('checked', true);
        
    }else{
        $('.child[data-rowid |="'+id+'"]').attr('checked', false);
    }
  }
</script>
<!--// google maps -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
  var lists = [];
  $('.show-map').click(function(){
    getLocation();
      coords = [];
      var current_coords = end_point != null ? end_point : start_point;
      $('.add-coords:checked').each(function() {
          coords.push({
              "points": $(this).val(),
              // "info": $(this).closest('label').find('.cc-name').val()
              "info": $(this).data('rowcname'),
              "row_check": $(this).data('rowid')
          });
      });
      console.log(coords);
      locations = [];
      s_points = start_point.split(',');
      locations.push([
          'Your Location', s_points[0], s_points[1]
      ]);
      $.ajax({
          url: "{{ route('get.nearest.shop') }}",
          type: "post",
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
     setTimeout(function() {
  console.log(lists);
    var dropdownHTML = "<select>";
$.each(lists, function(index, value) {
  dropdownHTML += "<option>" + value + "</option>";
});
dropdownHTML += "</select>";
$('#sortedlist').html(dropdownHTML);
  
 }, 5000);
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
      travelMode: google.maps.TravelMode.WALKING,
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
        console.log(waypointOrder);
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
          var name = '';
           var otherData = " <button class='btn btn-primary btn-sm map-report-clicked mr-2' data-rowid='"+ locations[i][0] +"' data-id='"+ locations[i][3] +"'>Print</button> <button class='btn btn-primary btn-sm map-visit-clear mr-1'  data-id='"+ locations[i][3] +"'>Visit Clear</button> <button class='btn btn-primary btn-sm open-map mr-1' data-id='"+ locations[i][3] +"'>Direction</button>";
          if(i == 0) {
            title = locations[i][0] + otherData;
            //name = ""+ locations[i][0];
            
          } else {
            var order = waypointOrder[i - 1];
            title = locations[order + 1][0] + " <button class='btn btn-primary btn-sm map-report-clicked mr-2' data-rowid='"+ locations[order + 1][0] +"' data-id='"+ locations[order + 1][3] +"'>Print</button> <button class='btn btn-primary btn-sm map-visit-clear mr-1'  data-id='"+ locations[order + 1][3] +"'>Visit Clear</button> <button class='btn btn-primary btn-sm open-map mr-1' data-id='"+ locations[order + 1][3] +"'>Direction</button>";
            //name = "" + locations[order + 1][0];
              
          }
          //console.log(marker);
          addInfoWindow(marker, title );
          
          
          
          
          // Edited by Izaz
          totalDistance += legs[i].distance.value;
          totalDuration += legs[i].duration.value;
         
        }
        
        
        
          $("#map-total-distance").text('Total distance: '+ Math.round( totalDistance / 1000)+' kilometers' );
          $("#map-total-duration").text('Total duration: '+ Math.round(totalDuration / 60)+' minutes' );
      }
    });
    
  }
   
  function addInfoWindow(marker, title ) {
      //console.log(itr);
      //console.log(title.split('ends'));
      
      lists.push(title);
      
      
          
    var infowindow = new google.maps.InfoWindow({
        content: title
    });
    marker.addListener('click', function() {
        infowindow.open(map, marker);
        marker.setIcon('http://maps.google.com/mapfiles/ms/micons/orange.png');
          getLocationn();
    });
  }
   google.load("maps", "3.exp", {callback: initMap, other_params:'key=AIzaSyD4DT5qvOftiCuda7_7Bb6Uj19x_eo_pyk&callback=initMap&libraries=&v=weekly&channel=2'});
</script>
<script type="text/javascript">
  $('.check-all').click(function(){
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
        $('.approve-to').each(function(confirm){
            if(!is_rev){
                $(this).prop('checked' , true);
                $('#multiple-approve').append($(this).closest('label').html());
                $('#multiple-approve .approve-to').last().attr('checked' , 'checked');
            }
            else{
                $(this).prop('checked' , false);
                $('#multiple-approve .approve-to').each(function(){
                    $(this).remove();
                });
            }
        });
    });
    // check all unvisited customer
    $('.check-all-unvisited').click(function(){
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
        $('tbody .check-unvisited').each(function(confirm){
            if(!is_rev){
                $(this).prop('checked' , 'checked');
                $('#multiple-approve').append($(this).closest('label').html());
                $('#multiple-approve .check-unvisited').last().attr('checked' , 'checked');
            }
            else{
                $(this).prop('checked' , false);
                $('#multiple-approve .check-unvisited').each(function(){
                    $(this).remove();
                });
            }
        });
    });
    // Change area
    
    $('.change-area').click(function(event){
      event.preventDefault();
      var cTR = $(this).closest('tr');
      var c_name = cTR.find('td').eq(1).text();
      $('.old-category').val(c_name)
      $('.category-form').attr('action' , $(this).attr('href'));
     // $('#change-area-popup .modal-title').html('Edit <b>' + c_name + '</b>');
      $('#change-area-popup .modal-footer button').text('Update Category');
    });
    
    $("body").delegate('.map-checked-box', 'click', function(){
        var dataID = $(this).data('id');
        var status = true;
       // window.open("https://www.w3schools.com");
      //   if($(this).prop('checked') == false){
      //     status = false;
      //   }
        $(".chk-by-map[value='"+dataID+"']").prop('checked', status);
      });
      $("body").delegate('.open-map', 'click', function(){
        var dataID = $(this).data('id');
       var result = $("input[name='checkbox1[]'][data-rowid='"+ dataID +"']").data("loc");
       console.log(result);
       console.log(dataID);
       
       var a = "https://www.google.com/maps?q=+";
       var c  = a.concat(result);
       window.open(c);
      });
    // parent child check boxs controll
     
  $('input.parent1').on('change', function(){
      var id = $(this).val();
      if($(this).is(':checked')){
          $('.child[data-rowid |="'+id+'"]').attr('checked', true);
      }else{
          $('.child[data-rowid |="'+id+'"]').attr('checked', false);
      }
  });
  
</script>
<script type="text/javascript">
  //  change area multiple
  
  $('#change_area_multiple').on('click', function () { // perform action 
              var ids = []
          var checkboxes = document.querySelectorAll('input[name="changearea[]"]:checked') // get all checked value of user id
          
          for (var i = 0; i < checkboxes.length; i++) {
              ids.push(checkboxes[i].value) // push all ids
          }
           console.log(ids);
           var areaselected = document.getElementById("area-select").value;
           $.ajax({
              type: "GET",
              dataType: "json",
              url: '{{ route('change.area.multiple') }}',
              data: {'areaselected': areaselected, 'user_id': ids},
              success: function (data){
                  
              }
          });
  });
  $('#un-visit-customer').on('click', function () { // perform action 
              var ids = []
          var checkboxes = document.querySelectorAll('input[name="changearea[]"]:checked') // get all checked value of user id
          
          for (var i = 0; i < checkboxes.length; i++) {
              ids.push(checkboxes[i].value) // push all ids
          }
           $.ajax({
              type: "GET",
              dataType: "json",
              url: '{{ route('un.visit.customer') }}',
              data: {'user_id': ids},
  
              success: function (data) {
               if(data.success == true){
                       
                           toastr.success(data.message);
                   } else {
                           toastr.error(data.message);
                   }
              }
          });
  });
  $('#change_creaetd_by').on('click', function () { // perform action 
              var ids = []
          var checkboxes = document.querySelectorAll('input[name="changearea[]"]:checked') // get all checked value of user id
          
          for (var i = 0; i < checkboxes.length; i++) {
              ids.push(checkboxes[i].value) // push all ids
          }
           console.log(ids);
           var adminselected = document.getElementById("admin-select").value;
           $.ajax({
              type: "GET",
              dataType: "json",
              url: '{{ route('change.admin.multiple') }}',
              data: {'adminselected': adminselected, 'user_id': ids},
              success: function (data) {
              console.log(data.message);
              }
          });
  });
  
  $('.show_all_point').on('click', function () { // perform action 
              var array = []
              var us_ids = []
          var checkboxes = document.querySelectorAll('input[name="checkbox1[]"]:checked') // get all checked value
           var us_id = document.querySelectorAll('input[name="changearea[]"]:checked')
          for (var i = 0; i < checkboxes.length; i++) {
              array.push(checkboxes[i].value)
          }
           initialize(array); // pass value to function
          
                console.log('a=' , array , checkboxes);
  });
     function initialize(a){
      
         var location = a; // same result as below var locations
      
          
      let locations = location.map(s => s
          .split(',')
          .flatMap(s => s.split('~'))
          .map((v, i) => i ? +v : v)
      );
  
          console.log(locations);
  
      var map = new google.maps.Map(document.getElementById('map_canvas'), {
        zoom: 10,
        center: new google.maps.LatLng(31.6118734,74.3403324),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });
  
      var infowindow = new google.maps.InfoWindow();
  
      var marker, i ;
  
      for (i = 0; i < locations.length; i++) {  
        marker = new google.maps.Marker({
          position: new google.maps.LatLng(locations[i][1], locations[i][2]),
          map: map
        });
  
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
           //ar otherData = " <input type='checkbox' class='map-checked-box mr-1' data-id='"+ locations[i][3] +"' >";
             console.log(locations[i][3]);
            infowindow.setContent(locations[i][0] + locations[i][3] + " <button class='btn btn-primary btn-sm map-checked-box mr-1' onclick='" + state(marker) +"' data-id='"+ locations[i][3] +"'>Print</button> <button class='btn btn-primary btn-sm open-map mr-1' data-id='"+ locations[i][3] +"'>Direction</button> <button class='btn btn-primary btn-sm map-visit-clear mr-1' onclick='" + state(marker) +"' data-id='"+ locations[i][3] +"'>Visit Clear</button> <button class='btn btn-primary btn-sm selecCustomer mr-1' onclick='" + state(marker) +"' data-id='"+ locations[i][3] +"'>Select Customer</button> ");
            infowindow.open(map, marker);
          }
        })(marker, i ));
      }
     function state(marker){ 
    marker.setIcon('http://maps.google.com/mapfiles/ms/micons/orange.png');
    getLocationn();
  }
     }
    
</script>
<script type="text/javascript">
  function getLocationn() {
  if (navigator.geolocation) {
   return navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else {
   alert("Try any other browser");
  }
  }
  var a ;
  function showPosition(position) {
   $('[name="location_url_ot_visit"]').val(position.coords.latitude+','+position.coords.longitude);
    a = setTimeout(function(){
       document.getElementById('ot-value-visit').value = "";
   }, 30000);
   
  }
  
  function myStopFunction() {
  clearTimeout(a);
  }
  
  $(document).ready(function(){
  $(document).on('change', '.js-switch2', function () {
   let visit_clear = $(this).prop('checked') === true ? 1 : 0;
   var location = $('#ot-value-visit').val();
   var cslocation = $('.customer-loc').val();
   var userId = $(this).data('id');
   //console.log(visit_clear);
   if ($(this).prop('checked') == 1 && location != "") {
   
   $.ajax({
       type: "GET",
       dataType: "json",
       url: '{{ route('users.update.visit_clear') }}',
       data: {'visit_clear': visit_clear, 'user_id': userId , 'location': location , 'cs_location': cslocation },
      success: function (data) {
            if(data.success == true){
                
                    toastr.success(data.message);
            } else {
                    toastr.error(data.message);
            }
       },
       error: function(err){
       toastr.error(data.message);
       
       }
   });
    }
     else {
         console.log(visit_clear);
   
         console.log(userId);
   $.ajax({
       type: "GET",
       dataType: "json",
       url: '{{ route('users.update.visit_clear') }}',
       data: {'visit_clear': visit_clear, 'user_id': userId },
       success: function (data) {
            if(data.success == true){
                
                    toastr.success(data.message);
            } else {
                    toastr.error(data.message);
            }
       },
       error: function(err){
       toastr.error(data.message);
       
       }
   });
   }
   
   
  });
  });
  
  $("body").delegate('.map-report-clicked, .row-report-clicked', 'click', function(){
  var param = "<p>"+$(this).data('rowid')+"<p>";
  console.log(param);
   window.location='printerplus://send?text='+param;
  
  });

  $("body").delegate('.selecCustomer', 'click', function(){
   var userId = $(this).data('id');
   console.log(userId);
    $('#select-' + userId).prop('checked', true);
 });
  
  $("body").delegate('.map-visit-clear', 'click', function(){
   var location = $('#ot-value-visit').val();
   var cslocation = $('.customer-loc').val();
   var userId = $(this).data('id');
   var visit_clear = 1;
   
   $.ajax({
       type: "GET",
       dataType: "json",
       url: '{{ route('users.update.visit_clear') }}',
       data: {'visit_clear': visit_clear, 'user_id': userId , 'location': location , 'cs_location': cslocation },
       success: function (data) {
           if (data.message != ""){
               toastr.success(data.message);
           }
           else {
               toastr.error(data.message1);
           }
       
       }
   });
    
  });
  
  //   $('.remove-d-none').on('click', function() {
  //   $(this).siblings(".d-none").removeClass("d-none");
  // /*
  //  OR $(this).siblings(".menu").removeClass("d-none");
  // */
  // });
  
  $(".remove-d-none").click(function(){
  $(this).siblings(".menu").toggle();
  });
  $(".js-example-basic-multiple").select2();
  function getdata(){
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPositions);
  } else {
   alert("Try any other browser");
  }
  }
  function showPositions(position) {
  var arealocation = position.coords.latitude+','+position.coords.longitude;
  $('[name="area_location"]').val(position.coords.latitude+','+position.coords.longitude);
  $('.d-n').removeClass('d-none');
  $(':input[type="area-btn"]').prop('disabled', false);
  a = setTimeout(function(){
       document.getElementById('area_location').value = "";
       $('.d-n').addClass('d-none');
   }, 30000);
  
  }
  
  function selectedarea(){
  
  var values = $('#are-id').val();
  var location = $('#area_location').val();
   var url =   "{{ route('all.customers') }}/"+values + "/" + location;
   console.log(url);
   document.location.href=url;
  
  
  }   
  function getAssignedCustomer(){
  
  var values = "assigned_customer";
   var url =   "{{ route('all.customers') }}/"+values + "/" ;
   console.log(url);
   document.location.href=url;
  
  
  }
  function getNewCustomer(){
    var noofdays = $('#set-days').val();
   console.log(noofdays);
  
  var values = "last_30_days_customer";
   var url =   "{{ route('all.customers') }}/"+values + "/"+noofdays ;
   console.log(url);
   document.location.href=url;
  
  
  }
  function storesortedlist(){
   
   var data =@json($shortestids);
   var d = JSON.stringify(data);
   console.log(data);
   $.ajax({
       type: "GET",
       dataType: "json",
       url: '{{ route('store.ids.list') }}',
       data: {ids : d}, 
       success: function (data) {
       toastr.success(data.message);
       }
   });
  }
  $(document).on('change', '.call', function () {
   var userId = $(this).data('id');
   console.log(userId);
   $.ajax({
       type: "GET",
       dataType: "json",
       url: '{{ route('users.update.call') }}',
       data: {'call': "call" , 'user_id': userId},
                 success: function (data) {
            if(data.success == true){
                
                    toastr.success(data.message);
            } else {
                    toastr.error(data.message);
            }
       },
       error: function(err){
       toastr.error(data.message);
       }
   });
    });
  function deletecustomer(el) {
   $.ajax({
       type: "GET",
       dataType: "json",
       url: '{{ route('delete.customer') }}',
       data: {ids : $(el).data('id')}, 
       success: function (data) {
       toastr.success(data.message);
       }
   });
  $("#delete-customer-modal .close").click();
  }
  //on modal show
  $('#delete-customer-modal').on('shown.bs.modal', function(e) {
  var data_id = $(e.relatedTarget).data('id') //get attr of `a` tag
  console.log("Inside this " + data_id)
  $(this).find('.modal-footer a').data('id', data_id); //add it inside `a` tag modal
  
  });
    $(document).ready(function(){
  $(document).on('click', '.inactive-click', function() {
  var rowid = $(this).data('rowid');
  var name = $('#customer-name-'+rowid).text();
  console.log(name);
  $('#Customer-status-model').modal('toggle');
  $('#c-name').html(name);
  
  
  });
  });
  $('#getSelectedCustomer').click(function() {
  const customer = $('input[name="selected[]"]:checked').map(function() {
    return this.value;
  }).get();
  var area = $('.areaid').val();
  var formData = new FormData();
  $.each(customer, function(index, value) {
    formData.append('user_id[]', value);
  });
  formData.append('areaselected', area);
  formData.append('_token', '{{ csrf_token() }}'); // add CSRF token
  $.ajax({
    url: '{{ route('change.area.multiple') }}',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: function(response) {
      toastr.success(response.message);
      $("#change_area_multiple-popup .close").click();
    },
    error: function(xhr, status, error) {
      // handle error response
    }
  });

});

</script>
@endpush
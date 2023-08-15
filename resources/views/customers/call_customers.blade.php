@extends('layouts.app')
@section('title') All Customer @endsection
@section('content')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<style>
.visitclear{
    background:green;
}
.callcustomer{
    background:yellow;
}
.customer-pending {
    background:pink;
}
</style>
@endpush
<!-- Breadcrumbs-->


             <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Call To Customer</h5>
            <p class="text-muted m-b-10 text-center">Customer needs to call for order</p>
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
        <i class="fa fa-table"></i> <b><?php echo empty($subadmin_name) ? '' : $subadmin_name ?></b> Customers List</div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="example" class="table table-bordered table-custom-th" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Details</th>
               
                
                
              </tr>
            </thead>
            <tbody>
              @foreach($customers as $key => $customer)
              @if ($customer->call_customer == 1)
                 <tr class="callcustomer">
           
              @elseif ($customer->visit_clear == 1)
              <tr class="visitclear">
                  @elseif ($customer->customer_pending == 1)
                  <tr class="customer-pending">
                      @else 
                      <tr>
                      @endif
                  <input type="hidden" class="area_id" name="area_id" value="{{ $customer->area_id }}">
                  <td class="text-center">
                  
                       <a href="tel:{{ $customer->phone }}" class="btn btn-sm btn-danger phone-btn call"><i class="fa fa-phone"></i>Call</a>
                       <br>
                      <div class="text-center btn btn-sm">
                  <span><b>Change Call Status</b></span><br><input type="checkbox" data-id="{{ $customer->id }}" name="call" id="call" class="js-switch call" data-toggle="toggle" data-onstyle="danger" data-size="xs">
                </div>

                  <br>
                  Name : {{ $customer->user->name }}

                <br>

                Area : {{ $customer->area->name }}

                <br>

                T.No{{ sizeof($customer->invoices) }}

                <br>

                Address : <a href="http://maps.google.com/maps?q=+{{ $customer->location_url }}" target="_blank">
                  {{ $customer->address }}/{{ $customer->location_url }}</a>

                  <br>

                  Phone : {{ $customer->phone }}

                  <br>

                  Customer Request : {{ $customer->customer_request }}

                  <br>

                  Created At : {{ $customer->created_at }}













                    
               
                
                 
                </td>

              @endforeach
            </tbody>
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
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary">Close</button>
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
           <div id="map_canvas" style="height: 400px"></div>
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
                @foreach($areas as $a)
                <option value="{{ $a->id }}" >{{ $a->name }}</option>
                @endforeach
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
    $('.show-map').click(function(){
        coords = [];
        var current_coords = end_point != null ? end_point : start_point;
        $('.add-coords:checked').each(function() {
            coords.push({
                "points": $(this).val(),
                // "info": $(this).closest('label').find('.cc-name').val()
                "info": $(this).parent().parent().next().next().text().replace("Clear", "").trim(),
                "row_check": $(this).data('rowid')
            });
        });
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
             var otherData = " <button class='btn btn-primary btn-sm map-report-clicked mr-2' data-id='"+ locations[i][3] +"'>Print</button> <button class='btn btn-primary btn-sm map-visit-clear mr-1'  data-id='"+ locations[i][3] +"'>Visit Clear</button> <button class='btn btn-primary btn-sm open-map mr-1' data-id='"+ locations[i][3] +"'>Direction</button>";
            if(i == 0) {
              title = locations[i][0] + otherData;
            } else {
              var order = waypointOrder[i - 1];
              title = locations[order + 1][0] + " <button class='btn btn-primary btn-sm map-report-clicked mr-2' data-id='"+ locations[order + 1][3] +"'>Print</button> <button class='btn btn-primary btn-sm map-visit-clear mr-1'  data-id='"+ locations[order + 1][3] +"'>Visit Clear</button> <button class='btn btn-primary btn-sm open-map mr-1' data-id='"+ locations[order + 1][3] +"'>Direction</button>";
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
            getLocationn();
      });
    }
    google.load("maps", "3.exp", {callback: initMap, other_params:'key=AIzaSyA6GhjR-WmiKCgr71McBioeymDd6_Ti_0s&libraries=places,drawing'});
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
        
    // console.log(array);
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
          infowindow.setContent(locations[i][0] + locations[i][3] + " <button class='btn btn-primary btn-sm map-checked-box mr-1' onclick='" + state(marker) +"' data-id='"+ locations[i][3] +"'>Print</button> <button class='btn btn-primary btn-sm open-map mr-1' data-id='"+ locations[i][3] +"'>Direction</button> <button class='btn btn-primary btn-sm map-visit-clear mr-1' onclick='" + state(marker) +"' data-id='"+ locations[i][3] +"'>Visit Clear</button> ");
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
      if(arealocation != "" &&  $('#are-id').val() != "")
      $(':input[type="area-btn"]').prop('disabled', false);
    }
    
    function selectedarea(){
    
       var values = $('#are-id').val();
       var location = $('#area_location').val();
        var url =   "{{ route('all.customers') }}/"+values + "/" + location;
        console.log(url);
        document.location.href=url;
       
 
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
  </script>
@endpush
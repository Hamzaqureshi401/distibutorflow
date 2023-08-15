@extends('layouts.app')
@section('content')
<!-- Main-body start -->
      <!-- Page-header start -->
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">New Customer Location</h5>
            <p class="text-muted m-b-10 text-center">Customer location</p>
           
              <div class="card-header">
               <button class="btn btn-info btn-sm show-map" type="button" data-toggle="modal" data-target="#map-modal">Show Map</button>
                <button class="btn btn-info btn-sm show_all_point" type="button" data-toggle="modal" onclick ='initialiaze();' id="show_all_point" data-target="#map-modal-points">Show All Points</button>
        <i class="fa fa-table"></i> Location List
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
                     <tr class="text-center">
                        <th>ID</th>
                        <th>Creater</th>
                        <th>Cords</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($records as $record)
                     <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $record->user->name }}</td>
                         <td><a href="http://maps.google.com/maps?q=+{{ $record->cords }}" target="_blank">
                           {{ $record->cords }}
                        </a></td>
                        
                        <td><a href="{{ route('delete.location' , $record->id) }}" class="btn btn-sm btn-danger "><i class="fa fa-trash"></i>Delete Location</a>
                    </td>
                        
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
        <button class="btn btn-secondary">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="map-modal-points" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">All Shops</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
          <div id="map-total-distance"></div>
          <div id="map-total-duration"></div>
        <div id="map_canvas" style="height: 400px"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary">Close</button>
      </div>
    </div>
  </div>
</div>


@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('assets/js/google.maps.js') }}"></script>

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
   
    var locations = @json($records->pluck('cords')->toArray());
    var coords = [];
    $('.show-map').click(function(){
       getLocation();
        coords = [];
        var current_coords = end_point != null ? end_point : start_point;
        var location = @json($records->pluck('cords')->toArray());
        var address =  @json($records->pluck('user_id')->toArray());
        var cidsarr = @json($records->pluck('id')->toArray());
        var myOpts = "No Name!";
        for(var i=0; i < location.length; i++)
            {
               coords.push({
                   "points": location[i],
                   "info": myOpts,
                   "row_check": cidsarr[i]
               });
               
           }
  //      console.log(coords);
        locations = [];
        s_points = start_point.split(',');
        locations.push([
            'Your Location', s_points[0], s_points[1]
        ]);
        $.ajax({
            url: "{{ route('get.nearest.shop') }}",
            type: "Get",
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
            var otherData = " <button class='btn btn-primary btn-sm map-report-clicked mr-2' data-id='"+ locations[i][3] +"'>Print</button> <button class='btn btn-primary btn-sm open-map mr-1' data-id='"+ locations[i][2] +"'>Direction</button> <input type='checkbox'  class='map-checked-box mr-1' data-id='"+ locations[i][3] +"' >";
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






    $('#show_all_point').on('click', function () { // perform action 
  
         initialize(); // pass value to function
  });
   function initialize(){

    var map = new google.maps.Map(document.getElementById('map_canvas'), {
      zoom: 10,
      center: new google.maps.LatLng(31.6118734,74.3403324),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      //console.log(11 , locations[i]);
      s_points = locations[i].split(',');
       console.log(s_points[0] , s_points[1]);
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(s_points[0], s_points[1]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i] +'<button onclick="myFunction()">Click me</button>');
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
   }
</script>
@endpush
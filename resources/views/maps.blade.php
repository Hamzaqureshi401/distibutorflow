<!DOCTYPE html>
<html>
<head>
	<title>Live Location</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">
	<style>
		#map {
			height: 400px;
			width: 100%;
		}
	</style>
</head>
<body>
	<div id="map"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4DT5qvOftiCuda7_7Bb6Uj19x_eo_pyk&callback=initMap&libraries=&v=weekly&channel=2"></script>
	<script>
		// Note: This example requires that you consent to location sharing when
// prompted by your browser. If you see the error "The Geolocation service
// failed.", it means you probably did not give permission for the browser to
// locate you.
let map, infoWindow;
let marker;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -34.397, lng: 150.644 },
    zoom: 18,
  });
  infoWindow = new google.maps.InfoWindow();

  const locationButton = document.createElement("button");

  locationButton.textContent = "Pan to Current Location";
  locationButton.classList.add("custom-map-control-button");
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
  locationButton.addEventListener("click", () => {
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          updateMap(pos);
        },
        () => {
          handleLocationError(true, infoWindow, map.getCenter());
        }
      );
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, map.getCenter());
    }
  });

  // Update user location every 2 seconds
  setInterval(() => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          updateMap(pos);
        },
        () => {
          handleLocationError(true, infoWindow, map.getCenter());
        }
      );
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, map.getCenter());
    }
  }, 2000);
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
}

function updateMap(pos) {
  if (!marker) {
    // Create a marker on the first update
    marker = new google.maps.Marker({
      position: pos,
      map: map,
    });
  } else {
    // Update the marker position
    marker.setPosition(pos);
  }
  map.setCenter(pos);
}
window.initMap = initMap;



	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4DT5qvOftiCuda7_7Bb6Uj19x_eo_pyk&callback=initMap&libraries=&v=weekly&channel=2&callback=initMap">
	</script>
</body>
</html>

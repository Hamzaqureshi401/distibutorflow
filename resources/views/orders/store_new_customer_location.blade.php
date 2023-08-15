<script type="text/javascript">
   function storeLocation() {
           if (navigator.geolocation) {
             navigator.geolocation.getCurrentPosition(takePosition);
           } else { 
             x.innerHTML = "Geolocation is not supported by this browser.";
           }
         }
   
         function takePosition(position) {
          // x.innerHTML = "Latitude: " + position.coords.latitude + 
          // "<br>Longitude: " + position.coords.longitude;
   
          var lat = position.coords.latitude;
          var lon = position.coords.longitude;
          console.log(position.coords.latitude + "," + position.coords.longitude);
           store_new_cstmr_location(position.coords.latitude + "," + position.coords.longitude);
         }

    function store_new_cstmr_location(cords){

       $.ajax({
               type: "GET",
               dataType: "json",
               url: '{{ route('store.new.cstmr.location') }}',
               data: {'cords': cords},
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
</script>
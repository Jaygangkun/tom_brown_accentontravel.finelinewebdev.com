<?php 

$document = JFactory::getDocument();
$document->addScript('http://maps.googleapis.com/maps/api/js?&sensor=false');
		$display = '';
		$display .= '
					function createMarker(point, info, map) {
					var marker = new google.maps.Marker({
					position: point
					});
					var infoWindow = new google.maps.InfoWindow();
					google.maps.event.addListener(marker, "click", function() {
					infoWindow.setContent(info);
					infoWindow.open(map, marker);
					});
					return marker;
					}
					
					function load() {
					var mapOptions = 
					{
					zoom: 8,
					mapTypeId: google.maps.MapTypeId.TERRAIN
					};	
						
					var map = new google.maps.Map(document.getElementById("map"), mapOptions);
					
					window.setTimeout(function() 
					{
					var latlngbounds = new google.maps.LatLngBounds();';
		$addressText1 = '<strong>a(MUSE.)</strong><br>44 Baltimore Ave,<br>Rehoboth Beach, DE 19971 <br><strong>Phone:</strong> 302-227-7107<br><strong>Address</strong><br><input id="address1" type="text"><br><input type="button" class="button" onclick=getDirections() value="Get Directions">';	
		$display .=     'latlngbounds.extend( new google.maps.LatLng( 38.717115, -75.080017 ) );';
		$display .= 	"var marker = createMarker(new google.maps.LatLng( 38.717115, -75.080017), '".$addressText1."', map);
						marker.setMap(map);";
				

		$display .= 'map.fitBounds(latlngbounds);
					}, 100);
					window.setTimeout(function() {
					}, 200);
					geocoder = new google.maps.Geocoder;
				    directionsService = new google.maps.DirectionsService();
				    directionsDisplay = new google.maps.DirectionsRenderer({
				      suppressMarkers: false
				    });
				    directionsDisplay.setMap(map);
				    directionsDisplay.setPanel(document.getElementById("directionsPanel"));
					}
					</script> 
					<script type="text/javascript">
					window.onload = function() {
					load()
					};
					function getDirections() {
				     geocoder.geocode( { "address": document.getElementById("address1").value },
				     function(results, status) {
				       if(status == google.maps.GeocoderStatus.OK) {
				         var origin = results[0].geometry.location;
				         var destination = new google.maps.LatLng(38.717115, -75.080017);
				         var request = {
				           origin: origin,
				           destination: destination,
				           travelMode: google.maps.DirectionsTravelMode.DRIVING
				         };
				
				         directionsService.route(request, function(response, status) {
				           if (status == google.maps.DirectionsStatus.OK) {
				             directionsDisplay.setDirections(response);
				           }
				         });
				
				         } else {
				          document.getElementById("address1").value = 
				            "Directions cannot be computed at this time.";
				       }
				     });
				   }';
			$document->addScriptDeclaration($display);		
			echo '<div class="mapWrapper" style="padding-bottom:10px">
					<div class="googlemap" id="map" style="width:98%; height:500px">If this text does not disappear quickly, then your browser does not support Google Maps</div></div>
					<div id="directionsPanel"></div>';

		
?>
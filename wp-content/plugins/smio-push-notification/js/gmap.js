jQuery(document).ready(function() {
  SMIOinitialize();
  $('#smio_gmap_radius').on('change', function (event) {
	  SMIOcodeAddress();
  });
  $('.smio_gmap_input').on('keypress', function (event) {
    if(event.which === 13){
      event.preventDefault();
      SMIOcodeAddress();
    }
  });
});

var SMIOgeocoder;
var SMIOmap;
var SMIOcircle = 0;
var SMIOmarker = 0;
function SMIOinitialize() {
	if($("#smio-gmap").length < 1){
		return;
	}
  SMIOgeocoder = new google.maps.Geocoder();
  var lat = ($("#smio_latitude").val() == "")? 26.820553 : $("#smio_latitude").val();
  var lng = ($("#smio_longitude").val() == "")? 30.802498000000014 : $("#smio_longitude").val();
  var latlng = new google.maps.LatLng(lat, lng);
  var mapOptions = {
    zoom: 3,
    center: latlng
  }
  SMIOmap = new google.maps.Map(document.getElementById('smio-gmap'), mapOptions);
  if($("#smio_latitude").val() != "" && $("#smio_longitude").val()){
	  SMIOmarker = new google.maps.Marker({
          map: SMIOmap,
          draggable:true,
          position: latlng
      });
	  SMIOmap.setZoom(10);
	  SMIOdrawCircle();
	  SMIOdraggerMarker();
	  SMIOgeocodePosition(SMIOmarker.getPosition());
  }
}

function SMIOdrawCircle() {
  if(SMIOcircle != 0){
    SMIOcircle.setMap(null);
  }
  SMIOcircle = new google.maps.Circle({
    map: SMIOmap,
    radius: (1609.34 * $('#smio_gmap_radius').val()),
    fillColor: '#00AA00',
    strokeColor: '#fff',
    strokeOpacity: '.5',
    strokeWeight: '2'
  });
  SMIOcircle.bindTo('center', SMIOmarker, 'position');
}

function SMIOcodeAddress() {
  var address = $('#smio_gmap_address').val();
  SMIOgeocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      SMIOmap.setCenter(results[0].geometry.location);
      if(SMIOmarker != 0){
        SMIOmarker.setMap(null);
      }
      SMIOmarker = new google.maps.Marker({
          map: SMIOmap,
          draggable:true,
          position: results[0].geometry.location
      });
      $("#smio_latitude").val(results[0].geometry.location.lat());
      $("#smio_longitude").val(results[0].geometry.location.lng());
      SMIOdrawCircle();
      SMIOdraggerMarker();
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

function SMIOdraggerMarker() {
	google.maps.event.addListener(SMIOmarker,'dragend',function(event){
		$('#smio_latitude').val(event.latLng.lat());
		$('#smio_longitude').val(event.latLng.lng());
		SMIOdrawCircle();
		SMIOgeocodePosition(SMIOmarker.getPosition());
  });
}

function SMIOgeocodePosition(pos) {
  SMIOgeocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      $('#smio_gmap_address').val(responses[0].formatted_address);
    } else {
      alert('Cannot determine address at this location.');
    }
  });
}
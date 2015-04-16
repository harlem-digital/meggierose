function do_geocode() {
    navigator.geolocation.getCurrentPosition(
    	function( position ) {
			latlong = jQuery( '#wptouch_geolocation_coords' ).val().split( ',' );
			radius = jQuery( '#wptouch_geolocation_radius' ).val().split( ',' );

			distance = Math.floor( get_haversine( position.coords.latitude, position.coords.longitude, parseFloat( latlong[0] ), parseFloat( latlong[1] ) ) );

			if ( distance < radius ) {
				jQuery( '#wptouch_geolocation_image,#wptouch_geolocation_text' ).css( 'display', 'block' );
			}
	    }
	);
}

Number.prototype.toRad = function() {
   	return this * Math.PI / 180;
}

function get_haversine( lat1, lon1, lat2, lon2 ) {
	var R = 6371; // km
	//has a problem with the .toRad() method below.
	var x1 = lat2-lat1;
	var dLat = x1.toRad();
	var x2 = lon2-lon1;
	var dLon = x2.toRad();
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1.toRad()) * Math.cos(lat2.toRad()) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var d = R * c;

	return d;
}

jQuery( document ).ready( function() {
	do_geocode();
});
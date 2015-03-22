<?php
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'wptouch_geolocation_settings' );
add_filter( 'wptouch_setting_defaults_foundation', 'wptouch_geolocation_default_settings' );
add_filter( 'wptouch_modify_setting__foundation__geolocation_geocoded', 'wptouch_geolocation_get_coords' );

add_action( 'wptouch_body_top', 'wptouch_geolocation_set_coords' );

function wptouch_geolocation_settings( $page_options ){
	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Local Promotions', 'wptouch-pro' ),
		'foundation-location-settings',
		array(
			wptouch_add_pro_setting(
				'checkbox',
				'geolocation_enabled',
				__( 'Enable location detection', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
			wptouch_add_pro_setting(
				'text',
				'geolocation_address',
				__( 'Address on which to center the geofence', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
			wptouch_add_pro_setting(
				'numeric',
				'geolocation_radius',
				__( 'Geofence Radius (in km)', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_ADVANCED,
				'1.0'
			),
			wptouch_add_pro_setting(
				'textarea',
				'geolocation_html',
				__( 'Text to show to nearby visitors', 'wptouch-pro' ),
				'HTML is permitted.',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
			wptouch_add_pro_setting(
				'hidden',
				'geolocation_geocoded',
				__( 'Encoded Location', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	return $page_options;
}

function wptouch_geolocation_default_settings( $defaults ) {
	$defaults->geolocation_enabled = false;
	$defaults->geolocation_address = false;
	$defaults->geolocation_radius = 5;
	$defaults->geolocation_geocoded = false;
	$defaults->geolocation_html = false;
	return $defaults;
}

function wptouch_geolocation_get_coords( $content ) {
	if ( wptouch_has_geolocation() ) {
		$settings = foundation_get_settings();

		if ( $settings->geolocation_address ) {
			$endpoint = 'http://open.mapquestapi.com/geocoding/v1/address?key=Fmjtd%7Cluur2da7nu%2C7w%3Do5-9a8xda&';
			$data = array( 'location'=>$settings->geolocation_address, 'output'=>'json');
			$geocode = json_decode( file_get_contents( $endpoint . http_build_query( $data ) ) );

			if ( $coords = $geocode->results[0]->locations[0]->latLng ) {
				return $coords->lat . ',' . $coords->lng;
			}
		} else {
			return false;
		}
	}

	return false;
}

function wptouch_has_geolocation() {
	$settings = wptouch_get_settings( 'foundation' );
	if ( !$settings->geolocation_enabled ) {
		return false;
	} else {
		return true;
	}
}

function wptouch_geolocation_set_coords() {
	$settings = foundation_get_settings();
	if ( wptouch_has_geolocation() && $settings->geolocation_html ) {
		echo '<input type="hidden" id="wptouch_geolocation_coords" value="' . $settings->geolocation_geocoded . '">';
		echo '<input type="hidden" id="wptouch_geolocation_radius" value="' . $settings->geolocation_radius . '">';
		echo '<div id="wptouch_geolocation_text" style="background: rgba( 0, 0, 0, 0.5 ); display: none; padding: 30px; text-align: center; font-size: 1.3em; line-height: 1.4; color: #fff"><i class="icon-compass"></i> ' . $settings->geolocation_html . '</div>';
	}
}

add_action( 'foundation_enqueue_scripts', 'wptouch_geolocation_enqueue_scripts' );
function wptouch_geolocation_enqueue_scripts() {
	$settings = wptouch_get_settings( 'foundation' );
	if ( $settings->geolocation_enabled ) {
		wp_enqueue_script(
			'geolocation-js',
			WPTOUCH_URL . '/pro/modules/geolocation/geolocation.js',
			false,
			FOUNDATION_VERSION,
			true
		);
	}
}
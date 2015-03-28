<?php

add_action( 'foundation_module_init_mobile', 'foundation_advertising_init' );
add_filter( 'wptouch_body_classes', 'foundation_advertising_body_classes' );

function foundation_advertising_init() {
	if ( !foundation_advertising_enabled() ) {
		return;
	}

	$settings = foundation_get_settings();
	
	// Can't use WP is_single(), etc. functions here
	if ( 	$settings->advertising_blog_listings ||
			$settings->advertising_single ||
			$settings->advertising_pages ||
			$settings->advertising_taxonomy ||
			$settings->advertising_search
	) {
		switch ( $settings->advertising_location ) {
			case 'footer':
				add_action( 'wptouch_advertising_bottom', 'foundation_handle_advertising' );
				break;
			case 'header':
				add_action( 'wptouch_advertising_top', 'foundation_handle_advertising' );
				break;
			case 'top-content':
				add_filter( 'the_content', 'foundation_handle_advertising_content_top' );
				break;
			case 'bottom-content':
				add_filter( 'the_content', 'foundation_handle_advertising_content_bottom' );
				break;
			default:
				WPTOUCH_DEBUG( WPTOUCH_WARNING, 'Unknown advertising location: ' . $settings->advertising_location );
				break;
		}	
	}
}

function foundation_advertising_body_classes( $classes ) {
	if ( !foundation_advertising_enabled() ) {
		return $classes;
	}

	$settings = foundation_get_settings();

	if ( $settings->advertising_type != 'none' ) {
		$classes[] = $settings->advertising_location . '-ad';	
	}

	if ( $settings->advertising_type == 'custom' ) {
		$classes[] = 'custom-ad';	
	}	
	
	return $classes;
}


function foundation_get_admob_ad() {
	global $wptouch_pro;

	ob_start();
	if ( $wptouch_pro->get_active_device_class() == WPTOUCH_DEFAULT_DEVICE_CLASS ) {
		include( dirname( __FILE__ ) . '/admob.php' );
	} 
			
	$advertising = ob_get_contents();
	ob_end_clean();	
	
	return $advertising;
}

function foundation_get_google_ad() {
	global $wptouch_pro;
	$settings = foundation_get_settings();

	ob_start();
	if ( $wptouch_pro->get_active_device_class() == WPTOUCH_DEFAULT_DEVICE_CLASS ) {
		switch( $settings->google_code_type ) {
			case 'sync':
				include( dirname( __FILE__ ) . '/adsense-iphone-sync.php' );
				break;
			case 'async':
				include( dirname( __FILE__ ) . '/adsense-iphone-async.php' );
				break;
		}
	} 

	$advertising = ob_get_contents();
	ob_end_clean();	
	
	return $advertising;
}

function foundation_handle_advertising_content( $content, $top_content = true ) {
	if ( !foundation_advertising_enabled() ) {
		return;
	}

	ob_start();
	foundation_handle_advertising();
	$advertising = ob_get_contents();
	ob_end_clean();	

	if ( $top_content ) {
		return $advertising . $content;
	} else {
		return $content . $advertising;	
	}
}
		
function foundation_handle_advertising_content_top( $content ) {
	return foundation_handle_advertising_content( $content, true );
}	

function foundation_handle_advertising_content_bottom( $content ) {
	return foundation_handle_advertising_content( $content, false );
}	

function foundation_advertising_can_show_ads() {
	$settings = foundation_get_settings();
	
	$can_show_ads = false;	

	if ( $settings->advertising_blog_listings  ) {
		$can_show_ads = ( is_home() || is_author() || is_date() );
	} 

	if ( $settings->advertising_single ) {
		$can_show_ads = $can_show_ads || is_single();
	} 

	if ( $settings->advertising_pages ) {
		$can_show_ads = $can_show_ads || is_page();
	} 

	if ( $settings->advertising_taxonomy ) {
		$can_show_ads = $can_show_ads || ( is_category() || is_tag() || is_tax() );
	} 

	if ( $settings->advertising_search ) {
		$can_show_ads = $can_show_ads || is_search();
	} 
	
	return $can_show_ads;
}
		
function foundation_handle_advertising() {
	if ( !foundation_advertising_enabled() ) {
		return;
	}

	$settings = foundation_get_settings();
	
	if ( foundation_advertising_can_show_ads() ) { 
		switch( $settings->advertising_type ) {
			case 'admob':
				echo '<div class="wptouch-ad">' . foundation_get_admob_ad() . '</div>';
				break;
			case 'google':
				echo '<div class="wptouch-ad">' . foundation_get_google_ad() . '</div>';
				break;
			case 'custom':
				echo '<div class="wptouch-custom-ad">' . $settings->custom_advertising_mobile . '</div>';
				break;
			case 'default':
				// Try to get this advertising type from a plugin
				do_action( 'wptouch_advertising_' . $settings->advertising_type );
				break;
		}
	}
}


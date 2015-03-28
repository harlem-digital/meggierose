<?php
add_action( 'foundation_module_init_mobile', 'foundation_webapp_init' );
add_action( 'wptouch_post_head', 'foundation_setup_meta_area' );

add_filter( 'wptouch_body_classes', 'foundation_webapp_body_classes' );
add_filter( 'foundation_inline_style', 'foundation_webapp_inline_style' );
add_filter( 'login_enqueue_scripts', 'foundation_webapp_inject_login_screen_components' );

define( 'WPTOUCH_WEBAPP_COOKIE', 'wptouch-webapp' );
define( 'WPTOUCH_WEBAPP_PERSIST_COOKIE', 'wptouch-webapp-persist' );

function foundation_webapp_mode_enabled() {
	$settings = foundation_get_settings();
	return apply_filters( 'wptouch_allow_wam', $settings->webapp_mode_enabled );
}

function foundation_webapp_inject_login_screen_components() {
	if ( function_exists( 'wptouch_is_showing_mobile_theme_on_mobile_device' ) && wptouch_is_showing_mobile_theme_on_mobile_device() ) {
		echo '<style type="text/css">' . foundation_webapp_inline_style( '' ) . '</style>';
		foundation_setup_meta_area();
		foundation_setup_homescreen_icons();
	}
}

function foundation_webapp_inline_style( $style_data ) {
	require_once( WPTOUCH_DIR . '/core/file-operations.php' );

	return $style_data . wptouch_load_file( dirname( __FILE__ ) . '/add2home.css' );
}

function foundation_webapp_get_style_deps( $other_styles = array() ) {
	$style_deps = $other_styles;

	if ( defined( 'WPTOUCH_MODULE_RESET_INSTALLED' ) ) {
		$style_deps[] = 'foundation_reset';
	}

	return $style_deps;
}

function foundation_webapp_get_persistence_salt() {
	global $blog_id;

	if ( $blog_id ) {
		return substr( md5( $blog_id ), 0, 8 );
	} else return substr( md5( 'none' ), 0, 8 );
}

function foundation_webapp_init() {
	$settings = foundation_get_settings();

	// Do redirect in webapp mode
	if ( $settings->webapp_enable_persistence && wptouch_fdn_is_web_app_mode() ) {
		if ( isset( $_COOKIE[WPTOUCH_WEBAPP_PERSIST_COOKIE . '-' . foundation_webapp_get_persistence_salt()] ) ) {

			$current_url = rtrim( $_SERVER['HTTP_HOST'] . strtolower( $_SERVER['REQUEST_URI'] ), '/' );
			$stored_url = str_replace( array( 'https://', 'http://' ), array( '', '' ), rtrim( strtolower( $_COOKIE[WPTOUCH_WEBAPP_PERSIST_COOKIE . '-' . foundation_webapp_get_persistence_salt()] ), '/' ) );

			if ( $current_url != $stored_url && !isset( $_COOKIE[WPTOUCH_WEBAPP_COOKIE . '-' . foundation_webapp_get_persistence_salt()] ) && empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) {
				$cookie = $_COOKIE[WPTOUCH_WEBAPP_PERSIST_COOKIE . '-' . foundation_webapp_get_persistence_salt()];
				header( 'Location: ' . $cookie );
				die;
			}
		}
	}

	if ( foundation_webapp_mode_enabled() && $settings->webapp_show_notice == true && !wptouch_fdn_is_web_app_mode() ) {

		wp_enqueue_script(
			'foundation_add2home',
			WPTOUCH_URL . '/pro/modules/webapp/add2home.js',
			false,
			FOUNDATION_VERSION,
			true
		);

		wp_enqueue_script(
			'foundation_add2home_config',
			WPTOUCH_URL . '/pro/modules/webapp/add2home-config.js',
			array( 'foundation_add2home' ),
			FOUNDATION_VERSION,
			true
		);

		$add_to_home_strings = array(
			'bubbleMessage' => str_replace( array( '[icon]', '[device]' ), array( '%icon', 'home screen' ), $settings->webapp_notice_message ),
			'bubbleExpiryInDays' => $settings->webapp_notice_expiry_days*60*24
		);

		wp_localize_script( 'foundation_add2home_config', 'wptouchFdnAddToHome',  $add_to_home_strings );

	}

	if ( foundation_webapp_mode_enabled() ) {

		wp_enqueue_script(
			'foundation_webapp',
			WPTOUCH_URL . '/pro/modules/webapp/webapp.js',
			array( 'jquery' ),
			FOUNDATION_VERSION,
			true
		);

		$webapp_strings = array();

		if ( $settings->webapp_ignore_urls ) {

			$ignored_wa_urls = explode( "\n", $settings->webapp_ignore_urls );

			$trimmed_wa_urls = array();
			foreach( $ignored_wa_urls as $wa_url ) {
				$trimmed_wa_urls[] = strtolower( trim( $wa_url ) );
			}

			$webapp_strings[ 'ignoredWebAppURLs' ] = $trimmed_wa_urls;
		}

		$admin_settings = wptouch_get_settings();

		if ( $admin_settings->enable_url_filter && $admin_settings->filtered_urls && $admin_settings->url_filter_behaviour == 'exclude_urls' ) {

			$ignored_urls = explode( "\n", $admin_settings->filtered_urls );

			$trimmed_urls = array();
			foreach( $ignored_urls as $url ) {
				$trimmed_urls[] = strtolower( trim( $url ) );
			}

			$webapp_strings[ 'ignoredURLs' ] = $trimmed_urls;

		}

		$webapp_strings[ 'externalLinkText' ] =  __( 'External link— open it in the browser?', 'wptouch-pro' );
		$webapp_strings[ 'externalFileText' ] = __( 'File link— Do you want to open it in the browser?', 'wptouch-pro' );
		$webapp_strings[ 'persistence' ] = ( $settings->webapp_enable_persistence ? '1' : '0' );
		$webapp_strings[ 'persistenceSalt' ] = foundation_webapp_get_persistence_salt();

		wp_localize_script( 'foundation_webapp', 'wptouchWebApp',  $webapp_strings ) ;
	}
}

function foundation_setup_meta_area() {
	$settings = foundation_get_settings();

	echo '<meta name="apple-mobile-web-app-title" content="' . $settings->homescreen_icon_title . '">' . "\n";

	if ( foundation_webapp_mode_enabled() ) {

		// We're web-app capable
		echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";

		// iOS7
		if ( wptouch_fdn_iOS_7() || wptouch_fdn_iOS_8() ) {
			echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
		} else {
			echo '<meta name="apple-mobile-web-app-status-bar-style" content="black">' . "\n";
		}

		// Check for startup screens
		if ( wptouch_is_device_real_ipad() ) {
			// Only output iPad startup screens

			// iPad Portrait
			if ( $settings->startup_screen_ipad_1_portrait ) {
				echo '<link href="' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_ipad_1_portrait . '" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">' . "\n";
			}

			// iPad Landscape
			if ( $settings->startup_screen_ipad_1_landscape ) {
			echo '<link href="' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_ipad_1_landscape . '" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">' . "\n";
			}

			// iPad Retina Portrait
			if ( $settings->startup_screen_ipad_3_portrait ) {
				echo '<link href="' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_ipad_3_portrait . '" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">' . "\n";
			}

			// iPad Retina Landscape
			if ( $settings->startup_screen_ipad_3_landscape ) {
				echo '<link href="' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_ipad_3_landscape . '" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">' . "\n";
			}
		} else {
			// iPhone
			if ( $settings->startup_screen_iphone_2g_3g ) {
				echo '<link href="' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_iphone_2g_3g . '" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">' . "\n";
			}

			// iPhone Retina
			if ( $settings->startup_screen_iphone_4_4s ) {
				echo '<link href="' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_iphone_4_4s . '" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">' . "\n";
			}

			// iPhone 5
			if ( $settings->startup_screen_iphone_5 ) {
				echo '<link href="' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_iphone_5 . '"  media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">' . "\n";
			}

			// iPhone 6
			if ( $settings->startup_screen_iphone_6 ) {
				echo '<link href="' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_iphone_6 . '"  media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">' . "\n";
				echo '<style>html{background-image:url("' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_iphone_6 . '") no-repeat top left;}</style>';
			}

			// iPhone 6+
			if ( $settings->startup_screen_iphone_6plus ) {
				echo '<link href="' . WPTOUCH_BASE_CONTENT_URL . $settings->startup_screen_iphone_6plus . '"  media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">' . "\n";
			}
		}
	}

}

function foundation_webapp_body_classes( $classes ) {
	$settings = foundation_get_settings();

	if ( wptouch_fdn_is_web_app_mode() && isset( $_COOKIE[ WPTOUCH_WEBAPP_COOKIE . '-' . foundation_webapp_get_persistence_salt() ] ) ) {
		$classes[] = 'web-app-mode';
	}

	return $classes;
}

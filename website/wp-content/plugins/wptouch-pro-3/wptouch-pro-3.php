<?php
/*
	Plugin Name: WPtouch Pro
	Plugin URI: http://www.wptouch.com/
	Version: 3.7.4.1
	Description: The easy way to create great mobile experiences with your WordPress website.
	Author: BraveNewCode Inc.
	Author URI: http://www.wptouch.com/
	Text Domain: wptouch-pro
	Domain Path: /lang
	License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	Trademark: 'WPtouch Pro' is a registered trademark of BraveNewCode Inc., and can not be re-used in conjuction with GPL v2 distributions or conveyances of this software under the license terms of the GPL v2 without express prior permission of BraveNewCode Inc.
*/

define( 'WPTOUCH_IS_PRO', true );

function wptouch_pro_create_three_object() {
	define( 'WPTOUCH_VERSION', '3.7.4.1' );

	define( 'WPTOUCH_BASE_NAME', basename( __FILE__, '.php' ) . '.php' );
	define( 'WPTOUCH_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . basename( __FILE__, '.php' ) );

	$data = explode( DIRECTORY_SEPARATOR, WPTOUCH_DIR );
	define( 'WPTOUCH_ROOT_NAME', $data[ count( $data ) - 1 ] );

	global $wptouch_pro;

	if ( !$wptouch_pro ) {
		require_once( 'core/bncid.php' );

		// Load main configuration information - sets up directories and constants
		require_once( 'core/config.php' );

		// Load global functions
		require_once( 'core/globals.php' );

		// Load main compatibility file
		require_once( 'core/compat.php' );

		// Load main WPtouch Pro class
		require_once( 'core/class-wptouch-pro.php' );

		// Load main debugging class
		require_once( 'core/class-wptouch-pro-debug.php' );

		// Load right-to-left text code
		require_once( 'core/rtl.php' );

		$wptouch_pro = new WPtouchProThree;
		$wptouch_pro->initialize();

		do_action( 'wptouch_pro_loaded' );
	}
}

// Global WPtouch Pro activation hook
function wptouch_pro_handle_activation() {
	global $wptouch_pro;
	if ( !$wptouch_pro ) {
		wptouch_pro_create_three_object();
	}

	$wptouch_pro->handle_activation();
}

// Global WPtouch Pro deactivation hook
function wptouch_pro_handle_deactivation() {
	global $wptouch_pro;
	if ( !$wptouch_pro ) {
		wptouch_pro_create_three_object();
	}

	$wptouch_pro->handle_deactivation();
}

// Activation hook for some basic initialization
register_activation_hook( __FILE__,  'wptouch_pro_handle_activation' );
register_deactivation_hook( __FILE__, 'wptouch_pro_handle_deactivation' );

// Main WPtouch Pro activation hook
add_action( 'plugins_loaded', 'wptouch_pro_create_three_object' );
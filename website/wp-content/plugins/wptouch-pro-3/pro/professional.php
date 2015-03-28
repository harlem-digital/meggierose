<?php

add_action( 'wptouch_admin_ajax_intercept', 'wptouch_pro_admin_ajax_intercept' );
add_filter( 'wptouch_theme_directories', 'wptouch_pro_theme_directories' );
add_filter( 'wptouch_addon_directories', 'wptouch_pro_addon_directories' );
add_filter( 'wptouch_settings_compat', 'wptouch_pro_add_compat_settings' );
add_action( 'admin_enqueue_scripts', 'wptouch_pro_pointers', 1000 );

function wptouch_pro_add_compat_settings( $page_options ) {
	if ( function_exists( 'icl_get_languages' ) ) {
		wptouch_add_page_section(
			WPTOUCH_ADMIN_SETUP_COMPAT,
			'WPML',
			'compat-wpml',
			array(
				wptouch_add_setting(
					'checkbox',
					'show_wpml_lang_switcher',
					__( 'Show WPML language switcher in theme footer', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'3.2.1'
				)
			),
			$page_options
		);
	}

	return $page_options;
}

function wptouch_pro_addon_directories( $addon_directories ) {
	if ( wptouch_is_multisite_enabled() ) {
		$addon_directories[] = array( WPTOUCH_BASE_CONTENT_MS_DIR . '/extensions', WPTOUCH_BASE_CONTENT_MS_URL . '/extensions' );
	}

	$addon_directories[] = array( WPTOUCH_BASE_CONTENT_DIR . '/extensions', WPTOUCH_BASE_CONTENT_URL . '/extensions' );

	return $addon_directories;
}

function wptouch_pro_theme_directories( $theme_directories ) {
	if ( wptouch_is_multisite_enabled() ) {
		$theme_directories[] = array( WPTOUCH_BASE_CONTENT_MS_DIR . '/themes', WPTOUCH_BASE_CONTENT_MS_URL . '/themes' );
	}

	$theme_directories[] = array( WPTOUCH_BASE_CONTENT_DIR . '/themes', WPTOUCH_BASE_CONTENT_URL . '/themes' );

	return $theme_directories;
}

// Functions only available in the pro version
function wptouch_can_show_license_menu() {
	$should_show_license_menu = true;

	if ( !wptouch_is_multisite_enabled( ) ) {
		$settings = wptouch_get_settings( 'bncid' );
		$should_show_license_menu = ( !$settings->license_accepted );
	} else {
		if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
			// Plugin is network activated
			$settings = wptouch_get_settings( 'bncid' );
			$should_show_license_menu = ( !$settings->license_accepted ) && is_network_admin();
		} else {
			$settings = wptouch_get_settings( 'bncid' );
			$should_show_license_menu = ( !$settings->license_accepted );
		}
	}

	return $should_show_license_menu;
}

function wptouch_should_show_license_nag() {
	if ( wptouch_is_multisite_enabled() ) {
		$settings = wptouch_get_settings( 'bncid' );
		if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
			return ( !$settings->license_accepted ) && current_user_can( 'manage_network_options' );
		} else {
			return ( !$settings->license_accepted );
		}
	} else {
		return wptouch_can_show_license_menu();
	}
}

function wptouch_show_renewal_notice() {
	$settings = wptouch_get_settings( 'bncid' );

	if ( isset( $settings->license_expired ) ) {
		return $settings->license_expired;
	} else {
		return false;
	}
}

function wptouch_is_update_available() {
	global $wptouch_pro;

	return $wptouch_pro->check_for_update();
}

function wptouch_get_license_activation_url() {
	if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
		return network_admin_url( 'admin.php?page=wptouch-admin-license' );
	} else {
		return admin_url( 'admin.php?page=wptouch-admin-license' );
	}
}

function wptouch_add_pro_notifications() {
	global $wptouch_pro;

	// Check if licensed
	if ( ( WPTOUCH_SIMULATE_ALL || !wptouch_has_license() ) ) {
		if ( wptouch_show_renewal_notice() ) {
			$wptouch_pro->add_notification(
				__( 'License Expired', 'wptouch-pro' ),
				__( 'Your product license has expired. Renew now to continue to receive feature and security updates.', 'wptouch-pro' ),
				'error',
				'http://www.wptouch.com/renew/?utm_campaign=renew-notification&utm_medium=web&utm_source=wptouch'
			);
		} else if ( wptouch_should_show_license_nag() ) {
			$wptouch_pro->add_notification(
				__( 'License Missing', 'wptouch-pro' ),
				__( 'This installation of WPtouch Pro is currently unlicensed.', 'wptouch-pro' ),
				'error',
				wptouch_admin_url( 'admin.php?page=wptouch-admin-license' )
			);
		}
	}

	// Plugin upgrade available
	$version = wptouch_is_upgrade_available();
	if ( ( WPTOUCH_SIMULATE_ALL || $version ) ) {
		if ( !wptouch_has_license() ) {
			if ( wptouch_show_renewal_notice() ) {
				$wptouch_pro->add_notification(
					sprintf( __( 'WPtouch Pro %s', 'wptouch-pro' ), $version ),
					__( 'A new version of WPtouch Pro is available. Renew your license to re-enable product updates.', 'wptouch-pro' ),
					'upgrade',
					'http://www.wptouch.com/renew/'
				);
			} else if ( wptouch_should_show_license_nag() ) {
				$wptouch_pro->add_notification(
					sprintf( __( 'WPtouch Pro %s', 'wptouch-pro' ), $version ),
					__( 'A new version of WPtouch Pro is available. Please activate or purchase a license to enable product updates.', 'wptouch-pro' ),
					'upgrade',
					'http://www.wptouch.com/pricing/'
				);
			}
		} else {
			$wptouch_pro->add_notification(
				sprintf( __( 'WPtouch Pro %s', 'wptouch-pro' ), $version ),
				__( 'A new version of WPtouch Pro is available.', 'wptouch-pro' ),
				'upgrade',
				is_multisite() ? network_admin_url( 'plugins.php?plugin_status=upgrade' ) : wptouch_admin_url( 'plugins.php?plugin_status=upgrade' )
			);
		}
	}


	// Theme upgrade available
	$available_themes = $wptouch_pro->get_available_themes( true );
	foreach( $available_themes as $name => $theme ) {
		if ( isset( $theme->upgrade_available ) && $theme->upgrade_available ) {
			$wptouch_pro->add_notification(
				__( 'Theme Update Available', 'wptouch-pro' ),
				__( 'One or more updates are available for your installed themes.', 'wptouch-pro' ),
				'upgrade',
				admin_url( 'admin.php?page=wptouch-admin-themes-and-addons' )
			);

			break;
		}
	}

	// Add-on upgrade available
	$available_addons = $wptouch_pro->get_available_addons( true );
	foreach( $available_addons as $name => $addons ) {
		if ( isset( $addons->upgrade_available ) && $addons->upgrade_available ) {
			$wptouch_pro->add_notification(
				__( 'Extension Update Available', 'wptouch-pro' ),
				__( 'One or more updates are available for your installed extensions.', 'wptouch-pro' ),
				'upgrade',
				admin_url( 'admin.php?page=wptouch-admin-themes-and-addons' )
			);

			break;
		}
	}

	// Error
	if ( WPTOUCH_SIMULATE_ALL || function_exists( 'wptouch_init' ) ) {
		$wptouch_pro->add_notification(
			'WPtouch 1.x',
			__( 'WPtouch Pro 3 cannot co-exist with WPtouch 1.x. Disable it first in the WordPress Plugins settings.', 'wptouch-pro' ),
			'error',
			'https://support.wptouch.com/support/solutions/articles/5000525434-known-incompatibilities'
		);
	}

	// Error
	if ( WPTOUCH_SIMULATE_ALL || defined( 'WPTOUCH_PRO_MIN_BACKUP_FILES' ) ) {
		$wptouch_pro->add_notification(
			'WPtouch 2.x',
			__( 'WPtouch Pro 3 cannot co-exist with WPtouch Pro 2.x. Disable it first in the WordPress Plugins settings.', 'wptouch-pro' ),
			'error',
			'https://support.wptouch.com/support/solutions/articles/5000525434-known-incompatibilities'
		);
	}
}

function wptouch_pro_pointers( $hook_suffix ) {
	global $wptouch_pro;

    // Don't run on WP < 3.3
    if ( get_bloginfo( 'version' ) < '3.3' )
        return;

    $screen = get_current_screen();
    $screen_id = $screen->id;

    $theme_update = false;
    $addon_update = false;

    // Theme upgrade available
	$available_themes = $wptouch_pro->get_available_themes( true );
	foreach( $available_themes as $name => $theme ) {
		if ( isset( $theme->upgrade_available ) && $theme->upgrade_available ) {
			$theme_update = true;
			break;
		}
	}

	// Add-on upgrade available
	$available_addons = $wptouch_pro->get_available_addons( true );
	foreach( $available_addons as $name => $addons ) {
		if ( isset( $addons->upgrade_available ) && $addons->upgrade_available ) {
			$addon_update = true;
			break;
		}
	}

	if ( $theme_update && $addon_update ) {
		$pointer_title = __( 'WPtouch Pro: Theme &amp; Extension Updates Available', 'wptouch-pro' );
		$pointer_content = __( 'One or more updates are available for your installed themes and extensions.', 'wptouch-pro' );
	} elseif( $theme_update ) {
		$pointer_title = __( 'WPtouch Pro: Theme Updates Available', 'wptouch-pro' );
		$pointer_content = __( 'One or more updates are available for your installed themes.', 'wptouch-pro' );
	} else {
		$pointer_title = __( 'WPtouch Pro: Extension Updates Available', 'wptouch-pro' );
		$pointer_content = __( 'One or more updates are available for your installed extensions.', 'wptouch-pro' );
	}

   	$target = '#toplevel_page_wptouch-admin-touchboard';

    if ( $theme_update || $addon_update ) {
	    // Get dismissed pointers
	    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	    $pointer_id = 'wptouch_theme_addon_updates_' . date( 'y_W' );

		$pointers['pointers'][] = array(
			'id' => $pointer_id,
	        'target' => $target,
	        'options' => array(
	            'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
	                $pointer_title,
	                $pointer_content
	            ),
	            'position' => array( 'edge' => 'left', 'align' => 'middle' )
	        )
	    );

        // Sanity check
        if ( in_array( $pointer_id, $dismissed ) ) {
        	return;
        }

	    // Add pointers style to queue.
	    wp_enqueue_style( 'wp-pointer' );

	    // Add pointers script to queue. Add custom script.
	    wp_enqueue_script( 'wptouch-pointer', WPTOUCH_URL . '/admin/js/wptouch-admin-pointer.js', array( 'wp-pointer' ) );

	    // Add pointer options to script.
	    wp_localize_script( 'wptouch-pointer', 'wptouchpointer', $pointers );
	}
}

function wptouch_pro_admin_ajax_intercept( $ajax_action ) {
	global $wptouch_pro;

	switch( $ajax_action) {
		case 'activate-license-key':
			$email = $wptouch_pro->post['email'];
			$key = $wptouch_pro->post['key'];

			$settings = wptouch_get_settings( 'bncid' );
			$old_settings = $settings;

			$settings->bncid = $email;
			$settings->wptouch_license_key = $key;

			WPTOUCH_DEBUG( WPTOUCH_INFO, "Attempting site activation with email [" . $email . "] and key [" . $key . "]" );

			$settings->save();

			$wptouch_pro->bnc_api = false;
			$wptouch_pro->setup_bncapi();

			// let's try to activate the license
			$wptouch_pro->activate_license();

			// Check to see if the credentials were valid
			if ( $wptouch_pro->bnc_api->response_code >= 406 && $wptouch_pro->bnc_api->response_code <= 408 ) {
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Activation response code was [" . $wptouch_pro->bnc_api->response_code . "]" );
				echo '2';
			} else if ( $wptouch_pro->bnc_api->server_down ) {
				// Server is unreachable for some reason
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Activation response code was [SERVER UNREACHABLE]" );
				echo '4';
			} else if ( $wptouch_pro->bnc_api->verify_site_license() ) {
				// Activation successful
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Activation successful, response code was [" . $wptouch_pro->bnc_api->response_code . "]" );

				$settings = wptouch_get_settings( 'bncid' );

				$settings->license_accepted = 1;
				$settings->license_accepted_time = time();

				$settings->save();

				echo '1';
			} else {
				$bnc_info = $wptouch_pro->bnc_api->check_api();

				if ( isset( $bnc_info[ 'license_expired' ] ) && $bnc_info[ 'license_expired' ] ) {
					WPTOUCH_DEBUG( WPTOUCH_WARNING, "Failure: license is expired [" . $wptouch_pro->bnc_api->response_code . "]" );
					echo '5';
				} else {
					// No more licenses - might be triggered another way
					WPTOUCH_DEBUG( WPTOUCH_WARNING, "Failure: activation response code was [" . $wptouch_pro->bnc_api->response_code . "]" );
					echo '3';
				}
			}
			break;
		case 'reset-license-info':
			$bnc_settings = wptouch_get_settings( 'bncid' );
			$bnc_settings->bncid = '';
			$bnc_settings->wptouch_license_key = '';

			$bnc_settings->license_accepted = false;
			$bnc_settings->license_accepted_time = 0;
			$bnc_settings->next_update_check_time = 0;
			$bnc_settings->license_expired = false;
			$bnc_settings->license_expiry_date = 0;

			$bnc_settings->save();
			break;
		case 'download-addon':
			global $wptouch_pro;

			require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

			$addon_installer = new WPtouchAddonThemeInstaller;
			$addon_installer->install( $wptouch_pro->post[ 'base' ] , $wptouch_pro->post[ 'url' ], 'extensions' );

			$result = array();

			if ( file_exists( WPTOUCH_BASE_CONTENT_DIR . '/extensions/' . $wptouch_pro->post[ 'base' ] ) ) {
				$result['status'] = 1;
			} else {
				$result['status'] = 0;
				if ( $addon_installer->had_error() ) {
					$result['error'] = $addon_installer->error_text();
				} else {
					$result['error'] = __( 'Unknown error', 'wptouch-pro' );
				}
			}

			echo json_encode( $result );

			break;
		case 'download-theme':
			global $wptouch_pro;

			require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

			$addon_installer = new WPtouchAddonThemeInstaller;
			$addon_installer->install( $wptouch_pro->post[ 'base' ] , $wptouch_pro->post[ 'url' ], 'themes' );

			$result = array();

			if ( file_exists( WPTOUCH_BASE_CONTENT_DIR . '/themes/' . $wptouch_pro->post[ 'base' ] ) ) {
				$result['status'] = 1;
			} else {
				$result['status'] = 0;
				if ( $addon_installer->had_error() ) {
					$result['error'] = $addon_installer->error_text();
				} else {
					$result['error'] = __( 'Unknown error', 'wptouch-pro' );
				}
			}

			echo json_encode( $result );

			break;
	}
}

function wptouch_pro_handle_admin_command() {
	global $wptouch_pro;

	if ( isset( $wptouch_pro->get['admin_command'] ) ) {
		$admin_menu_nonce = $wptouch_pro->get['admin_menu_nonce'];
		if ( wptouch_admin_menu_nonce_is_valid( $admin_menu_nonce ) ) {
			// check user permissions
			if ( current_user_can( 'switch_themes' ) ) {
				switch( $wptouch_pro->get['admin_command'] ) {
					case 'activate_theme':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Activating theme [' . $wptouch_pro->get['theme_name'] . ']' );
						wptouch_pro_activate_theme( $wptouch_pro->get['theme_name'], $wptouch_pro->get[ 'theme_location' ] );
						break;
					case 'activate_addon':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Activating add-on [' . $wptouch_pro->get['addon_name'] . ']' );
						$addon_to_activate = $wptouch_pro->get['addon_name'];
						if ( $addon_to_activate ) {
							$settings = $wptouch_pro->get_settings();

							if ( !isset( $settings->active_addons[ $addon_to_activate ] ) ) {
								$paths = explode( '/', ltrim( rtrim( $wptouch_pro->get['addon_location'], DIRECTORY_SEPARATOR ), DIRECTORY_SEPARATOR ) );

								$addon_info = new stdClass;

								$addon_info->addon_name = $paths[ count( $paths ) - 1 ];
								unset( $paths[ count( $paths ) - 1 ] );
								$addon_info->location = '/' . implode( '/', $paths );

								$settings->active_addons[ $addon_to_activate ] = $addon_info;

								$settings->save();
							}
						}
						break;
					case 'deactivate_addon':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Deactivating add-on [' . $wptouch_pro->get['addon_name'] . ']' );
						$addon_to_deactivate = $wptouch_pro->get['addon_name'];
						if ( $addon_to_deactivate ) {
							$settings = $wptouch_pro->get_settings();

							if ( isset( $settings->active_addons[ $addon_to_deactivate ] ) ) {
								unset( $settings->active_addons[ $addon_to_deactivate ] );
								$settings->save();
							}
						}
						break;
					case 'copy_theme':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Copying theme [' . $wptouch_pro->get['theme_name'] . ']' );
						wptouch_pro_copy_theme( $wptouch_pro->get[ 'theme_name' ], $wptouch_pro->get['theme_location'] );
						break;
					case 'delete_theme':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Deleting theme [' . $wptouch_pro->get['theme_location'] . ']' );
						require_once( WPTOUCH_DIR . '/core/file-operations.php' );

						$theme_location = WP_CONTENT_DIR . $wptouch_pro->get['theme_location'];

						$wptouch_pro->recursive_delete( $theme_location );
						break;
				}
			}
		}

		$used_query_args = array( 'admin_menu_nonce', 'admin_command', 'theme_name', 'theme_location' );

		header( 'Location: ' . remove_query_arg( $used_query_args ) );
		die;
	}
}

function wptouch_pro_activate_theme( $theme_name, $theme_location ) {
	global $wptouch_pro;

	if ( $theme_name ) {
		$settings = $wptouch_pro->get_settings();

		$paths = explode( '/', ltrim( rtrim( $theme_location, '/' ), '/' ) );

		$settings->current_theme_name = $paths[ count( $paths ) - 1 ];
		unset( $paths[ count( $paths ) - 1 ] );

		$settings->current_theme_location = '/' . implode( '/', $paths );
		$settings->current_theme_friendly_name = $theme_name;

		$settings->save();
	}
}

function wptouch_pro_copy_theme( $theme_name, $theme_location ) {
	global $wptouch_pro;

	require_once( WPTOUCH_DIR . '/core/file-operations.php' );
	$theme_location = WP_CONTENT_DIR . $theme_location;
	$theme_name = wptouch_convert_to_class_name( $theme_name );

	$num = $wptouch_pro->get_theme_copy_num( $theme_name );
	$copy_dest = WPTOUCH_CUSTOM_THEME_DIRECTORY . '/' . $theme_name . '-copy-' . $num;
	wptouch_create_directory_if_not_exist( $copy_dest );

	$wptouch_pro->recursive_copy( $theme_location, $copy_dest );

	$readme_file = $copy_dest . '/readme.txt';
	$readme_info = $wptouch_pro->load_file( $readme_file );
	if ( $readme_info ) {
		if ( preg_match( '#Theme Name: (.*)#', $readme_info, $matches ) ) {
			$new_name = $matches[1] . ' Copy #' . $num;
			$readme_info = str_replace( $matches[0], 'Theme Name: ' . $new_name, $readme_info );
			$f = fopen( $readme_file, "w+t" );
			if ( $f ) {
				fwrite( $f, $readme_info );
				fclose( $f );
			}
		}
		return array( 'name' => $new_name, 'location' => $copy_dest );
	} else {
		WPTOUCH_DEBUG( WPTOUCH_ERROR, "Unable to modify readme.txt file after copy" );
		return false;
	}
}

function wptouch_pro_check_for_update() {
	global $wptouch_pro;

	$upgrade_available = false;

	$wptouch_pro->setup_bncapi();

	$bnc_api = $wptouch_pro->get_bnc_api();

	$plugin_name = WPTOUCH_ROOT_NAME . '/wptouch-pro-3.php';

	WPTOUCH_DEBUG( WPTOUCH_INFO, 'Checking BNC server for a new product update' );

    // Check for WordPress 3.0 function
	if ( function_exists( 'is_super_admin' ) ) {
		$option = get_site_transient( 'update_plugins' );
	} else {
		$option = function_exists( 'get_transient' ) ? get_transient( 'update_plugins' ) : get_option( 'update_plugins' );
	}

	$version_available = false;

	if ( false === ( $latest_info = get_site_transient( '_wptouch_bncid_latest_version' ) ) ) {
		$latest_info = $bnc_api->get_product_version();

		set_site_transient( '_wptouch_bncid_latest_version', $latest_info, WPTOUCH_API_GENERAL_CACHE_TIME );
	}

	if ( $latest_info && $latest_info[ 'version' ] != WPTOUCH_VERSION ) {
		WPTOUCH_DEBUG( WPTOUCH_INFO, 'A new product update is available [' . $latest_info['version'] . ']' );

		if ( isset( $latest_info[ 'upgrade_url' ] ) && wptouch_has_license() ) {
			if ( !isset( $option->response[ $plugin_name ] ) ) {
				$option->response[ $plugin_name ] = new stdClass();
			}

			// Update upgrade options
			$option->response[ $plugin_name ]->url = 'http://www.wptouch.com/';
			$option->response[ $plugin_name ]->package = $latest_info[ 'upgrade_url' ];
			$option->response[ $plugin_name ]->new_version = $latest_info['version'];
			$option->response[ $plugin_name ]->id = '0';
			$option->response[ $plugin_name ]->slug = WPTOUCH_ROOT_NAME;
		} else {
			if ( is_object( $option ) && isset( $option->response ) ) {
				unset( $option->response[ $plugin_name ] );
			}
		}

		$wptouch_pro->latest_version_info = $latest_info;
		$upgrade_available = $latest_info['version'];
	} else {
		if ( is_object( $option ) && isset( $option->response ) ) {
			unset( $option->response[ $plugin_name ] );
		}
	}

	// WordPress 3.0 changed some stuff, so we check for a WP 3.0 function
	if ( function_exists( 'is_super_admin' ) ) {
		set_site_transient( 'update_plugins', $option );
	} else if ( function_exists( 'set_transient' ) ) {
		set_transient( 'update_plugins', $option );
	}

	return $upgrade_available;
}

function wptouch_pro_update_site_info() {
	global $wptouch_pro;

	$wptouch_pro->setup_bncapi();
	$bnc_api = $wptouch_pro->get_bnc_api();

	$settings = wptouch_get_settings();
	$active_addons = array();
	foreach( $settings->active_addons as $name => $info ) {
		$active_addons[] = $info->addon_name;
	}

	$settings_diff = array();

	$all_domains = $wptouch_pro->get_active_setting_domains();
	foreach( $all_domains as $domain ) {
		if ( $domain == 'bncid' ) {
			continue;
		}

		$this_diff = new stdClass;
		$default_settings = $wptouch_pro->get_setting_defaults( $domain );

		$settings = wptouch_get_settings( $domain );
		foreach( $settings as $key => $value ) {
			if ( !isset( $default_settings->$key ) || $settings->$key != $default_settings->$key ) {
				$this_diff->$key = $settings->$key;
			}
		}

		unset( $this_diff->domain );

		if ( count( (array)$this_diff ) ) {
			$settings_diff[ $domain ] = $this_diff;
		}
	}

	$wptouch_pro->bnc_api->user_update_info( WPTOUCH_VERSION, 'bauhaus', $active_addons, $settings_diff );
}


function mwp_wptouch_pro_get_latest_info() {
	global $wptouch_pro;

	$latest_info = false;

	// Do some basic caching
	$mwp_info = get_option( 'wptouch_pro_mwp', false );
	if ( !$mwp_info || !is_object( $mwp_info ) ) {
		$mwp_info = new stdClass;
		$mwp_info->last_check = 0;
		$mwp_info->last_result = false;
	}

	$time_since_last_check = time() - $mwp_info->last_check;
	if ( $time_since_last_check > 300 ) {
		$wptouch_pro->setup_bncapi();
    	$bnc_api = $wptouch_pro->get_bnc_api();
    	if ( $bnc_api ) {
    		$latest_info = $bnc_api->get_product_version();
    		if ( $latest_info ) {
    			$mwp_info->last_result = $latest_info;
    			$mwp_info->last_check = time();

    			// Save the result
    			update_option( 'wptouch_pro_mwp', $mwp_info );
    		}
    	}
	} else {
		// Use the cached copy
		$latest_info = $mwp_info->last_result;
	}

	return $latest_info;
}

add_action( 'wptouch_version_update', 'wptouch_auto_update_themes_addons' );

function md5_dir($dir)
{
    if (!is_dir($dir))
    {
        return false;
    }

    $filemd5s = array();
    $d = dir($dir);

    while (false !== ($entry = $d->read()))
    {
        if ($entry != '.' && $entry != '..')
        {
             if (is_dir($dir.'/'.$entry))
             {
                 $filemd5s[] = md5_dir($dir.'/'.$entry);
             }
             else
             {
             	if ( $entry != '.DS_Store' ) {
                 $filemd5s[] = md5_file($dir.'/'.$entry);
             	}
             }
         }
    }
    $d->close();
    return md5(implode('', $filemd5s));
}

function wptouch_auto_update_themes_addons() {
	global $wptouch_pro;
	if ( !isset( $wptouch_pro->post ) ) {
		require_once( WPTOUCH_DIR . '/core/theme-hashes.php' );
		$theme_hashes = wptouch_get_hashes();

		if ( current_user_can( 'manage_options' ) ) {
			$current_theme = $wptouch_pro->get_current_theme_info();

			$available_themes = $wptouch_pro->get_available_themes( true );
			$available_addons = $wptouch_pro->get_available_addons( true );
			$updates = 0;
			$backed_up = false;
			$errors = array();

			if ( count( $available_themes ) > 0 || count( $available_addons ) > 0 ) {

				require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

				foreach( $available_themes as $name => $theme ) {
					$hash = false;
					$safe_to_install_theme = false;

					if ( isset( $theme->upgrade_available ) && $theme->upgrade_available ) {
						if ( !is_object( $current_theme ) || $name != $current_theme->name ) {
							$safe_to_install_theme = true;
						} else {
							$hash = md5_dir( WP_CONTENT_DIR . $current_theme->location );
							if ( in_array( $theme->version, $theme_hashes[ $theme->base ] ) && $hash == $theme_hashes[ $theme->base ][ $theme->version] ) {
								// OK to upgrade the active theme (it's the same as when it shipped)
								$safe_to_install_theme = true;
							}
						}

						// Hashes didn't match.
						if ( $safe_to_install_theme === false ) {
							$copied_theme = wptouch_pro_copy_theme( $theme->name, $theme->location );
							if ( is_array( $copied_theme ) ) {
								wptouch_pro_activate_theme( $copied_theme[ 'name' ], $copied_theme[ 'location' ] );
								$backed_up = $theme->name;
							}
						}

						$installer = new WPtouchAddonThemeInstaller;
						$installer->install( $theme->base , $theme->download_url, 'themes' );
						if ( $installer->had_error() ) {
							$errors[] = $installer->error_text();
						} else {
							$updates++;
						}
					}
				}

				foreach( $available_addons as $name => $addon ) {
					if ( isset( $addon->upgrade_available ) && $addon->upgrade_available ) {
						$installer = new WPtouchAddonThemeInstaller;
						$installer->install( $addon->base , $addon->download_url, 'extensions' );
						if ( $installer->had_error() ) {
							$errors[] = $installer->error_text();
						} else {
							$updates++;
						}
					}
				}

				if ( $backed_up ) {
					$backed_up = sprintf( __( '%sYour customizations to %s have been saved as a new theme and the original reinstalled for your reference.', 'wptouch-pro' ), '</p><p>', $backed_up );
				}

				if ( $updates && count( $errors ) > 0 ) {
					echo '<div id="message" class="error"><p>' . __( 'WPtouch Pro: Some themes or extensions could not be auto-updated. Please check the WPtouch Pro Themes &amp; Extensions page to manually update them.', 'wptouch-pro' ) . $backed_up . '</p></div>';
				} elseif ( $updates ) {
					echo '<div id="message" class="updated"><p>' . __( 'WPtouch Pro: Your themes and extensions have been automatically updated. Thank you for updating WPtouch Pro!', 'wptouch-pro' ) . $backed_up . '</p></div>';
				}
			}
		}
	}
}
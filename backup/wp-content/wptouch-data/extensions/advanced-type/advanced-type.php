<?php
define( 'ADVANCED_TYPE_VERSION', '1.0.2' );
define( 'ADDON_ADVANCED_TYPE_PAGENAME', 'Advanced Type' );

// Google requires an API key to access their font lists.
// Key currently limited to 10,000 requests/day.
define( 'GOOGLE_API_KEY', 'AIzaSyA0wmENxwGZ4KXVKNpLVImA-pMRdYbzrgI' );

add_action( 'wptouch_admin_head', 'wptouch_addon_type_admin_head' );
add_filter( 'admin_init', 'wptouch_addon_type_admin_init' );
add_filter( 'wptouch_addon_options', 'wptouch_addon_type_content_options' );
add_filter( 'wptouch_setting_defaults_addons', 'wptouch_addon_type_content_settings_defaults' );
add_action( 'wptouch_parent_style_queued', 'wptouch_addon_type_init_mobile' );
add_action( 'wptouch_admin_ajax_reload_fonts', 'wptouch_addon_type_handle_ajax_reload_fonts' );
add_filter( 'wptouch_body_classes', 'wptouch_addon_advanced_type_body_classes' );

function wptouch_addon_advanced_type_body_classes( $classes ) {
	$classes['font'] = 'body-font';
	return $classes;
}

add_action('admin_notices', 'advanced_type_notice');
function advanced_type_notice() {
	if ( !ini_get( 'allow_url_fopen' ) ) {
	    echo '<div class="error">
	       	<p>' . sprintf( __( '%sAdvanced Type for WPtouch Pro%s requires %s to be enabled on your server in order to load web fonts. Please contact your server administrator or web host to enable this function.', 'wptouch-pro' ), '<strong>', '</strong>', '<code>allow_url_fopen</code>' ) .'</p>
	    	</div>';
   }
}

// If the customer is using custom fonts, suppress the core font module's output.
$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );
if ( $settings->advanced_type_source != 'theme' ) {
	add_filter( 'wptouch_filter_google_fonts', 'wptouch_advanced_type_return_false' );
}

function wptouch_advanced_type_return_false() {
	return false;
}

function wptouch_addon_type_admin_head() {
	echo '<script id="fontListTemplate" type="text/x-jsrender">';
	echo '	<option value="{{:family}}" {{:~selected( family, ~location )}}>{{:name}}</option>';
	echo '</script>';
}

function wptouch_addon_type_handle_ajax_reload_fonts( $args ) {
	if ( $args->post[ 'source' ] == 'typekit' ) {
		$font_set = advanced_type_get_fonts( 'typekit', $args->post[ 'kit' ] );
	} elseif ( $args->post[ 'source' ] == 'fontdeck' ) {
		$font_set = advanced_type_get_fonts( 'fontdeck', $args->post[ 'project' ], $args->post[ 'domain' ] );
	}

	echo json_encode( $font_set[ 'raw' ] );
}

function wptouch_addon_type_admin_init() {
	global $wptouch_pro;
	$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );
	if ( $wptouch_pro->admin_is_wptouch_page() ) {
		wp_enqueue_script(
			'js-render',
			WPTOUCH_BASE_CONTENT_URL . '/extensions/advanced-type/jsrender.min.js',
			array( 'wptouch-pro-admin' ),
			ADVANCED_TYPE_VERSION,
			true
		);

		wp_enqueue_script(
			'advanced-type',
			WPTOUCH_BASE_CONTENT_URL . '/extensions/advanced-type/admin.js',
			array( 'wptouch-pro-admin' ),
			ADVANCED_TYPE_VERSION,
			true
		);

		$google_fonts = advanced_type_get_fonts( 'google' );

		$typekit_fonts = advanced_type_get_fonts( 'typekit', $settings->advanced_type_typekit_kit );

		$fontdeck_fonts = advanced_type_get_fonts( 'fontdeck', $settings->advanced_type_fontdeck_project, $settings->advanced_type_fontdeck_domain );

		$type_data = array(
			'load_fonts_message' => __( 'Load Fonts', 'wptouch-pro' ),
			'load_fonts_error' => __( 'Could not load your fonts. Please check project/kit details and reload.', 'wptouch-pro' ),
			'active_fonts' => json_encode( $settings->advanced_type_fonts ),
			'google_fonts' => json_encode( $google_fonts[ 'raw' ] ),
			'typekit_fonts' => ( $typekit_fonts[ 'raw' ] != 'error' ) ? json_encode( $typekit_fonts[ 'raw' ] ) : '',
			'fontdeck_fonts' => ( $fontdeck_fonts[ 'raw' ] != 'error' ) ? json_encode( $fontdeck_fonts[ 'raw' ] ) : ''
		);

		wp_localize_script( 'advanced-type', 'type_data', $type_data );

		wp_enqueue_style(
			'advanced-type',
			WPTOUCH_BASE_CONTENT_URL . '/extensions/advanced-type/admin.css',
			'',
			ADVANCED_TYPE_VERSION
		);
	}
}

function wptouch_addon_type_content_settings_defaults( $settings ) {
	// Default to using the theme's bundled font pairings.
	$settings->advanced_type_source = 'theme';

	$settings->advanced_type_typekit_kit = false;

	$settings->advanced_type_fontdeck_project = false;
	$settings->advanced_type_fontdeck_domain = $_SERVER[ 'SERVER_NAME' ];

	$settings->advanced_type_fonts = array();
	$settings->advanced_type_font_subsets = array();

	return $settings;
}

function advanced_type_catch_warnings( $errno, $errstr ) {
	throw new Exception( $errstr );
}

function advanced_type_get_fonts( $font_source, $param1=false, $param2=false ) {
	//	Delete Transients
	// delete_transient( 'google_fonts' );
	// delete_transient( 'typekit_fonts_' . $param1 );
	// delete_transient( 'fontdeck_fonts_' . $param1 );

	if ( ini_get( 'allow_url_fopen' ) ) {
		$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );
		// Typekit: param1 = kit ID
		// Fontdeck: param1 = project ID, param2 = domain

		switch ( $font_source ) {
			case 'google':
				if ( false === ( $font_set = get_transient( 'google_fonts' ) ) ) {
					$permitted_weights = array( 'regular', 'italic', 'bold', 'bolditalic', '700', '700italic' );

					set_error_handler( 'advanced_type_catch_warnings' );
					try {
						$font_list_raw = json_decode( file_get_contents( 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . GOOGLE_API_KEY ) );

						foreach( $font_list_raw->items as $font ) {
							$weights = ':';
							$subsets = ':';

							foreach ( $font->variants as $font_weight ) {
								if ( in_array( $font_weight, $permitted_weights ) ) {
									$weights .= ',' . $font_weight;
								}
							}

							foreach ( $font->subsets as $font_subset ) {
								$subsets .= ',' . $font_subset;
							}

							$font_set[ 'options' ][ $font->family . $weights . $subsets ] = $font->family;
							$font_set[ 'raw' ][] = array( 'family' => $font->family . $weights . $subsets, 'name' => $font->family );
						}

						set_transient( 'google_fonts', $font_set, 60*60*24*7 );
					} catch ( Exception $error ) {
						$font_set = array( 'options' => false, 'raw' => 'error' );
					}
					restore_error_handler();
				}
				break;
			case 'typekit':
			delete_transient( 'typekit_fonts_' . $param1 );
				if ( false === ( $font_set = get_transient( 'typekit_fonts_' . $param1 ) ) ) {

					if ( $param1 ) {
						set_error_handler( 'advanced_type_catch_warnings' );
						require_once( 'php-typekit/typekit-client.php' );
						$typekit = new Typekit();
						try {
							$kit = $typekit->get( $param1 );
							foreach ( $kit[ 'kit' ][ 'families' ] as $font ) {
								$key = implode( ',', $font[ 'css_names' ] ) . ':' . $font[ 'css_stack' ];
								$key = str_replace( '"', '\'', $key);
								$font_set[ 'options' ][ $key ] = $font[ 'name' ];
								$font_set[ 'raw' ][] = array( 'family' => $key, 'name' => $font[ 'name' ] );
							}

							set_transient( 'typekit_fonts_' . $param1 , $font_set, 60*60*24*7 );
						} catch ( Exception $error ) {
							$font_set = array( 'options' => false, 'raw' => 'error' );
						}
						restore_error_handler();
					} else {
						$font_set = array( 'options' => false, 'raw' => false );
					}
				}

				break;
			case 'fontdeck':
				if ( false === ( $font_set = get_transient( 'font_set_' . $param1 ) ) ) {
					if ( $param1 && $param2 ) {
						set_error_handler( 'advanced_type_catch_warnings' );
						try {
							$fontdeck_raw = json_decode( file_get_contents( 'http://f.fontdeck.com/s/css/api/' . $param2 . '/' . $param1 . '.json' ) );
							foreach( $fontdeck_raw->fonts as $font ) {
								$font_set[ 'options' ][ $font->name . ':' . $font->font_family ] = $font->name;
								$font_set[ 'raw' ][] = array( 'family' => $font->name . ':' . $font->font_family, 'name' => $font->name );
							}

							set_transient( 'fontdeck_fonts_' . $param1 , $font_set, 60*60*24*7 );
						} catch ( Exception $error ) {
							$font_set = array( 'options' => false, 'raw' => 'error' );
						}
						restore_error_handler();
					} else {
						$font_set = array( 'options' => false, 'raw' => false );
					}
				}

				break;
		}

		return $font_set;
	}
}

function wptouch_addon_type_content_options( $page_options ) {
	$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );

	wptouch_add_sub_page(
		ADDON_ADVANCED_TYPE_PAGENAME,
		'wptouch-addon-advanced-type',
		$page_options
	);

	if ( !ini_get( 'allow_url_fopen' ) ) {
		wptouch_add_page_section(
			ADDON_ADVANCED_TYPE_PAGENAME,
			__( 'Font Source', 'wptouch-pro' ),
			'addon-type-source',
			array(
				wptouch_add_pro_setting(
					'radiolist',
					'advanced_type_source',
					__( 'Select the source for your web fonts', 'wptouch-pro' ),
					__( 'Other options will become available once allow_url_fopen is enabled', 'wptouch-pro' ),
					WPTOUCH_SETTING_BASIC,
					'1.0',
					array(
						'theme' => __( 'Theme Font Pairings (Configure in Theme Settings)', 'wptouch-pro' ),
					)
				),
			),
			$page_options,
			ADDON_SETTING_DOMAIN
		);
	} else {
		wptouch_add_page_section(
			ADDON_ADVANCED_TYPE_PAGENAME,
			__( 'Font Source', 'wptouch-pro' ),
			'addon-type-source',
			array(
				wptouch_add_pro_setting(
					'radiolist',
					'advanced_type_source',
					__( 'Select the source for your web fonts', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0',
					array(
						'theme' => __( 'Theme Font Pairings (Configure in Theme Settings)', 'wptouch-pro' ),
						'google' => __( 'Custom Google Fonts', 'wptouch-pro' ),
						'typekit' => 'Typekit',
						'fontdeck' => 'Fontdeck',
					)
				),
			),
			$page_options,
			ADDON_SETTING_DOMAIN
		);
		wptouch_add_page_section(
			ADDON_ADVANCED_TYPE_PAGENAME,
			__( 'Kit ID', 'wptouch-pro' ),
			'addon-typekit-id',
			array(
				wptouch_add_pro_setting(
					'text',
					'advanced_type_typekit_kit',
					__( '', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0'
				),
			),
			$page_options,
			ADDON_SETTING_DOMAIN
		);

		wptouch_add_page_section(
			ADDON_ADVANCED_TYPE_PAGENAME,
			__( 'Domain', 'wptouch-pro' ),
			'addon-fontdeck-domain',
			array(
				wptouch_add_pro_setting(
					'text',
					'advanced_type_fontdeck_domain',
					__( '', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0'
				),
			),
			$page_options,
			ADDON_SETTING_DOMAIN
		);

		wptouch_add_page_section(
			ADDON_ADVANCED_TYPE_PAGENAME,
			__( 'Project', 'wptouch-pro' ),
			'addon-fontdeck-project',
			array(
				wptouch_add_pro_setting(
					'text',
					'advanced_type_fontdeck_project',
					__( '', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0'
				),
			),
			$page_options,
			ADDON_SETTING_DOMAIN
		);

		$advanced_type_fonts = array( 'null' => 'Select a font' );

		//
		// Build the font picker options – results are stored as transients for 7 days to reduce API/data loads.
		// Manual refresh/load fonts clears the cache.
		// While Fontdeck & Typekit provide font stack information, Google does not. It does, however, require us
		// to request font weights & subsets on a per-request basis.
		//
		// We build an array of available fonts according the following two patterns:
		//
		// Google:   family name (for CSS):weights to request:available subsets => family name (displayed)
		// FD/TK:    family name:complete font stack (for CSS) => family name (displayed)
		//
		// Fontdeck & Typekit transients are keyed to the kit/project loaded to avoid problems with those being changed.
		//

		// Load Google Fonts

		$google_fonts = advanced_type_get_fonts( 'google' );

		$typekit_fonts = advanced_type_get_fonts( 'typekit', $settings->advanced_type_typekit_kit );

		$fontdeck_fonts = advanced_type_get_fonts( 'fontdeck', $settings->advanced_type_fontdeck_project, $settings->advanced_type_fontdeck_domain );

		switch ( $settings->advanced_type_source ) {
			case 'google':
				$advanced_type_fonts = $google_fonts[ 'options' ];
				break;

			case 'typekit':
				$advanced_type_fonts = $typekit_fonts[ 'options' ];
				break;

			case 'fontdeck':
				$advanced_type_fonts = $fontdeck_fonts[ 'options' ];
				break;
		}

		// Build an array of known available font subsets that Google offers.
		$subsets = array(
			'latin' => __( 'Latin (Default)', 'wptouch-pro' ),
			'latin-ext' => __( 'Latin Extended', 'wptouch-pro' ),
			'cyrillic' => __( 'Cyrillic', 'wptouch-pro' ),
			'cyrillic-ext' => __( 'Cyrillic Extended', 'wptouch-pro' ),
			'greek' => __( 'Greek', 'wptouch-pro' ),
			'greek-ext' => __( 'Greek Extended', 'wptouch-pro' ),
			'devanagari' => __( 'Devanagari', 'wptouch-pro' ),
			'vietnamese' => __( 'Vietnamese', 'wptouch-pro' ),
		);

		// Seed translations for known strings (font locations)
		__( 'Heading', 'wptouch-pro' );
		__( 'Body', 'wptouch-pro' );
		__( 'Meta', 'wptouch-pro' );

		// Get the pairings registered by the active mobile theme.
		// We'll use this to detect what selectors the theme expects fonts to be loaded for
		// A font selector is built for each. If we're using Google, also present a list
		// of subsets to choose from. These will be filtered by Javascript.
		if ( function_exists( 'foundation_get_google_font_pairings' ) ) {
			$theme_pairings = foundation_get_google_font_pairings();
			$one_pairing = array_shift( $theme_pairings );

			$theme_font_options = array();
			foreach ( $one_pairing as $legacy_font_option ) {
				$theme_font_options[] = wptouch_add_pro_setting(
					'list',
					'advanced_type_fonts[' . $legacy_font_option->selector . ']',
					__( ucwords( $legacy_font_option->selector ), 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0',
					$advanced_type_fonts
				);

				$theme_font_options[] = wptouch_add_pro_setting(
					'checklist',
					'advanced_type_font_subsets[' . $legacy_font_option->selector . ']',
					__( 'Load subsets', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_ADVANCED,
					'1.0',
					$subsets
				);
			}

			wptouch_add_page_section(
				ADDON_ADVANCED_TYPE_PAGENAME,
				__( 'Font Selection', 'wptouch-pro' ),
				'addon-font-picker-settings',
				$theme_font_options,
				$page_options,
				ADDON_SETTING_DOMAIN
			);
		}
	}

	return $page_options;
}

function wptouch_addon_type_init_mobile() {
	$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );

	if ( $settings->advanced_type_source != 'theme' ) {
		// Enqueue, configure, and load the core font loader
		wp_enqueue_script(
			'google-font-loader',
			'//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js'
		);

		$fonts = array();
		$set_id = null;

		switch ( $settings->advanced_type_source ) {
			case 'google':
				// Build appropriate font loading statements for each of the chosen typefaces
				foreach ( $settings->advanced_type_fonts as $selector => $font ) {
					preg_match( '/(.*):(.*):/', $font, $font );

					if ( substr( $font[ 2 ], 0, 1 ) == ',' ) {
						$font[ 2 ] = substr( $font[ 2 ], 1 );
					}

					if ( isset( $settings->advanced_type_font_subsets[ $selector ] ) ) {
						$subsets = ':' . implode( ',', $settings->advanced_type_font_subsets[ $selector ]);
					} else {
						$subsets = '';
					}

					$fonts[] = $font[ 1 ] . ':' . $font[ 2 ] . $subsets;
				}
				break;

			// Customization for both Typekit and Fontdeck occur remotely. Load by ID only.
			case 'typekit':
				$set_id = $settings->advanced_type_typekit_kit;
				break;

			case 'fontdeck':
				$set_id = $settings->advanced_type_fontdeck_project;
				break;
		}

		// Using wp_localize_script to pass these variables through to our loading script.
		$data_array = array(
			'font_source' => $settings->advanced_type_source,
			'fonts' => $fonts,
			'set_id' => $set_id
		);

		wp_enqueue_script( 'wptouch-pro-font-loader', WPTOUCH_BASE_CONTENT_URL . '/extensions/advanced-type/advanced-type.js' );
		wp_localize_script( 'wptouch-pro-font-loader', 'FontData', $data_array );

		// Build in-line selectors – apply the selected typefaces to the appropriate elements.
		$inline_style_data = '';
		foreach( $settings->advanced_type_fonts as $selector => $font ) {
			if ( $settings->advanced_type_source == 'google' ) {
				preg_match( '/(.*):(.*):(.*)/', $font, $font );
				$font = $font[ 1 ];
			} else {
				preg_match( '/(.*):(.*)/', $font, $font );
				$font = $font[ 2 ];
			}

			if ( !strstr( $font, "'" ) && strstr( $font, ' ' ) ) {
				$font = "'" . $font . "'";
			}

			$inline_style_data .= "." . $selector . "-font" . " {\n";

			$inline_style_data .= "\tfont-family: " . $font . ";\n";
			$inline_style_data .= "}\n";
		}

		if ( $inline_style_data ) {
			// Tie into the parent theme CSS load to allow us to output inline styles.
			wp_add_inline_style( 'wptouch-parent-theme-css', $inline_style_data );
		}
	}
}
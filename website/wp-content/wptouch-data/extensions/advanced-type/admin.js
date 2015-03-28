var typekit_settings = jQuery( '#section-addon-typekit-id' );
var fontdeck_settings = jQuery( '#section-addon-fontdeck-project,#section-addon-fontdeck-domain' );
var font_picker = jQuery( '#section-addon-font-picker-settings' );
var subset_pickers = jQuery( '[id^="setting-advanced_type_font_subsets"]', font_picker )
var load_button;
var load_error;

var advanced_type_google_fonts = type_data.google_fonts;
var advanced_type_typekit_fonts = type_data.typekit_fonts;
var advanced_type_fontdeck_fonts = type_data.fontdeck_fonts;

var activeFontSource;
var activeFonts = JSON.parse( type_data.active_fonts );

function doAdvancedTypeAdminReady() {
	var fontSources = jQuery( '#setting-advanced_type_source input' );
	activeFontSource = jQuery( '#setting-advanced_type_source input:checked' ).val();

	if ( fontSources.is( 'input' ) ) {
		jQuery( '#preview, #reset' ).remove();
	}

	// Inject a notice that is shown when changing font source - we need a save in order to reload the font picker.
	font_picker.before( '<p id="reload_fonts"><a class="button-primary" href="#">' + type_data.load_fonts_message + '</a></p>' ).hide();
	load_button = jQuery( '#reload_fonts' );
	jQuery( load_button ).before( '<p id="font_load_error" style="display: none">' + type_data.load_fonts_error + '</p>' ).hide();
	load_error = jQuery( '#font_load_error' );

	configureAdmin( activeFontSource );

	fontSources.on( 'click', function( e ) {
		activeFontSource = jQuery( this ).val();
		configureAdmin( activeFontSource );
	});

	jQuery( '#wptouch-settings-content' ).on( 'keyup', '#advanced_type_typekit_kit, #advanced_type_fontdeck_domain, #advanced_type_fontdeck_project', function() {
		load_button.show();
		load_error.fadeOut();
		font_picker.hide();
	});

	jQuery( load_button ).on( 'click', 'a', function( e ) {
		e.preventDefault();

		switch( activeFontSource ) {
			case 'typekit':
				ajaxParams = {
					'source': 'typekit',
					'kit': jQuery( '#advanced_type_typekit_kit' ).val()
				};
				break;
			case 'fontdeck':
				ajaxParams = {
					'source': 'fontdeck',
					'project': jQuery( '#advanced_type_fontdeck_project' ).val(),
					'domain': jQuery( '#advanced_type_fontdeck_domain' ).val()
				};
				break;
		}

		wptouchAdminAjax( 'reload_fonts', ajaxParams, function( result ) {
			if ( JSON.parse( result ) !== 'error' ) {
				window[ 'advanced_type_' + activeFontSource + '_fonts' ] = result;
				configureAdmin( activeFontSource );
			} else {
				load_error.fadeIn();
			}
		});
	});
}

function isActiveFont( family, location ) {
	family = family.split( '"' );
	if ( family[ 0 ] == activeFonts[ location ] ) {
		return ' selected';
	}
}

function configureAdmin( selectedSource ) {
	// Show/hide fields as necessary to streamline the interface for the selected font source.
	// If you change the selector back to the one that was live at page load, though, font picker is restored.
	subset_pickers.hide();
	if ( selectedSource != 'theme' ) {
		jQuery( 'select', font_picker ).each( function() {
	    	jQuery( this ).children().remove();

	    	thisLocation = jQuery( this ).attr( 'id' ).toString().match( /\[(.*)\]/ );

	    	if ( window[ 'advanced_type_' + selectedSource + '_fonts' ] != '' ) {
		    	jQuery( this ).append( jQuery.templates( '#fontListTemplate' ).render( JSON.parse( window[ 'advanced_type_' + selectedSource + '_fonts' ] ), { location: thisLocation[ 1 ], selected: isActiveFont }) );
				font_picker.show();
				load_button.hide();
		    } else {
    			font_picker.hide();
		    }
		});

		if ( selectedSource == 'typekit' ) {
			typekit_settings.show();
			console.log( advanced_type_typekit_fonts );
			if ( typeof advanced_type_typekit_fonts == 'string' && advanced_type_typekit_fonts.length == 5 ) {
				font_picker.hide();
			}
		} else {
			typekit_settings.hide();
		}

		if ( selectedSource == 'fontdeck' ) {
			fontdeck_settings.show();
			if ( typeof advanced_type_fontdeck_fonts == 'string' && advanced_type_fontdeck_fonts.length == 5 ) {
				font_picker.hide();
			}
		} else {
			fontdeck_settings.hide();
		}

		if ( selectedSource == 'google' ) {
			subset_pickers.show();

			jQuery( 'select', font_picker ).each( function() {
				updateSubsets( this );
			}).on( 'change', function() {
				updateSubsets( this );
			});
		}
	} else {
		typekit_settings.hide();
		fontdeck_settings.hide();
		font_picker.hide();
		load_button.hide();
	}
}

function updateSubsets( fontSelector ) {
	// When you choose a Google font, filter the list of subsets to show only those available for the selected font.
	selector = jQuery( fontSelector ).attr( 'id' ).split( '[' );
	myCheckBoxes = jQuery( 'input[name="wptouch__addons__advanced_type_font_subsets[' + selector[ 1 ] + '[]' );
	myCheckBoxes.prop( 'disabled', true).parent().hide();

	checkboxParentLi = myCheckBoxes.parent().parent().parent();

	current_font = jQuery( fontSelector ).val();

	if ( current_font ) {
		if ( current_font.indexOf( ':' ) > -1 ) {
			font_elements = current_font.split( ':' );
			font_subsets = font_elements[ 2 ].split( ',' );
		} else {
			font_subset = false;
		}

		for (var i = 1; i < font_subsets.length; i++) {
			jQuery( 'input[value="' + font_subsets[ i ] + '"]', checkboxParentLi ).prop( 'disabled', false ).parent().show();
		}
		jQuery( 'input:disabled', checkboxParentLi ).prop( 'checked', false );
	}
}

jQuery( document ).ready( function() { doAdvancedTypeAdminReady(); });
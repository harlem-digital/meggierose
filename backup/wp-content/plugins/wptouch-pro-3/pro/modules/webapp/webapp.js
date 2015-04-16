/* WPtouch Foundation Web-App Mode Module JS */

function _wptouchWebAppSetupEachLink( oneLink ) {
	var targetURL = oneLink.attr( 'href' );
	var targetLink = oneLink;

	var localDomain = '//' + location.hostname;
	var rootDomain = location.hostname.split( '.' );
	var masterDomain = rootDomain[1] + '.' + rootDomain[2];

	// Check for URLs that should break out of Web-App mode
	if ( typeof wptouchWebApp.ignoredWebAppURLs != 'undefined' ) {
		jQuery.each( wptouchWebApp.ignoredWebAppURLs, function( i, val ) {
			if ( targetURL.match( val ) ) {
				targetLink.addClass( 'ignored' );
			}
		});
	}

	// Check for URLs that should break out of Web-App mode
	if ( typeof wptouchWebApp.ignoredURLs != 'undefined' ) {
		jQuery.each( wptouchWebApp.ignoredURLs, function( i, val ) {
			if ( targetURL.match( val ) ) {
				targetLink.addClass( 'ignored' );
			}
		});
	}

	// Look for files that should definitely open in another browser, and also images
	if ( targetURL.match( ( /[^\s]+(\.(pdf|numbers|pages|xls|xlsx|doc|docx|zip|tar|gz|csv|txt))$/i ) ) ) {
		targetLink.addClass( 'file' );
	} else if ( targetURL.match( ( /[^\s]+(\.(mp3|mp4|m4a|mov|m4v|wav|aiff|ogg))$/i ) ) ) {
		targetLink.addClass( 'media' );
	} else if ( targetURL.match( ( /[^\s]+(\.(jpg|jpeg|gif|png|bmp|tiff|svg))$/i ) ) ) {
		targetLink.addClass( 'img-link' );
	}

	// Click handler for all Web-App mode links without hash targets
	var thisTargetUrl = oneLink.attr( 'href' ).split('#')[0];
	var thisTargetAnchor = oneLink.attr( 'href' ).split('#')[1];

	if ( oneLink.hasClass( 'external' ) || oneLink.parent( 'li' ).hasClass( 'external' ) ) {
		return confirm( wptouchWebApp.externalLinkText );
	} else if ( oneLink.hasClass( 'file' ) ) {
		return confirm( wptouchWebApp.externalFileText );
	} else if ( oneLink.hasClass( 'media' ) ) {
		window.location = thisTargetUrl;
		return false;
	} else if ( oneLink.hasClass( 'img-link' ) ) {
		return false;
	} else if ( thisTargetUrl.match( localDomain ) || ( !thisTargetUrl.match( 'http://' )  && !thisTargetUrl.match( 'https://' ) ) ) {
		// This is a local HTTP address, or HTTP is missing from the URL
		// First, let's make sure it's not in the ignored list first
		if ( oneLink.hasClass( 'ignored' ) || oneLink.parent( 'li' ).hasClass( 'ignored' ) ) {
			// This is *not* a local link or one that has been ignored, so let's ask what to do
	       	return confirm( wptouchWebApp.externalLinkText );
		} else if ( thisTargetAnchor && thisTargetUrl.match( '//' ) ) {
			wptouchWebAppLoadPage( thisTargetUrl );
			return false;
		} else if ( thisTargetAnchor && thisTargetAnchor == 'respond' && !thisTargetUrl.match( '//' ) ) {
			wptouchWebAppLoadPage( targetLink.attr( 'href' ) );
			return false;
		} else if ( thisTargetAnchor && !thisTargetUrl.match( '//' ) ) {
			return true;
		} else if ( oneLink.attr( 'href' ) == '#' ) {
			// Allow local JS to execute
			return true;
		} else {
			// This is a local link that hasn't been ignored, so let's move to it
			wptouchWebAppLoadPage( thisTargetUrl );
			return false;
		}
	} else {
		return confirm( wptouchWebApp.externalLinkText );
	}
}

function _wptouchWebAppSetupLinkTrapping( webAppLinks ) {
	webAppLinks.each( function() {
		jQuery( this ).off( 'click.weblinks' ).on( 'click.weblinks', function() {
			var thisElement = jQuery( this );
			var result = _wptouchWebAppSetupEachLink( thisElement );
			return result;
		});
	});
}

function wptouchWebAppSetupLinkTrapping() {
	// Need to update this list
	var webAppLinks = jQuery( 'a' ).not( 'ul.no-parent-links li.has_children > a, .no-ajax, .back-button, .fwd-button, .back-to-top, .comment-reply-link, .slide-toggle, .show-hide-toggle, [href^=mailto], [href^=tel]' );
	_wptouchWebAppSetupLinkTrapping( webAppLinks );
}

function wptouchWebAppLoadPage( targetUrl ) {
	setTimeout( function() {
		wptouchWebAppSaveState( targetUrl );
		window.location = targetUrl;
	}, 250 );
}

// Main entry point for webapp mode
jQuery( document ).ready( function() {
	if ( navigator.standalone ) {
		wptouchWebAppOnly();
		wptouchWebAppSetupLinkTrapping();
		wptouchWebAppHandleShortcodes();
	}
}).ajaxComplete( function() {
	if ( navigator.standalone ) {
		wptouchWebAppSetupLinkTrapping();
		wptouchWebAppHandleShortcodes();

		// Erase the cookie
		wptouchCreateCookie( 'wptouch-webapp-' + wptouchWebApp.persistenceSalt, '', -1 );
	}
});

// Things to do only when in Web-App Mode
function wptouchWebAppOnly() {
	wptouchCreateCookie( 'wptouch-webapp-' + wptouchWebApp.persistenceSalt, 'on' );
	if ( wptouchWebApp.persistence == 0 ) {
		wptouchEraseCookie( 'wptouch-webapp-persist-' + wptouchWebApp.persistenceSalt );
	}
	jQuery( 'body' ).addClass( 'web-app-mode' );

	wptouchWebAppBackButton();

    if( window.location.hash ) { // just in case there is no hash
    	jQuery( document.body ).animate( {
            'scrollTop': jQuery( window.location.hash ).offset().top
        }, 500);
    }


	jQuery( '#switch' ).remove();
}

function wptouchWebAppBackButton(){
	if ( jQuery( '.back-button' ).length ) {
		var startPosition = 0;
		var backButton = jQuery( '.back-button' );

		jQuery( window ).scroll( function () {
			var newPosition = jQuery( this ).scrollTop();
			if ( newPosition > startPosition ) {
				backButton.removeClass( 'visible' );
			} else {
				if ( !backButton.hasClass( 'visible' ) ) {
					backButton.addClass( 'visible' );
				}
			}
			startPosition = newPosition;
		});

		// New back button history handling
		if ( history.length == '1' || document.referrer == '' ) {
			backButton.hide();
		}
	}
}

function wptouchWebAppSaveState( url ) {
	if ( wptouchWebApp.persistence == 1 && !url.match( 'action=logout' ) ) {
		wptouchCreateCookie( 'wptouch-webapp-persist-' + wptouchWebApp.persistenceSalt, url, 365 );
	}
}

function wptouchWebAppHandleShortcodes() {
	// For web application mode
	var webAppDivs = jQuery( '.wptouch-shortcode-webapp-only' );
	webAppDivs.show();
}
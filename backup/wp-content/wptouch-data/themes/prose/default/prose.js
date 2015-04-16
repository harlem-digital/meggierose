/* WPtouch Prose Theme Js File */

function doProseReady() {
	proseElManip();
	proseHandleFontSize();
	proseHandleTabMenu();
	doProseRelatedLoaded();
	proseMoveFooterDiv();
	proseWebApp();
	proseBindTappableLinks();
	proseSetupProgress();
	jQuery( 'body' ).pushIt( { menuWidth: 260 } );
}

function proseElManip(){
	jQuery( '.post-meta, .post-author, .post-date, #slider small, .load-more-link, .footer, .back-to-top, .wptouch-mobile-switch, .post-password-form *, #menu, #site-intro' ).addClass( 'meta-font' );
	jQuery( 'body, #menu .options p' ).addClass( 'body-font' );
	jQuery( 'body.archive .load-more-link').text( ' ' + translated_strings.load_more_label );
	jQuery( '.fonts-podkova_badscript .progress-indicator, .fonts-podkova_badscript .pagetitle span' ).removeClass( 'meta-font' );

	// Reposition header image on non-post pages & set height.
	if ( jQuery( 'body.single div.header-image' ).is( 'div' ) ) {
		jQuery( 'body' ).addClass( 'has-header-image' );
	} else if ( jQuery( 'div.header-image' ).is( 'div' ) ) {
		jQuery( 'div.header-image' ).prependTo( '.post-head-area' );
		jQuery( 'body' ).addClass( 'has-header-image' );
	}

	if ( !jQuery( 'body' ).hasClass( 'wptouch-multiads' ) ) {
		adToMove = jQuery( '.header-ad  .page-wrapper > .wptouch-ad, .header-ad .page-wrapper > .wptouch-custom-ad' );
	} else {
		adToMove = jQuery( '.header-ad .ad-location-header' );
	}
	adToMove.prependTo('.page-wrapper').addClass( 'ad-location-header' );
	topOffset = ( jQuery('#header-area' ).height() + 37 ) * -1;
	jQuery( '.header-ad.has-header-image #content .post-head-area' ).css( 'margin-top', topOffset );
}

function proseHandleFontSize(){
	jQuery( '#font-size' ).on( 'click', 'a', function( e ) {

		e.preventDefault();

		oldsize = wptouchReadCookie( 'prose-font-size' );
		if ( !oldsize ) {
			oldsize = 'medium';
		}

		newsize = jQuery( this ).attr( 'data-font-size' );

		parent = jQuery( this ).parent();

		if ( !parent.hasClass( 'active' ) ) {
			wptouchCreateCookie( 'prose-font-size', newsize, 365 );

			jQuery( '#font-size .active' ).removeClass( 'active' );
			parent.addClass( 'active' );

			if ( oldsize != 'medium' ) {
				oldclass = 'font-size-' + oldsize;
				jQuery( 'body' ).removeClass( oldclass );
			}

			if ( newsize != 'medium' ) {
				newclass = 'font-size-' + newsize;
				jQuery( 'body' ).addClass( newclass );
			}
		}

		proseSetSizes();
	});
}

function proseWebApp(){
	if ( navigator.standalone ) {
		jQuery( window ).resize( function() {
			var windowHeight = ( jQuery( window ).height() - 28 );
			if ( jQuery( 'body.web-app-mode.ios7.smartphone.portrait' ).length ) {
				jQuery( '.wptouch-menu' ).css( 'max-height', windowHeight );
			}
			if ( jQuery( 'body.web-app-mode.ios7.smartphone.landscape' ).length ) {
				jQuery( '.wtouch-menu' ).css( 'max-height', windowHeight );
			}
		}).resize();

		if ( jQuery( 'body.web-app-mode.ios7' ).length ) {
			jQuery( 'body' ).prepend( '<span class="fixed-header-fill"></span>' );

			/* No touchmove, please */
			jQuery( '#header-title-logo' ).each( function(){
				jQuery( this ).on( 'touchmove', function( e ){ e.preventDefault(); } );
			});
		}
	}
}

// Add 'touched' class to these elements when they're actually touched (100ms delay) for a better UI experience (tappable module)
function proseBindTappableLinks(){
	// Drop down menu items
	jQuery( 'li.menu-item' ).each( function(){
		jQuery( this ).addClass( 'tappable' );
	});
}

// Move the footer below the switch
function proseMoveFooterDiv(){
	if ( jQuery( '#switch' ).length ) {
		var footerDiv = jQuery( '.footer' );
		jQuery( '#switch' ).after( footerDiv );
	}
}

function proseHandleTabMenu() {
	if ( jQuery( 'ul.tab-menu' ).length ) {
		jQuery( 'ul.tab-menu' ).on( 'click', 'a', function( e ) {
			jQuery( 'ul.tab-menu li a' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );
			jQuery( '.tab-section' ).hide();
			var sectionName = ( '.' + jQuery( this ).attr( 'data-section' ) );
			jQuery( sectionName ).fadeIn();

			e.preventDefault();
		});

		jQuery( 'ul.tab-menu li' ).find( 'a' ).first().click();
	} else {
		jQuery( '.wptouch-menu' ).css( 'display', 'block' );
	}
}

function doProseRelatedLoaded() {
	proseCreateDots();

	var slideNumber = 0;
	var isContinuous = false;

	var slideOption = '0';
	if ( jQuery( '#slider' ).hasClass( 'slide' ) ) {
		slideOption = '4000';
		if ( jQuery( '#slider' ).hasClass( 'slow' ) ) {
			slideOption = '6000';
		} else if ( jQuery( '#slider').hasClass( 'fast' ) ) {
			slideOption = '2500';
		}
	}

	if ( jQuery( '#slider' ).hasClass( 'continuous' ) ) {
		isContinuous = true;
	}

	var bullets = jQuery( '.dots' ).find( 'li' );

	var sliderOptions = {
		startSlide: slideNumber,
		continuous: isContinuous,
		callback: function( pos ) {
			var i = bullets.length;
			while (i--) {
				bullets[i].className = ' ';
			}
			bullets[pos].className = 'active';
		}
	}

	// only include this parameter if it's non-zero
	if ( slideOption > 0  && !jQuery( 'body' ).hasClass( 'rtl' ) ) {
		sliderOptions.auto = slideOption;
	}

	jQuery( '.one-swipe-image' ).css( 'visibility', 'visible' );

	max_height = 0;
	jQuery( '.one-swipe-image' ).each( function() {
		if ( jQuery( this ).height() > max_height ) {
			max_height = jQuery( this ).height();
		}
	});
	max_height += 25; // Pad it
	jQuery( '.one-swipe-image' ).css( 'height', max_height + 'px' );

	var featuredSlider = new Swipe( document.getElementById( 'slider' ), sliderOptions );
}

function proseCreateDots() {

	var sliderEl = jQuery( '#slider' );
	var images = sliderEl.find( 'a' );
	var slideNumber = 0;

	// Create dots
	var dots = '<ul class="dots">';

	for ( i = 0; i < images.length; i++ ) {
		dots = dots + '<li data-pos="'+i+'">&nbsp;</li>';
	}

	dots = dots + '</ul>';

	sliderEl.before( dots );

	jQuery( '.dots' ).find( 'li[data-pos="'+slideNumber+'"]' ).addClass( 'active' );

	if ( jQuery( '.dots li' ).length <= 1 ) {
		jQuery( '.dots' ).hide();
	}
}


var wh, h, sHeight;

function proseSetSizes(){
	wh = jQuery( window ).height();
	h = jQuery( '.post' ).height();
	sHeight = h - wh;
}

function proseSetupProgress() {
	if ( jQuery( 'body' ).hasClass( 'single' ) || jQuery( 'body' ).hasClass( 'page' ) ) {
		var circ = jQuery( '.animated-circle' );
		var progCount = jQuery( '.progress-count' );
		var indicator = jQuery( '.progress-indicator' );

		var thisWindow = jQuery( window );

		var wordsPerMin = 300; // based on this article: http://www.forbes.com/sites/brettnelson/2012/06/04/do-you-read-fast-enough-to-be-successful/
		var wordsArray = jQuery( '#content' ).text().split( ' ' );
		var wordCount = wordsArray.length;
		var minCount = Math.round( wordCount / wordsPerMin );

		if ( jQuery( 'body' ) .hasClass( 'page' ) ) {
			minCount = 1;
			indicator.addClass( 'open' );
		}

		if ( minCount > 1 ) {
			reading_time =  sprintf( translated_strings.reading_time_minutes, minCount );
		} else {
			reading_time = sprintf( translated_strings.reading_time_minute, 1 );
		}

		jQuery( '.reading-time' ).text( reading_time );


		var sharingTools = false;
		if ( jQuery( '.sharing-options' ).is( 'div' ) ) {
			sharingTools = jQuery( '.sharing-options' ).clone();
			jQuery( '.sharing-options' ).remove();
		}

		function proseUpdateProgress( perc ){
			var minutesCompleted = Math.round( perc * minCount );
			var remaining = minCount - minutesCompleted;
			if ( remaining ) {
				if ( perc > .4 && remaining < minCount ) {
					if ( remaining > 1 ) {
						message = sprintf( translated_strings.remaining_minutes, remaining );
					} else {
						message = sprintf( translated_strings.remaining_minute, remaining );
					}

					indicator.fadeIn().removeClass( 'share' ).html( message );
				} else {
					if ( indicator.is( ':visible' ) ) {
						indicator.fadeOut();
					} else {
						indicator.hide();
					}
				}
			} else {
				if ( sharingTools ) {
					indicator.fadeIn().addClass( 'share' ).html( '<a href="#">' + translated_strings.share_post + '</a>' ).append( sharingTools );
					jQuery( '.progress-indicator > a' ).click( function( e ) {
						e.preventDefault();
						jQuery( this ).parent().addClass( 'open' );
					 	jQuery( '.smartphone.web-app-mode .back-button' ).animate( { 'bottom': '105px' }, 250 );
					});
				} else {
					indicator.hide();
				}
			}
		}

		proseSetSizes();

		thisWindow.on( 'scroll', function(){
			var perc = Math.max( 0, Math.min( 1, thisWindow.scrollTop()/sHeight ) );
			proseUpdateProgress( perc );
		}).on( 'resize', function(){
			proseSetSizes();
			thisWindow.trigger( 'scroll' );
		});
	}
}

jQuery( document ).ready( function() {
	doProseReady();
} );

jQuery( document ).ajaxComplete( function() {
	proseElManip();
});
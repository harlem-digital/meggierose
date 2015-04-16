/* admin stuff for Prose */

function proseAdminReady() {

	// Hide the settings section about Comments on Pages
	jQuery( '#setting-share_location' ).hide();
	if ( jQuery( '#wptouch__foundation__latest_posts_page' ).is( 'select' ) ) {
		jQuery( '#setting-show_comments_on_pages' ) .hide();
	} else {
		jQuery( '#section-foundation-pages' ).hide();
	}
}

jQuery( document ).ready( function() {
	proseAdminReady();
});

if ( wptouchFdnAddToHome.bubbleExpiryInDays == 0 ) {
	localStorage.removeItem( 'org.cubiq.addtohome' );
}

var addToHomeConfig = {
	message: wptouchFdnAddToHome.bubbleMessage,				// The message shown (configured in admin)
	displayPace: wptouchFdnAddToHome.bubbleExpiryInDays,	// Days to wait before showing the popup again (0 = always displayed)
	autostart: true,										// Automatically open the balloon
	skipFirstVisit: false,									// Show the balloon to returning visitors only (setting this to true is HIGHLY RECCOMENDED)
	startDelay: 1,											// Display the message after that many seconds from page load
	lifespan: 1200,//60,											// 60 seconds before it is automatically destroyed
	icon: true												// Display the homescreen icon
};

addToHomescreen( addToHomeConfig );
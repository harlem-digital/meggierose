// Instantiate an empty object so that we can use a variable as a key further on.
settings = {}

if ( FontData.font_source == 'google' ) {
	settings[ FontData.font_source ] = {
		families: FontData.fonts
	}
} else {
	settings[ FontData.font_source ] = {
		id: FontData.set_id
	}
}

WebFont.load( settings );
$( function () {
	let sex_radio  = $( 'div[data-sex]', '.sex-radio' );
	let users_item = $( 'div[data-sex]', '.users' );
	$( "input:radio", '.choose-sex' ).on( 'click', function () {
		let sex = $( this ).val();
		for ( let i = 0; i < sex_radio.length; i++ ) {
			let data_sex = $( 'div', '.sex-radio' )[ i ].getAttribute( 'data-sex' );
			if ( sex === data_sex ) {
				sex_radio[ i ].setAttribute( 'style', 'display: visible' );
				users_item[ i ].setAttribute( 'style', 'display: visible' );
			} else if (sex == 0) {
				sex_radio[ i ].setAttribute( 'style', 'display: visible' );
				users_item[ i ].setAttribute( 'style', 'display: visible' );
			} else {
				sex_radio[ i ].setAttribute( 'style', 'display: none' );
				users_item[ i ].setAttribute( 'style', 'display: none' );
			}
		}
	} );
} );
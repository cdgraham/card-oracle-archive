(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( document ).ready( function() {
		let clickedButtons = new Array();
		let count = 0;
		let positions = $( "div.data" ).data( "positions" );
	
		console.log( 'Number of card positions is ' + positions );

		$( ".btn-block" ).hide();
		$( "#Submit" ).prop( "disabled", true );
	
		$( 'button.clicked' ).click( function() {
			count++;
	
			if ( count <= positions ) {
				$( this ).css( 'opacity', '0.75' );
					clickedButtons.push( this.value );
				$( "#picks" ).val( clickedButtons.join() );
	
				if ( count == positions ) {
					$( ".btn-block" ).show();
					$( "#Submit" ).prop("disabled", false);
					$( 'html, body' ).animate( {
						scrollTop: ( $( "h1" ).offset().top )
					}, 500 );
			   }
			}
		  } )
	} )

})( jQuery );
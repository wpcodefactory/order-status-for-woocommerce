/**
 * wfwp-wcos-admin.js.
 *
 * @version 1.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( document ).ready( function () {
	/**
	 * Make title required.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	jQuery( '[id^="titlediv"]' ).find( '#title' ).prop( 'required', true );
} );

jQuery( '#publish, #save-post' ).click( function () {
	/**
	 * Validate title.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	var value = jQuery( '[id^="titlediv"]' ).find( '#title' ).val();
	if ( value.length < 1 ) {
		return false;
	} else if ( jQuery.isNumeric( value.charAt( 0 ) ) ) {
		alert( wfwp_wcos_admin_object.start_with_number_error_message );
		return false;
	}
} );

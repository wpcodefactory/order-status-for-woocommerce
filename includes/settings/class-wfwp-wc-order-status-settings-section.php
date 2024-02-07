<?php
/**
 * Order Status for WooCommerce - Section Settings
 *
 * @version 1.4.6
 * @since   1.4.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Order_Status_Settings_Section' ) ) :

class WFWP_WC_Order_Status_Settings_Section {

	/**
	 * id.
	 *
	 * @version 1.4.6
	 * @since   1.4.6
	 */
	public $id;

	/**
	 * desc.
	 *
	 * @version 1.4.6
	 * @since   1.4.6
	 */
	public $desc;

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function __construct() {
		add_filter( 'woocommerce_get_sections_wfwp_wc_order_status', array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_wfwp_wc_order_status_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

}

endif;

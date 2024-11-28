<?php
/**
 * Order Status for WooCommerce - Settings
 *
 * @version 1.7.0
 * @since   1.4.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Order_Status_Settings' ) ) :

class WFWP_WC_Order_Status_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function __construct() {

		$this->id    = 'wfwp_wc_order_status';
		$this->label = __( 'Order Status', 'order-status-for-woocommerce' );
		parent::__construct();

		// Sections
		require_once( 'class-wfwp-wc-order-status-settings-section.php' );
		require_once( 'class-wfwp-wc-order-status-settings-general.php' );

	}

	/**
	 * get_settings.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'order-status-for-woocommerce' ),
				'type'      => 'title',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'order-status-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Reset', 'order-status-for-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Check the box and save changes to reset.', 'order-status-for-woocommerce' ),
				'id'        => $this->id . '_' . $current_section . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notices_settings_reset_success' ), PHP_INT_MAX );
		}
	}

	/**
	 * admin_notices_settings_reset_success.
	 *
	 * @version 1.7.0
	 * @since   1.4.0
	 */
	function admin_notices_settings_reset_success() {
		echo '<div class="notice notice-success is-dismissible"><p><strong>' .
			esc_html__( 'Your settings have been reset.', 'order-status-for-woocommerce' ) .
		'</strong></p></div>';
	}

	/**
	 * save.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function save() {
		global $current_section;
		parent::save();
		$this->maybe_reset_settings();
		do_action( 'wfwp_wc_order_status_settings_saved', $current_section );
	}

}

endif;

return new WFWP_WC_Order_Status_Settings();

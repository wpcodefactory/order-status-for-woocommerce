<?php
/**
 * Order Status for WooCommerce - General Section Settings
 *
 * @version 1.9.1
 * @since   1.4.0
 *
 * @author  Algoritmika Ltd
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Order_Status_Settings_General' ) ) :

class WFWP_WC_Order_Status_Settings_General extends WFWP_WC_Order_Status_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'order-status-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_order_statuses.
	 *
	 * @version 1.9.1
	 * @since   1.9.1
	 */
	function get_order_statuses() {
		$statuses = array();
		foreach ( wc_get_order_statuses() as $key => $status ) {
			if ( strlen( $key ) >= 3 && 'wc-' === substr( $key, 0, 3 ) ) {
				$key = substr( $key, 3 );
			}
			$statuses[ $key ] = $status;
		}
		return $statuses;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.9.1
	 * @since   1.4.0
	 *
	 * @todo    (v1.9.1) Admin Order Actions: better section desc?
	 */
	function get_settings() {

		// General
		$settings = array(
			array(
				'title'    => __( 'Order Status Options', 'order-status-for-woocommerce' ),
				'desc'     => (
					'<span class="dashicons dashicons-info"></span> ' .
					sprintf(
						/* Translators: %s: Menu title. */
						__( 'Create & edit order statuses in the %s menu.', 'order-status-for-woocommerce' ),
						'<a href="' . admin_url( 'edit.php?post_type=wfwp_wc_order_status' ) . '">' .
							__( 'Order Status', 'order-status-for-woocommerce' ) .
						'</a>'
					)
				),
				'type'     => 'title',
				'id'       => 'wfwp_wc_order_status_plugin_options',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wfwp_wc_order_status_plugin_options',
			),
		);

		// Admin Order Actions
		$settings = array_merge( $settings, array(
			array(
				'title'    => __( 'Admin Order List Actions Buttons', 'order-status-for-woocommerce' ),
				'desc'     => __( 'By default, the "Processing" action is displayed only for pending and on-hold orders; "Complete" only for pending, processing and on-hold orders. You can override it here.', 'order-status-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'wfwp_wc_order_status_admin_order_actions_options',
			),
			array(
				'title'    => __( 'Processing', 'order-status-for-woocommerce' ),
				'desc'     => __( 'Override', 'order-status-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'wfwp_wc_order_status_admin_order_actions_processing_override',
				'default'  => 'no',
			),
			array(
				'desc'     => __( 'Order statuses', 'order-status-for-woocommerce' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'wfwp_wc_order_status_admin_order_actions_processing_has_status',
				'default'  => array( 'pending', 'on-hold' ),
				'options'  => $this->get_order_statuses(),
			),
			array(
				'title'    => __( 'Complete', 'order-status-for-woocommerce' ),
				'desc'     => __( 'Override', 'order-status-for-woocommerce' ),
				'type'     => 'checkbox',
				'id'       => 'wfwp_wc_order_status_admin_order_actions_complete_override',
				'default'  => 'no',
			),
			array(
				'desc'     => __( 'Order statuses', 'order-status-for-woocommerce' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'wfwp_wc_order_status_admin_order_actions_complete_has_status',
				'default'  => array( 'pending', 'on-hold', 'processing' ),
				'options'  => $this->get_order_statuses(),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wfwp_wc_order_status_admin_order_actions_options',
			),
		) );

		return $settings;

	}

}

endif;

return new WFWP_WC_Order_Status_Settings_General();

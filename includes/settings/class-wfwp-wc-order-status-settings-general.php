<?php
/**
 * Order Status for WooCommerce - General Section Settings
 *
 * @version 1.4.0
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
	 * get_settings.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function get_settings() {

		remove_filter(
			'wc_order_statuses',
			array( wfwp_wc_order_status()->core, 'sort_order_statuses' ),
			PHP_INT_MAX
		);
		$unsorted_statuses = wc_get_order_statuses();
		add_filter(
			'wc_order_statuses',
			array( wfwp_wc_order_status()->core, 'sort_order_statuses' ),
			PHP_INT_MAX
		);

		return array(
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
				'title'    => __( 'Order status sorting', 'order-status-for-woocommerce' ),
				'type'     => 'select',
				'class'    => 'chosen_select',
				'id'       => 'wfwp_wc_order_status_sorting',
				'default'  => 'default',
				'options'  => array(
					'default'   => __( 'Default', 'order-status-for-woocommerce' ),
					'title_asc' => __( 'By title (ascending)', 'order-status-for-woocommerce' ),
					'custom'    => __( 'Custom', 'order-status-for-woocommerce' ),
				),
			),
			array(
				'desc'     => (
					'<details style="width: fit-content;">' .
						'<summary style="cursor: pointer;">' . '<strong>' . __( 'Custom sorting', 'order-status-for-woocommerce' ) . '</strong>' . '</summary>' .
						'<p>' . '* ' . __( 'The "Sorting" option must be set to "Custom".', 'order-status-for-woocommerce' ) . '</p>' .
						'<p>' . '* ' . __( 'One status slug per line.', 'order-status-for-woocommerce' ) . '</p>' .
						'<p>' . '* ' . __( 'Default sorting:', 'order-status-for-woocommerce' ) . '</p>' .
							'<pre style="border: 1px solid gray; padding: 10px;">' . implode( PHP_EOL, array_keys( $unsorted_statuses ) ) . '</pre>' .
					'</details>'
				),
				'type'     => 'textarea',
				'id'       => 'wfwp_wc_order_status_sorting_custom',
				'default'  => '',
				'css'      => 'height: 150px; font-family: monospace;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'wfwp_wc_order_status_plugin_options',
			),
		);

	}

}

endif;

return new WFWP_WC_Order_Status_Settings_General();

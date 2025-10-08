<?php
/**
 * Order Status for WooCommerce - General Section Settings
 *
 * @version 1.9.0
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
	 * get_unsorted_statuses.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function get_unsorted_statuses() {
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
		return $unsorted_statuses;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.9.0
	 * @since   1.4.0
	 *
	 * @todo    (v1.9.0) display default sorting for order list, preview, bulk actions?
	 */
	function get_settings() {

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

		$settings = array_merge( $settings, array(
			array(
				'title'    => __( 'Sorting Options', 'order-status-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'wfwp_wc_order_status_sorting_options',
				'desc'     => (
					'<details style="width: fit-content;">' .
						'<summary style="cursor: pointer;">' .
							__( 'Custom sorting tips', 'order-status-for-woocommerce' ) .
						'</summary>' .
						'<p>' . '* ' . __( 'The "Sorting" option must be set to "Custom".', 'order-status-for-woocommerce' ) . '</p>' .
						'<p>' . '* ' . __( 'One status slug per line.', 'order-status-for-woocommerce' ) . '</p>' .
						'<p>' . '* ' . __( 'Default sorting (admin order edit page dropdown box):', 'order-status-for-woocommerce' ) . '</p>' .
							'<pre style="border: 1px solid gray; padding: 10px;">' .
								implode( PHP_EOL, array_keys( $this->get_unsorted_statuses() ) ) .
							'</pre>' .
					'</details>'
				),
			),
		) );

		$sorting_options = array(
			array(
				'title' => __( 'Admin order edit page dropdown box', 'order-status-for-woocommerce' ),
				'key'   => 'wfwp_wc_order_status_sorting',
			),
			array(
				'title' => __( 'Admin order list action buttons', 'order-status-for-woocommerce' ),
				'key'   => 'wfwp_wc_order_status_sorting_order_list_actions',
			),
			array(
				'title' => __( 'Admin order preview action buttons', 'order-status-for-woocommerce' ),
				'key'   => 'wfwp_wc_order_status_sorting_order_preview_actions',
			),
			array(
				'title' => __( 'Admin order bulk actions', 'order-status-for-woocommerce' ),
				'key'   => 'wfwp_wc_order_status_sorting_bulk_actions',
			),
		);

		foreach ( $sorting_options as $option_data ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => $option_data['title'],
					'type'     => 'select',
					'class'    => 'chosen_select',
					'id'       => $option_data['key'],
					'default'  => 'default',
					'options'  => array(
						'default'   => __( 'Default', 'order-status-for-woocommerce' ),
						'title_asc' => __( 'By title (ascending)', 'order-status-for-woocommerce' ),
						'custom'    => __( 'Custom', 'order-status-for-woocommerce' ),
					),
				),
				array(
					'desc'     => __( 'Custom sorting', 'order-status-for-woocommerce' ),
					'type'     => 'textarea',
					'id'       => $option_data['key'] . '_custom',
					'default'  => '',
					'css'      => 'height: 150px; font-family: monospace;',
				),
			) );
		}

		$settings = array_merge( $settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'wfwp_wc_order_status_sorting_options',
			),
		) );

		return $settings;

	}

}

endif;

return new WFWP_WC_Order_Status_Settings_General();

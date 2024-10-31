<?php
/*
Plugin Name: Additional Custom Order Status for WooCommerce
Plugin URI: https://wpfactory.com/item/order-status-for-woocommerce/
Description: Manage order statuses in WooCommerce. Beautifully.
Version: 1.6.0
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: order-status-for-woocommerce
Domain Path: /langs
WC tested up to: 9.3
Requires Plugins: woocommerce
*/

defined( 'ABSPATH' ) || exit;

if ( 'order-status-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.4.3
	 * @since   1.1.0
	 */
	$plugin = 'order-status-for-woocommerce-pro/order-status-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		defined( 'WFWP_WC_ORDER_STATUS_FILE_FREE' ) || define( 'WFWP_WC_ORDER_STATUS_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'WFWP_WC_ORDER_STATUS_VERSION' ) || define( 'WFWP_WC_ORDER_STATUS_VERSION', '1.6.0' );

defined( 'WFWP_WC_ORDER_STATUS_FILE' ) || define( 'WFWP_WC_ORDER_STATUS_FILE', __FILE__ );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-wfwp-wc-order-status.php';

if ( ! function_exists( 'wfwp_wc_order_status' ) ) {
	/**
	 * Returns the main instance of WFWP_WC_Order_Status to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function wfwp_wc_order_status() {
		return WFWP_WC_Order_Status::instance();
	}
}

add_action( 'plugins_loaded', 'wfwp_wc_order_status' );

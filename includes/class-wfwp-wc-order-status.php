<?php
/**
 * Order Status for WooCommerce - Main Class
 *
 * @version 1.1.1
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Order_Status' ) ) :

final class WFWP_WC_Order_Status {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = WFWP_WC_ORDER_STATUS_VERSION;

	/**
	 * @var   WFWP_WC_Order_Status The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main WFWP_WC_Order_Status Instance.
	 *
	 * Ensures only one instance of WFWP_WC_Order_Status is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  WFWP_WC_Order_Status - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * WFWP_WC_Order_Status Constructor.
	 *
	 * @version 1.1.1
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Check for active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ), 9 );

		// Pro
		if ( 'order-status-for-woocommerce-pro.php' === basename( WFWP_WC_ORDER_STATUS_FILE ) ) {
			require_once( 'pro/class-wfwp-wc-order-status-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * localize.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function localize() {
		load_plugin_textdomain( 'order-status-for-woocommerce', false, dirname( plugin_basename( WFWP_WC_ORDER_STATUS_FILE ) ) . '/langs/' );
	}

	/**
	 * includes.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function includes() {
		// Classes
		require_once( 'classes/class-wfwp-wc-shop-order-status.php' );
		// Core
		$this->core = require_once( 'class-wfwp-wc-order-status-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function admin() {
		require_once( 'admin/class-wfwp-wc-order-status-admin.php' );
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( WFWP_WC_ORDER_STATUS_FILE ), array( $this, 'action_links' ) );
		// Version update
		if ( get_option( 'wfwp_wc_order_status_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * action_links.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'edit.php?post_type=wfwp_wc_order_status' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'order-status-for-woocommerce.php' === basename( WFWP_WC_ORDER_STATUS_FILE ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/order-status-for-woocommerce/">' .
				__( 'Go Pro', 'order-status-for-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * version_updated.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function version_updated() {
		update_option( 'wfwp_wc_order_status_version', $this->version );
	}

	/**
	 * plugin_url.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( WFWP_WC_ORDER_STATUS_FILE ) );
	}

	/**
	 * plugin_path.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( WFWP_WC_ORDER_STATUS_FILE ) );
	}

}

endif;

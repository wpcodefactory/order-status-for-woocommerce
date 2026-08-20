<?php
/**
 * Order Status for WooCommerce - Main Class
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Order_Status
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Order_Status' ) ) :

	/**
	 * Main WFWP_WC_Order_Status class.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	final class WFWP_WC_Order_Status {

		/**
		 * Plugin version.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @var string
		 */
		public $version = WFWP_WC_ORDER_STATUS_VERSION;

		/**
		 * Core.
		 *
		 * @version 1.3.0
		 * @since   1.3.0
		 *
		 * @var WFWP_WC_Order_Status_Core
		 */
		public $core;

		/**
		 * Pro.
		 *
		 * @version 1.3.0
		 * @since   1.3.0
		 *
		 * @var WFWP_WC_Order_Status_Pro
		 */
		public $pro;

		/**
		 * Instance.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @var WFWP_WC_Order_Status The single instance of the class
		 */
		protected static $instance = null;

		/**
		 * Main WFWP_WC_Order_Status Instance.
		 *
		 * Ensures only one instance of WFWP_WC_Order_Status is loaded or can be loaded.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @static
		 * @return WFWP_WC_Order_Status - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * WFWP_WC_Order_Status Constructor.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @access  public
		 */
		public function __construct() {

			// Check for active WooCommerce plugin.
			if ( ! function_exists( 'WC' ) ) {
				return;
			}

			// Load libs.
			if ( is_admin() ) {
				require_once plugin_dir_path( WFWP_WC_ORDER_STATUS_FILE ) . 'vendor/autoload.php';
			}

			// Declare compatibility with custom order tables for WooCommerce.
			add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

			// Pro.
			if ( 'order-status-for-woocommerce-pro.php' === basename( WFWP_WC_ORDER_STATUS_FILE ) ) {
				$this->pro = require_once plugin_dir_path( __FILE__ ) . 'pro/class-wfwp-wc-order-status-pro.php';
			}

			// Include required files.
			$this->includes();

			// Admin.
			if ( is_admin() ) {
				$this->admin();
			}
		}

		/**
		 * WC declare compatibility.
		 *
		 * @version 1.4.3
		 * @since   1.4.0
		 *
		 * @see https://developer.woocommerce.com/docs/features/high-performance-order-storage/recipe-book/
		 */
		public function wc_declare_compatibility() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				$files = (
					defined( 'WFWP_WC_ORDER_STATUS_FILE_FREE' ) ?
					array( WFWP_WC_ORDER_STATUS_FILE, WFWP_WC_ORDER_STATUS_FILE_FREE ) :
					array( WFWP_WC_ORDER_STATUS_FILE )
				);
				foreach ( $files as $file ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
						'custom_order_tables',
						$file,
						true
					);
				}
			}
		}

		/**
		 * Includes.
		 *
		 * @version 1.6.0
		 * @since   1.0.0
		 */
		public function includes() {

			// Classes.
			require_once plugin_dir_path( __FILE__ ) . 'classes/class-wfwp-wc-shop-order-status.php';

			// Core.
			$this->core = require_once plugin_dir_path( __FILE__ ) . 'class-wfwp-wc-order-status-core.php';
		}

		/**
		 * Admin.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 */
		public function admin() {

			// Admin class.
			require_once plugin_dir_path( __FILE__ ) . 'admin/class-wfwp-wc-order-status-admin.php';

			// Action links.
			add_filter(
				'plugin_action_links_' . plugin_basename( WFWP_WC_ORDER_STATUS_FILE ),
				array( $this, 'action_links' )
			);

			// "Recommendations" page.
			add_action( 'init', array( $this, 'add_cross_selling_library' ) );

			// WC Settings tab as WPFactory submenu item.
			add_action( 'init', array( $this, 'move_wc_settings_tab_to_wpfactory_menu' ) );

			// Settings.
			add_filter(
				'woocommerce_get_settings_pages',
				array( $this, 'add_woocommerce_settings_tab' )
			);

			// Version update.
			if ( get_option( 'wfwp_wc_order_status_version', '' ) !== $this->version ) {
				add_action( 'admin_init', array( $this, 'version_updated' ) );
			}
		}

		/**
		 * Action links.
		 *
		 * @version 1.6.0
		 * @since   1.0.0
		 *
		 * @param mixed $links Links.
		 * @return array
		 */
		public function action_links( $links ) {
			$custom_links = array();

			$custom_links[] = '<a href="' . admin_url( 'edit.php?post_type=wfwp_wc_order_status' ) . '">' .
				__( 'Statuses', 'order-status-for-woocommerce' ) .
			'</a>';

			$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=wfwp_wc_order_status' ) . '">' .
			__( 'Settings', 'order-status-for-woocommerce' ) .
			'</a>';

			if ( 'order-status-for-woocommerce.php' === basename( WFWP_WC_ORDER_STATUS_FILE ) ) {
				$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/order-status-for-woocommerce/">' .
					__( 'Go Pro', 'order-status-for-woocommerce' ) .
				'</a>';
			}

			return array_merge( $custom_links, $links );
		}

		/**
		 * Add cross selling library.
		 *
		 * @version 1.6.0
		 * @since   1.6.0
		 */
		public function add_cross_selling_library() {

			if ( ! class_exists( '\WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling' ) ) {
				return;
			}

			$cross_selling = new \WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling();
			$cross_selling->setup( array( 'plugin_file_path' => WFWP_WC_ORDER_STATUS_FILE ) );
			$cross_selling->init();
		}

		/**
		 * Move WC settings tab to WPFactory menu.
		 *
		 * @version 1.8.0
		 * @since   1.6.0
		 */
		public function move_wc_settings_tab_to_wpfactory_menu() {

			if ( ! class_exists( '\WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu' ) ) {
				return;
			}

			$wpfactory_admin_menu = \WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();

			if ( ! method_exists( $wpfactory_admin_menu, 'move_wc_settings_tab_to_wpfactory_menu' ) ) {
				return;
			}

			$wpfactory_admin_menu->move_wc_settings_tab_to_wpfactory_menu(
				array(
					'wc_settings_tab_id' => 'wfwp_wc_order_status',
					'menu_title'         => __( 'Order Status', 'order-status-for-woocommerce' ),
					'page_title'         => __( 'Additional Custom Order Status for WooCommerce', 'order-status-for-woocommerce' ),
					'plugin_icon'        => array(
						'get_url_method'    => 'wporg_plugins_api',
						'wporg_plugin_slug' => 'order-status-for-woocommerce',
					),
				)
			);
		}

		/**
		 * Add WooCommerce settings tab.
		 *
		 * @version 1.6.0
		 * @since   1.4.0
		 *
		 * @param array $settings Settings.
		 */
		public function add_woocommerce_settings_tab( $settings ) {
			$settings[] = require_once plugin_dir_path( __FILE__ ) . 'settings/class-wfwp-wc-order-status-settings.php';
			return $settings;
		}

		/**
		 * Version updated.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function version_updated() {
			update_option( 'wfwp_wc_order_status_version', $this->version );
		}

		/**
		 * Plugin URL.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugin_dir_url( WFWP_WC_ORDER_STATUS_FILE ) );
		}

		/**
		 * Plugin path.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( WFWP_WC_ORDER_STATUS_FILE ) );
		}
	}

endif;

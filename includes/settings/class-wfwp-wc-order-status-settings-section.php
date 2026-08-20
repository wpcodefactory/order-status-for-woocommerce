<?php
/**
 * Order Status for WooCommerce - Section Settings
 *
 * @version 1.4.6
 * @since   1.4.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Order_Status\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Order_Status_Settings_Section' ) ) :

	/**
	 * WFWP_WC_Order_Status_Settings_Section class.
	 *
	 * @version 1.4.6
	 * @since   1.4.0
	 */
	class WFWP_WC_Order_Status_Settings_Section {

		/**
		 * ID.
		 *
		 * @version 1.4.6
		 * @since   1.4.6
		 *
		 * @var string
		 */
		public $id;

		/**
		 * Description.
		 *
		 * @version 1.4.6
		 * @since   1.4.6
		 *
		 * @var string
		 */
		public $desc;

		/**
		 * Constructor.
		 *
		 * @version 1.4.0
		 * @since   1.4.0
		 */
		public function __construct() {
			add_filter(
				'woocommerce_get_sections_wfwp_wc_order_status',
				array( $this, 'settings_section' )
			);
			add_filter(
				'woocommerce_get_settings_wfwp_wc_order_status_' . $this->id,
				array( $this, 'get_settings' ),
				PHP_INT_MAX
			);
		}

		/**
		 * Settings section.
		 *
		 * @version 1.4.0
		 * @since   1.4.0
		 *
		 * @param array $sections Sections.
		 */
		public function settings_section( $sections ) {
			$sections[ $this->id ] = $this->desc;
			return $sections;
		}
	}

endif;

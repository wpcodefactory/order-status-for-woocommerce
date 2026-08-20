<?php
/**
 * Order Status for WooCommerce - Status Class
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Order_Status
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Shop_Order_Status' ) ) :

	/**
	 * WFWP_WC_Shop_Order_Status class.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	class WFWP_WC_Shop_Order_Status {

		/**
		 * Post ID.
		 *
		 * @var int
		 */
		public $post_id;

		/**
		 * Title.
		 *
		 * @var string
		 */
		public $title;

		/**
		 * Slug.
		 *
		 * @var string
		 */
		public $slug;

		/**
		 * WooCommerce slug.
		 *
		 * @var string
		 */
		public $wc_slug;

		/**
		 * Text color.
		 *
		 * @var string
		 */
		public $text_color;

		/**
		 * Background color.
		 *
		 * @var string
		 */
		public $bg_color;

		/**
		 * Is bulk action.
		 *
		 * @var bool
		 */
		public $is_bulk_action;

		/**
		 * Is report.
		 *
		 * @var bool
		 */
		public $is_report;

		/**
		 * Is order list action.
		 *
		 * @var bool
		 */
		public $is_order_list_action;

		/**
		 * Order list icon.
		 *
		 * @var string
		 */
		public $order_list_icon;

		/**
		 * Order list icon color.
		 *
		 * @var string
		 */
		public $order_list_icon_color;

		/**
		 * Order list icon background color.
		 *
		 * @var string
		 */
		public $order_list_icon_bg_color;

		/**
		 * Is order preview action.
		 *
		 * @var bool
		 */
		public $is_order_preview_action;

		/**
		 * Is order editable.
		 *
		 * @var bool
		 */
		public $is_order_editable;

		/**
		 * Is order paid.
		 *
		 * @var bool
		 */
		public $is_order_paid;

		/**
		 * Is order valid for payment.
		 *
		 * @var bool
		 */
		public $is_order_valid_for_payment;

		/**
		 * Do set order date paid.
		 *
		 * @var bool
		 */
		public $do_set_order_date_paid;

		/**
		 * Do download permissions.
		 *
		 * @var bool
		 */
		public $do_download_permissions;

		/**
		 * Do send email.
		 *
		 * @var bool
		 */
		public $do_send_email;

		/**
		 * Email address.
		 *
		 * @var string
		 */
		public $email_address;

		/**
		 * Email subject.
		 *
		 * @var string
		 */
		public $email_subject;

		/**
		 * Do wrap email.
		 *
		 * @var bool
		 */
		public $do_wrap_email;

		/**
		 * Email heading.
		 *
		 * @var string
		 */
		public $email_heading;

		/**
		 * Email content.
		 *
		 * @var string
		 */
		public $email_content;

		/**
		 * Admin note.
		 *
		 * @var string
		 */
		public $admin_note;

		/**
		 * Private data.
		 *
		 * @version 1.3.0
		 * @since   1.3.0
		 *
		 * @var bool
		 */
		private $is_override;

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @param int|bool $post_id Post ID (default: false).
		 *
		 * @todo (dev) [!] if `is_override`: skip unused options, e.g., `is_report`?
		 * @todo (dev) go through all `options` automatically?
		 * @todo (dev) store all post meta as serialized data?
		 * @todo (dev) store all class properties as single array (i.e., `$this->data`)?
		 */
		public function __construct( $post_id = false ) {

			// General data.
			$this->post_id = ( $post_id ? $post_id : get_the_ID() );
			$this->title   = get_the_title( $this->post_id );
			$this->slug    = get_post_field( 'post_name', get_post( $this->post_id ) );
			$this->wc_slug = 'wc-' . $this->slug;

			// Styling options.
			$this->text_color = $this->get_option( 'text_color', '#000000' );
			$this->bg_color   = $this->get_option( 'bg_color', '#999999' );

			// Admin options.
			$this->is_bulk_action = ( 'yes' === $this->get_option( 'is_bulk_action', 'yes' ) );
			$this->is_report      = ( 'yes' === $this->get_option( 'is_report', 'yes' ) );

			// Action buttons options.
			$this->is_order_list_action     = ( 'yes' === $this->get_option( 'is_order_list_action', 'no' ) );
			$this->order_list_icon          = $this->get_option( 'order_list_icon', 'e011' );
			$this->order_list_icon_color    = $this->get_option( 'order_list_icon_color', '#999999' );
			$this->order_list_icon_bg_color = $this->get_option( 'order_list_icon_bg_color', '#ffffff' );
			$this->is_order_preview_action  = ( 'yes' === $this->get_option( 'is_order_preview_action', 'no' ) );

			// Order options.
			$this->is_order_editable          = ( 'yes' === $this->get_option( 'is_order_editable', 'no' ) );
			$this->is_order_paid              = ( 'yes' === $this->get_option( 'is_order_paid', 'no' ) );
			$this->is_order_valid_for_payment = ( 'yes' === $this->get_option( 'is_order_valid_for_payment', 'no' ) );
			$this->do_set_order_date_paid     = ( 'yes' === $this->get_option( 'do_set_order_date_paid', 'no' ) );
			$this->do_download_permissions    = ( 'yes' === $this->get_option( 'do_download_permissions', 'no' ) );

			// Admin note.
			$this->admin_note = $this->get_option( 'admin_note' );

			// Do action.
			do_action( 'wfwp_wc_shop_order_status_init', $this );
		}

		/**
		 * Get option.
		 *
		 * @version 1.3.0
		 * @since   1.3.0
		 *
		 * @param string $option        Option name.
		 * @param mixed  $default_value Default value (default: '').
		 *
		 * @return mixed
		 */
		public function get_option( $option, $default_value = '' ) {
			$value = get_post_meta( $this->post_id, '_' . $option, true );
			return (
				'' !== $value ?
				$value :
				$default_value
			);
		}

		/**
		 * Is override.
		 *
		 * @version 2.0.0
		 * @since   1.3.0
		 *
		 * @see https://github.com/woocommerce/woocommerce/blob/7.7.0/plugins/woocommerce/includes/wc-order-functions.php#L96
		 *
		 * @todo (dev) use `wc_is_order_status( $this->wc_slug )` instead (with `remove_filter/add_filter( 'wc_order_statuses', array( wfwp_wc_order_status()->core, 'add_custom_order_statuses' ), PHP_INT_MAX )`)
		 * @todo (feature) add default statuses as drafts?
		 */
		public function is_override() {
			if ( ! isset( $this->is_override ) ) {
				$this->is_override = in_array(
					$this->wc_slug,
					array(
						'wc-pending',
						'wc-processing',
						'wc-on-hold',
						'wc-completed',
						'wc-cancelled',
						'wc-refunded',
						'wc-failed',
					),
					true
				);
			}
			return $this->is_override;
		}
	}

endif;

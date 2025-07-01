<?php
/**
 * Order Status for WooCommerce - Status Class
 *
 * @version 1.7.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Shop_Order_Status' ) ) :

class WFWP_WC_Shop_Order_Status {

	/**
	 * Public data.
	 *
	 * @version 1.4.4
	 * @since   1.0.0
	 */
	public $post_id;
	public $title;
	public $slug;
	public $wc_slug;
	public $text_color;
	public $bg_color;
	public $is_bulk_action;
	public $is_report;
	public $is_order_list_action;
	public $order_list_icon;
	public $order_list_icon_color;
	public $order_list_icon_bg_color;
	public $is_order_preview_action;
	public $is_order_editable;
	public $is_order_paid;
	public $do_set_order_date_paid;
	public $do_download_permissions;
	public $do_send_email;
	public $email_address;
	public $email_subject;
	public $do_wrap_email;
	public $email_heading;
	public $email_content;
	public $admin_note;

	/**
	 * Private data.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	private $is_override;

	/**
	 * Constructor.
	 *
	 * @version 1.7.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) [!] if `is_override`: skip unused options, e.g., `is_report`?
	 * @todo    (dev) go through all `options` automatically?
	 * @todo    (dev) store all post meta as serialized data?
	 * @todo    (dev) store all class properties as single array (i.e., `$this->data`)?
	 */
	function __construct( $post_id = false ) {

		// General data
		$this->post_id = ( $post_id ? $post_id : get_the_ID() );
		$this->title   = get_the_title( $this->post_id );
		$this->slug    = get_post_field( 'post_name', get_post( $this->post_id ) );
		$this->wc_slug = 'wc-' . $this->slug;

		// Styling options
		$this->text_color = $this->get_option( 'text_color', '#000000' );
		$this->bg_color   = $this->get_option( 'bg_color', '#999999' );

		// Admin options
		$this->is_bulk_action = ( 'yes' === $this->get_option( 'is_bulk_action', 'yes' ) );
		$this->is_report      = ( 'yes' === $this->get_option( 'is_report', 'yes' ) );

		// Action buttons options
		$this->is_order_list_action     = ( 'yes' === $this->get_option( 'is_order_list_action', 'no' ) );
		$this->order_list_icon          = $this->get_option( 'order_list_icon', 'e011' );
		$this->order_list_icon_color    = $this->get_option( 'order_list_icon_color', '#999999' );
		$this->order_list_icon_bg_color = $this->get_option( 'order_list_icon_bg_color', '#ffffff' );
		$this->is_order_preview_action  = ( 'yes' === $this->get_option( 'is_order_preview_action', 'no' ) );

		// Order options
		$this->is_order_editable       = ( 'yes' === $this->get_option( 'is_order_editable', 'no' ) );
		$this->is_order_paid           = ( 'yes' === $this->get_option( 'is_order_paid', 'no' ) );
		$this->do_set_order_date_paid  = ( 'yes' === $this->get_option( 'do_set_order_date_paid', 'no' ) );
		$this->do_download_permissions = ( 'yes' === $this->get_option( 'do_download_permissions', 'no' ) );

		// Email options (default values)
		$default_subject = sprintf(
			/* Translators: %1$s: Site Title placeholder, %2$s: Order Number placeholder, %3$s: Status Title placeholder, %4$s: Order Date placeholder. */
			__( '%1$s Order %2$s status changed to %3$s - %4$s', 'order-status-for-woocommerce' ),
			'[{site_title}]',
			'#{order_number}',
			'{status_to_title}',
			'{order_date}'
		);
		$default_heading = sprintf(
			/* Translators: %s: Status Title placeholder. */
			__( 'Order status changed to %s', 'order-status-for-woocommerce' ),
			'{status_to_title}'
		);
		$default_content = sprintf(
			/* Translators: %1$s: Order Number placeholder, %2$s: Status Title placeholder, %3$s: Status Title placeholder. */
			__( 'Order %1$s status changed from %2$s to %3$s.', 'order-status-for-woocommerce' ),
			'#{order_number}',
			'{status_from_title}',
			'{status_to_title}'
		);

		// Email options
		$this->do_send_email = ( 'yes' === $this->get_option( 'do_send_email', 'no' ) );
		$this->email_address = $this->get_option( 'email_address' );
		$this->email_subject = $this->get_option( 'email_subject', $default_subject );
		$this->do_wrap_email = ( 'yes' === $this->get_option( 'do_wrap_email', 'yes' ) );
		$this->email_heading = $this->get_option( 'email_heading', $default_heading );
		$this->email_content = $this->get_option( 'email_content', $default_content );

		// Admin note
		$this->admin_note = $this->get_option( 'admin_note' );

	}

	/**
	 * get_option.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function get_option( $option, $default = '' ) {
		return (
			'' !== ( $value = get_post_meta( $this->post_id, '_' . $option, true ) ) ?
			$value :
			$default
		);
	}

	/**
	 * is_override.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 *
	 * @see     https://github.com/woocommerce/woocommerce/blob/7.7.0/plugins/woocommerce/includes/wc-order-functions.php#L96
	 *
	 * @todo    (dev) use `wc_is_order_status( $this->wc_slug )` instead (with `remove_filter/add_filter( 'wc_order_statuses', array( wfwp_wc_order_status()->core, 'add_custom_order_statuses' ), PHP_INT_MAX )`)
	 * @todo    (feature) add default statuses as drafts?
	 */
	function is_override() {
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
				)
			);
		}
		return $this->is_override;
	}

}

endif;

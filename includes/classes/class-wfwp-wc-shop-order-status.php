<?php
/**
 * Order Status for WooCommerce - Status Class
 *
 * @version 1.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Shop_Order_Status' ) ) :

class WFWP_WC_Shop_Order_Status {

	/**
	 * data.
	 *
	 * @since 1.0.0
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
	public $do_send_email;
	public $email_address;
	public $email_subject;
	public $email_heading;
	public $email_content;
	public $admin_note;

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    [dev] (maybe) go through all `options` automatically
	 * @todo    [dev] (maybe) store all post meta as serialized data
	 * @todo    [dev] (maybe) store all class properties as single array (i.e. `$this->data`)
	 */
	function __construct( $post_id ) {

		// General data
		$this->post_id                   = $post_id;
		$this->title                     = get_the_title( $this->post_id );
		$this->slug                      = get_post_field( 'post_name', get_post( $this->post_id ) );
		$this->wc_slug                   = 'wc-' . $this->slug;

		// Styling options
		$this->text_color                = get_post_meta( $this->post_id, '_' . 'text_color', true );
		$this->bg_color                  = get_post_meta( $this->post_id, '_' . 'bg_color', true );

		// Admin options
		$this->is_bulk_action            = ( 'yes' === get_post_meta( $this->post_id, '_' . 'is_bulk_action', true ) );
		$this->is_report                 = ( 'yes' === get_post_meta( $this->post_id, '_' . 'is_report', true ) );

		// Action buttons options
		$this->is_order_list_action      = ( 'yes' === get_post_meta( $this->post_id, '_' . 'is_order_list_action', true ) );
		$this->order_list_icon           = get_post_meta( $this->post_id, '_' . 'order_list_icon', true );
		$this->order_list_icon_color     = get_post_meta( $this->post_id, '_' . 'order_list_icon_color', true );
		$this->order_list_icon_bg_color  = get_post_meta( $this->post_id, '_' . 'order_list_icon_bg_color', true );
		$this->is_order_preview_action   = ( 'yes' === get_post_meta( $this->post_id, '_' . 'is_order_preview_action', true ) );

		// Order options
		$this->is_order_editable         = ( 'yes' === get_post_meta( $this->post_id, '_' . 'is_order_editable', true ) );
		$this->is_order_paid             = ( 'yes' === get_post_meta( $this->post_id, '_' . 'is_order_paid', true ) );

		// Email options
		$this->do_send_email             = ( 'yes' === get_post_meta( $this->post_id, '_' . 'do_send_email', true ) );
		$this->email_address             = get_post_meta( $this->post_id, '_' . 'email_address', true );
		$this->email_subject             = get_post_meta( $this->post_id, '_' . 'email_subject', true );
		$this->email_heading             = get_post_meta( $this->post_id, '_' . 'email_heading', true );
		$this->email_content             = get_post_meta( $this->post_id, '_' . 'email_content', true );

		// Admin note
		$this->admin_note                = get_post_meta( $this->post_id, '_' . 'admin_note', true );

	}

}

endif;

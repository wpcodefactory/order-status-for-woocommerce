<?php
/**
 * Order Status for WooCommerce - Options
 *
 * @version 1.4.4
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

return apply_filters( 'wfwp_wc_order_status_options', array(
	'main_data' => array(
		'title'    => __( 'Status Slug', 'order-status-for-woocommerce' ),
		'desc'     => __( 'Status slug cannot be edited.', 'order-status-for-woocommerce' ),
		'options'  => array(
			'slug' => array(
				'id'       => 'slug',
				'type'     => 'text',
				'css'      => 'width:100%',
				'readonly' => true,
			),
		),
	),
	'styling_options' => array(
		'title'    => __( 'Styling Options', 'order-status-for-woocommerce' ),
		'desc'     => __( 'Styling options are visible in "Status" column and on order preview page.', 'order-status-for-woocommerce' ),
		'options'  => array(
			'text_color' => array(
				'title'    => __( 'Text color', 'order-status-for-woocommerce' ),
				'id'       => 'text_color',
				'type'     => 'color',
				'default'  => '#000000',
			),
			'bg_color' => array(
				'title'    => __( 'Background color', 'order-status-for-woocommerce' ),
				'id'       => 'bg_color',
				'type'     => 'color',
				'default'  => '#999999',
			),
		),
	),
	'general_options' => array(
		'title'    => __( 'General Options', 'order-status-for-woocommerce' ),
		'options'  => array(
			'is_bulk_action' => array(
				'title'    => __( 'Add to order bulk actions', 'order-status-for-woocommerce' ),
				'id'       => 'is_bulk_action',
				'type'     => 'select',
				'default'  => 'yes',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
			),
			'is_report' => array(
				'title'    => __( 'Add to reports', 'order-status-for-woocommerce' ),
				'id'       => 'is_report',
				'type'     => 'select',
				'default'  => 'yes',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
			),
		),
	),
	'action_buttons_options' => array(
		'title'    => __( 'Action Buttons Options', 'order-status-for-woocommerce' ),
		'options'  => array(
			'is_order_list_action' => array(
				'title'    => __( 'Add to order list action buttons', 'order-status-for-woocommerce' ),
				'id'       => 'is_order_list_action',
				'type'     => 'select',
				'default'  => 'no',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
			),
			'order_list_icon' => array(
				'title'    => __( 'Order list icon', 'order-status-for-woocommerce' ) .
					' [<a target="_blank" href="https://rawgit.com/woothemes/woocommerce-icons/master/demo.html">' . __( 'icon codes', 'order-status-for-woocommerce' ) . '</a>]' .
					' <span class="view %slug%' . '"></span>',
				'id'       => 'order_list_icon',
				'type'     => 'text',
				'default'  => 'e011',
			),
			'order_list_icon_color' => array(
				'title'    => __( 'Order list icon color', 'order-status-for-woocommerce' ),
				'id'       => 'order_list_icon_color',
				'type'     => 'color',
				'default'  => '#999999',
			),
			'order_list_icon_bg_color' => array(
				'title'    => __( 'Order list icon background color', 'order-status-for-woocommerce' ),
				'id'       => 'order_list_icon_bg_color',
				'type'     => 'color',
				'default'  => '#ffffff',
			),
			'is_order_preview_action' => array(
				'title'    => __( 'Add to admin order preview action buttons', 'order-status-for-woocommerce' ),
				'id'       => 'is_order_preview_action',
				'type'     => 'select',
				'default'  => 'no',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
			),
		),
	),
	'order_options' => array(
		'title'    => __( 'Order Options', 'order-status-for-woocommerce' ),
		'options'  => array(
			'is_order_editable' => array(
				'title'    => __( 'Is order editable', 'order-status-for-woocommerce' ),
				'id'       => 'is_order_editable',
				'type'     => 'select',
				'default'  => 'no',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
			),
			'is_order_paid' => array(
				'title'    => __( 'Is order paid', 'order-status-for-woocommerce' ),
				'id'       => 'is_order_paid',
				'type'     => 'select',
				'default'  => 'no',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
			),
			'do_set_order_date_paid' => array(
				'title'    => __( 'Set order "date paid" on status update', 'order-status-for-woocommerce' ),
				'id'       => 'do_set_order_date_paid',
				'type'     => 'select',
				'default'  => 'no',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
			),
			'do_download_permissions' => array(
				'title'    => __( 'Download permissions', 'order-status-for-woocommerce' ),
				'id'       => 'do_download_permissions',
				'type'     => 'select',
				'default'  => 'no',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
			),
		),
	),
	'email_options' => array(
		'title'    => __( 'Email Options', 'order-status-for-woocommerce' ),
		'desc'     => apply_filters( 'wfwp_wc_order_status_settings', '<div style="padding:10px; background-color: #f0f0f0; font-weight: bold; margin-bottom: 5px; display: block;">' .
				'&#x26A0; Emails are available in <a target="_blank" href="https://wpfactory.com/item/order-status-for-woocommerce/">Order Status for WooCommerce Pro version</a> only.' .
			'</div>' ) .
			sprintf( __( 'Placeholders in <strong>subject</strong>, <strong>heading</strong> and <strong>content</strong>: %s', 'order-status-for-woocommerce' ),
				'<code>' . implode( '</code>, <code>', array(
					'{order_id}',
					'{order_number}',
					'{order_date}',
					'{order_billing_first_name}',
					'{order_billing_last_name}',
					'{site_title}',
					'{status_to}',
					'{status_to_title}',
					'{status_from}',
					'{status_from_title}',
				) ) . '</code>' ) . '<br>' .
			sprintf( __( 'Additional placeholder in <strong>heading</strong> and <strong>content</strong>: %s', 'order-status-for-woocommerce' ),
				'<code>' . implode( '</code>, <code>', array( '{order_details}' ) ) . '</code>' ) . '<br>' .
			sprintf( __( 'Placeholders in email <strong>address</strong>: %s', 'order-status-for-woocommerce' ),
				'<code>' . implode( '</code>, <code>', array( '%customer%', '%admin%' ) ) . '</code>' ),
		'options'  => array(
			'do_send_email' => array(
				'title'    => __( 'Send email on status change', 'order-status-for-woocommerce' ),
				'id'       => 'do_send_email',
				'type'     => 'select',
				'default'  => 'no',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
				'custom_attributes' => apply_filters( 'wfwp_wc_order_status_settings', 'disabled="disabled"' ),
			),
			'email_address' => array(
				'title'    => __( 'Email address', 'order-status-for-woocommerce' ),
				'id'       => 'email_address',
				'type'     => 'text',
				'css'      => 'width:100%',
				'default'  => '',
				'placeholder' => get_option( 'admin_email' ),
			),
			'email_subject' => array(
				'title'    => __( 'Subject', 'order-status-for-woocommerce' ),
				'id'       => 'email_subject',
				'type'     => 'text',
				'css'      => 'width:100%',
				'default'  => sprintf( __( '%s Order %s status changed to %s - %s', 'order-status-for-woocommerce' ), '[{site_title}]', '#{order_number}', '{status_to_title}', '{order_date}' ),
			),
			'do_wrap_email' => array(
				'title'    => __( 'Wrap in WooCommerce template', 'order-status-for-woocommerce' ),
				'id'       => 'do_wrap_email',
				'type'     => 'select',
				'default'  => 'yes',
				'options'  => array(
					'no'  => __( 'No', 'order-status-for-woocommerce' ),
					'yes' => __( 'Yes', 'order-status-for-woocommerce' ),
				),
			),
			'email_heading' => array(
				'title'    => __( 'WooCommerce template heading', 'order-status-for-woocommerce' ),
				'id'       => 'email_heading',
				'type'     => 'text',
				'css'      => 'width:100%',
				'default'  => sprintf( __( 'Order status changed to %s', 'order-status-for-woocommerce' ), '{status_to_title}' ),
			),
			'email_content' => array(
				'title'    => __( 'Content', 'order-status-for-woocommerce' ),
				'id'       => 'email_content',
				'type'     => 'textarea',
				'css'      => 'width:100%;height:200px',
				'default'  => sprintf( __( 'Order %s status changed from %s to %s', 'order-status-for-woocommerce' ), '#{order_number}', '{status_from_title}', '{status_to_title}' ),
			),
		),
	),
	'admin_note' => array(
		'title'    => __( 'Admin Note', 'order-status-for-woocommerce' ),
		'desc'     => __( 'Admin note is visible on current page only.', 'order-status-for-woocommerce' ),
		'context'  => 'side',
		'options'  => array(
			'admin_note' => array(
				'id'       => 'admin_note',
				'type'     => 'textarea',
				'css'      => 'width:100%;height:100px',
				'default'  => '',
			),
		),
	),
	'actions' => array(
		'title'    => __( 'Actions', 'order-status-for-woocommerce' ),
		'desc'     => '<p>' . implode( '</p><p>', array(
			'<a style="font-style:normal; color:#a00;" href="' .
				wp_nonce_url( admin_url( '?wfwp_wcos_delete=__wfwp_wcos_post_id__' ), 'delete', 'wfwp_wcos_nonce' ) .
				'" onclick="return confirm(\'' . __( 'Are you sure?', 'order-status-for-woocommerce' ) . '\')">' .
				__( 'Delete status', 'order-status-for-woocommerce' ) . '</a>',
			'<a style="font-style:normal; color:#a00;" href="' .
				wp_nonce_url( admin_url( '?wfwp_wcos_delete_fallback=__wfwp_wcos_post_id__&wfwp_wcos_delete_fallback_status=on-hold' ), 'delete_fallback', 'wfwp_wcos_nonce' ) .
				'" onclick="return confirm(\'' . __( 'Are you sure?', 'order-status-for-woocommerce' ) . '\')">' .
				__( 'Delete status with fallback', 'order-status-for-woocommerce' ) . '</a>',
			'<a style="font-style:normal;" href="' . admin_url( 'edit.php?post_status=wc-%slug%&post_type=shop_order' ) . '" target="_blank">' .
				__( 'View orders', 'order-status-for-woocommerce' ) . '</a>',
		) ) . '</p>',
		'context'  => 'side',
		'options'  => array(),
	),
) );

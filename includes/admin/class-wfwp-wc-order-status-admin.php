<?php
/**
 * Order Status for WooCommerce - Admin Class
 *
 * @version 1.4.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Order_Status_Admin' ) ) :

class WFWP_WC_Order_Status_Admin {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) editable slug - if slug is changed, change all orders to new status (or make it editable at least in draft)
	 * @todo    (feature) [!] custom columns in "Statuses" list (slug, icon, options, etc.)
	 * @todo    (feature) icon picker (JS?)
	 * @todo    (feature) translations (for the custom status title)
	 * @todo    (feature) add option to set which "options" to show in admin meta boxes
	 * @todo    (feature) customizable default value for each "option"
	 */
	function __construct() {

		add_action( 'admin_menu', array( $this, 'add_menu_link' ), 100 );

		add_action( 'admin_head', array( $this, 'hide_default_options' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post_wfwp_wc_order_status', array( $this, 'save_meta_box' ), PHP_INT_MAX, 2 );

		add_filter( 'wp_insert_post_data', array( $this, 'on_insert_post_data' ), PHP_INT_MAX, 2 );

		add_filter( 'admin_init', array( $this, 'delete_status' ), PHP_INT_MAX );
		add_filter( 'admin_notices', array( $this, 'delete_status_notices' ), PHP_INT_MAX );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * filter_options.
	 *
	 * @version 1.4.0
	 * @since   1.3.0
	 *
	 * @todo    (dev) [!] `is_override`: enable all options, e.g., `is_report`, `is_order_paid`, etc.
	 * @todo    (dev) `draft`: better msg, e.g., check for duplicated slug
	 */
	function filter_options( $options, $post_id = false ) {

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ( $status = new WFWP_WC_Shop_Order_Status( $post_id ) ) ) {

			// Override
			if ( $status->is_override() ) {

				if ( isset( $options['main_data']['desc'] ) ) {
					$options['main_data']['desc'] .= '<br>' . '<strong>' .
						sprintf( __( 'You are overriding the default %s WooCommerce status.', 'order-status-for-woocommerce' ), '<code>' . $status->wc_slug . '</code>' ) .
					'</strong>';
				}

				if ( isset( $options['general_options']['options']['is_report'] ) ) {
					unset( $options['general_options']['options']['is_report'] );
				}

				if ( isset( $options['action_buttons_options'] ) ) {
					unset( $options['action_buttons_options'] );
				}

				if ( isset( $options['order_options']['options']['is_order_paid'] ) ) {
					unset( $options['order_options']['options']['is_order_paid'] );
				}

				if ( isset( $options['order_options']['options']['do_set_order_date_paid'] ) ) {
					unset( $options['order_options']['options']['do_set_order_date_paid'] );
				}

				if ( isset( $options['email_options']['options']['do_send_email']['title'] ) ) {
					$options['email_options']['options']['do_send_email']['title'] = __( 'Send an additional email on status change', 'order-status-for-woocommerce' );
				}

			}

			// Draft
			if ( 'draft' === get_post_status( $post_id ) ) {

				if ( isset( $options['main_data']['desc'] ) ) {
					$options['main_data']['desc'] .= '<br>' . '<strong>' .
						__( 'This is a draft. The slug may change after you publish.', 'order-status-for-woocommerce' ) .
					'</strong>';
				}

			}

		}

		return $options;

	}

	/**
	 * get_options.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) recheck option descriptions
	 */
	function get_options( $post_id = false ) {
		if ( ! isset( $this->options ) ) {
			$this->options = require_once( 'wfwp-wc-order-status-options.php' );
			$this->options = $this->filter_options( $this->options, $post_id );
		}
		return $this->options;
	}

	/**
	 * enqueue_scripts.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function enqueue_scripts() {
		global $pagenow, $typenow;
		if ( ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) && 'wfwp_wc_order_status' === $typenow ) {
			wp_enqueue_script(
				'wfwp-wcos-admin',
				wfwp_wc_order_status()->plugin_url() . '/includes/js/wfwp-wcos-admin' . ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ? '' : '.min' ) . '.js',
				array( 'jquery' ),
				wfwp_wc_order_status()->version,
				true
			);
			wp_localize_script(
				'wfwp-wcos-admin',
				'wfwp_wcos_admin_object',
				array(
					'start_with_number_error_message' => __( 'Order status title cannot start with number.', 'order-status-for-woocommerce' ),
				)
			);
		}
	}

	/**
	 * delete_status_notices.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) retrieve fallback status title (instead of slug)
	 */
	function delete_status_notices() {
		if ( isset( $_GET['wfwp_wcos_delete_finished'] ) ) {
			$status_title    = $_GET['wfwp_wcos_delete_finished'];
			echo '<div class="notice notice-success is-dismissible"><p>' . sprintf( __( '%s status deleted.', 'order-status-for-woocommerce' ), $status_title ) . '</p></div>';
		}
		if ( isset( $_GET['wfwp_wcos_delete_fallback_finished'] ) && isset( $_GET['wfwp_wcos_delete_fallback_orders_updated'] ) && isset( $_GET['wfwp_wcos_delete_fallback_status'] ) ) {
			$status_title    = $_GET['wfwp_wcos_delete_fallback_finished'];
			$orders_updated  = $_GET['wfwp_wcos_delete_fallback_orders_updated'];
			$fallback_status = $_GET['wfwp_wcos_delete_fallback_status'];
			echo '<div class="notice notice-success is-dismissible"><p>' . sprintf( __( '%s status deleted.', 'order-status-for-woocommerce' ), $status_title ) . ' ' .
				sprintf( __( '%s order(s) updated (to %s).', 'order-status-for-woocommerce' ), $orders_updated, $fallback_status ) . '</p></div>';
		}
	}

	/**
	 * delete_status.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) add option to temporary remove emails (and possibly other triggers) on fallback status
	 * @todo    (feature) customizable "fallback status"
	 */
	function delete_status() {
		if ( isset( $_GET['wfwp_wcos_delete'] ) && current_user_can( 'manage_woocommerce' ) ) {
			if ( ! isset( $_GET['wfwp_wcos_nonce'] ) || ! wp_verify_nonce( $_GET['wfwp_wcos_nonce'], 'delete' ) ) {
				wp_die( __( 'Security check (nonce not verified).', 'order-status-for-woocommerce' ) );
			}
			$post_id         = $_GET['wfwp_wcos_delete'];
			$status          = new WFWP_WC_Shop_Order_Status( $post_id );
			wp_delete_post( $post_id, true );
			wp_safe_redirect( admin_url( 'edit.php?post_type=wfwp_wc_order_status&wfwp_wcos_delete_finished=' . $status->title ) );
			exit;
		}
		if ( isset( $_GET['wfwp_wcos_delete_fallback'] ) && isset( $_GET['wfwp_wcos_delete_fallback_status'] ) && current_user_can( 'manage_woocommerce' ) ) {
			if ( ! isset( $_GET['wfwp_wcos_nonce'] ) || ! wp_verify_nonce( $_GET['wfwp_wcos_nonce'], 'delete_fallback' ) ) {
				wp_die( __( 'Security check (nonce not verified).', 'order-status-for-woocommerce' ) );
			}
			$post_id         = $_GET['wfwp_wcos_delete_fallback'];
			$status          = new WFWP_WC_Shop_Order_Status( $post_id );
			$fallback_status = $_GET['wfwp_wcos_delete_fallback_status'];
			$orders          = wc_get_orders( array( 'limit' => -1, 'status' => $status->slug, 'type' => 'shop_order' ) );
			foreach ( $orders as $order ) {
				$order->update_status( $fallback_status, sprintf( __( 'Custom order status (%s) deleted.', 'order-status-for-woocommerce' ), $status->title, $fallback_status ) );
			}
			wp_delete_post( $post_id, true );
			wp_safe_redirect( admin_url( 'edit.php?post_type=wfwp_wc_order_status&wfwp_wcos_delete_fallback_finished=' . $status->title .
				'&wfwp_wcos_delete_fallback_orders_updated=' . count( $orders ) . '&wfwp_wcos_delete_fallback_status=' . $fallback_status ) );
			exit;
		}
	}

	/**
	 * on_insert_post_data.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (fix) are there any issues with same title slugs?
	 * @todo    (dev) re-check: publish at once (remove other post statuses)
	 * @todo    (dev) delete draft on invalid title (i.e., starts with number)?
	 */
	function on_insert_post_data( $data, $postarr ) {
		if ( 'wfwp_wc_order_status' === $data['post_type'] ) {
			$data['post_name'] = substr( $data['post_name'], 0, 17 );
			if ( ! empty( $data['post_name'] ) ) {
				$data['post_status'] = 'publish';
			}
		}
		return $data;
	}

	/**
	 * hide_default_options.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (fix) re-do quick edit, trash, bulk edit, bulk move to trash (see https://wordpress.stackexchange.com/questions/295169/remove-trash-delete-option-for-custom-post-type-taxonomy)
	 * @todo    (dev) remove "Move to Trash" / remove "Visibility" / remove "Publish" - i.e., use PHP instead of CSS
	 * @todo    (dev) maybe enqueue css file instead?
	 */
	function hide_default_options() {
		echo '<style>
			.post-type-wfwp_wc_order_status #normal-sortables,
			.post-type-wfwp_wc_order_status .misc-pub-post-status,
			.post-type-wfwp_wc_order_status .misc-pub-curtime,
			.post-type-wfwp_wc_order_status #visibility,
			.post-type-wfwp_wc_order_status #delete-action,
			.post-type-wfwp_wc_order_status div.row-actions,
			.post-type-wfwp_wc_order_status div.bulkactions {
				display: none;
			}
		</style>';
	}

	/**
	 * add_menu_link.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_menu_link() {
		add_submenu_page(
			'woocommerce',
			__( 'Order Status', 'order-status-for-woocommerce' ),
			__( 'Order Status', 'order-status-for-woocommerce' ),
			'manage_woocommerce',
			'edit.php?post_type=wfwp_wc_order_status'
		);
	}

	/**
	 * add_meta_box.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_meta_box() {
		foreach ( $this->get_options() as $section_id => $section ) {
			add_meta_box(
				'wfwp-wc-order-status-data-' . $section_id,
				$section['title'],
				array( $this, 'meta_box_callback' ),
				'wfwp_wc_order_status',
				( isset( $section['context'] ) ? $section['context'] : 'advanced' ),
				'default',
				array( 'id' => $section_id, 'section' => $section )
			);
		}
	}

	/**
	 * meta_box_callback.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) better solution for `__wfwp_wcos_post_id__` (why not `get_the_ID()`?) and `%slug%`
	 * @todo    (dev) nonce
	 * @todo    (dev) code refactoring
	 * @todo    (dev) `wc_help_tip`
	 */
	function meta_box_callback( $post, $metabox ) {
		$post_id = $post->ID;
		$html = '';
		if ( ! empty( $metabox['args']['section']['desc'] ) ) {
			$html .= '<p><em>' .  str_replace(
				array( '%slug%', '__wfwp_wcos_post_id__' ),
				array( ( ! empty( $post->post_name ) ? $post->post_name : sanitize_title( get_the_title() ) ), get_the_ID() ),
				$metabox['args']['section']['desc'] ) . '</em></p>';
		}
		$options = $this->get_options( $post_id );
		if ( ! empty( $options[ $metabox['args']['id'] ]['options'] ) ) {
			$html .= '<table class="widefat striped">';
			foreach ( $options[ $metabox['args']['id'] ]['options'] as $option ) {
				if ( 'title' === $option['type'] ) {
					$html .= '<tr>';
					$html .= '<th colspan="3" style="text-align:left;font-weight:bold;">' . $option['title'] . '</th>';
					$html .= '</tr>';
				} else {
					// Option value
					$meta_name = '_' . $option['id'];
					if ( 'slug' === $option['id'] ) {
						$option_value = 'wc-' . ( ! empty( $post->post_name ) ? $post->post_name : sanitize_title( get_the_title() ) );
					} elseif ( get_post_meta( $post_id, $meta_name ) ) {
						$option_value = get_post_meta( $post_id, $meta_name, true );
					} else {
						$option_value = ( isset( $option['default'] ) ? $option['default'] : '' );
					}
					// Custom attributes, CSS, input ending
					$css               = ( isset( $option['css'] ) ) ? $option['css']  : '';
					$input_ending      = '';
					$custom_attributes = '';
					if ( ! empty( $option['readonly'] ) ) {
						if ( ! isset( $option['custom_attributes'] ) ) {
							$option['custom_attributes'] = '';
						}
						$option['custom_attributes'] .= ' readonly';
					}
					if ( 'select' === $option['type'] ) {
						if ( isset( $option['multiple'] ) ) {
							$custom_attributes = ' multiple';
							$option_name       = $option['id'] . '[]';
						} else {
							$option_name       = $option['id'];
						}
						if ( isset( $option['custom_attributes'] ) ) {
							$custom_attributes .= ' ' . $option['custom_attributes'];
						}
						$select_options = '';
						foreach ( $option['options'] as $select_option_key => $select_option_value ) {
							$selected = '';
							if ( is_array( $option_value ) ) {
								foreach ( $option_value as $single_option_value ) {
									if ( '' != ( $selected = selected( $single_option_value, $select_option_key, false ) ) ) {
										break;
									}
								}
							} else {
								$selected = selected( $option_value, $select_option_key, false );
							}
							$select_options .= '<option value="' . $select_option_key . '" ' . $selected . '>' . $select_option_value . '</option>';
						}
					} elseif ( 'textarea' !== $option['type'] ) {
						$input_ending = ' id="' . $option['id'] . '" name="' . $option['id'] . '" value="' . $option_value . '">';
						if ( isset( $option['custom_attributes'] ) ) {
							$input_ending = ' ' . $option['custom_attributes'] . $input_ending;
						}
						if ( isset( $option['placeholder'] ) ) {
							$input_ending = ' placeholder="' . $option['placeholder'] . '"' . $input_ending;
						}
					}
					// Field by type
					switch ( $option['type'] ) {
						case 'price':
							$field_html = '<input style="' . $css . '" class="short wc_input_price" type="number" step="0.0001"' . $input_ending;
							break;
						case 'date':
							$field_html = '<input style="' . $css . '" class="input-text" display="date" type="text"' . $input_ending;
							break;
						case 'textarea':
							$field_html = '<textarea style="' . $css . '" id="' . $option['id'] . '" name="' . $option['id'] . '">' .
								$option_value . '</textarea>';
							break;
						case 'select':
							$field_html = '<select' . $custom_attributes . ' style="' . $css . '" id="' . $option['id'] . '" name="' .
								$option_name . '">' . $select_options . '</select>';
							break;
						default:
							$field_html = '<input style="' . $css . '" class="short" type="' . $option['type'] . '"' . $input_ending;
							break;
					}
					$html .= '<tr>';
					// Title
					if ( ! empty( $option['title'] ) ) {
						if ( 'order_list_icon' === $option['id'] ) {
							$option['title'] = str_replace( '%slug%', ( ! empty( $post->post_name ) ? $post->post_name : sanitize_title( get_the_title() ) ), $option['title'] );
						}
						$maybe_desc_tip = ( ! empty( $option['desc_tip'] ) ? ' <em style="font-size:small;">(' . $option['desc_tip'] . ')</em>' : '' );
						$html .= '<th style="text-align:left;width:25%;">' . $option['title'] . $maybe_desc_tip . '</th>';
					}
					// Desc
					if ( ! empty( $option['desc'] ) ) {
						$html .= '<td style="font-style:italic;width:25%;">' . $option['desc'] . '</td>';
					}
					$html .= '<td>' . $field_html . '</td>';
					$html .= '</tr>';
				}
			}
			$html .= '</table>';
		}
		echo $html;
	}

	/**
	 * save_meta_box.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) [!] sanitize
	 */
	function save_meta_box( $post_id, $post ) {
		foreach ( $this->get_options( $post_id ) as $section_id => $section ) {
			foreach ( $section['options'] as $option ) {
				if ( 'title' === $option['type'] || ! empty( $option['readonly'] ) ) {
					continue;
				}
				update_post_meta( $post_id, '_' . $option['id'], ( isset( $_POST[ $option['id'] ] ) ? $_POST[ $option['id'] ] : $option['default'] ) );
			}
		}
	}

}

endif;

return new WFWP_WC_Order_Status_Admin();

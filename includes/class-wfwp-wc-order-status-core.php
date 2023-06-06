<?php
/**
 * Order Status for WooCommerce - Core Class
 *
 * @version 1.3.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFWP_WC_Order_Status_Core' ) ) :

class WFWP_WC_Order_Status_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.1.1
	 * @since   1.0.0
	 *
	 * @todo    (dev) customizable filters priorities
	 * @todo    (dev) add "reset settings" button
	 * @todo    (dev) maybe compatibility with WP < 4.7 (bulk actions)
	 * @todo    (dev) maybe compatibility with WC < 3.0 (order properties: ID etc.)
	 * @todo    (dev) maybe compatibility with WC < 3.0 ? (icons css)
	 * @todo    [!] (feature) reduce/increase stock: `wc_maybe_reduce_stock_levels` and `wc_maybe_increase_stock_levels`
	 * @todo    (feature) "status rules"
	 * @todo    (feature) "default order status"
	 * @todo    [!] (feature) "Processing" and "Complete" action buttons (list & preview) (see `woocommerce_admin_order_actions`)
	 * @todo    (feature) "delete all custom statuses" and "delete all custom statuses with fallback" button
	 */
	function __construct() {

		// WP stuff
		add_action( 'init', array( $this, 'create_order_status_post_type' ), 9 );
		add_action( 'init', array( $this, 'register_custom_post_statuses' ), 9 );

		// Main WC filter
		add_filter( 'wc_order_statuses', array( $this, 'add_custom_statuses_to_filter' ), PHP_INT_MAX );

		// Styling
		add_action( 'admin_head', array( $this, 'add_custom_status_column_css' ), 10 );

		// Admin
		add_filter( 'bulk_actions-edit-shop_order', array( $this, 'bulk_actions' ), PHP_INT_MAX );
		add_filter( 'woocommerce_reports_order_statuses', array( $this, 'reports' ), PHP_INT_MAX );

		// Action buttons
		add_filter( 'woocommerce_admin_order_actions', array( $this, 'order_list_actions' ), PHP_INT_MAX, 2 );
		add_action( 'admin_head', array( $this, 'add_custom_status_actions_css' ), 10 );
		add_filter( 'woocommerce_admin_order_preview_actions', array( $this, 'order_preview_actions' ), PHP_INT_MAX, 2 );

		// Order
		add_filter( 'wc_order_is_editable', array( $this, 'order_editable' ), PHP_INT_MAX, 2 );
		add_filter( 'woocommerce_order_is_paid_statuses', array( $this, 'order_paid' ), PHP_INT_MAX );

		// Shortcodes
		add_shortcode( 'alg_wc_os_order_meta', array( $this, 'order_meta' ) );

		// "Core loaded" action
		do_action( 'wfwp_wc_order_status_core_loaded', $this );

	}

	/**
	 * order_meta.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 *
	 * @todo    [!] (dev) move all shortcodes to a separate file/class
	 * @todo    [!] (feature) add more shortcodes?
	 */
	function order_meta( $atts, $content = '' ) {
		if ( ! empty( $this->shortcode_data['order_id'] ) && isset( $atts['key'] ) ) {
			$meta = get_post_meta( $this->shortcode_data['order_id'], $atts['key'], true );
			if ( isset( $atts['sub_key'] ) ) {
				$sub_keys = explode( ',', $atts['sub_key'] );
				foreach ( $sub_keys as $sub_key ) {
					if ( is_array( $meta ) && isset( $meta[ $sub_key ] ) ) {
						$meta = $meta[ $sub_key ];
					} else {
						return '';
					}
				}
			}
			return $this->output_shortcode( $meta, $atts );
		}
		return '';
	}

	/**
	 * output_shortcode.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 *
	 * @todo    [!] (feature) more common atts, e.g., add, multiply, format, find/replace, strip_tags, any_func, etc.
	 */
	function output_shortcode( $value, $atts ) {
		return ( '' !== $value ? ( ( isset( $atts['before'] ) ? $atts['before'] : '' ) . $value . ( isset( $atts['after'] ) ? $atts['after'] : '' ) ) : '' );
	}

	/**
	 * order_paid.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function order_paid( $statuses ) {
		foreach ( $this->get_statuses() as $status ) {
			if ( $status->is_override() ) {
				continue;
			}
			if ( $status->is_order_paid ) {
				$statuses[] = $status->slug;
			}
		}
		return $statuses;
	}

	/**
	 * order_editable.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function order_editable( $is_editable, $order ) {
		$order_status = $order->get_status();
		$statuses     = $this->get_statuses();
		return ( ! empty( $statuses[ $order_status ] ) ? $statuses[ $order_status ]->is_order_editable : $is_editable );
	}

	/**
	 * order_preview_actions.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function order_preview_actions( $actions, $order ) {
		$status_actions = array();
		foreach ( $this->get_statuses() as $status ) {
			if ( $status->is_override() ) {
				continue;
			}
			if ( $status->is_order_preview_action ) {
				if ( ! $order->has_status( array( $status->slug ) ) ) {
					$status_actions[ $status->slug ] = array(
						'url'    => $this->get_status_action_url( $status->slug, $order->get_id() ),
						'name'   => $status->title,
						'title'  => sprintf( __( 'Change order status to %s', 'order-status-for-woocommerce' ), $custom_order_status ),
						'action' => $status->slug,
					);
				}
			}
		}
		if ( $status_actions ) {
			if ( ! empty( $actions['status']['actions'] ) && is_array( $actions['status']['actions'] ) ) {
				$actions['status']['actions'] = array_merge( $actions['status']['actions'], $status_actions );
			} else {
				$actions['status'] = array(
					'group'   => __( 'Change status: ', 'woocommerce' ),
					'actions' => $status_actions,
				);
			}
		}
		return $actions;
	}

	/**
	 * add_custom_status_actions_css.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function add_custom_status_actions_css() {
		$style = '';
		foreach ( $this->get_statuses() as $status ) {
			if ( $status->is_override() ) {
				continue;
			}
			$style .= '.view.' . $status->slug . '::after {
				font-family: WooCommerce !important;
				color: '            . $status->order_list_icon_color    . ' !important;
				background-color: ' . $status->order_list_icon_bg_color . ' !important;
				content: "\\'       . $status->order_list_icon         . '" !important;
			}' . PHP_EOL;
		}
		if ( ! empty( $style ) ) {
			echo '<style>' . $style . '</style>';
		}
	}

	/**
	 * order_list_actions.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function order_list_actions( $actions, $order ) {
		foreach ( $this->get_statuses() as $status ) {
			if ( $status->is_override() ) {
				continue;
			}
			if ( $status->is_order_list_action ) {
				if ( ! $order->has_status( array( $status->slug ) ) ) {
					$actions[ $status->slug ] = array(
						'url'    => $this->get_status_action_url( $status->slug, $order->get_id() ),
						'name'   => $status->title,
						'action' => "view " . $status->slug,
					);
				}
			}
		}
		return $actions;
	}

	/**
	 * reports.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function reports( $statuses ) {
		foreach ( $this->get_statuses() as $status ) {
			if ( $status->is_override() ) {
				continue;
			}
			if ( $status->is_report ) {
				$statuses[] = $status->slug;
			}
		}
		return $statuses;
	}

	/**
	 * bulk_actions.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @see     https://make.wordpress.org/core/2016/10/04/custom-bulk-actions/
	 */
	function bulk_actions( $bulk_actions ) {
		foreach ( $this->get_statuses() as $status ) {
			if ( $status->is_bulk_action ) {
				$bulk_actions[ 'mark_' . $status->slug ] = sprintf( __( 'Change status to %s', 'order-status-for-woocommerce' ), $status->title );
			} elseif ( $status->is_override() && isset( $bulk_actions[ 'mark_' . $status->slug ] ) ) {
				unset( $bulk_actions[ 'mark_' . $status->slug ] );
			}
		}
		return $bulk_actions;
	}

	/**
	 * add_custom_status_column_css.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_status_column_css() {
		$style = '';
		foreach ( $this->get_statuses() as $status ) {
			$style .= 'mark.order-status.status-' . $status->slug . ' {
				color: '            . $status->text_color . ' !important;
				background-color: ' . $status->bg_color   . ' !important;
			}' . PHP_EOL;
		}
		if ( ! empty( $style ) ) {
			echo '<style>' . $style . '</style>';
		}
	}

	/**
	 * add_custom_statuses_to_filter.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_statuses_to_filter( $order_statuses ) {
		foreach ( $this->get_statuses() as $status ) {
			$order_statuses[ $status->wc_slug ] = $status->title;
		}
		return $order_statuses;
	}

	/**
	 * register_custom_post_statuses.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @see     https://developer.wordpress.org/reference/functions/register_post_status/
	 *
	 * @todo    (dev) `$status->is_override()`?
	 */
	function register_custom_post_statuses() {
		foreach ( $this->get_statuses() as $status ) {
			register_post_status( $status->wc_slug, array(
				'label'                     => $status->title,
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( $status->title . ' <span class="count">(%s)</span>', $status->title . ' <span class="count">(%s)</span>' ),
			) );
		}
	}

	/**
	 * create_order_status_post_type.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @see     https://developer.wordpress.org/reference/functions/register_post_type/
	 *
	 * @todo    (dev) re-check `capabilities` and `capability_type`
	 */
	function create_order_status_post_type() {
		register_post_type( 'wfwp_wc_order_status',
			array(
				'labels'             => array(
					'name'                    => _x( 'Statuses', 'post type general name', 'order-status-for-woocommerce' ),
					'singular_name'           => _x( 'Status', 'post type singular name', 'order-status-for-woocommerce' ),
					'menu_name'               => _x( 'Statuses', 'admin menu', 'order-status-for-woocommerce' ),
					'name_admin_bar'          => _x( 'Status', 'add new on admin bar', 'order-status-for-woocommerce' ),
					'add_new'                 => _x( 'Add New', 'status', 'order-status-for-woocommerce' ),
					'add_new_item'            => __( 'Add New Status', 'order-status-for-woocommerce' ),
					'new_item'                => __( 'New Status', 'order-status-for-woocommerce' ),
					'edit_item'               => __( 'Edit Status', 'order-status-for-woocommerce' ),
					'view_item'               => __( 'View Status', 'order-status-for-woocommerce' ),
					'all_items'               => __( 'All Statuses', 'order-status-for-woocommerce' ),
					'search_items'            => __( 'Search Statuses', 'order-status-for-woocommerce' ),
					'parent_item_colon'       => __( 'Parent Statuses:', 'order-status-for-woocommerce' ),
					'not_found'               => __( 'No statuses found.', 'order-status-for-woocommerce' ),
					'not_found_in_trash'      => __( 'No statuses found in Trash.', 'order-status-for-woocommerce' ),
				),
				'description'        => __( 'WooCommerce custom order status', 'order-status-for-woocommerce' ),
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => false,
				'query_var'          => false,
				'map_meta_cap'       => true,
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'revisions' ),
			)
		);
	}

	/**
	 * get_statuses.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) add and sort by `priority` option (instead if `title`)
	 */
	function get_statuses() {
		if ( isset( $this->statuses ) ) {
			return $this->statuses;
		}
		$this->statuses = array();
		$args = array(
			'post_type'      => 'wfwp_wc_order_status',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'fields'         => 'ids',
		);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			foreach ( $loop->posts as $post_id ) {
				$status = new WFWP_WC_Shop_Order_Status( $post_id );
				$this->statuses[ $status->slug ] = $status;
			}
		}
		return $this->statuses;
	}

	/**
	 * get_status_action_url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_status_action_url( $status, $order_id ) {
		return wp_nonce_url(
			admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=' . $status . '&order_id=' . $order_id ),
			'woocommerce-mark-order-status'
		);
	}

}

endif;

return new WFWP_WC_Order_Status_Core();

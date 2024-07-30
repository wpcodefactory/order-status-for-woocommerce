=== Custom Order Status for WooCommerce ===
Contributors: wpcodefactory, algoritmika, anbinder, karzin, omardabbas, kousikmukherjeeli
Tags: woocommerce, status, order status, woo commerce
Requires at least: 4.7
Tested up to: 6.6
Stable tag: 1.4.7
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Manage order statuses in WooCommerce. Beautifully.

== Description ==

**Custom Order Status for WooCommerce** plugin lets you add and manage **default & custom order statuses** in WooCommerce.

### &#9989; Main Features ###

You can add any number of statuses and for *each status* you can set:

* **Styling options** - status text color, background color.
* **General options** - add status to order bulk actions, add status to reports.
* **Action buttons options** - icon, icon color & background color, add status to order list action buttons and/or admin order preview action buttons.
* **Order options** - is order editable, is order paid, download permissions, etc.

You can also change **order status sorting**, including the default WooCommerce order statuses.

### &#127942; Premium Version ###

[Order Status for WooCommerce Pro](https://wpfactory.com/item/order-status-for-woocommerce/) version also has an option to send **emails** on custom order status change. For each custom status email you can set: email address, subject, heading and content.

### &#128472; Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/order-status-for-woocommerce/).

### &#8505; More ###

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Order Status".

== Changelog ==

= 1.4.7 - 30/07/2024 =
* WC tested up to: 9.1.
* Tested up to: 6.6.

= 1.4.6 - 07/02/2024 =
* Dev - PHP 8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.
* WC tested up to: 8.5.

= 1.4.5 - 20/11/2023 =
* WC tested up to: 8.3.
* Tested up to: 6.4.
* Plugin title updated.

= 1.4.4 - 01/11/2023 =
* Dev - Order Options - "Download permissions" option added (defaults to `no`).
* Dev - Minor code refactoring.
* WC tested up to: 8.2.

= 1.4.3 - 03/10/2023 =
* Fix - Declaring HPOS compatibility for the free plugin version, even if the Pro version is activated.

= 1.4.2 - 26/09/2023 =
* WC tested up to: 8.1.
* Tested up to: 6.3.
* Plugin icon, banner updated.

= 1.4.1 - 18/06/2023 =
* WC tested up to: 7.8.

= 1.4.0 - 09/06/2023 =
* Dev - Order Options - 'Set order "date paid" on status update' option added.
* Dev - "WooCommerce > Settings > Order Status" settings section added for global options.
* Dev - "Order status sorting" option added.
* Dev – "High-Performance Order Storage (HPOS)" compatibility.
* Dev - Admin settings descriptions updated.

= 1.3.0 - 06/06/2023 =
* Dev - Default WooCommerce status class property added (`is_override`).
* Dev - Emails - "Wrap in WooCommerce template" option added (defaults to `yes`).
* Dev - Emails - `{completed_order}` placeholder added.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.
* WC tested up to: 7.7.
* Tested up to: 6.2.

= 1.2.0 - 21/10/2022 =
* Dev - Admin menu priority lowered from `PHP_INT_MAX` to `100`.
* Deploy script added.
* Readme.txt updated.
* WC tested up to: 7.0.

= 1.1.1 - 26/05/2022 =
* Dev - Priorities lowered from `10` to `9` for `load_plugin_textdomain`, `register_post_type` and `register_post_status` (all in `init` action).
* Tested up to: 6.0.
* WC tested up to: 6.5.

= 1.1.0 - 10/02/2022 =
* Dev - Shortcodes - `[alg_wc_os_order_meta]` shortcode added.
* Dev - Emails - Placeholders (heading/content) - `{order_billing_first_name}` and `{order_billing_last_name}` placeholders added.
* Dev - Emails - Sending emails via WooCommerce `WC_Email` class now. Old method can be still enabled via the `wfwp_wc_order_status_wc_email` filter.
* Dev - Emails - `wfwp_wc_order_status_email_content` filter added.
* Dev - JS minified.
* Dev - Admin settings descriptions updated.
* Dev - Localization - `load_plugin_textdomain()` function moved to the to `init` action.
* Dev - Plugin is initialized on `plugins_loaded` action now.
* Dev - Code refactoring.
* Tested up to: 5.9.
* WC tested up to: 6.2.

= 1.0.0 - 19/12/2018 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.

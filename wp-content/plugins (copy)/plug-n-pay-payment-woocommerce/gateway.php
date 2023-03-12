<?php
/*
Plugin Name: WooCommerce Plug'n Pay Gateway
Plugin URI: https://pledgedplugins.com/products/plug-n-pay-payment-gateway-woocommerce/
Description: A payment gateway for Plug'n Pay. A Plug'n Pay account and a server with cURL, SSL support, and a valid SSL certificate is required (for security reasons) for this gateway to function. Requires WC 3.0.0+
Version: 4.0.5
Author: Pledged Plugins
Author URI: https://pledgedplugins.com
Text Domain: wc-plugnpay
Domain Path: /languages
WC requires at least: 3.0.0
WC tested up to: 3.7

	Copyright: © Pledged Plugins.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plug'n Pay class which sets the gateway up for us
 */
class WC_PlugnPay {

	/**
	 * Constructor
	 */
	public function __construct() {
		define( 'WC_PLUGNPAY_VERSION', '4.0.5' );
		define( 'WC_PLUGNPAY_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
		define( 'WC_PLUGNPAY_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'WC_PLUGNPAY_MAIN_FILE', __FILE__ );

		// required files
		require_once( 'includes/class-wc-gateway-plugnpay-logger.php' );
		require_once( 'updates/updates.php' );

		// Actions
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
		add_filter( 'woocommerce_payment_gateways', array( $this, 'register_gateway' ) );
		add_action( 'woocommerce_order_status_on-hold_to_processing', array( $this, 'capture_payment' ) );
		add_action( 'woocommerce_order_status_on-hold_to_completed', array( $this, 'capture_payment' ) );
		add_action( 'woocommerce_order_status_on-hold_to_cancelled', array( $this, 'cancel_payment' ) );
		add_action( 'woocommerce_order_status_on-hold_to_refunded', array( $this, 'cancel_payment' ) );
	}

	/**
	 * Add relevant links to plugins page
	 * @param  array $links
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=plugnpay' ) . '">' . __( 'Settings', 'wc-plugnpay' ) . '</a>',
			'<a href="https://pledgedplugins.com/support/" target="_blank">' . __( 'Support', 'wc-plugnpay' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}

	/**
	 * Init localisations and files
	 */
	public function init() {
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}

		// Includes
		if ( is_admin() ) {
			require_once( 'includes/class-wc-plugnpay-privacy.php' );
		}

		include_once( 'includes/class-wc-gateway-plugnpay.php' );

		$this->load_plugin_textdomain();
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if
	 * the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/wc-plugnpay/wc-plugnpay-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/wc-plugnpay-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wc-plugnpay' );
		$dir    = trailingslashit( WP_LANG_DIR );

		load_textdomain( 'wc-plugnpay', $dir . 'wc-plugnpay/wc-plugnpay-' . $locale . '.mo' );
		load_plugin_textdomain( 'wc-plugnpay', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Register the gateway for use
	 */
	public function register_gateway( $methods ) {
		$methods[] = 'WC_Gateway_PlugnPay';
		return $methods;
	}

	/**
	 * Capture payment when the order is changed from on-hold to complete or processing
	 *
	 * @param  int $order_id
	 */
	public function capture_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( $order->get_payment_method() == 'plugnpay' ) {
			$charge   = $order->get_meta( '_plugnpay_charge_id' );
			$captured = $order->get_meta( '_plugnpay_charge_captured' );

			if ( $charge && $captured == 'no' ) {
				$gateway = new WC_Gateway_PlugnPay();
				$args = array(
					'amount'		=> $order->get_total(),
					'trans_id'		=> $order->get_transaction_id(),
					'type' 			=> 'capture',
				);
 			die('ReachedHereAsell');
				$response = $gateway->plugnpay_request( $args );

				if ( $response->error || $response->declined ) {
					$order->add_order_note( __( 'Unable to capture charge!', 'wc-plugnpay' ) . ' ' . $response->error_message );
				} else {
					$complete_message = sprintf( __( 'Plug\'n Pay charge complete (Charge ID: %s)', 'wc-plugnpay' ), $response->transaction_id );
					$order->add_order_note( $complete_message );

					$order->update_meta_data( '_plugnpay_charge_captured', 'yes' );
					$order->update_meta_data( 'Plug\'n Pay Payment ID', $response->transaction_id );

					$order->set_transaction_id( $response->transaction_id );
					$order->save();
				}
			}
		}
	}

	/**
	 * Cancel pre-auth on refund/cancellation
	 *
	 * @param  int $order_id
	 */
	public function cancel_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( $order->get_payment_method() == 'plugnpay' ) {
			$charge   = $order->get_meta( '_plugnpay_charge_id' );

			if ( $charge ) {
				$gateway = new WC_Gateway_PlugnPay();
				$args = array(
					'amount'		=> $order->get_total(),
					'trans_id'		=> $order->get_transaction_id(),
					'type' 			=> 'cancel',
				);
				$response = $gateway->plugnpay_request( $args );

				if ( $response->error || $response->declined ) {
					$order->add_order_note( __( 'Unable to refund charge!', 'wc-plugnpay' ) . ' ' . $response->error_message );
				} else {
					$cancel_message = sprintf( __( 'Plug\'n Pay charge refunded (Charge ID: %s)', 'wc-plugnpay' ), $response->transaction_id );
					$order->add_order_note( $cancel_message );
					$order->delete_meta_data( '_plugnpay_charge_captured' );
					$order->delete_meta_data( '_plugnpay_charge_id' );
					$order->save();
				}
			}
		}
	}

}
new WC_PlugnPay();
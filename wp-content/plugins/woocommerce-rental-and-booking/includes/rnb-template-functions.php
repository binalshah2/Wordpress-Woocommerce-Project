<?php
/**
 * WooCommerce Template
 *
 * Functions for the templating system.
 *
 * @author   WooThemes
 * @category Core
 * @package  WooCommerce/Functions
 * @version  2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( ! function_exists( 'rnb_price_flip_box' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_price_flip_box() {
		rnb_get_template( 'rnb/global/price-flip-box.php' );
	}
}

if ( ! function_exists( 'rnb_validation_notice' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_validation_notice() {
		rnb_get_template( 'rnb/global/rnb-notice.php' );
	}
}


if ( ! function_exists( 'rnb_select_inventory' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_select_inventory() {
		rnb_get_template( 'rnb/booking-content/select-inventory.php' );
	}
}





if ( ! function_exists( 'rnb_pickup_locations' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_pickup_locations() {
		rnb_get_template( 'rnb/booking-content/pickup-locations.php' );
	}
}



if ( ! function_exists( 'rnb_return_locations' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_return_locations() {
		rnb_get_template( 'rnb/booking-content/return-locations.php' );
	}
}



if ( ! function_exists( 'rnb_pickup_datetimes' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_pickup_datetimes() {
		rnb_get_template( 'rnb/booking-content/pickup-datetimes.php' );
	}
}



if ( ! function_exists( 'rnb_return_datetimes' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_return_datetimes() {
		rnb_get_template( 'rnb/booking-content/return-datetimes.php' );
	}
}



if ( ! function_exists( 'rnb_quantity' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_quantity() {
		rnb_get_template( 'rnb/booking-content/quantity.php' );
	}
}



if ( ! function_exists( 'rnb_payable_categories' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_payable_categories() {
		rnb_get_template( 'rnb/booking-content/rnb-categories.php' );
	}
}


if ( ! function_exists( 'rnb_payable_resources' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_payable_resources() {
		rnb_get_template( 'rnb/booking-content/payable-resources.php' );
	}
}



if ( ! function_exists( 'rnb_payable_persons' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_payable_persons() {
		rnb_get_template( 'rnb/booking-content/payable-persons.php' );
	}
}

if ( ! function_exists( 'rnb_payable_deposits' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_payable_deposits() {
		rnb_get_template( 'rnb/booking-content/payable-deposits.php' );
	}
}



if ( ! function_exists( 'rnb_booking_summary' ) ) {
	function rnb_booking_summary() {
		rnb_get_template( 'rnb/booking-content/booking-summary.php' );
	}
}

if ( ! function_exists( 'rnb_booking_summary_two' ) ) {
	function rnb_booking_summary_two() {
		rnb_get_template( 'rnb/booking-content/booking-summary-two.php' );
	}
}


if ( ! function_exists( 'rnb_direct_booking' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_direct_booking() {
		rnb_get_template( 'rnb/booking-content/direct-booking.php' );
	}
}




if ( ! function_exists( 'rnb_request_quote' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function rnb_request_quote() {
		rnb_get_template( 'rnb/booking-content/request-quote.php' );
	}
}



/**
 * Display meta data belonging to an item.
 * @param  array $item
 */
function display_item_meta( $item ) {


	$product   = $this->get_product_from_item( $item );


	$item_meta = new WC_Order_Item_Meta( $item, $product );
	$item_meta->display();
}



if ( ! function_exists( 'rnb_modal_booking_func' ) ) {

	/**
	 * Output of the modal
	 *
	 */
	function rnb_modal_booking_func() {
		rnb_get_template( 'rnb/booking-content/booking-modal.php' );
	}
}






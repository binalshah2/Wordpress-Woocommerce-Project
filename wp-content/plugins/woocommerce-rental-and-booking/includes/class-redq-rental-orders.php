<?php

/**
*
*/
class Rnb_Orders{

	public function __construct() {
		add_filter( 'woocommerce_display_item_meta', array( $this , 'redq_rental_order_items_meta_display'), 10, 3  );
	}


	/**
	* Output of order meta in emails
	*
	* @version 1.0.0
	* @since 2.0.4
	*/
	public function redq_rental_order_items_meta_display( $html, $item, $args ) {
		$strings = array();
		$html    = '';
		$args    = wp_parse_args( $args, array(
			'before'    => '<ul class="wc-item-meta"><li>',
			'after'		=> '</li></ul>',
			'separator'	=> '</li><li>',
			'echo'		=> true,
			'autop'		=> false,
		) );

		foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
			if($meta->key !== 'pickup_hidden_datetime' && $meta->key !== 'return_hidden_datetime' && $meta->key !== 'return_hidden_days' && $meta->key !== 'redq_google_cal_sync_id' && $meta->key !== 'booking_inventory' ) :
				$value = $args['autop'] ? wp_kses_post( wpautop( make_clickable( $meta->display_value ) ) ) : wp_kses_post( make_clickable( $meta->display_value ) );
				$strings[] = '<strong class="wc-item-meta-label">' . wp_kses_post( $meta->display_key ) . ':</strong> ' . $value;
			endif;
		}

		if ( $strings ) {
			$html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
		}

		return $html;
	}


}


new Rnb_Orders();




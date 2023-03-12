<?php
/**
 * RNB Global Accessable Functions
 *
 * Functions for order specific things.
 *
 * @author 		RedQTeam
 * @category 	Core
 * @package 	RNB Global Accessable Functions
 * @version     3.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Calculate Block Dates
 *
 * @since 1.0.0
 * @return object
 */
function calculate_block_dates($post_id=null){

	if($post_id === null){
		$post_id = get_the_ID();
	}

	$block_dates = array();
	$block_times = array();
	$block_dates_final = array();

    $conditional_data = reddq_rental_get_settings( $post_id, 'conditions' );
    $conditional_data = $conditional_data['conditions'];

    $output_date_format  = $conditional_data['date_format'];
    $rental_block = $conditional_data['blockable'];

	$rental_availability = get_post_meta( $post_id, 'redq_block_dates_and_times', true );

	$flag = 0;
	$time_flag = 0;
	$first = array();
	$first_time = array();
	if(isset($rental_block) && $rental_block === 'yes'){
		if(isset($rental_availability) && !empty($rental_availability)){
			foreach ($rental_availability as $key => $value) {
				if($flag === 0){
					$first = $value['only_block_dates'];
					$flag = 1;
				}
				$block_dates = array_intersect($first, $value['only_block_dates']);
			}
		}
	}

	$result = array();

	if(isset($block_dates) && !empty($block_dates)){
		foreach ($block_dates as $key => $value) {
			$result[] = convert_to_output_format($value, $output_date_format);
		}
    }
    

    global $wpdb;

    $product_id = 26;

    $sql = "SELECT availability.pickup_datetime, availability.return_datetime FROM {$wpdb->prefix}rnb_availability as availability WHERE availability.product_id={$product_id}";            
    $dates = $wpdb->get_results($sql, ARRAY_A); 
	

	return $result;
}

    
/**
 * rnb_update_product_price_html
 *
 * @param  mixed $price_html
 * @param  mixed $product
 *
 * @return string
 */
function rnb_update_product_price_html( $price_html, $product ) {    
    
    $product_type = $product->get_type();

    if(isset($product_type) && $product_type === 'redq_rental'){

        $product_id = $product->get_id();
        $price = 0;
        $prefix = '';
        $suffix = esc_html__('/ Per Day', 'redq-rental');
       
        $conditions = reddq_rental_get_settings($product_id, 'conditions');
        $conditions = $conditions['conditions'];
        $show_price = $conditions['show_price_type'];
        
        $inventory = get_post_meta( $product_id, '_redq_product_inventory', true ); 
        $inventory_id = !empty( $inventory ) ? $inventory[0] : null;        

        $pricing_data = redq_rental_get_pricing_data($inventory_id, $product_id);
        $day_pricing_type = isset($pricing_data['pricing_type']) ? $pricing_data['pricing_type'] : 'general_pricing';
        $hourly_pricing_type = isset($pricing_data['hourly_pricing_type']) ? $pricing_data['hourly_pricing_type'] : 'hourly_pricing';
        $pricing_type = $show_price === 'daily' ? $day_pricing_type : $hourly_pricing_type;

        switch ($pricing_type) {
            case 'general_pricing':
                $price = $pricing_data['general_pricing'];                
                break;
            case 'daily_pricing':
                $daily_pricing = $pricing_data['daily_pricing'];           
                $today = strtolower(date("l"));
                $price = isset( $daily_pricing[$today] ) ? $daily_pricing[$today] : 0;
                break;
            case 'monthly_pricing':
                $monthly_pricing = $pricing_data['monthly_pricing'];
                $current_month = strtolower(date("F"));
                $price = isset( $monthly_pricing[$current_month] ) ? $monthly_pricing[$current_month] : 0;          
                break;
            case 'days_range':
                $day_ranges_cost = $pricing_data['days_range'];
                $price = isset($day_ranges_cost[0]) ? $day_ranges_cost[0]['range_cost'] : 0;
                $prefix = esc_html__('From ', 'redq-rental');
                $suffix = esc_html__('', 'redq-rental');
                break;   
            case 'hourly_general':            
                $price = $pricing_data['hourly_general'];
                $suffix = esc_html__('/ Per Hour', 'redq-rental');
                break;  
            case 'hourly_range':
                $hourly_ranges_cost = $pricing_data['hourly_range'];
                $price = isset($hourly_ranges_cost[0]) ? $hourly_ranges_cost[0]['range_cost'] : 0;
                $prefix = esc_html__('From ', 'redq-rental');
                $suffix = esc_html__('', 'redq-rental');
                break;                     
            case 'flat_hours':               
                if($hourly_pricing_type === 'hourly_general'){
                    $price = $pricing_data['hourly_general'];
                    $suffix = esc_html__('/ Per Hour', 'redq-rental');
                }else{
                    $hourly_ranges_cost = $pricing_data['hourly_range'];
                    $price = isset($hourly_ranges_cost[0]) ? $hourly_ranges_cost[0]['range_cost'] : 0;
                    $prefix = esc_html__('From ', 'redq-rental');
                    $suffix = esc_html__('', 'redq-rental');
                }
                break; 
        }       
        
        $price_html = '<span class="amount"> '.$prefix.'&nbsp;'.wc_price($price).'&nbsp;'.$suffix.'</span>';
        return $price_html;
    }    
    
    return $price_html;
}
add_filter( 'woocommerce_get_price_html', 'rnb_update_product_price_html', 10, 2 );


/**
 * Localize all booking data
 *
 * @since 1.0.0
 * @return object
 */
function reqd_rental_availability_data($post_id=null){
    $rental_availability = get_post_meta( $post_id, 'redq_block_dates_and_times', true );
	return $rental_availability;
}


/**
 * Manage all Block Dates
 *
 * @since 1.0.0
 * @return object
 */
function manage_all_dates($start_dates, $end_dates , $choose_euro_format, $output_format , $step = '+1 day'){

    $dates = array();

    if($choose_euro_format === 'no'){
        $current = strtotime($start_dates);
        $last = strtotime($end_dates);
    }else{
        $current = strtotime(str_replace('/' , '.' , $start_dates));
        $last = strtotime(str_replace('/' , '.' , $end_dates));
    }

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }


    return $dates;
}


/**
 * Manage all Block Dates
 *
 * @since 1.0.0
 * @return object
 */
function get_plain_dates_ara($start_dates, $end_dates , $step = '+1 day'){

    $dates = array();
    $output_format = 'Y-m-d';
    $current = strtotime($start_dates);
    $last = strtotime($end_dates);

    while( $current <= $last ) {
        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}



/**
 * Convert Euro To Standard
 *
 * @since 1.0.0
 * @return Date Format 'Y-m-d'
 */
function convert_to_generalized_format( $date, $euro_format ){

    $output_format = 'Y-m-d';
    if($euro_format === 'no'){
        $input_date = strtotime($date);
    }else{
        $input_date = strtotime(str_replace('/' , '.' , $date));
    }
    $output_date = date($output_format, $input_date);

	return $output_date;
}


/**
 * Convert To Output Format
 *
 * @since 1.0.0
 * @return Date Format 'Y-m-d'
 */
function convert_to_output_format( $date, $output_format = 'Y-m-d' ){

    $input_date = strtotime($date);
    $output_date = date($output_format, $input_date);

    return $output_date;
}


/**
 * Manage all new Block Dates that are selected during blooking
 *
 * @version 5.0.7
 * @since 3.0.4
 * @return array
 */
function  rental_user_blocked_dates( $booked_dates_ara, $block_dates_times, $quantity ){ 
    for ($qty=1; $qty <= $quantity; $qty++) {
        foreach ($block_dates_times as $block_dates_time_key => $block_dates_time_value) {
            $matched_elements = array_intersect($block_dates_time_value['only_block_dates'], $booked_dates_ara);
            
            if( !empty($matched_elements) ) continue;

            if(empty($matched_elements)) {
                $length = sizeof($booked_dates_ara);
                $first = $booked_dates_ara[0];
                $last = $booked_dates_ara[$length-1];
                $booked_ara = array(
                    'pickup_date' => $first,
                    'return_date' => $last,
                    'inventory_id' => $block_dates_time_key
                );                
                return $booked_ara; 
            }
        }
    }    
    return '';
}




/**
 * Manage all Block Dates that are selected during blooking
 *
 * @since 3.0.4
 * @return array
 */

function rental_iventory_selected_attributes( $tax_indentifier, $taxonomoy, $unique_model_name ){

	$selected_terms = array();

	if( isset($tax_indentifier) && !empty($tax_indentifier)) {
		foreach ($tax_indentifier as $taxi_key => $taxi_value) {
			if($taxi_value['title'] === $unique_model_name){
				$args = array(
							'orderby'           => 'name',
							'order'             => 'ASC',
							'fields'      => 'all',
						);
				if(taxonomy_exists('pickup_location')){
					$terms_per_post = wp_get_post_terms( $taxi_value['inventory_id'], $taxonomoy, $args );
				}

				if(isset($terms_per_post) && is_array($terms_per_post)){
					foreach ($terms_per_post as $term_key => $term_value) {
						$selected_terms[] = $term_value->slug;
					}
				}
			}
		}

		return $selected_terms;
	}

	return $selected_terms;
}


if(!function_exists('rental_meta_data')) :

    /**
     * Enqueue css and js files
     *
     * @version 3.0.5
     * @since 3.0.5
     * @access public
     * @param string $key key name that will be checked
     * @param string $fallback default value
     * @return string
     */
    function rental_meta_data($product_id, $key, $fallback, $sub_key=''){
        $product_id = isset($product_id) && !empty($product_id) ? $product_id : get_the_ID();
        $meta_value = get_post_meta( $product_id, $key, true);
        if(!empty($sub_key)){
            if (isset($meta_value) && !empty($meta_value)){
                return $meta_value;
            }
        }else{
            if (isset($meta_value) && !empty($meta_value)){
                return $meta_value;
            }
        }
        return $fallback;
    }

endif;


if(!function_exists('rental_extract_meta_data')) :

    /**
     * Split options
     *
     *
     *
     * Call this function in following ways
     *
     * 1.  extract(
     *      rental_extract_meta_data(
     *          array(
     *            'Your_variable_name' = array ('default_value', 'main_ara_key', 'main_ara_sub_key'),
     *         )
     *      )
     *     );
     *
     *
     *
     * @version 3.0.5
     * @since 3.0.5
     * @var Array $helper
     * @access public
     */
    function rental_extract_meta_data($helper) {
        foreach ($helper as $option_key => $option_fallback) {
            if(is_array($option_fallback)){
                $product_id = $option_fallback[0];
                $fallback = $option_fallback[1];
                $main_key = array_key_exists(2, $option_fallback) ? $option_fallback[2] : '';
                $sub_key = array_key_exists(3, $option_fallback) ? $option_fallback[3] : '';
                $helper[$option_key] =  rental_meta_data($product_id, $main_key, $fallback, $sub_key);
            }else{
                $helper[$option_key] =  rental_meta_data($option_key, $option_fallback, '');
            }
        }

        return $helper;
    }

endif;



if(!function_exists('rental_product_in_cart')) :
    /**
     * @version 3.0.5
     * @since 3.0.5
     * @var Array $helper
     * @access public
     */
    function rental_product_in_cart($product_id) {
        global $woocommerce;
        $cart_dates = array();
        $block_dates = array();

        // $conditional_data = reddq_rental_get_settings( $product_id, 'conditions' );
        // $conditional_data = $conditional_data['conditions'];

        // $output_date_format  = $conditional_data['date_format'];
        // $euro_format  = $conditional_data['euro_format'];

        // $product_type = wc_get_product($product_id)->get_type();
        // $cart_items = $woocommerce->cart->get_cart();

        // $inventories = get_post_meta( $product_id, 'redq_block_dates_and_times', true );
        // $inventory_length = sizeof($inventories);
        // $count = 0;

        // if(isset($product_type) && !empty($product_type)) {
        //     foreach($cart_items as $key => $val ) {
        //         if($product_id === $val['product_id']){
        //             $count++;
        //             $block_dates[] = manage_all_dates($val['rental_data']['pickup_date'], $val['rental_data']['dropoff_date'], $euro_format, $output_date_format);
        //         }
        //     }
        // }

        // if($count >= $inventory_length){
        //     if(isset($block_dates) && !empty($block_dates)){
        //         foreach ($block_dates as $key => $date_values) {
        //             foreach ($date_values as $dkey => $dvalue) {
        //                $cart_dates[] = $dvalue;
        //             }
        //         }
        //     }
        // }

        return $cart_dates;
    }

endif;


if(!function_exists('reddq_rental_staring_block_days')) :

    /**
     * Retrieve all block dates starting from current day
     *
     * @since 3.0.9
     * @version 3.0.9
     *
     * @param null
     * @return all block starting block days
     */
    function reddq_rental_staring_block_days($product_id) {

        $conditional_data = reddq_rental_get_settings( $product_id, 'conditions' );
        $conditional_data = $conditional_data['conditions'];
        $output_format  = $conditional_data['date_format'];
        $rental_block = $conditional_data['blockable'];
        $weekend  = $conditional_data['weekends'];
        $no_of_days  = $conditional_data['pre_block_days'];

        $standard_format = 'Y-m-d';
        $starting_block_dates = array();
        $today = strtotime(date($standard_format));
        $numeric_today = date('w', $today);
        $count = 0;

        while ($count < $no_of_days) {
            $day = strtotime('+'.$count.' day', $today);
            $count_day = date('w', $day);
            if(in_array($count_day, $weekend)){
                $no_of_days++;
                $count++;
                continue;
            }
            $starting_block_dates[] = date($output_format, $day);
            $count++;
        }

        return $starting_block_dates;
    }

endif;



if(!function_exists('reddq_rental_booking_extra_block_days')) :

    /**
     * Retrieve all block dates after a booking
     *
     * @since 3.0.9
     * @version 3.0.9
     *
     * @param null
     * @return all block post booking block days
     */
    function reddq_rental_booking_extra_block_days($product_id, $dates) {

        $conditional_data = reddq_rental_get_settings( $product_id, 'conditions' );
        $conditional_data = $conditional_data['conditions'];
        $output_format  = $conditional_data['date_format'];
        $rental_block = $conditional_data['blockable'];
        $weekend  = $conditional_data['weekends'];
        $blocked_dates = calculate_block_dates( $product_id );

        //Calculation for post block
        $post_block_days  = $conditional_data['post_block_days'];
        $length = sizeof($dates);
        $last = $dates[$length-1];

        $post_booking_block_days = array();
        $last_day = strtotime($last); // Last Day must be provided in non-european format
        $numeric_lastday = date('w', $last_day);
        $count = 1;

        while ($count <= $post_block_days) {
            $day = strtotime('+'.$count.' day', $last_day);
            $count_day = date('w', $day);
            if(in_array($count_day, $weekend)){
                $post_block_days++;
                $count++;
                continue;
            }

            //Hacked v6.0.3
            $date = date('Y-m-d', $day);
            if( !in_array( convert_to_output_format( $date, $output_format ), $blocked_dates ) ){
                $post_booking_block_days[] = $date;
            }

            //Original
            //$post_booking_block_days[] = date('Y-m-d', $day);

            $count++;
        }

        //Calculation for pre block
        $first = $dates[0];
        $pre_block_days = $conditional_data['before_block_days'];

        $pre_booking_block_days = array();
        $first_day = strtotime($first); // first Day must be provided in non-european format
        $numeric_firstday = date('w', $first_day);
        $count = 1;

        while ($count <= $pre_block_days) {
            $day = strtotime('-'.$count.' day', $first_day);
            $count_day = date('w', $day);
            if(in_array($count_day, $weekend)){
                $pre_block_days++;
                $count++;
                continue;
            }

            //Hacked v6.0.3
            $date = date('Y-m-d', $day);
            if( !in_array( convert_to_output_format( $date, $output_format ), $blocked_dates ) ){
                $pre_booking_block_days[] = $date;
            }

            //original
            //$pre_booking_block_days[] = date('Y-m-d', $day);

            $count++;
        }

        $extra_block_days = array_merge($dates, $post_booking_block_days, $pre_booking_block_days);
        usort( $extra_block_days, "reddq_rental_date_sort" );

        return $extra_block_days;
    }

endif;


if(!function_exists('reddq_rental_date_sort')) :
    /**
     * Sort date array
     *
     * @since 3.0.9
     * @version 3.0.9
     *
     * @param null
     * @return sorted block dates
     */
    function reddq_rental_date_sort( $time1, $time2 ) {

        if (strtotime($time1) > strtotime($time2))
            return 1;
        else if (strtotime($time1) < strtotime($time2))
            return -1;
        else
            return 0;

    }
endif;

if(!function_exists('reddq_rental_extra_buffer_block_days')) :

    /**
     * Retrieve all block dates after a booking
     *
     * @since 3.0.9
     * @version 3.0.9
     *
     * @param null
     * @return all block post booking block days
     */
    function reddq_rental_extra_buffer_block_days($product_id, $dates) {

        $conditional_data = reddq_rental_get_settings( $product_id, 'conditions' );
        $conditional_data = $conditional_data['conditions'];
        $output_format  = $conditional_data['date_format'];
        $rental_block = $conditional_data['blockable'];
        $weekend  = $conditional_data['weekends'];

        //Calculation for post buffer days
        $post_block_days  = intval($conditional_data['post_block_days']);
        $length = sizeof($dates);
        $last = $dates[$length-1];

        $post_booking_block_days = array();
        $last_day = strtotime($last); // Last Day must be provided in non-european format
        $numeric_lastday = date('w', $last_day);
        $count = 1;

        while ($count <= $post_block_days) {
            $day = strtotime('+'.$count.' day', $last_day);
            $count_day = date('w', $day);
            if(in_array($count_day, $weekend)){
                $post_block_days++;
                $count++;
                continue;
            }
            $post_booking_block_days[] = date('Y-m-d', $day);
            $count++;
        }

        return $post_booking_block_days;
    }

endif;


if(!function_exists('reddq_rental_extra_pre_buffer_block_days')) :

    /**
     * Retrieve all block dates after a booking
     *
     * @since 3.0.9
     * @version 3.0.9
     *
     * @param null
     * @return all block post booking block days
     */
    function reddq_rental_extra_pre_buffer_block_days($product_id, $dates) {

        $conditional_data = reddq_rental_get_settings( $product_id, 'conditions' );
        $conditional_data = $conditional_data['conditions'];
        $output_format  = $conditional_data['date_format'];
        $rental_block = $conditional_data['blockable'];
        $weekend  = $conditional_data['weekends'];

        //Calculation for pre block
        $first = $dates[0];
        $pre_block_days = 2+1;

        $pre_booking_block_days = array();
        $first_day = strtotime($first); // first Day must be provided in non-european format
        $numeric_firstday = date('w', $first_day);
        $count = 1;

        while ($count <= $pre_block_days) {
            $day = strtotime('-'.$count.' day', $first_day);
            $count_day = date('w', $day);
            if(in_array($count_day, $weekend)){
                $pre_block_days++;
                $count++;
                continue;
            }
            $pre_booking_block_days[] = date('Y-m-d', $day);
            $count++;
        }

        return  $pre_booking_block_days;
    }

endif;



if(!function_exists('rnb_process_rental_order_data')) :

    /**
     * Process rental order data
     *
     * @since 3.0.9
     * @version 3.0.9
     *
     * @param null
     * @return all block post booking block days
     */
    function rnb_process_rental_order_data( $product_id, $order_id, $item_id, $inventory_id, $booked_dates_ara, $quantity ) {
		if( !sizeof($booked_dates_ara) ) return;

        $booked_infos = array(
            'pickup_date'   => $booked_dates_ara['pickup_datetime'],
            'return_date'   => $booked_dates_ara['return_datetime'],
            'inventory_id'  => $inventory_id,
        );
   
        //Inventories reference
        if(isset($inventory_id) && !empty($inventory_id)){
            update_post_meta( $order_id, 'order_item_'.$item_id.'_inventory_ref', $inventory_id );
        }

        if(isset($booked_infos) && !empty($booked_infos)){
            rnb_save_dates_on_database($product_id, $inventory_id, $order_id, $item_id, $booked_infos );               
        }		
    }

endif;


if(!function_exists('rnb_save_dates_on_database')) :    
    /**
     * rnb_save_dates_on_database
     *
     * @param  mixed $product_id
     * @param  mixed $order_id
     * @param  mixed $item_id
     * @param  mixed $block_dates_times
     *
     * @return void
     */
    function rnb_save_dates_on_database( $product_id, $inventory_id, $order_id, $item_id, $booked_infos ) {
        if(isset($booked_infos) && !empty($booked_infos)){
            $length = sizeof($booked_infos);
            $args = array(
                'pickup_datetime'   => $booked_infos['pickup_date'],
                'return_datetime'   => $booked_infos['return_date'],
                'product_id'        => $product_id,
                'inventory_id'      => $inventory_id,
                'order_id'          => $order_id,
                'item_id'           => $item_id,               
            );      
            rnb_booking_dates_insert($args);
        }      
    }
endif;


if(!function_exists('reddq_rental_get_settings')) :

    /**
     * Retrieve all global and local settigns
     *
     * @since 4.0.5
     * @version 4.0.5
     *
     * @param null
     * @return all block post booking block days
     */
    function reddq_rental_get_settings( $product_id, $type='labels', $required_values = array('pickup_date') ) {

        $settigns_data = array();

        extract(rental_extract_meta_data(array(
            'display_settings' => array($product_id, 'global', 'rnb_settings_for_display' ),
            'labels_settings' => array($product_id,'global', 'rnb_settings_for_labels' ),
            'conditions_settings' => array($product_id,'global', 'rnb_settings_for_conditions' ),
            'validations_settings' => array($product_id,'global', 'rnb_settings_for_validations' ),
        )));

        switch ($type) {
            case 'general':
                $settigns_data['general']['lang_domain']        = get_option('rnb_lang_domain');
                $settigns_data['general']['months']             = get_option('rnb_lang_months');
                $settigns_data['general']['weekdays']           = get_option('rnb_lang_weekdays');
                $settigns_data['general']['instance_pay']       = get_option('rnb_instance_payment');
                $settigns_data['general']['total_days']         = get_option('rnb_total_days_label', esc_html__('Total Days', 'redq-rental'));
                $settigns_data['general']['total_hours']        = get_option('rnb_total_hours_label', esc_html__('Total Hours', 'redq-rental'));
                $settigns_data['general']['payment_due']        = get_option('rnb_payment_due_label', esc_html__('Payment Due', 'redq-rental'));
                $settigns_data['general']['attribute_tab']      = get_option('rnb_attribute_tab', esc_html__('Attributes', 'redq-rental'));
                $settigns_data['general']['feature_tab']        = get_option('rnb_feature_tab', esc_html__('Features', 'redq-rental'));
                $settigns_data['general']['rfq_user_pass']      = get_option('rnb_enable_rfq_without_user_pass', 'yes');
                $settigns_data['general']['day_of_week_start']  = get_option('rnb_day_of_week_start', 1) - 1;
                $settigns_data['general']['holidays']           = get_option('rnb_holidays', array());

                break;
            case 'display':
                if($display_settings === 'local'){
                    extract(rental_extract_meta_data(array(
                        'pickup_date' => array($product_id, 'open', 'redq_rental_local_show_pickup_date' ),
                        'pickup_time' => array($product_id, 'open', 'redq_rental_local_show_pickup_time' ),
                        'return_date' => array($product_id, 'open', 'redq_rental_local_show_dropoff_date' ),
                        'return_time' => array($product_id, 'open', 'redq_rental_local_show_dropoff_time' ),
                        'quantity' => array($product_id, 'open', 'rnb_enable_quantity' ),
                        'flip_box' => array($product_id, 'open', 'redq_rental_local_show_pricing_flip_box' ),
                        'discount' => array($product_id, 'open', 'redq_rental_local_show_price_discount_on_days' ),
                        'instance_payment' => array($product_id, 'open', 'redq_rental_local_show_price_instance_payment' ),
                        'rfq' => array($product_id, 'open', 'redq_rental_local_show_request_quote' ),
                        'book_now' => array($product_id, 'open', 'redq_rental_local_show_book_now' ),
                    )));
                    $settigns_data['display']['pickup_date'] = $pickup_date;
                    $settigns_data['display']['pickup_time'] = $pickup_time;
                    $settigns_data['display']['return_date'] = $return_date;
                    $settigns_data['display']['return_time'] = $return_time;
                    $settigns_data['display']['quantity'] = $quantity;
                    $settigns_data['display']['flip_box'] = $flip_box;
                    $settigns_data['display']['instance_payment'] = $instance_payment;
                    $settigns_data['display']['discount'] = $discount;
                    $settigns_data['display']['rfq'] = $rfq;
                    $settigns_data['display']['book_now'] = $book_now;
                }else{
                    $options_data = array();
                    $options_data['pickup_date'] = get_option('rnb_show_pickup_date', 'yes');
                    $options_data['pickup_time'] = get_option('rnb_show_pickup_time', 'yes');
                    $options_data['return_date'] = get_option('rnb_show_dropoff_date', 'yes');
                    $options_data['return_time'] = get_option('rnb_show_dropoff_time', 'yes');
                    $options_data['quantity'] = get_option('rnb_enable_quantity', 'yes');
                    $options_data['flip_box'] = get_option('rnb_enable_price_flipbox', 'yes');
                    $options_data['discount'] = get_option('rnb_enable_price_discount', 'closed');
                    $options_data['instance_payment'] = get_option('rnb_enable_instance_payment', 'closed');
                    $options_data['rfq'] = get_option('rnb_enable_rfq_btn', 'closed');
                    $options_data['book_now'] = get_option('rnb_enable_book_now_btn', 'yes');

                    foreach ($options_data as $key => $value) {
                        if(!empty($value) && $value === 'yes'){
                            $settigns_data['display'][$key] = 'open';
                        }else{
                           $settigns_data['display'][$key] = 'closed';
                        }
                    }
                }
                break;

            case 'conditions':
                if($conditions_settings === 'local'){
                    extract(rental_extract_meta_data(array(
                        'blockable' => array($product_id, 'yes', 'redq_block_general_dates' ),
                        'date_format' => array($product_id, 'm/d/Y', 'redq_calendar_date_format' ),
                        'euro_format' => array($product_id, 'no', 'redq_choose_european_date_format' ),
                        'max_time_late' => array($product_id, '0', 'redq_max_time_late' ),
                        'pay_extra_hours' => array($product_id, 'no', 'rnb_pay_extra_hours' ),
                        'single_day_booking' => array($product_id, 'open', 'redq_rental_local_enable_single_day_time_based_booking' ),
                        'max_book_days' => array($product_id, '', 'redq_max_rental_days' ),
                        'min_book_days' => array($product_id, '', 'redq_min_rental_days' ),
                        'pre_block_days' => array($product_id, '', 'redq_rental_starting_block_dates' ),
                        'before_block_days' => array($product_id, '', 'redq_rental_before_booking_block_dates' ),
                        'post_block_days' => array($product_id, '', 'redq_rental_post_booking_block_dates' ),
                        'time_interval' => array($product_id, '60', 'redq_time_interval' ),
                        'show_price_type' => array($product_id, 'daily', 'rnb_show_price_type' ),
                        'weekends' => array($product_id, array(), 'redq_rental_off_days' ),
                        'allowed_times' => array($product_id, '', 'redq_allowed_times' ),
                        'booking_layout' => array($product_id, 'layout_one', 'rnb_booking_layout' ),
                        'time_format' => array($product_id, '24-hours', 'redq_calendar_time_format' ),
                    )));
                    $settigns_data['conditions']['blockable'] = $blockable;
                    $settigns_data['conditions']['date_format'] = $date_format;
                    $settigns_data['conditions']['euro_format'] = $euro_format;
                    $settigns_data['conditions']['time_format'] = $time_format;
                    $settigns_data['conditions']['max_time_late'] = $max_time_late;
                    $settigns_data['conditions']['pay_extra_hours'] = $pay_extra_hours;
                    $settigns_data['conditions']['single_day_booking'] = $single_day_booking;
                    $settigns_data['conditions']['max_book_days'] = $max_book_days;
                    $settigns_data['conditions']['min_book_days'] = $min_book_days;
                    $settigns_data['conditions']['pre_block_days'] = $pre_block_days;
                    $settigns_data['conditions']['before_block_days'] = $before_block_days;
                    $settigns_data['conditions']['post_block_days'] = $post_block_days;
                    $settigns_data['conditions']['time_interval'] = $time_interval;
                    $settigns_data['conditions']['show_price_type'] = $show_price_type;
                    $settigns_data['conditions']['weekends'] = $weekends;

                    $settigns_data['conditions']['allowed_times'] = !empty($allowed_times) ? rnb_format_allow_times($allowed_times) : [];
                    $settigns_data['conditions']['booking_layout'] = $booking_layout;
                }else{
                    $settigns_data['conditions']['blockable'] = get_option('rnb_block_rental_days', 'yes');
                    $settigns_data['conditions']['date_format'] = get_option('rnb_choose_date_format', 'm/d/Y');
                    $settigns_data['conditions']['time_format'] = get_option('rnb_choose_time_format', '24-hours');
                    $settigns_data['conditions']['euro_format'] = $settigns_data['conditions']['date_format'] === 'd/m/Y' ? 'yes':'no';
                    $settigns_data['conditions']['max_time_late'] = get_option('rnb_max_time_late', 0);
                    $settigns_data['conditions']['pay_extra_hours'] = get_option('rnb_pay_extra_hours', 'no');
                    $single_day_booking = get_option('rnb_single_day_booking', 'yes');
                    $settigns_data['conditions']['single_day_booking'] = isset($single_day_booking) && $single_day_booking === 'yes' ? 'open' : 'closed';
                    $settigns_data['conditions']['max_book_days'] = get_option('rnb_max_book_day');
                    $settigns_data['conditions']['min_book_days'] = get_option('rnb_min_book_day');
                    $settigns_data['conditions']['pre_block_days'] = get_option('rnb_staring_block_days');
                    $settigns_data['conditions']['before_block_days'] = get_option('rnb_before_block_days');
                    $settigns_data['conditions']['post_block_days'] = get_option('rnb_post_block_days');
                    $settigns_data['conditions']['time_interval'] = get_option('rnb_time_intervals', '30');
                    $settigns_data['conditions']['show_price_type'] = get_option('rnb_show_price_type', 'daily');
                    $settigns_data['conditions']['booking_layout'] = get_option('rnb_booking_layout', 'layout_one');

                    //subtraction 1 to adjsut weekday
                    $saved_weekends = get_option('rnb_weekends');
                    $weekends = array();
                    if(isset($saved_weekends) && !empty($saved_weekends)) {
                        foreach ($saved_weekends as $wkey => $wvalue) {
                            array_push($weekends, ($wvalue-1));
                        }
                    }
                    $settigns_data['conditions']['weekends'] = $weekends;
                    $allowed_times = get_option('rnb_allowed_times');
                    $settigns_data['conditions']['allowed_times'] = !empty($allowed_times) ? rnb_format_allow_times($allowed_times) : [];
                }
                break;

            case 'validations':
                if($validations_settings === 'local'){
                    extract(rental_extract_meta_data(array(
                        'pickup_location' => array($product_id, 'closed', 'redq_rental_local_required_pickup_location' ),
                        'return_location' => array($product_id, 'closed', 'redq_rental_local_required_return_location' ),
                        'person' => array($product_id, 'closed', 'redq_rental_local_required_person' ),
                        'pickup_time' => array($product_id, 'closed', 'redq_rental_required_local_pickup_time' ),
                        'return_time' => array($product_id, 'closed', 'redq_rental_required_local_return_time' ),
                    )));
                    $settigns_data['validations']['pickup_location'] = $pickup_location;
                    $settigns_data['validations']['return_location'] = $return_location;
                    $settigns_data['validations']['person'] = $person;
                    $settigns_data['validations']['pickup_time'] = $pickup_time;
                    $settigns_data['validations']['return_time'] = $return_time;
                }else{
                    $options_data = array();
                    $options_data['pickup_location'] = get_option('rnb_required_pickup_loc', 'yes');
                    $options_data['return_location'] = get_option('rnb_required_dropoff_loc', 'yes');
                    $options_data['pickup_time'] = get_option('rnb_required_pickup_time', 'yes');
                    $options_data['return_time'] = get_option('rnb_required_dropoff_time', 'yes');
                    $options_data['person'] = get_option('rnb_required_person', 'yes');
                    foreach ($options_data as $key => $value) {
                        if(!empty($value) && $value === 'yes'){
                            $settigns_data['validations'][$key] = 'open';
                        }else{
                           $settigns_data['validations'][$key] = 'closed';
                        }
                    }
                }

                extract(rental_extract_meta_data(array(
                    'fri_min' => array($product_id, '00:00', 'redq_rental_fri_min_time' ),
                    'fri_max' => array($product_id, '24:00', 'redq_rental_fri_max_time' ),
                    'sat_min' => array($product_id, '00:00', 'redq_rental_sat_min_time' ),
                    'sat_max' => array($product_id, '24:00', 'redq_rental_sat_max_time' ),
                    'sun_min' => array($product_id, '00:00', 'redq_rental_sun_min_time' ),
                    'sun_max' => array($product_id, '24:00', 'redq_rental_sun_max_time' ),
                    'mon_min' => array($product_id, '00:00', 'redq_rental_mon_min_time' ),
                    'mon_max' => array($product_id, '24:00', 'redq_rental_mon_max_time' ),
                    'thu_min' => array($product_id, '00:00', 'redq_rental_thu_min_time' ),
                    'thu_max' => array($product_id, '24:00', 'redq_rental_thu_max_time' ),
                    'wed_min' => array($product_id, '00:00', 'redq_rental_wed_min_time' ),
                    'wed_max' => array($product_id, '24:00', 'redq_rental_wed_max_time' ),
                    'thur_min' => array($product_id, '00:00', 'redq_rental_thur_min_time' ),
                    'thur_max' => array($product_id, '24:00', 'redq_rental_thur_max_time' ),
                )));

                $time_avail = array();
                $time_avail['fri']['min'] = $fri_min;
                $time_avail['fri']['max'] = $fri_max;
                $time_avail['sat']['min'] = $sat_min;
                $time_avail['sat']['max'] = $sat_max;
                $time_avail['sun']['min'] = $sun_min;
                $time_avail['sun']['max'] = $sun_max;
                $time_avail['mon']['min'] = $mon_min;
                $time_avail['mon']['max'] = $mon_max;
                $time_avail['thu']['min'] = $thu_min;
                $time_avail['thu']['max'] = $thu_max;
                $time_avail['wed']['min'] = $wed_min;
                $time_avail['wed']['max'] = $wed_max;
                $time_avail['thur']['min'] = $thur_min;
                $time_avail['thur']['max'] = $thur_max;

                $settigns_data['validations']['openning_closing'] = $time_avail;

                break;

            case 'layout_two':

                if( isset($required_values) && !empty($required_values) ) :
                    foreach ($required_values as $key => $value) {
                        $data = array();
                        if( $value === 'datetime' ){
                            $data['date_top_heading'] = get_option( 'rnb_date_top_heading' );
                            $data['date_top_desc'] = get_option( 'rnb_date_top_desc' );
                            $data['date_inner_heading'] = get_option('rnb_date_inner_heading');
                            $data['date_inner_desc'] = get_option('rnb_date_inner_desc');
                        }

                        if( $value === 'location' ){
                            $data['location_top_heading'] = get_option( 'rnb_location_top_heading' );
                            $data['location_top_desc'] = get_option( 'rnb_location_top_desc' );
                            $data['location_inner_heading'] = get_option('rnb_location_inner_heading');
                            $data['location_inner_desc'] = get_option('rnb_location_inner_desc');
                        }

                        if( $value === 'resource' ){
                            $data['resource_top_heading'] = get_option( 'rnb_resource_top_heading' );
                            $data['resource_top_desc'] = get_option( 'rnb_resource_top_desc' );
                            $data['resource_inner_heading'] = get_option('rnb_resource_inner_heading');
                            $data['resource_inner_desc'] = get_option('rnb_resource_inner_desc');
                        }

                        if( $value === 'person' ){
                            $data['person_top_heading'] = get_option( 'rnb_person_top_heading' );
                            $data['person_top_desc'] = get_option( 'rnb_person_top_desc' );
                            $data['person_inner_heading'] = get_option('rnb_person_inner_heading');
                            $data['person_inner_desc'] = get_option('rnb_person_inner_desc');
                        }

                        if( $value === 'deposit' ){
                            $data['deposit_top_heading'] = get_option( 'rnb_deposit_top_heading' );
                            $data['deposit_top_desc'] = get_option( 'rnb_deposit_top_desc' );
                            $data['deposit_inner_heading'] = get_option('rnb_deposit_inner_heading');
                            $data['deposit_inner_desc'] = get_option('rnb_deposit_inner_desc');
                        }

                        if( $value === 'summary' ){
                            $data['summary_top_heading'] = get_option( 'rnb_summary_top_heading' );
                            $data['summary_top_desc'] = get_option( 'rnb_summary_top_desc' );
                            $data['summary_inner_heading'] = get_option('rnb_summary_inner_heading');
                            $data['summary_inner_desc'] = get_option('rnb_summary_inner_desc');
                        }

                        $settigns_data[$value] = $data;
                    }

                endif;

                break;

            default:
                if($labels_settings === 'local'){
                    $builds_fields = array();
                    if(isset($required_values) && !empty($required_values)){
                        foreach ($required_values as $key => $value) {
                            if($value === 'pickup_location'){
                                $builds_fields['pickup_location'] = array($product_id, esc_html__('Pickup Locations', 'redq-rental'), 'redq_pickup_location_heading_title' );
                                $builds_fields['pickup_loc_placeholder'] = array($product_id, esc_html__('Pickup Locations', 'redq-rental'), 'redq_pickup_loc_placeholder' );

                                extract(rental_extract_meta_data($builds_fields));

                                $settigns_data['labels']['pickup_location'] = $pickup_location;
                                $settigns_data['labels']['pickup_loc_placeholder'] = $pickup_loc_placeholder;
                            }
                            if($value === 'return_location'){
                                $builds_fields['return_location'] = array($product_id, esc_html__('Return Locations', 'redq-rental'), 'redq_dropoff_location_heading_title' );
                                $builds_fields['return_loc_placeholder'] = array($product_id, esc_html__('Return Locations', 'redq-rental'), 'redq_return_loc_placeholder' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['return_location'] = $return_location;
                                $settigns_data['labels']['return_loc_placeholder'] = $return_loc_placeholder;
                            }
                            if($value === 'pickup_date'){
                                $builds_fields['pickup_datetime'] = array($product_id, esc_html__('Pickup Date&Time', 'redq-rental'), 'redq_pickup_date_heading_title' );
                                $builds_fields['pickup_date'] = array($product_id, esc_html__('Date', 'redq-rental'), 'redq_pickup_date_placeholder' );
                                $builds_fields['pickup_time'] = array($product_id, esc_html__('Time', 'redq-rental'), 'redq_pickup_time_placeholder' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['pickup_datetime'] = $pickup_datetime;
                                $settigns_data['labels']['pickup_date'] = $pickup_date;
                                $settigns_data['labels']['pickup_time'] = $pickup_time;
                            }
                            if($value === 'return_date'){
                                $builds_fields['return_datetime'] = array($product_id, esc_html__('Return Date&Time', 'redq-rental'), 'redq_dropoff_date_heading_title' );
                                $builds_fields['return_date'] = array($product_id, esc_html__('Date', 'redq-rental'), 'redq_dropoff_date_placeholder' );
                                $builds_fields['return_time'] = array($product_id, esc_html__('Time', 'redq-rental'), 'redq_dropoff_time_placeholder' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['return_datetime'] = $return_datetime;
                                $settigns_data['labels']['return_date'] = $return_date;
                                $settigns_data['labels']['return_time'] = $return_time;
                            }
                            if($value === 'person'){
                                $builds_fields['adults'] = array($product_id, esc_html__('Adults', 'redq-rental'), 'redq_adults_heading_title' );
                                $builds_fields['adults_placeholder'] = array($product_id, esc_html__('Choose Adults', 'redq-rental'), 'redq_adults_placeholder' );
                                $builds_fields['childs'] = array($product_id, esc_html__('Childs', 'redq-rental'), 'redq_childs_heading_title' );
                                $builds_fields['childs_placeholder'] = array($product_id, esc_html__('Choose Childs', 'redq-rental'), 'redq_childs_placeholder' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['adults'] = $adults;
                                $settigns_data['labels']['adults_placeholder'] = $adults_placeholder;
                                $settigns_data['labels']['childs'] = $childs;
                                $settigns_data['labels']['childs_placeholder'] = $childs_placeholder;
                            }

                            if($value === 'quantity'){
                                $builds_fields['quantity'] = array($product_id, esc_html__('Quantity', 'redq-rental'), 'rnb_quantity_label' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['quantity'] = $quantity;
                            }

                            if($value === 'resources'){
                                $builds_fields['resource'] = array($product_id, esc_html__('Resources', 'redq-rental'), 'redq_resources_heading_title' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['resource'] = $resource;
                            }
                            if($value === 'categories'){
                                $builds_fields['category'] = array($product_id, esc_html__('Categories', 'redq-rental'), 'redq_rnb_cat_heading' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['categories'] = $category;
                            }
                            if($value === 'deposites'){
                                $builds_fields['deposite'] = array($product_id, esc_html__('Deposite', 'redq-rental'), 'redq_security_deposite_heading_title' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['deposite'] = $deposite;
                            }
                            if($value === 'inventory'){
                                $builds_fields['inventory'] = array($product_id, esc_html__('Inventory', 'redq-rental'), 'rnb_inventory_title' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['inventory'] = $inventory;
                            }
                            if($value === 'booking_info'){
                                $builds_fields['discount'] = array($product_id, esc_html__('Discounts', 'redq-rental'), 'redq_discount_text_title' );
                                $builds_fields['instance_pay'] = array($product_id, esc_html__('Instance Pay', 'redq-rental'), 'redq_instance_pay_text_title' );
                                $builds_fields['total_cost'] = array($product_id, esc_html__('Total Cost', 'redq-rental'), 'redq_total_cost_text_title' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['discount'] = $discount;
                                $settigns_data['labels']['instance_pay'] = $instance_pay;
                                $settigns_data['labels']['total_cost'] = $total_cost;
                            }
                            if($value === 'price_info'){
                                $builds_fields['flipbox'] = array($product_id, esc_html__('Show Pricing', 'redq-rental'), 'redq_show_pricing_flipbox_text' );
                                $builds_fields['flipbox_info'] = array($product_id, esc_html__('Pricing Info', 'redq-rental'), 'redq_flip_pricing_plan_text' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['flipbox'] = $flipbox;
                                $settigns_data['labels']['flipbox_info'] = $flipbox_info;
                            }
                            if($value === 'buttons'){
                                $builds_fields['book_now'] = array($product_id, esc_html__('Book Now', 'redq-rental'), 'redq_book_now_button_text' );
                                $builds_fields['rfq'] = array($product_id, esc_html__('Request For Quote', 'redq-rental'), 'redq_rfq_button_text' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['book_now'] = $book_now;
                                $settigns_data['labels']['rfq'] = $rfq;
                            }

                            if($value === 'rfq_form'){
                                $builds_fields['username'] = array($product_id, esc_html__('Username', 'redq-rental'), 'redq_username_placeholder' );
                                $builds_fields['password'] = array($product_id, esc_html__('Password', 'redq-rental'), 'redq_password_placeholder' );
                                $builds_fields['first_name'] = array($product_id, esc_html__('First Name', 'redq-rental'), 'redq_first_name_placeholder' );
                                $builds_fields['last_name'] = array($product_id, esc_html__('Last Name', 'redq-rental'), 'redq_last_name_placeholder' );
                                $builds_fields['email'] = array($product_id, esc_html__('Email', 'redq-rental'), 'redq_email_placeholder' );
                                $builds_fields['phone'] = array($product_id, esc_html__('Phone', 'redq-rental'), 'redq_phone_placeholder' );
                                $builds_fields['message'] = array($product_id, esc_html__('Message', 'redq-rental'), 'redq_message_placeholder' );
                                $builds_fields['submit_button'] = array($product_id, esc_html__('Submit', 'redq-rental'), 'redq_submit_button_text' );
                                extract(rental_extract_meta_data($builds_fields));
                                $settigns_data['labels']['username'] = $username;
                                $settigns_data['labels']['password'] = $password;
                                $settigns_data['labels']['first_name'] = $first_name;
                                $settigns_data['labels']['last_name'] = $last_name;
                                $settigns_data['labels']['phone'] = $phone;
                                $settigns_data['labels']['email'] = $email;
                                $settigns_data['labels']['message'] = $message;
                                $settigns_data['labels']['submit_button'] = $submit_button;
                            }
                        }
                    }

                }else{

                    if(isset($required_values) && !empty($required_values)){
                        foreach ($required_values as $key => $value) {
                            if($value === 'pickup_location'){
                                $settigns_data['labels']['pickup_location'] = get_option( 'rnb_pickup_location_title', esc_html__('Pickup Location', 'redq-rental') );
                                $settigns_data['labels']['pickup_loc_placeholder'] = get_option( 'rnb_pickup_location_placeholder', esc_html__('Pickup Location', 'redq-rental') );
                            }
                            if($value === 'return_location'){
                                $settigns_data['labels']['return_location'] = get_option( 'rnb_dropoff_location_title', esc_html__('Dropoff Location', 'redq-rental') );
                                $settigns_data['labels']['return_loc_placeholder'] = get_option( 'rnb_dropoff_location_placeholder', esc_html__('Dropoff Location', 'redq-rental') );
                            }
                            if($value === 'pickup_date'){
                                $settigns_data['labels']['pickup_datetime'] = get_option( 'rnb_pickup_datetime_title', esc_html__('Pickup Date & Time', 'redq-rental') );
                                $settigns_data['labels']['pickup_date'] = get_option( 'rnb_pickup_date_placeholder', esc_html__('Date', 'redq-rental') );
                                $settigns_data['labels']['pickup_time'] = get_option( 'rnb_pickup_time_placeholder', esc_html__('Time', 'redq-rental') );
                            }
                            if($value === 'return_date'){
                                $settigns_data['labels']['return_datetime'] = get_option( 'rnb_dropoff_datetime_title', esc_html__('Return Date & Time', 'redq-rental') );
                                $settigns_data['labels']['return_date'] = get_option( 'rnb_dropoff_date_placeholder', esc_html__('Date', 'redq-rental') );
                                $settigns_data['labels']['return_time'] = get_option( 'rnb_dropoff_time_placeholder', esc_html__('Time', 'redq-rental') );
                            }
                            if($value === 'person'){
                                $settigns_data['labels']['adults'] = get_option( 'rnb_adults_title', esc_html__('Adults', 'redq-rental') );
                                $settigns_data['labels']['adults_placeholder'] = get_option( 'rnb_adults_placeholder', esc_html__('Adults', 'redq-rental') );
                                $settigns_data['labels']['childs'] = get_option( 'rnb_childs_title', esc_html__('Childs', 'redq-rental') );
                                $settigns_data['labels']['childs_placeholder'] = get_option( 'rnb_childs_placeholder', esc_html__('Childs', 'redq-rental') );
                            }
                            if($value === 'quantity'){
                                $settigns_data['labels']['quantity'] = get_option( 'rnb_quantity_title', esc_html__('Quantity', 'redq-rental') );
                            }
                            if($value === 'resources'){
                                $settigns_data['labels']['resource'] = get_option( 'rnb_resources_title', esc_html__('Resource', 'redq-rental') );
                            }
                            if($value === 'categories'){
                                $settigns_data['labels']['categories'] = get_option( 'rnb_categories_title', esc_html__('Category', 'redq-rental') );
                            }
                            if($value === 'deposites'){
                                $settigns_data['labels']['deposite'] = get_option( 'rnb_deposit_title', esc_html__('Deposit', 'redq-rental') );
                            }
                            if($value === 'inventory'){
                                $settigns_data['labels']['inventory'] = get_option( 'rnb_inventory_title', esc_html__('Choose Inventory', 'redq-rental') );
                            }
                            if($value === 'booking_info'){
                                $settigns_data['labels']['discount'] = get_option( 'rnb_discount_text', esc_html__('Discount', 'redq-rental') );
                                $settigns_data['labels']['instance_pay'] = get_option( 'rnb_instance_pay_text', esc_html__('Instance Pay', 'redq-rental') );
                                $settigns_data['labels']['total_cost'] = get_option( 'rnb_total_cost_text', esc_html__('Total Cost', 'redq-rental') );
                            }
                            if($value === 'price_info'){
                                $settigns_data['labels']['flipbox'] = get_option( 'rnb_pricing_flipbox_title', esc_html__('Show Pricing', 'redq-rental') );
                                $settigns_data['labels']['flipbox_info'] = get_option( 'rnb_pricing_flipbox_info_title', esc_html__('Pricing Info', 'redq-rental') );
                            }
                            if($value === 'buttons'){
                                $settigns_data['labels']['book_now'] = get_option( 'rnb_book_now_text', esc_html__('Book Now', 'redq-rental') );
                                $settigns_data['labels']['rfq'] = get_option( 'rnb_rfq_text', esc_html__('Request For Quote', 'redq-rental') );
                            }

                            if($value === 'rfq_form'){
                                $settigns_data['labels']['username'] = get_option( 'redq_username_placeholder', esc_html__('Username', 'redq-rental') );
                                $settigns_data['labels']['password'] = get_option( 'redq_password_placeholder', esc_html__('Password', 'redq-rental') );
                                $settigns_data['labels']['first_name'] = get_option( 'redq_first_name_placeholder', esc_html__('First Name', 'redq-rental') );
                                $settigns_data['labels']['last_name'] = get_option( 'redq_last_name_placeholder', esc_html__('Last Name', 'redq-rental') );
                                $settigns_data['labels']['email'] = get_option( 'redq_email_placeholder', esc_html__('Email', 'redq-rental') );
                                $settigns_data['labels']['phone'] = get_option( 'redq_phone_placeholder', esc_html__('Phone', 'redq-rental') );
                                $settigns_data['labels']['message'] = get_option( 'redq_message_placeholder', esc_html__('Message', 'redq-rental') );
                                $settigns_data['labels']['submit_button'] = get_option( 'redq_submit_button_text', esc_html__('Submit', 'redq-rental') );                                
                            }
                        }
                    }
                }
                break;
        }

        return $settigns_data;
    }

endif;


if(!function_exists('rnb_load_alloptions')) :

    /**
     * Retrieve rnb prefixed options values from wp_option table
     *
     * @since 4.0.5
     * @version 4.0.5
     *
     * @param null
     * @return rnb option values
     */
    function rnb_load_alloptions() {
        global $wpdb;

        if ( ! wp_installing() || ! is_multisite() )
            $alloptions = wp_cache_get( 'rnboptions', 'options' );
        else
            $alloptions = false;

        if ( !$alloptions ) {
            $suppress = $wpdb->suppress_errors();
            if ( !$alloptions_db = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'rnb%' AND autoload = 'yes'" ) )
                $alloptions_db = $wpdb->get_results( "SELECT option_name, option_value FROM wp_options WHERE option_name LIKE 'rnb%'" );
            $wpdb->suppress_errors($suppress);
            $alloptions = array();
            foreach ( (array) $alloptions_db as $o ) {
                $alloptions[$o->option_name] = $o->option_value;
            }
            if ( ! wp_installing() || ! is_multisite() )
                wp_cache_add( 'rnboptions', $alloptions, 'options' );
        }

        return $alloptions;
    }

endif;




if(!function_exists('redq_rental_get_pricing_data')) :

    /**
     * Retrieve rnb prefixed options values from wp_option table
     *
     * @since 4.0.5
     * @version 4.0.5
     *
     * @param null
     * @return rnb option values
     */
    function redq_rental_get_pricing_data( $inventory_id, $product_id = null ) {

        $pricing_data = array();
        extract(rental_extract_meta_data(array(
            'pricing_type'      => array($inventory_id, 'general_pricing', 'pricing_type' ),
            'hourly_pricing_type' => array($inventory_id, 'hourly_price', 'hourly_pricing_type' ),
            'price_discount'    => array($product_id, array(), 'redq_price_discount_cost' ),
            'perkilo_price'     => array($inventory_id, 0, 'perkilo_price' ),
        )));
        $pricing_data['pricing_type'] = $pricing_type;
        $pricing_data['hourly_pricing_type'] = $hourly_pricing_type;
        $pricing_data['perkilo_price'] = $perkilo_price;

        switch ($pricing_type) {
            case 'daily_pricing':
                extract(rental_extract_meta_data(array(
                    'daily_pricing' => array($inventory_id, '', 'redq_daily_pricing' ),
                )));
                $pricing_data[$pricing_type] = $daily_pricing;
                break;
            case 'monthly_pricing':
                extract(rental_extract_meta_data(array(
                    'monthly_pricing' => array($inventory_id, '', 'redq_monthly_pricing' ),
                )));
                $pricing_data[$pricing_type] = $monthly_pricing;
                break;
            case 'days_range':
                extract(rental_extract_meta_data(array(
                    'days_range' => array($inventory_id, '', 'redq_day_ranges_cost' ),
                )));
                $pricing_data[$pricing_type] = $days_range;
                break;
            default:
                extract(rental_extract_meta_data(array(
                    'general_price' => array($inventory_id, '', 'general_price' ),
                )));
                $pricing_data[$pricing_type] = $general_price;
                break;
        }

        switch ($hourly_pricing_type) {
            case 'hourly_range':
                extract(rental_extract_meta_data(array(
                    'hourly_range' => array($inventory_id, '', 'redq_hourly_ranges_cost' ),
                )));
                $pricing_data[$hourly_pricing_type] = $hourly_range;
                break;
            default:
                extract(rental_extract_meta_data(array(
                    'hourly_price' => array($inventory_id, '', 'hourly_price' ),
                )));
                $pricing_data[$hourly_pricing_type] = $hourly_price;
                break;
        }

        $pricing_data['price_discount'] = $price_discount;
        return $pricing_data;
    }

endif;



if(!function_exists('redq_rental_day_names')) :

    /**
     * Day Names
     *
     * @since 5.0.3
     * @version 5.0.3
     *
     * @param null
     * @return rnb option values
     */
    function redq_rental_day_names( ) {
        $day_names = array(
            'friday'    => esc_html__( 'Friday', 'redq-rental' ),
            'saturday'  => esc_html__( 'Saturday', 'redq-rental' ),
            'sunday'    => esc_html__( 'Sunday', 'redq-rental' ),
            'monday'    => esc_html__( 'Monday', 'redq-rental' ),
            'tuesday'   => esc_html__( 'Tuesday', 'redq-rental' ),
            'wednesday' => esc_html__( 'Wednesday', 'redq-rental' ),
            'thursday'  => esc_html__( 'Thursday', 'redq-rental' ),
        );
        return $day_names;
    }

endif;



if(!function_exists('redq_rental_month_names')) :

    /**
     * Day Names
     *
     * @since 5.0.3
     * @version 5.0.3
     *
     * @param null
     * @return rnb option values
     */
    function redq_rental_month_names( ) {
        $day_names = array(
            'january'       => esc_html__( 'January', 'redq-rental' ),
            'february'      => esc_html__( 'February', 'redq-rental' ),
            'march'         => esc_html__( 'March', 'redq-rental' ),
            'april'         => esc_html__( 'April', 'redq-rental' ),
            'may'           => esc_html__( 'May', 'redq-rental' ),
            'june'          => esc_html__( 'June', 'redq-rental' ),
            'july'          => esc_html__( 'July', 'redq-rental' ),
            'august'        => esc_html__( 'August', 'redq-rental' ),
            'september'     => esc_html__( 'September', 'redq-rental' ),
            'october'       => esc_html__( 'October', 'redq-rental' ),
            'november'      => esc_html__( 'November', 'redq-rental' ),
            'december'      => esc_html__( 'December', 'redq-rental' ),
        );
        return $day_names;
    }

endif;


if(!function_exists('redq_rental_array_flatten')) :
    /**
     * Flatten array
     *
     * @since 6.0.1
     * @version 6.0.1
     *
     * @param null
     * @return array
     */
    function redq_rental_array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
              $result = array_merge($result, redq_rental_array_flatten($value));
            }else {
              $result[$key] = $value;
            }
        }
        return $result;
    }
endif;


if(!function_exists('reddq_rental_handle_holidays')) :
    /**
     * Retrieve All Holidays
     *
     * @since 6.0.4
     * @version 6.0.4
     * update 6.0.8
     *
     * @param null
     * @return rnb option values
     */
    function reddq_rental_handle_holidays( $product_id, $holidays_list ){

        $holidays           = [];
        $results            = [];
        $conditional_data   = reddq_rental_get_settings( $product_id, 'conditions' );
        $conditional_data   = $conditional_data['conditions'];
        $output_format      = $conditional_data['date_format'] ? $conditional_data['date_format'] : 'd/m/Y';
        $holidays_ara       = !empty($holidays_list) ? explode(',', $holidays_list) : $holidays_list;

        if( isset( $holidays_ara ) && !empty( $holidays_ara ) ) :
            foreach ($holidays_ara as $key => $value) {
                if(!empty($value)){
                    $date_range = explode('to', $value);
                    $start_date = $date_range[0];
                    $end_date   = $date_range[1];
                    $holidays[] = manage_all_dates( $start_date, $end_date, 'no', $output_format, '+1 day');
                }
            }
        endif;

        if(isset($holidays) && !empty($holidays)){
            foreach ($holidays as $holiday) {
                foreach ($holiday as $key => $value) {
                    $results[] = $value;
                }
            }
        }
        return apply_filters( 'rnb_holidays', $results );
    }
endif;


if(!function_exists('redq_rental_global_color_control')) :

    function redq_rental_global_color_control(){
        $rnb_base_color = get_option('rnb_overall_color_display_option');
        return $rnb_base_color;
    }
endif;


if(!function_exists('rnb_format_allow_times')) :
    /**
     * Retrieve allow times
     *
     * @since 6.0.4
     * @version 6.0.4
     * @return array
     */
    function rnb_format_allow_times($allowed_times){
        $formatted_times = [];
        $time_ara = explode(',', $allowed_times);
        if( isset($time_ara) && !empty($time_ara) ){
            foreach ($time_ara as $key => $value) {
                $formatted_times[] = trim($value);
            }
            $formatted_times = array_filter($formatted_times, function($value) { return $value !== ''; });
        }
        return $formatted_times;
    }
endif;

function rnb_custom_date_insert($place_holders, $values) {

    global $wpdb;

    $tablename = $wpdb->prefix.'rnb_availability';

    $query           = "INSERT INTO $tablename (`block_by`, `pickup_datetime`, `return_datetime`, `rental_duration`, `inventory_id`) VALUES ";
    $query           .= implode( ', ', $place_holders );
    $sql             = $wpdb->prepare( "$query ", $values );

    if ( $wpdb->query( $sql ) ) {
        return true;
    } else {
        return false;
    }

}



/**
 * rnb_booking_dates_insert
 *
 * @param  array $data
 *
 * @return void
 */
function rnb_booking_dates_insert($args = array()){
    $defaults = array(
        'pickup_datetime'   => '',
        'return_datetime'   => '',
        'rental_duration'   => '',
        'product_id'        => '',
        'inventory_id'      => '',
        'order_id'          => '',
        'item_id'           => '',
        'created_at'        => current_time( 'mysql' ),
        'updated_at'        => current_time( 'mysql' ),
        'block_by'          => 'FRONTEND_ORDER',
    ); 
    
    if(!isset($args['pickup_datetime']) && !isset($args['return_datetime']) ) return;

    global $wpdb;    
    $pickup_datetime = isset($args['pickup_datetime']) ? $args['pickup_datetime'] : $args['return_datetime'];
    $return_datetime = isset($args['return_datetime']) ? $args['return_datetime'] : $args['pickup_datetime'];

    $formatted_args = array(
        'pickup_datetime'   => date('Y-m-d H:i',strtotime($pickup_datetime)),
        'return_datetime'   => date('Y-m-d H:i',strtotime($return_datetime)), 
        'rental_duration'   => rnb_calculate_date_difference($pickup_datetime, $return_datetime),
    ); 

    $args = wp_parse_args( $formatted_args, $args );  
    $rental_data = wp_parse_args( $args, $defaults );

    $tablename = $wpdb->prefix.'rnb_availability';  
    $wpdb->insert($tablename, $rental_data, array('%s','%s','%s','%d','%d','%d','%d','%s','%s','%s'));
}

/**
 * rnb_booking_dates_update
 *
 * @param  array $data
 *
 * @return void
 */
function rnb_booking_dates_update($args = array()){    
    global $wpdb; 
    $defaults = array(        
        'product_id' => '',
        'order_id'   => '',
        'item_id'    => '',    
    );

    $where = wp_parse_args( $args, $defaults ); 
    
    $updated_data = array(              
        'updated_at'     => current_time( 'mysql' ),
        'delete_status'  => 1,
    ); 
    $tablename = $wpdb->prefix.'rnb_availability';  
    $wpdb->update($tablename, $updated_data, $where, array('%s','%d'), array('%d','%d','%d'));
}


/**
 * rnb_calculate_date_difference
 *
 * @param  datetime $date_1
 * @param  datetime $date_2
 * @param  string $format
 *
 * @return string
 */
function rnb_calculate_date_difference($date_1 , $date_2 , $format = '%dD:%hH:%iM' ) {
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);    
    $interval  = date_diff($datetime1, $datetime2);    
    return $interval->format($format);    
}

function rnb_format_date_time( $date_time ){
    $edt = explode('T', $date_time);
    $date = $edt[0];
    $time = isset($edt[1]) ? $edt[1] : '';
    $result = strtotime($date.' '.$time);  
    return date('Y-m-d\TH:i', $result);
}
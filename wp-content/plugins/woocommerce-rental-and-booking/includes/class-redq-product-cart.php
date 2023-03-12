<?php
if (!defined('ABSPATH'))
    exit;
/**
 * Hande cart page
 *
 * @version 5.0.0
 * @since 1.0.0
 */
class WC_Redq_Rental_Cart
{

    public function __construct()
    {
        add_filter('woocommerce_add_cart_item_data', array($this, 'redq_rental_add_cart_item_data'), 20, 2);
        add_filter('woocommerce_add_cart_item', array($this, 'redq_rental_add_cart_item'), 20, 1);
        add_filter('woocommerce_get_cart_item_from_session', array($this, 'redq_rental_get_cart_item_from_session'), 20, 2);
        add_filter('woocommerce_cart_item_quantity', array($this, 'redq_cart_item_quantity'), 20, 2);
        add_filter('woocommerce_add_to_cart_validation', array($this, 'redq_add_to_cart_validation'), 20, 3);
        add_action('woocommerce_checkout_process', array($this, 'redq_rental_checkout_order_process'), 20, 3);
        add_filter('woocommerce_get_item_data', array($this, 'redq_rental_get_item_data'), 20, 2);
        add_action('woocommerce_new_order_item', array($this, 'redq_rental_order_item_meta'), 20, 3);
        add_action('woocommerce_thankyou', array($this, 'woocommerce_thankyou'), 20, 1);
        add_action('wp_ajax_quote_booking_data', array($this, 'quote_booking_data'));
        add_action('wp_ajax_nopriv_quote_booking_data', array($this, 'quote_booking_data'));
    }


    /**
     * If checkout failed during an AJAX call, send failure response.
     */
    protected function send_ajax_failure_response()
    {
        if (is_ajax()) {
            // only print notices if not reloading the checkout, otherwise they're lost in the page reload
            if (!isset(WC()->session->reload_checkout)) {
                ob_start();
                wc_print_notices();
                $messages = ob_get_clean();
            }

            $response = array(
                'result' => 'failure',
                'messages' => isset($messages) ? $messages : '',
                'refresh' => isset(WC()->session->refresh_totals),
                'reload' => isset(WC()->session->reload_checkout),
            );

            unset(WC()->session->refresh_totals, WC()->session->reload_checkout);

            wp_send_json($response);
        }
    }


    public function woocommerce_thankyou($order_id)
    {
        $order = new WC_Order($order_id);
        $items = $order->get_items();

        foreach ($items as $item) {
            foreach ($item['item_meta'] as $key => $value) {
                if ($key === 'Quote Request') {
                    wp_update_post(array(
                        'ID' => $value[0],
                        'post_status' => 'quote-completed'
                    ));
                }
            }
        }
    }


    /**
     * Insert posted data into cart item meta
     *
     * @param  string $product_id , array $cart_item_meta
     * @return array
     */
    public function redq_rental_add_cart_item_data($cart_item_meta, $product_id)
    {
        $product_type = wc_get_product($product_id)->get_type();
        if (isset($product_type) && $product_type === 'redq_rental' && !isset($cart_item_meta['rental_data']['quote_id'])) {
            $posted_data = $this->get_posted_data($product_id, $_POST);
            $cart_item_meta['rental_data'] = $posted_data;
        }
        
        return $cart_item_meta;
    }


    /**
     * Add cart item meta
     *
     * @param  array $cart_item
     * @return array
     */
    public function redq_rental_add_cart_item($cart_item)
    {
        

        $product_id = $cart_item['data']->get_id();
        $product_type = wc_get_product($product_id)->get_type();
        if (isset($cart_item['rental_data']['quote_id']) && $product_type === 'redq_rental') {
            $cart_item['data']->set_price(get_post_meta($cart_item['rental_data']['quote_id'], '_quote_price', true));
        } else {
            if (isset($cart_item['rental_data']['rental_days_and_costs']['cost']) && $product_type === 'redq_rental') {
				//echo "<pre>";
				//print_r($cart_item['rental_data']);
				//die (print 'here');
				$cost = $cart_item['rental_data']['rental_days_and_costs']['cost']/$cart_item['rental_data']['rental_days_and_costs']['days'];

				//$days = ceil($cart_item['rental_data']['rental_days_and_costs']['days']*0.0328767);
                $days = $_SESSION['diffMonths'];

				//echo $cart_item['rental_data']['rental_days_and_costs']['cost']/$days;
				//die (print $cost*$days);
                $cart_item['data']->set_price($cost*$days);
            }

            if (isset($cart_item['quantity']) && $product_type === 'redq_rental') {
                $cart_item['quantity'] = isset($cart_item['rental_data']['quantity']) ? $cart_item['rental_data']['quantity'] : 1;
            }
        }

        return $cart_item;
    }


    /**
     * Get item data from session
     *
     * @param  array $cart_item
     * @return array
     */
    public function redq_rental_get_cart_item_from_session($cart_item, $values)
    {
        //print_r($_SESSION);
        if (!empty($values['rental_data'])) {
            $cart_item = $this->redq_rental_add_cart_item($cart_item);
        }
        return $cart_item;
    }


    /**
     * Set quanlity always 1
     *
     * @param  array $cart_item_key , int $product_quantity
     * @return int
     */
    public function redq_cart_item_quantity($product_quantity, $cart_item_key)
    {
        global $woocommerce;
        $cart_details = $woocommerce->cart->cart_contents;

        if (isset($cart_details)) {
            foreach ($cart_details as $key => $value) {
                if ($key === $cart_item_key) {
                    $product_id = $value['product_id'];
                    $product_type = wc_get_product($product_id)->get_type();
                    if ($product_type === 'redq_rental') {
                        return $value['quantity'] ? $value['quantity'] : 1;
                    } else {
                        return $product_quantity;
                    }
                }
            }
        }
    }


    /**
     * Set Validation
     *
     * @param  array $valid , int $product_id, int $quantity
     * @return boolean
     */
    public function redq_add_to_cart_validation($valid, $product_id, $quantity)
    {
        return $valid;
    }


    /**
     * Show cart item data in cart and checkout page
     *
     * @param  blank array $custom_data , array $cart_item
     * @return array
     */
    public function redq_rental_get_item_data($custom_data, $cart_item)
    {

        $product_id = $cart_item['data']->get_id();
        $product_type = wc_get_product($product_id)->get_type();

        if (isset($product_type) && $product_type === 'redq_rental') {

            $rental_data = $cart_item['rental_data'];

            $options_data = array();
            $options_data['quote_id'] = '';

            $get_labels = reddq_rental_get_settings($product_id, 'labels', array('pickup_location', 'return_location', 'pickup_date', 'return_date', 'resources', 'categories', 'person', 'deposites', 'inventory'));
            $labels = $get_labels['labels'];
            $get_displays = reddq_rental_get_settings($product_id, 'display');
            $displays = $get_displays['display'];

            $get_conditions = reddq_rental_get_settings($product_id, 'conditions');
            $conditional_data = $get_conditions['conditions'];

            $get_general = reddq_rental_get_settings($product_id, 'general');
            $general_data = $get_general['general'];

            if (isset($rental_data) && !empty($rental_data)) {
                if (isset($rental_data['quote_id'])) {
                    $custom_data[] = array(
                        'name' => $options_data['quote_id'] ? $options_data['quote_id'] : __('Quote Request', 'redq-rental'),
                        'value' => '#' . $rental_data['quote_id'],
                        'display' => ''
                    );
                }
                // code for pick up location binal
                if (isset($rental_data['pickup_location'])) {
                    $custom_data[] = array(
                        'name' => $labels['pickup_location'],
                        'value' => $rental_data['pickup_location']['address'],
                        'display' => ''
                    );
                    
                }

                //$product_id =  $rental_data['booking_inventory'];
                    //print_r($rental_data);
                    $pickUpDate =  $rental_data['pickup_date'];
                    $dropOffDate = $rental_data['dropoff_date'];
                    $size = $rental_data['payable_resource'][0]['resource_name'];
                    

                    $availalble_resources = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Equipments/GetAvailableEquipmentbyDateRange?EquipmentCategory=3&StartDate=$pickUpDate&EndDate=$dropOffDate");
                    $eqp_data = $availalble_resources->Result;
                    
    
                    //echo $product_id;

                    global $assigned_array;
                    if($product_id == 1145 || $product_id == 1146)
                    {
                        //$size = "20";
                        $size = substr($size, 0, 2);
                    }
                    else
                    {
                        $size = substr($size, 0, 2);  
                    }
                    //echo $size;
                    if($product_id == 1145)
                    {
                    //  echo "Const1";
                        foreach($eqp_data as $data)
                        {
                            
                            if($data->EquipmentType==6 && !in_array($data->EquipmentId,$assigned_array))
                            {
                                $assigned_array[] = $data->EquipmentName;
                                
                            }
                        }
                        //echo "Const2";
                        foreach($eqp_data as $data)
                        {
                            if($data->EquipmentType==3 && !in_array($data->EquipmentId,$assigned_array))
                            {
                                $assigned_array[] = $data->EquipmentName;
                                
                            }
                        }
                        //echo "<pre>";
                        //print_r($data);
                    }
                    
                    //die;
                    if($product_id == 1146)
                    {
                        foreach($eqp_data as $data)
                        {
                            //echo $data->EquipmentType.'<br />';
                            if($data->EquipmentType==5 && !in_array($data->EquipmentId,$assigned_array))
                            {

                                $assigned_array[] = $data->EquipmentName;
                                

                            }
                        }       
                    }

                    if($product_id == 1090)
                    {
                        foreach($eqp_data as $data)
                        {
                            //echo $data->EquipmentType.'-'.$size.'<br />';
                            if($data->EquipmentType==2 && $size==10 && !in_array($data->EquipmentId,$assigned_array))
                            {
                                $assigned_array[] = $data->EquipmentName;
                            }

                            if($data->EquipmentType==3 && $size==20 && !in_array($data->EquipmentId,$assigned_array))
                            {
                                //print_r($data);
                                if($rental_data['pickup_location']['address']=="Storage @ Strathclyde")
                                {
                                    //$data->ConditionDescription=="Strathclyde";
                                    if($data->ConditionDescription=="Strathclyde" && !in_array($data->EquipmentId,$assigned_array))
                                    {
                                        //echo $data->EquipmentId;
                                        $assigned_array[] = $data->EquipmentName;
                                        //print_r($data);
                                        
                                    }
                                    
                                }
                                else
                                {
                                    
                                    if($data->ConditionDescription!="Strathclyde" && !in_array($data->EquipmentId,$assigned_array))
                                    {
                                        //echo $data->EquipmentId;
                                        $assigned_array[] = $data->EquipmentName;
                                       
                                    
                                    }
                                }   
                                
                            }

                            if($data->EquipmentType==4 && $size==30 && !in_array($data->EquipmentId,$assigned_array))
                            {
                                $assigned_array[] = $data->EquipmentName;
                            }
                        }       
                    }
                    $EquipmentName = $assigned_array[0];
                    $custom_data[] = array(
                        'name' => "Equipment Name",
                        'value' => $EquipmentName,
                        'display' => ''
                    );
                if (isset($rental_data['pickup_location']) && !empty($rental_data['pickup_location']['cost'])) {
                    $custom_data[] = array(
                        'name' => $labels['pickup_location'] . __(' Cost', 'redq-rental'),
                        'value' => wc_price($rental_data['pickup_location']['cost']),
                        'display' => ''
                    );

                }

                if (isset($rental_data['dropoff_location'])) {
                    $custom_data[] = array(
                        'name' => $labels['return_location'],
                        'value' => $rental_data['dropoff_location']['address'],
                        'display' => ''
                    );
                }

                if (isset($rental_data['dropoff_location']) && !empty($rental_data['dropoff_location']['cost'])) {
                    $custom_data[] = array(
                        'name' => $labels['return_location'] . __(' Cost', 'redq-rental'),
                        'value' => wc_price($rental_data['dropoff_location']['cost']),
                        'display' => ''
                    );
                }

                if (isset($rental_data['location_cost'])) {
                    $custom_data[] = array(
                        'name' => esc_html__('Location Cost', 'redq-rental'),
                        'value' => wc_price($rental_data['location_cost']),
                        'display' => ''
                    );
                }

                if (isset($rental_data['payable_cat'])) {
                    $cat_name = '';
                    foreach ($rental_data['payable_cat'] as $key => $value) {
                        if ($value['multiply'] === 'per_day') {
                            $cat_name .= $value['name'] . '×' . $value['quantity'] . ' ( ' . wc_price($value['cost']) . ' - ' . __('Per Month', 'redq-rental') . ' )' . ' , <br> ';
                        } else {
                            $cat_name .= $value['name'] . '×' . $value['quantity'] . ' ( ' . wc_price($value['cost']) . ' - ' . __('One Time', 'redq-rental') . ' )' . ' , <br> ';
                        }
                    }
                    $custom_data[] = array(
                        'name' => $labels['categories'],
                        'value' => $cat_name,
                        'display' => ''
                    );
                }

                if (isset($rental_data['payable_resource'])) {
                    $resource_name = '';
                    foreach ($rental_data['payable_resource'] as $key => $value) {
                        if ($value['cost_multiply'] === 'per_day') {
                            $resource_name .= $value['resource_name'] . ' ( ' . wc_price($value['resource_cost']) . ' - ' . __('Per Month', 'redq-rental') . ' )' . ' , <br> ';
                        } else {
                            $resource_name .= $value['resource_name'] . ' ( ' . wc_price($value['resource_cost']) . ' - ' . __('One Time', 'redq-rental') . ' )' . ' , <br> ';
                        }
                    }
                    $custom_data[] = array(
                        'name' => $labels['resource'],
                        'value' => $resource_name,
                        'display' => ''
                    );
                }

                if (isset($rental_data['payable_security_deposites'])) {
                    $security_deposite_name = '';
                    foreach ($rental_data['payable_security_deposites'] as $key => $value) {
                        if ($value['cost_multiply'] === 'per_day') {
                            $security_deposite_name .= $value['security_deposite_name'] . ' ( ' . wc_price($value['security_deposite_cost']) . ' - ' . __('Per Month', 'redq-rental') . ' )' . ' , <br> ';
                        } else {
                            $security_deposite_name .= $value['security_deposite_name'] . ' ( ' . wc_price($value['security_deposite_cost']) . ' - ' . __('One Time', 'redq-rental') . ' )' . ' , <br> ';
                        }
                    }
                    $custom_data[] = array(
                        'name' => $labels['deposite'],
                        'value' => $security_deposite_name,
                        'display' => ''
                    );
                }

                if (isset($rental_data['adults_info'])) {
                    $custom_data[] = array(
                        'name' => $labels['adults'],
                        'value' => $rental_data['adults_info']['person_count'],
                        'display' => ''
                    );
                }

                if (isset($rental_data['childs_info'])) {
                    $custom_data[] = array(
                        'name' => $labels['childs'],
                        'value' => $rental_data['childs_info']['person_count'],
                        'display' => ''
                    );
                }


                if (isset($rental_data['pickup_date']) && $displays['pickup_date'] === 'open') {

                    $pickup_date_time = convert_to_output_format($rental_data['pickup_date'], $conditional_data['date_format']);

                    if (isset($rental_data['pickup_time'])) {
                        $pickup_date_time = $pickup_date_time . ' ' . esc_html__('at', 'redq-rental') . ' ' . $rental_data['pickup_time'];
                    }
                    $custom_data[] = array(
                        'name' => $labels['pickup_datetime'],
                        'value' => $pickup_date_time,
                        'display' => ''
                    );
                }

                if (isset($rental_data['dropoff_date']) && $displays['return_date'] === 'open') {

                    $return_date_time = convert_to_output_format($rental_data['dropoff_date'], $conditional_data['date_format']);

                    if (isset($rental_data['dropoff_time'])) {
                        $return_date_time = $return_date_time . ' ' . esc_html__('at', 'redq-rental') . ' ' . $rental_data['dropoff_time'];
                    }

                    $custom_data[] = array(
                        'name' => $labels['return_datetime'],
                        'value' => $return_date_time,
                        'display' => ''
                    );
                }

                if (isset($rental_data['booking_inventory']) && !empty( $rental_data['booking_inventory'] ) ) {
 
                    $custom_data[] = array(
                        'name' => $labels['inventory'],
                        'value' => get_the_title( $rental_data['booking_inventory'] ),
                        'display' => ''
                    );
                }

                if (isset($rental_data['rental_days_and_costs'])) {

                    if ($rental_data['rental_days_and_costs']['pricing_type'] === 'flat_hours') {
                        $custom_data[] = array(
                            'name' => $general_data['total_hours'] ? $general_data['total_hours'] : esc_html__('Total Hours', 'redq-rental'),
                            'value' => $rental_data['rental_days_and_costs']['flat_hours'],
                            'display' => ''
                        );
                    } 

                    if ($rental_data['rental_days_and_costs']['days'] <= 0 && $rental_data['rental_days_and_costs']['pricing_type'] !== 'flat_hours') {
                        $custom_data[] = array(
                            'name' => $general_data['total_hours'] ? $general_data['total_hours'] : esc_html__('Total Hours', 'redq-rental'),
                            'value' => $rental_data['rental_days_and_costs']['hours'],
                            'display' => ''
                        );
                    }   
                    
                    if ($rental_data['rental_days_and_costs']['days'] > 0 && $rental_data['rental_days_and_costs']['pricing_type'] !== 'flat_hours') {
                        $custom_data[] = array(
                            'name' => esc_html__('Total Months', 'redq-rental'),
                            //'value' => ceil($rental_data['rental_days_and_costs']['days']*0.0328767),
                             'value' => $_SESSION['diffMonths'],
                            'display' => ''
                        );

						
                    }                                     
                    
                    if (!empty($rental_data['rental_days_and_costs']['due_payment'])) {
                       /*  $custom_data[] = array(
                           'name' => $general_data['payment_due'] ? $general_data['payment_due'] : esc_html__('Due Payment', 'redq-rental'),
                            'value' => wc_price($rental_data['rental_days_and_costs']['due_payment']),
                            'display' => ''
                        );
						*/
                    }
                }
            }
        }

        return $custom_data;
    }


    /**
     * Checking Processed Data
     *
     * @param  string order_id , array $posted_data, object order
     * @return array
     */
    public function redq_rental_checkout_order_process()
    {

        $cart_items = WC()->cart->get_cart();

        //Check if rentable is no
        if (isset($cart_items) && !empty($cart_items)) :            
            foreach ($cart_items as $cart_item) {
                $product_id = $cart_item['product_id'];
                $product_type = wc_get_product($product_id)->get_type();
                $get_conditions = reddq_rental_get_settings($product_id, 'conditions');
                $conditional_data = $get_conditions['conditions'];
                if (isset($product_type) && $product_type !== 'redq_rental') return;
                if($conditional_data['blockable'] === 'no') return;
            }            
        endif;       

        //Checking available quantity in both cart item and previously booked dates
        if (isset($cart_items) && !empty($cart_items)) :
            foreach ($cart_items as $cart_item) {

                $quantity_ara = array();
                $product_id = $cart_item['product_id'];
                $product_type = wc_get_product($product_id)->get_type();

                if (isset($product_type) && $product_type !== 'redq_rental') return;

                $quantity = isset($cart_item['quantity']) ? $cart_item['quantity'] : 1;
                $rental_data = $cart_item['rental_data'];
                $dates = $rental_data['rental_days_and_costs']['booked_dates']['saved'];

                $pickup_datetime = '';
                $return_datetime = '';

                if( isset( $rental_data['pickup_date'] ) && !empty( $rental_data['pickup_date'] ) ) {
                    $date = date_create($rental_data['pickup_date']);
                    $pickup_datetime = date_format($date, "Y-m-d");
                }

                if( isset( $rental_data['pickup_time'] ) && !empty( $rental_data['pickup_time'] ) ) {
                    $pickup_datetime .= ' ' . $rental_data['pickup_time'];       
                } else {
                    $pickup_datetime .= ' 00:00';
                }

                if( isset( $rental_data['dropoff_date'] ) && !empty( $rental_data['dropoff_date'] ) ) {
                    $date = date_create($rental_data['dropoff_date']);
                    $return_datetime = date_format($date, "Y-m-d");
                }

                if( isset( $rental_data['dropoff_time'] ) && !empty( $rental_data['dropoff_time'] ) ) {
                    $return_datetime .= ' ' . $rental_data['dropoff_time'];      
                } else {
                    $return_datetime .= ' 23:00';
                }
                
                $inventory_id = $rental_data['booking_inventory'];
                $check_inventory = array(
                    'pickup_datetime' => $pickup_datetime,
                    'return_datetime' => $return_datetime,
                    'inventory_id'    => $inventory_id,
                    'product_id'      => $product_id,
                    'quantity'        => get_post_meta( $inventory_id, 'quantity', true),
                );

                $available_qty = rnb_inventory_quantity_availability_check($check_inventory);

                if ($quantity > $available_qty) {
                    wc_add_notice(sprintf(__('Quantity %s is not available', 'redq-rental'), $quantity), 'error');
                    $this->send_ajax_failure_response();
                }
            }
        endif;
        //End checking available quantity        
    }



    /**
     * order_item_meta function
     *
     * @param  string $item_id , array $values
     * @return array
     */
    public function redq_rental_order_item_meta($item_id, $values, $order_id)
    {

        if (array_key_exists('legacy_values', $values)) {
            $product_id = $values->legacy_values['product_id'];
            $product_type = wc_get_product($product_id)->get_type();
        }

        if (isset($product_type) && $product_type === 'redq_rental') {

            $rental_data = $values->legacy_values['rental_data'];
            

            $options_data = array();
            $options_data['quote_id'] = '';
            $quantity = isset($rental_data['quantity']) ? $rental_data['quantity'] : 1;

            $get_labels = reddq_rental_get_settings($product_id, 'labels', array('pickup_location', 'return_location', 'pickup_date', 'return_date', 'resources', 'categories', 'person', 'deposites', 'inventory'));
            $labels = $get_labels['labels'];
            $get_displays = reddq_rental_get_settings($product_id, 'display');
            $displays = $get_displays['display'];
            $get_conditions = reddq_rental_get_settings($product_id, 'conditions');
            $conditional_data = $get_conditions['conditions'];
            $get_general = reddq_rental_get_settings($product_id, 'general');
            $general_data = $get_general['general'];

            if (isset($rental_data['quote_id'])) {
                wc_add_order_item_meta($item_id, $options_data['quote_id'] ? $options_data['quote_id'] : __('Quote Request', 'redq-rental'), $rental_data['quote_id']);
            }

            if (isset($rental_data['pickup_location'])) {
                wc_add_order_item_meta($item_id, $labels['pickup_location'], $rental_data['pickup_location']['address']);
            }

            if (isset($rental_data['pickup_location']) && !empty($rental_data['pickup_location']['cost'])) {
                wc_add_order_item_meta($item_id, $labels['pickup_location'] . __(' Cost', 'redq-rental'), wc_price($rental_data['pickup_location']['cost']));
            }

            if (isset($rental_data['dropoff_location'])) {
                wc_add_order_item_meta($item_id, $labels['return_location'], $rental_data['dropoff_location']['address']);
            }

            if (isset($rental_data['dropoff_location']) && !empty($rental_data['dropoff_location']['cost'])) {
                wc_add_order_item_meta($item_id, $labels['return_location'] . __(' Cost', 'redq-rental'), wc_price($rental_data['dropoff_location']['cost']));
            }

            if (isset($rental_data['location_cost']) && !empty($rental_data['location_cost'])) {
                wc_add_order_item_meta($item_id, esc_html__('Location Cost', 'redq-rental'), wc_price($rental_data['location_cost']));
            }

            if (isset($rental_data['payable_cat'])) {
                $rnb_cat = '';
                foreach ($rental_data['payable_cat'] as $key => $value) {
                    if ($value['multiply'] === 'per_day') {
                        $rnb_cat .= $value['name'] . '×' . $value['quantity'] . ' ( ' . wc_price($value['cost']) . ' - ' . __('Per Month', 'redq-rental') . ' )' . ' , <br> ';
                    } else {
                        $rnb_cat .= $value['name'] . '×' . $value['quantity'] . ' ( ' . wc_price($value['cost']) . ' - ' . __('One Time', 'redq-rental') . ' )' . ' , <br> ';
                    }
                }
                wc_add_order_item_meta($item_id, $labels['categories'], $rnb_cat);
            }

            if (isset($rental_data['payable_resource'])) {
                $resource_name = '';
                foreach ($rental_data['payable_resource'] as $key => $value) {
                    if ($value['cost_multiply'] === 'per_day') {
                        $resource_name .= $value['resource_name'] . ' ( ' . wc_price($value['resource_cost']) . ' - ' . __('Per Month', 'redq-rental') . ' )' . ' , <br> ';
                    } else {
                        $resource_name .= $value['resource_name'] . ' ( ' . wc_price($value['resource_cost']) . ' - ' . __('One Time', 'redq-rental') . ' )' . ' , <br> ';
                    }
                }
                wc_add_order_item_meta($item_id, $labels['resource'], $resource_name);
            }

            if (isset($rental_data['payable_security_deposites'])) {
                $security_deposite_name = '';
                foreach ($rental_data['payable_security_deposites'] as $key => $value) {
                    if ($value['cost_multiply'] === 'per_day') {
                        $security_deposite_name .= $value['security_deposite_name'] . ' ( ' . wc_price($value['security_deposite_cost']) . ' - ' . __('Per Month', 'redq-rental') . ' )' . ' , <br> ';
                    } else {
                        $security_deposite_name .= $value['security_deposite_name'] . ' ( ' . wc_price($value['security_deposite_cost']) . ' - ' . __('One Time', 'redq-rental') . ' )' . ' , <br> ';
                    }
                }
                wc_add_order_item_meta($item_id, $labels['deposite'], $security_deposite_name);
            }

            if (isset($rental_data['adults_info'])) {
                wc_add_order_item_meta($item_id, $labels['adults'], $rental_data['adults_info']['person_count']);
            }

            if (isset($rental_data['childs_info'])) {
                wc_add_order_item_meta($item_id, $labels['childs'], $rental_data['childs_info']['person_count']);
            }

            if (isset($rental_data['pickup_date']) && $displays['pickup_date'] === 'open') {

                $pickup_date_time = convert_to_output_format($rental_data['pickup_date'], $conditional_data['date_format']);

                $ptime = '';

                if (isset($rental_data['pickup_time'])) {
                    $pickup_date_time = $pickup_date_time . ' ' . esc_html__('at', 'redq-rental') . ' ' . $rental_data['pickup_time'];
                    $ptime = $rental_data['pickup_time'];
                } else {
                    $ptime = '00:00';
                }

                wc_add_order_item_meta($item_id, $labels['pickup_datetime'], $pickup_date_time);
                wc_add_order_item_meta($item_id, 'pickup_hidden_datetime', $rental_data['pickup_date'] . '|' . $ptime);
            }

            if (isset($rental_data['dropoff_date']) && $displays['return_date'] === 'open') {

                $return_date_time = convert_to_output_format($rental_data['dropoff_date'], $conditional_data['date_format']);
                $rtime = '';

                if (isset($rental_data['dropoff_time'])) {
                    $return_date_time = $return_date_time . ' ' . esc_html__('at', 'redq-rental') . ' ' . $rental_data['dropoff_time'];
                    $rtime = $rental_data['dropoff_time'];
                } else {
                    $rtime = '23:00';
                }
                
                wc_add_order_item_meta($item_id, $labels['return_datetime'], $return_date_time);
                wc_add_order_item_meta($item_id, 'return_hidden_datetime', $rental_data['dropoff_date'] . '|' . $rtime);
            }

            if (isset($rental_data['rental_days_and_costs'])) {

                if ($rental_data['rental_days_and_costs']['pricing_type'] === 'flat_hours' ) {
                    wc_add_order_item_meta($item_id, $general_data['total_hours'] ? $general_data['total_hours'] : esc_html__('Total Hours', 'redq-rental'), $rental_data['rental_days_and_costs']['flat_hours']);
                    if($rental_data['rental_days_and_costs']['days'] > 0) {
                        wc_add_order_item_meta($item_id, 'return_hidden_days', $rental_data['rental_days_and_costs']['days']);
                    }                    
                }

                if ($rental_data['rental_days_and_costs']['days'] > 0 && $rental_data['rental_days_and_costs']['pricing_type'] !== 'flat_hours' ) {
                    wc_add_order_item_meta($item_id, esc_html__('Total Months', 'redq-rental'), ceil($rental_data['rental_days_and_costs']['days']*0.0328767));
                    wc_add_order_item_meta($item_id, 'return_hidden_days', $rental_data['rental_days_and_costs']['days']);
                } 
                
                if ($rental_data['rental_days_and_costs']['days'] <= 0 && $rental_data['rental_days_and_costs']['pricing_type'] !== 'flat_hours' ) {
                    wc_add_order_item_meta($item_id, $general_data['total_hours'] ? $general_data['total_hours'] : esc_html__('Total Hours', 'redq-rental'), $rental_data['rental_days_and_costs']['hours']);
                } 

                if (!empty($rental_data['rental_days_and_costs']['due_payment'])) {
                    wc_add_order_item_meta($item_id, $general_data['payment_due'] ? $general_data['payment_due'] : esc_html__('Due Payment', 'redq-rental'), wc_price($rental_data['rental_days_and_costs']['due_payment']));
                }
            }

            // Start inventory post meta update from here
            $booked_dates_ara = isset($rental_data['rental_days_and_costs']['booked_dates']['saved']) ? $rental_data['rental_days_and_costs']['booked_dates']['saved'] : array();


            $inventory_id = $rental_data['booking_inventory'];

            $pickup_datetime = '';
            $return_datetime = '';

            if( isset( $rental_data['pickup_date'] ) && !empty( $rental_data['pickup_date'] ) ) {
                $date = date_create($rental_data['pickup_date']);
                $pickup_datetime = date_format($date, "Y-m-d");
            }

            if( isset( $rental_data['pickup_time'] ) && !empty( $rental_data['pickup_time'] ) ) {
                $pickup_datetime .= ' ' . $rental_data['pickup_time'];       
            } else {
                $pickup_datetime .= ' 00:00';
            }

            if( isset( $rental_data['dropoff_date'] ) && !empty( $rental_data['dropoff_date'] ) ) {
                $date = date_create($rental_data['dropoff_date']);
                $return_datetime = date_format($date, "Y-m-d");
            }

            if( isset( $rental_data['dropoff_time'] ) && !empty( $rental_data['dropoff_time'] ) ) {
                $return_datetime .= ' ' . $rental_data['dropoff_time'];      
            } else {
                $return_datetime .= ' 23:00';
            }

            $booked_dates_ara = array(
                'pickup_datetime' => $pickup_datetime,
                'return_datetime' => $return_datetime,
                'inventory_id'    => $inventory_id,
                'product_id'      => $product_id,
                'quantity'        => get_post_meta( $inventory_id, 'quantity', true),
            );

            wc_add_order_item_meta($item_id, 'booking_inventory', $inventory_id);
            wc_add_order_item_meta($item_id, $labels['inventory'], get_the_title( $inventory_id ) );

            rnb_process_rental_order_data($product_id, $order_id, $item_id, $inventory_id, $booked_dates_ara, $quantity);

        }
    }


    // AJAX ADD TO CART FROM QUOTE
    public function quote_booking_data()
    {

        $quote_id = $_POST['quote_id'];
        $product_id = $_POST['product_id'];
        $cart_data = array();
        $posted_data = array();

        $quote_meta = json_decode(get_post_meta($quote_id, 'order_quote_meta', true), true);

        if (isset($quote_meta) && is_array($quote_meta)) :
            foreach ($quote_meta as $key => $value) {
            if (isset($quote_meta[$key]['name'])) :
                $posted_data[$quote_meta[$key]['name']] = $quote_meta[$key]['value'];
            endif;
        }
        endif;


        $posted_data['quote_id'] = $quote_id;
        $ajax_data = $this->get_posted_data($product_id, $posted_data);
        $cart_data['rental_data'] = $ajax_data;
        if (WC()->cart->add_to_cart($product_id, $quantity = 1, $variation_id = '', $variation = '', $cart_data)) {
            echo json_encode(array(
                'success' => true,
            ));
        }

        wp_die();
    }


    /**
     * Return all post data for rental
     *
     * @param  string $product_id , array $posted_data
     * @return array
     */
    public function get_posted_data($product_id, $posted_data)
    {
        
        $payable_cat = array();
        $payable_resource = array();
        $payable_security_deposites = array();
        $adults_info = array();
        $childs_info = array();
        $pickup_location = array();
        $dropoff_location = array();
        $data = array();

        $conditional_data = reddq_rental_get_settings($product_id, 'conditions');

        if (isset($posted_data['booking_inventory']) && !empty($posted_data['booking_inventory'])) {
            $data['booking_inventory'] = $posted_data['booking_inventory'];
        }

        // $pricing_data = redq_rental_get_pricing_data($product_id);
        $pricing_data = redq_rental_get_pricing_data($data['booking_inventory'], $product_id);
        

        $conditional_data = $conditional_data['conditions'];
        $euro_format = $conditional_data['euro_format'];


        if (isset($posted_data['quote_id']) && !empty($posted_data['quote_id'])) {
            $data['quote_id'] = $posted_data['quote_id'];
        }

        if (isset($posted_data['categories']) && !empty($posted_data['categories'])) {
            foreach ($posted_data['categories'] as $key => $value) {
                $categories = explode('|', $value);
                $payable_cat[$key]['name'] = $categories[0];
                $payable_cat[$key]['cost'] = $categories[1];
                $payable_cat[$key]['multiply'] = $categories[2];
                $payable_cat[$key]['hourly_cost'] = $categories[3];
                $payable_cat[$key]['quantity'] = $categories[4];
            }
            $data['payable_cat'] = $payable_cat;
        }

        if (isset($posted_data['extras']) && !empty($posted_data['extras'])) {
            foreach ($posted_data['extras'] as $key => $value) {
                $extras = explode('|', $value);
                $payable_resource[$key]['resource_name'] = $extras[0];
                $payable_resource[$key]['resource_cost'] = $extras[1];
                $payable_resource[$key]['cost_multiply'] = $extras[2];
                $payable_resource[$key]['resource_hourly_cost'] = $extras[3];
            }
            $data['payable_resource'] = $payable_resource;
        }

        if (isset($posted_data['security_deposites']) && !empty($posted_data['security_deposites'])) {
            foreach ($posted_data['security_deposites'] as $key => $value) {
                $extras = explode('|', $value);
                $payable_security_deposites[$key]['security_deposite_name'] = $extras[0];
                $payable_security_deposites[$key]['security_deposite_cost'] = $extras[1];
                $payable_security_deposites[$key]['cost_multiply'] = $extras[2];
                $payable_security_deposites[$key]['security_deposite_hourly_cost'] = $extras[3];
            }
            $data['payable_security_deposites'] = $payable_security_deposites;
        }

        if (isset($posted_data['additional_adults_info']) && !empty($posted_data['additional_adults_info'])) {

            $person = explode('|', $posted_data['additional_adults_info']);
            $adults_info['person_count'] = $person[0];
            $adults_info['person_cost'] = $person[1];
            $adults_info['cost_multiply'] = $person[2];
            $adults_info['person_hourly_cost'] = $person[3];

            $data['adults_info'] = $adults_info;
        }


        if (isset($posted_data['additional_childs_info']) && !empty($posted_data['additional_childs_info'])) {

            $person = explode('|', $posted_data['additional_childs_info']);
            $childs_info['person_count'] = $person[0];
            $childs_info['person_cost'] = $person[1];
            $childs_info['cost_multiply'] = $person[2];
            $childs_info['person_hourly_cost'] = $person[3];

            $data['childs_info'] = $childs_info;
        }


        if ($conditional_data['booking_layout'] === 'layout_one') {
            if (isset($posted_data['pickup_location']) && !empty($posted_data['pickup_location'])) {
                $pickup_location_split = explode('|', $posted_data['pickup_location']);
                $pickup_location['address'] = $pickup_location_split[0];
                $pickup_location['title'] = $pickup_location_split[1];
                $pickup_location['cost'] = $pickup_location_split[2];
                $data['pickup_location'] = $pickup_location;
            }

            if (isset($posted_data['dropoff_location']) && !empty($posted_data['dropoff_location'])) {

                $dropoff_location_split = explode('|', $posted_data['dropoff_location']);
                $dropoff_location['address'] = $dropoff_location_split[0];
                $dropoff_location['title'] = $dropoff_location_split[1];
                $dropoff_location['cost'] = $dropoff_location_split[2];

                $data['dropoff_location'] = $dropoff_location;
            }
        } else {
            if (isset($posted_data['pickup_location']) && !empty($posted_data['pickup_location'])) {
                $pickup_location['address'] = $posted_data['pickup_location'];
                $pickup_location['title'] = $posted_data['pickup_location'];
                $data['pickup_location'] = $pickup_location;
            }
            if (isset($posted_data['dropoff_location']) && !empty($posted_data['dropoff_location'])) {
                $dropoff_location['address'] = $posted_data['dropoff_location'];
                $dropoff_location['title'] = $posted_data['dropoff_location'];
                $data['dropoff_location'] = $dropoff_location;
            }

            if (isset($posted_data['total_distance']) && !empty($posted_data['total_distance'])) {
                $distance = explode('|', $posted_data['total_distance']);
                $total_kilos = $distance[0] ? $distance[0] : '';
                $location_cost = floatval($pricing_data['perkilo_price']) * $total_kilos;
                $data['location_cost'] = $location_cost;
            }
        }


        if (isset($posted_data['pickup_date']) && !empty($posted_data['pickup_date'])) {
            $data['pickup_date'] = convert_to_generalized_format($posted_data['pickup_date'], $euro_format);
        }

        if (isset($posted_data['pickup_time']) && !empty($posted_data['pickup_time'])) {
            $data['pickup_time'] = $posted_data['pickup_time'];
        }

        if (isset($posted_data['dropoff_date']) && !empty($posted_data['dropoff_date'])) {
            $data['dropoff_date'] = convert_to_generalized_format($posted_data['dropoff_date'], $euro_format);
        }

        if (isset($posted_data['dropoff_time']) && !empty($posted_data['dropoff_time'])) {
            $data['dropoff_time'] = $posted_data['dropoff_time'];
        }

        if (isset($posted_data['inventory_quantity']) && !empty($posted_data['inventory_quantity'])) {
            $data['quantity'] = $posted_data['inventory_quantity'];
        }


        if (isset($data['pickup_date']) && !empty($data['pickup_date']) && !isset($data['dropoff_date']) && empty($data['dropoff_date'])) {
            if (!isset($data['pickup_time']) || !isset($data['dropoff_time'])) {
                $data['dropoff_date'] = $data['pickup_date'];
            } else {
                $data['dropoff_date'] = $data['pickup_date'];
            }
        }

        if (isset($data['pickup_time']) && !empty($data['pickup_time']) && !isset($data['dropoff_time']) && empty($data['dropoff_time'])) {
            $data['dropoff_time'] = $data['pickup_time'];
        }

        if (isset($data['dropoff_date']) && !empty($data['dropoff_date']) && !isset($data['pickup_date']) && empty($data['pickup_date'])) {
            if (!isset($data['pickup_time']) || !isset($data['dropoff_time'])) {
                $data['pickup_date'] = $data['dropoff_date'];
            } else {
                $data['pickup_date'] = $data['dropoff_date'];
            }
        }

        if (isset($data['dropoff_time']) && !empty($data['dropoff_time']) && !isset($data['pickup_time']) && empty($data['pickup_time'])) {
            $data['pickup_time'] = $data['dropoff_time'];
        }

        $cost_calculation = $this->calculate_cost($product_id, $data['booking_inventory'], $data, $conditional_data);

        $data['rental_days_and_costs'] = $cost_calculation;
        $data['max_hours_late'] = get_post_meta($product_id, 'redq_max_time_late', true);


        if ($conditional_data['euro_format'] == 'yes') {
            $data['pickup_date'] = str_replace('.', '/', $data['pickup_date']);
        } else {
            $data['pickup_date'] = $data['pickup_date'];
        }

        if ($conditional_data['euro_format'] == 'yes') {
            $data['dropoff_date'] = str_replace('.', '/', $data['dropoff_date']);
        } else {
            $data['dropoff_date'] = $data['dropoff_date'];
        }



        return $data;
    }


    /**
     * Return rental cost and days
     *
     * @param  string $key , array $data
     * @return array
     */
    public function calculate_cost($product_id, $inventory_id, $data, $conditional_data)
    {

        $payable_person = array();

        $pricing_data = redq_rental_get_pricing_data($inventory_id, $product_id);
        $calculate_cost_and_day = array();

        $location_cost = isset($data['location_cost']) ? $data['location_cost'] : 0;
        $pickup_cost = isset($data['pickup_location']['cost']) ? $data['pickup_location']['cost'] : 0;
        $dropoff_cost = isset($data['dropoff_location']['cost']) ? $data['dropoff_location']['cost'] : 0;
        $payable_cat = isset($data['payable_cat']) ? $data['payable_cat'] : array();           
        $payable_resource = isset($data['payable_resource']) ? $data['payable_resource'] : array();
        $payable_security_deposites = isset($data['payable_security_deposites']) ? $data['payable_security_deposites'] : array();
        $adults_info = isset($data['adults_info']) ? $data['adults_info'] : [];
        $childs_info = isset($data['childs_info']) ? $data['childs_info'] : array();
        $payable_person['adults'] = $adults_info;
        $payable_person['childs'] = $childs_info;

        $pickup_date = isset($data['pickup_date']) ? $data['pickup_date'] : '';        
        $pickup_time = isset($data['pickup_time']) ? $data['pickup_time'] : '';       
        $dropoff_date = isset($data['dropoff_date']) ? $data['dropoff_date'] : '';
        $dropoff_time = isset($data['dropoff_time']) ? $data['dropoff_time'] : '';

        $price_discount = isset($pricing_data['price_discount']) && $pricing_data['price_discount'] ? $pricing_data['price_discount'] : array();

        $days = $this->calculate_rental_days($data, $conditional_data);

        $pricing_type = $pricing_data['pricing_type'];

        $calculate_cost_and_day['pricing_type'] = $pricing_type;
        $calculate_cost_and_day['flat_hours'] = $days['flat_hours'];
        $calculate_cost_and_day['days'] = $days['days'];
        $calculate_cost_and_day['hours'] = $days['hours'];
        $calculate_cost_and_day['booked_dates'] = $days['booked_dates'];       

        $booking_args = array(
            'pricing_data' => $pricing_data,
            //'extra_hours_payment' => $conditional_data['extra_hours_payment'],
            'extra_hours_payment' => '',
            'pickup_date' => $pickup_date,
            'durations' => $days,
            'payable_cat' => $payable_cat,
            'payable_resource' => $payable_resource,
            'payable_person' => $payable_person,
            'payable_security_deposites' => $payable_security_deposites,
            'pickup_cost' => $pickup_cost,
            'dropoff_cost' => $dropoff_cost,
            'location_cost' => $location_cost
        );

        switch ($pricing_type) {
            case 'flat_hours':
                $cost = $this->calculate_flat_hours_pricing_plan_cost($booking_args);
                break;  
            case 'general_pricing':
                $cost = $this->calculate_general_pricing_plan_cost($booking_args);
                break;
            case 'daily_pricing':
                $cost = $this->calculate_daily_pricing_plan_cost($booking_args);
                break;
            case 'monthly_pricing':
                $cost = $this->calculate_monthly_pricing_plan_cost($booking_args);
                break;
            case 'days_range':
                $cost = $this->calculate_day_ranges_pricing_plan_cost($booking_args);
                break;                     
        }        
   
        $pre_payment_percentage = get_option('rnb_instance_payment');

        if (empty($pre_payment_percentage)) {
            $pre_payment_percentage = 100;
        }

        $instance_payment = ($cost * $pre_payment_percentage) / 100;
        $due_payment = $cost - $instance_payment;

        $calculate_cost_and_day['cost'] = $instance_payment;
        $calculate_cost_and_day['due_payment'] = $due_payment;

        return $calculate_cost_and_day;    
    }



    /**
     * Calculate total rental days
     *
     * @param  array $data
     * @return string
     */
    public function calculate_rental_days($data, $conditional_data)
    {

        $durations = array();
        $choose_euro_format = $conditional_data['euro_format'];
        $max_hours_late = $conditional_data['max_time_late'];
        $output_format = $conditional_data['date_format'];


        $pickup_date = isset($data['pickup_date']) ? $data['pickup_date'] : '';
        $dropoff_date = isset($data['dropoff_date']) ? $data['dropoff_date'] : '';
        $pickup_time = isset($data['pickup_time']) ? $data['pickup_time'] : '';
        $dropoff_time = isset($data['dropoff_time']) ? $data['dropoff_time'] : '';

        $formated_pickup_time = date("H:i", strtotime($pickup_time));
        $formated_dropoff_time = date("H:i", strtotime($dropoff_time));
        $pickup_date_time = strtotime("$pickup_date $formated_pickup_time");
        $dropoff_date_time = strtotime("$dropoff_date $formated_dropoff_time");

        $hours = abs($pickup_date_time - $dropoff_date_time) / (60 * 60);
        $total_hours = 0;
        $extra_hours = $hours % 24;

        $enable_single_day_time_booking = $conditional_data['single_day_booking'];

        if ($hours < 24) {
            if ($enable_single_day_time_booking == 'open') {
                $days = 1;
            } else {
                $days = 0;
            }
            $total_hours = ceil($hours);
        } else {
            $days = intval($hours / 24);           

            if ($enable_single_day_time_booking == 'open') {
                if ($extra_hours >= floatval($max_hours_late)) {
                    $days = $days + 1;
                }
            } else {
                if ($extra_hours > floatval($max_hours_late)) {
                    $days = $days + 1;
                }
            }
        }

        $booked_dates = array();
        $current = strtotime($pickup_date);
        $count = 0;

        while ($count < $days) {
            $day = strtotime('+' . $count . ' day', $current);
            $booked_dates['formatted'][] = date($output_format, $day);
            $booked_dates['saved'][] = date('Y-m-d', $day);
            $booked_dates['iso'][] = $day;
            $count++;
        }

        $durations['flat_hours'] = $hours;
        $durations['days'] = $days;
        $durations['hours'] = $total_hours;
        $durations['extra_hours'] = $extra_hours;
        $durations['booked_dates'] = $booked_dates;
        
        return $durations;
    }

    /**
	 * Calculate hourly pricing
	 *
	 * @param  array $data
	 * @return string
	 */
	public function calculate_hourly_price($hours, $pricing_data){

		$cost = 0;
		$flag = 0;

		if($pricing_data['hourly_pricing_type'] === 'hourly_general'){
			$cost = intval($hours)*floatval($pricing_data['hourly_general']);
		}

		if($pricing_data['hourly_pricing_type'] === 'hourly_range'){
			$hourly_ranges_pricing_plan = $pricing_data['hourly_range'];
			foreach ($hourly_ranges_pricing_plan as $key => $value) {
				if($flag == 0){
					if($value['cost_applicable'] === 'per_hour'){
						if(intval($value['min_hours']) <= intval($hours) && intval($value['max_hours']) >= intval($hours)){
							$cost = floatval($value['range_cost']) * intval($hours);
							$flag = 1;
						}
					}else{
						if(intval($value['min_hours']) <= intval($hours) && intval($value['max_hours']) >= intval($hours)){
							$cost = floatval($value['range_cost']);
							$flag = 1;
						}
					}
				}
			}
		}

		return $cost;
    }
    
    /**
     * Calculate Flat hours pricing plan's cost
     *
     * @param  string $general_pricing, string $days, array $payable_resource, array $payable_person
     * @return string
     */
    public function calculate_flat_hours_pricing_plan_cost($booking_args)
    {
        extract($booking_args);

        $cost = 0;
        $total_hours = ceil($durations['flat_hours']);      

        $cost = $this->calculate_hourly_price($total_hours, $pricing_data);
        $cost = $this->calculate_hourly_extras_cost($cost, $total_hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, true);

        return $cost;
    }

    /**
     * Calculate general pricing plan's cost
     *
     * @param  string $general_pricing, string $days, array $payable_resource, array $payable_person
     * @return string
     */
    public function calculate_general_pricing_plan_cost($booking_args)
    {
        extract($booking_args);

        $cost = 0;
        $day_cost = 0;
        $hour_cost = 0;
        $days = $durations['days'];
        $hours = $durations['hours'];
        $extra_hours = $durations['extra_hours'];
        
        $general_pricing = $pricing_data['general_pricing'];
        $price_discount = isset( $pricing_data['price_discount'] ) ? $pricing_data['price_discount'] : [];

        if ($days > 0) {
            $rental_days = $extra_hours_payment === 'yes' && $extra_hours > 0 ? $days - 1 : $days;

            $day_cost = intval($rental_days) * floatval($general_pricing);
            $day_cost = $this->calculate_price_discount($day_cost, $price_discount, $rental_days);
            $day_cost = $this->calculate_extras_cost($day_cost, $rental_days, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost);
            
            if($extra_hours_payment === 'yes' && $extra_hours > 0){
                $hour_cost = $this->calculate_hourly_price($extra_hours, $pricing_data);
                $hour_cost = $this->calculate_hourly_extras_cost($hour_cost, $extra_hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, false);
            }

            $cost = $day_cost + $hour_cost;           
        } else {
            $cost = $this->calculate_hourly_price($hours, $pricing_data);
            $cost = $this->calculate_hourly_extras_cost($cost, $hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, true);
        }

        return $cost;
    }



    /**
     * Calculate daily pricing plan's cost
     *
     * @param  array $daily_pricing_plan, string $pickup_date, string $days, array $payable_resource, array $payable_person
     * @return string
     */
    public function calculate_daily_pricing_plan_cost( $booking_args )
    {
        extract($booking_args);

        $cost = 0;      
        $day_cost = 0;
        $hour_cost = 0;
        $days = $durations['days'];
        $hours = $durations['hours'];
        $extra_hours = $durations['extra_hours'];

        $daily_pricing_plan = $pricing_data['daily_pricing'];
        $price_discount = isset( $pricing_data['price_discount'] ) ? $pricing_data['price_discount'] : [];

        if ($days > 0) {

            $rental_days = $extra_hours_payment === 'yes' && $extra_hours > 0 ? $days - 1 : $days;

            for ($i = 0; $i < intval($rental_days); $i++) {
                $day = strtolower(date("l", strtotime($pickup_date . " +$i day")));
                $day_cost = $daily_pricing_plan[$day] != '' ?  $day_cost + floatval($daily_pricing_plan[$day]) : $day_cost + 0;
            } 

            $day_cost = $this->calculate_price_discount($day_cost, $price_discount, $rental_days);
            $day_cost = $this->calculate_extras_cost($day_cost, $rental_days, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost);
        
            if($extra_hours_payment === 'yes' && $extra_hours > 0){
                $hour_cost = $this->calculate_hourly_price($extra_hours, $pricing_data);
                $hour_cost = $this->calculate_hourly_extras_cost($hour_cost, $extra_hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, false);
            }
        
            $cost = $day_cost + $hour_cost;
        
        } else {
            $cost = $this->calculate_hourly_price($hours, $pricing_data);            
            $cost = $this->calculate_hourly_extras_cost($cost, $hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, true);
        }

        return $cost;
    }


    /**
     * Calculate monthly pricing plan's cost
     *
     * @param  array $monthly_pricing_plan, string $pickup_date, string $days, array $payable_resource, array $payable_person
     * @return string
     */
    public function calculate_monthly_pricing_plan_cost($booking_args)
    {
        extract($booking_args);      

        $cost = 0;      
        $day_cost = 0;
        $hour_cost = 0;
        $days = $durations['days'];
        $hours = $durations['hours'];
        $extra_hours = $durations['extra_hours'];

        $monthly_pricing_plan = $pricing_data['monthly_pricing'];
        $price_discount = isset( $pricing_data['price_discount'] ) ? $pricing_data['price_discount'] : [];

        if ($days > 0) {
            $rental_days = $extra_hours_payment === 'yes' && $extra_hours > 0 ? $days - 1 : $days;

            for ($i = 0; $i < intval($rental_days); $i++) {
                $month = strtolower(date("F", strtotime($pickup_date . " +$i day")));               
                $day_cost = $monthly_pricing_plan[$month] != '' ?  $day_cost + floatval($monthly_pricing_plan[$month]) : $day_cost + 0;                
            }
            $day_cost = $this->calculate_price_discount($day_cost, $price_discount, $rental_days);
            $day_cost = $this->calculate_extras_cost($day_cost, $rental_days, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost);
       
            if($extra_hours_payment === 'yes' && $extra_hours > 0){
                $hour_cost = $this->calculate_hourly_price($extra_hours, $pricing_data);
                $hour_cost = $this->calculate_hourly_extras_cost($hour_cost, $extra_hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, false);
            }
        
            $cost = $day_cost + $hour_cost;      
       
        } else {
            $cost = $this->calculate_hourly_price($hours, $pricing_data); 
            $cost = $this->calculate_hourly_extras_cost($cost, $hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, true);
        }

        return $cost;
    }


    /**
     * Calculate day ranges plan's cost
     *
     * @param  array $day_ranges_pricing_plan, string $pickup_date, string $days, array $payable_resource, array $payable_person
     * @return string
     */
    public function calculate_day_ranges_pricing_plan_cost( $booking_args )
    {
        extract($booking_args);        

        $cost = 0;  
        $flag = 0;    
        $day_cost = 0;
        $hour_cost = 0;
        $days = $durations['days'];
        $hours = $durations['hours'];
        $extra_hours = $durations['extra_hours'];

        $day_ranges_pricing_plan = $pricing_data['days_range'];
        $price_discount = isset( $pricing_data['price_discount'] ) ? $pricing_data['price_discount'] : [];        

        if ($days > 0) {

            $rental_days = $extra_hours_payment === 'yes' && $extra_hours > 0 ? $days - 1 : $days;

            foreach ($day_ranges_pricing_plan as $key => $value) {
                if ($flag == 0) {
                    if ($value['cost_applicable'] === 'per_day') {
                        if (intval($value['min_days']) <= intval($rental_days) && intval($value['max_days']) >= intval($rental_days)) {
                            $day_cost = floatval($value['range_cost']) * intval($rental_days);
                            $flag = 1;
                        }
                    } else {
                        if (intval($value['min_days']) <= intval($rental_days) && intval($value['max_days']) >= intval($rental_days)) {
                            $day_cost = floatval($value['range_cost']);
                            $flag = 1;
                        }
                    }
                }
            }

            $day_cost = $this->calculate_price_discount($day_cost, $price_discount, $rental_days);
            $day_cost = $this->calculate_extras_cost($day_cost, $rental_days, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, false);

            if($extra_hours_payment === 'yes' && $extra_hours > 0){
                $hour_cost = $this->calculate_hourly_price($extra_hours, $pricing_data);
                $hour_cost = $this->calculate_hourly_extras_cost($hour_cost, $extra_hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, false);
            }
        
            $cost = $day_cost + $hour_cost;

        } else {
            $cost = $this->calculate_hourly_price($hours, $pricing_data);
            $cost = $this->calculate_hourly_extras_cost($cost, $hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, true);
        }

        return $cost;
    }


    /**
     * Calculate price discount
     *
     * @param  string $cost, array $price_discount, string $days
     * @return string
     */
    public function calculate_price_discount($cost, $price_discount, $days)
    {

        $flag = 0;
        $discount_amount;
        $discount_type;

        foreach ($price_discount as $key => $value) {
            if ($flag == 0) {
                if (intval($value['min_days']) <= intval($days) && intval($value['max_days']) >= intval($days)) {
                    $discount_type = $value['discount_type'];
                    $discount_amount = $value['discount_amount'];
                    $flag = 1;
                }
            }
        }

        if (isset($discount_type) && !empty($discount_type) && isset($discount_amount) && !empty($discount_amount)) {
            if ($discount_type === 'percentage') {
                $cost = $cost - ($cost * $discount_amount) / 100;
            } else {
                $cost = $cost - $discount_amount;
            }
        }

        return $cost;
    }


    /**
     * Calculate resource and person cost
     *
     * @param  string $general_pricing, string $days, array $payable_resource, array $payable_person
     * @return string
     */
    public function calculate_extras_cost($cost, $days, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost)
    {

        if (isset($pickup_cost) && !empty($pickup_cost)) {
            $cost = floatval($cost) + floatval($pickup_cost);
        }

        if (isset($dropoff_cost) && !empty($dropoff_cost)) {
            $cost = floatval($cost) + floatval($dropoff_cost);
        }

        if (isset($location_cost) && !empty($location_cost)) {
            $cost = floatval($cost) + floatval($location_cost);
        }

        if (isset($payable_cat) && sizeof($payable_cat) != 0) {
            foreach ($payable_cat as $key => $value) {
                if ($value['multiply'] == 'per_day') {
                    $cost = floatval($cost) + intval($value['quantity']) * intval($days) * floatval($value['cost']);
                } else {
                    $cost = floatval($cost) + intval($value['quantity']) * floatval($value['cost']);
                }
            }
        }

        if (isset($payable_resource) && sizeof($payable_resource) != 0) {
            foreach ($payable_resource as $key => $value) {
                if ($value['cost_multiply'] == 'per_day') {
                    $cost = floatval($cost) + intval($days) * floatval($value['resource_cost']);
                } else {
                    $cost = floatval($cost) + floatval($value['resource_cost']);
                }
            }
        }

        if (isset($payable_security_deposites) && sizeof($payable_security_deposites) != 0) {
            foreach ($payable_security_deposites as $key => $value) {
                if ($value['cost_multiply'] == 'per_day') {
                    $cost = floatval($cost) + intval($days) * floatval($value['security_deposite_cost']);
                } else {
                    $cost = floatval($cost) + floatval($value['security_deposite_cost']);
                }
            }
        }

        $adults = $payable_person['adults'];
        $childs = $payable_person['childs'];

        if (isset($adults) && sizeof($adults) != 0) {
            if ($adults['cost_multiply'] == 'per_day') {
                $cost = floatval($cost) + intval($days) * floatval($adults['person_cost']);
            } else {
                $cost = floatval($cost) + floatval($adults['person_cost']);
            }
        }

        if (isset($childs) && sizeof($childs) != 0) {
            if ($childs['cost_multiply'] == 'per_day') {
                $cost = floatval($cost) + intval($days) * floatval($childs['person_cost']);
            } else {
                $cost = floatval($cost) + floatval($childs['person_cost']);
            }
        }

        return $cost;
    }



    /**
     * Calculate hourly resource and person cost
     *
     * @param  string $general_pricing, string $days, array $payable_resource, array $payable_person
     * @return string
     */
    public function calculate_hourly_extras_cost($cost, $hours, $payable_cat, $payable_resource, $payable_person, $payable_security_deposites, $pickup_cost, $dropoff_cost, $location_cost, $one_time_item)
    {

        if (isset($pickup_cost) && !empty($pickup_cost) && $one_time_item) {
            $cost = floatval($cost) + floatval($pickup_cost);
        }

        if (isset($dropoff_cost) && !empty($dropoff_cost) && $one_time_item) {
            $cost = floatval($cost) + floatval($dropoff_cost);
        }

        if (isset($location_cost) && !empty($location_cost) && $one_time_item) {
            $cost = floatval($cost) + floatval($location_cost);
        }

        if (isset($payable_cat) && sizeof($payable_cat) != 0) {
            foreach ($payable_cat as $key => $value) {
                if ($value['multiply'] == 'per_day') {
                    $cost = floatval($cost) + intval($value['quantity']) * intval($hours) * floatval($value['hourly_cost']);
                } elseif($one_time_item) {
                    $cost = floatval($cost) + intval($value['quantity']) * floatval($value['hourly_cost']);
                }
            }
        }

        if (isset($payable_resource) && sizeof($payable_resource) != 0) {
            foreach ($payable_resource as $key => $value) {
                if ($value['cost_multiply'] == 'per_day') {
                    $cost = floatval($cost) + intval($hours) * floatval($value['resource_hourly_cost']);
                } elseif($one_time_item) {
                    $cost = floatval($cost) + floatval($value['resource_cost']);
                }
            }
        }

        $adults = $payable_person['adults'];
        $childs = $payable_person['childs'];

        if (isset($adults) && sizeof($adults) != 0) {
            if ($adults['cost_multiply'] == 'per_day') {
                $cost = floatval($cost) + intval($hours) * floatval($adults['person_hourly_cost']);
            } elseif($one_time_item) {
                $cost = floatval($cost) + floatval($adults['person_cost']);
            }
        }

        if (isset($childs) && sizeof($childs) != 0) {
            if ($childs['cost_multiply'] == 'per_day') {
                $cost = floatval($cost) + intval($hours) * floatval($childs['person_hourly_cost']);
            } elseif($one_time_item) {
                $cost = floatval($cost) + floatval($childs['person_cost']);
            }
        }

        if (isset($payable_security_deposites) && sizeof($payable_security_deposites) != 0) {
            foreach ($payable_security_deposites as $key => $value) {
                if ($value['cost_multiply'] == 'per_day') {
                    $cost = floatval($cost) + intval($hours) * floatval($value['security_deposite_hourly_cost']);
                } elseif($one_time_item) {
                    $cost = floatval($cost) + floatval($value['security_deposite_cost']);
                }
            }
        }

        return $cost;
    }


}

new WC_Redq_Rental_Cart();
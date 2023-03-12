<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */
// Stop WordPress from adding <p> tags
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

if(!wp_next_scheduled('moco_process_user_synch_action')){
    add_action('init', 'moco_schedule_user_synch_cron');
}

function moco_cron_schedules($schedules){
    if(!isset($schedules["10min"])){
        $schedules["4hours"] = array(
            'interval' => 4* HOUR_IN_SECONDS,
            'display' => __('Once every 4 hours'));
    }
    return $schedules;
}


function moco_schedule_user_synch_cron(){
	
    wp_schedule_event(time(), '4hours', 'moco_process_user_synch_action');
}

function moco_process_user_synch()
{
	return true;
	$moco_user_data = moco_fetch_data_curl("http://qaapi.craneops.net/api/Operator/Customer/GetAllJobCustomer");

	ini_set("display_errors",1);
	error_reporting(1);

	$moco_user_data_result = $moco_user_data->Result;
	echo count($moco_user_data_result);
	if(count($moco_user_data_result))
	{
		foreach($moco_user_data_result as $user_data)
		{
			

			$user_id = username_exists( $user_data->Id );
		
			if (!$user_id && false == email_exists($user_data->Email)) {
				$userdata = array(
					'user_pass'             => $user_data->Id.'_123',   //(string) The plain-text user password.
					'user_login'            => $user_data->Id,   //(string) The user's login username.
					'user_email'            => $user_data->Email,   //(string) The user email address.
					//'display_name'          => '',   //(string) The user's display name. 
					'first_name'            => $user_data->ContactName,   //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
					//'last_name'             => '',   //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
							
				);
				$user_id = wp_insert_user($userdata);
				if(is_wp_error($user_id))
				{
					echo $user_id->get_error_message();
					echo "<br />";
					echo $userdata->Id;
				}
				else
				{
					$post_id = "user_$user_id"; // user ID = 2
					update_field( 'moco_id', $user_data->Id, $post_id );
					update_field( 't1_customer', $user_data->T1Customer, $post_id );
					update_field( 'description', $user_data->Description, $post_id );
					update_field( 'selntype1code', $user_data->SelnType1Code, $post_id );
					update_field( 'contact_details', $user_data->ContactDetails, $post_id );
					update_field( 'fax', $user_data->Fax, $post_id );
					update_field( 'acc_type', $user_data->AccType, $post_id );
					update_field( 'credit_limit', $user_data->CreditLimit, $post_id );
					update_field( 'customer_old_email', $user_data->CustomerOldEmail, $post_id );
					update_field( 'contact_title', $user_data->ContactTitle, $post_id );
					update_field( 'contact_initials', $user_data->ContactInitials, $post_id );
					update_field( 'contact_posn', $user_data->ContactPosn, $post_id );
					update_field( 'phone', $user_data->Phone, $post_id );
					update_field( 'chart_name', $user_data->ChartName, $post_id );
					update_field( 'pay_name', $user_data->PayName, $post_id );
					update_field( 'address_1', $user_data->Address1, $post_id );
					update_field( 'address_2', $user_data->Address2, $post_id );
					update_field( 'address_3', $user_data->Address3, $post_id );
					update_field( 'city', $user_data->City, $post_id );
					update_field( 'state', $user_data->State, $post_id );
					update_field( 'bad_credit_flag', $user_data->BadCreditFlag, $post_id );
					update_field( 'is_cash_customer', $user_data->IsCashCustomer, $post_id );
					update_field( 'secondary_email_address', $user_data->SecondaryEmailAddress, $post_id );
					update_field( 'is_preffered_customer', $user_data->IsPrefferedCustomer, $post_id );
					update_field( 'email_permission', $user_data->EmailPermission, $post_id );
					update_field( 'is_purchase_order', $user_data->IsPurchaseOrder, $post_id );
					update_field( 'is_credit_flag', $user_data->IsCreditFlag, $post_id );
					
					
					update_field( 'billing_address_1', $user_data->Address1, $post_id );
					update_field( 'billing_address_2', $user_data->Address2, $post_id );
					update_field( 'billing_city', $user_data->City, $post_id );
					update_field( 'billing_state', $user_data->State, $post_id );
					update_field( 'billing_phone', $user_data->Phone, $post_id );
				}
				

				//echo "Inserted";die (print 'Inserted');
			} else {
				if(true == email_exists($user_data->Email))
				{
					echo "<br />EmailId--".$user_data->Email."<br />";
				}
				else
				{
					echo "<br />UserId--".$user_id."<br />";
				}
				
				//echo "Not Inserted";
				
			}

			//echo $user_data->T1Customer;
			//die (print 'Inside');
		}

		echo "Finished";
	}
	
}

function moco_fetch_data_curl($url)
{
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
		"Accept: application/json"
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return json_decode($response);
}
add_filter('cron_schedules','moco_cron_schedules');
function moco_process_user_synch_tmp()
{
	$moco_user_data = moco_fetch_data_curl("http://qaapi.craneops.net/api/Operator/Customer/GetAllJobCustomer");

	$moco_user_data_result = $moco_user_data->Result;
	$email_array = $id_array = $duplicate_email_array = $duplicate_id_array = $wrong_email_array = $not_email_array = array();
	foreach($moco_user_data_result as $user_data)
	{
		if(in_array($user_data->Email,$email_array))
		{
			$duplicate_email_array[] = $user_data->Email;
		}
		else
		{
			$email_array[] = $user_data->Email;
			if(false==email_exists($user_data->Email) && false==username_exists($user_data->Id))
			{
				//$not_email_array['email'][] = $user_data->Email;
				if(true==username_exists( $user_data->Id ))
				{
					//$not_email_array[] = $user_data->Email;
				}
				
			}

			if(false==username_exists($user_data->Id))
			{
				$not_email_array['ids'][] = $user_data->Id;
				if(true==email_exists( $user_data->Email ))
				{
					//$not_email_array[] = $user_data->Id;
				}
				
			}
		}

		if(in_array($user_data->Id,$id_array))
		{
			$duplicate_id_array[] = $user_data->Id;
		}
		else
		{
			$id_array[] = $user_data->Id;
		}

		if (filter_var($user_data->Email, FILTER_VALIDATE_EMAIL)) {
		 // echo("$email is a valid email address");
		} else {
		  $wrong_email_array[] = $user_data->Email;
		}
		
	}
	echo "<pre>";
	print_r($wrong_email_array);
//	print_r($duplicate_id_array);

	die (print '');
}
if(isset($_GET["moco_cron"]))
{
	moco_process_user_synch_tmp();

}

//moco_process_user_synch_tmp();
add_action( 'moco_process_user_synch_action', 'moco_process_user_synch',10);

if(isset($_GET["moco_equipment"]))
{
	moco_process_equipment_synch(1);

}
function moco_process_equipment_synch($EquipmentCategory=1)
{
	$moco_equipment_data = moco_fetch_data_curl("http://qaapi.craneops.net/api/Operator/Equipments/GetJobsAvailableEquipmentDetails?EquipmentCategory=$EquipmentCategory");

	$moco_equipment_data_result = $moco_equipment_data->Result;
	echo "<pre>";
	foreach($moco_equipment_data_result as $equipment_data)
	{
		print_r($equipment_data);
	}
	die (print 'processed');
}

add_action( 'woocommerce_checkout_order_processed', 'moco_process_booking',  1, 1  );
function moco_process_booking( $order_id ){
	global $wpdb, $woocommerce, $post; 
	$order = new WC_Order( $order_id );
	//$book_table = $wpdb->prefix.'desktopsbb_bookings';
	$user = wp_get_current_user();
    $user_id =  ( isset( $user->ID ) ? (int) $user->ID : 0 );   
	

	foreach ($order->get_items() as $item_key => $item) {
        $order_item_id   = $item->get_product_id();
		if($order_item_id == 1090)
		{
			$pickUp = explode(' at ',$item->get_meta('Pickup Date'));
			$dropOff = explode(' at ',$item->get_meta('Dropoff Date'));
			$pickUpDate = $pickUp[0];
			$dropOffDate = $dropOff[0];

			$pickUpTime = $pickUp[1];
			$dropOffTime = $dropOff[1];
			//var_dump($pickUp);
			//print_r($pickUp);
			//print_r($dropOff);
			//$variations = get_variation_data_from_variation_id( $item_id );
			//print_r($variations);
			//die (print 'In variations');
			//echo "http://qaapi.craneops.net/api/Operator/Equipments/GetAvailableEquipmentbyDateRange?EquipmentCategory=3&StartDate=$pickUp&EndDate=$dropOff";
			$availalble_resources = moco_fetch_data_curl("http://qaapi.craneops.net/api/Operator/Equipments/GetAvailableEquipmentbyDateRange?EquipmentCategory=3&StartDate=$pickUpDate&EndDate=$dropOffDate");
			if(count($availalble_resources) && isset($availalble_resources->Result))
			{

				$post_id = "user_$user_id"; // user ID = 2
				$moco_id = get_field( 'moco_id', $post_id);
				
				$eqp_data = $availalble_resources->Result;
				$eqp_details = $eqp_data[0];

				$post_array = array();
				$post_array['id'] = "0";
				$post_array['JobTypeId'] = "3";
				$post_array['JobTypeName'] = "Moco";
				$post_array['MocoEquipmentId'] = $eqp_details->EquipmentId;
				$post_array['BillingCode'] = 4;
				$post_array['CashCustomerId'] = $moco_id;
				$post_array['StartDate'] = $pickUpDate;
				$post_array['StartTime'] = $pickUpTime;
				$post_array['EndDate'] = $dropOffDate;
				$post_array['EndTime'] = $dropOffTime;
				$post_array['InvoiceStartDateMoco'] = $pickUpDate;
				$post_array['DollorValue'] = $order->get_total();
				$post_array['RentalTypeId'] = "2";

				$moco_job = moco_update_data_curl("http://qaapi.craneops.net/api/Operator/MocoUnconfirmedJob/AddMocoJob",$post_array);

				print_r($moco_job);

			}
			
			die (print 'In variations');

		}
		
	}
	
	die (print 'In Filter');
}

function moco_update_data_curl($url,$post_array)
{
	echo json_encode($post_array);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>json_encode($post_array),
	  CURLOPT_HTTPHEADER => array(
		"Accept: application/json"
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return json_decode($response);
}

function get_variation_data_from_variation_id( $item_id ) {
    $_product = new WC_Product_Variation( $item_id );
    $variation_data = $_product->get_variation_attributes();
	return $variation_data;
   
}
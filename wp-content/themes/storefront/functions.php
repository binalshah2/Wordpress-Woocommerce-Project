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
include('vendor/autoload.php');
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
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
	
    //wp_schedule_event(time(), '4hours', 'moco_process_user_synch_action');
}

function moco_process_user_synch()
{
	return true;
	$moco_user_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetAllJobCustomer");

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
					update_field( 'ARBalance', $user_data->ARBalance, $post_id );
					
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
	$moco_user_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetAllJobCustomer");

	$moco_user_data_result = $moco_user_data->Result;
	$email_array = $id_array = $duplicate_email_array = $duplicate_id_array = $wrong_email_array = $not_email_array = array();

	echo "<pre>";
	print_r($moco_user_data_result);
	die (print '');
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
	$moco_equipment_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Equipments/GetJobsAvailableEquipmentDetails?EquipmentCategory=$EquipmentCategory");

	$moco_equipment_data_result = $moco_equipment_data->Result;
	echo "<pre>";
	foreach($moco_equipment_data_result as $equipment_data)
	{
		print_r($equipment_data);
	}
	die (print 'processed');
}
add_action( 'woocommerce_thankyou', 'woocommerce_thankyou_change_order_status', 10, 1 );
function woocommerce_thankyou_change_order_status( $order_id ){
    if( ! $order_id ) return;

    $order = wc_get_order( $order_id );

    //if( $order->get_status() == 'processing' )
        $order->update_status( 'pending' );
}
//Consumer key	ck_6e910698b855bfd0353f9ad533438e430690a2fc
//Consumer secret cs_e3c80782fb52284a55e1e6526fec3946314393e1
add_action( 'woocommerce_checkout_order_processed', 'moco_process_booking',  1, 1  );
function moco_process_booking( $order_id){
	
	global $wpdb, $woocommerce, $post; 
	$order = new WC_Order( $order_id );
	//$order->update_status( 'wc-pending' );
	//$book_table = $wpdb->prefix.'desktopsbb_bookings';
	$user = wp_get_current_user();
    $user_id =  ( isset( $user->ID ) ? (int) $user->ID : 0 );   
	

	foreach ($order->get_items() as $item_key => $item) {
        $order_item_id   = $item->get_product_id();
		if($order_item_id == 1090 || $order_item_id == 1145 || $order_item_id == 1146)
		{
			$pickUp = explode(' at ',$item->get_meta('Pickup Date'));
			$size = $item->get_meta('Size');
			$dropOff = explode(' at ',$item->get_meta('Dropoff Date'));
			$pickUpDate = $pickUp[0];
			$dropOffDate = $dropOff[0];
			
			
			$pickupLocation = $item->get_meta('PickupLocations');

			$pickUpTime = "00:00 am";//$pickUp[1];
			$dropOffTime = "00:00 am";//$dropOff[1];
			//var_dump($pickUp);
			//print_r($pickUp);
			//print_r($dropOff);
			//$variations = get_variation_data_from_variation_id( $item_id );
			//print_r($variations);
			//die (print 'In variations');
			//EquipmentType
			//echo "http://api.craneops.net/api/Operator/Equipments/GetAvailableEquipmentbyDateRange?EquipmentCategory=3&StartDate=$pickUp&EndDate=$dropOff";
			$availalble_resources = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Equipments/GetAvailableEquipmentbyDateRange?EquipmentCategory=3&StartDate=$pickUpDate&EndDate=$dropOffDate");
			if(isset($availalble_resources->Result))
			{

				$post_id = "user_$user_id"; // user ID = 2
				$moco_id = get_field( 'moco_id', $post_id);
				//echo "<pre>";
				$eqp_data = $availalble_resources->Result;
				//print_r($eqp_data);
				//$eqp_details = processMocoAssignment($eqp_data,$order_item_id,$size); //$eqp_data[0];
				$eqp_details = processMocoAssignment($eqp_data,$order_item_id,$size); //$eqp_data[0];

				$rate_code = getRateCode($eqp_data,$order_item_id,$size,$pickupLocation);

				wc_add_order_item_meta($item->get_id(),"EquipmentId",$eqp_details->EquipmentId);
				wc_add_order_item_meta($item->get_id(),"RateCode",$rate_code);


				//print_r($eqp_details);
				//die (print 'first');
				$post_array = array();
				$post_array['id'] = "0";
				$post_array['JobTypeId'] = "3";
				$post_array['JobTypeName'] = "Moco";
				$post_array['MocoEquipmentId'] = $eqp_details->EquipmentId;
				$post_array['RateCode'] = $rate_code;
				$post_array['BillingCode'] = 4;
				$post_array['CashCustomerId'] = $moco_id;
				$post_array['StartDate'] = $pickUpDate;
				$post_array['StartTime'] = $pickUpTime;
				$post_array['EndDate'] = $dropOffDate;
				$post_array['EndTime'] = $dropOffTime;
				$post_array['InvoiceStartDateMoco'] = $pickUpDate;
				$post_array['DollorValue'] = $order->get_total();
				$post_array['RentalTypeId'] = "2";
				$post_array['CompanyName'] = $user->first_name.' '.$user->last_name;
				//print_r($post_array);
				$moco_job = moco_update_data_curl("http://api.craneops.net/api/Operator/MocoUnconfirmedJob/AddMocoJob",$post_array);

			//print_r($moco_job);

			}
			
			//die (print 'In variations');

		}
		
	}
	
	//die (print 'In Filter');
}

function moco_update_data_curl($url,$post_array)
{
	//echo json_encode($post_array);
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
		"Accept: application/json",
		"Content-Type: application/json"
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
$assigned_array = array();

function processMocoAssignment($eqp_data,$order_item_id,$size)
{
	//echo "<pre>";
	$location ="Storage @ Strathclyde|Storage @ Strathclyde|0";
	$choosen_location = $_POST['form']['pickup_location'];
	
	//echo "Order Item id"; echo $order_item_id;
	//echo "<br/>";
	//echo "Size"; echo $size;

	global $assigned_array;
	if($order_item_id == 1145 || $order_item_id == 1146)
	{
		//$size = "20";
		$size = substr($size, 0, 2);
	}
	else
	{
		$size = substr($size, 0, 2);  
	}
	//echo $size;
	if($order_item_id == 1145)
	{
	//	echo "Const1";
		foreach($eqp_data as $data)
		{
			
			if($data->EquipmentType==6 && !in_array($data->EquipmentId,$assigned_array))
			{
				$assigned_array[] = $data->EquipmentId;
				return $data;
			}
		}
		//echo "Const2";
		foreach($eqp_data as $data)
		{
			if($data->EquipmentType==3 && !in_array($data->EquipmentId,$assigned_array))
			{
				$assigned_array[] = $data->EquipmentId;
				return $data;
			}
		}
		//echo "<pre>";
		//print_r($data);
	}
	
	//die;
	if($order_item_id == 1146)
	{
		foreach($eqp_data as $data)
		{
			//echo $data->EquipmentType.'<br />';
			if($data->EquipmentType==5 && !in_array($data->EquipmentId,$assigned_array))
			{

				$assigned_array[] = $data->EquipmentId;
				return $data;

			}
		}		
	}

	if($order_item_id == 1090)
	{
		foreach($eqp_data as $data)
		{
			//echo $data->EquipmentType.'-'.$size.'<br />';
			if($data->EquipmentType==2 && $size==10 && !in_array($data->EquipmentId,$assigned_array))
			{
				return $data;
			}

			if($data->EquipmentType==3 && $size==20 && !in_array($data->EquipmentId,$assigned_array))
			{
				//print_r($data);
				if($location==$choosen_location)
				{
					//$data->ConditionDescription=="Strathclyde";
					if($data->ConditionDescription=="Strathclyde" && !in_array($data->EquipmentId,$assigned_array))
					{
						//echo $data->EquipmentId;
						$assigned_array[] = $data->EquipmentId;
						//print_r($data);
						return $data;
					}
					
				}
				else
				{
					
					if($data->ConditionDescription!="Strathclyde" && !in_array($data->EquipmentId,$assigned_array))
					{
						//echo $data->EquipmentId;
						$assigned_array[] = $data->EquipmentId;
						return $data;
					
					}
				}	
				
			}

			if($data->EquipmentType==4 && $size==30 && !in_array($data->EquipmentId,$assigned_array))
			{
				return $data;
			}
		}		
	}

	return false;
	
}

function check_moco_availibility()
{
	//print_r($_POST);
	$returnData['availibile'] = true;
	echo json_encode($returnData);
	exit();
}


       
// add the action 
//add_action( 'woocommerce_add_to_cart', 'moco_woocommerce_add_to_cart', 10, 3 ); 

add_action("wp_ajax_check_moco_availibility", "check_moco_availibility");
add_action("wp_ajax_nopriv_check_moco_availibility", "check_moco_availibility");


//add_action('user_register','moco_register_function');
add_action('user_register','moco_register_qa_function');

function moco_register_function($user_id){
	
	$user_info = get_userdata($user_id);

	$post_id = "user_$user_id"; // user ID = 2
	
	$post_array = array();
	$post_array['ContactName'] = $user_info->first_name.' '.$user_info->last_name;
	$post_array['Email'] = $user_info->user_email;
	$post_array['Address1'] = get_field( 'billing_address_1', $post_id);
	$post_array['Address2'] = get_field( 'billing_address_2', $post_id);
	$post_array['Address3'] = "";
	$post_array['City'] = get_field( 'billing_city', $post_id);
	$post_array['State'] = get_field( 'billing_state', $post_id);

	 $user_data = 
	 moco_update_data_curl("http://api.craneops.net/api/Operator/Customer/ManageCustomer",$post_array);
}

function bka_moco_register_qa_function($user_id){
	
	$user_info = get_userdata($user_id);
	$post_id = "user_$user_id"; // user ID = 2
	
	$post_array = array();
	$post_array['ContactName'] = $user_info->first_name.' '.$user_info->last_name;
	$post_array['Email'] = $user_info->user_email;
	$post_array['Address1'] = get_field( 'billing_address_1', $post_id);
	$post_array['Address2'] = get_field( 'billing_address_2', $post_id);
	$post_array['Address3'] = "";
	$post_array['City'] = get_field( 'billing_city', $post_id);
	$post_array['State'] = get_field( 'billing_state', $post_id);

	$moco_user_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetAllJobCustomer");

	//ini_set("display_errors",1);
	//serror_reporting(1);
	

		$flag = 0;
		$moco_user_data_result = $moco_user_data->Result;
	

		$userdata = array();
		foreach($moco_user_data_result as $key => $user_data)
		{
			//print_r($user_data);

			if($user_data->Email==$user_info->user_email)
			{
				$flag = 1;
				$userdata[$key]['Id'] =  $user_data->Id;
				$userdata[$key]['T1Customer'] =  $user_data->T1Customer ; 
			}
			
			//print_r($user_data);

			
			
		}
		//echo $flag;
		if($flag=="1")
		{
			//print_r($userdata);
			//echo $userdata[0]['Id'];
			//die;
			update_user_meta( $user_id ,'moco_id', $userdata[0]['Id']);
			update_user_meta( $user_id ,'t1_customer', $userdata[0]['T1Customer']);
			//die;
			 //$user_data = moco_update_data_curl("http://qaapi.craneops.net/api/Operator/Customer/ManageCustomer",$post_array);

		}
		else
		{
			//$user_data = moco_update_data_curl("http://qaapi.craneops.net/api/Operator/Customer/ManageCustomer",$post_array);
			$user_data = moco_update_data_curl("http://api.craneops.net/api/Operator/Customer/ManageCustomer",$post_array);
			
			$moco_user_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetAllJobCustomer");

			//ini_set("display_errors",1);
			//serror_reporting(1);
			

				$flag = 0;
				$moco_user_data_result = $moco_user_data->Result;
			

				$userdata = array();
				foreach($moco_user_data_result as $key => $user_data)
				{
					//print_r($user_data);

					if($user_data->Email==$user_info->user_email)
					{
						$flag = 1;
						$userdata[$key]['Id'] =  $user_data->Id;
						$userdata[$key]['T1Customer'] =  $user_data->T1Customer ; 
						update_user_meta( $user_id ,'moco_id', $userdata[$key]['Id']);
						update_user_meta( $user_id ,'t1_customer', $userdata[$key]['T1Customer']);
					}
					
					//print_r($user_data);

					
					
				}
			
		}
		
	
}
function moco_register_qa_function($user_id){
	
	$user_info = get_userdata($user_id);
	$post_id = "user_$user_id"; // user ID = 2
	
	//print_r($user_info);
	$customerId = $user_info->user_login;

	$moco_user_customer = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetCustomerById?cutomerId=$customerId");
	//print_r($moco_user_customer->Result);
	$moco_user_data_result = $moco_user_customer->Result;
		
	
	
	$rcount = count($moco_user_customer->Result);
	// B1 call GET /api/Operator/Customer/GetMocoT1CustomerNumber
	if($rcount==0)
	{
		$moco_user_t1customer = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetMocoT1CustomerNumber");
		//print_r($moco_user_t1customer);
		$T1Customer_number = $moco_user_t1customer->Result;
		update_user_meta( $user_id ,'t1_customer', $T1Customer_number);
	}
	else
	{
		$T1Customer_number = $moco_user_data_result->T1Customer;
		
		
		update_user_meta( $user_id ,'t1_customer', $T1Customer_number);
		
		
	}

	
	// B2 update t1 number to user meta

	
	update_user_meta( $user_id ,'billing_address_1', $_REQUEST['billing_address_1']);
	update_user_meta( $user_id ,'billing_address_2', $_REQUEST['billing_address_2']);
	update_user_meta( $user_id ,'billing_city',  $_REQUEST['billing_city']);
	update_user_meta( $user_id ,'billing_state', $_REQUEST['billing_state']);


	$post_array = array();

	// B3 Pass T1 number from B2
	$post_array['ContactName'] = $user_info->first_name.' '.$user_info->last_name;
	$post_array['T1Customer']  = $user_info->t1_customer;
	$post_array['Email'] = $user_info->user_email;
	//$post_array['Address1'] = get_field( 'billing_address_1', $user_id);
	//$post_array['Address2'] = get_field( 'billing_address_2', $user_id);
	//$post_array['Address3'] = "";
	//$post_array['City'] = get_field( 'billing_city', $user_id);
	//$post_array['State'] = get_field( 'billing_state', $user_id);

	$post_array['Address1'] = $_REQUEST['billing_address_1'];
	$post_array['Address2'] = $_REQUEST['billing_address_2'];
	$post_array['Address3'] = "";
	$post_array['City']     = $_REQUEST['billing_city'];
	$post_array['State']    = $_REQUEST['billing_state'];

	// B4 POST ALL DATA To api/Operator/Customer/GetAllJobCustomer
	if($_REQUEST['billing_t1_number']=='' && $rcount==0)
	{
		$user_data = moco_update_data_curl("http://api.craneops.net/api/Operator/Customer/ManageCustomer",$post_array);
	}
	

	// B5  Pass T1 Number GET /api/Operator/Customer/GetCustomerByT1Number
	$moco_user_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetCustomerByT1Number?t1number=$user_info->t1_customer");
	$moco_user_data_result = $moco_user_data->Result;
	
	//echo "<pre>";
	//print_r($moco_user_data_result->Id);
	// B6  Fetch Id from B5 AND update user meta and replace username with  id.
		
	//echo "<br/>";
	//echo $user_info->ID;
	global $wpdb;
	if($rcount==0)
	{
		update_user_meta( $user_id ,'moco_id', $moco_user_data_result->Id);
	
		$query = "UPDATE $wpdb->users SET user_login = '".$moco_user_data_result->Id."'
				 WHERE ID = '".$user_info->ID."'";
		$wpdb->query($query);
	}

			//update_user_meta( $user_id ,'user_login', $user_data->Id);
	//wp_update_user( array( 'ID' => $user_info->ID, 'user_login' => $moco_user_data_result->Id ) );

		
	// B7  Redirect user to payment flow  
		
		
	
}
function getRateCode($eqp_data,$order_item_id,$size,$location)
{
	
	$size = substr($size, 0, 2);
	
	//office 
	if($order_item_id == 1146)
	{
		switch($size)
		{
			case '10':
				return "12MS";
				break;
			case '20':
				return "22MS";
				break;
			case '40':
				return "42MS";
				break;
				
		}
	}

	//construction
	if($order_item_id == 1145)
	{
		switch($size)
		{
			case '10':
				return "13MS";
				break;
			case '20':
				return "23MS";
				break;
			case '40':
				return "43MS";
				break;
				
		}
	}

	// storage
	if($order_item_id == 1090)
	{
		if($location=="OnSite")
		{
			switch($size)
			{
				case '20':
					return "20MO";
					break;
				case '40':
					return "40MO";
					break;
					
			}
		}
		else
		{
			switch($size)
			{
				case '10':
					return "10MS";
					break;
				case '20':
					return "20MS";
					break;
				case '40':
					return "40MS";
					break;
					
			}
		}
	}
	
}

function bka_getResourceCost($product_id,$size,$location="")
{
	
	if($product_id == 1146)
	{
		
		switch($size)
		{
			case '10':
				return "1";
				break;
			case '20':
				return "115";
				break;
			case '40':
				return "1";
				break;
				
		}
	}


	if($product_id == 1145)
	{
		switch($size)
		{
			case '10':
				return "160";
				break;
			case '20':
				return "269";
				break;
			case '40':
				return "500";
				break;
				
		}
	}

	if($product_id == 1090)
	{
		//echo $location;die (print '');
		if($location=="OnSite")
		{
			switch($size)
			{
				case '20':
					return "394.8";
					break;
				case '40':
					return "620.4";
					break;
					
			}
		}
		else
		{
			switch($size)
			{
				case '10':
					return "295";
					break;
				case '20':
					return "329";
					break;
				case '40':
					return "470";
					break;
					
			}
		}
	}
}
function getResourceCost($product_id,$size,$location="")
{
	
	if($product_id == 1146)
	{
		
		switch($size)
		{
			case '10':
				return "1";
				break;
			case '20':
				return "1290";
				break;
			case '40':
				return "1";
				break;
				
		}
	}


	if($product_id == 1145)
	{
		switch($size)
		{
			//case '10':
			//	return "160";
			//	break;
			case '20':
				return "305";
				break;
			case '40':
				return "500";
				break;
				
		}
	}

	if($product_id == 1090)
	{
		//echo $location;die (print '');
		if($location=="OnSite")
		{
			switch($size)
			{
				case '20':
					return "455";
					break;
				case '40':
					return "715";
					break;
					
			}
		}
		else
		{
			switch($size)
			{
				case '10':
					return "355";
					break;
				case '20':
					return "395";
					break;
				case '40':
					return "570";
					break;
					
			}
		}
	}
}
add_action( 'woocommerce_cart_calculate_fees', 'custom_advance_fees', 10, 1 );
function custom_advance_fees($cart) {

	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

	global $woocommerce;
	
	$items = $woocommerce->cart->get_cart();
	
	foreach ( $items as $item ) {
		if(isset($item['rental_data']['payable_resource'][0]))
		{
			$res = $item['rental_data']['payable_resource'][0];
			$resource_cost = $res['resource_cost'];
			$resource_size = $res['resource_name'];
			$res_title = $resource_size.' '.$item['data']->get_name()." security deposit";
			$cart->add_fee( __( $res_title, "woocommerce" ), $resource_cost, false );
		}
	}

}





include('api.php');


function initialize_my_theme_options() {
    //define the settings field
    add_settings_field(
        'my_address',               //The ID
        'Address',         // the label for field
        'my_message_display',  //The callback function
        'general'                       //the page
    );

    //register the footer_message setting the general section
    register_setting(
        'general',
        'my_address'
    );

	add_settings_field(
        'my_phone',               //The ID
        'Phone',         // the label for field
        'my_phone_display',  //The callback function
        'general'                       //the page
    );

	add_settings_field(
        'my_email',               //The ID
        'Email',         // the label for field
        'my_email_display',  //The callback function
        'general'                       //the page
    );

    //register the footer_message setting the general section
    register_setting(
        'general',
        'my_address'
    );

	 register_setting(
        'general',
        'my_phone'
    );

	 register_setting(
        'general',
        'my_email'
    );
}
add_action('admin_init','initialize_my_theme_options');


function my_message_display() {
    echo '<textarea style="width: 300px; height: 200px;" name="my_address" id="my_address">'.get_option('my_address').' </textarea>';
}

function my_phone_display() {
    echo '<input type="text" name="my_phone" id="my_phone" value="'.get_option('my_phone').'" />';
}

function my_email_display() {
    echo '<input type="text" name="my_email" id="my_email" value="'.get_option('my_email').'" />';
}
function paynow_shortcode() {
  
   global $wpdb;
   $current_user_id = get_current_user_id();
   if($current_user_id=="")
   {
   	?>
   	<!--<div class="rc_invoice"><p>Please Login or Register below to pay your Invoice</p></div>-->
   	<!--<div class="rc_invoice">
   		<p>We are facing some technical challanges with our online registration and payment system<br/> 
   		and have thus Temporarily Disabled it until further notice. </p>
   		<p>We will inform you via email as soon as we are back.We sincerely apologize for the inconvenience!</p></div>-->

   	<?php
   	 echo do_shortcode('[woocommerce_my_account]');

   }
   else
   {

		   $the_user = get_user_by( 'id', $current_user_id ); // 54 is a user ID
		   //print_r($the_user->user_login);
		   //echo $the_user->user_email;
		   //$moco_user_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetAllJobCustomer");
		   $moco_user_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetAllJobCustomer");

			//ini_set("display_errors",1);
			//serror_reporting(1);
			
			
			$moco_user_data_result = $moco_user_data->Result;
			//print_r($moco_user_data_result);

				$userdata = array();
				foreach($moco_user_data_result as $key => $user_data)
				{
					//print_r($user_data);
					if($user_data->Id==$the_user->user_login)
					{
						//$userdata['user_email'] =  $user_data->Email;
						$userdata['user_ARBalance'] =  $user_data->ARBalance ; 
					}
					
					
				}
				//$userdata['user_ARBalance'] = 20;
				if($userdata['user_ARBalance']=="")
				{
					$userdata['user_ARBalance'] = "0.00";
					//$class = 'disabled';
				}	
				if($_POST['amount']!="")
				{
					$amount = $_POST['amount'];
					//$class = 'disabled';
				}

			?>
			
   	
			<div class="rc_invoice"><p>Please find the invoice amount to pay below:</p></div>
			<?php
		    echo "<h4></h4>";
		     echo "<form id='frminv' name='frminv' method='post' enctype='multipart/form-data'>";
		    echo "<br/>";
		    echo "<div class='rc_invoice_amt'>Amount : $".$userdata['user_ARBalance'];
		    echo "<br/>";
		    echo "<div class='rc_invoice_amt'>Amount : $<input type='text' name='amount' id='amount' value=".$amount.">";
		    echo "<input type='hidden' name='payproduct' id='payproduct' value='1372'/>";
		    echo '<input type="button" name="paynow" id="paynow" value="Pay Now" onclick="checkbal();" class="single_add_to_cart_button button alt rc_invoice_btn" '.$class.'>';
		  
		    echo "</form>";
		    ?>
		    <script type="text/javascript">
		    function checkbal()
		    {
		    	var ar_bal = <?php echo $userdata['user_ARBalance']; ?>;
		    	var amount = document.getElementById("amount").value;
		    	if(amount<='1.00')
		    	{
		    		alert("Payment amount cannot exceed invoice amount");
		    		return false;
		    	}
		    	else
		    	{
		    		document.getElementById("frminv").submit();

		    	}

		    }
		    </script>
		    <?php
		   	//echo "asdsd";
		   	//print_r($_POST);
			if($_POST['payproduct']=='1372')
			{
				//print_r($_POST);
				global $woocommerce;
				//$custom_price = $userdata['user_ARBalance'];
				$custom_price = $_POST['amount'];
				//$custom_price = '1';
				$product_id = 1372;
				$quantity = 1;
				// Cart item data to send & save in order
				$cart_item_data = array('custom_price' => $custom_price);   
				// woocommerce function to add product into cart check its documentation also 
				// what we need here is only $product_id & $cart_item_data other can be default.
				$woocommerce->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );
				// Calculate totals
				$woocommerce->cart->calculate_totals();
				// Save cart to session
				$woocommerce->cart->set_session();
				// Maybe set cart cookies
				$woocommerce->cart->maybe_set_cart_cookies();

				$class = 'disabled';
				$url = "https://moco.bb/cart/";
				
				//wp_redirect( $url);


				
				echo "<div class='rc_invoice_cart'><a href='".$url."''>Please click here to continue payment</a></div>";


			}
	?>
	<br/>
	<br/>
	<br/>
	<div class="rc_invoice"><p> If you have made payment online, please wait for 48 hours to reflect in your account balance.</p></div>
   			<div class="rc_invoice"><p> Payment done online will be applied to oldest invoice first.</p></div>
	<?php
	}
   
}
add_shortcode('paynow', 'paynow_shortcode'); 


add_filter( 'woocommerce_default_address_fields' , 'QuadLayers_optional_postcode_checkout' );
function QuadLayers_optional_postcode_checkout( $p_fields ) {
$p_fields['postcode']['required'] = false;
return $p_fields;
}
add_filter( 'woocommerce_default_address_fields' , 'QuadLayers_optional_city_checkout' );
function QuadLayers_optional_city_checkout( $p_fields ) {
$p_fields['city']['required'] = false;
return $p_fields;
}

// code added for export 

add_action( 'manage_posts_extra_tablenav', 'admin_order_list_top_bar_button', 20, 1 );
function admin_order_list_top_bar_button( $which ) {
    global $pagenow, $typenow;

    if ( 'shop_order' === $typenow && 'edit.php' === $pagenow && 'top' === $which ) {
        ?>
        <div class="alignleft actions custom">
            <button type="submit" name="export_order" style="height:32px;" class="button" value="yes"><?php
                echo __( 'Exprort Orders', 'woocommerce' ); ?></button>
        </div>
        <?php
    }
}
add_action( 'restrict_manage_posts', 'display_admin_shop_order_language_filter' );
function display_admin_shop_order_language_filter() {
    global $pagenow, $typenow;

    if ( 'shop_order' === $typenow && 'edit.php' === $pagenow &&
    isset($_GET['export_order']) && $_GET['export_order'] === 'yes' ) {

        ## -------- The code to be trigered -------- ##
    	ob_end_clean();
        product_export_orders();

        ## -------------- End of code -------------- ##
    }
}
function product_export_orders() {
   
    

    global $wpdb;
    $tp= "crn_posts";
    $resultpost = $wpdb->get_results( "SELECT ID FROM ".$tp." where post_type='shop_order' and post_status ='wc-completed'");
    //print_r($resultpost);
    for($t=0;$t<count($resultpost);$t++)
    {
    	$posts[] = $resultpost[$t]->ID;
    }
   	//print_r($posts);
   	$ordersno = implode(",",$posts);
    
	$table_name = "crn_woocommerce_order_items";
	$query1 ="SELECT * FROM ".$table_name." where order_item_type='line_item' and order_id IN(".$ordersno.") order by order_id";
	$result = $wpdb->get_results($query1);
	
	ob_end_clean();
	$filename = "pay_export_" . date('Y-m-d') . ".csv"; 
	$delimiter = ","; 
	 
	// Create a file pointer 
	$f = fopen(ABSPATH . "wp-content/uploads/exports/" . $filename, 'w'); 
	$heading = array('FORMAT BATCH IMPORT , STANDARD 1.0','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
	fputcsv($f, $heading, $delimiter); 
	
	$fields = array('DOCID','DEXCHRATETBL','DEXCHRATEDATE','DCCYCODE','DEXCHRATEAMT','DREF1','DREF2','DREF3','DDATE1','DDATE2','DDATE3','DDATE4','DSOURCE','DPERIOD','DTYPE','DCLRENTITY','DENTITYPE','DREVPRDIND','DREVPERIOD','DIMAGEDIRCD','DIMAGEFILE','DTINVRECIND','DTINVRECDATE','DCONTDOCTYPE','DBUSREGNBR','DPOSTIMPIND','DPOSTADDRCODE','DPOSTNAME','DPOSTADDR1','DPOSTADDR2','DPOSTADDR3','DPOSTCITY','DPOSTSTATE','DPOSTCODE','DPOSTCTRYCODE','DPOSTCTRYNAME','DPOSTMESS1','DPOSTMESS2','DPRINTSTAT','DRDTYPE','DRDFDOCDATE','DRDFDUEDATE','DRDFREQMODE','DRDFREQUNIT','DRDFREQLDOM','DRDNBROCC','DRDDOCCCYAMT','DRDDOCAMT','DRDONHOLDIND','DRDREVWDATE','DRDPPLDGCODE','DRDPPACCNBRI','DRDOSLDGCODE','DRDOSACCNBRI','DDIRECTDEBITIND','DPAYACCT','DREASCODE','DREASDATE','DREASNARR1','DREASNARR2','DPRTREQIND','LINEID','LNEDETAILTYPE','LLDGCODE','LACCNBR','LDRAWNAME','LUNITS1','LUNITS2','LUNITS3','LUNITS4','LRATETYPE','LRATECODE','LRATEAMT','LCCYAMOUNT','LAMOUNT1','LAMOUNT2','LAMOUNT3','LAMOUNT4','LNARR1','LNARR2','LNARR3','LMERCHCCYAMT','LMERCHAMT','LDUEDATE','LDISCCCYAMT','LDISCAMT1','LDISCAMT2','LDISCDATE','LRETNCCYAMT','LRETNAMT','LRETNREASON','LRETNREVIEW','LPRESCSTAT','LCOINCCYAMT','LCOINAMT','LUSERFLD1','LUSERFLD2','LUSERFLD3','LUSERFLD4','LUSERFLD5','LUSERFLD6','LUSERFLD7','LUSERFLD8','LUSERFLD9','LUSERFLD10','LGSTTYPE','LGSTRATECODE','LGSTDATE','LGSTRATEAMT','LGSTCCYAMT','LGSTAMT','LGSTEXCCCYAMT','LGSTEXCAMT','LGSTRCRATEAMT','LGSTRCCCYAMT','LGSTRCAMT','LEXTHOLDA','LEXTHOLDI','LSEPCHEQUE','LCOLLCOMM','LASSETIND','LASSETREG','LASSETNBR','LBOOKNBR','LTEMPLASSNBR','LASSETDESCR','LITEMTYPE','LITEMCODE','LITEMRATE','LRESGRPCODE','LRESCODE','LRESUNITSNAME','LRESUNITSRATE','LCNTRCODE','LPRESPMTRLSAMT','LPRESPMTRLSCCYAMT','LPRESPEXLNSAMT','LPRESPEXLNSCCYAMT','LCTRCTPROFNAME','LCTRCTNBR','LCTRCTSCHEDNBR','LCTRCTSCHEDITEMNBR','LCTRCTVARNBR','LCTRCTPARTYPROF','LCTRCTPARTYNBR','LCTRCTCLAIMNBR','BIMPNAME','BNAME','BWKFLWASSIGNUSER','BGROUP','BDESCR','BNARR1','BNARR2','BNARR3','BFORMAT','BDOCTYPE','BPERIOD','BCALENDAR','BPROGRP','BREVPROGRP','BSUSPLDGCODE','BSUSPACCNBRI','BBALLDG','BBALACCNBR','BGSTRATECODE','BGSTDATE','BBALDATE1','BBALREF1','BDATE','BCLRENTITY','BPROCTYPE','BSTATUS','RDBATTYPE','RDGENDOCREFMTH','LPDESCR','LPUNITS','LPUNITSNAME','LPRATETYPE','LPRATECODE','LPRATEAMT','LPCCYAMT','LPAMT','LPDOCDATE','LPDOCREF','LPGSTTYPE','LPGSTRATECODE','LPGSTDATE','LPGSTRATEAMT','LPGSTCCYAMT','LPGSTAMT','LPGSTINCCCYAMT','LPGSTINCAMT','LPGSTEXCCCYAMT','LPGSTEXCAMT','LBRECTYPE','LBRECCCYAMT','LBRECAMT','LBCHQNBR','LBDRWNAME','LBDRWBANK','LBDRWBRANCH','LBVCHNBR','LBCARDNBR','LBEXPRYDATE','LBOTHREASON','LBCARDHOLDERNAME','LOPLOCNCODE','LOPORDNBR','LOBKORDNBR','LOSRCINVAMT','LOSRCINVEXCAMT','LOSRCADDCOST','LOSRCADDCOSTEXC','LOPORDMATCHOPT','LOSRCDETAILS1','LOSRCDETAILS2','LAFILENAME','LAATTTYPE');
	fputcsv($f, $fields, $delimiter); 
	
	foreach( $result as $results ) {


		


		$table_name1 = "crn_woocommerce_order_itemmeta";
		$result1 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='_product_id'");

		$result21 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='_qty'");
		$result22 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='_line_total'");

		$table_name0 = "wp_flexi_product_relation";
		$result0 = $wpdb->get_results( "SELECT * FROM ".$table_name0."  where product_id='".$result1[0]->meta_value."'");

		$table_name11 = "crn_woocommerce_order_itemmeta";
		$result11 = $wpdb->get_results( "SELECT * FROM ".$table_name11."  where order_item_id='".$results->order_item_id."' and meta_key='_variation_id'");

		$table_name110 = "crn_postmeta";
		$result110 = $wpdb->get_results( "SELECT * FROM ".$table_name110."  where order_item_id='".$result11[0]->meta_value."' and meta_key='_regular_price'");

		$table_name12 = "crn_postmeta";
		$result12 = $wpdb->get_results( "SELECT * FROM ".$table_name12."  where post_id='".$result11[0]->meta_value."' and meta_key='_regular_price'");
		//print_r($results1);
		$table_name13 = "wp_flexi_rate_table";
		$result13 = $wpdb->get_results( "SELECT * FROM ".$table_name13."  where attribute_id='".$result11[0]->meta_value."'");
		
		$result2 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='_line_total'");
		$result3 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='pickup_hidden_datetime'");
		if($result3[0]->meta_value=="")
		{
			
		}
		else
		{
			//$datetime = $result3[0]->meta_value;
		}
		$table_name2="crn_posts";
		$result31 = $wpdb->get_results( "SELECT * FROM ".$table_name2."  where id='".$results->order_id."' and post_type='shop_order'");
		$datetime = $result31[0]->post_date;
		$datetime1 = explode(" ",$datetime);
		//echo $datetime1[0];
		$datetime2 = explode("-",$datetime1[0]);
		//$datetime3 = $datetime2[2].'/'.$datetime2[1].'/'.$datetime2[0];
		//echo $datetime3;

		$datetime3 = date('d/m/Y',strtotime($datetime));
		$GA = $datetime3;
		//$month=date("F",strtotime($datetime));
		//$year=date("Y",strtotime($datetime));
		$table_name314="crn_postmeta";
		$result314 = $wpdb->get_results( "SELECT * FROM ".$table_name314."  where post_id='".$results->order_id."' and meta_key='_plugnpay_charge_id'");
		$DREF2 = $result314[0]->meta_value;
		//$DREF22 = substr($DREF2, -6);
		$DREF22 = "$DREF2\t";
		//$DREF22 = $DREF2;

		$result315 = $wpdb->get_results( "SELECT * FROM ".$table_name314."  where post_id='".$results->order_id."' and meta_key='_paid_date'");
		$DREF3 = $result315[0]->meta_value;
		$DREF3DAETIME = explode(" ",$DREF3);
		//die;
		$result4 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='Select Start Date'");
		$result5 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='Select End Date'");

		$table_name3="crn_postmeta";

		$result39 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_customer_user'");
		$user_id = $result39[0]->meta_value;

		$result40 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_company'");
		$company = $result40[0]->meta_value;

		
		$result41 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_first_name'");
		$firstname = $result41[0]->meta_value;

		$result42 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_last_name'");
		$lastname = $result42[0]->meta_value;

		$name = $firstname.' '.$lastname;

		$result43 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_address_1'");
		//print_r($result43);
		$_billing_address_1 = $result43[0]->meta_value;
		$_billing_address_1 = explode(",",$_billing_address_1);

		$DPOSTADDR1=$_billing_address_1[0];

		$DPOSTADDR2=$_billing_address_1[1];

		$result44 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_address_2'");
		$_billing_address_2 = $result44[0]->meta_value;
		//$_billing_address_2 = str_replace(","," ",$_billing_address_2);
		$result45 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_city'");
		$_billing_city = $result45[0]->meta_value;

		$result46= $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_state'");
		$_billing_state = $result46[0]->meta_value;

		//$_billing_state = "St Michael";
		$result47= $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_postcode'");
		$_billing_postcode = $result47[0]->meta_value;


		$table_name4="crn_product_inventory_relation";
		$result48= $wpdb->get_results( "SELECT * FROM ".$table_name4."  where order_id='".$results->order_id."'");
		$sku = '3.86.'.$result48[0]->sku.'.06f';
		$unit = $result48[0]->suit_no;


		$result49= $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='p_code'");
		$p_code = $result49[0]->meta_value;
		$user_id = get_post_meta($results->order_id, '_customer_user', true);
		$LACCNBR = get_user_meta($user_id, 't1_customer', true);


		if($company=="")
		{
			$company = $firstname.' '.$lastname;
		}
			for ($i = 0; $i < 3; $i++)
			{
				if($i==0){$LINEID="1";}if($i==1){$LINEID="400";}if($i==2){$LINEID="500";}if($i==3){$LINEID="500";}
				if($i==0){$LNEDETAILTYPE="L";}if($i==1){$LNEDETAILTYPE="P";}if($i==2){$LNEDETAILTYPE="B";}if($i==3){$LNEDETAILTYPE="P";}
				if($i==0){$LLDGCODE="AR";}if($i==1){$LLDGCODE="GL";}if($i==2){$LLDGCODE="GL";}if($i==3){$LLDGCODE="GL";}
				if($i==4){$LLDGCODE="GL";}
				

				if($i==0)
				{
					$LACCNBR = get_user_meta($user_id, 't1_customer', true);
					$LACCNBR1 = "$LACCNBR\t";
					$LUNITS1= '-'.$result21[0]->meta_value;
					$LRATE = '-'.$result22[0]->meta_value;
					$LAMOUNT12 = '';
					$LAMOUNT122 = '';
					$DG = 		'-'.$result22[0]->meta_value;
					$DH = 		'-'.$result22[0]->meta_value; 
					$DI = 		'-'.$result22[0]->meta_value;
					$LAMOUNT1 = '-'.$result22[0]->meta_value;
					$DPOSTIMPIND = "Y";
					$number = "001";
					$DPOSTADDRCODE=	"$number\t";
					$DPOSTNAME = $name;
					$DPOSTADDR1 = $DPOSTADDR1;
					$DPOSTADDR2 = $DPOSTADDR2;
					$DPOSTSTATE = $_billing_state;
					$AI = "bb";
					$AH = " ";
					$GA = " ";
					$GB = " ";
				}
				
				
				if($i==1)
				{
					$LACCNBR1 = '2900010101';
					$LAMOUNT12 = $result22[0]->meta_value;
					$LAMOUNT122 = $result22[0]->meta_value;
					$DG = '';
					$DH = '';
					$DI = '';
					$LAMOUNT1 = " ";
					$DPOSTIMPIND = "";
					$DPOSTADDRCODE="";
					$DPOSTNAME = "";
					$DPOSTADDR1 = "";
					$DPOSTADDR2 = "";
					$DPOSTSTATE = "";
					$AI = "";
					$AH ="";
					$GA = $datetime3;
					$GB = $results->order_id.'t';

				}
				
				if($i==2)
				{
					$LACCNBR1 = '2900010101';
					$LAMOUNT12 = '';
					$LAMOUNT122 = $result22[0]->meta_value;
					$DG = '';
					$DH = '';
					$DI = '';
					$LAMOUNT1 = " ";
					$DPOSTIMPIND = "";
					$DPOSTADDRCODE="";
					$DPOSTNAME = "";
					$DPOSTADDR1 = "";
					$DPOSTADDR2 = "";
					$DPOSTSTATE = "";
					$AI = "";
					$AH ="";
					$GA = " ";
					$GB = " ";

				}
				$LPDESCR = 'Payments will be applied to oldest'.' '.'invoice first - '.$results->order_id;

			 $lineData = array($results->order_id.'t','cebuy',$datetime3,'bbd','1',$results->order_id.'t',
			 			$DREF22,$DREF3DAETIME[1],$datetime3,'','','','AR',$datetime2[1],'ARCRIMP','','S',
			 			'','','','','','','','',
			 			$DPOSTIMPIND,$DPOSTADDRCODE,$DPOSTNAME,$DPOSTADDR1,$DPOSTADDR2,'',$billing_city,
			 			$DPOSTSTATE,$AH,$AI,'','','',
			 			'','','','','','','','','','','','','','','','','','','','','','','Y',
			 			$LINEID,$LNEDETAILTYPE,$LLDGCODE,$LACCNBR1,'','','','','','',
			 			'','',$LAMOUNT1,$LAMOUNT1,'','','','Payments will be applied to oldest','invoice first -',$results->order_id,'','','','',
			 			'','','','','','','','','','','','','','','','','','','','',
			 			'I','NA','','','',$DG,$DH,$DI,'','','','','',
			 			 '','','','','','','','','','','','','','','','','','','','','','','','','','','',
			 			 '','MOCO_PAY','','','$AR','','','','','CEWEBREC','ARCRIMP',$datetime2[1],'ce',$datetime2[0],
			 			 '','GL','2990099999','GL','2900010100','','','','','','','','S','','',$LPDESCR,'','','',
			 			 'NA','',$LAMOUNT12,$LAMOUNT12,$GA,$GB,'','','','0','0','0',
			 			 $LAMOUNT122,$LAMOUNT122,$LAMOUNT122,$LAMOUNT122,'$oth',
			 			 $LAMOUNT122,$LAMOUNT122,'','','','','','','',$company,'','','','','','','','','','','',''); 
			 fputcsv($f, $lineData, $delimiter); 
			}
		
       
        
       
    }  
    
  	fseek($f, 0); 
 
// Set headers to download file rather than displayed 
	header('Content-Type: text/csv'); 
	header('Content-Disposition: attachment; filename="' . $filename . '";'); 
	 
	// Output all remaining data on a file pointer 
	fpassthru($f); 
	 
	// Exit from file 
	exit();
    
}
add_filter( 'woocommerce_states', 'custom_woocommerce_states' );
 
function custom_woocommerce_states( $states ) {
  $states['BB'] = array(
    'St.Lucy' => 'St.Lucy',
    'St.Peter' => 'St.Peter',
    'St.Andrew' => 'St.Andrew',
    'St.James' => 'St.James',
    'St.Joseph' => 'St.Joseph',
    'St.George' => 'St.George',
    'St.Thomas' => 'St.Thomas',
    'St.John' => 'St.John',
    'St.Michael' => 'St.Michael',
    'St.Philip' => 'St.Philip',
    'Christ Church' => 'Christ Church'
  );
 
  return $states;
}
add_filter( 'woocommerce_default_address_fields' , 'wpse_120741_wc_def_state_label' );
function wpse_120741_wc_def_state_label( $address_fields ) {
     $address_fields['state']['label'] = 'Parish';
     return $address_fields;
}
function remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'remove_wp_ver_css_js', 9999 );

function sess_start() {
    if (!session_id())
    session_start();
}
add_action('init','sess_start');

add_filter('nav_menu_submenu_css_class','some_function', 10, 3 );
function some_function( $classes, $args, $depth ){

    foreach ( $classes as $key => $class ) {
        if ( $class == 'sub-menu' && $depth == 0) {
            //$classes[ $key ] = 'your-class11';
        }elseif($class == 'sub-menu' && $depth == 1){
            //$classes[ $key ] = 'your-class';
        }
    } 
    return $classes;
}

if(isset($_GET['synch_order_data_dropbox']))
{
	synch_order_data_dropbox();
}
function synch_order_data_dropbox()
{
  
//$timezone = date_default_timezone_get();
//echo "The current server timezone is: " . $timezone;

//echo $date = date('m/d/Y h:i:s a', time());

//die();
	   $tz = 'America/Barbados';
		$timestamp = strtotime("-1 Day");
		$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
		$dt->setTimestamp($timestamp); //adjust the object to correct timestamp
		echo $barb_date = $dt->format('Y-m-d');
//		die (print '');

		global $wpdb;
		$tp= "crn_posts";
		$date_from = $barb_date;//"2021-08-01";
		$date_to = $barb_date;//"2021-12-01";
		$resultpost = $wpdb->get_results( "SELECT ID FROM $wpdb->posts 
				WHERE post_type = 'shop_order'
				AND post_status ='wc-completed'
				AND post_date BETWEEN '{$date_from}  00:00:00' AND '{$date_to} 23:59:59'
			");
	   
		for($t=0;$t<count($resultpost);$t++)
		{
			$posts[] = $resultpost[$t]->ID;
		}
		//print_r($posts);



		$ordersno = implode(",",$posts);
		
		$table_name = "crn_woocommerce_order_items";
		$query1 ="SELECT * FROM ".$table_name." where order_item_type='line_item' and order_id IN(".$ordersno.") order by order_id";
		$result = $wpdb->get_results($query1);
		
		ob_end_clean();
		$filename = "pay_export_" . $barb_date . ".csv"; 
		$delimiter = ","; 
		 
		// Create a file pointer 
		$f = fopen(ABSPATH . "wp-content/uploads/exports/" . $filename, 'w'); 
		$heading = array('FORMAT BATCH IMPORT , STANDARD 1.0','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
		fputcsv($f, $heading, $delimiter); 
		
		$fields = array('DOCID','DEXCHRATETBL','DEXCHRATEDATE','DCCYCODE','DEXCHRATEAMT','DREF1','DREF2','DREF3','DDATE1','DDATE2','DDATE3','DDATE4','DSOURCE','DPERIOD','DTYPE','DCLRENTITY','DENTITYPE','DREVPRDIND','DREVPERIOD','DIMAGEDIRCD','DIMAGEFILE','DTINVRECIND','DTINVRECDATE','DCONTDOCTYPE','DBUSREGNBR','DPOSTIMPIND','DPOSTADDRCODE','DPOSTNAME','DPOSTADDR1','DPOSTADDR2','DPOSTADDR3','DPOSTCITY','DPOSTSTATE','DPOSTCODE','DPOSTCTRYCODE','DPOSTCTRYNAME','DPOSTMESS1','DPOSTMESS2','DPRINTSTAT','DRDTYPE','DRDFDOCDATE','DRDFDUEDATE','DRDFREQMODE','DRDFREQUNIT','DRDFREQLDOM','DRDNBROCC','DRDDOCCCYAMT','DRDDOCAMT','DRDONHOLDIND','DRDREVWDATE','DRDPPLDGCODE','DRDPPACCNBRI','DRDOSLDGCODE','DRDOSACCNBRI','DDIRECTDEBITIND','DPAYACCT','DREASCODE','DREASDATE','DREASNARR1','DREASNARR2','DPRTREQIND','LINEID','LNEDETAILTYPE','LLDGCODE','LACCNBR','LDRAWNAME','LUNITS1','LUNITS2','LUNITS3','LUNITS4','LRATETYPE','LRATECODE','LRATEAMT','LCCYAMOUNT','LAMOUNT1','LAMOUNT2','LAMOUNT3','LAMOUNT4','LNARR1','LNARR2','LNARR3','LMERCHCCYAMT','LMERCHAMT','LDUEDATE','LDISCCCYAMT','LDISCAMT1','LDISCAMT2','LDISCDATE','LRETNCCYAMT','LRETNAMT','LRETNREASON','LRETNREVIEW','LPRESCSTAT','LCOINCCYAMT','LCOINAMT','LUSERFLD1','LUSERFLD2','LUSERFLD3','LUSERFLD4','LUSERFLD5','LUSERFLD6','LUSERFLD7','LUSERFLD8','LUSERFLD9','LUSERFLD10','LGSTTYPE','LGSTRATECODE','LGSTDATE','LGSTRATEAMT','LGSTCCYAMT','LGSTAMT','LGSTEXCCCYAMT','LGSTEXCAMT','LGSTRCRATEAMT','LGSTRCCCYAMT','LGSTRCAMT','LEXTHOLDA','LEXTHOLDI','LSEPCHEQUE','LCOLLCOMM','LASSETIND','LASSETREG','LASSETNBR','LBOOKNBR','LTEMPLASSNBR','LASSETDESCR','LITEMTYPE','LITEMCODE','LITEMRATE','LRESGRPCODE','LRESCODE','LRESUNITSNAME','LRESUNITSRATE','LCNTRCODE','LPRESPMTRLSAMT','LPRESPMTRLSCCYAMT','LPRESPEXLNSAMT','LPRESPEXLNSCCYAMT','LCTRCTPROFNAME','LCTRCTNBR','LCTRCTSCHEDNBR','LCTRCTSCHEDITEMNBR','LCTRCTVARNBR','LCTRCTPARTYPROF','LCTRCTPARTYNBR','LCTRCTCLAIMNBR','BIMPNAME','BNAME','BWKFLWASSIGNUSER','BGROUP','BDESCR','BNARR1','BNARR2','BNARR3','BFORMAT','BDOCTYPE','BPERIOD','BCALENDAR','BPROGRP','BREVPROGRP','BSUSPLDGCODE','BSUSPACCNBRI','BBALLDG','BBALACCNBR','BGSTRATECODE','BGSTDATE','BBALDATE1','BBALREF1','BDATE','BCLRENTITY','BPROCTYPE','BSTATUS','RDBATTYPE','RDGENDOCREFMTH','LPDESCR','LPUNITS','LPUNITSNAME','LPRATETYPE','LPRATECODE','LPRATEAMT','LPCCYAMT','LPAMT','LPDOCDATE','LPDOCREF','LPGSTTYPE','LPGSTRATECODE','LPGSTDATE','LPGSTRATEAMT','LPGSTCCYAMT','LPGSTAMT','LPGSTINCCCYAMT','LPGSTINCAMT','LPGSTEXCCCYAMT','LPGSTEXCAMT','LBRECTYPE','LBRECCCYAMT','LBRECAMT','LBCHQNBR','LBDRWNAME','LBDRWBANK','LBDRWBRANCH','LBVCHNBR','LBCARDNBR','LBEXPRYDATE','LBOTHREASON','LBCARDHOLDERNAME','LOPLOCNCODE','LOPORDNBR','LOBKORDNBR','LOSRCINVAMT','LOSRCINVEXCAMT','LOSRCADDCOST','LOSRCADDCOSTEXC','LOPORDMATCHOPT','LOSRCDETAILS1','LOSRCDETAILS2','LAFILENAME','LAATTTYPE');
		fputcsv($f, $fields, $delimiter); 
		$email_html = '<div class="m_paymail_wrapper" style="display: block;
	    float: left;
	    width: 100%;">
	<div class="paymail_intro">
			Hi,<br><br>
			Please find the payments received on '.$dt->format('d-F-Y').' below:
	</div>
	<div class="paymail_table">
		<table style="display: block;
	    float: left;
	    width: 800px;
	    font-size: 16px;">
			<tr>
				<th style="padding-bottom: 10px !important;text-align: left;border-bottom: 1px solid;
	    padding: 10px 0px;">Date</th>
				<th style="padding-bottom: 10px !important;text-align: left;border-bottom: 1px solid;
	    padding: 10px 0px;">Name of Company</th>
				<th style="padding-bottom: 10px !important;text-align: left;border-bottom: 1px solid;
	    padding: 10px 0px;">Name</th>
				<th style="padding-bottom: 10px !important;text-align: left;border-bottom: 1px solid;
	    padding: 10px 0px;">Amount(BBD)</th>
			</tr>';
		foreach( $result as $results ) {
		$email_html .= '<tr>';

			


			$table_name1 = "crn_woocommerce_order_itemmeta";
			$result1 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='_product_id'");

			$result21 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='_qty'");
			$result22 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='_line_total'");

			$table_name0 = "wp_flexi_product_relation";
			$result0 = $wpdb->get_results( "SELECT * FROM ".$table_name0."  where product_id='".$result1[0]->meta_value."'");

			$table_name11 = "crn_woocommerce_order_itemmeta";
			$result11 = $wpdb->get_results( "SELECT * FROM ".$table_name11."  where order_item_id='".$results->order_item_id."' and meta_key='_variation_id'");

			$table_name110 = "crn_postmeta";
			$result110 = $wpdb->get_results( "SELECT * FROM ".$table_name110."  where order_item_id='".$result11[0]->meta_value."' and meta_key='_regular_price'");

			$table_name12 = "crn_postmeta";
			$result12 = $wpdb->get_results( "SELECT * FROM ".$table_name12."  where post_id='".$result11[0]->meta_value."' and meta_key='_regular_price'");
			//print_r($results1);
			$table_name13 = "wp_flexi_rate_table";
			$result13 = $wpdb->get_results( "SELECT * FROM ".$table_name13."  where attribute_id='".$result11[0]->meta_value."'");
			
			$result2 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='_line_total'");
			$result3 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='pickup_hidden_datetime'");
			if($result3[0]->meta_value=="")
			{
				
			}
			else
			{
				//$datetime = $result3[0]->meta_value;
			}
			$table_name2="crn_posts";
			$result31 = $wpdb->get_results( "SELECT * FROM ".$table_name2."  where id='".$results->order_id."' and post_type='shop_order'");
			$datetime = $result31[0]->post_date;
			$datetime1 = explode(" ",$datetime);
			//echo $datetime1[0];
			$datetime2 = explode("-",$datetime1[0]);
			//$datetime3 = $datetime2[2].'/'.$datetime2[1].'/'.$datetime2[0];
			//echo $datetime3;

			$datetime3 = date('d/m/Y',strtotime($datetime));

			$datetimeMail = date('d-F-Y',strtotime($datetime));

			$email_html .= "<td style='border-bottom: 1px solid;
	    padding: 10px 0px;width: 160px'>$datetimeMail</td>";

			$GA = $datetime3;
			//$month=date("F",strtotime($datetime));
			//$year=date("Y",strtotime($datetime));
			$table_name314="crn_postmeta";
			$result314 = $wpdb->get_results( "SELECT * FROM ".$table_name314."  where post_id='".$results->order_id."' and meta_key='_plugnpay_charge_id'");
			$DREF2 = $result314[0]->meta_value;
			//$DREF22 = substr($DREF2, -6);
			$DREF22 = "$DREF2\t";
			//$DREF22 = $DREF2;

			$result315 = $wpdb->get_results( "SELECT * FROM ".$table_name314."  where post_id='".$results->order_id."' and meta_key='_paid_date'");
			$DREF3 = $result315[0]->meta_value;
			$DREF3DAETIME = explode(" ",$DREF3);
			//die;
			$result4 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='Select Start Date'");
			$result5 = $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='Select End Date'");

			$table_name3="crn_postmeta";

			$result39 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_customer_user'");
			$user_id = $result39[0]->meta_value;

			$result40 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_company'");
			$company = $result40[0]->meta_value;

			$email_html .= "<td style='border-bottom: 1px solid;
	    padding: 10px 0px;width: 350px'>$company</td>";

			
			$result41 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_first_name'");
			$firstname = $result41[0]->meta_value;

			$result42 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_last_name'");
			$lastname = $result42[0]->meta_value;

			$name = $firstname.' '.$lastname;

			$email_html .= "<td style='border-bottom: 1px solid;
	    padding: 10px 0px;width: 180px'>$name</td>";
			$email_html .= "<td style='border-bottom: 1px solid;
	    padding: 10px 0px;width: 180px'>".$result22[0]->meta_value."</td>";
			$email_html .= '</tr>';

			$result43 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_address_1'");
			//print_r($result43);
			$_billing_address_1 = $result43[0]->meta_value;
			$_billing_address_1 = explode(",",$_billing_address_1);

			$DPOSTADDR1=$_billing_address_1[0];

			$DPOSTADDR2=$_billing_address_1[1];

			$result44 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_address_2'");
			$_billing_address_2 = $result44[0]->meta_value;
			//$_billing_address_2 = str_replace(","," ",$_billing_address_2);
			$result45 = $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_city'");
			$_billing_city = $result45[0]->meta_value;

			$result46= $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_state'");
			$_billing_state = $result46[0]->meta_value;

			//$_billing_state = "St Michael";
			$result47= $wpdb->get_results( "SELECT * FROM ".$table_name3."  where post_id='".$results->order_id."' and meta_key='_billing_postcode'");
			$_billing_postcode = $result47[0]->meta_value;


			$table_name4="crn_product_inventory_relation";
			$result48= $wpdb->get_results( "SELECT * FROM ".$table_name4."  where order_id='".$results->order_id."'");
			$sku = '3.86.'.$result48[0]->sku.'.06f';
			$unit = $result48[0]->suit_no;


			$result49= $wpdb->get_results( "SELECT * FROM ".$table_name1."  where order_item_id='".$results->order_item_id."' and meta_key='p_code'");
			$p_code = $result49[0]->meta_value;
			$user_id = get_post_meta($results->order_id, '_customer_user', true);
			$LACCNBR = get_user_meta($user_id, 't1_customer', true);


			if($company=="")
			{
				$company = $firstname.' '.$lastname;
			}
				for ($i = 0; $i < 3; $i++)
				{
					if($i==0){$LINEID="1";}if($i==1){$LINEID="400";}if($i==2){$LINEID="500";}if($i==3){$LINEID="500";}
					if($i==0){$LNEDETAILTYPE="L";}if($i==1){$LNEDETAILTYPE="P";}if($i==2){$LNEDETAILTYPE="B";}if($i==3){$LNEDETAILTYPE="P";}
					if($i==0){$LLDGCODE="AR";}if($i==1){$LLDGCODE="GL";}if($i==2){$LLDGCODE="GL";}if($i==3){$LLDGCODE="GL";}
					if($i==4){$LLDGCODE="GL";}
					

					if($i==0)
					{
						$LACCNBR = get_user_meta($user_id, 't1_customer', true);
						$LACCNBR1 = "$LACCNBR\t";
						$LUNITS1= '-'.$result21[0]->meta_value;
						$LRATE = '-'.$result22[0]->meta_value;
						$LAMOUNT12 = '';
						$LAMOUNT122 = '';
						$DG = 		'-'.$result22[0]->meta_value;
						$DH = 		'-'.$result22[0]->meta_value; 
						$DI = 		'-'.$result22[0]->meta_value;
						$LAMOUNT1 = '-'.$result22[0]->meta_value;
						$DPOSTIMPIND = "Y";
						$number = "001";
						$DPOSTADDRCODE=	"$number\t";
						$DPOSTNAME = $name;
						$DPOSTADDR1 = $DPOSTADDR1;
						$DPOSTADDR2 = $DPOSTADDR2;
						$DPOSTSTATE = $_billing_state;
						$AI = "bb";
						$AH = " ";
						$GA = " ";
						$GB = " ";
					}
					
					
					if($i==1)
					{
						$LACCNBR1 = '2900010101';
						$LAMOUNT12 = $result22[0]->meta_value;
						$LAMOUNT122 = $result22[0]->meta_value;
						$DG = '';
						$DH = '';
						$DI = '';
						$LAMOUNT1 = " ";
						$DPOSTIMPIND = "";
						$DPOSTADDRCODE="";
						$DPOSTNAME = "";
						$DPOSTADDR1 = "";
						$DPOSTADDR2 = "";
						$DPOSTSTATE = "";
						$AI = "";
						$AH ="";
						$GA = $datetime3;
						$GB = $results->order_id.'t';

					}
					
					if($i==2)
					{
						$LACCNBR1 = '2900010101';
						$LAMOUNT12 = '';
						$LAMOUNT122 = $result22[0]->meta_value;
						$DG = '';
						$DH = '';
						$DI = '';
						$LAMOUNT1 = " ";
						$DPOSTIMPIND = "";
						$DPOSTADDRCODE="";
						$DPOSTNAME = "";
						$DPOSTADDR1 = "";
						$DPOSTADDR2 = "";
						$DPOSTSTATE = "";
						$AI = "";
						$AH ="";
						$GA = " ";
						$GB = " ";

					}
					$LPDESCR = 'Payments will be applied to oldest'.' '.'invoice first - '.$results->order_id;

				 $lineData = array($results->order_id.'t','cebuy',$datetime3,'bbd','1',$results->order_id.'t',
				$DREF22,$DREF3DAETIME[1],$datetime3,'','','','AR',$datetime2[1],'ARCRIMP','','S',
				'','','','','','','','',
				$DPOSTIMPIND,$DPOSTADDRCODE,$DPOSTNAME,$DPOSTADDR1,$DPOSTADDR2,'',$billing_city,
				$DPOSTSTATE,$AH,$AI,'','','',
				'','','','','','','','','','','','','','','','','','','','','','','Y',
				$LINEID,$LNEDETAILTYPE,$LLDGCODE,$LACCNBR1,'','','','','','',
				'','',$LAMOUNT1,$LAMOUNT1,'','','','Payments will be applied to oldest','invoice first -',$results->order_id,'','','','',
				'','','','','','','','','','','','','','','','','','','','',
				'I','NA','','','',$DG,$DH,$DI,'','','','','',
				 '','','','','','','','','','','','','','','','','','','','','','','','','','','',
				 '','MOCO_PAY','','','$AR','','','','','CEWEBREC','ARCRIMP',$datetime2[1],'ce',$datetime2[0],
				 '','GL','2990099999','GL','2900010100','','','','','','','','S','','',$LPDESCR,'','','',
				 'NA','',$LAMOUNT12,$LAMOUNT12,$GA,$GB,'','','','0','0','0',
				 $LAMOUNT122,$LAMOUNT122,$LAMOUNT122,$LAMOUNT122,'$oth',
				 $LAMOUNT122,$LAMOUNT122,'','','','','','','',$company,'','','','','','','','','','','',''); 
				 fputcsv($f, $lineData, $delimiter); 
				}
			
		   
			
		   
		}  
		
		fseek($f, 0); 

		$email_html .= '</table>
	</div>

	<div style="display: block; float: left; width: 100%; padding-top: 30px;">
		Best Regards,
	</div>
</div>';
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.dropboxapi.com/oauth2/token?grant_type=refresh_token&refresh_token=TlOcvmy9SNsAAAAAAAAAAaPW1u-W5gKWJcJw1SS4ucGUWI9YIO7THhkpKCnU3alF',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'client_id=9nv8jn7jpq7k9cu&client_secret=guh48nqszxfgyeq',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$data = json_decode($response);

		$path = ABSPATH . "wp-content/uploads/exports/" . $filename;
		$fp = fopen($path, 'rb');
		$size = filesize($path);
		

	

		//Configure Dropbox Application
		$app = new DropboxApp("9nv8jn7jpq7k9cu", "guh48nqszxfgyeq",$data->access_token);

		//Configure Dropbox service
		$dropbox = new Dropbox($app);
		
		$dropboxFile = new DropboxFile($path);

		$file = $dropbox->simpleUpload($dropboxFile, "/DesktopsFilesExport/Payments/".$filename, ['autorename' => true]);
		if(count($results))
		{
			wp_mail("accounts@crane.bb","Payments - Moco.bb - ".$dt->format('d-F-Y'),$email_html);
           wp_mail("danielle@crane.bb","Payments - Moco.bb - ".$dt->format('d-F-Y'),$email_html);
           wp_mail("kartik@webstylze.com","Payments - Moco.bb - ".$dt->format('d-F-Y'),$email_html);
			wp_mail("aliasgar.arif@gmail.com","Payments - Moco.bb - ".$dt->format('d-F-Y'),$email_html);
		}
		exit;
}


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

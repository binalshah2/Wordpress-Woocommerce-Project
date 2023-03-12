<?php
add_filter( 'woocommerce_rest_check_permissions', 'moco_allow_rest_api_queries', 10, 4 ); 
function moco_allow_rest_api_queries( $permission, $context, $zero, $object ) {

	$headers =  getallheaders();

	// Optionally limit permitted queries to different contexts.
	/*if ( 'read' != $context ) {
		return $permission;
	}*/
	// Write the parameters to the error log (or debug.log file) to see what requests are being accessed.
	//error_log( sprintf( 'Permission: %s, Context: %s; Object: %s', var_export( $permission, true ), $context, var_export( $object, true ) ) );

	return true;  // Allow all queries.
}
add_action('rest_api_init', 'register_moco_routes');
function register_moco_routes() {

	global $wp;


	
	register_rest_route('v3', '/productDetails', array(
        'methods' => 'GET',
        'callback' => 'getMocoProductDetails'
    ));

//echo $wp->request;die (print '');
	if($wp->request=='wp-json/v3/cart_test')
	{
		//$_POST = $_GET;
	}
   

	
	register_rest_route('v3', '/logout', array(
        'methods' => 'GET',
        'callback' => 'moco_logout'
    ));
	
	

	register_rest_route('v3', '/check_phone', array(
        'methods' => 'GET',
        'callback' => 'moco_check_phone'
    ));


	register_rest_route('v3', '/change_password', array(
        'methods' => 'POST',
        'callback' => 'moco_change_password'
    ));

	

	register_rest_route('v3', '/countries', array(
        'methods' => 'GET',
        'callback' => 'moco_countries'
    ));




	register_rest_route('v3', '/add_device', array(
        'methods' => 'POST',
        'callback' => 'moco_add_device'
    ));


	register_rest_route('v3', '/addresses', array(
        'methods' => 'GET',
        'callback' => 'mocoget_address_book'
    ));

	register_rest_route('v3', '/add_address', array(
        'methods' => 'POST',
        'callback' => 'mocoadd_address_book'
    ));

	register_rest_route('v3', '/edit_address', array(
        'methods' => 'POST',
        'callback' => 'mocoedit_address_book'
    ));

	register_rest_route('v3', '/delete_address', array(
        'methods' => 'POST',
        'callback' => 'mocodelete_address_book'
    ));


	register_rest_route('v3', '/check_availibility', array(
        'methods' => 'GET',
        'callback' => 'moco_check_availibility'
    ));


	register_rest_route('v3', '/cart_test', array(
        'methods' => 'POST',
        'callback' => 'moco_cart_test'
    ));


	register_rest_route('v3', '/place_order', array(
        'methods' => 'POST',
        'callback' => 'moco_place_order'
    ));

	register_rest_route('v3', '/place_order_arb', array(
        'methods' => 'POST',
        'callback' => 'moco_place_order_arb'
    ));


	register_rest_route('v3', '/request_quote', array(
        'methods' => 'POST',
        'callback' => 'request_quote'
    ));


	register_rest_route('v3', '/arb_balance', array(
        'methods' => 'GET',
        'callback' => 'moco_arb_balance'
    ));
    register_rest_route('v3', '/arb_balance_website', array(
        'methods' => 'GET',
        'callback' => 'moco_arb_balance_website'
    ));
	
	register_rest_route('v3', '/contact_info', array(
        'methods' => 'GET',
        'callback' => 'moco_contact_info'
    ));
    register_rest_route('v3', '/moco_get_all_customers', array(
        'methods' => 'GET',
        'callback' => 'moco_get_all_customers'
    ));
    register_rest_route('v3', '/moco_get_duplicate_customers', array(
        'methods' => 'GET',
        'callback' => 'moco_get_duplicate_customers'
    ));
    register_rest_route('v3', '/moco_get_customers_nine_numbers', array(
        'methods' => 'GET',
        'callback' => 'moco_get_customers_nine_numbers'
    ));
     register_rest_route('v3', '/moco_email_to_customers', array(
        'methods' => 'GET',
        'callback' => 'moco_email_to_customers'
    ));
      register_rest_route('v3', '/moco_get_containers', array(
        'methods' => 'GET',
        'callback' => 'moco_get_containers'
    ));
      register_rest_route('v3', '/moco_customers_exists', array(
        'methods' => 'GET',
        'callback' => 'moco_customers_exists'
    ));
    register_rest_route('v3', '/moco_activate_user', array(
        'methods' => 'GET',
        'callback' => 'moco_activate_user'
    ));

}
function moco_activate_user(WP_REST_Request $request)
{
	global $wpdb;
	$params = $request->get_params();
	update_user_meta($params['userid'],'alg_wc_ev_is_activated','1');
	$data['msg'] = "User activated successfully";
	echo json_encode($data);
    exit;
}
function moco_customers_exists(WP_REST_Request $request)
{
	global $wpdb;
	$params = $request->get_params();
	$search = $params['searchtxt'];
	if($search=="")
	{
		$data['msg'] = "Please Enter search term";
	}
	else
	{

		$query = "SELECT * FROM crn_moco_customers where  email='".$search."' OR T1Customer='".$search."'";
		$result = $wpdb->get_results( $query );
		if(count($result)=="0")
		{
			$data['msg'] = "Looks like you dont have account";
		}
		else
		{
			$data['msg'] = "Thank you! Your account has been verified. Please click link below to continue registration.";
			$data['username'] = $result[0]->MId;
			$data['email'] = $result[0]->Email;
		}
	}
	echo json_encode($data);
    exit;
}
//http://api.craneops.net/api/Operator/Equipments/GetAvailableEquipmentbyDateRange?EquipmentCategory=3&StartDate=07%2F14%2F2021&EndDate=08%2F25%2F2021
function moco_get_containers(WP_REST_Request $request)
{
	//https://moco.bb/wp-json/v3/moco_get_containers
	$moco_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Equipments/GetAvailableEquipmentbyDateRange?EquipmentCategory=3&StartDate=07%2F28%2F2021&EndDate=09%2F28%2F2021");
	$moco_data_result = $moco_data->Result;
	global $wpdb;
	$wpdb->query($wpdb->prepare ("DELETE FROM crn_moco_equipments" ));
	$userdata = array();
	foreach($moco_data_result as $key => $user_data)
		{
			$userdata[$key]['EquipmentId'] =  $user_data->EquipmentId;
			$userdata[$key]['EquipmentName'] =  $user_data->EquipmentName ; 
			$userdata[$key]['EquipmentCode'] =  $user_data->EquipmentCode;
			$userdata[$key]['IsSecondaryEquipment'] =  $user_data->IsSecondaryEquipment ; 
			$userdata[$key]['CreatedBy'] =  $user_data->CreatedBy ; 
			$userdata[$key]['EquipmentCategory'] =  $user_data->EquipmentCategory ; 
			$userdata[$key]['EquipmentCategoryName'] =  $user_data->EquipmentCategoryName ; 
		  	$userdata[$key]['ColorCode'] =  $user_data->ColorCode ; 
		  	$userdata[$key]['EquipmentImage'] =  $user_data->EquipmentImage ; 
		  	$userdata[$key]['EquipmentType'] =  $user_data->EquipmentType ; 
		  	$userdata[$key]['EquipmentCondition'] =  $user_data->EquipmentCondition ; 
		  	$userdata[$key]['ConditionDescription'] =  $user_data->ConditionDescription ; 
		  	$userdata[$key]['IsNewAdded'] =  $user_data->IsNewAdded ; 

		  	$data = array(
			"EquipmentId" =>  $user_data->EquipmentId,
			"EquipmentName" =>  $user_data->EquipmentName , 
			"EquipmentCode" =>  addslashes($user_data->EquipmentCode),
			"IsSecondaryEquipment" =>  addslashes($user_data->IsSecondaryEquipment) , 
			"CreatedBy" =>  addslashes($user_data->CreatedBy) , 
			"EquipmentCategory" =>  addslashes($user_data->EquipmentCategory) , 
			"EquipmentCategoryName" =>  addslashes($user_data->EquipmentCategoryName) , 
		  	"ColorCode" =>  addslashes($user_data->ColorCode) , 
		  	"EquipmentImage" =>  addslashes($user_data->EquipmentImage) , 
		  	"EquipmentType" =>  addslashes($user_data->EquipmentType) , 
		  	"EquipmentCondition" =>  addslashes($user_data->EquipmentCondition) , 
		  	"ConditionDescription" =>  addslashes($user_data->ConditionDescription) , 
		  	"IsNewAdded" =>  addslashes($user_data->IsNewAdded) 
		  	 );
			$wpdb->insert("crn_moco_equipments", $data);

			
		}
	return $userdata;

}
function moco_email_to_customers()
{
	global $wpdb;
	echo $query = "SELECT * FROM crn_moco_customers WHERE id='4'";
	$results = $wpdb->get_results($query);

	print_r($results);
	$username = $results[0]->MId;
	$email = $results[0]->Email;
	$company = $results[0]->ContactName;
	$to = "binalshah2@gmail.com";
	die; 
    $subject = "Hi , welcome to our site!";
    $body = '<h1>Please click at below link to regsiter with our site</h1></br>
              <p><a href="http://localhost/moco/registration/?company='.$company.'&email='.$email.'
              	&username='.$username.'">Register</a></p>
              <p>Kind Regards,</p>
              <p>Site Admin.</p>';
    //die;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    if (wp_mail($to, $subject, $body, $headers)) {
      error_log("email has been successfully sent to user whose email is " . $user_email);
    }else{
      error_log("email failed to sent to user whose email is " . $user_email);
    }
}
/*
function moco_register_qa_function($user_id){
	//echo $user_id;
	$user_id = '6898';
	$user_info = get_userdata($user_id);
	//print_r($user_info);
	//die;
	$post_id = "user_$user_id"; // user ID = 2
	
	$post_array = array();
	$post_array['ContactName'] = $user_info->first_name.' '.$user_info->last_name;
	$post_array['Email'] = $user_info->user_email;
	$post_array['Address1'] = get_field( 'billing_address_1', $post_id);
	$post_array['Address2'] = get_field( 'billing_address_2', $post_id);
	$post_array['Address3'] = "";
	$post_array['City'] = get_field( 'billing_city', $post_id);
	$post_array['State'] = get_field( 'billing_state', $post_id);

	$moco_user_data = moco_fetch_data_curl("http://qaapi.craneops.net/api/Operator/Customer/GetAllJobCustomer");

	//ini_set("display_errors",1);
	//serror_reporting(1);
	
	//print_r($moco_user_data);
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
		
	
}
*/
function moco_get_all_customers(WP_REST_Request $request)
{
	//http://moco.bb/wp-json/v3/moco_get_all_customers/
	$moco_user_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetAllJobCustomer");

	$moco_user_data_result = $moco_user_data->Result;
	//print_r($moco_user_data_result);
		//echo count($moco_user_data_result);
		//die;
	  	global $wpdb;
	  	$wpdb->query($wpdb->prepare ("DELETE FROM crn_moco_customers" ));
	  	//die;

		$userdata = array();
		foreach($moco_user_data_result as $key => $user_data)
		{
			//print_r($user_data);
			$userdata[$key]['Id'] =  $user_data->Id;
			$userdata[$key]['T1Customer'] =  $user_data->T1Customer ; 
			$userdata[$key]['Description'] =  $user_data->Description;
			$userdata[$key]['ContactName'] =  $user_data->ContactName ; 
			$userdata[$key]['ContactDetails'] =  $user_data->ContactDetails ; 
			$userdata[$key]['AccType'] =  $user_data->AccType ; 
			$userdata[$key]['CreditLimit'] =  $user_data->CreditLimit ; 
		  	$userdata[$key]['Email'] =  $user_data->Email ; 
		  	$userdata[$key]['CustomerOldEmail'] =  $user_data->CustomerOldEmail ; 
		  	$userdata[$key]['ContactTitle'] =  $user_data->ContactTitle ; 
		  	$userdata[$key]['ContactInitials'] =  $user_data->ContactInitials ; 
		  	$userdata[$key]['ContactPosn'] =  $user_data->ContactPosn ; 
		  	$userdata[$key]['Phone'] =  $user_data->Phone ; 
		  	$userdata[$key]['ChartName'] =  $user_data->ChartName ; 
		  	$userdata[$key]['PayName'] =  $user_data->PayName ; 
		  	$userdata[$key]['Address1'] =  $user_data->Address1 ;
		  	$userdata[$key]['Address2'] =  $user_data->Address2 ;  
		    $userdata[$key]['Address3'] =  $user_data->Address3 ;  
		    $userdata[$key]['City'] =  $user_data->City ;
		    $userdata[$key]['State'] =  $user_data->State ;
		    $userdata[$key]['BadCreditFlag'] =  $user_data->BadCreditFlag ;  
		    $userdata[$key]['IsCashCustomer'] =  $user_data->IsCashCustomer ;  
		    $userdata[$key]['SecondaryEmailAddress'] =  $user_data->SecondaryEmailAddress ;  
		    $userdata[$key]['IsPrefferedCustomer'] =  $user_data->IsPrefferedCustomer ;  
		    $userdata[$key]['EmailPermission'] =  $user_data->EmailPermission ;  
		    $userdata[$key]['IsPurchaseOrder'] =  $user_data->IsPurchaseOrder ;  
		    $userdata[$key]['IsCreditFlag'] =  $user_data->IsCreditFlag ;
		    $userdata[$key]['ARBalance'] =  $user_data->ARBalance ; 
			
		  
		    $data = array(
			"MId" =>  $user_data->Id,
			"T1Customer" =>  $user_data->T1Customer , 
			"Description" =>  addslashes($user_data->Description),
			"ContactName" =>  addslashes($user_data->ContactName) , 
			"ContactDetails" =>  addslashes($user_data->ContactDetails) , 
			"AccType" =>  addslashes($user_data->AccType) , 
			"CreditLimit" =>  addslashes($user_data->CreditLimit) , 
		  	"Email" =>  addslashes($user_data->Email) , 
		  	"CustomerOldEmail" =>  addslashes($user_data->CustomerOldEmail) , 
		  	"ContactTitle" =>  addslashes($user_data->ContactTitle) , 
		  	"ContactInitials" =>  addslashes($user_data->ContactInitials) , 
		  	"ContactPosn" =>  addslashes($user_data->ContactPosn) , 
		  	"Phone" =>  addslashes($user_data->Phone) , 
		  	"ChartName" =>  addslashes($user_data->ChartName) , 
		  	"PayName" =>  addslashes($user_data->PayName) , 
		  	"Address1" =>  addslashes($user_data->Address1) ,
		  	"Address2" =>  addslashes($user_data->Address2),  
		    "Address3" =>  addslashes($user_data->Address3) ,  
		    "City" =>  addslashes($user_data->City) ,
		    "State" =>  addslashes($user_data->State),
		    "BadCreditFlag" =>  addslashes($user_data->BadCreditFlag) ,  
		    "IsCashCustomer" =>  addslashes($user_data->IsCashCustomer) ,  
		    "SecondaryEmailAddress" =>  addslashes($user_data->SecondaryEmailAddress) ,  
		    "IsPrefferedCustomer" =>  addslashes($user_data->IsPrefferedCustomer) ,  
		    "EmailPermission" =>  addslashes($user_data->EmailPermission) ,  
		    "IsPurchaseOrder" =>  addslashes($user_data->IsPurchaseOrder) ,  
		    "IsCreditFlag" =>  addslashes($user_data->IsCreditFlag) ,
		    "ARBalance" =>  addslashes($user_data->ARBalance) );
			$wpdb->insert("crn_moco_customers", $data);

			
		}
	
	//print_r($userdata);
	//die;
	//$user->ID = 6324;

	//$post_id = "user_".$user->ID; // user ID = 2

	return $userdata;
}
function moco_get_duplicate_customers(WP_REST_Request $request)
{
	//http://moco.bb/wp-json/v3/moco_get_duplicate_customers/
	global $wpdb;
	$query = "SELECT T1Customer, Email,COUNT(Email) FROM crn_moco_customers GROUP BY Email HAVING COUNT(Email) > 1";
	$results = $wpdb->get_results($query);
	$return_array = array();
	for($i=0;$i<count($results);$i++)
	{
		$query1="SELECT * FROM `crn_moco_customers` WHERE `T1Customer`='999999' and Email='".$results[$i]->Email."'";
		$results1 = $wpdb->get_results($query1);
		if(!empty($results1))
		{
			//print_r($results1);	
			$return_array[$i]['MId']=$results1[0]->MId;
			$return_array[$i]['T1Customer']=$results1[0]->T1Customer;
			$return_array[$i]['Description']=$results1[0]->Description;
			$return_array[$i]['ContactName']=$results1[0]->ContactName;
			$return_array[$i]['ContactDetails']=$results1[0]->ContactDetails;
			$return_array[$i]['AccType']=$results1[0]->AccType;
			$return_array[$i]['CreditLimit']=$results1[0]->CreditLimit;
			$return_array[$i]['Email']=$results1[0]->Email;
			$return_array[$i]['CustomerOldEmail']=$results1[0]->CustomerOldEmail;
			$return_array[$i]['ContactTitle']=$results1[0]->ContactTitle;
			$return_array[$i]['ContactInitials']=$results1[0]->ContactInitials;
			$return_array[$i]['ContactPosn']=$results1[0]->ContactPosn;
			$return_array[$i]['Phone']=$results1[0]->Phone;
			$return_array[$i]['ChartName']=$results1[0]->ChartName;
			$return_array[$i]['PayName']=$results1[0]->PayName;
			$return_array[$i]['Address1']=$results1[0]->Address1;
			$return_array[$i]['Address2']=$results1[0]->Address2;
			$return_array[$i]['Address3']=$results1[0]->Address3;
			$return_array[$i]['City']=$results1[0]->City;
			$return_array[$i]['State']=$results1[0]->State;
			$return_array[$i]['BadCreditFlag']=$results1[0]->BadCreditFlag;
			$return_array[$i]['IsCashCustomer']=$results1[0]->IsCashCustomer;
			$return_array[$i]['SecondaryEmailAddress']=$results1[0]->SecondaryEmailAddress;
			$return_array[$i]['IsPrefferedCustomer']=$results1[0]->IsPrefferedCustomer;
			$return_array[$i]['EmailPermission']=$results1[0]->EmailPermission;
			$return_array[$i]['IsPurchaseOrder']=$results1[0]->IsPurchaseOrder;
			$return_array[$i]['IsCreditFlag']=$results1[0]->IsCreditFlag;
			$return_array[$i]['ARBalance']=$results1[0]->ARBalance;
		}
		
	}
	$data = array_values($return_array);
	//print_r($data);
	ob_end_clean();
	$filename = "customer_duplicate.csv"; 
	$delimiter = ","; 
	 
	// Create a file pointer 
	$f = fopen('php://memory', 'w'); 
	$fields = array('Id','T1Customer','Description','ContactName','ContactDetails','AccType','CreditLimit','Email','CustomerOldEmail','ContactTitle','ContactInitials','ContactPosn','Phone','ChartName','PayName','Address1','Address2','Address3','City','State','BadCreditFlag','IsCashCustomer','SecondaryEmailAddress','IsPrefferedCustomer','EmailPermission','IsPurchaseOrder','IsCreditFlag','ARBalance');
	fputcsv($f, $fields, $delimiter); 
	for($i=0;$i<count($data);$i++)
	{
		//print_r($data);
		 $lineData = array($data[$i]['MId'],$data[$i]['T1Customer'],$data[$i]['Description'],$data[$i]['ContactName'],$data[$i]['ContactDetails'],$data[$i]['AccType'],$data[$i]['CreditLimit'],$data[$i]['Email'],$data[$i]['CustomerOldEmail'],$data[$i]['ContactTitle'],$data[$i]['ContactInitials'],$data[$i]['ContactPosn'],$data[$i]['Phone'],$data[$i]['ChartName'],$data[$i]['PayName'],$data[$i]['Address1'],$data[$i]['Address2'],$data[$i]['Address3'],$data[$i]['City'],$data[$i]['State'],$data[$i]['BadCreditFlag'],$data[$i]['IsCashCustomer'],$data[$i]['SecondaryEmailAddress'],$data[$i]['IsPrefferedCustomer'],$data[$i]['EmailPermission'],$data[$i]['IsPurchaseOrder'],$data[$i]['IsCreditFlag'],$data[$i]['ARBalance']); 
		 fputcsv($f, $lineData, $delimiter);   
	}
	fseek($f, 0); 
 
// Set headers to download file rather than displayed 
	header('Content-Type: text/csv'); 
	header('Content-Disposition: attachment; filename="' . $filename . '";'); 
	 
	// Output all remaining data on a file pointer 
	fpassthru($f); 
	
	die;
	
}
function moco_get_customers_nine_numbers(WP_REST_Request $request)
{
	//http://moco.bb/wp-json/v3/moco_get_duplicate_customers/
	global $wpdb;
	$query1="SELECT * FROM `crn_moco_customers` WHERE `T1Customer`='999999'";
	$results1 = $wpdb->get_results($query1);
	$return_array = array();
	for($i=0;$i<count($results1);$i++)
	{
		
		if(!empty($results1))
		{
			//print_r($results1);	
			$return_array[$i]['MId']=$results1[0]->MId;
			$return_array[$i]['T1Customer']=$results1[0]->T1Customer;
			$return_array[$i]['Description']=$results1[0]->Description;
			$return_array[$i]['ContactName']=$results1[0]->ContactName;
			$return_array[$i]['ContactDetails']=$results1[0]->ContactDetails;
			$return_array[$i]['AccType']=$results1[0]->AccType;
			$return_array[$i]['CreditLimit']=$results1[0]->CreditLimit;
			$return_array[$i]['Email']=$results1[0]->Email;
			$return_array[$i]['CustomerOldEmail']=$results1[0]->CustomerOldEmail;
			$return_array[$i]['ContactTitle']=$results1[0]->ContactTitle;
			$return_array[$i]['ContactInitials']=$results1[0]->ContactInitials;
			$return_array[$i]['ContactPosn']=$results1[0]->ContactPosn;
			$return_array[$i]['Phone']=$results1[0]->Phone;
			$return_array[$i]['ChartName']=$results1[0]->ChartName;
			$return_array[$i]['PayName']=$results1[0]->PayName;
			$return_array[$i]['Address1']=$results1[0]->Address1;
			$return_array[$i]['Address2']=$results1[0]->Address2;
			$return_array[$i]['Address3']=$results1[0]->Address3;
			$return_array[$i]['City']=$results1[0]->City;
			$return_array[$i]['State']=$results1[0]->State;
			$return_array[$i]['BadCreditFlag']=$results1[0]->BadCreditFlag;
			$return_array[$i]['IsCashCustomer']=$results1[0]->IsCashCustomer;
			$return_array[$i]['SecondaryEmailAddress']=$results1[0]->SecondaryEmailAddress;
			$return_array[$i]['IsPrefferedCustomer']=$results1[0]->IsPrefferedCustomer;
			$return_array[$i]['EmailPermission']=$results1[0]->EmailPermission;
			$return_array[$i]['IsPurchaseOrder']=$results1[0]->IsPurchaseOrder;
			$return_array[$i]['IsCreditFlag']=$results1[0]->IsCreditFlag;
			$return_array[$i]['ARBalance']=$results1[0]->ARBalance;
		}
		
	}
	$data = array_values($return_array);
	//print_r($data);
	ob_end_clean();
	$filename = "customer999999.csv"; 
	$delimiter = ","; 
	 
	// Create a file pointer 
	$f = fopen('php://memory', 'w'); 
	$fields = array('Id','T1Customer','Description','ContactName','ContactDetails','AccType','CreditLimit','Email','CustomerOldEmail','ContactTitle','ContactInitials','ContactPosn','Phone','ChartName','PayName','Address1','Address2','Address3','City','State','BadCreditFlag','IsCashCustomer','SecondaryEmailAddress','IsPrefferedCustomer','EmailPermission','IsPurchaseOrder','IsCreditFlag','ARBalance');
	fputcsv($f, $fields, $delimiter); 
	for($i=0;$i<count($data);$i++)
	{
		//print_r($data);
		 $lineData = array($data[$i]['MId'],$data[$i]['T1Customer'],$data[$i]['Description'],$data[$i]['ContactName'],$data[$i]['ContactDetails'],$data[$i]['AccType'],$data[$i]['CreditLimit'],$data[$i]['Email'],$data[$i]['CustomerOldEmail'],$data[$i]['ContactTitle'],$data[$i]['ContactInitials'],$data[$i]['ContactPosn'],$data[$i]['Phone'],$data[$i]['ChartName'],$data[$i]['PayName'],$data[$i]['Address1'],$data[$i]['Address2'],$data[$i]['Address3'],$data[$i]['City'],$data[$i]['State'],$data[$i]['BadCreditFlag'],$data[$i]['IsCashCustomer'],$data[$i]['SecondaryEmailAddress'],$data[$i]['IsPrefferedCustomer'],$data[$i]['EmailPermission'],$data[$i]['IsPurchaseOrder'],$data[$i]['IsCreditFlag'],$data[$i]['ARBalance']); 
		 fputcsv($f, $lineData, $delimiter);   
	}
	fseek($f, 0); 
 
// Set headers to download file rather than displayed 
	header('Content-Type: text/csv'); 
	header('Content-Disposition: attachment; filename="' . $filename . '";'); 
	 
	// Output all remaining data on a file pointer 
	fpassthru($f); 
	
	die;
	
}
function moco_logout(WP_REST_Request $request) {
	
	global $wpdb;
	$params = $request->get_params();

	if(!isset($params['device_id']) || empty($params['device_id']))
	{
		return [
			'success' => false,
			'error' => "device_id is required"
		];
	}

	$device_id = $params['device_id'];
	$table = $wpdb->prefix.'user_devices';
	$wpdb->delete( $table, array( 'device_id' => $device_id ) );


	return [
		'success' => true,
		'params' => $params
	];
}

function moco_check_phone(WP_REST_Request $request)
{
	if(!isset($_GET['phone_number']) || empty($_GET['phone_number']))
	{
		return [
			'success' => false,
			'error' => "phone_number is required",
			'post' => $_GET

		];
	}

	if(!isset($_GET['country_code']) || empty($_GET['country_code']))
	{
		return [
			'success' => false,
			'error' => "country_code is required",
			'post' => $_GET

		];
	}

	 global $wpdb;
        $phone_number = $_GET['phone_number'];
		$country_code = $_GET['country_code'];
         
        $query = "SELECT $wpdb->usermeta.user_id as user_id FROM $wpdb->usermeta   WHERE 
          ( $wpdb->usermeta.meta_key = 'billing_phone' AND $wpdb->usermeta.meta_value = '$phone_number' ) ";
		$result = $wpdb->get_results($query, ARRAY_A );
		if($result)
		{
			foreach($result as $res)
			{
				$check= get_user_meta($res['user_id'],'country_code', true);
				if($check==$country_code)
				{
					return [
						'success' => false,
						'error' => "Phone Number is not available!",
			'post' => $_GET
		
					];
				}
			}


			return [
					'success' => true,
					'details' => "Phone Number is available!",
			'post' => $_GET

				];
			
		}
		else
		{
			return [
				'success' => true,
				'details' => "Phone Number is available!",
			'post' => $_GET

			];
		}
}

function moco_change_password(WP_REST_Request $request) {
	
	global $wpdb;
	$user = mocoValidate();
	if(!$user)
	{
		return [
		 'success' => false,
		 'error'=> 'Unauthorized Access'
		];
	}

	$params = $request->get_params();

	
	if(!isset($params['old_password']))
	{
		 return [
			 'success' => false,
			 'error'=> 'old_password field is required!'
			];
	}

	if(!isset($params['new_password']))
	{
		 return [
			 'success' => false,
			 'error'=> 'new_password field is required!'
			];
	}

	$old_password = $params['old_password'];
	$new_password = $params['new_password'];

	if (wp_check_password( $old_password, $user->data->user_pass, $user->ID ) ) {
		wp_set_password( $new_password, $user->ID );
		return [
		 'success' => true,
		 'error'=> 'Password Changed!'
		];
	} else {
		return [
		 'success' => false,
		 'error'=> 'Invalid Old Password!'
		];
	}
}

function mocoValidate()
{
	$headers = getallheaders();
	if(isset($headers['mocoauthtoken']) && isset($headers['mocouserid']) && $headers['mocoauthtoken']!='' && $headers['mocouserid']!='')
	{
		return get_userdata($headers['mocouserid']);
	}

	return false;
}


function moco_countries()
{
	global $wpdb;
	$params = array();
	$results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."countries", ARRAY_A );
	$final_data = array();


	foreach($results as $key=>$country_data)
	{
		 $final_data[$key]['country'] = $country_data['country_name'];
		 $final_data[$key]['parishes'] = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."parishes where country='".addslashes($country_data['country_name'])."'" );
		


	}

	$response['countries'] = $final_data;
	return $response;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => site_url("wp-json/wc/v3/data/countries/"),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "mocoauthtoken: 2342",
    "mocouserid: 1",
    "Cookie: request_a_quote_wp_session=9468109f0805138c38286554694cf1c7%7C%7C1603457033%7C%7C1603456673"
  ),
));

$response['countries'] = json_decode(curl_exec($curl));

curl_close($curl);
return $response;

}

function moco_add_device(WP_REST_Request $request)
{

	global $wpdb;
	$user = mocoValidate();
	if(!$user)
	{
		return [
		 'success' => false,
		 'error'=> 'Unauthorized Access'
		];
	}

	$params = $request->get_params();
	if(!isset($params['device_id']))
	{
		 return [
			 'success' => false,
			 'error'=> 'device_id field is required!'
			];
	}

	$data['device_id'] = $params['device_id'];
	$data['user_id'] = $user->ID;

	//print_r($data);
	//die (print '');
	$wpdb->insert($wpdb->prefix.'user_devices', $data);
    return [
        'success' => true,
		'request'=> $request->get_params()
	];

 
}

add_filter('jwt_auth_token_before_dispatch', 'add_user_info_jwt', 10, 2);

function add_user_info_jwt($token, $user) {


    $token['userId'] = $user->ID;
	$token['role'] = $user->roles[0];

    return $token;
}

function mocoget_address_book()
{
	global $wpdb;
	$user = mocoValidate();
	if(!$user)
	{
		return [
		 'success' => false,
		 'error'=> 'Unauthorized Access'
		];
	}

	$address = new WC_Address_Book();

	$address_names = $address->get_address_names( $user->ID );

	$name = $address->set_new_address_name( $address_names );

	$addressses = $address->get_address_book($user->ID);
	$final_address = array();
	

	foreach($addressses as $key=>$add)
	{
		$add["name"] = $key;
		$new_address = array();
		foreach($add as $key_inner=>$temp)
		{
			$new_key = str_replace($key,'shipping',$key_inner);
			$new_address[$new_key] = $temp;
		}
		
		$new_address['shipping_country_code'] = get_user_meta($user->ID,$key.'_country_code', true);
		
		$final_address[] = $new_address;
	}
	
	return [
			'success' => true,
			'data' => $final_address

		];
	
}

function mocoadd_address_book(WP_REST_Request $request)
{
	
	global $wpdb;
	$user = mocoValidate();
	if(!$user)
	{
		return [
		 'success' => false,
		 'error'=> 'Unauthorized Access'
		];
	}

	wp_set_current_user($user->ID);
	
	$params = $request->get_params();

	$shipping_data = $params['shipping'];
	
	$address = new WC_Address_Book();

	//echo get_current_user_id();die (print '');
	//print_r($_POST);
	//echo $shipping_data['shipping_address_nickname'];

	

	/*if(!$address->validate_address_nickname($shipping_data['shipping_address_nickname']))
	{
		return [
		 'success' => false,
		 'error'=> 'Address nick name already exists!'
		];
	}
	*/
	$address_names = $address->get_address_names( $user->ID );

	$name = $address->set_new_address_name( $address_names );

	

	foreach ( $shipping_data as $key => $value ) {
			$key = str_replace( 'shipping_', $name . '_', $key );
			//echo $key;
			//echo '--';
			////echo $value;
			add_user_meta( $user->ID, $key, $value );
	}
	$address_names[] = $name;

	update_user_meta( $user->ID, 'wc_address_book', $address_names );

	
	
	return [
			'success' => true,
			'data' => $shipping_data

		];
	
}

function mocoedit_address_book(WP_REST_Request $request)
{
	global $wpdb;
	$user = mocoValidate();
	if(!$user)
	{
		return [
		 'success' => false,
		 'error'=> 'Unauthorized Access'
		];
	}

	wp_set_current_user($user->ID);
	
	$params = $request->get_params();

	$shipping_data = $params['shipping'];
	$name = $shipping_data['name'];

	unset($shipping_data['name']);
	
	$address = new WC_Address_Book();

	//echo get_current_user_id();die (print '');
	//print_r($_POST);
	//echo $shipping_data['shipping_address_nickname'];

	

	/*if(!$address->validate_address_nickname($shipping_data['shipping_address_nickname']))
	{
		return [
		 'success' => false,
		 'error'=> 'Address nick name already exists!'
		];
	}
	*/
	
	

	foreach ( $shipping_data as $key => $value ) {
			$key = str_replace( 'shipping_', $name . '_', $key );
			//echo $key;
			//echo '--';
			//echo $value;
			update_user_meta( $user->ID, $key, $value );
	}
	

	
	
	return [
			'success' => true,
			'data' => $shipping_data

		];
	
}

function mocodelete_address_book(WP_REST_Request $request)
{
	
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$user = mocoValidate();
	if(!$user)
	{
		return [
		 'success' => false,
		 'error'=> 'Unauthorized Access'
		];
	}

	wp_set_current_user($user->ID);
	
	$params = $request->get_params();

	
	$name = $params['name'];

	$_POST['name'] = $name;
	
	$address = new WC_Address_Book();
	//die (print 'rqached');
	$address->wc_address_book_delete_moco($name);	
	
	return [
			'success' => true

		];
	
}

function getMocoProductDetails(WP_REST_Request $request)
{
	global $wpdb;
	$params = $request->get_params();
	if(!isset($params['product_id']) || empty($params['product_id']))
	{
		return [
			'success' => false,
			'error' => "product_id is required"
		];
	}

	

	$product = wc_get_product($params['product_id']);
	$product_id = $params['product_id'];
	$redq_product_inventory = get_post_meta( $product_id, '_redq_product_inventory', true );

	if( !empty( $redq_product_inventory ) )
	{
		$resources = $product->redq_get_rental_payable_attributes('resource', $redq_product_inventory[0]);
		$attributes_cost = array();
		if($product_id == 1090)
		{
			foreach($resources  as $key=>$resource)
			{
				$size = substr($resource['resource_slug'], 0, 2);

				$attributes_cost["onSite"] = getResourceCost($product_id,$size,"OnSite");
				$attributes_cost["selfStorage"] =getResourceCost($product_id,$size,"selfStorage");
				$resources[$key]['resource_cost'] = $attributes_cost;
				$resources[$key]['resource_applicable'] = "Per Month";
				unset($resources[$key]['resource_hourly_cost']);
			}
		}
		else
		{
			
			foreach($resources  as $key=>$resource)
			{
				$size = substr($resource['resource_slug'], 0, 2);
				$resources[$key]['resource_cost'] = getResourceCost($product_id,$size);
				$resources[$key]['resource_applicable'] = "Per Month";
				unset($resources[$key]['resource_hourly_cost']);
			}
			
		}

		
	}
	if($product_id=="1146")
	{
				unset($resources[0]);
				unset($resources[2]);
				$resources = array_values($resources);
	}
	
	
	$return_array['name'] = $product->get_title();
	$return_array['description'] =get_post($product_id)->post_content;
	$return_array['images'][] = array("src"=>wp_get_attachment_url( $product->get_image_id() ));
	$return_array['attributes'] = $resources;
		
	
	return ['success' => true, 'data'=>$return_array];
}

function moco_check_availibility(WP_REST_Request $request)
{
	global $wpdb;
	$params = $request->get_params();

	if(!isset($params['product_id']) || empty($params['product_id']))
	{
		return [
			'success' => false,
			'error' => "product_id is required"
		];
	}

	if(!isset($params['pickup_date']) || empty($params['pickup_date']))
	{
		return [
			'success' => false,
			'error' => "pickup_date is required"
		];
	}

	if(!isset($params['dropoff_date']) || empty($params['dropoff_date']))
	{
		return [
			'success' => false,
			'error' => "dropoff_date is required"
		];
	}

	if(!isset($params['size']) || empty($params['size']))
	{
		return [
			'success' => false,
			'error' => "size is required"
		];
	}

	$pickUpDate = $params['pickup_date'];
	$dropOffDate = $params['dropoff_date'];
	$size = $params['size'];
	$product_id = $params['product_id'];		
	

	$availalble_resources = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Equipments/GetAvailableEquipmentbyDateRange?EquipmentCategory=3&StartDate=$pickUpDate&EndDate=$dropOffDate");
	//return $availalble_resources;
	$quantity_input = 0;
	if(isset($availalble_resources->Result))
	{
		
		$eqp_data = $availalble_resources->Result;
		//return $eqp_data;
		//print_r($eqp_data);
		$eqp_details = processMocoAssignment($eqp_data,$product_id,$size); 
		
		//return($eqp_details);

		if(!$eqp_details)
		{
			$quantity_input = 0;
		}
		else
		{
			$quantity_input = 1;
		}
			
	}

	if(!$quantity_input)
	{
		return [
			'success' => false,
			'error' => "This product is not available in the selected date range!"
		];
	}

	return [
			'success' => true,
			'details' => "Product is available!",
		];

}


function moco_cart_test(WP_REST_Request $request)
{
	$params = $request->get_params();

	return [
			'success' => true,
			'post' => $_POST,
			'get' => $_GET,
			'request' => $_REQUEST
		];
}

function moco_place_order(WP_REST_Request $request)
{
	$params = $request->get_params();
	

	
	$errors = array();

	if(!isset($params['amount']) || empty($params['amount']))
	{
		$errors[]= "amount is required";
	}

	if(!isset($params['card_num']) || empty($params['card_num']))
	{
		$errors[]= "card_num is required";
	}

	if(!isset($params['exp_date']) || empty($params['exp_date']))
	{
		$errors[]= "exp_date is required";
	}

	if(!isset($params['card_code']) || empty($params['card_code']))
	{
		$errors[]= "card_code is required";
	}

	if(!isset($params['first_name']) || empty($params['first_name']))
	{
		$errors[]= "first_name is required";
	}

	if(!isset($params['last_name']) || empty($params['last_name']))
	{
		$errors[]= "last_name is required";
	}

	if(!isset($params['address']) || empty($params['address']))
	{
		$errors[]= "address is required";
	}

	if(!isset($params['city']) || empty($params['city']))
	{
		$errors[]= "city is required";
	}

	if(!isset($params['state']) || empty($params['state']))
	{
		$errors[]= "state is required";
	}

	if(!isset($params['country']) || empty($params['country']))
	{
		$errors[]= "country is required";
	}

	if(!isset($params['zip']) || empty($params['zip']))
	{
		$errors[]= "zip is required";
	}

	if(!isset($params['email']) || empty($params['email']))
	{
		$errors[]= "email is required";
	}

	if(!isset($params['phone']) || empty($params['phone']))
	{
		$errors[]= "phone is required";
	}

	if(!isset($params['cart_key']) || empty($params['cart_key']))
	{
		$errors[]= "cart_key is required";
	}

	if(!isset($params['user_id']) || empty($params['user_id']))
	{
		$errors[]= "user_id is required";
	}


	if(!isset($params['order_id']))
	{
		$errors[]= "order_id is required";
	}

	if(count($errors))
	{
		return [
			'success' => false,
			'order_id' => 0,
			'error' => implode(", ",$errors),
		];
	}

	$_REQUEST['cocart-load-cart'] = $params['cart_key'];



	// If we did not request to load a cart then just return.
		if ( ! isset( $_REQUEST['cocart-load-cart'] ) ) {
			return;
		}

		$cart_key        = trim( wp_unslash( $_REQUEST['cocart-load-cart'] ) );
		$override_cart   = true;  // Override the cart by default.
		$notify_customer = false; // Don't notify the customer by default.
		$redirect        = false; // Don't safely redirect the customer to the cart after loading by default.

		wc_nocache_headers();

		include_once COCART_ABSPATH . 'includes/class-cocart-session.php';

		// Get the cart in the database.
		$handler     = new CoCart_Session_Handler();
		$stored_cart = $handler->get_cart( $cart_key );

		if ( empty( $stored_cart ) ) {
			CoCart_Logger::log( sprintf( __( 'Unable to find cart for: %s', 'cart-rest-api-for-woocommerce' ), $cart_key ), 'info' );

			if ( $notify_customer ) {
				wc_add_notice( __( 'Sorry but this cart has expired!', 'cart-rest-api-for-woocommerce' ), 'error' );
			}

			return;
		}

		// Get the cart currently in session if any.
		$cart_in_session = WC()->session->get( 'cart', null );

		$new_cart = array();

		$new_cart['cart']                       = maybe_unserialize( $stored_cart['cart'] );
		$new_cart['applied_coupons']            = maybe_unserialize( $stored_cart['applied_coupons'] );
		$new_cart['coupon_discount_totals']     = maybe_unserialize( $stored_cart['coupon_discount_totals'] );
		$new_cart['coupon_discount_tax_totals'] = maybe_unserialize( $stored_cart['coupon_discount_tax_totals'] );
		$new_cart['removed_cart_contents']      = maybe_unserialize( $stored_cart['removed_cart_contents'] );

		// Check if we are overriding the cart currently in session via the web.
		if ( $override_cart ) {
			// Only clear the cart if it's not already empty.
			if ( ! WC()->cart->is_empty() ) {
				WC()->cart->empty_cart( false );

				do_action( 'cocart_load_cart_override', $new_cart, $stored_cart );
			}
		} else {
			$new_cart_content                       = array_merge( $new_cart['cart'], $cart_in_session );
			$new_cart['cart']                       = apply_filters( 'cocart_merge_cart_content', $new_cart_content, $new_cart['cart'], $cart_in_session );

			$new_cart['applied_coupons']            = array_merge( $new_cart['applied_coupons'], WC()->cart->get_applied_coupons() );
			$new_cart['coupon_discount_totals']     = array_merge( $new_cart['coupon_discount_totals'], WC()->cart->get_coupon_discount_totals() );
			$new_cart['coupon_discount_tax_totals'] = array_merge( $new_cart['coupon_discount_tax_totals'], WC()->cart->get_coupon_discount_tax_totals() );
			$new_cart['removed_cart_contents']      = array_merge( $new_cart['removed_cart_contents'], WC()->cart->get_removed_cart_contents() );

			do_action( 'cocart_load_cart', $new_cart, $stored_cart, $cart_in_session );
		}


		// Sets the php session data for the loaded cart.
		WC()->session->set( 'cart', $new_cart['cart'] );
		
		WC()->session->set( 'applied_coupons', $new_cart['applied_coupons'] );
		WC()->session->set( 'coupon_discount_totals', $new_cart['coupon_discount_totals'] );
		WC()->session->set( 'coupon_discount_tax_totals', $new_cart['coupon_discount_tax_totals'] );
		WC()->session->set( 'removed_cart_contents', $new_cart['removed_cart_contents'] );


$cart = WC()->session->get( 'cart' );

//$checkout = WC()->checkout();
$order_id = wc_create_order(array('customer_id' => $params['user_id']));
$order = wc_get_order($order_id);


foreach($cart as $item => $values) {
	
    $product_id = $values['product_id'];
    $product = wc_get_product($product_id);
    $var_id = $values['variation_id'];
    //$var_slug = $values['variation']['attribute_pa_weight'];
    $quantity = (int)$values['quantity'];
    $variationsArray = array();
   // $variationsArray['legacy_values']['rental_data'] =$values['rental_data'];
	//$variationsArray['legacy_values']['product_id'] =$values['product_id'];
   // $var_product = new WC_Product_Variation($var_id);
$item_id=$order->add_product($product, $quantity, $variationsArray);
//$item_id=$product_id;
               $rental_data = $values['rental_data'];
            

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

    //$order->add_product($product, $quantity, $variationsArray);
  }

$key_crt=0;
$keys = array_keys($cart);
foreach( $order->get_items() as $item_id => $item ){

	
$key = $keys[$key_crt];
//echo($cart[$key]['line_total']); die (print '');
//print_r($cart[$key]);die (print '');
 $product_quantity = (int) $item->get_quantity(); // product Quantity
    $new_product_price =$cart[$key]['line_total']/ $product_quantity; 



   
    
    // The new line item price
    $new_line_item_price = $new_product_price * $product_quantity;
    
    // Set the new price
    $item->set_subtotal( $new_line_item_price ); 
    $item->set_total( $new_line_item_price );

    // Make new taxes calculations
    $item->calculate_taxes();

    $item->save(); // Save line item data
	$key_crt++;
}

//update_post_meta($order_id, '_customer_user', get_current_user_id());
$order->calculate_totals();
$order->payment_complete(); 
//$cart->empty_cart();

		

  

    $billing_address    =   array(
        'first_name' => $fname=$params['first_name'],
        'last_name'  => $lname=$params['last_name'],
        'email'      => $email=$params['email'],
        'address_1'  => $address_1=$params['address'],
        'address_2'  => $address_2=$params['address'],
        'city'       => $city=$params['city'],
        'state'      => $state=$params['state'],
        'postcode'   => $postcode=$params['zip'],
        'country'    => $country=$params['country'],
    );
    $address = array(
        'first_name' => $fname,
        'last_name'  => $lname,
        'email'      => $email,
        'address_1'  => $address_1,
        'address_2'  => $address_2,
        'city'       => $city,
        'state'      => $state,
        'postcode'   => $postcode,
        'country'    => $country,
    );

   

    $order->set_address($billing_address,'billing');

    $order->set_address($address,'shipping');

    $order->set_payment_method('plugnpay');
	
	$order->update_status('pending');
   // $order->shipping_method_title = $shipping_method;

    $order->calculate_totals();

    //$order->update_status('completed');

    $order->save();

	
	$params['order_id'] = $order->get_id();
	$resp=moco_process_payment($params);
	if($resp==1)
	{
		$order->update_status('completed');
		$order->save();
		return [
			'success' => true,
			'order_id' => $order->get_id()
		];
	}
	
	return [
			'success' => false,
			'payment_error'=> $resp,
			'order_id' => $order->get_id()
		];
}

function request_quote(WP_REST_Request $request)
{
	$params = $request->get_params();
	

	
	$errors = array();

	$message = "Hello Admin,<br /><br />";
	$message = $message."Someone has requested a quote on moco app. Following are the details.<br />";

	if(!isset($params['storage_location']) || empty($params['storage_location']))
	{
		$errors[]= "storage_location is required";
	}
	else
	{
		$message = $message."Storage Location: ".$params['storage_location'].'<br />';
	}

	if(!isset($params['storage_category']) || empty($params['storage_category']))
	{
		$errors[]= "storage_category is required";
	}
	else
	{
		$message = $message."Storage Category: ".$params['storage_category'].'<br />';
	}

	if(!isset($params['shared_storage']) || empty($params['shared_storage']))
	{
		$errors[]= "shared_storage is required";
	}else
	{
		$message = $message."Storage Type: ".$params['shared_storage'].'<br />';
	}

	if(!isset($params['storage_size']) || empty($params['storage_size']))
	{
		$errors[]= "storage_size is required";
	}else
	{
		$message = $message."Storage Size: ".$params['storage_size'].'<br />';
	}

	if(!isset($params['name']) || empty($params['name']))
	{
		$errors[]= "name is required";
	}else
	{
		$message = $message."Name: ".$params['name'].'<br />';
	}

	if(!isset($params['phone']) || empty($params['phone']))
	{
		$errors[]= "phone is required";
	}else
	{
		$message = $message."Phone: ".$params['storage_location'].'<br />';
	}


	if(!isset($params['email']) || empty($params['email']))
	{
		$errors[]= "email is required";
	}else
	{
		$message = $message."Email: ".$params['email'].'<br />';
	}

	if(count($errors))
	{
		return [
			'success' => false,
			'error' => implode(", ",$errors),
		];
	}

	$message = $message."<br />Best Regards<br />Site Admin";
//wp_mail("aliasgar.arif@gmail.com","Moco: Request a quote submission",$message);
	wp_mail(get_option( 'admin_email' ),"Moco: Request a quote submission",$message);
	return [
			'success' => true,
			'data' => $params
		];


}

function wpse27856_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );

function moco_arb_balance(WP_REST_Request $request)
{

	global $wpdb;
	$user = mocoValidate();
	if(!$user)
	{
		return [
		 'success' => false,
		 'error'=> 'Unauthorized Access'
		];
	}

	$post_id = "user_".$user->ID; // user ID = 2

	return [
		 'success' => true,
		 'balance_amount'=> get_field( 'ARBalance', $post_id)
		];

	

}


function moco_contact_info(WP_REST_Request $request)
{

	

	return [
		 'success' => true,
		 'data'=> array("address"=>get_option('my_address'),"phone"=>get_option('my_phone'),"email"=>get_option('my_email'))
		];

	

}

function moco_place_order_arb(WP_REST_Request $request)
{
	$params = $request->get_params();
	

	
	$errors = array();

	if(!isset($params['amount']) || empty($params['amount']))
	{
		$errors[]= "amount is required";
	}

	if(!isset($params['card_num']) || empty($params['card_num']))
	{
		$errors[]= "card_num is required";
	}

	if(!isset($params['exp_date']) || empty($params['exp_date']))
	{
		$errors[]= "exp_date is required";
	}

	if(!isset($params['card_code']) || empty($params['card_code']))
	{
		$errors[]= "card_code is required";
	}

	if(!isset($params['first_name']) || empty($params['first_name']))
	{
		$errors[]= "first_name is required";
	}

	if(!isset($params['last_name']) || empty($params['last_name']))
	{
		$errors[]= "last_name is required";
	}

	if(!isset($params['address']) || empty($params['address']))
	{
		$errors[]= "address is required";
	}

	if(!isset($params['city']) || empty($params['city']))
	{
		$errors[]= "city is required";
	}

	if(!isset($params['state']) || empty($params['state']))
	{
		$errors[]= "state is required";
	}

	if(!isset($params['country']) || empty($params['country']))
	{
		$errors[]= "country is required";
	}

	if(!isset($params['zip']) || empty($params['zip']))
	{
		$errors[]= "zip is required";
	}

	if(!isset($params['email']) || empty($params['email']))
	{
		$errors[]= "email is required";
	}

	if(!isset($params['phone']) || empty($params['phone']))
	{
		$errors[]= "phone is required";
	}

	
	if(!isset($params['user_id']) || empty($params['user_id']))
	{
		$errors[]= "user_id is required";
	}


	if(!isset($params['order_id']))
	{
		$errors[]= "order_id is required";
	}

	if(count($errors))
	{
		return [
			'success' => false,
			'order_id' => 0,
			'error' => implode(", ",$errors),
		];
	}


	$order_id = wc_create_order(array('customer_id' => $params['user_id']));
	$order = wc_get_order($order_id);

	$product_id = 1332;
    $product = wc_get_product($product_id);
    
    
    $quantity = 1;
    $variationsArray = array();
   
	$item_id=$order->add_product($product, $quantity, $variationsArray);


	$key_crt=0;
	//$keys = array_keys($cart);
	foreach( $order->get_items() as $item_id => $item )
	{

		//$key = $keys[$key_crt];

		$product_quantity = (int) $item->get_quantity(); // product Quantity
		$new_product_price = $params['amount']; 

		// The new line item price
		$new_line_item_price = $new_product_price * $product_quantity;

		// Set the new price
		$item->set_subtotal( $new_line_item_price ); 
		$item->set_total( $new_line_item_price );

		// Make new taxes calculations
		$item->calculate_taxes();

		$item->save(); // Save line item data
		//$key_crt++;
	}

	
	$order->calculate_totals();
	$order->payment_complete(); 
    $billing_address    =   array(
        'first_name' => $fname=$params['first_name'],
        'last_name'  => $lname=$params['last_name'],
        'email'      => $email=$params['email'],
        'address_1'  => $address_1=$params['address'],
        'address_2'  => $address_2=$params['address'],
        'city'       => $city=$params['city'],
        'state'      => $state=$params['state'],
        'postcode'   => $postcode=$params['zip'],
        'country'    => $country=$params['country'],
    );
    $address = array(
        'first_name' => $fname,
        'last_name'  => $lname,
        'email'      => $email,
        'address_1'  => $address_1,
        'address_2'  => $address_2,
        'city'       => $city,
        'state'      => $state,
        'postcode'   => $postcode,
        'country'    => $country,
    );

   

    $order->set_address($billing_address,'billing');

    $order->set_address($address,'shipping');

    $order->set_payment_method('plugnpay');

    $order->update_status('pending');

    $order->calculate_totals();

    

    $order->save();

	$params['order_id'] = $order->get_id();
	$resp=moco_process_payment($params);
	if($resp==1)
	{
		$order->update_status('completed');
		$order->save();
		return [
			'success' => true,
			'order_id' => $order->get_id()
		];
	}
	
	return [
			'success' => false,
			'payment_error'=> $resp,
			'order_id' => $order->get_id()
		];
	
	


}

function moco_process_payment($params)
{
	
	//return true;
	$curl = curl_init();

	//$fields = "x_version=3.1&x_delim_char=%7C&x_delim_data=TRUE&x_relay_response=FALSE&x_encap_char=&x_method=CC&x_amount=$params[amount]&x_card_num=$params[card_num]&x_exp_date=$params[exp_date]&x_card_code=$params[card_code]&x_first_name=$params[first_name]&x_last_name=$params[last_name]&x_address=$params[address]&x_city=$params[city]&x_state=$params[state]&x_country=$params[country]&x_zip=$params[zip]&x_email=$params[email]&x_phone=$params[phone]&x_company=&x_invoice_num=$params[order_id]&x_description=Moco+-+Order+$params[order_id]&x_currency_code=BBD&x_customer_ip=$params[card_code]&x_type=AUTH_CAPTURE&x_login=demowebsty&x_tran_key=Justchill595@@";

	$fields = "x_version=3.1&x_delim_char=%7C&x_delim_data=TRUE&x_relay_response=FALSE&x_encap_char=&x_method=CC&x_amount=$params[amount]&x_card_num=$params[card_num]&x_exp_date=$params[exp_date]&x_card_code=$params[card_code]&x_first_name=$params[first_name]&x_last_name=$params[last_name]&x_address=$params[address]&x_city=$params[city]&x_state=$params[state]&x_country=$params[country]&x_zip=$params[zip]&x_email=$params[email]&x_phone=$params[phone]&x_company=&x_invoice_num=$params[order_id]&x_description=Moco+-+Order+$params[order_id]&x_currency_code=BBD&x_customer_ip=$params[card_code]&x_type=AUTH_CAPTURE&x_login=mococontai&x_tran_key=Justmoco595@@";

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://pay1.plugnpay.com/payment/pnpremote.cgi',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $fields,
	  CURLOPT_HTTPHEADER => array(
		'Content-Type: text/plain',
		'Cookie: TS01657446=01f90ae433090f4f0aa9ab08cf23dadc716d42cafc41e5659adbc19634c5be9183601e4b3f3e0a160a8b908ef715635a2cb984cbed'
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	$final_resp = explode("|",$response);
	//echo $final_resp[0];
	if($final_resp[0]==1)
	{
		return $final_resp[0];
	}
	else
	{
		return $final_resp[3];
	}
	

}
function moco_arb_balance_website(WP_REST_Request $request)
{

	$moco_user_data = moco_fetch_data_curl("http://api.craneops.net/api/Operator/Customer/GetAllJobCustomer");

	//ini_set("display_errors",1);
	//serror_reporting(1);
	
	//print_r($moco_user_data);
	$moco_user_data_result = $moco_user_data->Result;
	//print_r($moco_user_data_result);

		$userdata = array();
		foreach($moco_user_data_result as $key => $user_data)
		{
			//print_r($user_data);

			$userdata[$key]['user_email'] =  $user_data->Email;
			$userdata[$key]['user_ARBalance'] =  $user_data->ARBalance ; 
			$userdata[$key]['t1_customer'] =  $user_data->T1Customers ; 

			
		}
	
	//print_r($userdata);
	//die;
	//$user->ID = 6324;

	//$post_id = "user_".$user->ID; // user ID = 2

	return $userdata;

	

}
<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';
global $wpdb;
//print_r($_POST);
$query = "SELECT * FROM crn_moco_customers where email='".$_POST['email']."'";
$result = $wpdb->get_results( $query );
  
if(empty($result))
{
  $json = 0;
}
else
{
    $data['Id'] = $result[0]->MId; 
    $data['Email'] = $result[0]->Email; 
    $data['Company'] = $result[0]->ContactName;

    
}
echo '<a href="https://moco.bb/registration?company='.$data['Company'].'&email='.$data['Email'].'&username='.$data['Id'].'">Click here to continue your web registration</a>';

?>

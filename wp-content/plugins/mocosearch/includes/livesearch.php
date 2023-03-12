<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';
global $wpdb;
//print_r($_POST);
$query = "SELECT * FROM crn_moco_customers where T1Customer like '".$_POST['query']."%' OR MId like '".$_POST['query']."%' OR Email like '".$_POST['query']."%'";
$result = $wpdb->get_results( $query );

					// Loop through each WC_Order object
					foreach( $result as $order )
					{
						
					    ?>
					    <div class="row">
						    <div class="col-12 col-sm-4 col-lg-2"><?php echo $order->MId; ?></div>
						    <div class="col-12 col-sm-4 col-lg-2"><?php echo $order->T1Customer; ?></div>
						    <div class="col-12 col-sm-4 col-lg-2"><?php echo $order->ContactName; ?></div>
						    <div class="col-12 col-sm-4 col-lg-2"><?php echo $order->Email; ?></div>
						    <div class="col-12 col-sm-4 col-lg-2 rc_rslt_action"><a href="<?php echo admin_url(); ?>admin.php?page=customers&id=<?php echo $order->id; ?>">View / Edit</a></div>
						</div>
					    <?php
					}
				    
?>
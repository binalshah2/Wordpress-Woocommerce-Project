<?php
	global $product;
	
	$product_id = get_the_ID();
	$redq_product_inventory = get_post_meta( $product_id, '_redq_product_inventory', true );
	//print_r($redq_product_inventory);
	if( !empty( $redq_product_inventory ) )
		$pick_up_locations = $product->redq_get_rental_payable_attributes('pickup_location', $redq_product_inventory[0]);
		//echo "<pre>";
		//print_r($pick_up_locations);
 ?>
 <?php
 //print_r($_POST);
 $pickup_location_sel = $_POST['pickup_location']; 
 ?>
<?php if(isset($pick_up_locations) && !empty($pick_up_locations)): ?>
	<div class="redq-pick-up-location rnb-select-wrapper rnb-component-wrapper rc_location">
		<?php
      $labels = reddq_rental_get_settings( $product_id, 'labels', array('pickup_location') );
      $labels = $labels['labels'];
	  ?>
		<h5><?php //echo esc_attr($labels['pickup_location']); ?></h5>
		<h5>Storage Locations</h5>
		<select class="redq-select-boxes pickup_location rnb-select-box" name="pickup_location" data-placeholder="<?php echo esc_attr($labels['pickup_loc_placeholder']); ?>" onchange="setpvalue(this.value);">
			<!-- <option value=""><?php echo esc_attr($labels['pickup_loc_placeholder']); ?></option> -->
			<?php foreach ($pick_up_locations as $key => $value) { ?>
				<?php
					$loc = $value['address'].'|'.$value['title'].'|'.$value['cost'];
					if($loc==$pickup_location_sel)
					{
						$sel = "selected=selected";
					}
					else
					{
						$sel = "";
					}
				?>
				<option value="<?php echo esc_attr($value['address']); ?>|<?php echo esc_attr($value['title']); ?>|<?php echo esc_attr($value['cost']); ?>" data-pickup-location-cost="<?php echo esc_attr($value['cost']); ?>" <?php echo $sel;?>><?php echo esc_attr($value['title']); ?></option>
			<?php } ?>
		</select>
	</div>
<?php endif; ?>

<?php
	global $product;
	$product_id = get_the_ID();
	$redq_product_inventory = get_post_meta( $product_id, '_redq_product_inventory', true );

	if( !empty( $redq_product_inventory ) )
		$resources = $product->redq_get_rental_payable_attributes('resource', $redq_product_inventory[0]);
?>
<?php
//print_r($_POST);
?>
<?php if(isset($resources) && !empty($resources)): ?>

	<div class="payable-extras rnb-component-wrapper">
	<?php
			$labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('resources') );
			$labels = $labels['labels'];
		?>
		<h4><?php echo esc_attr($labels['resource']); ?></h4>
		<!--<select name="extras[]">-->
		
		<?php 
		$styleMoco = "";
		$classMoco = "";
		$oneSelected = true;
		foreach ($resources as $key => $value) { 
				
				$selected = "";
				if($styleMoco!="" && $oneSelected)
				{
					$selected = " checked=checked ";
					$oneSelected = false;
				}
				
				$styleMoco = "";
				$classMoco = "";
				if($product_id==1090)
				{
					
					$classMoco = " OnSite ";
					
				}

				if($product_id==1090 && substr($value['resource_name'], 0, 2)==10)
				{
					$styleMoco = " style='display:none !important;' ";
					$classMoco = " OnSite On_Site ";
					
				}
			$value['resource_cost'] = getResourceCost($product_id,substr($value['resource_name'], 0, 2),"OnSite");

			$valueSelf['resource_cost'] = getResourceCost($product_id,substr($value['resource_name'], 0, 2),"SelfStorage");
		?>	
			<?php
				if($value['resource_cost']=="1.00" || $value['resource_cost']=="" || $value['resource_cost']=="0.00")
				{
			?>
			<?php
				}
				else
				{
			?>
			<div class="attributes">
				<label style="width: 100%;" class="custom-block1 <?php echo $classMoco; ?>" <?php echo $styleMoco; ?>>
					<?php $dta = array(); $dta['name'] = $value['resource_name']; $dta['cost'] = $value['resource_cost'];  ?>
					
					
					<input style="width: 3% !important;height: 25px;" type="radio" name="extras[]" <?php echo $selected; ?> value = "<?php echo esc_attr($value['resource_name']); ?>|<?php echo esc_attr($value['resource_cost']); ?>|<?php echo esc_attr($value['resource_applicable']); ?>|<?php echo esc_attr($value['resource_hourly_cost']); ?>" data-name="<?php echo esc_attr($value['resource_name']); ?>" data-value-in="0" data-applicable="<?php echo esc_attr($value['resource_applicable']); ?>" data-value="<?php echo esc_attr($value['resource_cost']); ?>" data-value-onSite="<?php echo esc_attr($value['resource_cost']); ?>" data-value-selfStorage="<?php echo esc_attr($valueSelf['resource_cost']); ?>" data-hourly-rate="<?php echo esc_attr($value['resource_hourly_cost']); ?>" data-currency-before="$" data-currency-after="" class="carrental_extras">
					<?php echo esc_attr($value['resource_name']); ?>

					<?php if($value['resource_applicable'] == 'per_day'){ ?>

						<?php echo wc_price($value['resource_cost']); ?><span><?php _e(' - Per Month', 'redq-rental'); ?>
						<?php //echo wc_price($value['resource_hourly_cost']); ?><?php //_e(' - Per Hour','redq-rental'); ?>
					<?php }else{ ?>
						<?php echo wc_price($value['resource_cost']); ?><?php _e(' - One Time','redq-rental'); ?>
					<?php } ?>
				</label>

					<?php
						if($product_id==1090)
						{
						
						$value['resource_cost'] = getResourceCost($product_id,substr($value['resource_name'], 0, 2),"OffSite");

						$classMoco = " OffSite ";
						$styleMocoNew = " style='display:none;width: 100%;' ";
					?>
						<label class="custom-block1 <?php echo $classMoco; ?>" <?php echo $styleMocoNew; ?>>	
					<input style="width: 10% !important;height: 25px;" type="radio" name="extras[]"  value = "<?php echo esc_attr($value['resource_name']); ?>|<?php echo esc_attr($value['resource_cost']); ?>|<?php echo esc_attr($value['resource_applicable']); ?>|<?php echo esc_attr($value['resource_hourly_cost']); ?>" data-name="<?php echo esc_attr($value['resource_name']); ?>" data-value-in="0" data-applicable="<?php echo esc_attr($value['resource_applicable']); ?>" data-value="<?php echo esc_attr($value['resource_cost']); ?>" data-value-onSite="<?php echo esc_attr($value['resource_cost']); ?>" data-value-selfStorage="<?php echo esc_attr($valueSelf['resource_cost']); ?>" data-hourly-rate="<?php echo esc_attr($value['resource_hourly_cost']); ?>" data-currency-before="$" data-currency-after="" class="carrental_extras">
					<?php echo esc_attr($value['resource_name']); ?>

					<?php if($value['resource_applicable'] == 'per_day'){ ?>
						<?php echo wc_price($value['resource_cost']); ?><span><?php _e(' - Per Month', 'redq-rental'); ?>
						<?php //echo wc_price($value['resource_hourly_cost']); ?><?php //_e(' - Per Hour','redq-rental'); ?>
					<?php }else{ ?>
						<?php echo wc_price($value['resource_cost']); ?><?php _e(' - One Time','redq-rental'); ?>
					<?php } ?>
						</label>
					<?php
						}
					?>
				
			</div>
			<?php	
				}
			?>
			
		<?php } ?>
		<!--</select>-->
	</div>
<?php else : ?>
	<div class="payable-extras rnb-component-wrapper" style="display: none">
		<?php
			$labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('resources') );
			$labels = $labels['labels'];
		?>
		<h5><?php echo esc_attr($labels['resource']); ?></h5>
	</div>
<?php endif; ?>
<div class="rc_vat">All Prices Include VAT.</div>

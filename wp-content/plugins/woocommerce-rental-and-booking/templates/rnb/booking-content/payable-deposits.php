<?php
	global $product;
	$product_id = get_the_ID();
	$redq_product_inventory = get_post_meta( $product_id, '_redq_product_inventory', true );
	
	if( !empty( $redq_product_inventory ) )
		$security_deposites = $product->redq_get_rental_payable_attributes('deposite', $redq_product_inventory[0]);
?>
<?php if(isset($security_deposites) && !empty($security_deposites)): ?>
<div class="payable-security_deposites rnb-component-wrapper">
	<?php
		$labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('deposites') );
		$labels = $labels['labels'];
	?>
	<h5><?php echo esc_attr($labels['deposite']); ?></h5>
	<?php foreach ($security_deposites as $key => $value) { ?>
		<div class="attributes">
			<label class="custom-block">
				<?php $dta = array(); $dta['name'] = $value['security_deposite_name']; $dta['cost'] = $value['security_deposite_cost'];  ?>
				<input type="checkbox" <?php if($value['security_deposite_clickable'] === 'no'){ ?> checked onclick="return false" <?php } ?> name="security_deposites[]" value = "<?php echo esc_attr($value['security_deposite_name']); ?>|<?php echo esc_attr($value['security_deposite_cost']); ?>|<?php echo esc_attr($value['security_deposite_applicable']); ?>|<?php echo esc_attr($value['security_deposite_hourly_cost']); ?>" data-name="<?php echo esc_attr($value['security_deposite_name']); ?>" data-value-in="0" data-applicable="<?php echo esc_attr($value['security_deposite_applicable']); ?>" data-value="<?php echo esc_attr($value['security_deposite_cost']); ?>" data-hourly-rate="<?php echo esc_attr($value['security_deposite_hourly_cost']); ?>" data-currency-before="$" data-currency-after="" class="carrental_extras" />
				<?php echo esc_attr($value['security_deposite_name']); ?>
				<?php if($value['security_deposite_applicable'] == 'per_day'){ ?>
					<span class="pull-right show_if_day"><?php echo wc_price($value['security_deposite_cost']); ?><span> <?php _e(' - Per Day', 'redq-rental'); ?> </span></span>
					<span class="pull-right show_if_time" style="display: none;"><?php echo wc_price($value['security_deposite_hourly_cost']); ?><?php _e(' - Per Hour', 'redq-rental'); ?></span>
				<?php }else{ ?>
					<span class="pull-right"><?php echo wc_price($value['security_deposite_cost']); ?><?php _e(' - One Time', 'redq-rental'); ?></span>
				<?php } ?>
			</label>
		</div>
	<?php } ?>
</div>
<?php else : ?>
<div class="payable-security_deposites rnb-component-wrapper" style="display: none">
	<?php
		$labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('deposites') );
		$labels = $labels['labels'];
	?>
	<h5><?php echo esc_attr($labels['deposite']); ?></h5>
</div>
<?php endif; ?>
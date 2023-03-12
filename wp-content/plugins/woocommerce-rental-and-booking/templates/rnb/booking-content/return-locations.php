<?php
	global $product;
  $product_id = get_the_ID();
  $redq_product_inventory = get_post_meta( $product_id, '_redq_product_inventory', true );
	
  if( !empty( $redq_product_inventory ) )
    $drop_off_locations = $product->redq_get_rental_payable_attributes('dropoff_location', $redq_product_inventory[0]);
?>
<?php if(isset($drop_off_locations) && !empty($drop_off_locations)): ?>
<div class="redq-drop-off-location rnb-select-wrapper rnb-component-wrapper">
	<?php
    $labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('return_location') );
    $labels = $labels['labels'];
  ?>
	<h5><?php echo esc_attr($labels['return_location']); ?></h5>
	<select class="redq-select-boxes dropoff_location rnb-select-box" name="dropoff_location" data-placeholder="<?php echo esc_attr($labels['return_loc_placeholder']); ?>">
		<option value=""><?php echo esc_attr($labels['return_loc_placeholder']); ?></option>
		<?php foreach ($drop_off_locations as $key => $value) { ?>
			<option value="<?php echo esc_attr($value['address']); ?>|<?php echo esc_attr($value['title']); ?>|<?php echo esc_attr($value['cost']); ?>" data-dropoff-location-cost= "<?php echo esc_attr($value['cost']); ?>"><?php echo esc_attr($value['title']); ?></option>
		<?php } ?>
	</select>
</div>
<?php endif; ?>
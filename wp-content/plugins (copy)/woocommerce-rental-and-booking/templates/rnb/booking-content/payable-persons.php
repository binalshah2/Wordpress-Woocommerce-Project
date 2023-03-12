<?php
	global $product;
	$product_id = get_the_ID();
	$redq_product_inventory = get_post_meta( $product_id, '_redq_product_inventory', true );
	
	if( !empty( $redq_product_inventory ) )
		$person_info = $product->redq_get_rental_payable_attributes('person', $redq_product_inventory[0]);
    
       

	$adults = isset($person_info['adults']) ? $person_info['adults'] : '';
	$childs = isset($person_info['childs']) ? $person_info['childs'] : '';

	$labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('person') );
	$labels = $labels['labels'];
?>

<?php if(isset($adults) && !empty($adults)): ?>
	<div class="additional-person additional-person-adult rnb-select-wrapper rnb-component-wrapper">
		<h5><?php echo esc_attr($labels['adults']); ?></h5>
		<select class="additional_adults_info redq-select-boxes rnb-select-box" name="additional_adults_info" data-placeholder="<?php echo esc_attr($labels['adults_placeholder']); ?>">
			<option value=""><?php echo esc_attr($labels['adults_placeholder']); ?></option>
			<?php foreach ($adults as $key => $value) { ?>
				<?php if($value['person_cost_applicable'] == 'per_day'){ ?>
					<option class="show_adults_cost_if_day" value="<?php echo esc_attr($value['person_count']); ?>|<?php echo esc_attr($value['person_cost']); ?>|<?php echo esc_attr($value['person_cost_applicable']); ?>|<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_cost= "<?php echo esc_attr($value['person_cost']); ?>" data-person_hourly_cost= "<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_count="<?php echo esc_attr($value['person_count']); ?>" data-applicable="<?php echo esc_attr($value['person_cost_applicable']); ?>">
						<?php echo esc_attr($value['person_count']); ?><?php if(isset($value['person_cost']) && !empty($value['person_cost'])): ?><?php _e(' :  Cost - ','redq-rental'); ?><?php echo wc_price($value['person_cost']); ?><?php _e(' - Per day','redq-rental'); ?><?php endif; ?>
					</option>
					<option class="show_adults_cost_if_time" style="display: none;" value="<?php echo esc_attr($value['person_count']); ?>|<?php echo esc_attr($value['person_cost']); ?>|<?php echo esc_attr($value['person_cost_applicable']); ?>|<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_cost= "<?php echo esc_attr($value['person_cost']); ?>" data-person_hourly_cost= "<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_count="<?php echo esc_attr($value['person_count']); ?>" data-applicable="<?php echo esc_attr($value['person_cost_applicable']); ?>">
						<?php echo esc_attr($value['person_count']); ?><?php if(isset($value['person_cost']) && !empty($value['person_cost'])): ?><?php _e(' :  Cost - ','redq-rental'); ?><?php echo wc_price($value['person_hourly_cost']); ?><?php _e(' - Per hour','redq-rental'); ?><?php endif; ?>
					</option>
				<?php }else{ ?>
					<option value="<?php echo esc_attr($value['person_count']); ?>|<?php echo esc_attr($value['person_cost']); ?>|<?php echo esc_attr($value['person_cost_applicable']); ?>|<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_cost= "<?php echo esc_attr($value['person_cost']); ?>" data-person_count="<?php echo esc_attr($value['person_count']); ?>" data-applicable="<?php echo esc_attr($value['person_cost_applicable']); ?>">
						<?php echo esc_attr($value['person_count']); ?><?php if(isset($value['person_cost']) && !empty($value['person_cost'])): ?><?php _e(' :  Cost - ','redq-rental'); ?><?php echo wc_price($value['person_cost']); ?><?php _e(' - One time','redq-rental'); ?><?php endif; ?>
					</option>
				<?php } ?>
			<?php } ?>
		</select>
	</div>
	<?php else : ?>
		<div class="additional-person additional-person-adult rnb-select-wrapper rnb-component-wrapper" style="display: none">
			<h5><?php echo esc_attr($labels['adults']); ?></h5>
		</div>
<?php endif; ?>

<?php if(isset($childs) && !empty($childs)): ?>
	<div class="additional-person additional-person-child rnb-select-wrapper rnb-component-wrapper">
		<h5><?php echo esc_attr($labels['childs']); ?></h5>
		<select class="additional_childs_info redq-select-boxes rnb-select-box" name="additional_childs_info" data-placeholder="<?php echo esc_attr($labels['childs_placeholder']); ?>">
			<option value=""><?php echo esc_attr($labels['childs_placeholder']); ?></option>
			<?php foreach ($childs as $key => $value) { ?>
				<?php if($value['person_cost_applicable'] == 'per_day'){ ?>
					<option class="show_childs_cost_if_day" value="<?php echo esc_attr($value['person_count']); ?>|<?php echo esc_attr($value['person_cost']); ?>|<?php echo esc_attr($value['person_cost_applicable']); ?>|<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_cost= "<?php echo esc_attr($value['person_cost']); ?>" data-person_hourly_cost= "<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_count="<?php echo esc_attr($value['person_count']); ?>" data-applicable="<?php echo esc_attr($value['person_cost_applicable']); ?>">
						<?php echo esc_attr($value['person_count']); ?><?php if(isset($value['person_cost']) && !empty($value['person_cost'])): ?><?php _e(' :  Cost - ','redq-rental'); ?><?php echo wc_price($value['person_cost']); ?><?php _e(' - Per day','redq-rental'); ?><?php endif; ?>
					</option>
					<option class="show_childs_cost_if_time" style="display: none;" value="<?php echo esc_attr($value['person_count']); ?>|<?php echo esc_attr($value['person_cost']); ?>|<?php echo esc_attr($value['person_cost_applicable']); ?>|<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_cost= "<?php echo esc_attr($value['person_cost']); ?>" data-person_hourly_cost= "<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_count="<?php echo esc_attr($value['person_count']); ?>" data-applicable="<?php echo esc_attr($value['person_cost_applicable']); ?>">
						<?php echo esc_attr($value['person_count']); ?><?php if(isset($value['person_cost']) && !empty($value['person_cost'])): ?><?php _e(' :  Cost - ','redq-rental'); ?><?php echo wc_price($value['person_hourly_cost']); ?><?php _e(' - Per hour','redq-rental'); ?><?php endif; ?>
					</option>
				<?php }else{ ?>
					<option value="<?php echo esc_attr($value['person_count']); ?>|<?php echo esc_attr($value['person_cost']); ?>|<?php echo esc_attr($value['person_cost_applicable']); ?>|<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_cost= "<?php echo esc_attr($value['person_cost']); ?>" data-person_count="<?php echo esc_attr($value['person_count']); ?>" data-applicable="<?php echo esc_attr($value['person_cost_applicable']); ?>">
						<?php echo esc_attr($value['person_count']); ?><?php if(isset($value['person_cost']) && !empty($value['person_cost'])): ?><?php _e(' :  Cost - ','redq-rental'); ?><?php echo wc_price($value['person_cost']); ?><?php _e(' - One time','redq-rental'); ?><?php endif; ?>
					</option>
				<?php } ?>
			<?php } ?>
		</select>
	</div>
	<?php else : ?>
		<div class="additional-person additional-person-child rnb-select-wrapper rnb-component-wrapper" style="display: none;">
			<h5><?php echo esc_attr($labels['childs']); ?></h5>
		</div>
<?php endif; ?>

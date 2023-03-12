<?php 

	if(isset($day_range['min_days']) && !empty($day_range['min_days'])){
		$min_days = $day_range['min_days'];
	}else{
		$min_days = '';
	}

	if(isset($day_range['max_days']) && !empty($day_range['max_days'])){
		$max_days = $day_range['max_days'];
	}else{
		$max_days = '';
	}

	if(isset($day_range['range_cost']) && !empty($day_range['range_cost'])){
		$range_cost = $day_range['range_cost'];
	}else{
		$range_cost = '';
	}	

	if(isset($day_range['cost_applicable']) && !empty($day_range['cost_applicable'])){
		$cost_applicable = $day_range['cost_applicable'];
	}else{
		$cost_applicable = '';
	}

?>


<div class="days_range_group redq-remove-rows sort ui-state-default" style="background: none; border: none;">

	<div class="redq-show-bar">
		<h4 class="redq-headings"> <?php _e('Days','redq-rental'); ?> ( <?php echo esc_attr($min_days); ?> - <?php echo esc_attr($max_days); ?> ) <?php _e('-  Cost','redq-rental'); ?> : <?php echo esc_attr($range_cost); ?><?php echo esc_attr(get_woocommerce_currency_symbol()); ?>
			<button style="float: right" type="button" class="remove_row button"><i class="fa fa-trash-o"></i><?php _e('Remove','redq-rental'); ?></button>
			<a type="button" class="handlediv button-link" aria-expanded="true">
				<span class="screen-reader-text">Toggle panel: Product Image</span>
				<span class="handlediv toggle-indicator show-or-hide" title="Click to toggle"></span>
			</a>
		</h4>		
	</div>

	<div class="redq-hide-row" style="margin: 15px;">	
		<?php
			
			woocommerce_wp_text_input( 
				array( 
					'id' => 'min_days', 
					'name' => 'redq_min_days[]',
					'label' => __( 'Min Days', 'redq-rental' ), 
					'placeholder' => __( 'Days', 'redq-rental' ), 
					'type' => 'number', 
					'custom_attributes' => array(
						'step' 	=> '1',
						'min'	=> '0'
					),
					'value' => $min_days,  
				) 
			);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'max_days', 
					'name' => 'redq_max_days[]',
					'label' => __( 'Max Days', 'redq-rental' ), 
					'placeholder' => __( 'days', 'redq-rental' ), 
					'type' => 'number', 
					'custom_attributes' => array(
						'step' 	=> '1',
						'min'	=> '0'
					),
					'value' => $max_days,  
				) 
			);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'days_range_cost', 
					'name' => 'redq_days_range_cost[]',
					'label' => __( 'Days Range Cost ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 
					'placeholder' => __( 'Cost', 'redq-rental' ), 
					'type' => 'text', 					
					'value' => $range_cost,  
				) 
			);

			woocommerce_wp_select( 
				array( 
					'id' => 'day_range_cost_applicable',
					'name' => 'redq_day_range_cost_applicable[]',
					'label' => __( 'Applicable', 'redq-rental' ), 
					'description' => sprintf( __( 'This will be applicable during booking cost calculation', 'redq-rental' ), 'redq-rental' ), 
					'options' => array(	
						'' => __( 'Select Type', 'redq-rental' ),			
						'per_day' => __( 'Per Day', 'redq-rental' ),				
						'fixed'=> __( 'Fixed', 'redq-rental' ),										
					),
					'value' => $cost_applicable, 
				) 
			);

		?>
	</div>

</div>
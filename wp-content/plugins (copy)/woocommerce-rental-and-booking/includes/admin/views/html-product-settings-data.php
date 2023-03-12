<?php
$admin_url = get_admin_url();
$post_id = get_the_id();
?>
<div id="rnb_setting_tabs">
    <ul>
        <li><a href="#showorhide"><?php esc_attr_e('Display', 'redq-rental'); ?></a></li>
        <li><a href="#physical_appearence"><?php esc_attr_e('Labels', 'redq-rental'); ?></a></li>
        <li><a href="#logical_appearence"><?php esc_attr_e('Conditions', 'redq-rental'); ?></a></li>
        <li><a href="#validation"><?php esc_attr_e('Validations', 'redq-rental'); ?></a></li>
    </ul>
    <div id="showorhide">
        <?php
				$display_url = esc_url($admin_url) . 'admin.php?page=wc-settings&tab=rnb_settings&section=display';
				$value = get_post_meta($post_id, 'rnb_settings_for_display', true);
				woocommerce_wp_select(
					array(
						'id' => 'rnb_settings_for_display',
						'label' => __('Choose Settings For Display Tab', 'redq-rental'),
						'description' => sprintf(__('If you choose local setting then these following options will work, If you choose Global Setting then' . '<a href="%1$s" target="_blank"> ' . __('Global Settings', 'redq-rental') . '</a>' . ' Of This Plugin will work ', 'redq-rental'), $display_url),
						'options' => array(
							'global' => __('Global Settings', 'redq-rental'),
							'local' => __('Local Settings', 'redq-rental'),
						),
						//'desc_tip' => true,
						'value' => $value
					)
				);

				$show_pickup_date = get_post_meta($post_id, 'redq_rental_local_show_pickup_date', true);
				if (empty($show_pickup_date)) {
					$pickupdate = get_option('rnb_show_pickup_date');
					if (empty($pickupdate)) {
						$show_pickup_date = 'open';
					} else {
						if (get_option('rnb_show_pickup_date') === 'yes') {
							$show_pickup_date = 'open';
						} else {
							$show_pickup_date = 'closed';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_show_pickup_date',
						'label'   => __('Show Pickup Date', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($show_pickup_date),
					)
				);

				$show_pickup_time = get_post_meta($post_id, 'redq_rental_local_show_pickup_time', true);
				if (empty($show_pickup_time)) {
					$pickuptime = get_option('rnb_show_pickup_time');
					if (empty($pickuptime)) {
						$show_pickup_time = 'open';
					} else {
						if (get_option('rnb_show_pickup_time') === 'yes') {
							$show_pickup_time = 'open';
						} else {
							$show_pickup_time = 'closed';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_show_pickup_time',
						'label'   => __('Show Pickup Time', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($show_pickup_time),
					)
				);

				$show_dropoff_date = get_post_meta($post_id, 'redq_rental_local_show_dropoff_date', true);
				if (empty($show_dropoff_date)) {
					$dropoffdate = get_option('rnb_show_dropoff_date');
					if (empty($dropoffdate)) {
						$show_dropoff_date = 'open';
					} else {
						if (get_option('rnb_show_dropoff_date') === 'yes') {
							$show_dropoff_date = 'open';
						} else {
							$show_dropoff_date = 'closed';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_show_dropoff_date',
						'label'   => __('Show Dropoff Date', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($show_dropoff_date),
					)
				);

				$show_dropoff_time = get_post_meta($post_id, 'redq_rental_local_show_dropoff_time', true);
				if (empty($show_dropoff_time)) {
					$dropofftime = get_option('rnb_show_dropoff_time');
					if (empty($dropofftime)) {
						$show_dropoff_time = 'open';
					} else {
						if (get_option('rnb_show_dropoff_time') === 'yes') {
							$show_dropoff_time = 'open';
						} else {
							$show_dropoff_time = 'closed';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_show_dropoff_time',
						'label'   => __('Show Dropoff Time', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($show_dropoff_time),
					)
				);


				$enable_quantity = get_post_meta($post_id, 'rnb_enable_quantity', true);
				if (empty($enable_quantity)) {
					$qty = get_option('rnb_enable_quantity');
					if (empty($qty)) {
						$enable_quantity = 'open';
					} else {
						if (get_option('rnb_enable_quantity') === 'yes') {
							$enable_quantity = 'open';
						} else {
							$enable_quantity = 'closed';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'rnb_enable_quantity',
						'label'   => __('Enable Quantity', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($enable_quantity),
					)
				);


				$show_pricing_flip_box = get_post_meta($post_id, 'redq_rental_local_show_pricing_flip_box', true);
				if (empty($show_pricing_flip_box)) {
					$flipbox = get_option('rnb_enable_price_flipbox');
					if (empty($flipbox)) {
						$show_pricing_flip_box = 'open';
					} else {
						if (get_option('rnb_enable_price_flipbox') === 'yes') {
							$show_pricing_flip_box = 'open';
						} else {
							$show_pricing_flip_box = 'closed';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_show_pricing_flip_box',
						'label'   => __('Show pricing flip box', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($show_pricing_flip_box),
					)
				);

				$show_price_discount_on_days = get_post_meta($post_id, 'redq_rental_local_show_price_discount_on_days', true);
				if (empty($show_price_discount_on_days)) {
					$discount = get_option('rnb_enable_price_discount');
					if (empty($discount)) {
						$show_price_discount_on_days = 'open';
					} else {
						if (get_option('rnb_enable_price_discount') === 'yes') {
							$show_price_discount_on_days = 'open';
						} else {
							$show_price_discount_on_days = 'closed';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_show_price_discount_on_days',
						'label'   => __('Show price discount', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($show_price_discount_on_days),
					)
				);


				$show_price_instance_payment = get_post_meta($post_id, 'redq_rental_local_show_price_instance_payment', true);
				if (empty($show_price_instance_payment)) {
					$instance_payment = get_option('rnb_enable_instance_payment');
					if (empty($instance_payment)) {
						$show_price_instance_payment = 'open';
					} else {
						if (get_option('rnb_enable_instance_payment') === 'yes') {
							$show_price_instance_payment = 'open';
						} else {
							$show_price_instance_payment = 'closed';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_show_price_instance_payment',
						'label'   => __('Show instance payment', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($show_price_instance_payment),
					)
				);

				$show_request_quote = get_post_meta($post_id, 'redq_rental_local_show_request_quote', true);
				if (empty($show_request_quote)) {
					$show_request_quote_global = get_option('rnb_enable_rft_endpoint');
					if (empty($show_request_quote_global)) {
						$show_request_quote = 'closed';
					} else {
						if ($show_request_quote_global === 'yes') {
							$show_request_quote = 'open';
						} else {
							$show_request_quote = 'closed';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_show_request_quote',
						'label'   => __('Show Quote Request', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($show_request_quote),
					)
				);

				$show_book_now = get_post_meta($post_id, 'redq_rental_local_show_book_now', true);
				if (empty($show_book_now)) {
					$disable_book_now = get_option('rnb_enable_book_now_btn');
					if (empty($disable_book_now)) {
						$show_book_now = 'open';
					} else {
						if (get_option('rnb_enable_book_now_btn') === 'yes') {
							$show_book_now = 'closed';
						} else {
							$show_book_now = 'open';
						}
					}
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_show_book_now',
						'label'   => __('Show Book Now', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($show_book_now),
					)
				);

				?>
    </div>
    <div id="physical_appearence">
        <?php

				$labels_url = esc_url($admin_url) . 'admin.php?page=wc-settings&tab=rnb_settings&section=labels';
				$value = get_post_meta($post_id, 'rnb_settings_for_labels', true);
				woocommerce_wp_select(
					array(
						'id' => 'rnb_settings_for_labels',
						'label' => __('Choose Settings For Labels Tab', 'redq-rental'),
						'description' => sprintf(__('If you choose local setting then these following options will work, If you choose Global Setting then' . '<a href="%1$s" target="_blank"> ' . __('Global Settings', 'redq-rental') . '</a>' . ' Of This Plugin will work ', 'redq-rental'), $labels_url),
						'options' => array(
							'global' => __('Global Settings', 'redq-rental'),
							'local' => __('Local Settings', 'redq-rental'),
						),
						//'desc_tip' => true,
						'value' => $value
					)
				);

				$inventory_title = get_post_meta($post_id, 'rnb_inventory_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'rnb_inventory_title',
						'name' => 'rnb_inventory_title',
						'label' => __('Choose Inventory', 'redq-rental'),
						'placeholder' => __('Choose Inventory', 'redq-rental'),
						'type' => 'text',
						'value' => $inventory_title,
					)
				);

				$show_pricing_flipbox_text = get_post_meta($post_id, 'redq_show_pricing_flipbox_text', true);

				woocommerce_wp_text_input(
					array(
						'id' => 'show_pricing_flipbox_text',
						'name' => 'redq_show_pricing_flipbox_text',
						'label' => __('Show Pricing Text', 'redq-rental'),
						'placeholder' => __('Show Pricing Text', 'redq-rental'),
						'type' => 'text',
						'value' => $show_pricing_flipbox_text,
					)
				);

				$flip_pricing_plan_text = get_post_meta($post_id, 'redq_flip_pricing_plan_text', true);

				woocommerce_wp_text_input(
					array(
						'id' => 'flip_pricing_plan_text',
						'name' => 'redq_flip_pricing_plan_text',
						'label' => __('Show Pricing Info Heading Text', 'redq-rental'),
						'placeholder' => __('Show Pricing Info Heading Text', 'redq-rental'),
						'type' => 'text',
						'value' => $flip_pricing_plan_text,
					)
				);

				$pickup_location_heading_title = get_post_meta($post_id, 'redq_pickup_location_heading_title', true);

				woocommerce_wp_text_input(
					array(
						'id' => 'pickup_location_heading_title',
						'name' => 'redq_pickup_location_heading_title',
						'label' => __('Pickup Location Heading Title', 'redq-rental'),
						'placeholder' => __('pickup location title', 'redq-rental'),
						'type' => 'text',
						'value' => $pickup_location_heading_title,
					)
				);


				$redq_pickup_loc_placeholder = get_post_meta($post_id, 'redq_pickup_loc_placeholder', true);

				woocommerce_wp_text_input(
					array(
						'id' => 'redq_pickup_loc_placeholder',
						'name' => 'redq_pickup_loc_placeholder',
						'label' => __('Pickup Location Placeholder', 'redq-rental'),
						'placeholder' => __('pickup location placeholder', 'redq-rental'),
						'type' => 'text',
						'value' => $redq_pickup_loc_placeholder,
					)
				);

				$dropoff_location_heading_title = get_post_meta($post_id, 'redq_dropoff_location_heading_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'dropoff_location_heading_title',
						'name' => 'redq_dropoff_location_heading_title',
						'label' => __('Dropoff Location Heading Title', 'redq-rental'),
						'placeholder' => __('Dropoff location title', 'redq-rental'),
						'type' => 'text',
						'value' => $dropoff_location_heading_title,
					)
				);

				$redq_return_loc_placeholder = get_post_meta($post_id, 'redq_return_loc_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'redq_return_loc_placeholder',
						'name' => 'redq_return_loc_placeholder',
						'label' => __('Dropoff Location Placeholder', 'redq-rental'),
						'placeholder' => __('Dropoff location placeholder', 'redq-rental'),
						'type' => 'text',
						'value' => $redq_return_loc_placeholder,
					)
				);

				$pickup_date_heading_title = get_post_meta($post_id, 'redq_pickup_date_heading_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'pickup_date_heading_title',
						'name' => 'redq_pickup_date_heading_title',
						'label' => __('Pickup Date Heading Title', 'redq-rental'),
						'placeholder' => __('Pickup date title', 'redq-rental'),
						'type' => 'text',
						'value' => $pickup_date_heading_title,
					)
				);


				$pickup_date_placeholder = get_post_meta($post_id, 'redq_pickup_date_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'pickup_date_placeholder',
						'name' => 'redq_pickup_date_placeholder',
						'label' => __('Pickup Date Placeholder', 'redq-rental'),
						'placeholder' => __('Pickup date placeholder', 'redq-rental'),
						'type' => 'text',
						'value' => $pickup_date_placeholder,
					)
				);


				$pickup_time_placeholder = get_post_meta($post_id, 'redq_pickup_time_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'pickup_time_placeholder',
						'name' => 'redq_pickup_time_placeholder',
						'label' => __('Pickup Time Placeholder', 'redq-rental'),
						'placeholder' => __('Pickup date placeholder', 'redq-rental'),
						'type' => 'text',
						'value' => $pickup_time_placeholder,
					)
				);


				$dropoff_date_heading_title = get_post_meta($post_id, 'redq_dropoff_date_heading_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'dropoff_date_heading_title',
						'name' => 'redq_dropoff_date_heading_title',
						'label' => __('Dropoff Date Heading Title', 'redq-rental'),
						'placeholder' => __('Dropoff date title', 'redq-rental'),
						'type' => 'text',
						'value' => $dropoff_date_heading_title,
					)
				);


				$dropoff_date_placeholder = get_post_meta($post_id, 'redq_dropoff_date_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'dropoff_date_placeholder',
						'name' => 'redq_dropoff_date_placeholder',
						'label' => __('Drop-off Date Placeholder', 'redq-rental'),
						'placeholder' => __('Drop-off date placeholder', 'redq-rental'),
						'type' => 'text',
						'value' => $dropoff_date_placeholder,
					)
				);


				$dropoff_time_placeholder = get_post_meta($post_id, 'redq_dropoff_time_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'dropoff_time_placeholder',
						'name' => 'redq_dropoff_time_placeholder',
						'label' => __('Drop-off Time Placeholder', 'redq-rental'),
						'placeholder' => __('Drop-off time placeholder', 'redq-rental'),
						'type' => 'text',
						'value' => $dropoff_time_placeholder,
					)
				);

				$rnb_quantity_title = get_post_meta($post_id, 'rnb_quantity_label', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'rnb_quantity_title',
						'name' => 'rnb_quantity_label',
						'label' => __('Quantity Heading Title', 'redq-rental'),
						'placeholder' => __('Quantity title', 'redq-rental'),
						'type' => 'text',
						'value' => $rnb_quantity_title,
					)
				);

				$rnb_cat_heading_title = get_post_meta($post_id, 'redq_rnb_cat_heading', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'rnb_cat_heading_title',
						'name' => 'redq_rnb_cat_heading',
						'label' => __('Category Heading Title', 'redq-rental'),
						'placeholder' => __('Category title', 'redq-rental'),
						'type' => 'text',
						'value' => $rnb_cat_heading_title,
					)
				);

				$resources_heading_title = get_post_meta($post_id, 'redq_resources_heading_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'resources_heading_title',
						'name' => 'redq_resources_heading_title',
						'label' => __('Resources Heading Title', 'redq-rental'),
						'placeholder' => __('Resources title', 'redq-rental'),
						'type' => 'text',
						'value' => $resources_heading_title,
					)
				);

				$adults_heading_title = get_post_meta($post_id, 'redq_adults_heading_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'adults_heading_title',
						'name' => 'redq_adults_heading_title',
						'label' => __('Adults Heading Title', 'redq-rental'),
						'placeholder' => __('Adults title', 'redq-rental'),
						'type' => 'text',
						'value' => $adults_heading_title,
					)
				);

				$adults_placeholder = get_post_meta($post_id, 'redq_adults_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'adults_placeholder',
						'name' => 'redq_adults_placeholder',
						'label' => __('Adults Placeholder', 'redq-rental'),
						'placeholder' => __('Adults placeholder', 'redq-rental'),
						'type' => 'text',
						'value' => $adults_placeholder,
					)
				);

				$childs_heading_title = get_post_meta($post_id, 'redq_childs_heading_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'childs_heading_title',
						'name' => 'redq_childs_heading_title',
						'label' => __('Childs Heading Title', 'redq-rental'),
						'placeholder' => __('Childs title', 'redq-rental'),
						'type' => 'text',
						'value' => $childs_heading_title,
					)
				);

				$childs_placeholder = get_post_meta($post_id, 'redq_childs_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'childs_placeholder',
						'name' => 'redq_childs_placeholder',
						'label' => __('Childs Placeholder', 'redq-rental'),
						'placeholder' => __('Childs placeholder', 'redq-rental'),
						'type' => 'text',
						'value' => $childs_placeholder,
					)
				);


				$security_deposite_heading_title = get_post_meta($post_id, 'redq_security_deposite_heading_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'security_deposite_heading_title',
						'name' => 'redq_security_deposite_heading_title',
						'label' => __('Security Deposite Heading Title', 'redq-rental'),
						'placeholder' => __('Security deposite title', 'redq-rental'),
						'type' => 'text',
						'value' => $security_deposite_heading_title,
					)
				);


				$discount_text_title = get_post_meta($post_id, 'redq_discount_text_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'discount_text_title',
						'name' => 'redq_discount_text_title',
						'label' => __('Discount Text', 'redq-rental'),
						'placeholder' => __('Discount Text', 'redq-rental'),
						'type' => 'text',
						'value' => $discount_text_title,
					)
				);

				$instance_pay_text_title = get_post_meta($post_id, 'redq_instance_pay_text_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'instance_pay_text_title',
						'name' => 'redq_instance_pay_text_title',
						'label' => __('Instance Payment Text', 'redq-rental'),
						'placeholder' => __('Instance Payment Text', 'redq-rental'),
						'type' => 'text',
						'value' => $instance_pay_text_title,
					)
				);


				$total_cost_text_title = get_post_meta($post_id, 'redq_total_cost_text_title', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'total_cost_text_title',
						'name' => 'redq_total_cost_text_title',
						'label' => __('Total Cost Text', 'redq-rental'),
						'placeholder' => __('Total Cost Text', 'redq-rental'),
						'type' => 'text',
						'value' => $total_cost_text_title,
					)
				);


				$book_now_button_text = get_post_meta($post_id, 'redq_book_now_button_text', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'book_now_button_text',
						'name' => 'redq_book_now_button_text',
						'label' => __('Book Now Button Text', 'redq-rental'),
						'placeholder' => __('Book now button text', 'redq-rental'),
						'type' => 'text',
						'value' => $book_now_button_text,
					)
				);

				$rfq_button_text = get_post_meta($post_id, 'redq_rfq_button_text', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'rfq_button_text',
						'name' => 'redq_rfq_button_text',
						'label' => __('Request For Quote Button Text', 'redq-rental'),
						'placeholder' => __('Request For Quote Button Text', 'redq-rental'),
						'type' => 'text',
						'value' => $rfq_button_text,
					)
				);

				$rfq_username_placeholder = get_post_meta($post_id, 'redq_username_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'redq_username_placeholder',
						'name' => 'redq_username_placeholder',
						'label' => __('RFQ username placeholder', 'redq-rental'),
						'placeholder' => __('Username', 'redq-rental'),
						'type' => 'text',
						'value' => $rfq_username_placeholder,
					)
				);

				$rfq_password_placeholder = get_post_meta($post_id, 'redq_password_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'redq_password_placeholder',
						'name' => 'redq_password_placeholder',
						'label' => __('RFQ password placeholder', 'redq-rental'),
						'placeholder' => __('Password', 'redq-rental'),
						'type' => 'text',
						'value' => $rfq_password_placeholder,
					)
				);

				$rfq_first_name_placeholder = get_post_meta($post_id, 'redq_first_name_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'redq_first_name_placeholder',
						'name' => 'redq_first_name_placeholder',
						'label' => __('RFQ first Name placeholder', 'redq-rental'),
						'placeholder' => __('First Name', 'redq-rental'),
						'type' => 'text',
						'value' => $rfq_first_name_placeholder,
					)
				);

				$rfq_last_name_placeholder = get_post_meta($post_id, 'redq_last_name_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'redq_last_name_placeholder',
						'name' => 'redq_last_name_placeholder',
						'label' => __('RFQ last Name placeholder', 'redq-rental'),
						'placeholder' => __('Last Name', 'redq-rental'),
						'type' => 'text',
						'value' => $rfq_last_name_placeholder,
					)
				);

				$rfq_email_placeholder = get_post_meta($post_id, 'redq_email_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'redq_email_placeholder',
						'name' => 'redq_email_placeholder',
						'label' => __('RFQ email placeholder', 'redq-rental'),
						'placeholder' => __('Email', 'redq-rental'),
						'type' => 'text',
						'value' => $rfq_email_placeholder,
					)
				);

				$rfq_phone_placeholder = get_post_meta($post_id, 'redq_phone_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'redq_phone_placeholder',
						'name' => 'redq_phone_placeholder',
						'label' => __('RFQ phone placeholder', 'redq-rental'),
						'placeholder' => __('Phone', 'redq-rental'),
						'type' => 'text',
						'value' => $rfq_phone_placeholder,
					)
				);

				$rfq_message_placeholder = get_post_meta($post_id, 'redq_message_placeholder', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'redq_message_placeholder',
						'name' => 'redq_message_placeholder',
						'label' => __('RFQ message placeholder', 'redq-rental'),
						'placeholder' => __('Message', 'redq-rental'),
						'type' => 'text',
						'value' => $rfq_message_placeholder,
					)
				);

				$rfq_submit_button_text = get_post_meta($post_id, 'redq_submit_button_text', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'redq_submit_button_text',
						'name' => 'redq_submit_button_text',
						'label' => __('RFQ submit button text', 'redq-rental'),
						'placeholder' => __('Submit', 'redq-rental'),
						'type' => 'text',
						'value' => $rfq_submit_button_text,
					)
				);


				?>
    </div>
    <div id="logical_appearence">

        <?php do_action('rnb_before_logical_apearence'); ?>

        <?php

				$conditions_url = esc_url($admin_url) . 'admin.php?page=wc-settings&tab=rnb_settings&section=conditions';
				$value = get_post_meta($post_id, 'rnb_settings_for_conditions', true);
				woocommerce_wp_select(
					array(
						'id' => 'rnb_settings_for_conditions',
						'label' => __('Choose Settings For Conditions Tab', 'redq-rental'),
						'description' => sprintf(__('If you choose local setting then these following options will work, If you choose Global Setting then' . '<a href="%1$s" target="_blank"> ' . __('Global Settings', 'redq-rental') . '</a>' . ' Of This Plugin will work ', 'redq-rental'), $conditions_url),
						'options' => array(
							'global' => __('Global Settings', 'redq-rental'),
							'local' => __('Local Settings', 'redq-rental'),
						),
						//'desc_tip' => true,
						'value' => $value
					)
				);				

				$date_format = get_post_meta($post_id, 'redq_calendar_date_format', true);
				woocommerce_wp_select(
					array(
						'id' => 'choose_date_format',
						'label' => __('Date Format', 'redq-rental'),
						'description' => __('Date will display in this format all place in rental product', 'redq-rental'),
						'desc_tip' => true,
						'options' => array(
							'm/d/Y' => __('m/d/Y', 'redq-rental'),
							'd/m/Y' => __('d/m/Y', 'redq-rental'),
							'Y/m/d' => __('Y/m/d', 'redq-rental'),
						),
						'value' => $date_format
					)
				);

				$time_format = get_post_meta($post_id, 'redq_calendar_time_format', true);
				woocommerce_wp_select(
					array(
						'id' => 'choose_time_format',
						'label' => __('Time Format', 'redq-rental'),
						'description' => __('This will be applicable in the time picker field in product page', 'redq-rental'),
						'options' => array(
							'24-hours' => __('24 Hours', 'redq-rental'),
							'12-hours' => __('12 Hours', 'redq-rental'),
						),
						'value' => $time_format
					)
				);

				$max_time_late = get_post_meta($post_id, 'redq_max_time_late', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'max_time_late',
						'name' => 'redq_max_time_late',
						'label' => __('Max Hour Late', 'redq-rental'),
						'description' => __('Another day will count if customer returns by exceeding this no. of hour. Suppose you set the hour as 2. Now if a customer place an order from  10/10/2018 at 10:00 to 12/10/2018 at 12:00 he will be charged for 2 days ( although here is 50 hours means 2days and 2 hours). Now if he returns after 12/10/2018 at 12:00 then he will be charged for 3days ', 'redq-rental'),
						'desc_tip' => true,
						'placeholder' => __('E.g. - 2 (floating value is not allowed)', 'redq-rental'),
						'type' => 'number',
						'custom_attributes' => array(
							'step' 	=> '1',
							'min'	=> '0'
						),
						'value' => $max_time_late,
					)
                );
                
                $rnb_pay_extra_hours = get_post_meta($post_id, 'rnb_pay_extra_hours', true);
				woocommerce_wp_select(
					array(
						'id' => 'rnb_pay_extra_hours',
						'label' => __('Pay Extra Hours', 'redq-rental'),
						'description' => __('If you make this option as yes then customer has to pay for extra hours in hourly pricing plan.', 'redq-rental'),
						'options' => array(
							'yes' => __('Yes', 'redq-rental'),
							'no' => __('No', 'redq-rental'),
						),
						'value' => $rnb_pay_extra_hours
					)
				);

				$enable_single_day_time_based_booking = get_post_meta($post_id, 'redq_rental_local_enable_single_day_time_based_booking', true);
				if (isset($enable_single_day_time_based_booking) && empty($enable_single_day_time_based_booking)) {
					$enable_single_day_time_based_booking = 'open';
				}
				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_enable_single_day_time_based_booking',
						'label'   => __('Single Day Booking', 'redq-rental'),
						'desc_tip' => true,
						'description' => sprintf(__('Checked : If pickup and return date are same then it counts as 1-day. Also select this for single date. FYI : Set max time late as at least 0 for this. UnChecked : If pickup and return date are same then it counts as 0-day. Also select this for single date. ', 'redq-rental')),
						'cbvalue' => 'open',
						'value' => esc_attr($enable_single_day_time_based_booking),
					)
				);

				$max_rental_days = get_post_meta($post_id, 'redq_max_rental_days', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'max_rental_days',
						'name' => 'redq_max_rental_days',
						'label' => __('Maximum Booking Days', 'redq-rental'),
						'placeholder' => __('E.g. - 5', 'redq-rental'),
						'description' => __('No. of days that customer must have to select during placing an order otherwise he will not be allowed to place an order', 'redq-rental'),
						'desc_tip' => true,
						'type' => 'number',
						'custom_attributes' => array(
							'step' 	=> '1',
							'min'	=> '0'
						),
						'value' => $max_rental_days,
					)
				);


				$min_rental_days = get_post_meta($post_id, 'redq_min_rental_days', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'min_rental_days',
						'name' => 'redq_min_rental_days',
						'label' => __('Minimum Booking Days', 'redq-rental'),
						'placeholder' => __('E.g. - 1', 'redq-rental'),
						'description' => __('No. of days that customer must have to select during placing an order otherwise he will not be allowed to place an order', 'redq-rental'),
						'desc_tip' => true,
						'type' => 'number',
						'custom_attributes' => array(
							'step' 	=> '1',
							'min'	=> '0'
						),
						'value' => $min_rental_days,
					)
				);

				$starting_block_days = get_post_meta($post_id, 'redq_rental_starting_block_dates', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'starting_block_days',
						'name' => 'redq_rental_starting_block_dates',
						'label' => __('Initially blocked dates in calendar', 'redq-rental'),
						'placeholder' => __('E.g. - 2', 'redq-rental'),
						'description' => __('If you set the value as 2, When someone open the calendar in product page if today is 10/10/2018 then customer will see the initially bookable date as 12/10/2018', 'redq-rental'),
						'desc_tip' => true,
						'type' => 'number',
						'custom_attributes' => array(
							'step' 	=> '1',
							'min'	=> '0'
						),
						'value' => $starting_block_days,
					)
				);

				$pre_booking_block_days = get_post_meta($post_id, 'redq_rental_before_booking_block_dates', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'pre_booking_block_days',
						'name' => 'redq_rental_before_booking_block_dates',
						'label' => __('Pre Booking Block Days', 'redq-rental'),
						'placeholder' => __('E.g. - 2', 'redq-rental'),
						'description' => __('Selected no. of days will be blocked automatically after a booking order and customer will not be charged for extra these days. Suppose you set the value 2. Now if any customer books date from 10/10/18 to 12/10/18 then after completing the order 08/10/18 to 10/10/18 date will be disabled in calendar for this order. Although customer will not be charged for these extra 2 days', 'redq-rental'),
						'desc_tip' => true,
						'type' => 'number',
						'custom_attributes' => array(
							'step' 	=> '1',
							'min'	=> '0'
						),
						'value' => $pre_booking_block_days,
					)
				);


				$post_booking_block_days = get_post_meta($post_id, 'redq_rental_post_booking_block_dates', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'post_booking_block_days',
						'name' => 'redq_rental_post_booking_block_dates',
						'label' => __('Post Booking Block Days', 'redq-rental'),
						'placeholder' => __('E.g. - 2', 'redq-rental'),
						'description' => __('Selected no. of days will be blocked automatically after a booking and customer will not be charged for extra these days. Suppose you set the value 2. Now if any customer books date from 10/10/18 to 12/10/18 then after completing the order 10/10/18 to 14/10/18 date will be disabled in calendar for this order. Although customer will not be charged for these extra 2 days', 'redq-rental'),
						'type' => 'number',
						'custom_attributes' => array(
							'step' 	=> '1',
							'min'	=> '0'
						),
						'desc_tip' => true,
						'value' => $post_booking_block_days,
					)
				);

				$time_interval = get_post_meta($post_id, 'redq_time_interval', true);
				woocommerce_wp_text_input(
					array(
						'id' => 'time_interval',
						'name' => 'redq_time_interval',
						'label' => __('Time Interval', 'redq-rental'),
						'placeholder' => __('Time Interval in mins E.g. - 20', 'redq-rental'),
						'description' => __('Time Interval in mins E.g. - 20. Time interval will not work if you use allowed times options', 'redq-rental'),
						'type' => 'number',
						'data_type' => 'decimal',
						'custom_attributes' => array(
							'step' 	=> '1',
							'min'	=> '0',
							'max'   => '60'
						),
						'desc_tip' => true,
						'value' => $time_interval,
					)
                );
                
				woocommerce_wp_select(
					array(
						'id' => 'rnb_show_price_type',
						'name' => 'rnb_show_price_type',
						'label' => __( 'Show Product Pricing', 'redq-rental' ),
						'description' => __( 'If you set the value as yes then date or range of dates will be blocked after placing an order depending on no. of inventories. If you set the value as no then date will not be blocked after placing the order', 'redq-rental' ),
				        'desc_tip' => true,
				        'options' => array(
							'daily'=> __( 'Daily Pricing', 'redq-rental' ),
							'hourly' => __( 'Hourly Pricing', 'redq-rental' ),
						),
						'value' => get_post_meta($post_id,'rnb_show_price_type',true)
					)
				);


				$redq_allowed_times = get_post_meta($post_id, 'redq_allowed_times', true);
				woocommerce_wp_textarea_input(
					array(
						'id' => 'redq_allowed_times',
						'name' => 'redq_allowed_times',
						'label' => __('Allowed Times', 'redq-rental'),
						'placeholder' => __('Enter allowed time in comma separated format like 10:00, 12:00 (For 24 hour time format) or 10:00 am, 11:00 am (For 12 hour time format. Use space before am or pm) ', 'redq-rental'),
						'description' => __('Enter allowed time in comma separated format like 10:00, 12:00 (For 24 hour time format) or 10:00 am, 11:00 am (For 12 hour time format. Use space before am or pm) ', 'redq-rental'),
						'type' => 'textarea',
						'desc_tip' => true,
						'value' => $redq_allowed_times,
					)
				);

				$booking_layout = get_post_meta($post_id, 'rnb_booking_layout', true);
				woocommerce_wp_select(
					array(
						'id' => 'rnb_booking_layout',
						'label' => __('Choose Booking Layout', 'redq-rental'),
						'description'     => __('Choose your booking page layout. Either it will be normal view or modal view', 'redq-rental'),
						'options' => array(
							'layout_one'    => __('Normal Layout', 'redq-rental'),
							'layout_two'    => __('Modal Layout', 'redq-rental'),
						),
						'desc_tip' => true,
						'value' => $booking_layout
					)
				);

				$days = array(
					0 => esc_html__('Sunday', 'redq-rental'),
					1 => esc_html__('Monday', 'redq-rental'),
					2 => esc_html__('Tuesday', 'redq-rental'),
					3 => esc_html__('Wednesday', 'redq-rental'),
					4 => esc_html__('Thursday', 'redq-rental'),
					5 => esc_html__('Friday', 'redq-rental'),
					6 => esc_html__('Saturday', 'redq-rental'),
				);

				$rental_off_days = get_post_meta($post_id, 'redq_rental_off_days', true);

				if (isset($rental_off_days) && empty($rental_off_days)) {
					$rental_off_days = array();
				}

				?>

        <p class="form-field">
            <label for="weekend"><?php esc_attr_e('Select Weekends', 'redq-rental'); ?></label>
            <select multiple="multiple" class="inventory-resources" style="width:350px" name="redq_rental_off_days[]" data-placeholder="<?php esc_attr_e('Choose off days', 'woocommerce'); ?>" title="<?php esc_attr_e('Weekends', 'woocommerce') ?>" class="wc-enhanced-select">
                <?php if (is_array($days) && !empty($days)) : ?>
                <?php foreach ($days as $key => $value) { ?>
                <option value="<?php echo esc_attr($key); ?>" <?php if (in_array($key, $rental_off_days)) { ?> selected <?php 
																																																										} ?>><?php echo esc_attr($value); ?></option>
                <?php 
							} ?>
                <?php endif; ?>
            </select>
        </p>

        <?php do_action('rnb_after_logical_appearence'); ?>

    </div>

    <div id="validation">

        <?php

				$validations_url = esc_url($admin_url) . 'admin.php?page=wc-settings&tab=rnb_settings&section=validations';
				$value = get_post_meta($post_id, 'rnb_settings_for_validations', true);
				woocommerce_wp_select(
					array(
						'id' => 'rnb_settings_for_validations',
						'label' => __('Choose Settings For Validations Tab', 'redq-rental'),
						'description' => sprintf(__('If you choose local setting then these following options will work, If you chooose Global Setting then' . '<a href="%1$s" target="_blank"> ' . __('Global Settings', 'redq-rental') . '</a>' . ' Of This Plugin will work ', 'redq-rental'), $validations_url),
						'options' => array(
							'global' => __('Global Settings', 'redq-rental'),
							'local' => __('Local Settings', 'redq-rental'),
						),
						//'desc_tip' => true,
						'value' => $value
					)
				);

				$required_pickup_loc = get_post_meta($post_id, 'redq_rental_local_required_pickup_location', true);
				if (empty($required_pickup_loc)) {
					$required_pickup_loc = 'closed';
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_required_pickup_location',
						'label'   => __('Required Pickup Location', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($required_pickup_loc),
					)
				);


				$required_return_loc = get_post_meta($post_id, 'redq_rental_local_required_return_location', true);
				if (empty($required_return_loc)) {
					$required_return_loc = 'closed';
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_required_return_location',
						'label'   => __('Required Return Location', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($required_return_loc),
					)
				);


				$required_person = get_post_meta($post_id, 'redq_rental_local_required_person', true);
				if (empty($required_person)) {
					$required_person = 'closed';
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_local_required_person',
						'label'   => __('Required Person', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($required_person),
					)
				);


				$required_pickup_time = get_post_meta($post_id, 'redq_rental_required_local_pickup_time', true);
				if (empty($required_pickup_time)) {
					$required_pickup_time = 'closed';
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_required_local_pickup_time',
						'label'   => __('Required Pickup Time', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($required_pickup_time),
					)
				);


				$required_return_time = get_post_meta($post_id, 'redq_rental_required_local_return_time', true);
				if (empty($required_return_time)) {
					$required_return_time = 'closed';
				}

				woocommerce_wp_checkbox(
					array(
						'id'      => 'redq_rental_required_local_return_time',
						'label'   => __('Required Return Time', 'redq-rental'),
						'cbvalue' => 'open',
						'value' => esc_attr($required_return_time),
					)
				);



				?>


        <div class="table_grid">
            <h4 class="redq-headings"><?php _e('Daily Basis Openning & Closing Time', 'redq-rental') ?></h4>
            <p><?php echo esc_html__('Enter time in the following format. 10:00 or 23:30 or 24:00 etc (For 24-hour time format) and 10:00 am or 1:30 pm or 2:00 pm etc (For 12-hour time format. Space before am or pm is important)', 'redq-rental');  ?> </p>
            <table class="widefat">

                <tbody id="availability_rows">
                    <tr>
                        <td class="sort">&nbsp;</td>
                        <td>
                            <div class="day-name">
                                <?php esc_attr_e('Friday', 'redq-rental'); ?>
                            </div>
                        </td>
                        <td>
                            <div class="fri-min-time-outer">
                                <?php $fri_min_time = get_post_meta($post_id, 'redq_rental_fri_min_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Min Time', 'redq-rental'); ?>" class="min-time" name="redq_rental_fri_min_time" value="<?php echo esc_attr($fri_min_time); ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="max-time-outer">
                                <?php $fri_max_time = get_post_meta($post_id, 'redq_rental_fri_max_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Max Time', 'redq-rental'); ?>" class="max-time" name="redq_rental_fri_max_time" value="<?php echo esc_attr($fri_max_time); ?>" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="sort">&nbsp;</td>
                        <td>
                            <div class="day-name">
                                <?php esc_attr_e('Saturday', 'redq-rental'); ?>
                            </div>
                        </td>
                        <td>
                            <div class="fri-min-time-outer">
                                <?php $sat_min_time = get_post_meta($post_id, 'redq_rental_sat_min_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Min Time', 'redq-rental'); ?>" class="min-time" name="redq_rental_sat_min_time" value="<?php echo esc_attr($sat_min_time); ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="max-time-outer">
                                <?php $sat_max_time = get_post_meta($post_id, 'redq_rental_sat_max_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Max Time', 'redq-rental'); ?>" class="max-time" name="redq_rental_sat_max_time" value="<?php echo esc_attr($sat_max_time); ?>" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="sort">&nbsp;</td>
                        <td>
                            <div class="day-name">
                                <?php esc_attr_e('Sunday', 'redq-rental'); ?>
                            </div>
                        </td>
                        <td>
                            <div class="fri-min-time-outer">
                                <?php $sun_min_time = get_post_meta($post_id, 'redq_rental_sun_min_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Min Time', 'redq-rental'); ?>" class="min-time" name="redq_rental_sun_min_time" value="<?php echo esc_attr($sun_min_time); ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="max-time-outer">
                                <?php $sun_max_time = get_post_meta($post_id, 'redq_rental_sun_max_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Max Time', 'redq-rental'); ?>" class="max-time" name="redq_rental_sun_max_time" value="<?php echo esc_attr($sun_max_time); ?>" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="sort">&nbsp;</td>
                        <td>
                            <div class="day-name">
                                <?php esc_attr_e('Monday', 'redq-rental'); ?>
                            </div>
                        </td>
                        <td>
                            <div class="fri-min-time-outer">
                                <?php $mon_min_time = get_post_meta($post_id, 'redq_rental_mon_min_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Min Time', 'redq-rental'); ?>" class="min-time" name="redq_rental_mon_min_time" value="<?php echo esc_attr($mon_min_time); ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="max-time-outer">
                                <?php $mon_max_time = get_post_meta($post_id, 'redq_rental_mon_max_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Max Time', 'redq-rental'); ?>" class="max-time" name="redq_rental_mon_max_time" value="<?php echo esc_attr($mon_max_time); ?>" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="sort">&nbsp;</td>
                        <td>
                            <div class="day-name">
                                <?php esc_attr_e('Tuesday', 'redq-rental'); ?>
                            </div>
                        </td>
                        <td>
                            <div class="fri-min-time-outer">
                                <?php $thu_min_time = get_post_meta($post_id, 'redq_rental_thu_min_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Min Time', 'redq-rental'); ?>" class="min-time" name="redq_rental_thu_min_time" value="<?php echo esc_attr($thu_min_time); ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="max-time-outer">
                                <?php $thu_max_time = get_post_meta($post_id, 'redq_rental_thu_max_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Max Time', 'redq-rental'); ?>" class="max-time" name="redq_rental_thu_max_time" value="<?php echo esc_attr($thu_max_time); ?>" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="sort">&nbsp;</td>
                        <td>
                            <div class="day-name">
                                <?php esc_attr_e('Wednesday', 'redq-rental'); ?>
                            </div>
                        </td>
                        <td>
                            <div class="fri-min-time-outer">
                                <?php $wed_min_time = get_post_meta($post_id, 'redq_rental_wed_min_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Min Time', 'redq-rental'); ?>" class="min-time" name="redq_rental_wed_min_time" value="<?php echo esc_attr($wed_min_time); ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="max-time-outer">
                                <?php $wed_max_time = get_post_meta($post_id, 'redq_rental_wed_max_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Max Time', 'redq-rental'); ?>" class="max-time" name="redq_rental_wed_max_time" value="<?php echo esc_attr($wed_max_time); ?>" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="sort">&nbsp;</td>
                        <td>
                            <div class="day-name">
                                <?php esc_attr_e('Thursday', 'redq-rental'); ?>
                            </div>
                        </td>
                        <td>
                            <div class="fri-min-time-outer">
                                <?php $thur_min_time = get_post_meta($post_id, 'redq_rental_thur_min_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Min Time', 'redq-rental'); ?>" class="min-time" name="redq_rental_thur_min_time" value="<?php echo esc_attr($thur_min_time); ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="max-time-outer">
                                <?php $thur_max_time = get_post_meta($post_id, 'redq_rental_thur_max_time', true); ?>
                                <input type="text" placeholder="<?php esc_attr_e('Max Time', 'redq-rental'); ?>" class="max-time" name="redq_rental_thur_max_time" value="<?php echo esc_attr($thur_max_time); ?>" />
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>


</div> 
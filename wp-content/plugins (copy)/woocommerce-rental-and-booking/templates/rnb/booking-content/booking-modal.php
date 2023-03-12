<?php
    global $product;
    $product_id = $product->get_id();
    $allowed_html = wp_kses_allowed_html();
    $extra_labels = reddq_rental_get_settings( $product_id, 'layout_two', array( 'location', 'datetime', 'resource', 'person', 'deposit', 'summary' ) );
    $extra_datetime_labels = $extra_labels['datetime'];
    $extra_location_labels = $extra_labels['location'];    

    $labels = reddq_rental_get_settings( $product_id, 'labels', array('inventory') );
    $labels = $labels['labels'];

    $redq_product_inventory = get_post_meta( $product_id, '_redq_product_inventory', true );

    if( empty( $redq_product_inventory ) ) {
        wc_add_notice(sprintf( __( 'You don\'t have any invertory with this product. This product in not bookable.', 'redq-rental' ) ) );
        return;
    }

    $inventories = get_posts( array(
        'post_type' => 'Inventory',
        'post__in'  => $redq_product_inventory,
    ) );

    foreach ($inventories as $index => $inventory) {
        $inventories[$index]->quantity = get_post_meta( $inventory->ID, 'quantity', true );
    }
?>
<!--Start Stepper Modal-->
<a id="showBooking" href="#animatedModal"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>

<div id="animatedModal" class="rnb-animated-modal">
    <div class="modal-content-body">
        <div class="modal-header">
            <div class="close-animatedModal"><i class="fa fa-close"></i></div>
            <div class="title-wrapper">
                <div class="title">
                    <h3><?php echo esc_attr( $extra_location_labels['location_top_heading'] ); ?></h3>
                    <p><?php echo wp_kses( $extra_location_labels['location_top_desc'], $allowed_html ); ?></p>
                </div>
                <div class="price total-rental-price">
                    <h2></h2>
                </div>
            </div>
        </div>
        <!-- End Modal Header -->

        <div class="modal-content">
            <div id="rnbSmartwizard">


                <?php $extra_location_labels = $extra_labels['location']; ?>
                <h3><i class="icon ion-ios-location"></i> <?php echo esc_html__( 'Location', 'redq-rental' ); ?></h3>
                <section class="rnb-step-content-wrapper">
                    <div id="rnb-step-1" class="">                        
                        <div class="payable-inventory rnb-component-wrapper rnb-select-wrapper redq-pick-up-location" <?php echo ( count( $inventories ) < 2 ) ? 'style="display:none"' : ''; ?>>
                            <h5><?php echo esc_attr($labels['inventory']); ?></h5>
                            <select class="rnb-select-box" id="booking_inventory" name="booking_inventory" data-post-id="<?php echo $product_id ?>">
                            <?php foreach ($inventories as $inventory) : ?>
                                <option value="<?php echo $inventory->ID ?>"><?php echo $inventory->post_title ?></option>
                            <?php endforeach ?>
                            </select>
                        </div>

                        <?php if (!empty($extra_location_labels['location_inner_heading']) || !empty($extra_location_labels['location_inner_desc']) ) { ?>
                            <header class="section-title">
                                <h4><?php echo esc_attr( $extra_location_labels['location_inner_heading'] ); ?></h4>
                                <p><?php echo wp_kses( $extra_location_labels['location_inner_desc'], $allowed_html ); ?></p>
                            </header>
                        <?php } ?>
                        <!-- end .section-title -->

                        <div class="rnb-google-location-map-area">
                        <div class="rnb-autocomplete-input-group">
                            <input type="text" name="pickup_location" class="rnb-pickup-location" id="rnb-origin-autocomplete" />
                            <input type="text" name="dropoff_location" class="rnb-dropoff-location" id="rnb-destination-autocomplete" />
                        </div>
                            <div id="rnb-map" style="height:300px; width:100%"></div>
                            <div id="right-panel" style="display: none;">
                                <p><?php echo esc_html__( 'Total Distance:', 'redq-rental' ); ?> <span id="total"></span></p>
                            </div>
                            <input type="hidden" name="total_distance" class="rnb-distance" />
                        </div>
                    </div>
                </section>
                <!-- End #1sd Step -->

                <h3><i class="icon ion-calendar"></i> <?php echo esc_html__( 'Duration', 'redq-rental' ); ?></h3>
                <section class="rnb-step-content-wrapper">
                    <div id="rnb-step-2" class="">
                    <?php if (!empty($extra_datetime_labels['date_inner_heading']) || !empty($extra_datetime_labels['date_inner_desc']) ) { ?>
                        <header class="section-title">
                            <h4><?php echo esc_attr( $extra_datetime_labels['date_inner_heading'] ); ?></h4>
                            <p><?php echo wp_kses( $extra_datetime_labels['date_inner_desc'], $allowed_html ); ?></p>
                        </header>
                        <div class="date-error-message">
                            <i class="fa fa-info-circle"></i>
                            <span class="date-error-message-text">
                                <?php echo esc_html__('Duration selection is not correct!', 'redq-rental'); ?>
                            </span>
                        </div>
                    <?php } ?>
                        <!-- end .section-title -->
                        <div class="rnb-date-select-area">
                            <?php
                                rnb_pickup_datetimes();
                                rnb_return_datetimes();
                            ?>
                        </div>
                        <?php
                            $displays = reddq_rental_get_settings( get_the_ID(), 'display' );
                            $displays = $displays['display'];
                        ?>
                        <?php if(isset($displays['quantity']) && $displays['quantity']!=='closed'): ?>
                        <div class="redq-quantity rnb-select-wrapper rnb-component-wrapper">
                            <?php
                                $labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('quantity') );
                                $labels = $labels['labels'];
                            ?>
                            <h5><?php echo esc_attr($labels['quantity']); ?></h5>
                            <input type="number" name="inventory_quantity" class="inventory-qty" min="" max="" value="1">
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <!-- End #2nd Step -->


                <?php $resources = $product->redq_get_rental_payable_attributes('resource'); ?>
                <?php if(isset($resources) && !empty($resources)): ?>
                    <?php
                        $labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('resources') );
                        $labels = $labels['labels'];
                        $extra_resource_labels = $extra_labels['resource'];
                    ?>
                    <h3><i class="icon ion-briefcase"></i> <?php echo esc_attr($labels['resource']); ?> </h3>
                    <section class="rnb-step-content-wrapper">
                        <div id="rnb-step-3" class="">
                        <?php if (!empty($extra_resource_labels['resource_inner_heading']) || !empty($extra_resource_labels['resource_inner_desc']) ) { ?>
                            <header class="section-title">
                                <h4><?php echo esc_attr( $extra_resource_labels['resource_inner_heading'] ); ?></h4>
                                <p><?php echo wp_kses( $extra_resource_labels['resource_inner_desc'], $allowed_html ); ?></p>
                            </header>
                        <?php } ?>
                             <div class="rnb-resource-checkbox-area">
                                <?php foreach ($resources as $key => $value) { ?>
                                <label class="rnb-control rnb-control-checkbox" for="rnb-resource-<?php echo $key+1; ?>">
                                    <span class="title"><?php echo esc_attr($value['resource_name']); ?></span>

                                    <?php if($value['resource_applicable'] == 'per_day'){ ?>
                                        <span class="meta show_if_day"><?php echo wc_price($value['resource_cost']); ?><?php _e(' - Per Day', 'redq-rental'); ?></span>
                                        <span class="meta show_if_time"><?php echo wc_price($value['resource_hourly_cost']); ?><?php _e(' - Per Hour','redq-rental'); ?></span>
                                    <?php }else{ ?>
                                        <span class="meta"><?php echo wc_price($value['resource_cost']); ?><?php _e(' - One Time','redq-rental'); ?></span>
                                    <?php } ?>
                                    <input type="checkbox" data-items="<?php echo $key+1; ?>" id="rnb-resource-<?php echo $key+1; ?>" name="extras[]" value = "<?php echo esc_attr($value['resource_name']); ?>|<?php echo esc_attr($value['resource_cost']); ?>|<?php echo esc_attr($value['resource_applicable']); ?>|<?php echo esc_attr($value['resource_hourly_cost']); ?>" data-name="<?php echo esc_attr($value['resource_name']); ?>" data-value-in="0" data-applicable="<?php echo esc_attr($value['resource_applicable']); ?>" data-value="<?php echo esc_attr($value['resource_cost']); ?>" data-hourly-rate="<?php echo esc_attr($value['resource_hourly_cost']); ?>" data-currency-before="$" data-currency-after="" class="carrental_extras"/>
                                </label>
                                <?php } ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <!-- End #3rd Step -->



                <?php
                    $person_info = $product->redq_get_rental_payable_attributes('person');
                    $adults = isset($person_info['adults']) ? $person_info['adults'] : '';
                    $childs = isset($person_info['childs']) ? $person_info['childs'] : '';

                    $labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('person') );
                    $labels = $labels['labels'];
                    $extra_person_labels = $extra_labels['person'];
                ?>

                <?php if(isset($person_info) && !empty($person_info)): ?>
                    <h3><i class="icon ion-person-stalker"></i> <?php echo esc_html__( 'Person', 'redq-rental' ); ?> </h3>
                    <section class="rnb-step-content-wrapper">
                        <div id="rnb-step-4" class="">
                        <?php if (!empty($extra_person_labels['person_inner_heading']) || !empty($extra_person_labels['person_inner_desc']) ) { ?>
                            <header class="section-title">
                                <h4><?php echo esc_attr( $extra_person_labels['person_inner_heading'] ); ?></h4>
                                <p><?php echo wp_kses( $extra_person_labels['person_inner_desc'], $allowed_html ); ?></p>
                            </header>
                        <?php } ?>
                            <div class="rnb-persons-section">
                                <h4><?php echo esc_attr($labels['adults']); ?></h4>
                                <div class="person-radiobtns-area rnb-adult-area">
                                    <?php foreach ($adults as $key => $value) { ?>
                                        <label class="rnb-control rnb-control-radio rnb-adult-label" for="rnb-adult-<?php echo $key+1; ?>">
                                            <span class="title"><?php echo esc_attr($value['person_count']); ?></span>
                                            <?php if($value['person_cost_applicable'] == 'per_day'){ ?>
                                                <span class="meta show_if_day"><?php echo wc_price($value['person_cost']); ?><?php _e(' - Per Day', 'redq-rental'); ?></span>
                                                <span class="meta show_if_time"><?php echo wc_price($value['person_hourly_cost']); ?><?php _e(' - Per Hour','redq-rental'); ?></span>
                                            <?php }else{ ?>
                                                <span class="meta"><?php echo wc_price($value['person_cost']); ?><?php _e(' - One Time','redq-rental'); ?></span>
                                            <?php } ?>
                                            <input class="additional_adults_info" type="radio" name="additional_adults_info" data-items="<?php echo $key+1; ?>" id="rnb-adult-<?php echo $key+1; ?>" value="<?php echo esc_attr($value['person_count']); ?>|<?php echo esc_attr($value['person_cost']); ?>|<?php echo esc_attr($value['person_cost_applicable']); ?>|<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_cost= "<?php echo esc_attr($value['person_cost']); ?>" data-person_count="<?php echo esc_attr($value['person_count']); ?>" data-applicable="<?php echo esc_attr($value['person_cost_applicable']); ?>"/>
                                        </label>
                                    <?php } ?>
                                </div>
                                <!-- end .adult-radio-area -->

                                <h4><?php echo esc_attr($labels['childs']); ?></h4>
                                <div class="person-radiobtns-area rnb-child-area">
                                    <?php foreach ($childs as $key => $value) { ?>
                                        <label class="rnb-control rnb-control-radio rnb-child-label" for="rnb-child-<?php echo $key+1; ?>">
                                            <span class="title"><?php echo esc_attr($value['person_count']); ?></span>
                                            <?php if($value['person_cost_applicable'] == 'per_day'){ ?>
                                                <span class="meta show_if_day"><?php echo wc_price($value['person_cost']); ?><?php _e(' - Per Day', 'redq-rental'); ?></span>
                                                <span class="meta show_if_time"><?php echo wc_price($value['person_hourly_cost']); ?><?php _e(' - Per Hour','redq-rental'); ?></span>
                                            <?php }else{ ?>
                                                <span class="meta"><?php echo wc_price($value['person_cost']); ?><?php _e(' - One Time','redq-rental'); ?></span>
                                            <?php } ?>
                                            <input type="radio" class="additional_childs_info" name="additional_childs_info" data-items="<?php echo $key+1; ?>" id="rnb-child-<?php echo $key+1; ?>" value="<?php echo esc_attr($value['person_count']); ?>|<?php echo esc_attr($value['person_cost']); ?>|<?php echo esc_attr($value['person_cost_applicable']); ?>|<?php echo esc_attr($value['person_hourly_cost']); ?>" data-person_cost= "<?php echo esc_attr($value['person_cost']); ?>" data-person_count="<?php echo esc_attr($value['person_count']); ?>" data-applicable="<?php echo esc_attr($value['person_cost_applicable']); ?>"/>
                                        </label>
                                    <?php } ?>
                                </div>
                                <!-- end .child-radio-area -->
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
                <!-- End #4th Step -->


                <?php $security_deposites = $product->redq_get_rental_payable_attributes('deposite'); ?>
                <?php if(isset($security_deposites) && !empty($security_deposites)): ?>
                    <?php
                        $labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('deposites') );
                        $labels = $labels['labels'];
                        $extra_deposit_labels = $extra_labels['deposit'];
                    ?>
                    <h3><i class="icon ion-card"></i> <?php echo esc_attr($labels['deposite']); ?> </h3>
                    <section class="rnb-step-content-wrapper">
                        <div id="rnb-step-5" class="">
                        <?php if (!empty($extra_deposit_labels['deposit_inner_heading']) || !empty($extra_deposit_labels['deposit_inner_desc']) ) { ?>
                            <header class="section-title">
                                <h4><?php echo esc_attr( $extra_deposit_labels['deposit_inner_heading'] ); ?></h4>
                                <p><?php echo wp_kses( $extra_deposit_labels['deposit_inner_desc'], $allowed_html ); ?></p>
                            </header>
                        <?php } ?>
                            <!-- end .section-title -->
                            <div class="rnb-deposite-section">
                                <div class="deposite-checkbox-area">
                                    <?php foreach ($security_deposites as $key => $value) { ?>
                                    <label class="rnb-control rnb-control-checkbox rnb-deposit-label" for="rnb-deposit-<?php echo $key+1; ?>">
                                        <span class="title"><?php echo esc_attr($value['security_deposite_name']); ?></span>
                                        <?php if($value['security_deposite_applicable'] == 'per_day'){ ?>
                                            <span class="meta show_if_day"><?php echo wc_price($value['security_deposite_cost']); ?><span> <?php _e(' - Per Day', 'redq-rental'); ?> </span></span>
                                            <span class="meta show_if_time" style="display: none;"><?php echo wc_price($value['security_deposite_hourly_cost']); ?><?php _e(' - Per Hour', 'redq-rental'); ?></span>
                                        <?php }else{ ?>
                                            <span class="meta"><?php echo wc_price($value['security_deposite_cost']); ?><?php _e(' - One Time', 'redq-rental'); ?></span>
                                        <?php } ?>
                                        <input type="checkbox" data-items="<?php echo $key+1; ?>" id="rnb-deposit-<?php echo $key+1; ?>" <?php if($value['security_deposite_clickable'] === 'no'){ ?> checked onclick="return false" <?php } ?> name="security_deposites[]" value = "<?php echo esc_attr($value['security_deposite_name']); ?>|<?php echo esc_attr($value['security_deposite_cost']); ?>|<?php echo esc_attr($value['security_deposite_applicable']); ?>|<?php echo esc_attr($value['security_deposite_hourly_cost']); ?>" data-name="<?php echo esc_attr($value['security_deposite_name']); ?>" data-value-in="0" data-applicable="<?php echo esc_attr($value['security_deposite_applicable']); ?>" data-value="<?php echo esc_attr($value['security_deposite_cost']); ?>" data-hourly-rate="<?php echo esc_attr($value['security_deposite_hourly_cost']); ?>" data-currency-before="$" data-currency-after="" />
                                    </label>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </section>          
                <?php endif; ?>
                <!-- End #5th Step -->

                <?php $extra_summary_labels = $extra_labels['summary']; ?>
                <h3><i class="icon ion-calculator"></i> <?php echo esc_html__( 'Summary', 'redq-rental' ); ?></h3>
                <section class="rnb-step-content-wrapper">
                    <div id="rnb-step-6" class="">
                    <?php if (!empty($extra_summary_labels['summary_inner_heading']) || !empty($extra_summary_labels['summary_inner_desc']) ) { ?>
                        <header class="section-title">
                            <h4><?php echo esc_attr( $extra_summary_labels['summary_inner_heading'] ); ?></h4>
                            <p><?php echo wp_kses( $extra_summary_labels['summary_inner_desc'], $allowed_html ); ?></p>
                        </header>
                    <?php } ?>

                        <div class="booking-summay"></div>

                        <!-- end .section-title -->
                    
                        <div class="rnb-booking-summay rnb-default-hidden-page">
                            <?php rnb_booking_summary_two(); ?>
                        </div>
                    
                    </div>
                </section>
                <!-- End #6th Step -->
            </div>
        </div>
        <!-- End Modal Content -->
    </div>
</div>
<!-- End stepper modal -->

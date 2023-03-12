<?php
    global $product;
    $product_id = get_the_ID();
    $redq_product_inventory = get_post_meta( $product_id, '_redq_product_inventory', true );
    if( !empty( $redq_product_inventory ) )
        $categories = $product->redq_get_rental_payable_attributes('rnb_categories', $redq_product_inventory[0]);
?>
<?php if(isset($categories) && !empty($categories)): ?>
    <?php
        $labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('categories') );
        $labels = $labels['labels'];
    ?>
    <div class="payable-categories rnb-component-wrapper">
        <h5><?php echo esc_attr($labels['categories']); ?></h5>
        <?php foreach ($categories as $key => $value) { ?>
            <div class="attributes categories-attr">
                <label class="custom-block">
                    <?php $dta = array(); $dta['name'] = $value['name']; $dta['cost'] = $value['cost'];  ?>
                    <input type="checkbox" name="categories[]" value = "<?php echo esc_attr($value['name']); ?>|<?php echo esc_attr($value['cost']); ?>|<?php echo esc_attr($value['applicable']); ?>|<?php echo esc_attr($value['hourlycost']); ?>" data-name="<?php echo esc_attr($value['name']); ?>" data-value-in="0" data-applicable="<?php echo esc_attr($value['applicable']); ?>" data-value="<?php echo esc_attr($value['cost']); ?>" data-hourlyrate="<?php echo esc_attr($value['hourlycost']); ?>" data-currency-before="$" data-currency-after="" class="carrental_categories">
                    <?php echo esc_attr($value['name']); ?>

                    <?php if($value['applicable'] == 'per_day'){ ?>
                        <span class="pull-right show_if_day"><?php echo wc_price($value['cost']); ?><span><?php _e(' - Per Day','redq-rental'); ?></span></span>
                        <span class="pull-right show_if_time"><?php echo wc_price($value['hourlycost']); ?><?php _e(' - Per Hour','redq-rental'); ?></span>
                    <?php }else{ ?>
                        <span class="pull-right"><?php echo wc_price($value['cost']); ?><?php _e(' - One Time','redq-rental'); ?></span>
                    <?php } ?>
                </label>
                <?php
                    woocommerce_quantity_input( array(
                        'input_name'  => 'cat_quantity',
                        'min_value'   => 1,
                        'max_value'   => $value['qty'] ? $value['qty'] : 1
                    ) );
                ?>
            </div>
        <?php } ?>
    </div>
    <?php else : ?>
        <?php
        $labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('categories') );
        $labels = $labels['labels'];
    ?>
    <div class="payable-categories rnb-component-wrapper" style="display: none;">
        <h5><?php echo esc_attr($labels['categories']); ?></h5>
    </div>
<?php endif; ?>


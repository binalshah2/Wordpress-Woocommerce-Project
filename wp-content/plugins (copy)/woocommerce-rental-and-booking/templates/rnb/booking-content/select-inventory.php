<?php
/**
     * Redq rental product add to cart
     *
     * @author      redqteam
     * @package     RedqTeam/Templates
     * @version     1.0.0
     * @since       1.0.0
     */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;
$product_id = $product->get_id();

$redq_product_inventory = get_post_meta($product_id, '_redq_product_inventory', true);

if (empty($redq_product_inventory)) {
    wc_add_notice(sprintf(__('You don\'t have any inventory with this product. This product in not bookable.', 'redq-rental')));
    return;
}

$inventories = get_posts(array(
    'post_type' => 'Inventory',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'posts_per_page' => -1,
    'post__in'  => $redq_product_inventory,
));

foreach ($inventories as $index => $inventory) {
    $inventories[$index]->quantity = get_post_meta($inventory->ID, 'quantity', true);
}

$labels = reddq_rental_get_settings(get_the_ID(), 'labels', array('inventory'));
$labels = $labels['labels'];
?>

<div class="payable-inventory rnb-component-wrapper rnb-select-wrapper" <?php echo (count($inventories) < 2) ? 'style="display:none"' : ''; ?>>
    <h5><?php echo esc_attr($labels['inventory']); ?></h5>
    <select class="redq-select-boxes rnb-select-box" id="booking_inventory" name="booking_inventory" data-post-id="<?php echo $product_id ?>">
        <?php foreach ($inventories as $inventory) : ?>
        <option value="<?php echo $inventory->ID ?>"><?php echo $inventory->post_title ?></option>
        <?php endforeach ?>
    </select>
</div> 
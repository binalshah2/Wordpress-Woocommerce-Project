<?php
    $displays = reddq_rental_get_settings( get_the_ID(), 'display' );
    $displays = $displays['display'];
?>
<?php if(isset($displays['quantity']) && $displays['quantity']!=='closed'): ?>
<div class="redq-quantity1 rnb-select-wrapper rnb-component-wrapper">
    <?php
        $labels = reddq_rental_get_settings( get_the_ID(), 'labels', array('quantity') );
        $labels = $labels['labels'];
    ?>
    <h5><?php echo esc_attr($labels['quantity']); ?></h5>
	<button type="button" class="moco_minus" >-</button>

    <input type="text" id="moco_inventory_quantity" name="inventory_quantity" class="inventory-qty" min="" max="" value="1" readonly>
	<button type="button" class="moco_plus" >+</button>

</div>
<?php endif; ?>
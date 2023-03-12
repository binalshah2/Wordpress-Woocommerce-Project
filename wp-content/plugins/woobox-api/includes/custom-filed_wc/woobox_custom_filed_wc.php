<?php 
function add_custom_field_in_bulk_edit_quick_edit(){    

   ?>
   <br/>
   <br/>
   
   <div class="container-fluid">
   <div class="row">
        <div class="col-lg-6">
            <?php 
                woocommerce_wp_checkbox( 
                    array(
                    'id' => 'woobox_deal_of_the_day',
                    'class' => 'alignleft',
                    'label' => 'Deal Of The Day'
                    )       
                   
                    
                );
            ?>
        </div>
         <div class="col-lg-6">
            <?php 
               woocommerce_wp_checkbox( array(
                'id' => 'woobox_suggested_for_you',
                'class' => '',
                'label' => 'Suggested for you'
                )
            );
            ?>
        </div>

        <div class="col-lg-6">
            <?php 
            woocommerce_wp_checkbox( array(
                'id' => 'woobox_offer',
                'class' => '',
                'label' => 'Offers'
                )
            );
            ?>
        </div>

        <div class="col-lg-6">
            <?php 
                 woocommerce_wp_checkbox( 
                array(
                'id' => 'woobox_you_may_like',
                'class' => '',
                'label' => 'You may like'
                ) 
            );
            ?>
        </div>
   </div>
</div>
<?php


    
}
add_action( 'woocommerce_product_quick_edit_end', 'add_custom_field_in_bulk_edit_quick_edit', 99 );


function save_custom_field_in_bulk_edit_quick_edit( $post_id, $post ){
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    if ( 'product' !== $post->post_type ) return $post_id;

    if (isset($_REQUEST['woobox_deal_of_the_day'])) {
        update_post_meta( $post_id, 'woobox_deal_of_the_day', $_REQUEST['woobox_deal_of_the_day'] );
    } else {
        delete_post_meta( $post_id, 'woobox_deal_of_the_day' );
    }
    if (isset($_REQUEST['woobox_suggested_for_you'])) {
        update_post_meta( $post_id, 'woobox_suggested_for_you', $_REQUEST['woobox_suggested_for_you'] );
    } else {
        delete_post_meta( $post_id, 'woobox_suggested_for_you' );
    }
    if (isset($_REQUEST['woobox_offer'])) {
        update_post_meta( $post_id, 'woobox_offer', $_REQUEST['woobox_offer'] );
    } else {
        delete_post_meta( $post_id, 'woobox_offer' );
    }
    if (isset($_REQUEST['woobox_you_may_like'])) {
        update_post_meta( $post_id, 'woobox_you_may_like', $_REQUEST['woobox_you_may_like'] );
    } else {
        delete_post_meta( $post_id, 'woobox_you_may_like' );
    }
   

}
add_action( 'woocommerce_product_bulk_and_quick_edit', 'save_custom_field_in_bulk_edit_quick_edit', 99, 2 );


?>
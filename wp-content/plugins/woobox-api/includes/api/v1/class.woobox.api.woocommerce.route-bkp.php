<?php

add_action('rest_api_init', function ()
{
    $namespace = 'woobox-api';
    $base = 'woocommerce';

    register_rest_route($namespace . '/api/v1/' . $base, 'get-category/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_category'
    ));

    register_rest_route($namespace . '/api/v1/' . $base, 'get-product/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_product'
    ));

    register_rest_route($namespace . '/api/v1/' . $base, 'get-sub-category/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_sub_category'
    ));

    register_rest_route($namespace . '/api/v1/' . $base, 'get-product-attribute/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_product_attribute'
    ));

    register_rest_route($namespace . '/api/v1/' . $base, 'get-single-product/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_single_product'
    ));

    register_rest_route($namespace . '/api/v1/' . $base, 'get-featured-product/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_featured_product'
    ));

    register_rest_route($namespace . '/api/v1/' . $base, 'get-offer-product/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_offer_product'
    ));

    register_rest_route($namespace . '/api/v1/' . $base, 'get-search-product/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_search_product'
    ));

    register_rest_route($namespace . '/api/v1/' . $base, 'place-order/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_place_order'
    ));
    register_rest_route($namespace . '/api/v1/' . $base, 'delete-review/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_delete_review'
    ));

    register_rest_route($namespace . '/api/v1/' . $base, 'get-dashboard/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_dashboard'
    )); 

    

});


//    'tax_query'      => array( array(
//         'taxonomy'        => 'pa_color',
//         'field'           => 'slug',
//         'terms'           =>  array('blue', 'red', 'green'),
//         'operator'        => 'IN',
//     ) )
// ) );



function woobox_get_dashboard($request)
{
    $masterarray = array();
    $array = array();

    $args = array(
   'post_type'      => array('product'),
   'post_status'    => 'publish',
   'posts_per_page' => 5,
   'meta_query'     => array(

                            array(
                            'key' => 'woobox_suggested_for_you',
                            'value' => array('yes'),
                            'compare' => 'IN',
                            ) 
                        )
   
);

    

    $wp_query = new WP_Query($args);

    $total = $wp_query->found_posts;
    $num_pages = 1;
    $num_pages = $wp_query->max_num_pages;    
    

    while ($wp_query->have_posts())
    {
        $wp_query->the_post();
        $array = woobox_get_product_helper(get_the_ID(),$num_pages);

        array_push($masterarray, $array);

        $response = new WP_REST_Response($masterarray);

        $response->set_status(200);
        return $response;
    }
        
   
}

function woobox_delete_review($request)
{
    global $wpdb;

    $parameters = $request->get_params();

    $header = $request->get_headers();

    if (empty($header['token'][0]))
    {
        return new WP_Error('token_missing', 'Token Required', array(
            'status' => 404
        ));
    }

    $validate = new Woobox_Api_Authentication();
    $response = $validate->woobox_validate_token($header['token'][0]);
    $userid = $header['id'][0];

    $res = (array)  json_decode($response['body'], true);

    if ($res['data']['status'] != 200)
    {
        return $res;
    }
    $cart_items = $wpdb->get_results("DELETE FROM 
    {$wpdb->prefix}comments 
        where 
        user_id=" . $userid . " AND comment_ID =" . $parameters['review_id'] . "", OBJECT);

    $response = new WP_REST_Response(
                array(
                    "code" => "success",
                    "message" => "Review  Deleted",
                    "data" => array(
                    "status" => 200
                    )
                )
            );

    return $response;
}

function woobox_place_order($request)
{
    $parameters = $request->get_params(); 

    $header = $request->get_headers();   
    

    
    $transaction_id = $parameters['transaction_id'];

    $payment_gateway_id = $parameters['payment_gateway_id'];

    
    $payment_gateway = WC()->payment_gateways->payment_gateways()[$payment_gateway_id];

    $order = wc_get_order($parameters['order_id']);

    $order->set_payment_method($payment_gateway->id);    
    $order->set_payment_method_title($payment_gateway->title);    
    $order->set_transaction_id($transaction_id);    
    $order->set_date_paid(date('Y-m-d'));    
    $order->get_payment_method();
    $order->update_status( 'processing' );

    $order_data = $order->get_data();

    $masterarray = array();
    array_push($masterarray,$order_data);
    

    $response = new WP_REST_Response($masterarray);
    $response->set_status(200);
    

    return $response;



    
    

}



function woobox_get_product($request)
{
    global $product;

    $parameters = $request->get_params();
    

    $array = array();
    $masterarray = array();

    $meta = array();
    $dummymeta = array();
    $taxargs = array();
    $tax_query = array();
    $args = array();
    $page = 1;
    

    if (!empty($parameters))
    {
        foreach ($parameters as $key => $data)
        {

            if ($key == "price")
            {
                $meta['key'] = '_price';
                $meta['value'] = $parameters['price'];
                $meta['compare'] = 'BETWEEN';
                $meta['type'] = 'NUMERIC';

                array_push($dummymeta, $meta);

            }
            if ($key == "category")
            {
                $tax_query['taxonomy'] = 'product_cat';
                $tax_query['field'] = 'term_id';
                $tax_query['terms'] = $parameters[$key];
                $tax_query['operator'] = 'IN';
                array_push($taxargs, $tax_query);
            }
            if ($key == "brand")
            {
                $tax_query['taxonomy'] = 'pa_brand';
                $tax_query['field'] = 'slug';
                $tax_query['terms'] = $parameters[$key];
                $tax_query['operator'] = 'IN';
                array_push($taxargs, $tax_query);

            }

            if ($key == "size")
            {
                $tax_query['taxonomy'] = 'pa_size';
                $tax_query['field'] = 'slug';
                $tax_query['terms'] = $parameters[$key];
                $tax_query['operator'] = 'IN';
                array_push($taxargs, $tax_query);

            }

            if ($key == "color")
            {
                $tax_query['taxonomy'] = 'pa_color';
                $tax_query['field'] = 'slug';
                $tax_query['terms'] = $parameters[$key];
                $tax_query['operator'] = 'IN';
                array_push($taxargs, $tax_query);

            }



            if ($key == "page")
            {
                $page = $parameters[$key];

            }

        }
    }

    $args['post_type'] = 'product';
    $args['post_status'] = 'publish';
    $args['posts_per_page'] = 10;
    $args['paged'] = $page;

    if (!empty($meta))
    {
        $args['meta_query'] = $dummymeta;
    }
    if (!empty($taxargs))
    {
        $args['tax_query'] = $taxargs;
    }

    $wp_query = new WP_Query($args);

    $total = $wp_query->found_posts;
    $num_pages = 1;
    $num_pages = $wp_query->max_num_pages;    
    

    if ($total == 0)
    {
        return new WP_Error('empty_product', 'Sorry! No Product Available', array(
            'status' => 404
        ));
    }

    $product = null;
    $i = 1;
    while ($wp_query->have_posts())
    {
        $wp_query->the_post();
        $array = woobox_get_product_helper(get_the_ID(),$num_pages,$i);
        array_push($masterarray, $array);
        $i++;
    }

    $response = new WP_REST_Response($masterarray);

    $response->set_status(200);
    return $response;
}


function woobox_get_search_product($request)
{
    global $product;

    $parameters = $request->get_params();

    $array = array();
    $masterarray = array();

    $meta = array();
    $dummymeta = array();
    $taxargs = array();
    $tax_query = array();
    $args = array();
    $page = 1;
    $search_term = '';

    if (!empty($parameters))
    {
        foreach ($parameters as $key => $data)
        {

            if ($key == "page")
            {
                $page = $parameters[$key];

            }

            if ($key == "text")
            {
                $search_term = $parameters[$key];
                if (empty($search_term))
                {
                    return new WP_Error('empty_text', 'Please! Enter Product Name', array(
                        'status' => 404
                    ));
                }
            }

        }
    }

    // $args['post_type'] = 'product';
    // $args['post_status'] = 'publish';
    // $args['posts_per_page'] = 50;
    // $args['paged'] = $page;
    

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 50,
        'paged' => $page,
        's' => $search_term,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    );

    $wp_query = new WP_Query($args);

    $total = $wp_query->found_posts;
    $num_pages = 1;
    $num_pages = $wp_query->max_num_pages;    
    

    if ($total == 0)
    {
        return new WP_Error('empty_product', 'Sorry! No Product Available', array(
            'status' => 404
        ));
    }

    $product = null;
    $i = 1;
    while ($wp_query->have_posts())
    {
        $wp_query->the_post();
        $product = wc_get_product(get_the_ID());

        $array['num_pages'] = $num_pages;
        $array['srno'] = $i;
        $array['pro_id'] = $product->get_id();
        $array['categories'] = $product->get_category_ids();

        $array['name'] = $product->get_name();

        $array['type'] = $product->get_type();
        $array['slug'] = $product->get_slug();
        $array['date_created'] = $product->get_date_created();
        $array['date_modified'] = $product->get_date_modified();
        $array['status'] = $product->get_status();
        $array['featured'] = $product->get_featured();
        $array['catalog_visibility'] = $product->get_catalog_visibility();
        $array['description'] = $product->get_description();
        $array['short_description'] = $product->get_short_description();
        $array['sku'] = $product->get_sku();

        $array['virtual'] = $product->get_virtual();
        $array['permalink'] = get_permalink($product->get_id());
        $array['price'] = $product->get_price();
        $array['regular_price'] = $product->get_regular_price();
        $array['sale_price'] = $product->get_sale_price();
        $array['brand'] = $product->get_attribute('brand');
        $array['size'] = $product->get_attribute('size');
        $array['color'] = $product->get_attribute('color');

        $array['tax_status'] = $product->get_tax_status();
        $array['tax_class'] = $product->get_tax_class();
        $array['manage_stock'] = $product->get_manage_stock();
        $array['stock_quantity'] = $product->get_stock_quantity();
        $array['stock_status'] = $product->get_stock_status();
        $array['backorders'] = $product->get_backorders();
        $array['sold_individually'] = $product->get_sold_individually();
        $array['get_purchase_note'] = $product->get_purchase_note();
        $array['shipping_class_id'] = $product->get_shipping_class_id();

        $array['weight'] = $product->get_weight();
        $array['length'] = $product->get_length();
        $array['width'] = $product->get_width();
        $array['height'] = $product->get_height();
        $array['dimensions'] = html_entity_decode($product->get_dimensions());

        // Get Linked Products
        $array['upsell_ids'] = $product->get_upsell_ids();
        $array['cross_sell_ids'] = $product->get_cross_sell_ids();
        $array['parent_id'] = $product->get_parent_id();

        $array['reviews_allowed'] = $product->get_reviews_allowed();
        $array['rating_counts'] = $product->get_rating_counts();
        $array['average_rating'] = $product->get_average_rating();
        $array['review_count'] = $product->get_review_count();

        $thumb = wp_get_attachment_image_src($product->get_image_id() , "thumbnail");
        $full = wp_get_attachment_image_src($product->get_image_id() , "full");
        $array['thumbnail'] = $thumb[0];
        $array['full'] = $full[0];
        $gallery = array();
        foreach ($product->get_gallery_image_ids() as $img_id)
        {
            $g = wp_get_attachment_image_src($img_id, "full");
            $gallery[] = $g[0];
        }
        $array['gallery'] = $gallery;
        $gallery = array();

        array_push($masterarray, $array);
        $i++;
    }

    $response = new WP_REST_Response($masterarray);

    $response->set_status(200);
    return $response;
}

function woobox_get_offer_product($request)
{
    global $product;

    $parameters = $request->get_params();

    $array = array();
    $masterarray = array();

    $meta = array();
    $dummymeta = array();
    $taxargs = array();
    $tax_query = array();
    $args = array();
    $page = 1;

    $category = get_term_by('slug', 'offers', 'product_cat');
    $cat_id = $category->term_id;

    if (!empty($cat_id))
    {
        $tax_query['taxonomy'] = 'product_cat';
        $tax_query['field'] = 'term_id';
        $tax_query['terms'] = $cat_id;
        $tax_query['operator'] = 'IN';
        array_push($taxargs, $tax_query);
    }

    if (!empty($parameters))
    {
        foreach ($parameters as $key => $data)
        {

            if ($key == "page")
            {
                $page = $parameters[$key];

            }

        }
    }

    $args['post_type'] = 'product';
    $args['post_status'] = 'publish';
    $args['posts_per_page'] = 10;
    $args['paged'] = $page;

    if (!empty($taxargs))
    {
        $args['tax_query'] = $taxargs;
    }

    $wp_query = new WP_Query($args);

    $total = $wp_query->found_posts;
    $num_pages = 1;
    $num_pages = $wp_query->max_num_pages;    
    

    if ($total == 0)
    {
        return new WP_Error('empty_product', 'Sorry! No Product Available', array(
            'status' => 404
        ));
    }

    $product = null;
    $i = 1;
    while ($wp_query->have_posts())
    {
        $wp_query->the_post();
        $product = wc_get_product(get_the_ID());
        $array['num_pages'] = $num_pages;
        $array['srno'] = $i;
        $array['pro_id'] = $product->get_id();
        $array['categories'] = $product->get_category_ids();

        $array['name'] = $product->get_name();

        $array['type'] = $product->get_type();
        $array['slug'] = $product->get_slug();
        $array['date_created'] = $product->get_date_created();
        $array['date_modified'] = $product->get_date_modified();
        $array['status'] = $product->get_status();
        $array['featured'] = $product->get_featured();
        $array['catalog_visibility'] = $product->get_catalog_visibility();
        $array['description'] = $product->get_description();
        $array['short_description'] = $product->get_short_description();
        $array['sku'] = $product->get_sku();

        $array['virtual'] = $product->get_virtual();
        $array['permalink'] = get_permalink($product->get_id());
        $array['price'] = $product->get_price();
        $array['regular_price'] = $product->get_regular_price();
        $array['sale_price'] = $product->get_sale_price();
        $array['brand'] = $product->get_attribute('brand');
        $array['size'] = $product->get_attribute('size');
        $array['color'] = $product->get_attribute('color');

        $array['tax_status'] = $product->get_tax_status();
        $array['tax_class'] = $product->get_tax_class();
        $array['manage_stock'] = $product->get_manage_stock();
        $array['stock_quantity'] = $product->get_stock_quantity();
        $array['stock_status'] = $product->get_stock_status();
        $array['backorders'] = $product->get_backorders();
        $array['sold_individually'] = $product->get_sold_individually();
        $array['get_purchase_note'] = $product->get_purchase_note();
        $array['shipping_class_id'] = $product->get_shipping_class_id();

        $array['weight'] = $product->get_weight();
        $array['length'] = $product->get_length();
        $array['width'] = $product->get_width();
        $array['height'] = $product->get_height();
        $array['dimensions'] = html_entity_decode($product->get_dimensions());

        // Get Linked Products
        $array['upsell_ids'] = $product->get_upsell_ids();
        $array['cross_sell_ids'] = $product->get_cross_sell_ids();
        $array['parent_id'] = $product->get_parent_id();

        $array['reviews_allowed'] = $product->get_reviews_allowed();
        $array['rating_counts'] = $product->get_rating_counts();
        $array['average_rating'] = $product->get_average_rating();
        $array['review_count'] = $product->get_review_count();

        $thumb = wp_get_attachment_image_src($product->get_image_id() , "thumbnail");
        $full = wp_get_attachment_image_src($product->get_image_id() , "full");
        $array['thumbnail'] = $thumb[0];
        $array['full'] = $full[0];
        $gallery = array();
        foreach ($product->get_gallery_image_ids() as $img_id)
        {
            $g = wp_get_attachment_image_src($img_id, "full");
            $gallery[] = $g[0];
        }
        $array['gallery'] = $gallery;
        $gallery = array();

        array_push($masterarray, $array);
        $i++;
    }

    $response = new WP_REST_Response($masterarray);

    $response->set_status(200);
    return $response;
}

function woobox_get_featured_product($request)
{
    global $product;

    $parameters = $request->get_params();

    $array = array();
    $masterarray = array();

    $meta = array();
    $dummymeta = array();
    $taxargs = array();
    $tax_query = array();
    $args = array();
    $page = 1;

    $tax_query['taxonomy'] = 'product_visibility';
    $tax_query['field'] = 'name';
    $tax_query['terms'] = 'featured';
    array_push($taxargs, $tax_query);  

    if (!empty($parameters))
    {
        foreach ($parameters as $key => $data)
        {

            

            if ($key == "price")
            {
                $meta['key'] = '_price';
                $meta['value'] = $parameters['price'];
                $meta['compare'] = 'BETWEEN';
                $meta['type'] = 'NUMERIC';
                array_push($dummymeta, $meta);

            }
            if ($key == "category")
            {
                $tax_query['taxonomy'] = 'product_cat';
                $tax_query['field'] = 'term_id';
                $tax_query['terms'] = $parameters[$key];
                $tax_query['operator'] = 'IN';
                array_push($taxargs, $tax_query);
            }
            if ($key == "brand")
            {
                $tax_query['taxonomy'] = 'pa_brand';
                $tax_query['field'] = 'slug';
                $tax_query['terms'] = $parameters[$key];
                $tax_query['operator'] = 'IN';
                array_push($taxargs, $tax_query);

            }

            if ($key == "size")
            {
                $tax_query['taxonomy'] = 'pa_size';
                $tax_query['field'] = 'slug';
                $tax_query['terms'] = $parameters[$key];
                $tax_query['operator'] = 'IN';
                array_push($taxargs, $tax_query);

            }

            if ($key == "color")
            {
                $tax_query['taxonomy'] = 'pa_color';
                $tax_query['field'] = 'slug';
                $tax_query['terms'] = $parameters[$key];
                $tax_query['operator'] = 'IN';
                array_push($taxargs, $tax_query);

            }  

                    

            if ($key == "page")
            {
                $page = $parameters[$key];

            }

        }
    }

    $args['post_type'] = 'product';
    $args['post_status'] = 'publish';
    $args['posts_per_page'] = 10;
    $args['paged'] = $page;

    if (!empty($meta))
    {
        $args['meta_query'] = $dummymeta;
    }
    if (!empty($taxargs))
    {
        $args['tax_query'] = $taxargs;
    }



    $wp_query = new WP_Query($args);

    $total = $wp_query->found_posts;
    $num_pages = 1;
    $num_pages = $wp_query->max_num_pages;    

    if ($total == 0)
    {
        return new WP_Error('empty_product', 'Sorry! No Product Available', array(
            'status' => 404
        ));
    }

    $product = null;
    $i = 1;
    while ($wp_query->have_posts())
    {
        $wp_query->the_post();
        $product = wc_get_product(get_the_ID());
        $array['num_pages'] = $num_pages;
        $array['srno'] = $i;
        $array['pro_id'] = $product->get_id();
        $array['categories'] = $product->get_category_ids();

        $array['name'] = $product->get_name();

        $array['type'] = $product->get_type();
        $array['slug'] = $product->get_slug();
        $array['date_created'] = $product->get_date_created();
        $array['date_modified'] = $product->get_date_modified();
        $array['status'] = $product->get_status();
        $array['featured'] = $product->get_featured();
        $array['catalog_visibility'] = $product->get_catalog_visibility();
        $array['description'] = $product->get_description();
        $array['short_description'] = $product->get_short_description();
        $array['sku'] = $product->get_sku();

        $array['virtual'] = $product->get_virtual();
        $array['permalink'] = get_permalink($product->get_id());
        $array['price'] = $product->get_price();
        $array['regular_price'] = $product->get_regular_price();
        $array['sale_price'] = $product->get_sale_price();
        $array['brand'] = $product->get_attribute('brand');
        $array['size'] = $product->get_attribute('size');
        $array['color'] = $product->get_attribute('color');
        
        $array['stock_quantity'] = $product->get_stock_quantity();
        $array['tax_status'] = $product->get_tax_status();
        $array['tax_class'] = $product->get_tax_class();
        $array['manage_stock'] = $product->get_manage_stock();
        
        $array['stock_status'] = $product->get_stock_status();
        $array['backorders'] = $product->get_backorders();
        $array['sold_individually'] = $product->get_sold_individually();
        $array['get_purchase_note'] = $product->get_purchase_note();
        $array['shipping_class_id'] = $product->get_shipping_class_id();

        $array['weight'] = $product->get_weight();
        $array['length'] = $product->get_length();
        $array['width'] = $product->get_width();
        $array['height'] = $product->get_height();
        $array['dimensions'] = html_entity_decode($product->get_dimensions());

        // Get Linked Products
        $array['upsell_ids'] = $product->get_upsell_ids();
        $array['cross_sell_ids'] = $product->get_cross_sell_ids();
        $array['parent_id'] = $product->get_parent_id();

        $array['reviews_allowed'] = $product->get_reviews_allowed();
        $array['rating_counts'] = $product->get_rating_counts();
        $array['average_rating'] = $product->get_average_rating();
        $array['review_count'] = $product->get_review_count();

        $thumb = wp_get_attachment_image_src($product->get_image_id() , "thumbnail");
        $full = wp_get_attachment_image_src($product->get_image_id() , "full");
        $array['thumbnail'] = $thumb[0];
        $array['full'] = $full[0];
        $gallery = array();
        foreach ($product->get_gallery_image_ids() as $img_id)
        {
            $g = wp_get_attachment_image_src($img_id, "full");
            $gallery[] = $g[0];
        }
        $array['gallery'] = $gallery;
        $gallery = array();

        array_push($masterarray, $array);
        $i++;
    }

    $response = new WP_REST_Response($masterarray);

    $response->set_status(200);
    return $response;
}

function woobox_get_single_product($request)
{

    $parameters = $request->get_params();

    $product = wc_get_product($parameters['pro_id']);

    $array['pro_id'] = $product->get_id();
    $array['categories'] = $product->get_category_ids();

    $array['name'] = $product->get_name();

    $array['type'] = $product->get_type();
    $array['slug'] = $product->get_slug();
    $array['date_created'] = $product->get_date_created();
    $array['date_modified'] = $product->get_date_modified();
    $array['status'] = $product->get_status();
    $array['featured'] = $product->get_featured();
    $array['catalog_visibility'] = $product->get_catalog_visibility();
    $array['description'] = $product->get_description();
    $array['short_description'] = $product->get_short_description();
    $array['sku'] = $product->get_sku();

    $array['virtual'] = $product->get_virtual();
    $array['permalink'] = get_permalink($product->get_id());
    $array['price'] = $product->get_price();
    $array['regular_price'] = $product->get_regular_price();
    $array['sale_price'] = $product->get_sale_price();
    $array['brand'] = $product->get_attribute('brand');
    $array['size'] = $product->get_attribute('size');
    $array['color'] = $product->get_attribute('color');

    $array['tax_status'] = $product->get_tax_status();
    $array['tax_class'] = $product->get_tax_class();
    $array['manage_stock'] = $product->get_manage_stock();
    $array['stock_quantity'] = $product->get_stock_quantity();
    $array['stock_status'] = $product->get_stock_status();
    $array['backorders'] = $product->get_backorders();
    $array['sold_individually'] = $product->get_sold_individually();
    $array['get_purchase_note'] = $product->get_purchase_note();
    $array['shipping_class_id'] = $product->get_shipping_class_id();

    $array['weight'] = $product->get_weight();
    $array['length'] = $product->get_length();
    $array['width'] = $product->get_width();
    $array['height'] = $product->get_height();
    $array['dimensions'] = html_entity_decode($product->get_dimensions());

    // Get Linked Products
    $array['upsell_ids'] = $product->get_upsell_ids();
    $array['cross_sell_ids'] = $product->get_cross_sell_ids();
    $array['parent_id'] = $product->get_parent_id();

    $array['reviews_allowed'] = $product->get_reviews_allowed();
    $array['rating_counts'] = $product->get_rating_counts();
    $array['average_rating'] = $product->get_average_rating();
    $array['review_count'] = $product->get_review_count();

    $thumb = wp_get_attachment_image_src($product->get_image_id() , "thumbnail");
    $full = wp_get_attachment_image_src($product->get_image_id() , "full");
    $array['thumbnail'] = $thumb[0];
    $array['full'] = $full[0];
    $gallery = array();
    foreach ($product->get_gallery_image_ids() as $img_id)
    {
        $g = wp_get_attachment_image_src($img_id, "full");
        $gallery[] = $g[0];
    }
    $array['gallery'] = $gallery;
    $gallery = array();

    $response = new WP_REST_Response($array);

    $response->set_status(200);
    return $response;
}

function woobox_get_category($request)
{
    $taxonomy = 'product_cat';
    $orderby = 'name';
    $show_count = 0; // 1 for yes, 0 for no
    $pad_counts = 0; // 1 for yes, 0 for no
    $hierarchical = 1; // 1 for yes, 0 for no
    $title = '';
    $empty = 0;

    $args = array(
        'taxonomy' => $taxonomy,
        'orderby' => $orderby,
        'show_count' => $show_count,
        'pad_counts' => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li' => $title,
        'hide_empty' => $empty,
        'parent' => 0
    );
    $all_categories = get_categories($args);

    $a = array_map('get_category_child',$all_categories);

    $response = new WP_REST_Response($a);
    $response->set_status(200);
    return $response;

}

function woobox_get_product_attribute($request)
{

    $masterarray = array();
    $parameters = $request->get_params();

    $taxonomy = 'product_cat';
    $orderby = 'name';
    $show_count = 0; // 1 for yes, 0 for no
    $pad_counts = 0; // 1 for yes, 0 for no
    $hierarchical = 1; // 1 for yes, 0 for no
    $title = '';
    $empty = 0;

    $args = array(
        'taxonomy' => $taxonomy,
        'orderby' => $orderby,
        'show_count' => $show_count,
        'pad_counts' => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li' => $title,
        'hide_empty' => $empty,
        'parent' => 0
    );
    $all_categories = get_categories($args);

    $all_categories = get_categories($args);

    $masterarray['categories'] = $all_categories;

    $size = array();
    if (taxonomy_exists('pa_size'))
    {
        $size = get_terms(array(
            'taxonomy' => 'pa_size',
            'hide_empty' => false,
        ));

    }

    $masterarray['sizes'] = $size;

    $brand = array();

    if (taxonomy_exists('pa_brand'))
    {
        $brand = get_terms(array(
            'taxonomy' => 'pa_brand',
            'hide_empty' => false,
        ));
    }

    $masterarray['brands'] = $brand;

    $color = array();

    if (taxonomy_exists('pa_color'))
    {
        $color = get_terms(array(
            'taxonomy' => 'pa_color',
            'hide_empty' => false,
        ));

    }

    $masterarray['colors'] = $color;

    $response = new WP_REST_Response($masterarray);
    $response->set_status(200);
    return $response;

}

function get_category_child($arr)
{
    $a = (array) $arr;
    $child_terms_ids = get_term_children( $a['term_id'], 'product_cat' );
    $a['subcategory'] = $child_terms_ids;
    //print_r($a);
    return $a;
}

function woobox_get_sub_category($request)
{

    $parameters = $request->get_params();

    $taxonomy = 'product_cat';
    $orderby = 'name';
    $show_count = 0; // 1 for yes, 0 for no
    $pad_counts = 0; // 1 for yes, 0 for no
    $hierarchical = 1; // 1 for yes, 0 for no
    $title = '';
    $empty = 0;

    

    $args = array(
        'taxonomy' => $taxonomy,
        'orderby' => $orderby,
        'show_count' => $show_count,
        'pad_counts' => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li' => $title,
        'child_of' => $parameters['cat_id'],
        'hide_empty' => $empty,
        'parent' => $parameters['cat_id']
    );

    $all_categories = get_categories($args);

    $a = array_map('get_category_child',$all_categories);
    

    $response = new WP_REST_Response($a);
    $response->set_status(200);
    return $response;

}



?>

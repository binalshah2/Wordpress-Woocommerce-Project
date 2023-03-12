<?php

add_action('rest_api_init', function ()
{
    $namespace = 'woobox-api';
    $base = 'customer';

    register_rest_route($namespace . '/api/v1/' . $base, 'social_login/', array(
        'methods' => WP_REST_Server::ALLMETHODS,
        'callback' => 'woobox_get_customer_by_social'
    ));

});

function woobox_get_customer_by_social($request)
{
   
    $header = $request->get_headers();
    $parameters = $request->get_params();
    $email = $parameters['email'];
    $password = $parameters['accessToken'];

    $user = get_user_by('email', $email);
    $res = '';

    $address = array(
        'first_name' => $parameters['firstName'],
        'last_name'  => $parameters['lastName'],            
        'email'      => $email
        
    );
    
    if (!$user) 
    {
        
        $user = wp_create_user( $email, $password, $email );
        update_user_meta( $user, "billing_first_name", $address['first_name'] );
        update_user_meta( $user, "billing_last_name", $address['last_name']);
        update_user_meta( $user, "billing_email", $address['email'] );

        update_user_meta( $user, "shipping_first_name", $address['first_name'] );
        update_user_meta( $user, "shipping_last_name", $address['last_name']);

        update_user_meta( $user, 'first_name', trim( $address['first_name'] ) );
        update_user_meta( $user, 'last_name', trim( $address['last_name'] ) );

        $u = new WP_User( $user);
        $u->set_role( 'customer' );
        $validate = new Woobox_Api_Authentication();
        
        $res = $validate->woobox_validate_social("username=".$email."&password=".$password);

    }
    else
    {
        $validate = new Woobox_Api_Authentication();
        wp_set_password( $password, $user->ID);

        $u = new WP_User( $user);
        $u->set_role( 'customer' );

        $res = $validate->woobox_validate_social("username=".$email."&password=".$password);
        
    }
    $response = new WP_REST_Response(json_decode($res,true));
    $response->set_status(200);

    return $response;

}
?>
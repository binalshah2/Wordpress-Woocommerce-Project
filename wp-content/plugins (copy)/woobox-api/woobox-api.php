<?php
/*
Plugin Name: Woobox Api
Plugin URI:  http://www.iqonic.design
Description: Plugin Use For Custom Woccommerce Api Like Cart , Wishlist, Filter Product , Get Category.
Version:     1.0
Author:      Iqonic
Author URI:  http://www.iqonic.design
License:     GPL2
License URI: Licence URl
Text Domain: woobox
*/

if (!defined('ABSPATH'))
{
    exit;
}

if (!defined('JWT_AUTH_CORS_ENABLE'))
{
    define('JWT_AUTH_CORS_ENABLE', true);
}

if (!defined('JWT_AUTH_SECRET_KEY'))
{
    define('JWT_AUTH_SECRET_KEY', AUTH_KEY);
}

if (!defined('WOO_DIR'))
{
    define('WOO_DIR', plugin_dir_path(__FILE__));
}

if (!defined('WOO_DIR_URI'))
{
    define('WOO_DIR_URI', plugin_dir_url(__FILE__));
}

include_once (ABSPATH . 'wp-includes/pluggable.php');

/**
 * The core JWT Authentication for WP-API class that is used to authorize api
 */

if (!class_exists('Jwt_Auth_Public'))
{
    require plugin_dir_path(__FILE__) . '/jwt-authentication-for-wp-rest-api/jwt-auth.php';
}

require plugin_dir_path(__FILE__) . '/includes/class.woobox.api.php';


function load_custom_wp_admin_style()
{
    wp_enqueue_script('oauth-signature', WOO_DIR_URI . 'assest/js/oauth-signature.js', array() , '1.0', true);
    wp_register_script('woobox-sample', WOO_DIR_URI . 'assest/js/sample.js', array() , '1.0', true);
    //wp_enqueue_style('woobox-sample', WOO_DIR_URI.'assest/js/sample.js',array(), '1.0', 'all');
    wp_localize_script('woobox-sample', 'request_token', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_script('woobox-sample');

    wp_enqueue_style('bootstrap', WOO_DIR_URI . 'assest/css/bootstrap.min.css', array() , '4.1.3', 'all');

}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');

if (!class_exists('ReduxFramework'))
{
    require_once (WOO_DIR . '/Redux/ReduxCore/framework.php');        
}


require_once (WOO_DIR . '/app-option/option-set.php');
require_once (WOO_DIR . '/custom_post/custom_post.php');



new Woobox_Api();

require_once(WOO_DIR . 'envato_setup/envato_setup.php');
register_activation_hook( __FILE__,  'envato_theme_setup_wizard' );
if ( class_exists( 'Envato_Theme_Setup_Wizard' ) ) {
    Envato_Theme_Setup_Wizard::get_instance();
}

add_filter( 'rest_authentication_errors', function(){
    wp_set_current_user( 1 ); // replace with the ID of a WP user with the authorization you want
}, 101 );
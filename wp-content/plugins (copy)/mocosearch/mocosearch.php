<?php
/*
Plugin Name: mocosearch
Description: Search Your Customers
Author: Raincreatives
*/

// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';

function createTable()
{
    
    
} 
register_activation_hook(__FILE__, 'createTable');


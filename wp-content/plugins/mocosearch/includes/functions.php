<?php
/*
 * Add my new menu to the Admin Control Panel
 */

// Hook the 'admin_menu' action hook, run the function named 'mfp_Add_My_Admin_Link()'
add_action( 'admin_menu', 'mocosearch' );


// Add a new top level menu link to the ACP
function mocosearch()
{
     add_menu_page(
        'Customers', // Title of the page
        'Customers', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'customers',
        'search'
    );

}

function search(){
       include ("search.php");
}

?>
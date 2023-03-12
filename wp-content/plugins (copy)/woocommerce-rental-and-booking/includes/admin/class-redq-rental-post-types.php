<?php

/**
 * Class WC_Redq_Rental_Post_Types
 *
 *
 * @author      RedQTeam
 * @category    Admin
 * @package     Userplace\Admin
 * @version     1.0.3
 * @since       1.0.3
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

class WC_Redq_Rental_Post_Types {

	public function __construct(){
		add_action( 'init', array( $this, 'redq_rental_register_post_types' ), 10, 1);
		add_action( 'save_post', array($this, 'redq_product_to_inventory_save_post'));
		add_action( 'save_post', array($this, 'redq_only_inventory_save_post'));
		add_action( 'add_meta_boxes', array($this, 'redq_register_meta_boxes'));
    add_action( 'pre_get_posts', array( $this, 'quote_pre_get_posts' ), 1 );
    add_filter( 'manage_request_quote_posts_columns', array($this, 'redq_columns_request_quote_head') );
    add_action( 'manage_request_quote_posts_custom_column', array($this, 'redq_columns_request_quote_content'), 10, 2);
    add_filter( 'page_row_actions', array($this, 'remove_row_actions'), 10, 2 );
    add_filter( 'trashed_post', array($this, 'redq_trash_post'), 10, 1 );
	}

  	//Remove Quick Edit from Row Actions
  	function remove_row_actions( $actions, $post ) {
	  	if ( $post->post_type == 'request_quote' && isset( $actions['inline hide-if-no-js'] ) )
	    	unset( $actions['inline hide-if-no-js'] );
	 	return $actions;
	}


	// Show All column Head
	function redq_columns_request_quote_head($defaults) {
		unset($defaults['title']);
		unset($defaults['date']);
		$defaults['quote']  = __('Quote', 'redq-rental');
		$defaults['status']  = __('Status', 'redq-rental');
		$defaults['product']  = __('Product', 'redq-rental');
		$defaults['email'] = __('Email', 'redq-rental');
		$defaults['date'] = __('Date', 'redq-rental');
		return $defaults;
	}


	// Show All corresponding value for each column
	function redq_columns_request_quote_content($column_name, $post_ID) {
		$order_quote_meta = json_decode( get_post_meta($post_ID, 'order_quote_meta', true), true );
		$forms = array();

		foreach ($order_quote_meta as $key => $meta) {
		  	if( array_key_exists('forms', $meta) ) {
		    	$forms = $meta['forms'];
		  	}
		}

		if ($column_name == 'quote') { ?>
			<p><a href="<?php get_admin_url() ?>post.php?post=<?php echo $post_ID ?>&amp;action=edit"><strong><?php echo '#'.$post_ID ?></strong></a> <?php esc_html_e('by', 'redq-rental' ) ?> <?php echo $forms['quote_first_name'] . ' ' . $forms['quote_last_name'] ?></p>
			<?php }

			if ($column_name == 'status') {
			  	echo ucfirst( substr( get_post($post_ID)->post_status, 6 ) );
			}
			if ($column_name == 'product') {
			  	$product_id = get_post_meta($post_ID, 'add-to-cart', true);
			  	$product_title = get_the_title($product_id);
			  	$product_url = get_the_permalink($product_id);?>
			  	<a href="<?php echo esc_url( $product_url ) ?>" target="_blank"><?php echo $product_title ?></a>
			<?php }
			if ($column_name == 'date') {
			  	echo get_post($post_ID)->date;
			}
			if ($column_name == 'email') {
			  	foreach ($order_quote_meta as $meta) {
				    if( isset( $meta['forms'] ) ) {
				      	$contacts = $meta['forms'];
				      	foreach ($contacts as $key => $value) {
				        	if($key == 'email'){?>
				          		<a href="mailto:<?php echo $value ?>"><?php echo $value ?></a>
				        <?php }
				      }
				    }
			  	}
		}
	}

	/**
	 * Hande Post Type, Taxonomy, Term Meta
	 *
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_rental_register_post_types(){

		$labels = array(
			'name'               => __( 'All Inventories', 'redq-rental' ),
			'singular_name'      => __( 'Inventory', 'redq-rental' ),
			'menu_name'          => __( 'Inventory', 'redq-rental' ),
			'name_admin_bar'     => __( 'Inventory', 'redq-rental' ),
			'add_new'            => __( 'Add New Inventory', 'redq-rental' ),
			'add_new_item'       => __( 'Add New Inventory', 'redq-rental' ),
			'new_item'           => __( 'New Inventory', 'redq-rental' ),
			'edit_item'          => __( 'Edit Inventory', 'redq-rental' ),
			'view_item'          => __( 'View Inventory', 'redq-rental' ),
			'all_items'          => __( 'All inventory', 'redq-rental' ),
			'search_items'       => __( 'Search inventory', 'redq-rental' ),
			'parent_item_colon'  => __( 'Parent inventory:', 'redq-rental' ),
			'not_found'          => __( 'No inventory found.', 'redq-rental' ),
			'not_found_in_trash' => __( 'No inventory found in Trash.', 'redq-rental' )
		);

		$args = array(
			'labels'             => $labels,
	    	'description'        => __( 'Description.', 'redq-rental' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'inventory' ),
			'capability_type'    => 'post',
			'menu_icon'			 => 'dashicons-image-filter',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 57,
			'supports'           => array( 'title', 'thumbnail' ),
			'capability_type' => 'post',
			'capabilities' => array(
				// 'create_posts' => 'do_not_allow', // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
			),
			'map_meta_cap' => true,
		);

		register_post_type( 'inventory', $args );

		$this->redq_resgister_inventory_taxonomies('rnb_categories', __('RnB Categories', 'redq-rental'), 'inventory');
		$this->redq_resgister_inventory_taxonomies('resource', __('Resources', 'redq-rental'), 'inventory');
		$this->redq_resgister_inventory_taxonomies('person', __('Person', 'redq-rental'), 'inventory');
		$this->redq_resgister_inventory_taxonomies('deposite', __('Deposite', 'redq-rental'), 'inventory');
		$this->redq_resgister_inventory_taxonomies('attributes', __('Attributes', 'redq-rental'), 'inventory');
		$this->redq_resgister_inventory_taxonomies('features', __('Features', 'redq-rental'), 'inventory');
		$this->redq_resgister_inventory_taxonomies('pickup_location', __('Pickup Location', 'redq-rental'), 'inventory');
		$this->redq_resgister_inventory_taxonomies('dropoff_location', __('Dropoff Location', 'redq-rental'), 'inventory');
		//$this->redq_resgister_inventory_taxonomies('car_company', __('Car Company', 'redq-rental'), 'product');

		$this->redq_create_all_inventroy_term_meta();


	    $labels = array(
			'name'               => _x( 'Quote Request', 'post type general name', 'redq-rental' ),
			'singular_name'      => _x( 'Quote Request', 'post type singular name', 'redq-rental' ),
			'menu_name'          => _x( 'Quote', 'admin menu', 'redq-rental' ),
			'name_admin_bar'     => _x( 'Quote Request', 'add new on admin bar', 'redq-rental' ),
			'add_new'            => _x( 'Add New', 'request_quote', 'redq-rental' ),
			'add_new_item'       => __( 'Add New Quote', 'redq-rental' ),
			'new_item'           => __( 'New Quote Request', 'redq-rental' ),
			'edit_item'          => __( 'Edit Quote Request', 'redq-rental' ),
			'view_item'          => __( 'View Quote Request', 'redq-rental' ),
			'all_items'          => __( 'All Quotes', 'redq-rental' ),
			'search_items'       => __( 'Search Quote', 'redq-rental' ),
			'parent_item_colon'  => __( 'Parent Quote:', 'redq-rental' ),
			'not_found'          => __( 'No Quote found.', 'redq-rental' ),
			'not_found_in_trash' => __( 'No Quote found in Trash.', 'redq-rental' )
	    );

	    $args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'redq-rental' ),
			'public'             => false,
			// 'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'request_quote' ),
			'capability_type'    => 'post',
			'menu_icon'          => 'dashicons-awards',
			'has_archive'        => false,
			'hierarchical'       => true,
			'menu_position'      => 57,
			'supports'           => array(''),
			'map_meta_cap'       => true, //After disabling new qoute capabilities if this is not set then row actions are disabled. So no edit or trash will be availabe.
			'capabilities' => array(
			    'create_posts' => false  //Removing Add new quote capabilities
			),
	    );

	    register_post_type( 'request_quote', $args );

	    RedQ_Rental_And_Bookings::register_post_status();

	}


	/**
	 * Create all term meta
	 *
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_create_all_inventroy_term_meta(){

		//Term meta for RnB Categories taxonomy

		$rnb_cat_qty = array(
				'title' => __( 'Quantity', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_rnb_cat_qty',
				'column_name' => __( 'Qty','redq-rental' ),
				'placeholder' => __( 'Categories Quantity', 'redq-rental' ),
				'required' => false,
			);
		$qty = $this->redq_register_inventory_text_term_meta('rnb_categories',$rnb_cat_qty);


		$rnb_cat_cost_payable = array(
				'title' => __( 'Choose Payable or Not', 'redq-rental' ),
				'type'  => 'select',
				'id'    => 'inventory_rnb_cat_payable_or_not',
				'column_name' => __( 'Pay', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'yes',
							'value' => esc_html__('Yes', 'redq-rental')
						),
					'1' => array(
							'key' => 'no',
							'value' => esc_html__('No', 'redq-rental')
						),

				),
			);
		$payable = $this->redq_register_inventory_select_term_meta('rnb_categories',$rnb_cat_cost_payable);

		$rnb_cat_cost_args = array(
				'title' => __( 'Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_rnb_cat_cost_termmeta',
				'column_name' => __( 'Cost','redq-rental' ),
				'placeholder' => __( 'Categories Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$rnb_categories_cost = $this->redq_register_inventory_text_term_meta('rnb_categories',$rnb_cat_cost_args);

		$rnb_cat_price_applicable = array(
				'title' => __( 'Price Applicable', 'redq-rental' ),
				'type'  => 'select',
				'id'    => 'inventory_rnb_cat_price_applicable_term_meta',
				'column_name' => __( 'Applied', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'one_time',
							'value' => esc_html__('One Time', 'redq-rental')
						),
					'1' => array(
							'key' => 'per_day',
							'value' => esc_html__('Per Day', 'redq-rental')
						),

				),
			);
		$rnb_cat_price_applicable = $this->redq_register_inventory_select_term_meta('rnb_categories',$rnb_cat_price_applicable);


		$rnb_cat_hourly_cost_args = array(
				'title' => __( 'Hourly Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_rnb_cat_hourly_cost_termmeta',
				'column_name' => __( 'H.Cost', 'redq-rental' ),
				'placeholder' => __( 'Hourly Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$rnb_cat_hourly_cost = $this->redq_register_inventory_text_term_meta('rnb_categories',$rnb_cat_hourly_cost_args);

		$item_clickable = array(
				'title' => __( 'Always Selected Or Not', 'redq-rental' ),
				'type'  => 'select',
				'id'    => 'inventory_rnb_cat_clickable_term_meta',
				'column_name' => __( 'Selected', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'yes',
							'value' => esc_html__('Yes', 'redq-rental')
						),
					'1' => array(
							'key' => 'no',
							'value' => esc_html__('No', 'redq-rental')
						),

				),

			);
		$item_clickable = $this->redq_register_inventory_select_term_meta('rnb_categories',$item_clickable);



		//Term meta for resouce taxonomy
		$resource_cost_args = array(
				'title' => __( 'Resource Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_resource_cost_termmeta',
				'column_name' => __( 'R.Cost','redq-rental' ),
				'placeholder' => __( 'Resource Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$resource_cost = $this->redq_register_inventory_text_term_meta('resource',$resource_cost_args);

		$price_applicable_args = array(
				'title' => __( 'Price Applicable', 'redq-rental' ),
				'type'  => 'select',
				'id'    => 'inventory_price_applicable_term_meta',
				'column_name' => __( 'Applicable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'one_time',
							'value' => 'One Time'
						),
					'1' => array(
							'key' => 'per_day',
							'value' => 'Per Day'
						),

				),
			);
		$price_applicable = $this->redq_register_inventory_select_term_meta('resource',$price_applicable_args);


		$hourly_cost_args = array(
				'title' => __( 'Hourly Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_hourly_cost_termmeta',
				'column_name' => __( 'H.Cost', 'redq-rental' ),
				'placeholder' => __( 'Hourly Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$hourly_cost = $this->redq_register_inventory_text_term_meta('resource',$hourly_cost_args);


		//Term meta for person taxonomy
		$payable_person_args = array(
				'title' => __( 'Choose payable or not', 'redq-rental' ),
				'type'  => 'select',
				'id'    => 'inventory_person_payable_or_not',
				'column_name' => __( 'Payable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'yes',
							'value' => esc_html__('Yes', 'redq-rental')
						),
					'1' => array(
							'key' => 'no',
							'value' => esc_html__('No', 'redq-rental')
						),

				),
			);
		$payable_person = $this->redq_register_inventory_select_term_meta('person',$payable_person_args);

		$person_type_args = array(
				'title' => __( 'Choose Person Type', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_person_type',
				'column_name' => __( 'P.Type', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'none',
							'value' => esc_html__('None', 'redq-rental')
						),
					'1' => array(
							'key' => 'adult',
							'value' => esc_html__('Adult', 'redq-rental')
						),
					'2' => array(
							'key' => 'child',
							'value' => esc_html__('Child', 'redq-rental')
						),

				),
			);
		$person_type = $this->redq_register_inventory_select_term_meta('person',$person_type_args);



		$person_cost_args = array(
				'title' => __( 'Person Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_person_cost_termmeta',
				'column_name' => __( 'P.Cost', 'redq-rental' ),
				'placeholder' => __( 'Person Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$person_cost = $this->redq_register_inventory_text_term_meta('person',$person_cost_args);

		$person_price_applicable_args = array(
				'title' => __( 'Price Applicable', 'redq-rental' ),
				'type'  => 'select',
				'id'    => 'inventory_person_price_applicable_term_meta',
				'column_name' => __( 'Applicable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'one_time',
							'value' => 'One Time'
						),
					'1' => array(
							'key' => 'per_day',
							'value' => 'Per Day'
						),

				),
			);
		$person_price_applicable = $this->redq_register_inventory_select_term_meta('person',$person_price_applicable_args);

		$hourly_perosn_cost_args = array(
				'title' => __( 'Hourly Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_peroson_hourly_cost_termmeta',
				'column_name' => __( 'H.Cost', 'redq-rental' ),
				'placeholder' => __( 'Hourly Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$hourly_person_cost = $this->redq_register_inventory_text_term_meta('person',$hourly_perosn_cost_args);



		//Term meta for securtity deposit taxonomy
		$security_desposite_cost_args = array(
				'title' => __( 'Security Deposite Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_sd_cost_termmeta',
				'column_name' => __( 'S.D.Cost', 'redq-rental' ),
				'placeholder' => __( 'Security Deposite Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$security_desposite_cost = $this->redq_register_inventory_text_term_meta('deposite',$security_desposite_cost_args);

		$price_applicable_args = array(
				'title' => __( 'Price Applicable', 'redq-rental' ),
				'type'  => 'select',
				'id'    => 'inventory_sd_price_applicable_term_meta',
				'column_name' => __( 'Applicable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'one_time',
							'value' => 'One Time'
						),
					'1' => array(
							'key' => 'per_day',
							'value' => 'Per Day'
						),

				),

			);
		$price_applicable = $this->redq_register_inventory_select_term_meta('deposite',$price_applicable_args);

		$hourly_cost_args = array(
				'title' => __( 'Hourly Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_sd_hourly_cost_termmeta',
				'column_name' => __( 'H.Cost', 'redq-rental' ),
				'placeholder' => __( 'Hourly Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$hourly_cost = $this->redq_register_inventory_text_term_meta('deposite',$hourly_cost_args);



		$price_clickable_args = array(
				'title' => __( 'Security Deposite Clickable', 'redq-rental' ),
				'type'  => 'select',
				'id'    => 'inventory_sd_price_clickable_term_meta',
				'column_name' => __( 'Clickable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'yes',
							'value' => 'Yes'
						),
					'1' => array(
							'key' => 'no',
							'value' => 'No'
						),

				),

			);
		$price_clickable = $this->redq_register_inventory_select_term_meta('deposite',$price_clickable_args);



		//Term meta for pickup location taxonomy
		$pickup_loc_args = array(
				'title' => __( 'Pickup Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_pickup_cost_termmeta',
				'column_name' => __( 'Cost', 'redq-rental' ),
				'placeholder' => __( 'Pickup Location Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$security_desposite_cost = $this->redq_register_inventory_text_term_meta('pickup_location',$pickup_loc_args);


		$pickup_loc_lat = array(
				'title' => __( 'Latitude', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_pickup_location_lat',
				'column_name' => __( 'Latitude', 'redq-rental' ),
				'placeholder' => __( 'Latitude', 'redq-rental' ),
				'required' => false,
			);
		$pickup_loc_lat = $this->redq_register_inventory_text_term_meta('pickup_location',$pickup_loc_lat);

		$pickup_loc_lng = array(
				'title' => __( 'Longitude', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_pickup_location_lng',
				'column_name' => __( 'Longitude', 'redq-rental' ),
				'placeholder' => __( 'Longitude', 'redq-rental' ),
				'required' => false,
			);
		$pickup_loc_lng = $this->redq_register_inventory_text_term_meta('pickup_location',$pickup_loc_lng);



		//Term meta for dropoff location taxonomy
		$dropoff_locaiton_args = array(
				'title' => __( 'Dropoff Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_dropoff_cost_termmeta',
				'column_name' => __( 'Cost', 'redq-rental' ),
				'placeholder' => __( 'Dropoff Location Cost', 'redq-rental' ),
				'text_type' => 'price',
				'required' => false,
			);
		$security_desposite_cost = $this->redq_register_inventory_text_term_meta('dropoff_location',$dropoff_locaiton_args);


		$dropoff_loc_lat = array(
				'title' => __( 'Latitude', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_dropoff_location_lat',
				'column_name' => __( 'Latitude', 'redq-rental' ),
				'placeholder' => __( 'Latitude', 'redq-rental' ),
				'required' => false,
			);
		$dropoff_loc_lat = $this->redq_register_inventory_text_term_meta('dropoff_location',$dropoff_loc_lat);

		$dropoff_loc_lng = array(
				'title' => __( 'Longitude', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_dropoff_location_lng',
				'column_name' => __( 'Longitude', 'redq-rental' ),
				'placeholder' => __( 'Longitude', 'redq-rental' ),
				'required' => false,
			);
		$dropoff_loc_lng = $this->redq_register_inventory_text_term_meta('dropoff_location',$dropoff_loc_lng);


		//Term meta for attributes taxonomy
		$attributes_name_args = array(
			'title' => __( 'Attribute Name', 'redq-rental' ),
			'type'  => 'text',
			'id'    => 'inventory_attribute_name',
			'column_name' => __( 'A.Name', 'redq-rental' ),
			'placeholder' => __( 'Attribute Name', 'redq-rental' ),
			'required' => false,
		);
		$this->redq_register_inventory_text_term_meta('attributes',$attributes_name_args);

		$attributes_value_args = array(
			'title' => __( 'Attribute Value', 'redq-rental' ),
			'type'  => 'text',
			'id'    => 'inventory_attribute_value',
			'column_name' => __( 'A.Value', 'redq-rental' ),
			'placeholder' => __( 'Attribute Value', 'redq-rental' ),
			'required' => false,
		);
		$this->redq_register_inventory_text_term_meta('attributes',$attributes_value_args);

		$attr_imgoricon = array(
			'title' => __( 'Choose Image/Icon', 'redq-rental' ),
			'type'  => 'select',
			'id'    => 'choose_attribute_icon',
			'column_name' => __( 'I.Type', 'redq-rental' ),
			'options' => array(
				'0' => array(
					'key' => 'icon',
					'value' => 'Icon'
				),
				'1' => array(
					'key' => 'image',
					'value' => 'Image'
				),
			),
		);
		$this->redq_register_inventory_select_term_meta('attributes',$attr_imgoricon);

		$attributes_icon_name = array(
			'title' => __( 'Attribute Icon', 'redq-rental' ),
			'type'  => 'text',
			'id'    => 'inventory_attribute_icon',
			'column_name' => __( 'Icon', 'redq-rental' ),
			'placeholder' => __( 'Font-awesome icon Ex. fa fa-car', 'redq-rental' ),
			'required' => false,
		);
		$this->redq_register_inventory_icon_term_meta('attributes',$attributes_icon_name);

		$attributes_image_name = array(
			'title' => __( 'Attribute Image', 'redq-rental' ),
			'type'  => 'image',
			'id'    => 'attributes_image_icon',
			'column_name' => __( 'Image', 'redq-rental' ),
		);
		$this->redq_register_inventory_image_term_meta('attributes',$attributes_image_name);

		$highlighted_attr = array(
			'title' => __( 'Highlighted Or Not', 'redq-rental' ),
			'type'  => 'select',
			'id'    => 'inventory_attribute_highlighted',
			'column_name' => __( 'Highlighted', 'redq-rental' ),
			'options' => array(
				'0' => array(
					'key' => 'yes',
					'value' => 'Yes'
				),
				'1' => array(
					'key' => 'no',
					'value' => 'No'
				),
			),
		);
		$this->redq_register_inventory_select_term_meta('attributes',$highlighted_attr);


		//Term Meta for features
		$highlighted_attr = array(
			'title' => __( 'Highlighted Or Not', 'redq-rental' ),
			'type'  => 'select',
			'id'    => 'inventory_feature_highlighted',
			'column_name' => __( 'Highlighted', 'redq-rental' ),
			'options' => array(
				'0' => array(
					'key' => 'yes',
					'value' => 'Yes'
				),
				'1' => array(
					'key' => 'no',
					'value' => 'No'
				),
			),
		);
		$this->redq_register_inventory_select_term_meta('features',$highlighted_attr);



		$car_company_image = array(
				'title' => __( 'Car Company Image', 'redq-rental' ),
				'type'  => 'image',
				'id'    => 'product_car_company_icon',
				'column_name' => __( 'Image', 'redq-rental' ),
			);
		$company_image = $this->redq_register_inventory_image_term_meta('car_company',$car_company_image);


	}



	/**
	 * Create all taxonomies
	 *
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_resgister_inventory_taxonomies($taxonomy, $name, $post_type){

		$labels = array(
			'name'              => _x( ucwords($name), 'taxonomy general name', 'redq-rental' ),
			'singular_name'     => _x( $name, 'taxonomy singular name' ),
			'search_items'      => __( 'Search '.$name.'', 'redq-rental' ),
			'all_items'         => __( 'All '.$name.'', 'redq-rental' ),
			'parent_item'       => __( 'Parent '.$name.'', 'redq-rental' ),
			'parent_item_colon' => __( 'Parent '.$name.':', 'redq-rental' ),
			'edit_item'         => __( 'Edit '.$name.'', 'redq-rental' ),
			'update_item'       => __( 'Update '.$name.'', 'redq-rental' ),
			'add_new_item'      => __( 'Add New '.$name.'', 'redq-rental' ),
			'new_item_name'     => __( 'New '.$name.' Name', 'redq-rental' ),
			'menu_name'         => ucwords($name),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'public'            => true,
			'rewrite'           => array( 'slug' => $taxonomy ),
		);

		register_taxonomy( str_replace(' ', '_', $taxonomy), $post_type, $args );

	}



	/**
	 * Call text type term meta
	 *
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_register_inventory_text_term_meta($taxonomy, $args){
		$text_term_meta = 'Rnb_Term_Meta_Generator_Text';
		new $text_term_meta($taxonomy, $args);
	}


	/**
	 * Call icon type term meta
	 *
	 * @author RedQTeam
	 * @version 2.0.3
	 * @since 2.0.3
	 */
	public function redq_register_inventory_icon_term_meta($taxonomy, $args){
		$icon_term_meta = 'Rnb_Term_Meta_Generator_Icon';
		new $icon_term_meta($taxonomy, $args);
	}


	/**
	 * Call image type term meta
	 *
	 * @author RedQTeam
	 * @version 2.0.3
	 * @since 2.0.3
	 */
	public function redq_register_inventory_image_term_meta($taxonomy, $args){
		$image_term_meta = 'Rnb_Term_Meta_Generator_Image';
		new $image_term_meta( $taxonomy, $args);
	}



	/**
	 * Call select type term meta
	 *
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_register_inventory_select_term_meta($taxonomy, $args){
		$select_term_meta = 'Rnb_Term_Meta_Generator_Select';
		new $select_term_meta($taxonomy, $args);
	}


	/**
	 * Handle Save Meta for inventory
	 *
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_product_to_inventory_save_post($post_id){		
		$product = wc_get_product($post_id);
        $product_type = $product ? $product->get_type() : '';
        
		if(isset($product_type) && $product_type === 'redq_rental'){			
            update_post_meta( $post_id, '_redq_product_inventory', $_POST['_redq_product_inventory'] );
            update_post_meta( $post_id, '_price', 0 );
		}
	}


	/**
	 * Attach terms to invenotry post
	 *
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_attached_terms_to_inventory($POST, $inventory_id, $taxonomy, $post_key, $key ){
    	if(isset($POST[$post_key][$key]) && !empty($POST[$post_key][$key])){
    		$terms = $_POST[$post_key][$key];
    		$term_id = wp_set_object_terms( $inventory_id, $terms, $taxonomy );
   		}else{
	    	$term_id = wp_set_object_terms( $inventory_id, '', $taxonomy );
	    }
	}


	/**
	 * Only Inventory Save Posts
	 *
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_only_inventory_save_post($post_id){

		$post_type = get_post_type($post_id);

		if(isset($post_type) && $post_type === 'inventory') {
			// $update_availability = array();
			$create_availability = array();
			if(isset($_POST['redq_availability_block_by']) && isset($_POST['redq_availability_pickup_datetime']) && isset($_POST['redq_availability_dropoff_datetime']) && isset($_POST['redq_availability_row_id'])) {
				$availability_block_by 					= $_POST['redq_availability_block_by'];
				$availability_pickup_datetime 	= $_POST['redq_availability_pickup_datetime'];
				$availability_dropoff_datetime 	= $_POST['redq_availability_dropoff_datetime'];
				$availability_row_id 						= $_POST['redq_availability_row_id'];

				for($i=0; $i<sizeof($availability_block_by); $i++) {
					if( !empty( $availability_row_id[$i] ) ) {
						// $update_availability[$i]['block_by'] 					= $availability_block_by[$i];
						// $update_availability[$i]['pickup_datetime'] 	= $availability_pickup_datetime[$i];
						// $update_availability[$i]['return_datetime'] 	= $availability_dropoff_datetime[$i];
						// $update_availability[$i]['id'] 								= $availability_row_id[$i];
					} else {
						// CREATE
						$create_availability[$i]['block_by'] 					= $availability_block_by[$i];
						$create_availability[$i]['pickup_datetime'] 	= $availability_pickup_datetime[$i];
						$create_availability[$i]['return_datetime'] 	= $availability_dropoff_datetime[$i];
						$create_availability[$i]['rental_duration'] 	= rnb_calculate_date_difference($availability_pickup_datetime[$i], $availability_dropoff_datetime[$i]);
						$create_availability[$i]['inventory_id'] 			= $post_id;
					}
				}
			}

			if( isset( $_POST['redq_availability_remove_id'] ) ) {
				$remove_id = json_decode( $_POST['redq_availability_remove_id'] );
				
				global $wpdb;
    		$tablename = $wpdb->prefix.'rnb_availability';
				
				if( count( $remove_id ) > 0 ) {
					foreach ($remove_id as $id) {
						$wpdb->delete( $tablename, array( 'id' => $id ) );
					}
					
				}
			}


			if( count( $create_availability ) > 0 ) {
					$values = $place_holders = array();

			    foreach( $create_availability as $data ) {
		        array_push( $values, $data['block_by'], $data['pickup_datetime'], $data['return_datetime'], $data['rental_duration'], $data['inventory_id']);
		        $place_holders[] = "( %s, %s, %s, %s, %d)";
			    }

			    rnb_custom_date_insert( $place_holders, $values );
			}
		}

		// 	$new = new RedQ_Rental_And_Bookings();
		// 	$booked_dates_aras = array();
		// 	$only_block_dates = array();
		// 	$parent_id = wp_get_post_parent_id( $post_id );

		// 	$block_dates_and_times = get_post_meta($parent_id, 'redq_block_dates_and_times', true);
		// 	//Check all inventory post id exist
		// 	if(isset($block_dates_and_times) && !empty($block_dates_and_times) && is_array($block_dates_and_times) ){
		// 		foreach ($block_dates_and_times as $key => $value) {
		// 			$exist_post = get_post_type($key);
		// 			if(empty($exist_post) ){
		// 				unset($block_dates_and_times[$key]);
		// 			}
		// 		}
		// 	}

		// 	$conditions = reddq_rental_get_settings( $parent_id, 'conditions' );
		// 	$conditional_data = $conditions['conditions'];
		// 	$output_date_format    = $conditional_data['date_format'];
		// 	$european_date_format  = $conditional_data['euro_format'];

		// 	// Handle and create date availability array
		// 	$rental_availability = array();
		// 	if(isset($_POST['redq_rental_availability_type']) && isset($_POST['redq_rental_availability_from']) && isset($_POST['redq_rental_availability_to']) && isset($_POST['redq_availability_rentable'])){
		// 		$availability_type = $_POST['redq_rental_availability_type'];
		// 		$availability_from = $_POST['redq_rental_availability_from'];
		// 		$availability_to = $_POST['redq_rental_availability_to'];
		// 		$availability_rentable = $_POST['redq_availability_rentable'];
		// 		for($i=0; $i<sizeof($availability_type); $i++){
		// 			$rental_availability[$i]['type'] = $availability_type[$i];
		// 			$rental_availability[$i]['from'] = convert_to_generalized_format($availability_from[$i], $european_date_format);
		// 			$rental_availability[$i]['to'] = convert_to_generalized_format($availability_to[$i], $european_date_format);
		// 			$rental_availability[$i]['rentable'] = $availability_rentable[$i];
		// 			$rental_availability[$i]['post_id'] = get_the_ID();
		// 		}
		// 	}

		// 	// Handle all block days and will wrok only for inventroy post crate or update
		//  	if(isset($rental_availability) && !empty($rental_availability)){
		// 		update_post_meta( $post_id, 'redq_rental_availability', $rental_availability );
		// 		foreach ($rental_availability as $key => $value) {
		//         	$booked_dates_aras[] = get_plain_dates_ara($value['from'], $value['to']);
		//         }
		//  	}

		//  	if(isset($booked_dates_aras) && !empty($booked_dates_aras)){
		//  		foreach ($booked_dates_aras as $index => $booked_dates_aras) {
		// 			foreach ($booked_dates_aras as $key => $value) {
		// 				$only_block_dates[] = $value;
		// 			}
		// 		}
		//  	}

		// 	// Handle and create time availability array
		// 	$rental_time_availability = array();
		// 	if(isset($_POST['redq_rental_time_availability_from_time']) && isset($_POST['redq_rental_time_availability_to_time']) && isset($_POST['redq_time_availability_rentable'])){
		// 		$time_availability_chosen_date = $_POST['redq_rental_time_availability_date'];
		// 		$time_availability_from = $_POST['redq_rental_time_availability_from_time'];
		// 		$time_availability_to = $_POST['redq_rental_time_availability_to_time'];
		// 		$time_availability_rentable = $_POST['redq_time_availability_rentable'];
		// 		for($i=0; $i<sizeof($time_availability_from); $i++){
		// 			$rental_time_availability[$i]['date'] = $time_availability_chosen_date[$i];
		// 			$rental_time_availability[$i]['type'] = 'custom_time';
		// 			$rental_time_availability[$i]['from'] = $time_availability_from[$i];
		// 			$rental_time_availability[$i]['to'] = $time_availability_to[$i];
		// 			$rental_time_availability[$i]['rentable'] = $time_availability_rentable[$i];
		// 			$rental_time_availability[$i]['post_id'] = get_the_ID();
		// 		}
		// 	}


		// 	$block_times = array();
		// 	$combined_block_times = array();
		// 	$time_flag = 0;
		// 	$block_times_merge = array();

		// 	// Handle all block times and will wrok only for inventroy post crate or update
		//  	if(isset($rental_time_availability) && !empty($rental_time_availability)){
		// 		update_post_meta( $post_id, 'redq_rental_time_availability', $rental_time_availability );
		// 		foreach ($rental_time_availability as $time_key => $time_value) {
  //           		$block_times = $new->manage_all_times($time_value['date'] , $time_value['from'], $time_value['to']);
  //           		array_push($combined_block_times, $block_times);
  //           	}
		//  	}

		// 	if(isset($combined_block_times) && !empty($combined_block_times)){
		// 		foreach ($combined_block_times as $time_key => $time_value) {
		// 			if($time_flag === 0){
		// 				$first_time = $time_value;
		// 				$time_flag = 1;
		// 			}
		// 			$block_times_merge = array_merge_recursive($first_time, $time_value);
		// 		}
		// 	}

		// 	// Udate block dates , times and update availablity control main meta key
		// 	if(isset($block_dates_and_times) && !empty($block_dates_and_times) && is_array($block_dates_and_times)){
		// 		foreach ($block_dates_and_times as $key => $value) {
		// 			if($key === get_the_ID()){
		// 				$block_dates_and_times[$key]['block_dates'] = $rental_availability;
		// 				$block_dates_and_times[$key]['block_times'] = $rental_time_availability;
		// 				$block_dates_and_times[$key]['only_block_dates'] = $only_block_dates;
		// 				$block_dates_and_times[$key]['only_block_times'] = $block_times_merge;
		// 			}
		// 		}
		// 	}

		// 	update_post_meta($parent_id, 'redq_block_dates_and_times', $block_dates_and_times);

		// }

	}


	/**
	 * Trashed Posts
	 *
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_trash_post($post_id){

		$post_type = get_post_type($post_id);

		if(isset($post_type) && $post_type === 'inventory'){
			$product_id = wp_get_post_parent_id($post_id);
			$block_dates_and_times = get_post_meta($product_id, 'redq_block_dates_and_times', true);
			if(isset($block_dates_and_times) && !empty($block_dates_and_times) && is_array($block_dates_and_times)){
				foreach ($block_dates_and_times as $key => $value) {
					if($post_id === $key){
						unset($block_dates_and_times[$key]);
					}
				}
			}
			update_post_meta($product_id, 'redq_block_dates_and_times', $block_dates_and_times);
		}

		if(isset($post_type) && $post_type === 'product'){
			$product_id = $post_id;
			$block_dates_and_times = get_post_meta($product_id, 'redq_block_dates_and_times', true);
			if(isset($block_dates_and_times) && !empty($block_dates_and_times) && is_array($block_dates_and_times)){
				foreach ($block_dates_and_times as $key => $value) {
					wp_delete_post( $key, true );
				}
			}
		}
	}




	/**
	* Availability management meta box define
	* @param  callback redq_inventory_availability_control_cb, id redq_inventory_availability_control
	* @author RedQTeam
	* @version 2.0.0
	* @since 2.0.0
	*/
	public function redq_register_meta_boxes(){
    remove_meta_box( 'submitdiv', 'request_quote', 'side' );
    add_meta_box(
      'redq_request_for_a_quote_control',
      'Request For A Quote Management',
      'redq_request_for_a_quote_control_cb',
      'request_quote',
      'advanced',
      'low'
    );
		add_meta_box(
			'redq_inventory_availability_control',
			__('Availability Management', 'redq-rental'),
			'redq_inventory_availability_control_cb',
			'inventory',
			'normal',
			'high'
		);

		add_meta_box(
			'redq_inventory_quantity',
			__('Quantity Management', 'redq-rental'),
			'redq_inventory_quantity_cb',
			'inventory',
			'normal',
			'high'
		);



    add_meta_box(
      'redq_request_for_a_quote_save',
      __('Quote Actions', 'redq-rental'),
      'redq_request_for_a_quote_save_cb',
      'request_quote',
      'side',
      'high'
    );

    add_meta_box(
      'redq_request_for_a_quote_message',
      __('Request For A Quote Message', 'redq-rental'),
      'redq_request_for_a_quote_message_cb',
      'request_quote',
      'normal',
      'high'
    );
	}


  function quote_pre_get_posts( $query ) {

    if( is_admin() && $query->query['post_type'] == 'request_quote' ) {

      if( !isset( $query->query['post_status'] ) && empty( $query->query['post_status'] )) {
        $query->set('post_status', array( 'quote-pending', 'quote-processing', 'quote-on-hold', 'quote-accepted', 'quote-completed', 'quote-cancelled' ) );
        $query->set( 'order', 'DESC' );
      }

    }

  }

}


function redq_request_for_a_quote_save_cb( $post ) { ?>
  <ul class="quote_actions submitbox">
    <li class="wide" id="quote-status">
      <label><?php esc_html_e('Quote Status', 'redq-rental') ?></label>
      <?php
        $quote_statuses = apply_filters( 'redq_get_request_quote_post_statuses',
          array(
            'quote-pending'    => _x( 'Pending', 'Quote status', 'redq-rental' ),
            'quote-processing' => _x( 'Processing', 'Quote status', 'redq-rental' ),
            'quote-on-hold'    => _x( 'On Hold', 'Quote status', 'redq-rental' ),
            'quote-accepted'  => _x( 'Accepted', 'Quote status', 'redq-rental' ),
            'quote-completed'  => _x( 'Completed', 'Quote status', 'redq-rental' ),
            'quote-cancelled'  => _x( 'Cancelled', 'Quote status', 'redq-rental' ),
          )
        );
      ?>
      <select name="post_status">
        <?php foreach ($quote_statuses as $key => $value) : ?>
        <option value="<?php echo $key ?>" <?php echo ( $post->post_status === $key) ? 'selected="selected"' : '' ?>><?php echo $value ?></option>
      <?php endforeach; ?>
      </select>
    </li>
    <li class="wide">
      <label><?php esc_html_e('Price', 'redq-rental')?> (<?php echo esc_attr( get_post_meta( $post->ID, 'currency-symbol', true ) ) ?>)</label>
      <?php
        $price = get_post_meta($post->ID, '_quote_price', true);
      ?>
      <input type="text" class="redq_input_price" name="quote_price" value="<?php echo $price ?>">
      <input type="hidden" name="previous_post_status" value="<?php echo $post->post_status ?>">
    </li>
    <li class="wide last">
      <div id="delete-action"><?php

        if ( current_user_can( 'delete_post', $post->ID ) ) {

          if ( ! EMPTY_TRASH_DAYS ) {
            $delete_text = __( 'Delete Permanently', 'redq-rental' );
          } else {
            $delete_text = __( 'Move to Trash', 'redq-rental' );
          }
          ?><a class="submitdelete deletion" href="<?php echo esc_url( get_delete_post_link( $post->ID ) ); ?>"><?php echo $delete_text; ?></a><?php
        }
      ?></div>
      <input type="submit" class="button save_quote button-primary tips" name="save" value="<?php esc_html_e( 'Update Quote', 'redq-rental' ); ?>" data-tip="<?php esc_html_e( 'Update the %s', 'redq-rental' ); ?>" />
    </li>
  </ul>
<?php
}

function redq_request_for_a_quote_control_cb($post) {  ?>
 	<div id="request-a-quote-data">
        <h2><?php esc_html_e('Quote', 'redq-rental') ?> <?php echo '#' . $post->ID ?> <?php esc_html_e('Details', 'redq-rental') ?></h2>
        <p class="quote_number">
            <?php
                $product_id = get_post_meta($post->ID, 'add-to-cart', true);
                $product_title = get_the_title($product_id);
                $product_url = get_the_permalink($product_id);

                $get_labels = reddq_rental_get_settings( $product_id, 'labels', array('pickup_location', 'return_location', 'pickup_date', 'return_date', 'resources', 'categories', 'person', 'deposites') );
                $labels = $get_labels['labels'];
                $order_quote_meta = json_decode( get_post_meta($post->ID, 'order_quote_meta', true), true );            
            ?>
            <?php esc_html_e('Request for:', 'redq-rental') ?> <a href="<?php echo esc_url( $product_url ) ?>" target="_blank"><?php echo $product_title ?></a>
        </p>


        <?php 
            $contacts = array();
            foreach ($order_quote_meta as $meta) { 
                if( isset( $meta['name'] ) ) {                    
                    switch ($meta['name']) {
                        case 'add-to-cart':                          
                        case 'cat_quantity':                           
                        case 'currency-symbol':break;

                        case 'pickup_location':
                            if(!empty($meta['value'])):
                                $pickup_location_title = $labels['pickup_location'];
                                $dval = explode('|', $meta['value'] );
                                $pickup_value = $dval[0].' ( '.wc_price($dval[2]). ' )';                                 
                                echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $pickup_location_title ).':</dt>';
                                echo '<dd><p><strong>'.$pickup_value.'</strong></p></dd>';                            
                            endif;
                            break;

                        case 'dropoff_location':
                            if(!empty($meta['value'])):
                                $return_location_title = $labels['return_location'];
                                $dval = explode('|', $meta['value'] );
                                $return_value = $dval[0].' ( '.wc_price($dval[2]). ' )';                                 
                                echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $return_location_title ).':</dt>';
                                echo '<dd><p><strong>'.$return_value.'</strong></p></dd>';                                 
                            endif;
                            break;

                        case 'pickup_date':
                            if(!empty($meta['value'])):
                                $pickup_date_title = $labels['pickup_date'];
                                $pickup_date_value = $meta['value'];                                 
                                echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $pickup_date_title ).':</dt>';
                                echo '<dd><p><strong>'.$pickup_date_value.'</strong></p></dd>';                                
                            endif;
                            break;

                        case 'pickup_time':
                            if(!empty($meta['value'])):
                                $pickup_time_title = $labels['pickup_time'];
                                $pickup_time_value = $meta['value'] ? $meta['value'] : '' ;                                 
                                echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $pickup_time_title ).':</dt>';
                                echo '<dd><p><strong>'.$pickup_time_value.'</strong></p></dd>';                                
                            endif; 
                            break;

                        case 'dropoff_date':
                            if(!empty($meta['value'])):
                                $return_date_title = $labels['return_date'];
                                $return_date_value = $meta['value'] ? $meta['value'] : '' ;
                                echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $return_date_title ).':</dt>';
                                echo '<dd><p><strong>'.$return_date_value.'</strong></p></dd>';                                
                            endif;
                            break;

                        case 'dropoff_time':
                            if(!empty($meta['value'])):
                                $return_time_title = $labels['return_time'];
                                $return_time_value = $meta['value'] ? $meta['value'] : '' ; 
                                echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $return_time_title ).':</dt>';
                                echo '<dd><p><strong>'.$return_time_value.'</strong></p></dd>';                               
                            endif;
                            break;

                        case 'additional_adults_info':
                            if(!empty($meta['value'])):
                                $person_title = $labels['adults'];
                                $dval = explode('|', $meta['value'] );
                                $person_value = $dval[0].' ( '.wc_price($dval[1]).' - '.$dval[2]. ' )';
                                echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $person_title ).':</dt>';
                                echo '<dd><p><strong>'.$person_value.'</strong></p></dd>';                                
                            endif;
                            break;
                            
                        case 'additional_childs_info':
                            if(!empty($meta['value'])):
                                $person_title = $labels['childs'];
                                $dval = explode('|', $meta['value'] );
                                $person_value = $dval[0].' ( '.wc_price($dval[1]).' - '.$dval[2]. ' )';
                                echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $person_title ).':</dt>';
                                echo '<dd><p><strong>'.$person_value.'</strong></p></dd>';                                 
                            endif;
                            break;

                        case 'extras': 
                            $resources_title = $labels['resource'];
                            $resource_name = '';
                            $payable_resource = array();
                            foreach ($meta['value'] as $key => $value) {
                                $extras = explode('|', $value);
                                $payable_resource[$key]['resource_name'] = $extras[0];
                                $payable_resource[$key]['resource_cost'] = $extras[1];
                                $payable_resource[$key]['cost_multiply'] = $extras[2];
                                $payable_resource[$key]['resource_hourly_cost'] = $extras[3];
                            }
                            foreach ($payable_resource as $key => $value) {
                                if($value['cost_multiply'] === 'per_day'){
                                    $resource_name .= $value['resource_name'].' ( '.wc_price($value['resource_cost']).' - '.__('Per Day','redq-rental').' )'.' , <br> ';
                                }else{
                                    $resource_name .= $value['resource_name'].' ( '.wc_price($value['resource_cost']).' - '.__('One Time','redq-rental').' )'.' , <br> ';
                                }
                            }
                            echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $resources_title ).':</dt>';
                            echo '<dd><p><strong>'.$resource_name.'</strong></p></dd>';                             
                            break;
                            
                        case 'categories':
                            $categories_title = $labels['categories'];
                            $category_name = '';
                            $payable_category = array();
                            foreach ($meta['value'] as $key => $value) {
                                $category = explode('|', $value);
                                $payable_category[$key]['category_name'] = $category[0];
                                $payable_category[$key]['category_cost'] = $category[1];
                                $payable_category[$key]['cost_multiply'] = $category[2];
                                $payable_category[$key]['category_hourly_cost'] = $category[3];
                                $payable_category[$key]['category_qty'] = $category[4];
                            }
                            foreach ($payable_category as $key => $value) {
                                if($value['cost_multiply'] === 'per_day'){
                                    $category_name .= $value['category_name'].' ( '.wc_price($value['category_cost']).' - '.__('Per Day','redq-rental').' )'.' * '.$value['category_qty'].' , <br> ';
                                }else{
                                    $category_name .= $value['category_name'].' ( '.wc_price($value['category_cost']).' - '.__('One Time','redq-rental').' )'.' * '.$value['category_qty'].' , <br> ';
                                }
                            }
                            echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $categories_title ).':</dt>';
                            echo '<dd><p><strong>'.$category_name.'</strong></p></dd>';                         
                            break;    
                        case 'security_deposites':
                            $deposits_title = $labels['deposite'];
                            $deposite_name = '';
                            $payable_deposits = array();
                            foreach ($meta['value'] as $key => $value) {
                                $extras = explode('|', $value);
                                $payable_deposits[$key]['deposite_name'] = $extras[0];
                                $payable_deposits[$key]['deposite_cost'] = $extras[1];
                                $payable_deposits[$key]['cost_multiply'] = $extras[2];
                                $payable_deposits[$key]['deposite_hourly_cost'] = $extras[3];
                            }
                            foreach ($payable_deposits as $key => $value) {
                                if($value['cost_multiply'] === 'per_day'){
                                    $deposite_name .= $value['deposite_name'].' ( '.wc_price($value['deposite_cost']).' - '.__('Per Day','redq-rental').' )'.' , <br> ';
                                }else{
                                    $deposite_name .= $value['deposite_name'].' ( '.wc_price($value['deposite_cost']).' - '.__('One Time','redq-rental').' )'.' , <br> ';
                                }
                            }
                            echo '<dt style="float: left;margin-right: 10px;">'.esc_attr( $deposits_title ).':</dt>';
                            echo '<dd><p><strong>'.$deposite_name.'</strong></p></dd>';                             
                            break;

                        case 'inventory_quantity':
                            echo '<dt style="float: left;margin-right: 10px;">'.esc_html__( 'Quantity', 'redq-rental' ).':</dt>';
                            echo '<dd><p><strong>'.$meta['value'].'</strong></p></dd>';
                            break;

                        case 'quote_price':
                            echo '<dt style="float: left;margin-right: 10px;">'.esc_html__( 'Quote Price', 'redq-rental' ).':</dt>';
                            echo '<dd><p><strong>'.wc_price($meta['value']).'</strong></p></dd>';
                            break;  

                        default: 
                            echo '<dt style="float: left;margin-right: 10px;">'.$meta['name'].':</dt>';
                            echo '<dd><p><strong>'.$meta['value'].'</strong></p></dd>';
                            break;                        
                    }
                }
            
                if( isset( $meta['forms'] ) ) {
                    $contacts = $meta['forms'];   
                }  
            } 
        ?>

        <h2><?php esc_html_e('Customer information','redq-rental'); ?></h2>
        <?php 
            if($contacts){
                foreach ($contacts as $key => $value) { 
                    if( $key !== 'quote_message' ) :
                        echo '<p><strong>'.ucfirst( substr( $key, 6) ).' : </strong>'.$value.'</p>';             
                    endif;
                } 
            }            
        ?>
    </div>
  <?php
}

function redq_request_for_a_quote_message_cb($post) { ?>

	<textarea class="widefat add-quote-message" name="add-quote-message"></textarea>
	<button class="add-message-button"><?php esc_html_e('ADD MESSAGE', 'redq-rental') ?></button>

	<?php
		$quote_id = $post->ID;
		// Remove the comments_clauses where query here.
		remove_filter( 'comments_clauses', 'exclude_request_quote_comments_clauses' );
		$args = array(
		'post_id'   => $quote_id,
		'orderby'   => 'comment_ID',
		'order'     => 'DESC',
		'approve'   => 'approve',
		'type'      => 'quote_message'
		);
		$comments = get_comments($args); ?>
	<ul class="quote-message">
	<?php foreach($comments as $comment) : ?>
		<?php
			$list_class = 'message-list';
			$content_class = 'quote-message-content';
			if($comment->user_id === get_post_field( 'post_author', $quote_id ) ) {
				$list_class .= ' customer';
				$content_class .= ' customer';
			}
		?>
		<li class="<?php echo $list_class ?>">
		  	<div class="<?php echo $content_class ?>">
		    	<?php echo wpautop( wptexturize( wp_kses_post( $comment->comment_content ) ) ); ?>
		  	</div>
		  	<p class="meta">
		    	<abbr class="exact-date" title="<?php echo $comment->comment_date; ?>"><?php printf( __( 'added on %1$s at %2$s', 'redq-rental' ), date_i18n( wc_date_format(), strtotime( $comment->comment_date ) ), date_i18n( wc_time_format(), strtotime( $comment->comment_date ) ) ); ?></abbr>
		    <?php printf( ' ' . __( 'by %s', 'redq-rental' ), $comment->comment_author ); ?>
		    <!-- <a href="#" class="delete-message"><?php _e( 'Delete', 'redq-rental' ); ?></a> -->
		  	</p>
		</li>
	<?php endforeach; ?>
	</ul>
	<?php
}

function redq_inventory_quantity_cb($post) {
    
    $post_id = $post->ID;
    $currency = get_woocommerce_currency_symbol();
    
?>


	<div id="price_calculation_product_data" class="panel woocommerce_options_panel">
		<?php
            woocommerce_wp_text_input(
                array(
                    'id' => 'quantity',
                    'label' => __( 'Set Quantity', 'redq-rental' ),
                    'placeholder' => __( 'Add invenotry quantity', 'redq-rental' ),
                    'type' => 'text',
                    'desc_tip' => 'true',
                    'description' => sprintf( __( 'Minimum 1 is required for each invenotry to work with.', 'redq-rental' ) )
                )
            );
		?>
		
		<div class="location-price show_if_general_pricing">
			<?php
				woocommerce_wp_text_input(
					array(
						'id' => 'perkilo_price',
						'label' => __( 'Per Kilometer Price ( '.$currency.' )', 'redq-rental' ),
						'placeholder' => __( 'Per Kilometer Price', 'redq-rental' ),
						'type' => 'text',
						'desc_tip' => 'true',
						'description' => sprintf( __( 'If you select booking layout two then for location price it will be applied', 'redq-rental' ) )
					)
				);
			?>
		</div>

        <!-- Daily pricing plans -->
        <h4 class="redq-headings"><?php esc_html_e('Configure Day Pricing Plans', 'redq-rental'); ?></h4>
        
        <?php
			woocommerce_wp_select( array( 'id' => 'pricing_type','label' => __( 'Set Price Type', 'redq-rental' ), 'description' => sprintf( __( 'Choose a price type - this controls the <a href="%s">schema</a>.', 'redq-rental' ), 'http://schema.org/' ), 'options' => array(
				'general_pricing'            => __( 'General Pricing', 'redq-rental' ),
				'daily_pricing' => __( 'Daily Pricing', 'redq-rental' ),
				'monthly_pricing'       => __( 'Monthly Pricing', 'redq-rental' ),
                'days_range'       => __( 'Days Range Pricing', 'redq-rental' ),
                'flat_hours'       => __( 'Flat Hour Pricing', 'redq-rental' ),
			) ) );
		?>

		<div class="general-pricing-panel show_if_general_pricing">
			<h4 class="redq-headings"><?php _e('Set general pricing plan','redq-rental'); ?></h4>
			<?php
				woocommerce_wp_text_input( array( 
                    'id' => 'general_price', 
                    'label' => __( 'General Price ( '.$currency.' )', 'redq-rental' ), 
                    'placeholder' => __( 'Enter price here', 'redq-rental' ), 
                    'type' => 'text' 
                ) );
			?>
		</div>

		<div class="daily-pricing-panel">
			<h4 class="redq-headings"><?php _e('Set daily pricing Plan','redq-rental'); ?></h4>
			<?php
                $weeks = ['friday','saturday','sunday','monday','tuesday','wednesday','thursday'];
				$daily_pricing = get_post_meta( $post_id, 'redq_daily_pricing', true );
	            $daily_pricing = $daily_pricing ? $daily_pricing : array();   

                foreach ($weeks as $key => $day) {
                    woocommerce_wp_text_input( array( 
                        'id' => $day.'_price', 
                        'label' => __( ucfirst($day).' ( '.$currency.' )', 'redq-rental' ), 
                        'placeholder' => __( 'Enter price here', 'redq-rental' ), 
                        'type' => 'text', 
                        'value' => isset($daily_pricing[$weeks[$key]]) ? $daily_pricing[$weeks[$key]] : 0,  
                    ));
                }	
            ?>
		</div>

		<div class="monthly-pricing-panel">
			<h4 class="redq-headings"><?php _e('Set monthly pricing plan','redq-rental') ?></h4>
			<?php
                $months = ['january','february','march','april','may','june','july','august','september','october','november','december'];
				$monthly_pricing = get_post_meta( $post_id, 'redq_monthly_pricing', true );
	            $monthly_pricing = $monthly_pricing ? $monthly_pricing : array();

                foreach ($months as $key => $month) {
                    woocommerce_wp_text_input( array( 
                        'id' => $month.'_price', 
                        'label' => __( ucfirst($month).' ( '.$currency.' )', 'redq-rental' ), 
                        'placeholder' => __( 'Enter price here', 'redq-rental' ), 
                        'type' => 'text', 
                        'value' => isset($monthly_pricing[$months[$key]]) ? $monthly_pricing[$months[$key]] : 0, 
                    ) );
                }
            ?>
		</div>
       

		<div class="redq-days-range-panel">
			<h4 class="redq-headings"><?php _e('Set day ranges pricing plans','redq-rental') ?></h4>
			<div class="table_grid sortable" id="sortable">
				<table class="widefat">
					<tfoot>
						<tr>
							<th>
								<a href="#" class="button button-primary add_redq_row" data-row="<?php
									ob_start();
									include( 'views/html-days-range-meta.php' );
									$html = ob_get_clean();
									echo esc_attr( $html );
								?>"><?php _e( 'Add Days Range', 'redq-rental' ); ?></a>
							</th>
						</tr>
					</tfoot>
					<tbody id="resource_availability_rows">
						<?php
							 $days_range = get_post_meta($post_id , 'redq_day_ranges_cost' , true);
							if ( ! empty( $days_range ) && is_array( $days_range ) ) {
								foreach ( $days_range as $day_range ) {
									include( 'views/html-days-range-meta.php' );
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>		

        <!-- Starting hourly pricing plan -->
        <h4 class="redq-headings"><?php esc_html_e('Configure Hourly Pricing Plans', 'redq-rental'); ?></h4>
        <?php
            woocommerce_wp_select( 
                array( 
                    'id' => 'hourly_pricing_type',
                    'label' => __( 'Set Hourly Price Type', 'redq-rental' ), 
                    'description' => sprintf( __( 'Choose a price type - this controls the <a href="%s">schema</a>.', 'redq-rental' ), 'http://schema.org/' ), 
                    'options' => array(
                        'hourly_general'   => __( 'General Hourly Pricing', 'redq-rental' ),
                        'hourly_range' => __( 'Hourly Range Pricing', 'redq-rental' ),
                    ) 
                ) 
            );  
        ?>

        <div class="redq-hourly-general-panel show_if_general_pricing">
            <?php
                woocommerce_wp_text_input( array( 
                    'id' => 'hourly_price', 
                    'label' => __( 'Hourly Price ( '.$currency.' )', 'redq-rental' ), 
                    'placeholder' => __( 'Enter price here', 'redq-rental' ), 
                    'type' => 'text', 'desc_tip' => 'true', 
                    'description' => sprintf( __( 'Hourly price will be applicable if booking or rental days min 1day', 'redq-rental'
                ) ) ) );
            ?>
        </div>

        <div class="redq-hourly-range-panel">
            <h4 class="redq-headings"><?php _e('Set hourly ranges pricing plans','redq-rental') ?></h4>
            <div class="table_grid sortable" id="sortable">
                <table class="widefat">
                    <tfoot>
                        <tr>
                            <th>
                                <a href="#" class="button button-primary add_redq_row" data-row="<?php
                                    ob_start();
                                    include( 'views/html-hourly-range-meta.php' );
                                    $html = ob_get_clean();
                                    echo esc_attr( $html );
                                ?>"><?php _e( 'Add Hourly Range', 'redq-rental' ); ?></a>
                            </th>
                        </tr>
                    </tfoot>
                    <tbody id="resource_availability_rows">
                        <?php
                            $hourly_ranges = get_post_meta($post_id , 'redq_hourly_ranges_cost' , true);
                            if ( ! empty( $hourly_ranges ) && is_array( $hourly_ranges ) ) {
                                foreach ( $hourly_ranges as $hourly_range ) {
                                    include( 'views/html-hourly-range-meta.php' );
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>        
        
	</div>
	<?php
}


/**
* Hande Inventory availability management metabox callback
*
* @author RedQTeam
* @version 2.0.0
* @since 2.0.0
*/
function redq_inventory_availability_control_cb($post){
?>

	<!-- Date Availability tab -->
	<div id="availability_product_data" class="panel rental_date_availability woocommerce_options_panel">
		<h4 class="redq-headings"><?php _e('Product Date Availabilities','redq-rental') ?></h4>

		<div class="options_group own_availibility">
			<div class="table_grid">
				<table class="widefat">
					<thead style="2px solid #eee;">
						<tr>
							<th class="sort" width="1%">&nbsp;</th>
							<th><?php _e( 'Block type', 'redq-rental' ); ?></th>
							<th><?php _e( 'Pickup Datetime', 'redq-rental' ); ?></th>
							<th><?php _e( 'Dropoff Datetime', 'redq-rental' ); ?></th>
							<!-- <th><?php _e( 'Row ID', 'redq-rental' ); ?></th> -->
							<th class="remove" width="1%">&nbsp;</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="6">
								<a href="#" class="button button-primary add_redq_row" data-row="<?php
									ob_start();
									include( 'views/html-own-availability.php' );
									$html = ob_get_clean();
									echo esc_attr( $html );
								?>"><?php _e( 'Block Dates', 'redq-rental' ); ?></a>
								<span class="description"><?php _e( 'Please select the date range to be disabled for the product.', 'redq-rental' ); ?></span>
							</th>
						</tr>
					</tfoot>
					<tbody id="availability_rows">
						<?php

							$get_availabilities = $wpdb->get_results( 
								$wpdb->prepare( 
									"SELECT * FROM {$wpdb->prefix}rnb_availability
										WHERE inventory_id = %d AND block_by = %s",
									 	$post_id, 'CUSTOM'
								), ARRAY_A
							);

							if ( ! empty( $get_availabilities ) && is_array( $get_availabilities ) ) {
								foreach ( $get_availabilities as $availability ) {
									include( 'views/html-own-availability.php' );
								}
							}
						?>
						<input type="hidden" class="redq_availability_remove_id" name="redq_availability_remove_id" value="[]">
					</tbody>
				</table>
			</div>
		</div>
	</div>




	<!-- Time Availability tab -->
	<div id="availability_product_data" class="panel rental_time_availability woocommerce_options_panel">
		<h4 class="redq-headings"><?php //_e('Product Time Availabilities','redq-rental') ?></h4>

		<div class="options_group own_availibility">
			<div class="table_grid">
				<table class="widefat">
					<thead style="2px solid #eee;">
						<tr>
							<th class="sort" width="1%">&nbsp;</th>
							<th><?php //_e( 'Date', 'redq-rental' ); ?></th>
							<th><?php //_e( 'From', 'redq-rental' ); ?></th>
							<th><?php //_e( 'To', 'redq-rental' ); ?></th>
							<th><?php //_e( 'Bookable', 'redq-rental' ); ?>&nbsp;<a class="tips" data-tip="<?php //_e( 'Please select the date range for which you want the product to be disabled.', 'redq-rental' ); ?>"></a></th>
							<th class="remove" width="1%">&nbsp;</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="6">
								<!-- <a href="#" class="button button-primary add_redq_row" data-row="<?php
									// ob_start();
									// include( 'views/html-own-time-availability.php' );
									// $html = ob_get_clean();
									// echo esc_attr( $html );
								?>"><?php //_e( 'Add Time', 'redq-rental' ); ?></a> -->
							</th>
						</tr>
					</tfoot>
					<tbody id="availability_rows">
						<?php

							// $block_dates_and_times = get_post_meta($parent_id, 'redq_block_dates_and_times', true);

							// foreach ($block_dates_and_times as $key => $value) {
							// 	if($key === get_the_ID()){
							// 		$time_availablity = $value['block_times'];
							// 	}
							// }

							// if ( ! empty( $time_availablity ) && is_array( $time_availablity ) ) {
							// 	foreach ( $time_availablity as $availability ) {
							// 		include( 'views/html-own-time-availability.php' );
							// 	}
							// }
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>



<?php
}

new WC_Redq_Rental_Post_Types();

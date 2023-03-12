<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://scopeship.com
 * @since      1.0.0
 *
 * @package    Dropsync
 * @subpackage Dropsync/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dropsync
 * @subpackage Dropsync/admin
 * @author     Krupal Lakhia <krupaly2k@gmail.com>
 */
class Dropsync_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->set_options();
	}
	
	/**
     * Adds notices for the admin to display.
     * Saves them in a temporary plugin option.
     * This method is called on plugin activation, so its needs to be static.
     */
    public static function add_admin_notices() {

    	$notices 	= get_option( 'dropsync_deferred_admin_notices', array() );
  		
  		apply_filters( 'dropsync_admin_notices', $notices );
  		update_option( 'dropsync_deferred_admin_notices', $notices );

    } // add_admin_notices


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dropsync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dropsync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dropsync-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dropsync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dropsync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dropsync-admin.js', array( 'jquery' ), $this->version, false );

	}
	
/*	function block_enqueue_scripts() {
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/block.js',  array( 'wp-blocks', 'wp-element' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/block.build.js',  array( 'wp-blocks', 'wp-element' ), $this->version, false );
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/block.css',  array(), $this->version, false );
	
		$block_name = 'block-dynamic';
	
		$script_slug = $this->plugin_name . '-' . $block_name;
        $style_slug = $this->plugin_name . '-' . $block_name . '-style';
        $editor_style_slug = $this->plugin_name . '-' . $block_name . '-editor-style';

	 // Registering the block
        register_block_type(
            'Dropsync-block/block-dynamic',  // Block name with namespace
            [
                'style' => $style_slug, // General block style slug
                'editor_style' => $editor_style_slug, // Editor block style slug
                'editor_script' => $script_slug,  // The block script slug
                'render_callback' => [$this, 'block_dynamic_render_cb'], // The render callback
            ]
        );
	
	} */ 
	
	public function block_dynamic_render_cb ( $att ) {
        // Coming from RichText, each line is an array's element
        $sum = $att['number1'][0] + $att['number2'][0]; 
        $html = "<h1>$sum</h1>";
        return $html;
    }
    
	
	
	
	/**
	 * Adds a settings page link to a menu
	 *
	 * @link 		https://codex.wordpress.org/Administration_Menus
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function add_menu() {


		/*add_submenu_page(
			'edit.php?post_type=dropbox',
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Dropbox Sync', 'dropsync' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'dropsync' ) ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_options' )
		);*/
		    add_menu_page('Dropbox Sync', 'Dropbox Sync', 'manage_options', 'dropbox-sync', array($this,'dropbox_sync_menu'), plugins_url('/images/dropsync-icon.png', __FILE__));
			
			add_submenu_page(
			'dropbox-sync',
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'dropsync' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'dropsync' ) ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_options' )
		);
			//add_submenu_page('dropbox-sync','Settings','Settings','manage_options','dropbox-settings',array($this,dropbox_sync_menu_settings));

	} // add_menu()

	function dropbox_sync_menu() {
		
		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-display.php' );
	}
	function page_options() {
		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-display-settings.php' );
	}
	
	/**
     * Manages any updates or upgrades needed before displaying notices.
     * Checks plugin version against version required for displaying
     * notices.
     */
	public function admin_notices_init() {

		$current_version = '1.0.0';

		if ( $this->version !== $current_version ) {

			// Do whatever upgrades needed here.

			update_option('dropsync_version', $current_version);

			$this->add_notice();

		}

	} // admin_notices_init()

		/**
	 * Displays admin notices
	 *
	 * @return 	string 			Admin notices
	 */
	public function display_admin_notices() {

		$notices = get_option( 'dropsync_deferred_admin_notices' );

		if ( empty( $notices ) ) { return; }

		foreach ( $notices as $notice ) {

			echo '<div class="' . esc_attr( $notice['class'] ) . '"><p>' . $notice['notice'] . '</p></div>';

		}

		delete_option( 'dropsync_deferred_admin_notices' );

    } // display_admin_notices()

	
	
	/**
	 * Registers settings fields with WordPress
	 */
	public function register_fields() {

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'dropsync-token',
			apply_filters( $this->plugin_name . 'label-dropsync-token', esc_html__( 'Dropbox Token', 'dropsync' ) ),
			array($this, "field_text"),
			$this->plugin_name,
			$this->plugin_name . '-messages',
			array(
				'description' 	=> 'Paste your dropbox token here',
				'id' 			=> 'dropsync-token',
			
			)
		);

		add_settings_field(
			'dropsync-folder-path',
			apply_filters( $this->plugin_name . 'label-dropsync-folder-path', esc_html__( 'Dropbox Folder Path for Listing', 'dropsync' ) ),
			array($this, "field_text_small"),
			$this->plugin_name,
			$this->plugin_name . '-messages',
			array(
				'description' 	=> 'This path will retrieve data from mentioned dropbox folder',
				'id' 			=> 'dropsync-folder-path',
			
			)
		);
			

	} // register_fields()

	
	
	/**
	 * Creates an editor field
	 *
	 * NOTE: ID must only be lowercase letter, no spaces, dashes, or underscores.
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_editor( $args ) {

		$defaults['description'] 	= '';
		$defaults['settings'] 		= array( 'textarea_name' => $this->plugin_name . '-options[' . $args['id'] . ']' );
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-editor-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-editor.php' );

	} // field_editor()

		/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * @return 		array 			An array of options
	 */
	public static function get_options_list() {

		$options = array();

		$options[] = array( 'dropsync-token', 'text', '' );
		$options[] = array( 'dropsync-folder-path', 'text', '' );

		return $options;

	} // get_options_list()

		
		/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections() {

		// add_settings_section( $id, $title, $callback, $menu_slug );

		add_settings_section(
			$this->plugin_name . '-messages',
			apply_filters( $this->plugin_name . 'section-title-messages', esc_html__( 'Dropbox Configuration', 'dropsync' ) ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

	} // register_sections()

	/**
	 * Registers plugin settings
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);

	} // register_settings()

	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }

		$return 	= '';
		$sanitizer 	= new Dropsync_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	} // sanitizer()

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function section_messages( $params ) {

		include( plugin_dir_path( __FILE__ ) . 'partials/dropsync-admin-section-messages.php' );

	} // section_messages()

	/**
	 * Sets the class variable $options
	 */
	private function set_options() {

		$this->options = get_option( $this->plugin_name . '-options' );

	} // set_options()

	/**
	 * Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options( $input ) {

		//wp_die( print_r( $input ) );

		$valid 		= array();
		$options 	= $this->get_options_list();

		foreach ( $options as $option ) {

			$name = $option[0];
			$type = $option[1];

			if ( 'repeater' === $type && is_array( $option[2] ) ) {

				$clean = array();

				foreach ( $option[2] as $field ) {

					foreach ( $input[$field[0]] as $data ) {

						if ( empty( $data ) ) { continue; }

						$clean[$field[0]][] = $this->sanitizer( $field[1], $data );

					} // foreach

				} // foreach

				$count = dropsync_get_max( $clean );

				for ( $i = 0; $i < $count; $i++ ) {

					foreach ( $clean as $field_name => $field ) {

						$valid[$option[0]][$i][$field_name] = $field[$i];

					} // foreach $clean

				} // for

			} else {

				$valid[$option[0]] = $this->sanitizer( $type, $input[$name] );

			}

		}

		return $valid;

	} // validate_options()
	
	
	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_text( $args ) {
	
		$defaults['class'] 			= 'text widefat';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

	} // field_text()	
	
	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_text_small( $args ) {


		$defaults['class'] 			= 'text regular-text';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

	} // field_text()	
	
	
}

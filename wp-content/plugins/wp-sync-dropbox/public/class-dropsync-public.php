<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://scopeship.com
 * @since      1.0.0
 *
 * @package    Dropsync
 * @subpackage Dropsync/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dropsync
 * @subpackage Dropsync/public
 * @author     Krupal Lakhia <krupaly2k@gmail.com>
 */
class Dropsync_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dropsync-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dropsync-public.js', array( 'jquery' ), $this->version, false );

	}
	
		/**
	 * Processes shortcode books
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function list_dropboxfiles( $atts = array() ) {

		ob_start();

		$defaults['loop-template'] 	= $this->plugin_name . '-loop';
		$defaults['order'] 			= 'date';
		$defaults['quantity'] 		= 100;
		$args						= shortcode_atts( $defaults, $atts, 'dropboxfiles' );
	
		$items 						= $this->get_dropfiles( $args );

		if ( is_array( $items ) || is_object( $items ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/dropsync-public-display.php';

		} else {

			echo $items;

		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	} // list_openings()

	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {

		add_shortcode( 'dropsync', array( $this, 'list_dropboxfiles' ) );
		

	} // register_shortcodes()
	
	/**
	 * Returns a post object of portfolio posts
	 *
	 * @param 	array 		$params 			An array of optional parameters
	 * 							types 			An array of portfolio item type slugs
	 * 							quantity		Number of posts to return
	 * @param 	string 		$cache 				String to create a new cache of posts
	 *
	 * @return 	object 		A post object
	 */
	public function get_dropfiles( $params, $cache = '' ) {

		$return 	= '';
		$cache_name = $this->plugin_name . '_dropsync_posts';

		if ( ! empty( $cache ) ) {

			$cache_name .= '_' . $cache;

		}

		$return = wp_cache_get( $cache_name, $this->plugin_name . '_dropsync_posts' );

		if ( false === $return ) {

			$args 	= $this->set_args( $params );
			$query 	= new WP_Query( $args );

			if ( is_wp_error( $query ) ) {

				$options 	= get_option( $this->plugin_name . '-options' );
				$return 	= $options['message-no-books'];

			} else {

				wp_cache_set( $cache_name, $query->posts, $this->plugin_name . '_book_posts', 5 * MINUTE_IN_SECONDS );

				$return = $query->posts;

			}
		}
	
		return $return;

	} // get_books()
	
	
}

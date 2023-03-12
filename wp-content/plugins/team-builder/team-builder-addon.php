<?php

if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly.
    
}

final class Team_Builder_Addon
{   
    const TEAM_ADDON_VERSION = '1.0.0';

   
    const TEAM_ADDON_MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    
    const TEAM_ADDON_MINIMUM_PHP_VERSION = '7.0';

    
    private static $instance = null;

   
    public static function instance()
    {

        if (is_null(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;

    }

   
    public function __construct()
    {

        add_action('plugins_loaded', [$this, 'wpsm_team_b_on_plugins_loaded']);

    }

    
    public function elementor_textdomain()
    {

        load_plugin_textdomain('wpshopmart_team_b_text_domain');

    }

   
    public function wpsm_team_b_on_plugins_loaded()
    {        
        add_action('elementor/init', [$this, 'wpsm_team_b_init']);  
		add_action( 'elementor/elements/categories_registered',array($this, 'add_elementor_widget_categories' ));
		add_action('elementor/editor/before_enqueue_scripts', array($this, 'editor_enqueue_scripts'));

    }  	
	
	public function editor_enqueue_scripts()
    {
        
        // editor style
        wp_enqueue_style(
            'team-builder-editor',
            wpshopmart_team_b_directory_url . 'team-builder-elementor-addon/assets/css/editor.css',
            false
        );
    }
	
	public function add_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'wpshopmart',
		[
			'title' => __( 'WPShopmart', 'Team Builder' ),
			'icon' => 'font',
		]
	);	

	}


    
    public function wpsm_team_b_init()
    {

        $this->elementor_textdomain();		

        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'wpsm_team_b_init_widgets']);

    }

   
    public function wpsm_team_b_init_widgets()
    {

        // Include Widget files
        require_once (__DIR__ . '/team-builder-elementor-addon/widgets/team-builder-addon-widget.php');

        // Register widget
        \Elementor\Plugin::instance()
            ->widgets_manager
            ->register_widget_type(new \Team_Builder_Addon_Widget());

    }    	

}

Team_Builder_Addon::instance();


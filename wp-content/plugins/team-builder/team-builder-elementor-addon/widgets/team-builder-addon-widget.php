<?php

class Team_Builder_Addon_Widget extends \Elementor\Widget_Base {
	
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( "wpsm_team_b_bootstrap-front",wpshopmart_team_b_directory_url."team-builder-elementor-addon/assets/css/team-builder-addon-bootstrap.css", array(), false, "all" );		
		wp_register_style( "wpsm_team_b_css",wpshopmart_team_b_directory_url."team-builder-elementor-addon/assets/css/team-builder-addon-css.css", array(), false, "all" );
		wp_register_style( "wpsm_team_b_carousel_css",wpshopmart_team_b_directory_url."team-builder-elementor-addon/assets/css/team-builder-addon-carousel-css.css", array(), false, "all" );
		wp_enqueue_script('jquery');
		wp_register_script('wpsm_team_b_carousel_js',wpshopmart_team_b_directory_url ."team-builder-elementor-addon/assets/js/team-builder-addon-carousel-js.js",array ('jquery'), false, false);
		
	}
	
	public function get_style_depends() {
		return array( 'wpsm_team_b_bootstrap-front','wpsm_team_b_css','wpsm_team_b_carousel_css');
	}
	
	public function get_script_depends() {
		return array( 'wpsm_team_b_carousel_js' );
	}
	
	public function get_name() {
		return 'Team Builder';
	}

	
	public function get_title() {
		return __( 'Team Builder', 'wpshopmart_team_b_text_domain' );
	}

	
	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	
	public function get_categories() {
		return [ 'wpshopmart' ];
	}

	
	protected function _register_controls() {		
		
		require_once( wpshopmart_team_b_directory_path .'team-builder-elementor-addon/includes/team-builder-addon-controls.php' );
	}

	
	protected function render() {

		$settings = $this->get_settings_for_display();		
		require_once( __DIR__ . '/render-template/design-'.$settings['team_select_design'].'.php' );		

	}
	
	
	protected function _content_template()
	{				
		require_once( wpshopmart_team_b_directory_path .'team-builder-elementor-addon/includes/team-builder-addon-content.php' );
		
	}
}

?>

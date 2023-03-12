<?php

$this->start_controls_section(
	'team_select_design_content',
	[
		'label' => __( 'Design', 'team-builder-addon' ),
		'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
	]
);

$this->add_control(
	'team_select_design',
	[
		'label' => __( 'Select Design', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::SELECT,
		'default' => '1',
		'options' => [
			'1'  => __( 'Design-1', 'wpshopmart_team_b_text_domain' ),
			'2' => __( 'Design-2', 'wpshopmart_team_b_text_domain' ),
			'3' => __( 'Design-3', 'wpshopmart_team_b_text_domain' ),
			'4'  => __( 'Design-4', 'wpshopmart_team_b_text_domain' ),
			'5' => __( 'Design-5', 'wpshopmart_team_b_text_domain' ),
			'6' => __( 'Design-6', 'wpshopmart_team_b_text_domain' ),
			
		],
	]
);	

$this->end_controls_section();



$this->start_controls_section(
	'team_content_section',
	[
		'label' => __( 'Content', 'team-builder-addon' ),
		'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
	]
);

$repeater = new \Elementor\Repeater();

$repeater->add_control(
	'team_image',
	[
		'label' => __( 'Choose Image', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::MEDIA,
		'description' => 'Please upload square-cropped photos with a minimum dimension of 500px',			
		'default' => [
			'url' => wpshopmart_team_b_directory_url.'team-builder-elementor-addon/assets/images/team.jpg',
		],
	]
);		
	

$repeater->add_control(
	'team_member_name', [
		'label' => __( 'Member Name', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::TEXT,
		'default' => __( ' Sample Title ' , 'wpshopmart_team_b_text_domain' ),
		'label_block' => true,
	]
);

$repeater->add_control(
	'team_member_designation', [
		'label' => __( 'Member Designation', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::TEXT,
		'default' => __( ' Sample Title ' , 'wpshopmart_team_b_text_domain' ),
		'label_block' => true,
	]
);

$repeater->add_control(
	'team_description', [
		'label' => __( 'Member Description', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::TEXTAREA,
		'default' => __( ' Sample Description ' , 'wpshopmart_team_b_text_domain' ),
		'show_label' => true,
	]
);

$repeater->add_control(
	'team_facebook',
	[
		'label' => __( 'Facebook Profile Url', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::URL,
		'placeholder' => __( 'https://your-link.com', 'wpshopmart_team_b_text_domain' ),
		'show_external' => true,
		'default' => [
			'url' => '#',
			'is_external' => true,
			'nofollow' => true,
		],
	]
);

$repeater->add_control(
	'team_twitter',
	[
		'label' => __( 'Twitter Profile Url', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::URL,
		'placeholder' => __( 'https://your-link.com', 'wpshopmart_team_b_text_domain' ),
		'show_external' => true,
		'default' => [
			'url' => '#',
			'is_external' => true,
			'nofollow' => true,
		],
	]
);

$repeater->add_control(
	'team_linkedin',
	[
		'label' => __( 'Linkedin Profile Url', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::URL,
		'placeholder' => __( 'https://your-link.com', 'wpshopmart_team_b_text_domain' ),
		'show_external' => true,
		'default' => [
			'url' => '#',
			'is_external' => true,
			'nofollow' => true,
		],
	]
);

$repeater->add_control(
	'team_instagram',
	[
		'label' => __( 'Instagram Profile Url', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::URL,
		'placeholder' => __( 'https://your-link.com', 'wpshopmart_team_b_text_domain' ),
		'show_external' => true,
		'default' => [
			'url' => '#',
			'is_external' => true,
			'nofollow' => true,
		],
	]
);		

$this->add_control(
	'team_list',
	[
		'label' => __( 'Team Members', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::REPEATER,
		'fields' => $repeater->get_controls(),
		'default' => [
			[
				'team_image' => __( \Elementor\Utils::get_placeholder_image_src(), 'wpshopmart_team_b_text_domain' ),
				'team_member_name' => __( ' Sample Title ', 'wpshopmart_team_b_text_domain' ),
				'team_member_designation' => __( 'Sample Title', 'wpshopmart_team_b_text_domain' ),
				'team_description' => __( 'Sample Description', 'wpshopmart_team_b_text_domain' ),
				'team_facebook' => __( '#', 'wpshopmart_team_b_text_domain' ),
				'team_twitter' => __( '#', 'wpshopmart_team_b_text_domain' ),
				'team_linkedin' => __( '#', 'wpshopmart_team_b_text_domain' ),
				'team_instagram' => __( '#', 'wpshopmart_team_b_text_domain' ),
			],
			[
				'team_image' => __( \Elementor\Utils::get_placeholder_image_src(), 'wpshopmart_team_b_text_domain' ),
				'team_member_name' => __( ' Sample Title ', 'wpshopmart_team_b_text_domain' ),
				'team_member_designation' => __( 'Sample Title', 'wpshopmart_team_b_text_domain' ),
				'team_description' => __( 'Sample Description', 'wpshopmart_team_b_text_domain' ),
				'team_facebook' => __( '#', 'wpshopmart_team_b_text_domain' ),
				'team_twitter' => __( '#', 'wpshopmart_team_b_text_domain' ),
				'team_linkedin' => __( '#', 'wpshopmart_team_b_text_domain' ),
				'team_instagram' => __( '#', 'wpshopmart_team_b_text_domain' ),
			],		
			
		],
		'title_field' => '{{{ team_member_name }}}',
	]
);	

$this->end_controls_section();

$this->start_controls_section(
	'team_custom_css_section',
	[
		'label' => __( 'Code', 'team-builder-addon' ),
		'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
	]
);

$this->add_control(
	'team_custom_css',
	[
		'label' => __( 'Custom Css', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::CODE,
		'description' =>'Enter Css without using style tag',
		'language' => 'css',
		'rows' => 20,
	]
);	

$this->end_controls_section();

$this->start_controls_section(
	'team_style',
	[
		'label' => __( 'Style', 'team-builder-addon' ),
		'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	]
);

$this->add_control(
	'team_layout',
	[
		'label' => __( 'Team Layout', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::SELECT,
		'default' => '6',
		'options' => [
			'6'  => __( '2 Column Layout', 'wpshopmart_team_b_text_domain' ),
			'4' => __( '3 Column Layout', 'wpshopmart_team_b_text_domain' ),
			'3' => __( '4 Column Layout', 'wpshopmart_team_b_text_domain' ),
			
			
		],
	]
);	

$this->add_control(
	'team_font_family',
	[
		'label' => __( 'Font Family', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::FONT,
		'default' => "Open Sans",
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_member_wrapper_inner h3' => 'font-family: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_b_desig' => 'font-family: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_b_desc_design_1' => 'font-family: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' p' => 'font-family: {{VALUE}}',
			
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_member_wrapper_inner h3' => 'font-family: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_b_desig' => 'font-family: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_b_desc_design_3' => 'font-family: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' p' => 'font-family: {{VALUE}}',
			
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_member_wrapper_inner h3' => 'font-family: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_b_desig' => 'font-family: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_b_desc' => 'font-family: {{VALUE}}',
			
		],
	]
);

$this->add_control(
	'team_divider_1',
	[
		'type' => \Elementor\Controls_Manager::DIVIDER,
	]
);

$this->add_control(
	'team_member_font_size',
	[
		'label' => __( 'Team Member Name Font Size', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::SLIDER,
		'size_units' => [ 'px'],
		'range' => [
			'px' => [
				'min' => 8,
				'max' => 30,
				'step' => 1,
			],
			
		],
		'default' => [
			'unit' => 'px',
			'size' => 18,
		],
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_member_wrapper_inner h3' => 'font-size: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_member_wrapper_inner h3' => 'font-size: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_member_wrapper_inner h3' => 'font-size: {{SIZE}}{{UNIT}};',
			
		],
	]
);

$this->add_control(
	'team_member_designation_font_size',
	[
		'label' => __( 'Team Member Designation Font Size', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::SLIDER,
		'size_units' => [ 'px'],
		'range' => [
			'px' => [
				'min' => 8,
				'max' => 25,
				'step' => 1,
			],
			
		],
		'default' => [
			'unit' => 'px',
			'size' => 14,
		],
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_b_desig' => 'font-size: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_b_desig' => 'font-size: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_b_desig' => 'font-size: {{SIZE}}{{UNIT}};',
			
		],
	]
);

$this->add_control(
	'team_member_description_font_size',
	[
		'label' => __( 'Team Member Description Font Size', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::SLIDER,
		'size_units' => [ 'px'],
		'range' => [
			'px' => [
				'min' => 8,
				'max' => 25,
				'step' => 1,
			],
			
		],
		'default' => [
			'unit' => 'px',
			'size' => 14,
		],
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_b_desc_design_1' => 'font-size: {{SIZE}}{{UNIT}};',					
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_b_desc' => 'font-size: {{SIZE}}{{UNIT}};',					
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_b_desc_design_3' => 'font-size: {{SIZE}}{{UNIT}};',					
		],
	]
);

$this->add_control(
	'team_divider_2',
	[
		'type' => \Elementor\Controls_Manager::DIVIDER,
	]
);	


$this->add_control(
	'team_member_name_color',
	[
		'label' => __( 'Team Member Name Color', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#000000',
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_member_wrapper_inner h3' => 'color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_member_wrapper_inner h3' => 'color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_member_wrapper_inner h3' => 'color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_member_wrapper_inner h3 .wpsm_team_1_name_divider' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'team_member_designation_color',
	[
		'label' => __( 'Team Member Designation Color', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#000000',
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_b_desig' => 'color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_b_desig' => 'color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_b_desig' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'team_member_description_color',
	[
		'label' => __( 'Team Member Description Color', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#000000',
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_b_desc_design_1' => 'color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_b_desc' => 'color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_b_desc_design_3' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'team_member_social_profile_icon_color',
	[
		'label' => __( 'Team Member Social Profile Icon Color', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#4f4f4f',
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_social_div a i' => 'color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_social_div a i' => 'color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_social_div a i.fab' => 'border-color: {{VALUE}}',
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_social_div a i' => 'color: {{VALUE}}',					
		],
	]
);

$this->add_control(
	'team_member_social_profile_icon_background_color',
	[
		'label' => __( 'Team Social Profile Icon Background Color', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#e5e5e5',
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .wpsm_team_1_social_div a i' => 'background-color: {{VALUE}}',		
			
		],
	]
);

$this->add_control(
	'team_member_wrapper_background_color_design_2',
	[
		'label' => __( 'Wrapper Background for Design 2 and 3', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#ffffff',
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .wpsm_team_2_member_wrapper' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .wpsm_team_3_member_wrapper' => 'background-color: {{VALUE}}',	
			
		],
	]
);

$this->add_control(
	'team_divider_3',
	[
		'type' => \Elementor\Controls_Manager::DIVIDER,
	]
);

$this->add_control(
	'team_car_dots_bg_clr',
	[
		'label' => __( 'Carousel Dots Background Color', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#D6D6D6',
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .owl-dots .owl-dot span' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .owl-dots .owl-dot span' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .owl-dots .owl-dot span' => 'background-color: {{VALUE}}',			
			
		],
	]
);

$this->add_control(
	'team_car_dots_athv_bg_clr',
	[
		'label' => __( 'Carousel Dots Hover/Active Background Color', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#869791',
		'selectors' => [
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .owl-dots .owl-dot.active span' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .owl-dots .owl-dot.active span' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .owl-dots .owl-dot.active span' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #wpsm_team_1_b_row_'.get_the_ID().' .owl-dots .owl-dot:hover span' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #wpsm_team_2_b_row_'.get_the_ID().' .owl-dots .owl-dot:hover span' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #wpsm_team_3_b_row_'.get_the_ID().' .owl-dots .owl-dot:hover span' => 'background-color: {{VALUE}}',
			
		],
	]
);

$this->add_control(
	'display_car_dots',
	[
		'label' => __( 'Display Carousel Dots', 'wpshopmart_team_b_text_domain' ),
		'type' => \Elementor\Controls_Manager::SWITCHER,
		'label_on' => __( 'Yes', 'wpshopmart_team_b_text_domain' ),
		'label_off' => __( 'No', 'wpshopmart_team_b_text_domain' ),
		'return_value' => 'yes',
		'default' => 'yes',
		
	]
);

$this->end_controls_section();
		
?>
<?php
/*
 * slider Options
 */
$app_opt_name;
// Redux::setSection( $app_opt_name, array(
//     'title' => esc_html__( 'woobox', 'woobox' ),
//     'id'    => 'slider-editor',
//     'icon'  => 'el el-arrow-up',
//     'customizer_width' => '500px',
// ) );


Redux::setSection( $app_opt_name, array(
    'title' => esc_html__( 'Upload Slider Images', 'woobox' ),
    'id'    => 'woobox',
    'icon'  => 'el el-slideshare',
    'subsection' => false,
    'desc'  => esc_html__( '', 'woobox' ),
    'fields'           => array(
        
        array(
            'id'          => 'opt-slides',
            'type'        => 'slides',
            'title'       => __('Slides', 'woobox'),   
            'show' => array(
                'title' => false,
                'description' => false,
                'url' => true              
            ),         
            'placeholder' => array(
                'title'           => __('This is a title', 'woobox'),
                
            ),
        ),
                    
    )
    
) );

Redux::setSection( $app_opt_name, array(
    'title' => esc_html__( 'Theme Color', 'woobox' ),
    'id'    => 'woobox-theme-color',
    'icon'  => 'el el-brush',
    'subsection' => false,
    'desc'  => esc_html__( 'Select Your App Theme Color Here', 'woobox' ),
    'fields'           => array(
        
        array(
            'id'          => 'woobox_app_theme_color',
            'type'        => 'color',
            'transparent' => false,
            'title'       => __('Theme Color', 'woobox'),   
            'desc' => __('Choose App Theme Color Here', 'woobox'),
                    
            'placeholder' => array(
                'title'           => __('This is a title', 'woobox'),
                
            ),
        ),
                    
    )
    
) );

Redux::setSection( $app_opt_name, array(
    'title' => esc_html__( 'Advertisement Images', 'woobox' ),
    'id'    => 'banner',
    'icon'  => 'el el-slideshare',
    'subsection' => false,
    'desc'  => esc_html__( '', 'woobox' ),
    'fields'           => array(
        
        array(
            'id'          => 'banner_slider',
            'type'        => 'slides',
            'title'       => __('Advertisement Images', 'woobox'),   
            'show' => array(
                'title' => true,
                'description' => false,
                'url' => true              
            ),         
            'placeholder' => array(
                'title'           => __('This is a title', 'woobox'),
                
            ),
        ),
                    
    )
    
) );





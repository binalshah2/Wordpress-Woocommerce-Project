<!DOCTYPE html>
<html>
<head>
	<title>Moco </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<!-- CSS Styles -->

	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap.css">
	<link href="https://fonts.googleapis.com/css?family=Exo+2:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/reset.css">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/responsive.css">
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/slick.css" rel="stylesheet">
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/slick-theme.css" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body>
	<header class="<?php echo (is_front_page()) ? 'header"' : 'inner-header' ?>">
		<div class="container height-100">
			<div class="row">
				<div class="col-md-12">
					<div class="main-menu">
						<div class="responsive-nav">
                            <?php echo crane_custom_logo() ?>
							<div class="toggle">
								<!-- <i class="fa fa-bars menu"></i> -->
								<div class="menu-icon menu">
									<span></span>
									<span></span>
									<span></span>
								</div>
							</div>
						</div>
						
                            <?php echo crane_custom_logo() ?>
                        
						<nav class="menu" id="menu">
                            <?php wp_nav_menu( 
                                array( 
                                    'theme_location' => 'main-menu',
                                    'container' => 'ul'
                                ) 
                            ); ?>
						</nav>
					</div>
				</div>
				<div class="clearfix"></div>
            </div>
            <?php if(is_front_page()){ ?>
                <div class="row overflow-hidden height-100">
                    <div class="col-24 banner-slide left-100 height-100">
        
                        <div class="detail-main-banner col-xs-6 col-md-6 col-sm-6">
                            <div class="col-md-8 no-padding height-100">
                                <div class="detail-img">
                                    <img src="" class="img-responsive banner_image">
                                </div>
                            </div>
                            <div class="col-md-4 no-padding height-100">
                                <div class="detail-content">
                                    <div class="detail-inner">
                                        <img class="banner_icon" src="">
                                        <h1 class="yellow banner_name"></h1>
                                        <!-- <div class="banner_buttons"></div> -->
                                        <p class="banner_description"></p>
                                    </div>
                                    <ul class="detail-links">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="banner">
                            
                            <div class="banner-content">
                                <h1 class="yellow text-center">
                                    Need a Storage Container
                                    
                                </h1>

                               
                
                                <div class="mascot hidden-xs hidden-sm">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/mascot.png">
                                </div>
                            </div>

                        <?php //dynamic_sidebar( 'home_page_banner' ); ?>

                        </div>
                    </div>
                </div>

                <div>
                    
                    <?php
                    global $wpdb;
                    $customers = $wpdb->get_results("SELECT * FROM crn_posts where id='745'");
                    //print_r($customers);
                    echo $customers[0]->post_content;
                    ?>
                </div>
            <?php } ?>
        </div> 
	</header>

<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link href="<?php echo get_template_directory_uri(); ?>/assets/css/slick.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/assets/css/slick-theme.css" rel="stylesheet">
<!-- Bootstrap CSS -->
		    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap.css">
		    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.css">
		    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
		    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/query.css">
		    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/animation.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<?php wp_head(); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/slick.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/slick.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-C8JP4HWB51"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

 gtag('config', 'G-C8JP4HWB51');
</script>

<script>
	$(document).ready(function(){
		$('.menu-toggle').click(function(){
			$('body').css('overflow','hidden');
			$('.primary-navigation').show();
		});
	});
</script>

</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">

		<?php
		/**
		 * Functions hooked into storefront_header action
		 *
		 * @hooked storefront_header_container                 - 0
		 * @hooked storefront_skip_links                       - 5
		 * @hooked storefront_social_icons                     - 10
		 * @hooked storefront_site_branding                    - 20
		 * @hooked storefront_secondary_navigation             - 30
		 * @hooked storefront_product_search                   - 40
		 * @hooked storefront_header_container_close           - 41
		 * @hooked storefront_primary_navigation_wrapper       - 42
		 * @hooked storefront_primary_navigation               - 50
		 * @hooked storefront_header_cart                      - 60
		 * @hooked storefront_primary_navigation_wrapper_close - 68
		 */
		do_action( 'storefront_header' );
		?>
	</header><!-- #masthead -->
			<!--<div class="mascot hidden-xs hidden-sm">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/mascot.png">
            </div>-->
	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>
	<?php
	//echo get_the_ID();
	//961
	if(get_the_ID()=="895")
	{
	?>
	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">
			<div class="banner">
				<div class="container">
					<div class="row">
						<div class="txt-banner">RENT. STORE. BUY.</div>
							<a href="https://moco.bb/rent-a-container/">Rent a Container <i class="demo-icon icon-right-open"></i></a>
							<div class="banner-img mascot hidden-xs hidden-sm">
								<img src="<?php echo get_site_url(); ?>/wp-content/themes/storefront/assets/images/img-banner.png" />
							</div>
					</div>
				</div>
			</div>
	<?php
	} 
	if(get_the_ID()=="961")
	{
	?>
	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">
			<div class="banner aboutbn">
				<div class="container">
					<div class="row">
						<div class="txt-banner"><?php single_post_title(); ?></div>
							<a href="https://moco.bb/contact/">Contact us <i class="demo-icon icon-right-open"></i></a>
					</div>
				</div>
			</div>
	<?php
	}
	?>

	<?php
	if(get_the_ID()=="17")
	{
	?>
	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">
			<div class="banner contheadbg">
				<div class="container">
					<div class="row">
						<div class="txt-banner"><?php single_post_title(); ?></div>
							<a href="https://moco.bb/rentals/">Request A Quote <i class="demo-icon icon-right-open"></i></a>
							<a href="https://moco.bb/pay-now/" class="rcbgblue">Make A Payment <i class="demo-icon icon-right-open"></i></a>
						</div>
					</div>
				</div>
	<?php
	}
	?>

	<?php
	if(get_the_ID()=="770")
	{
	?>
	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">
			<div class="banner convheadbg">
				<div class="container">
					<div class="row">
						<div class="txt-banner"><?php single_post_title(); ?></div>
							<a href="https://moco.bb/contact/" class="rcconvlink">Contact Us<i class="demo-icon icon-right-open"></i></a>
						</div>
					</div>
				</div>
	<?php
	}
	?>
	


<?php
	if(get_the_ID()=="764")
	{
	?>
	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">
			<div class="banner rentheadbg">
				<div class="container">
					<div class="row">
						<div class="txt-banner"><?php single_post_title(); ?></div>
							<a href="https://moco.bb/pay-now/" class="rcbgblue" style="width: 34%;">Make A Payment<i class="demo-icon icon-right-open"></i></a>
						</div>
					</div>
				</div>
	<?php
	}
	?>

	<?php
	if(get_the_ID()=="766")
	{
	?>
	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">
			<div class="banner salesheadbg">
				<div class="container">
					<div class="row">
						<div class="txt-banner"><?php single_post_title(); ?></div>
							<a href="https://moco.bb/pay-now/" class="rcbgblue" style="width: 34%;">Make A Payment<i class="demo-icon icon-right-open"></i></a>
						</div>
					</div>
				</div>
	<?php
	}
	?>
	
<!-- code added by binal -->

		<?php
		do_action( 'storefront_content_top' );



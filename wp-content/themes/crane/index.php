<?php get_header(); ?>
<?php if(is_front_page()){ ?>
   <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php
        if ( have_posts() ) :

            get_template_part( 'loop' );
            

        else :

            get_template_part( 'content', 'none' );

        endif;
        ?>

        </main><!-- #main -->
    </div><!-- #primary -->
    <section class="video-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="video-content">
                        <?php dynamic_sidebar( 'home_video_area' ); ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    <section class="mascot-section">
        <div class="container">
            <div class="row">
                <?php dynamic_sidebar( 'home_after_video_area' ); ?>
            </div>
        </div>
    </section>

    <section class="testimonial">
        <div class="container">
            <div class="row">
                <div class="col-md-12"><?php 
                    $testimonial = get_category(12); ?>
                    <h1 class="text-center testimonial-title">OUR TEAM</h1>
                    <p class="text-center testimonial-desc"><?php echo $testimonial->description ?></p>
                </div>
            </div>
        </div>
        <section class="center slider"><?php
            $testimonials =  get_posts(array(
                'category' => 30,
                // 'orderby' => 'name',
                'post_status'=>'publish'
            ));
            foreach ($testimonials as $key => $testimonial) { ?>
                <div class="testimonial-box">
                    <div class="testimonial-thumb">
                        <img src="<?php echo get_the_post_thumbnail_url($testimonial->ID); ?>">
                    </div>
                    <h3 class="author text-center "><?php echo $testimonial->post_title ?></h3>
                    <p class="text-center "><?php echo strip_tags($testimonial->post_content) ?></p>
                </div>
                <?php
            }
            ?>
        </section>
    </section>
    
<?php }
?>
<?php 
if ( is_page( '17' ) ) 
{ 
    //echo "binal";
?>

<?php
} 
else
{
?>
<?php get_footer(); ?>
<?php
}
?>






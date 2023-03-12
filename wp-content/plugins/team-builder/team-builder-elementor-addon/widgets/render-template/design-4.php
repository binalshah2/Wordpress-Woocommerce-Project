<style>
#wpsm_team_1_b_row_<?php echo get_the_ID(); ?> .owl-dots
{
	<?php
	if($settings['display_car_dots'] === 'yes' )
		echo "display:block;";
	else 
		echo "display:none";
	?>
}
#wpsm_team_1_b_row_<?php echo get_the_ID(); ?> .wpsm_team_1_member_wrapper
{
	margin:10px;
}
<?php echo $settings['team_custom_css']; ?>
</style>
	<div class="wpsm_team_1_b_row" id="wpsm_team_1_b_row_<?php echo get_the_ID(); ?>">
		<div class="wpsm_row">
		<div class="wpsm-team-owl-carousel">
			<?php 
			
			switch($settings['team_layout']){
				case(6):
					$row=2;
					break;
				case(4):
					$row=3;
					break;
				case(3):
					$row=4;
					break;
			}
			if ( $settings['team_list'] ) {
					 foreach (  $settings['team_list'] as $index => $item ) { 
					 
					$team_member_name_setting_key = $this->get_repeater_setting_key( 'team_member_name', 'team_list', $index );
					$this->add_inline_editing_attributes( $team_member_name_setting_key,'none' );
					
					$team_member_designation_setting_key = $this->get_repeater_setting_key( 'team_member_designation', 'team_list', $index );
					$this->add_inline_editing_attributes( $team_member_designation_setting_key,'none' );
					
					$team_description_setting_key = $this->get_repeater_setting_key( 'team_description', 'team_list', $index );
					$this->add_inline_editing_attributes( $team_description_setting_key );
					?>				
						<div class="wpsm_team_1_member_wrapper">
							<img class="img-responsive wpsm_team_1_mem_img"  src="<?php echo $item['team_image']['url']; ?>" alt="<?php echo $item['team_member_name']; ?>">
							<div class="wpsm_team_1_member_wrapper_inner">
								<h3><span <?php echo $this->get_render_attribute_string( $team_member_name_setting_key ); ?>>
									<?php echo $item['team_member_name']; ?></span>
									<div class="wpsm_team_1_name_divider"></div>
								</h3>
								<?php if($item['team_member_designation']!="") { ?><span class="wpsm_team_1_b_desig" ><span <?php echo $this->get_render_attribute_string( $team_member_designation_setting_key ); ?>> <?php echo $item['team_member_designation']; ?></span> </span> <?php } ?>
								<?php if($item['team_description']!="") { ?><p class="wpsm_team_1_b_desc_design_1 " ><span <?php echo $this->get_render_attribute_string( $team_description_setting_key ); ?> > <?php echo $item['team_description']; ?></span> </p> <?php } ?>
								<div class="wpsm_team_1_social_div">
									 <?php
									 $target1 = $item['team_facebook']['is_external'] ? ' target="_blank"' : '';
									 $nofollow1 = $item['team_facebook']['nofollow'] ? ' rel="nofollow"' : '';
									?>								
									<?php if($item['team_facebook']['url']!="") { ?><a href="<?php echo $item['team_facebook']['url']; ?>" <?php echo $target1; ?> <?php echo $nofollow1; ?> title="facebook profile"><i class="fa fa-facebook"></i></a> <?php } ?>
									<?php
									 $target2 = $item['team_twitter']['is_external'] ? ' target="_blank"' : '';
									 $nofollow2 = $item['team_twitter']['nofollow'] ? ' rel="nofollow"' : '';
									?>
									<?php if($item['team_twitter']['url']!="") { ?><a href="<?php echo $item['team_twitter']['url']; ?>" <?php echo $target2; ?> <?php echo $nofollow2; ?> title="twitter profile"><i class="fa fa-twitter"></i></a><?php } ?>
									<?php
									 $target3 = $item['team_linkedin']['is_external'] ? ' target="_blank"' : '';
									 $nofollow3 = $item['team_linkedin']['nofollow'] ? ' rel="nofollow"' : '';
									?>
									<?php if($item['team_linkedin']['url']!="") { ?><a href="<?php echo $item['team_linkedin']['url']; ?>" <?php echo $target3; ?> <?php echo $nofollow3; ?> title="linkedin profile"><i class="fa fa-linkedin"></i></a><?php } ?>
									<?php
									 $target4 = $item['team_instagram']['is_external'] ? ' target="_blank"' : '';
									 $nofollow4= $item['team_instagram']['nofollow'] ? ' rel="nofollow"' : '';
									?>
									<?php if($item['team_instagram']['url']!="") { ?><a href="<?php echo $item['team_instagram']['url']; ?>" <?php echo $target4; ?> <?php echo $nofollow4; ?> title="Instagram profile"><i class="fa fa-instagram"></i></a><?php } ?>
								</div>
							</div>
						</div>		
					
					<?php 				 
					
				 }
			}
		?>	
		</div>
	</div>
</div>
<script>
jQuery(document).ready(function() {
	  var owl = jQuery('.wpsm-team-owl-carousel');
	  owl.owlCarousel({
		responsiveClass:true,
		navigation:true,
		loop: true,
		margin: 20,				
		autoplay: true,
		rewindNav : false,
		autoplayTimeout: 5000,
        autoplaySpeed: 500,				
		autoplayHoverPause: true,		
		responsive: {
		  0: {
			items: 1
		  },
		  500: {
			items: 2
		  },
		  767: {
			items: 2
		  },
		  992: {
			items: 3
		  },
		  1000: {
			items:<?php echo $row;?>
		  }
		}
	  });
	  
})
</script>
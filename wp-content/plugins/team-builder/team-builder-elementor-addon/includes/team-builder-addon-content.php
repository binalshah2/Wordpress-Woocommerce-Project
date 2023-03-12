<#
	if(settings.team_select_design==1)
	{
	#>
		<style>
		{{{ settings.team_custom_css }}}
		</style>
		<#
		var post_id='<?php echo get_the_ID(); ?>';
		#>
		<div class="wpsm_team_1_b_row" id="wpsm_team_1_b_row_{{ post_id }}">
		<div class="wpsm_row">
		<#
		var i=1;
		var row=0;
		switch({{{ settings.team_layout }}}){
			case(6):
				row=2;
			break;
			case(4):
				row=3;
			break;
			case(3):
				row=4;
			break;
		}
		#>
		<# if ( settings.team_list.length ) { #>
		<# _.each( settings.team_list, function( item,index ) { 
		team_member_name_Key = view.getRepeaterSettingKey( 'team_member_name', 'team_list', index );
		view.addInlineEditingAttributes( team_member_name_Key, 'none' );
		
		team_member_designation_Key = view.getRepeaterSettingKey( 'team_member_designation', 'team_list', index );
		view.addInlineEditingAttributes( team_member_designation_Key, 'none' );
		
		team_description_Key = view.getRepeaterSettingKey( 'team_description', 'team_list', index );
		view.addInlineEditingAttributes( team_description_Key, 'none' );
		
		#>
		<div class="wpsm_col-md-{{ settings.team_layout }} wpsm_col-sm-6 wpsm-col-div wpsm_single_team">
			<div class="wpsm_team_1_member_wrapper">
				<img class="img-responsive wpsm_team_1_mem_img"  src="{{ item.image.url }}" alt="{{ item.team_member_name }}">
				<div class="wpsm_team_1_member_wrapper_inner">
					<h3><span {{{ view.getRenderAttributeString( team_member_name_Key ) }}}>
						{{ item.team_member_name }}</span>
						<div class="wpsm_team_1_name_divider" ></div>
					</h3>								
					<# if(item.team_member_designation!="") { #><span class="wpsm_team_1_b_desig"><span {{{ view.getRenderAttributeString( team_member_designation_Key ) }}}> {{ item.team_member_designation }}</span> </span> <# } #>
					<# if(item.team_description!="") { #><p class="wpsm_team_1_b_desc_design_1 "><span {{{ view.getRenderAttributeString( team_description_Key ) }}}> {{ item.team_description }}</span> </p> <# } #>
					<div class="wpsm_team_1_social_div">
						 <#
						 var target1 = item.team_facebook.is_external ? ' target="_blank"' : '';
						 var nofollow1 = item.team_facebook.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_facebook.url!="") { #><a href="{{ item.team_facebook.url }}" {{ target1 }} {{ nofollow1 }} title="facebook profile"><i class="fa fa-facebook"></i></a> <# } #>
						<#
						 var target2 = item.team_twitter.is_external ? ' target="_blank"' : '';
						 var nofollow2 = item.team_twitter.nofollow ? ' rel="nofollow"' : '';
						#>
						<# if(item.team_twitter.url!="") { #><a href="{{ item.team_twitter.url }}" {{ target2 }} {{ nofollow2 }} title="twitter profile"><i class="fa fa-twitter"></i></a><# } #>
						<#
						 var target3 = item.team_linkedin.is_external ? ' target="_blank"' : '';
						 var nofollow3 = item.team_linkedin.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_linkedin.url!="") { #><a href="{{ item.team_linkedin.url }}" {{ target3 }} {{ nofollow3 }} title="linkedin profile"><i class="fa fa-linkedin"></i></a><# } #>
						<#
						 var target4 = item.team_instagram.is_external ? ' target="_blank"' : '';
						 var nofollow4 = item.team_instagram.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_instagram.url!="") { #><a href="{{ item.team_instagram.url }}" {{ target4 }} {{ nofollow4 }} title="Instagram profile"><i class="fa fa-instagram"></i></a><# } #>
					</div>
				</div>
			</div>
		</div>
		<#
		if(i%row==0){
			#>
			</div>
			<div class="wpsm_row">
			<#
		}	
		#>
		<#
		 i++;
		});
				
	}		
	#>								
	</div>
	</div>
	<#
	}
			
	else if(settings.team_select_design==2)
	{
	#>
	<style>
		{{ settings.team_custom_css }}
	</style>
	<#
	var post_id='<?php echo get_the_ID(); ?>';
	#>
	<div class="wpsm_team_2_b_row" id="wpsm_team_2_b_row_{{ post_id }}">
		<div class="wpsm_row"> 			
		<#
		var i=1;
		var row=0;
		switch({{{ settings.team_layout }}}){
			case(6):
				row=2;
			break;
			case(4):
				row=3;
			break;
			case(3):
				row=4;
			break;
		}
		#>
		<# if ( settings.team_list.length ) { #>
		<# _.each( settings.team_list, function( item,index ) { 
		team_member_name_Key = view.getRepeaterSettingKey( 'team_member_name', 'team_list', index );
		view.addInlineEditingAttributes( team_member_name_Key, 'none' );
		
		team_member_designation_Key = view.getRepeaterSettingKey( 'team_member_designation', 'team_list', index );
		view.addInlineEditingAttributes( team_member_designation_Key, 'none' );
		
		team_description_Key = view.getRepeaterSettingKey( 'team_description', 'team_list', index );
		view.addInlineEditingAttributes( team_description_Key, 'none' );
		
		#>					
		<div class="wpsm_col-md-{{ settings.team_layout }} wpsm_col-sm-6  wpsm-col-div">
			<div class="wpsm_team_2_member_wrapper">
				<img class="img-responsive wpsm_team_2_mem_img" src="{{ item.image.url }}" alt="{{ item.team_member_name }}">
				<div class="wpsm_team_2_member_wrapper_inner">
					<h3 {{{ view.getRenderAttributeString( team_member_name_Key ) }}}>
						{{ item.team_member_name }}
					</h3>
					<# if(item.team_member_designation!="") { #><span class="wpsm_team_2_b_desig"><span {{{ view.getRenderAttributeString( team_member_designation_Key ) }}}> {{ item.team_member_designation }}</span></span> <# } #>
					<div class="wpsm_team_2_social_div">
						<#
						 var target1 = item.team_facebook.is_external ? ' target="_blank"' : '';
						 var nofollow1 = item.team_facebook.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_facebook.url!="") { #><a href="{{ item.team_facebook.url }}" {{ target1 }} {{ nofollow1 }} title="facebook profile"><i class="fa fa-facebook" ></i></a> <# } #>
						<#
						 var target2 = item.team_twitter.is_external ? ' target="_blank"' : '';
						 var nofollow2 = item.team_twitter.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_twitter.url!="") { #><a href="{{ item.team_twitter.url }}" {{ target2 }} {{ nofollow2 }} title="twitter profile"><i  class="fa fa-twitter"></i></a><# } #>
						<#
						 var target3 = item.team_linkedin.is_external ? ' target="_blank"' : '';
						 var nofollow3 = item.team_linkedin.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_linkedin.url!="") { #><a href="{{ item.team_linkedin.url }}" {{ target3 }} {{ nofollow3 }} title="linkedin profile"><i  class="fa fa-linkedin"></i></a><# } #>
						<#
						 var target4 = item.team_instagram.is_external ? ' target="_blank"' : '';
						 var nofollow4 = item.team_instagram.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_instagram.url!="") { #><a href="{{ item.team_instagram.url }}" {{ target4 }} {{ nofollow4 }} title="instagram profile"><i  class="fa fa-instagram"></i></a><# } #>
					</div>
					<# if(item.team_description!="") { #><p class="wpsm_team_2_b_desc"><span {{{ view.getRenderAttributeString( team_description_Key ) }}}>{{ item.team_description }}</span></p> <# } #>
					
				</div>
			</div>
		</div>
		
		<#
		if(i%row==0){
			#>
			</div>
			<div class="wpsm_row">
			<#
		}	
		#>
		<#
		 i++;
		});
				
	}		
	#>		
	</div>
	</div>
	<#
	}
	else if(settings.team_select_design==3)
	{
	#>
	<style>
	{{{ settings.team_custom_css }}}
	</style>
	<#
	var post_id='<?php echo get_the_ID(); ?>';
	#>
	<div class="wpsm_team_3_b_row" id="wpsm_team_3_b_row_{{ post_id }}">
		<div class="wpsm_row">  
			
		<#
		var i=1;
		var row=0;
		switch({{{ settings.team_layout }}}){
			case(6):
				row=2;
				break;
			case(4):
				row=3;
				break;
			case(3):
				row=4;
				break;
		}
		#>
		<# if ( settings.team_list.length ) { #>
		<# _.each( settings.team_list, function( item,index ) { 
		team_member_name_Key = view.getRepeaterSettingKey( 'team_member_name', 'team_list', index );
		view.addInlineEditingAttributes( team_member_name_Key, 'none' );
		
		team_member_designation_Key = view.getRepeaterSettingKey( 'team_member_designation', 'team_list', index );
		view.addInlineEditingAttributes( team_member_designation_Key, 'none' );
		
		team_description_Key = view.getRepeaterSettingKey( 'team_description', 'team_list', index );
		view.addInlineEditingAttributes( team_description_Key, 'none' );
		
		#>	
								 
		<div class="wpsm_col-md-{{ settings.team_layout }} wpsm_col-sm-6 wpsm-col-div wpsm_single_team">
			<div class="wpsm_team_3_member_wrapper">
				<div class="wpsm_tram_img_wrap">
				  <img class="img-responsive wpsm_team_3_mem_img" src="{{ item.image.url }}" alt="{{ item.team_member_name }}">
				</div>
				
				<div class="wpsm_team_3_member_wrapper_inner">
					<h3><span {{{ view.getRenderAttributeString( team_member_name_Key ) }}}>
						{{ item.team_member_name }}</span>
						<div class="wpsm_team_3_name_divider" ></div>
					</h3>
					<# if(item.team_member_designation!="") { #><span class="wpsm_team_3_b_desig"><span {{{ view.getRenderAttributeString( team_member_designation_Key ) }}}> {{ item.team_member_designation }}</span> </span> <# } #>
					<# if(item.team_description!="") { #><p class="wpsm_team_3_b_desc_design_3"><span {{{ view.getRenderAttributeString( team_description_Key ) }}}> {{ item.team_description }}</span></p> <# } #>
					<div class="wpsm_team_3_social_div">
						<#
						 var target1 = item.team_facebook.is_external ? ' target="_blank"' : '';
						 var nofollow1 = item.team_facebook.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_facebook.url!="") { #><a href="{{ item.team_facebook.url }}" {{ target1 }} {{ nofollow1 }} title="facebook profile"><i class="fa fa-facebook"></i></a> <# } #>
						<#
						 var target2 = item.team_twitter.is_external ? ' target="_blank"' : '';
						 var nofollow2 = item.team_twitter.nofollow ? ' rel="nofollow"' : '';
						#>
						<# if(item.team_twitter.url!="") { #><a href="{{ item.team_twitter.url }}" {{ target2 }} {{ nofollow2 }} title="twitter profile"><i class="fa fa-twitter"></i></a><# } #>
						<#
						 var target3 = item.team_linkedin.is_external ? ' target="_blank"' : '';
						 var nofollow3 = item.team_linkedin.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_linkedin.url!="") { #><a href="{{ item.team_linkedin.url }}" {{ target3 }} {{ nofollow3 }} title="linkedin profile"><i class="fa fa-linkedin"></i></a><# } #>
						<#
						 var target4 = item.team_instagram.is_external ? ' target="_blank"' : '';
						 var nofollow4 = item.team_instagram.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_instagram.url!="") { #><a href="{{ item.team_instagram.url }}" {{ target4 }} {{ nofollow4 }} title="instagram profile"><i class="fa fa-instagram"></i></a><# } #>
					</div>
				</div>
			</div>
		</div>
		<#
		if(i%row==0){
			#>
			</div>
			<div class="wpsm_row">
			<#
		}	
		#>
		<#
		 i++;
		});
				
	}		
	#>		
	</div>
	</div>
	<#
	}
	else if(settings.team_select_design==4)
	{
		#>
		<style>
		{{{ settings.team_custom_css }}}
		#wpsm_team_1_b_row_<?php echo get_the_ID(); ?> .owl-dots
		{
			<#
			if(settings.display_car_dots == 'yes' ) { #>
				<?php echo "display:block;"; ?>
			<# } #>
			<# else { #>
			<?php echo "display:none;"; ?>
			<# } #>
			
		}
		</style>
		<#
		var post_id='<?php echo get_the_ID(); ?>';
		#>
		<div class="wpsm_team_1_b_row" id="wpsm_team_1_b_row_{{ post_id }}">
		<div class="wpsm_row">
			<div class="owl-carousel">
		<#
		
		var row=0;
		switch({{{ settings.team_layout }}}){
			case(6):
				row=2;
			break;
			case(4):
				row=3;
			break;
			case(3):
				row=4;
			break;
		}
		#>
		<# if ( settings.team_list.length ) { #>
		<# _.each( settings.team_list, function( item,index ) { 
		team_member_name_Key = view.getRepeaterSettingKey( 'team_member_name', 'team_list', index );
		view.addInlineEditingAttributes( team_member_name_Key, 'none' );
		
		team_member_designation_Key = view.getRepeaterSettingKey( 'team_member_designation', 'team_list', index );
		view.addInlineEditingAttributes( team_member_designation_Key, 'none' );
		
		team_description_Key = view.getRepeaterSettingKey( 'team_description', 'team_list', index );
		view.addInlineEditingAttributes( team_description_Key, 'none' );
		
		#>					
		<div class="wpsm_team_1_member_wrapper">
			<img class="img-responsive wpsm_team_1_mem_img"  src="{{ item.image.url }}" alt="{{ item.team_member_name }}">
			<div class="wpsm_team_1_member_wrapper_inner">
				<h3><span {{{ view.getRenderAttributeString( team_member_name_Key ) }}}>
					{{ item.team_member_name }}</span>
					<div class="wpsm_team_1_name_divider" ></div>
				</h3>								
				<# if(item.team_member_designation!="") { #><span class="wpsm_team_1_b_desig"><span {{{ view.getRenderAttributeString( team_member_designation_Key ) }}}> {{ item.team_member_designation }}</span> </span> <# } #>
				<# if(item.team_description!="") { #><p class="wpsm_team_1_b_desc_design_1 "><span {{{ view.getRenderAttributeString( team_description_Key ) }}}> {{ item.team_description }}</span> </p> <# } #>
				<div class="wpsm_team_1_social_div">
					 <#
					 var target1 = item.team_facebook.is_external ? ' target="_blank"' : '';
					 var nofollow1 = item.team_facebook.nofollow ? ' rel="nofollow"' : '';
					#>	
					<# if(item.team_facebook.url!="") { #><a href="{{ item.team_facebook.url }}" {{ target1 }} {{ nofollow1 }} title="facebook profile"><i class="fa fa-facebook"></i></a> <# } #>
					<#
					 var target2 = item.team_twitter.is_external ? ' target="_blank"' : '';
					 var nofollow2 = item.team_twitter.nofollow ? ' rel="nofollow"' : '';
					#>
					<# if(item.team_twitter.url!="") { #><a href="{{ item.team_twitter.url }}" {{ target2 }} {{ nofollow2 }} title="twitter profile"><i class="fa fa-twitter"></i></a><# } #>
					<#
					 var target3 = item.team_linkedin.is_external ? ' target="_blank"' : '';
					 var nofollow3 = item.team_linkedin.nofollow ? ' rel="nofollow"' : '';
					#>	
					<# if(item.team_linkedin.url!="") { #><a href="{{ item.team_linkedin.url }}" {{ target3 }} {{ nofollow3 }} title="linkedin profile"><i class="fa fa-linkedin"></i></a><# } #>
					<#
					 var target4 = item.team_instagram.is_external ? ' target="_blank"' : '';
					 var nofollow4 = item.team_instagram.nofollow ? ' rel="nofollow"' : '';
					#>	
					<# if(item.team_instagram.url!="") { #><a href="{{ item.team_instagram.url }}" {{ target4 }} {{ nofollow4 }} title="Instagram profile"><i class="fa fa-instagram"></i></a><# } #>
				</div>
			</div>
		</div>				
					
		<#
					
		});
				
	}		
	#>	
	</div>		
	</div>
	</div>

	<#
	}
	else if(settings.team_select_design==5)
	{
	#>
	<style>
	{{ settings.team_custom_css }}
	#wpsm_team_2_b_row_<?php echo get_the_ID(); ?> .owl-dots
	{
	<#
	if(settings.display_car_dots == 'yes' ) { #>
		<?php echo "display:block;"; ?>
	<# } #>
	<# else { #>
	<?php echo "display:none;"; ?>
	<# } #>
	
	}
	</style>
	<#
	var post_id='<?php echo get_the_ID(); ?>';
	#>
	<div class="wpsm_team_2_b_row" id="wpsm_team_2_b_row_{{ post_id }}">
		<div class="wpsm_row"> 	
			<div class="owl-carousel">
			
			<#
		
			var row=0;
			switch({{{ settings.team_layout }}}){
				case(6):
					row=2;
				break;
				case(4):
					row=3;
				break;
				case(3):
					row=4;
				break;
			}
			#>
			<# if ( settings.team_list.length ) { #>
			<# _.each( settings.team_list, function( item,index ) { 
			team_member_name_Key = view.getRepeaterSettingKey( 'team_member_name', 'team_list', index );
			view.addInlineEditingAttributes( team_member_name_Key, 'none' );
			
			team_member_designation_Key = view.getRepeaterSettingKey( 'team_member_designation', 'team_list', index );
			view.addInlineEditingAttributes( team_member_designation_Key, 'none' );
			
			team_description_Key = view.getRepeaterSettingKey( 'team_description', 'team_list', index );
			view.addInlineEditingAttributes( team_description_Key, 'none' );
			
			#>					
					
			<div class="wpsm_team_2_member_wrapper">
				<img class="img-responsive wpsm_team_2_mem_img" src="{{ item.image.url }}" alt="{{ item.team_member_name }}">
				<div class="wpsm_team_2_member_wrapper_inner">
					<h3 {{{ view.getRenderAttributeString( team_member_name_Key ) }}}>
						{{ item.team_member_name }}
					</h3>
					<# if(item.team_member_designation!="") { #><span class="wpsm_team_2_b_desig"><span {{{ view.getRenderAttributeString( team_member_designation_Key ) }}}> {{ item.team_member_designation }}</span></span> <# } #>
					<div class="wpsm_team_2_social_div">
						<#
						 var target1 = item.team_facebook.is_external ? ' target="_blank"' : '';
						 var nofollow1 = item.team_facebook.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_facebook.url!="") { #><a href="{{ item.team_facebook.url }}" {{ target1 }} {{ nofollow1 }} title="facebook profile"><i class="fa fa-facebook" ></i></a> <# } #>
						<#
						 var target2 = item.team_twitter.is_external ? ' target="_blank"' : '';
						 var nofollow2 = item.team_twitter.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_twitter.url!="") { #><a href="{{ item.team_twitter.url }}" {{ target2 }} {{ nofollow2 }} title="twitter profile"><i  class="fa fa-twitter"></i></a><# } #>
						<#
						 var target3 = item.team_linkedin.is_external ? ' target="_blank"' : '';
						 var nofollow3 = item.team_linkedin.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_linkedin.url!="") { #><a href="{{ item.team_linkedin.url }}" {{ target3 }} {{ nofollow3 }} title="linkedin profile"><i  class="fa fa-linkedin"></i></a><# } #>
						<#
						 var target4 = item.team_instagram.is_external ? ' target="_blank"' : '';
						 var nofollow4 = item.team_instagram.nofollow ? ' rel="nofollow"' : '';
						#>	
						<# if(item.team_instagram.url!="") { #><a href="{{ item.team_instagram.url }}" {{ target4 }} {{ nofollow4 }} title="instagram profile"><i  class="fa fa-instagram"></i></a><# } #>
					</div>
					<# if(item.team_description!="") { #><p class="wpsm_team_2_b_desc"><span {{{ view.getRenderAttributeString( team_description_Key ) }}}>{{ item.team_description }}</span></p> <# } #>
					
				</div>
			</div>
					
					
					
			<#
					 
			});
				
		}		
		#>		
		</div>
		</div>
		</div>

		<#
		}
			
		else if(settings.team_select_design==6)
		{
		#>
		<style>
		{{{ settings.team_custom_css }}}
		#wpsm_team_3_b_row_<?php echo get_the_ID(); ?> .owl-dots
		{
		<#
		if(settings.display_car_dots == 'yes' ) { #>
			<?php echo "display:block;"; ?>
		<# } #>
		<# else { #>
		<?php echo "display:none;"; ?>
		<# } #>
	
		}
		</style>
		<#
		var post_id='<?php echo get_the_ID(); ?>';
		#>
		<div class="wpsm_team_3_b_row" id="wpsm_team_3_b_row_{{ post_id }}">
		<div class="wpsm_row">  
		<div class="owl-carousel">
		<#
		
		var row=0;
		switch({{{ settings.team_layout }}}){
			case(6):
				row=2;
				break;
			case(4):
				row=3;
				break;
			case(3):
				row=4;
				break;
		}
		#>
		<# if ( settings.team_list.length ) { #>
		<# _.each( settings.team_list, function( item,index ) { 
		team_member_name_Key = view.getRepeaterSettingKey( 'team_member_name', 'team_list', index );
		view.addInlineEditingAttributes( team_member_name_Key, 'none' );
		
		team_member_designation_Key = view.getRepeaterSettingKey( 'team_member_designation', 'team_list', index );
		view.addInlineEditingAttributes( team_member_designation_Key, 'none' );
		
		team_description_Key = view.getRepeaterSettingKey( 'team_description', 'team_list', index );
		view.addInlineEditingAttributes( team_description_Key, 'none' );
		
		#>									 
					
		<div class="wpsm_team_3_member_wrapper">
			<div class="wpsm_tram_img_wrap">
			  <img class="img-responsive wpsm_team_3_mem_img" src="{{ item.image.url }}" alt="{{ item.team_member_name }}">
			</div>
			
			<div class="wpsm_team_3_member_wrapper_inner">
				<h3><span {{{ view.getRenderAttributeString( team_member_name_Key ) }}}>
					{{ item.team_member_name }}</span>
					<div class="wpsm_team_3_name_divider" ></div>
				</h3>
				<# if(item.team_member_designation!="") { #><span class="wpsm_team_3_b_desig"><span {{{ view.getRenderAttributeString( team_member_designation_Key ) }}}> {{ item.team_member_designation }}</span> </span> <# } #>
				<# if(item.team_description!="") { #><p class="wpsm_team_3_b_desc_design_3"><span {{{ view.getRenderAttributeString( team_description_Key ) }}}> {{ item.team_description }}</span></p> <# } #>
				<div class="wpsm_team_3_social_div">
					<#
					 var target1 = item.team_facebook.is_external ? ' target="_blank"' : '';
					 var nofollow1 = item.team_facebook.nofollow ? ' rel="nofollow"' : '';
					#>	
					<# if(item.team_facebook.url!="") { #><a href="{{ item.team_facebook.url }}" {{ target1 }} {{ nofollow1 }} title="facebook profile"><i class="fa fa-facebook"></i></a> <# } #>
					<#
					 var target2 = item.team_twitter.is_external ? ' target="_blank"' : '';
					 var nofollow2 = item.team_twitter.nofollow ? ' rel="nofollow"' : '';
					#>
					<# if(item.team_twitter.url!="") { #><a href="{{ item.team_twitter.url }}" {{ target2 }} {{ nofollow2 }} title="twitter profile"><i class="fa fa-twitter"></i></a><# } #>
					<#
					 var target3 = item.team_linkedin.is_external ? ' target="_blank"' : '';
					 var nofollow3 = item.team_linkedin.nofollow ? ' rel="nofollow"' : '';
					#>	
					<# if(item.team_linkedin.url!="") { #><a href="{{ item.team_linkedin.url }}" {{ target3 }} {{ nofollow3 }} title="linkedin profile"><i class="fa fa-linkedin"></i></a><# } #>
					<#
					 var target4 = item.team_instagram.is_external ? ' target="_blank"' : '';
					 var nofollow4 = item.team_instagram.nofollow ? ' rel="nofollow"' : '';
					#>	
					<# if(item.team_instagram.url!="") { #><a href="{{ item.team_instagram.url }}" {{ target4 }} {{ nofollow4 }} title="instagram profile"><i class="fa fa-instagram"></i></a><# } #>
				</div>
			</div>
		</div>					
					
		<#
					 
		});
				
		}		
		#>		
	</div>
	</div>
	</div>
	<#
}
#>
		
		
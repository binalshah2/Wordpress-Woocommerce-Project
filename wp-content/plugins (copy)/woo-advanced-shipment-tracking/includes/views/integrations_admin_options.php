<section id="integrations_content" class="tab_section">	
	<div class="tab_container_without_bg">		
		<div class="integration-grid-row grid-row">
			<?php
			foreach ( $this->integrations_settings_options() as $id => $array ) {
			$tgl_class = isset( $array['tgl_color'] ) ? 'ast-tgl-btn-green' : '';
			$disabled = isset( $array['disabled'] ) && true == $array['disabled'] ? 'disabled' : '';
			$checked = ( 'enable_parcelforce_integration' == $id ) ? 'checked' : '' ;
			$upgrade_class = ( 'enable_parcelforce_integration' == $id ) ? '' : 'upgrade_to_ast_pro' ;
			?>
			<div class="grid-item">
				<div class="grid-item-wrapper">
					<img src="<?php echo esc_url( wc_advanced_shipment_tracking()->plugin_dir_url() ); ?>assets/images/<?php esc_html_e( $array['img'] ); ?>">
					<div class="grid-img-bottom">
						<span class="ast-tgl-btn-parent">
							<input type="hidden" name="<?php esc_html_e( $id ); ?>" value="0"/>
							<input class="ast-tgl ast-tgl-flat ast-settings-toggle" id="<?php esc_html_e( $id ); ?>" name="<?php esc_html_e( $id ); ?>" type="checkbox" value="1" <?php esc_html_e( $disabled ); ?> <?php esc_html_e( $checked ); ?>/>
							<label class="ast-tgl-btn <?php esc_html_e( $tgl_class ); ?> <?php esc_html_e( $upgrade_class ); ?>" for="<?php esc_html_e( $id ); ?>"></label>
						</span>
						<a class="integration-more-info" href="https://docs.zorem.com/docs/ast-pro/advanced-shipment-tracking-pro/integrations/" target="blank"><?php esc_html_e( 'more info', 'woo-advanced-shipment-tracking' ); ?></a>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>	
</section>

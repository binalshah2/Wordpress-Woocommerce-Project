<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Advanced_Shipment_Tracking_Admin_Notice {

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		$this->init();	
	}
	
	/**
	 * Get the class instance
	 *
	 * @return WC_Advanced_Shipment_Tracking_Admin_Notice
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	* init from parent mail class
	*/
	public function init() {						
		//add_action( 'admin_notices', array( $this, 'ast_pro_admin_notice' ) );	
		//add_action( 'admin_init', array( $this, 'ast_pro_admin_notice_ignore' ) );	

		add_action( 'admin_notices', array( $this, 'ast_pro_1_year_admin_notice' ) );	
		add_action( 'admin_init', array( $this, 'ast_pro_1_year_admin_notice_ignore' ) );
		
		add_action( 'before_shipping_provider_list', array( $this, 'ast_db_update_notice' ) );	
		add_action( 'admin_init', array( $this, 'ast_db_update_notice_ignore' ) );	
		
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
	}

	/*
	* init on plugin loaded
	*/
	public function on_plugins_loaded() {
		
		$wc_ast_api_key = get_option( 'wc_ast_api_key' ); 
		if ( $wc_ast_api_key && !function_exists( 'trackship_for_woocommerce' ) ) {			
			add_action( 'admin_notices', array( $this, 'ast_install_ts4wc' ) );
		}
	}	
	
	/*
	* Display admin notice on plugin install or update
	*/
	public function ast_pro_1_year_admin_notice() { 		
		
		if ( class_exists( 'ast_pro' ) ) {
			return;
		}
		
		$date_now = date( 'Y-m-d' );

		if ( get_option('ast_pro_1_year_admin_notice_ignore') || $date_now > '2022-03-31' ) {
			return;
		}	
		
		$dismissable_url = esc_url(  add_query_arg( 'ast-pro-1-year-ignore-notice', 'true' ) );
		?>		
		<style>		
		.wp-core-ui .notice.ast-dismissable-notice{
			position: relative;
			padding-right: 38px;
			border-left-color: #005B9A;
		}
		.wp-core-ui .notice.ast-dismissable-notice h3{
			margin-bottom: 5px;
		} 
		.wp-core-ui .notice.ast-dismissable-notice a.notice-dismiss{
			padding: 9px;
			text-decoration: none;
		} 
		.wp-core-ui .button-primary.ast_notice_btn {
			background: #005B9A;
			color: #fff;
			border-color: #005B9A;
			text-transform: uppercase;
			padding: 0 11px;
			font-size: 12px;
			height: 30px;
			line-height: 28px;
			margin: 5px 0 15px;
		}
		</style>
		<div class="notice updated notice-success ast-dismissable-notice">			
			<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>			
						
			<p><a target="blank" href="https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/">AST PRO</a> is celebrating 1 Year!  - Advanced Shipment Tracking Pro allows you to streamline & automate your fulfillment workflow, save time on your daily tasks and keep your customers happy and informed on their shipped orders.</p>
			<p>Use code <strong>ASTPRO20</strong> to redeem your discount (valid by March 31th 2022).</p>
			
			<a class="button-primary ast_notice_btn" target="blank" href="https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/">Upgrade Now</a>
			<a class="button-primary ast_notice_btn" href="<?php esc_html_e( $dismissable_url ); ?>">Dismiss</a>				
		</div>	
		<?php 				
	}	
	
	/*
	* Dismiss admin notice for trackship
	*/
	public function ast_pro_1_year_admin_notice_ignore() {
		if ( isset( $_GET['ast-pro-1-year-ignore-notice'] ) ) {
			update_option( 'ast_pro_1_year_admin_notice_ignore', 'true' );
		}
	}
	
	/*
	* Display admin notice on plugin install or update
	*/
	public function ast_pro_admin_notice() { 		
		
		if ( class_exists( 'ast_pro' ) ) {
			return;
		}
		
		if ( get_option('ast_pro_1_3_4_admin_notice_ignore') ) {
			return;
		}	
		
		$dismissable_url = esc_url(  add_query_arg( 'ast-pro-1-3-4-ignore-notice', 'true' ) );
		?>		
		<style>		
		.wp-core-ui .notice.ast-dismissable-notice{
			position: relative;
			padding-right: 38px;
			border-left-color: #005B9A;
		}
		.wp-core-ui .notice.ast-dismissable-notice h3{
			margin-bottom: 5px;
		} 
		.wp-core-ui .notice.ast-dismissable-notice a.notice-dismiss{
			padding: 9px;
			text-decoration: none;
		} 
		.wp-core-ui .button-primary.ast_notice_btn {
			background: #005B9A;
			color: #fff;
			border-color: #005B9A;
			text-transform: uppercase;
			padding: 0 11px;
			font-size: 12px;
			height: 30px;
			line-height: 28px;
			margin: 5px 0 15px;
		}
		</style>
		<?php
		if ( is_plugin_active( 'woocommerce-product-vendors/woocommerce-product-vendors.php' ) ) {
		?>
			<div class="notice updated notice-success ast-dismissable-notice">			
				<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>			
				<h3>AST Fulfillment manager!</h3>
				<p>We noticed that you are using the WooCommerce Product Vendors plugin. The <a target="blank" href="https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/">AST Fulfillment manager</a> comes with built-in integration with the Product Vendors extension. Check out <a target="blank" href="https://www.zorem.com/whats-new-in-ast-fulfillment-manager">more info</a></br>
				Get a 20% discount to upgrade to the Advanced Shipment Tracking Pro! Use code <a target="blank" href="https://www.zorem.com/whats-new-in-ast-fulfillment-manager"><strong>ASTPRO20</strong></a> to redeem your discount (valid by Oct 3oth).
				</p>			
				<a class="button-primary ast_notice_btn" target="blank" href="https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/">Go Pro</a>
				<a class="button-primary ast_notice_btn" href="<?php esc_html_e( $dismissable_url ); ?>">No Thanks</a>				
			</div>	
		<?php } else if ( is_plugin_active( 'woocommerce-shipstation-integration/woocommerce-shipstation.php' ) ) { ?>	
			<div class="notice updated notice-success ast-dismissable-notice">			
				<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>			
				<h3>AST Fulfillment manager!</h3>
				<p>We noticed that you are using ShipStation to ship your orders. The <a target="blank" href="https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/">AST Fulfillment manager </a> comes with built-in integration with the ShipStation extension and helps you avoid repetitively copy & paste tracking info from the order notes to the shipment tracking section.</br>
				Get a 20% discount to upgrade to the Advanced Shipment Tracking Pro! Use code <a target="blank" href="https://www.zorem.com/whats-new-in-ast-fulfillment-manager"><strong>ASTPRO20</strong></a> to redeem your discount (valid by Oct 3oth).
				</p>			
				<a class="button-primary ast_notice_btn" target="blank" href="https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/">Go Pro</a>
				<a class="button-primary ast_notice_btn" href="<?php esc_html_e( $dismissable_url ); ?>">No Thanks</a>				
			</div>
		<?php } else if ( is_plugin_active( 'woocommerce-services/woocommerce-services.php' ) ) { ?>
			<div class="notice updated notice-success ast-dismissable-notice">			
				<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>			
				<h3>AST Fulfillment manager!</h3>
				<p>We noticed that you are using WooCommerce Shipping to ship your orders. The <a target="blank" href="https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/">AST Fulfillment manager</a> comes with built-in integration with the WooCommerce Shipping & Tax extension and helps you avoid repetitively copy & paste tracking info into the shipment tracking section after generating the labels.</br>
				Get a 20% discount to upgrade to the Advanced Shipment Tracking Pro! Use code <a target="blank" href="https://www.zorem.com/whats-new-in-ast-fulfillment-manager"><strong>ASTPRO20</strong></a> to redeem your discount (valid by Oct 3oth).
				</p>			
				<a class="button-primary ast_notice_btn" target="blank" href="https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/">Go Pro</a>
				<a class="button-primary ast_notice_btn" href="<?php esc_html_e( $dismissable_url ); ?>">No Thanks</a>				
			</div>	
		<?php } else { ?>
			<div class="notice updated notice-success ast-dismissable-notice">			
				<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>			
				<h3>AST Fulfillment manager!</h3>
				<p>AST Fulfillment Manager comes with advanced features that will streamline & automate your fulfillment workflow and help keep your customers happy and informed!</br>
				Get a 20% discount when you upgrade to the AST Fulfillment Manager! Use code <a target="blank" href="https://www.zorem.com/whats-new-in-ast-fulfillment-manager"><strong>ASTPRO20</strong></a> to redeem your discount (valid by Oct 3oth).
				</p>			
				<a class="button-primary ast_notice_btn" target="blank" href="https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/">Go Pro</a>
				<a class="button-primary ast_notice_btn" href="<?php esc_html_e( $dismissable_url ); ?>">No Thanks</a>				
			</div>	
		<?php 
		}		
	}	
	
	/*
	* Dismiss admin notice for trackship
	*/
	public function ast_pro_admin_notice_ignore() {
		if ( isset( $_GET['ast-pro-1-3-4-ignore-notice'] ) ) {
			update_option( 'ast_pro_1_3_4_admin_notice_ignore', 'true' );
		}
	}	
	
	/*
	* Display admin notice on plugin install or update
	*/
	public function ast_db_update_notice() { 		
		
		if ( get_option('ast_db_update_notice_updated_ignore') ) {
			return;
		}	
		
		$dismissable_url = esc_url(  add_query_arg( 'ast-db-update-notice-updated-ignore', 'true' ) );
		$update_providers_url = esc_url( admin_url( '/admin.php?page=woocommerce-advanced-shipment-tracking&tab=shipping-providers&open=synch_providers' ) );
		?>		
		<style>		
		.wp-core-ui .notice.ast-pro-dismissable-notice a.notice-dismiss{
			padding: 9px;
			text-decoration: none;
		}
		.wp-core-ui .button-primary.ast_notice_btn {
			background: #005B9A;
			color: #fff;
			border-color: #005B9A;
			padding: 0 11px;
			font-size: 12px;
			height: 30px;
			line-height: 28px;
			margin: 5px 0 15px;
		}
		.ast-notice{
			background: #fff;
    		border: 1px solid #e0e0e0;
    		margin: 0 0 25px;
    		padding: 1px 12px;
			box-shadow: none;
		}
		</style>	
		<div class="ast-notice notice notice-success is-dismissible ast-pro-dismissable-notice">			
			<a href="<?php esc_html_e( $dismissable_url ); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>			
			<p>Shipping providers update is available, please click on update providers to update the shipping providers list.</p>
			<a class="button-primary ast_notice_btn" href="<?php esc_html_e( $update_providers_url ); ?>">Update Providers</a>			
		</div>
	<?php 		
	}	
	
	/*
	* Dismiss admin notice for trackship
	*/
	public function ast_db_update_notice_ignore() {
		if ( isset( $_GET['ast-db-update-notice-updated-ignore'] ) ) {
			update_option( 'ast_db_update_notice_updated_ignore', 'true' );
		}
		if ( isset( $_GET['open'] ) && 'synch_providers' == $_GET['open'] ) {
			update_option( 'ast_db_update_notice_updated_ignore', 'true' );
		}
	}	

	/*
	* Display admin notice on if Store is connected to TrackShip and TrackShip For WooCommerce plugin is not activate
	*/
	public function ast_install_ts4wc() {
		?>
		<div class="notice notice-error">			
			<p><strong>Please note:</strong> TrackShip's functionality was moved and now you need to also install <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=search&s=TrackShip+For+WooCommerce&plugin-search-input=Search+Plugins' ) ); ?>" target="blank">TrackShip for WooCommerce</a> plugin. To avoid any interruptions with the service and keep tracking orders with TrackShip, please install <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=search&s=TrackShip+For+WooCommerce&plugin-search-input=Search+Plugins' ) ); ?>" target="blank">TrackShip for WooCommerce</a> before updating to this version of the Advanced Shipment Tracking plugin.</p>	
		</div>		
		<?php 		
	}	
}

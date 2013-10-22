<?php
/**
 * Plugin Name: WooCommerce-Gateway-Tranzila
 * Plugin URI: http://superplug.in
 * Description: Provides a Tranzila Payment Gateway for Woocommerce.
 * Version: 1.3.3
 * Author: SuperPlugn Team
 * Author URI: http://superplug.in/team
 * Requires at least: 3.5
 * Tested up to: 3.6.1
 *
 * Text Domain: wc_gateway_tranzila
 * Domain Path: /languages/
 *
 * @author SuperPlugn Team
 */


if ( !function_exists('sp_tranzila_gateway_class') ){

	/**
	 * Includes wc-gateway-tranzila class.
	 * Includes autoupdate class and add filters and actions for autoupdating
	 *
	 * @return void
	 */
	function sp_tranzila_gateway_class(){

		include_once ( plugin_dir_path( __FILE__ ) . 'class-wc-gateway-tranzila.php' ) ;

	}
}
add_action( 'plugins_loaded', 'sp_tranzila_gateway_class' );


if ( !function_exists('sp_add_tranzila_gateway_class') ) {

	//Add the gateway
	function sp_add_tranzila_gateway_class( $methods ){

		$methods[] = 'WC_Gateway_Tranzila';

		return $methods;

	}
	add_filter( 'woocommerce_payment_gateways', 'sp_add_tranzila_gateway_class' );

}

//Plugin text domain
if ( !function_exists('sp_add_w2t_text_domain') ) {
	function sp_add_w2t_text_domain(){
		
		load_plugin_textdomain("wc_gateway_tranzila", false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
	}
	
	add_action( 'init' , 'sp_add_w2t_text_domain' );
}


//Autoupdate
include ( plugin_dir_path( __FILE__ ) . 'software-autoupdate.php' ) ;
new Sp_Software_Autoupdate( __FILE__ , 'wc-gateway-tranzila');
//Software license
include_once ( plugin_dir_path( __FILE__ ) . 'software-license.php' ) ;
$GLOBALS['wcgt_software_license'] = new Sp_Software_License( __FILE__ , 'wc-gateway-tranzila') ;



//just for testing: TODO delete this!!!
/*add_filter( 'woocommerce_billing_fields', 'sp_npr_filter', 10, 1 );
function sp_npr_filter( $address_fields  ){
	
	foreach($address_fields as $field => $value){
		unset($address_fields[$field]);
	}
	return $address_fields;
	
}*/

?>
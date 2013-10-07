<?php
/**
 * Review Order Shortcode
 *
 * The review-order page. Used by PayPal Express module to display the order review page.
 *
 * @package		WooCommerce
 * @category	Shortcode
 * @author		Daniel Espinoza
 */

function get_woocommerce_review_order( $atts ) {
	global $woocommerce;
	return $woocommerce->shortcode_wrapper('woocommerce_review_order', $atts);
}

/**
 * Outputs the pay page - payment gateways can hook in here to show payment forms etc
 **/
function woocommerce_review_order() {
	global $woocommerce;

	$woocommerce->nocache();
	$woocommerce->show_messages();

	echo "
	<script>

	jQuery(document).ready(function($) {
		$('#paypalexpress_shipping_method').live('change', function(){
			$('body').trigger('paypalexpress_update_checkout');
		});

		var updateTimer;
		var xhr;


		function paypalexpress_update_checkout() {

			if (xhr) xhr.abort();

			var method 			= $('#paypalexpress_shipping_method').val();

			$('#order_methods, #paypalexpress_order_review').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6}});

			var data = {
				action: 			'woocommerce_paypalexpress_update_shipping_method',
				security: 			woocommerce_params.update_shipping_method_nonce,
				shipping_method: 	method
			};

			xhr = $.ajax({
				type: 		'POST',
				url: 		woocommerce_params.ajax_url,
				data: 		data,
				success: 	function( response ) {
					$('#paypalexpress_order_review').after(response).remove();
				}
			});

		}


		$('body').bind('paypalexpress_update_checkout', function() {
			clearTimeout(updateTimer);
			paypalexpress_update_checkout();
		});

	});
	</script>
	";

	echo '<form method="POST" action="' . add_query_arg( 'pp_action', 'payaction', add_query_arg( 'wc-api', 'WC_Gateway_PayPal_Express', home_url( '/' ) ) ) . '">';

	$template = plugin_dir_path( __FILE__ ) . '/template/review-order.php';

	load_template( $template, false );

	do_action( 'woocommerce_ppe_checkout_order_review' );

	echo '<p><a class="button cancel" href="' . $woocommerce->cart->get_cart_url() . '">'.__('Cancel order', 'woothemes').'</a> ';

	echo '<input type="submit" class="button" value="' . __( 'Place Order','woothemes') . '" /></p>';

	echo '</form>';
}

add_shortcode( 'woocommerce_review_order', 'get_woocommerce_review_order' );
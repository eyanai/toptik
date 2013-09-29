<?php


add_action('toptik_get',function(){
	global $woocommerce;global $order;
	
	if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) :

		$mach=get_option('ye_plugin_options');
	 	$mailseller=$mach['ye_paypal_mail'];

		//$recevermail=get_option( 'receiver_email');
		//echo "<b>".$recevermail."</b>";
		//$order = new WC_Order($order_id);
		//$arg=get_paypal_args($order);
		//echo "<pre>yanai ".print_r($arg)."</pre>";
?>
		<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_cart">
		<input type="hidden" name="upload" value="1">
		<input type="hidden" name="currency_code" value="ILS">
		<input type="hidden" name="rm" value="2">
		<input type="hidden" name="return" value="http://localhost/toptik/checkout/order-received/?utm_nooverride=1">
		<input type="hidden" name="cancel_return" value="http://localhost/toptik/testipn">
		<input type="hidden" name="notify_url" value="http://localhost/toptik/testipn">
		<input type="hidden" name="business" value="<?php echo $mailseller;?>">


<?php
		$i=1;
		
		
		
		foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) :
			$paycart=$woocommerce->cart->get_cart();
			$_product = $cart_item['data'];

			// Only display if allowed
			if ( ! apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) || ! $_product->exists() || $cart_item['quantity'] == 0 )
				continue;

			// Get price
			
			$product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();
			?>
			<input type="hidden" name="item_name_<?php echo $i?>" value="<?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?>">
			<input type="hidden" name="amount_<?php echo $i?>" value="<?php echo $product_price;?>">
			<input type="hidden" name="shipping_<?php echo $i?>" value="1.75">
			<input type="hidden" name="quantity_<?php echo $i?>" value="<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', $cart_item['quantity'], $cart_item, $cart_item_key ); ?>">
			
		<?php // echo $woocommerce->cart->get_item_data( $cart_item ); ?>
		<?php endforeach; ?>
		<input type="hidden" name="amount_2" value="2.00">
		<input type="hidden" name="shipping_2" value="2.50">
		<input type="submit" value=" " class="submit_btb_paypal" id="paypal_btn">
		</form>
	<?php else : ?>
		<?php _e( 'No products in the cart.', 'woocommerce' ); ?>
	<?php endif; ?>

		
<?php	
});

/*

<input type="hidden" name="item_name_2" value="Item Name 2">

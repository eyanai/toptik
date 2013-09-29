<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

$woocommerce->show_messages();
?>
<?php do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post" id="cartFrom">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table cart">
	<thead class="cartThead">
		<tr>
			<th class="product-remove" id="topremove">הסר </th>
			<th class="product-thumbnail up"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-name up"></th>
			<th class="product-price up"><?php _e( 'Price', 'woocommerce' ); ?></th>
			<th class="product-quantity up"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="product-subtotal up"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->exists() && $values['quantity'] > 0 ) {
					?>
					<tr class = "<?php echo esc_attr( apply_filters('woocommerce_cart_table_item_class', 'cart_table_item', $values, $cart_item_key ) ); ?> cartToptik">
						<!-- Remove from cart link -->
						<td class="product-remove">
							<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
							?>
						</td>

						<!-- The thumbnail -->
						<td class="product-thumbnail">
							<?php
								$thumbnail = apply_filters( 'woocommerce_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );

								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo $thumbnail;
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
							?>
						</td>

						<!-- Product Name -->
						<td class="product-name">
							<?php
								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo apply_filters( 'woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
								else
									//printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );
									// echo "<pre>".print_r($_product,1)."</pre>";
									 echo "<span class='cartDes'>". $_product->post->post_excerpt."</span>";
									echo "<span class=\"cartMetaS\">";
									$calories = woocommerce_get_product_terms( $_product->id, 'pa_סידרה', 'names' );
									if($calories){
									echo "סידרה: ";
									foreach ($calories as $cat)
										  {
										 echo "<span class='red'>".$cat."</span>";
										  }	
									
									}
									
									if ( $_product->is_type( array( 'simple', 'variable' ) ) && get_option( 'woocommerce_enable_sku' ) == 'yes' && $_product->get_sku() ) : ?>
		<?php _e( 'SKU:', 'woocommerce' ); ?> <?php echo $_product->get_sku(); ?>
	<?php endif; echo "</span>";

			// Meta data
								echo $woocommerce->cart->get_item_data( $values);
							                   				// Backorder notification
                  if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                   					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
							?>
						</td>

						<!-- Product price -->
						<td class="product-price">
							<?php
								$product_price = get_option('woocommerce_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

								echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key );
							?>
						</td>

						<!-- Quantity inputs -->
						<td class="product-quantity">
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {

									$step	= apply_filters( 'woocommerce_quantity_input_step', '1', $_product );
									$min 	= apply_filters( 'woocommerce_quantity_input_min', '', $_product );
									$max 	= apply_filters( 'woocommerce_quantity_input_max', $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(), $_product );

									$product_quantity = sprintf( '<div class="quantity"><input type="number" name="cart[%s][qty]" step="%s" min="%s" max="%s" value="%s" size="4" title="' . _x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) . '" class="input-text qty text" maxlength="12" /></div>', $cart_item_key, $step, $min, $max, esc_attr( $values['quantity'] ) );
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
							?>
						</td>

						<!-- Product subtotal -->
						<td class="product-subtotal">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', $woocommerce->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
							?>
						</td>
					</tr>
					<?php
				}
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr class="noborder">
			<td colspan="6" class="actions">
					
				<?php if ( $woocommerce->cart->coupons_enabled() ) { ?>
					<div class="coupon">

						<label for="coupon_code"><?php //_e( 'Coupon', 'woocommerce' ); ?>:</label> <input name="coupon_code" class="input-text" id="coupon_code" value="" /> <input type="submit" class="button" id="cuponSub" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />

						<?php do_action('woocommerce_cart_coupon'); ?>

					</div>
				<?php } ?>

				<input type="submit" class="button" id="update_cart" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" /> 
				<input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e( 'Proceed to Checkout &rarr;', 'woocommerce' ); ?>" />

				<?php do_action('woocommerce_proceed_to_checkout'); ?>

				<?php $woocommerce->nonce_field('cart') ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>




	
	<?php 
		$pay=new WC_Gateway_Paypal;
		echo print_r( get_class_methods ('WC_Gateway_Paypal'));
		//$pay->toptik_get();
		//do_action('toptik_get');
		//toptik_get();
	?>



<div class="cart-collaterals">

	<?php do_action('woocommerce_cart_collaterals'); ?>

	<?php woocommerce_cart_totals(); ?>
	<?php /*?><div class="popupCart">      ///////////////in cross-sels-disdplay page
		<a href="#" class="members"></a>
		<a href="#" class="lock"></a>
	</div><?php */?>
	<?php //woocommerce_shipping_calculator(); ?>
	<?php woocommerce_cross_sell_display();?>
</div>
	<div class="premitionPop">
		<span class="cPop">סגור</span>
		<div class="contectPop">
		<?php 
			$page = get_page_by_title( 'תנאים והגבלות' );
			$content = apply_filters('the_content', $page->post_content); 
			echo $content;
		?>
		</div>
	</div>
	<div class="loginpop">
		<span class="cPop">סגור</span>
		<div class="loginPop">
			<?php woocommerce_login_form();?>
		</div>
	</div>
<?php get_footer('reg');?>
<?php do_action( 'woocommerce_after_cart' ); ?>

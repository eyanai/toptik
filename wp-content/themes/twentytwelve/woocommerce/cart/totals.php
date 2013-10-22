<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce,$_toptikcredit,$current_user;

$available_methods = $woocommerce->shipping->get_available_shipping_methods();
?>
<div class="cart_totals <?php if ( $woocommerce->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?> toptik">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>
	
	<div class="shipping">
						<span><?php //_e( 'Shipping', 'woocommerce' ); ?></span>
						<span><?php woocommerce_get_template( 'cart/shipping-methods.php', array( 'available_methods' => $available_methods ) ); ?></span>
	</div>
	

	<?php if ( ! $woocommerce->shipping->enabled || $available_methods || ! $woocommerce->customer->get_shipping_country() || ! $woocommerce->customer->has_calculated_shipping() ) : ?>

		<h2 class="cartTotalH2"><?php _e( 'Cart Totals', 'woocommerce' ); ?></h2>

		<div class="subCartTable clear">
			
				<div class="cart-subtotal">
					<span class="transSub"><strong><?php _e( 'Cart Subtotal', 'woocommerce' ); ?></strong></span>
					<span class="subGeray" id="cartSubToal" data-subtotls='<?php echo $woocommerce->cart->subtotal;?>'><strong><?php echo $woocommerce->cart->get_cart_subtotal(); ?></strong></span>
				</div>
				
				<div class="shipVal">
						<span class="transSub">משלוח:</span>
						<span class="subGeray toptik" id="shipnam"></span>
				</div>
				
				<div class="shipVal" id="beforDisc">
						<span class="transSub">סה"כ לפני הנחה:</span>
						<span class="subGeray toptik" id="sumValCost"></span>
				</div>
				<?php if ( $woocommerce->cart->get_discounts_before_tax() ) : ?>

					<div class="discount">
	<!--Cart Discount-->	<span class="transSub"><?php _e( 'קרדיט:', 'woocommerce' ); ?> <a href="<?php echo add_query_arg( 'remove_discounts', '1', $woocommerce->cart->get_cart_url() ) ?>"><?php _e( '[Remove]', 'woocommerce' ); ?></a></span>
						<span id='credirdiscout' data-credit='<?php echo $woocommerce->cart->discount_cart; ?>' class="subGeray toptik"> <?php echo $woocommerce->cart->get_discounts_before_tax();?> </span>
						
					</div>
					

				<?php endif; ?>
				<?php /*
					$creditDis=get_user_meta($current_user->ID,'credit_want',true);
				if(isset($creditDis) && !empty($creditDis)): ?>
				<div class="discount">
						<span class="transSub"><?php _e( 'הנחת קרדיטים', 'woocommerce' ); ?> <a href="<?php echo add_query_arg( 'remove_credit', '1', $woocommerce->cart->get_cart_url() ) ?>" id="removeCerdit"><?php _e( '[Remove]', 'woocommerce' ); ?></a></span>
						<span class="subGeray"><?php echo get_user_meta($current_user->ID ,'credit_want',true);?></span>
					</div>
				<?php endif; */?>

				<?php if ( $woocommerce->cart->needs_shipping() && $woocommerce->cart->show_shipping() && ( $available_methods || get_option( 'woocommerce_enable_shipping_calc' ) == 'yes' ) ) : ?>

					<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

					<?php /*?><tr class="shipping">
						<th><?php _e( 'Shipping', 'woocommerce' ); ?></th>
						<td><?php woocommerce_get_template( 'cart/shipping-methods.php', array( 'available_methods' => $available_methods ) ); ?></td>
					</tr>
<?php */?>
					<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

				<?php endif ?>
					
				<?php foreach ( $woocommerce->cart->get_fees() as $fee ) : ?>

					<div class="fee fee-<?php echo $fee->id ?>">
						<span class="transSub"><?php echo $fee->name ?></span>
						<span class="subGeray"><?php
							if ( $woocommerce->cart->tax_display_cart == 'excl' )
								echo  woocommerce_price( $fee->amount );
							else
								echo woocommerce_price( $fee->amount + $fee->tax );
						?></span>
					</div>

				<?php endforeach; ?>

				<?php
					// Show the tax row if showing prices exclusive of tax only
					if ( $woocommerce->cart->tax_display_cart == 'excl' ) {
						foreach ( $woocommerce->cart->get_tax_totals() as $code => $tax ) {
							echo '<tr class="tax-rate tax-rate-' . $code . '">
								<th>' . $tax->label . '</th>
								<td>' . $tax->formatted_amount . '</td>
							</tr>';
						}
					}
				?>

				<?php if ( $woocommerce->cart->get_discounts_after_tax() ) : ?>

					<div class="discount">
						<span class="transSub"><?php _e( 'Order Discount', 'woocommerce' ); ?> <a href="<?php echo add_query_arg( 'remove_discounts', '2', $woocommerce->cart->get_cart_url() ) ?>"><?php _e( '[Remove]', 'woocommerce' ); ?></a></span>
						<span class="subGeray" id="saveCupon" data-cupon='	<?php echo ($woocommerce->cart->discount_total)?>'><?php echo $woocommerce->cart->get_discounts_after_tax(); ?></span>
					
					</div>

				<?php endif; ?>

				<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

				<div class="total">
					<span class="transSub"><strong><?php _e( 'Order Total', 'woocommerce' ); ?></strong></span>
					<span class="subGeray">
						<strong><?php  echo $woocommerce->cart->get_total(); ?></strong>
						<?php
							// If prices are tax inclusive, show taxes here
							if (  $woocommerce->cart->tax_display_cart == 'incl' ) {
								$tax_string_array = array();

								foreach ( $woocommerce->cart->get_tax_totals() as $code => $tax ) {
									$tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
								}

								if ( ! empty( $tax_string_array ) ) {
									echo '<small class="includes_tax">' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) ) . '</small>';
								}
							}
						?>
					</span>
				</div>

				<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
				
				
				

		</div><!--subCartTable-->
		<div class="sammery">
			<div class="sammSum">הנחה בקנייה זו: <span class="saveing"></span></div>
			<div class="sammAll">סה"כ לתשלום: <span class="allSum"></span></div>
			
			<?php do_action('woocommerce_proceed_to_checkout'); ?>
				<a href="<?php 
				global $current_user;
					if(is_user_logged_in()){
						echo esc_url(get_permalink( woocommerce_get_page_id( 'checkout') ) );
					}else{
					//	echo esc_url( get_permalink( get_page_by_title( 'לתשלום' ) ) );
						echo esc_url( get_permalink( get_page_by_title( 'לקוח חדש' ) ) );
					}
				?>" class="chackOutCart"></a>
		</div>
		<?php if ( $woocommerce->cart->get_cart_tax() ) : ?>

			<p><small><?php

				$estimated_text = ( $woocommerce->customer->is_customer_outside_base() && ! $woocommerce->customer->has_calculated_shipping() ) ? sprintf( ' ' . __( ' (taxes estimated for %s)', 'woocommerce' ), $woocommerce->countries->estimated_for_prefix() . __( $woocommerce->countries->countries[ $woocommerce->countries->get_base_country() ], 'woocommerce' ) ) : '';

				printf( __( 'Note: Shipping and taxes are estimated%s and will be updated during checkout based on your billing and shipping information.', 'woocommerce' ), $estimated_text );

			?></small></p>

		<?php endif; ?>

	<?php elseif( $woocommerce->cart->needs_shipping() ) : ?>

		<?php if ( ! $woocommerce->customer->get_shipping_state() || ! $woocommerce->customer->get_shipping_postcode() ) : ?>

			<div class="woocommerce-info">

				<p><?php _e( 'No shipping methods were found; please recalculate your shipping and enter your state/county and zip/postcode to ensure there are no other available methods for your location.', 'woocommerce' ); ?></p>
				
			</div>

		<?php else : ?>

			<?php

				$customer_location = $woocommerce->countries->countries[ $woocommerce->customer->get_shipping_country() ];

				echo apply_filters( 'woocommerce_cart_no_shipping_available_html',
					'<div class="woocommerce-error"><p>' .
					sprintf( __( 'Sorry, it seems that there are no available shipping methods for your location (%s).', 'woocommerce' ) . ' ' . __( 'If you require assistance or wish to make alternate arrangements please contact us.', 'woocommerce' ), $customer_location ) .
					'</p></div>'
				);

			?>

		<?php endif; ?>

	<?php endif; ?>
	
	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
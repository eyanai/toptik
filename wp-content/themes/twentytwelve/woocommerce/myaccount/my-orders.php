<?php
/**
 * My Orders
 *
 * Shows recent orders on the account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

$customer_orders = get_posts( array(
    'numberposts' => $order_count,
    'meta_key'    => '_customer_user',
    'meta_value'  => get_current_user_id(),
    'post_type'   => 'shop_order',
    'post_status' => 'publish'
) );

if ( $customer_orders ) : ?>

	<h2 class="normaldevid"><span><?php echo apply_filters( 'woocommerce_my_account_my_orders_title', __( 'Recent Orders', 'woocommerce' ) ); ?></span></h2>

	<section class="my_account_orders_toptik">

				
				
	
		<?php
		$caunterorder=1;
			foreach ( $customer_orders as $customer_order ) {
				?>
			<div class="singel_order" id='<?php echo $caunterorder;?>_orderTop'>
			<h2 class="normaldevid ordernam"><span>הזמנה מס- <?php echo  $caunterorder;?></span></h2>
			<?php	
				$order = new WC_Order();

				$order->populate( $customer_order );

				$status     = get_term_by( 'slug', $order->status, 'shop_order_status' );
				$item_count = $order->get_item_count();

				?>	
						<span class="nobr1">קוד הזמנה :</span>	<?php echo $order->get_order_number(); ?>
						<br>

						<span class="nobr2">תאריך הזמנה :</span>
						<time datetime="<?php echo date('Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time>
						<br>
						
						<span class="nobr3">סטטוס הזמנה :</span>
						<?php echo ucfirst( __( $status->name, 'woocommerce' ) ); ?><br>
						
						<span class="nobr4">סה"כ הזמנה :</span>	
						<?php  echo sprintf( $order->get_formatted_order_total(), $item_count ); ?>
						
						<?php
							$actions = array();

							if ( in_array( $order->status, apply_filters( 'woocommerce_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order ) ) )
								$actions['pay'] = array(
									'url'  => $order->get_checkout_payment_url(),
									'name' => __( 'Pay', 'woocommerce' )
								);

							if ( in_array( $order->status, apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) )
								$actions['cancel'] = array(
									'url'  => $order->get_cancel_order_url(),
									'name' => __( 'Cancel', 'woocommerce' )
								);

							$actions['view'] = array(
								'url'  => add_query_arg( 'order', $order->id, get_permalink( woocommerce_get_page_id( 'view_order' ) ) ),
								'name' => __( 'View', 'woocommerce' )
							);

							$actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );
							?>
						<br>

						<?php	
							foreach( $actions as $key => $action ) {
								//echo '<a href="' . esc_url( $action['url'] ) . '" class="toptikviwebtm button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
							}
							?>
							<a href="<?php echo esc_url( add_query_arg('order', $order->id, get_permalink( woocommerce_get_page_id( 'view_order' ) ) ) ); ?>" class="toptikviwebtm" data-orderid='<?php echo $order->id; ?>' data-singel='<?php echo $caunterorder;?>'>פרטי הזמנה</a>
					
						<?php // woocommerce_get_template('myaccount/my-singelorder.php');?>
					
					<hr class="hrdabel">
				
	<div class="orderDetailSingel cf" id="<?php echo $caunterorder;?>_singelDetail">			
		<div class="singelorderR">
			<h2>כתובת לשליחת קבלה</h2>
			<?php 
			//echo $order->id."<br>";
			//echo $order->status."<br>";
			//echo $order->order_date."<br>";
			echo $order->billing_first_name." ";
			echo $order->billing_last_name."<br>";
			echo $order->billing_email."<br>";
			echo $order->billing_phone."<br>";
			echo $order->order_total."<br>";
			echo $order->billing_address_1."<br>";
			echo $order->billing_city."<br>";
			echo $order->billing_postcode."<br>";?>
		</div>
		<div class="singelorderR">
			<h2>כתובת למשלוח</h2>
			<?php
			echo $order->shipping_first_name." ";
			echo $order->shipping_last_name."<br>";
			echo $order->shipping_email."<br>";
			echo $order->shipping_phone."<br>";
			echo $order->shipping_address_1."<br>";
			echo $order->shipping_city."<br>";
			echo $order->shipping_postcode."<br>";?>
		</div>
		<div class="singelorderR">
			<h2>אמצעי תשלום</h2>
			<?php
			echo $order->payment_method."<br>";
			 ?>
		</div>
		<div class="singelorderR">
			<h2>צורת משלוח</h2>
			<?php
			echo $order->shipping_method."<br>";
			 ?>
		</div>
		
		<div class="castoumDetail cf"><!-------------------order detil---->
			<?php 
			$order = new WC_Order($order->id);
			
			
			?>
			<table class="personal" >
				<thead>
					<tr>
						<th class="product-name up personal"><?php _e( 'Product', 'woocommerce' ); ?></th>
						<th class="product-castoum up personal"></th>
						<th class="product-quantity up personal"><?php _e( 'Price', 'woocommerce' ); ?></th>
						
						<th class="product-quantity up personal"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
						
						<th class="product-subtotal up personal"><?php _e( 'Total', 'woocommerce' ); ?></th>
						
					</tr>
				</thead>
				
				<tbody>
					<?php
					if (sizeof($order->get_items())>0) {
			
						foreach($order->get_items() as $item) {
			
							$_product = get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );
			
							echo '
								<tr class = "' . esc_attr( apply_filters( 'woocommerce_order_table_item_class', 'order_table_item', $item, $order ) ) . '">';
								?>	
								<td class="product-thumbnail personal">
										<?php
											$thumbnail = apply_filters( 'woocommerce_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );
			
											if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
												echo $thumbnail;
											else
												printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
										?>
								</td>
								<td class="product-personal-name">	
								<?php	
								echo 	apply_filters( 'woocommerce_order_table_product_title', '<a href="' . get_permalink( $item['product_id'] ) . '">' . $item['name'] . '</a>', $item );
								?>
								</td>	
								<td class="product-price presonal">
										<?php
											$product_price = get_option('woocommerce_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();
			
											echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key );
										?>
									</td>		
								<td class="product-price presonal">
								<?php
								echo  apply_filters( 'woocommerce_order_table_item_quantity', '<strong class="product-quantity">' . $item['qty'] . '</strong>', $item );
			?>
								</td>	
								
							<?php			
							$item_meta = new WC_Order_Item_Meta( $item['item_meta'] );
							$item_meta->display();
			
							if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {
			
								$download_file_urls = $order->get_downloadable_file_urls( $item['product_id'], $item['variation_id'], $item );
			
								$i     = 0;
								$links = array();
			
								foreach ( $download_file_urls as $file_url => $download_file_url ) {
			
									$filename = woocommerce_get_filename_from_url( $file_url );
			
									$links[] = '<small><a href="' . $download_file_url . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_file_urls ) > 1 ? ' ' . ( $i + 1 ) . ': ' : ': ' ) ) . $filename . '</a></small>';
			
									$i++;
								}
			
								echo implode( '<br/>', $links );
							}
			
							echo '</td><td class="product-total">' . $order->get_formatted_line_subtotal( $item ) . '</td></tr>';
			
							// Show any purchase notes
							if ($order->status=='completed' || $order->status=='processing') {
								if ($purchase_note = get_post_meta( $_product->id, '_purchase_note', true))
									echo '<tr class="product-purchase-note"><td colspan="3">' . apply_filters('the_content', $purchase_note) . '</td></tr>';
							}
			
						}
					}
			
					do_action( 'woocommerce_order_items_table', $order );
					?>
				</tbody>
				</table>
				<div class="personalTabelFooter" >
						<div class="singel_table">
				
				<?php
					if ( $totals = $order->get_order_item_totals() ) foreach ( $totals as $total ) :
						?>
							<span scope="row" class="f1"><?php echo $total['label']; ?></span>
							<span class="f2"><?php echo $total['value']; ?></span>
						
						<?php
					endforeach;
				?>
				
					<span  class="f1">קרדיט</span>
					<span class="f2"><?php the_field('credit_order', $order->id); ?> &#8362;</span>
				</div>
				</div>
				<div class="toptikPersonalOrder">
					<div class="discountToptik">הנחה בקניה זו : <span class="topdiscount"></span></div>
					<div class="sumToptik">סה"כ לתשלום : <span class="topPsum"></span></div>
				</div>
				<div class="bDivider"></div>
				<div class="printE">
					<a href="#" class="toptikviwebtm toprint print after">הדפס הזמנה</a>
					<a href="#" class="resetPersonalOrder">< חזרה להזמנות שלי </a>
				</div>
</div><!-------------------order detil---->
		
	</div>
</div>
		<?php
				
		$caunterorder++;	}
		?>

	</section>

<?php endif; ?>

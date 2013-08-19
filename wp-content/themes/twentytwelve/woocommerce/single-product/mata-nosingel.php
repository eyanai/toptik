<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>

<div class="product_meta">
	<div class="tags_pro">
	<?php do_action( 'woocommerce_product_meta_start' ); ?>
	<?php
		$size = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
		echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( '<span class="tag"></span>', 'Tags:', $size, 'woocommerce' ) . ' ', '.</span>' );
	?><br>
	</div>
<?php if($product->get_tags()){
	}else{
	echo "</a>";
}?>



	<?php
		$calories = woocommerce_get_product_terms( $product->id, 'pa_סידרה', 'names' );
		if($calories){
		echo "<span class=\"groop\"><h2>סידרה: </h2>";
		foreach ($calories as $cat)
			  {
			 echo $cat;
			  }	
		echo "</span><br>";
		}
		
	?>

<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option( 'woocommerce_enable_sku' ) == 'yes' && $product->get_sku() ) : ?>
		<span itemprop="productID" class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo $product->get_sku(); ?>.</span></span><br>
	<?php endif; ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
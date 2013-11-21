<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

?>



<div class="images topClab">

	<div class="topSingelMata">
		<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>
		<h2>רכישת כרטיס מועדון</h2>
			<?php 
				$calories = woocommerce_get_product_terms( $product->id, 'pa_סידרה', 'names' );
				if($calories){
				echo "<span class=\"groop\"><h2> סידרה: </h2> ";
				foreach ($calories as $cat)
					  {
					 echo " ". $cat;
					  }	
				echo "</span>";
				}
			?>
			<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option( 'woocommerce_enable_sku' ) == 'yes' && $product->get_sku() ) : ?>
		<span itemprop="productID" class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo $product->sku(); ?></span></span><br>
	<?php endif; ?>

	
	</div>	
	<?php
		if ( has_post_thumbnail() ) {

			$image       		= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
			$image_title 		= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_link  		= wp_get_attachment_url( get_post_thumbnail_id() );
			$attachment_count   = count( $product->get_gallery_attachment_ids() );

			echo get_the_post_thumbnail($post_id, array(603,312), array('class' => 'alignleft'));
		}
	?>
	<div class="clubText">
	<?php //do_action( 'woocommerce_product_thumbnails' ); 
		the_content();
	?>
	</div>
</div>

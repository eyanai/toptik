<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

$related = $product->get_related();

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'posts_per_page' 		=> $posts_per_page,
	'orderby' 				=> $orderby,
	'post__in' 				=> $related,
	'post__not_in'			=> array($product->id)
) );

$products = new WP_Query( $args );

$woocommerce_loop['columns'] 	= $columns;

if ( $products->have_posts() ) : ?>

	<div class="related products">

		<span class="titelWcon related"><h2><?php _e( 'Related Products', 'woocommerce' ); ?></h2></span>

		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php woocommerce_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>

<?php endif;

wp_reset_postdata();


 get_sidebar('midel');


if ( has_nav_menu( 'brands' ) ) {?>
					<div class="brandsCon clear">
						<div class="recommendedtop"><h2>המותגים שלנו</h2></div>
						<span class="rIco">&laquo;</span><span class="lIco">&raquo;</span>
				<?php
					wp_nav_menu(array('theme_location'  => 'brands','container'=> 'div','container_class' => 'brandsMenu_wide',));?>
					</div>
<?php				}  
				
				 ?>




	<div class="recommendedtop goto"><h2 class="gotop"></h2></div>